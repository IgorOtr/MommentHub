# MomentHub

## 1. Visão Geral

O **MomentHub** é uma plataforma para centralizar, organizar e disponibilizar fotos e vídeos de eventos realizados em unidades físicas de empresas (inicialmente redes de restaurantes nos EUA: Terra Gaucha, Dom Helio, Eskina Brazilian Restaurant).

Fotógrafos/administradores cadastram links públicos de pastas do Google Drive contendo fotos e vídeos de um evento. O sistema lista esses arquivos num painel administrativo e os exibe numa galeria pública, sem necessidade de login, com identidade visual (logo + cores) de cada cliente.

**Status:** MVP completo e funcional, com várias iterações de design já aplicadas (ver seção 8). Rodando localmente via Docker.

---

## 2. Stack

* **Backend:** Laravel 13 (PHP 8.4)
* **Frontend:** Blade + Alpine.js (via Laravel Breeze) + Tailwind CSS 3
* **Banco de Dados:** MySQL 8 (Docker) — local usa SQLite por padrão, mas o projeto foi migrado para MySQL via Docker Compose
* **Autenticação:** Laravel Breeze (blade stack), sem verificação de e-mail ativa
* **Storage:** disco público do Laravel (`storage/app/public`, symlink em `public/storage`)
* **Google Drive:** API v3 REST via `GOOGLE_DRIVE_API_KEY` (sem OAuth), somente pastas/arquivos públicos

---

## 3. Como rodar o projeto

### Docker (ambiente principal — use este)

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm run build
```

* App: **http://localhost:8070**
* phpMyAdmin: **http://localhost:8075**
* MySQL exposto no host: `127.0.0.1:3322`
* Redis exposto no host: `127.0.0.1:6371`
* Login seed: `test@example.com` / `password` (via `DatabaseSeeder`)

Serviços do `docker-compose.yml`: `app` (PHP-FPM), `nginx`, `mysql`, `phpmyadmin`, `redis`.

### Sempre que editar Blade ou CSS/Tailwind

```bash
docker compose exec app php artisan view:clear   # limpa cache de views compiladas
docker compose exec app npm run build             # recompila Tailwind — necessário sempre que uma
                                                    # classe utilitária NOVA é usada pela primeira vez,
                                                    # senão a classe simplesmente não existe no CSS final
```

### Ambiente de debug local (fora do Docker, usado para screenshots via Preview tool)

Configurado em `.claude/launch.json` como `momenthub-debug` (porta 8071), aponta pro MySQL/Redis do Docker via portas expostas no host. **Gotcha:** o symlink `public/storage` é recriado pelo Docker apontando para `/var/www/html/...` (caminho interno do container), que não existe no host — depois de rodar algo no Docker, se for testar localmente com esse server, rode:

```bash
rm public/storage && php artisan storage:link
```

para recriar o symlink apontando pro caminho real do host.

---

## 4. Estrutura de Dados (schema atual)

```
customers
  id, name, slug (unique), description, primary_color, secondary_color,
  tertiary_color, logo, timestamps

stores
  id, customer_id (fk), name, slug (unique), description, address,
  phone, email, logo, timestamps

events
  id, customer_id (fk), store_id (fk), title, slug (unique), description,
  address, phone, email, logo, cover_image, event_date, timestamps

folders
  id, event_id (fk), name, slug (unique), description,
  google_drive_url, is_public (bool, default true), timestamps

media_files
  id, folder_id (fk), name, mime_type, file_type (enum: image|video),
  google_drive_file_id (unique), google_drive_url, thumbnail_url,
  preview_url, download_url, timestamps
```

Relacionamentos: `Customer hasMany Store/Event`, `Store belongsTo Customer, hasMany Event`, `Event belongsTo Customer+Store, hasMany Folder`, `Folder belongsTo Event, hasMany MediaFile`, `MediaFile belongsTo Folder`. Todas as FKs são `cascadeOnDelete`.

Slugs são gerados automaticamente (trait `App\Models\Concerns\HasSlug` + `App\Services\SlugService`) a partir de `name` (ou `title` no Event) na criação, com sufixo numérico em caso de colisão (`-2`, `-3`...).

---

## 5. Arquitetura

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/CustomerController.php   (index, store, show, update, destroy)
│   │   ├── Admin/StoreController.php      (store, show, update, destroy — sem index próprio)
│   │   ├── Admin/EventController.php      (store, show, update, destroy — auto-herda dados da Store)
│   │   ├── Admin/FolderController.php     (store, show, update, destroy — show sincroniza mídia)
│   │   ├── Public/GalleryController.php   (customer, store, event, folder — sem auth, retorna Blade views)
│   │   └── Api/GalleryController.php      (customer, store, event, folder — sem auth, retorna JSON via Resources)
│   ├── Requests/
│   │   ├── CustomerRequest.php  (valida cores em hex #RRGGBB, incl. tertiary_color)
│   │   ├── StoreRequest.php
│   │   ├── EventRequest.php
│   │   └── FolderRequest.php    (valida que a URL é do Google Drive via GoogleDriveService)
│   ├── Resources/
│   │   ├── CustomerResource.php  (id, name, slug, description, cores, logo_url, stores[])
│   │   ├── StoreResource.php     (dados da unidade + logo_url, events[])
│   │   ├── EventResource.php     (dados do evento + logo_url, folders[])
│   │   ├── FolderResource.php    (dados da pasta, incl. is_public, media_files[])
│   │   └── MediaFileResource.php (dados do arquivo: nome, tipo, urls de thumbnail/preview/download)
│   └── Middleware / Auth/*      (Breeze padrão)
├── Models/
│   ├── Concerns/HasSlug.php
│   └── Customer.php, Store.php, Event.php, Folder.php, MediaFile.php, User.php
└── Services/
    ├── SlugService.php          (geração de slug único)
    ├── GoogleDriveService.php   (extrai folder ID da URL, valida, lista arquivos via API key)
    └── MediaFileService.php     (sincroniza MediaFile a partir do GoogleDriveService::listFiles)
```

### Rotas (`routes/web.php`)

* `/` e `/dashboard` → redirect para `/admin`
* `/admin/*` protegido por `auth` (Breeze). CRUD via **modais**, nunca `create.blade.php`/`edit.blade.php`:
  `GET /admin/customers`, `POST /admin/customers`, `PUT|DELETE /admin/customers/{customer}`, `GET /admin/customer/{customer:slug}` — mesmo padrão para `stores`, `events`, `folders`.
* Galeria pública (sem auth), com **binding aninhado** (`scopeBindings()`) e nome de rota `gallery.*`:
  `GET /{customer:slug}`, `GET /{customer:slug}/{store:slug}`, `.../{event:slug}`, `.../{folder:slug}`
  ⚠️ **Importante:** essas rotas de segmento único ficam **depois** de `require auth.php` no arquivo — se ficarem antes, `{customer:slug}` engole rotas como `/login` como se fosse um slug de cliente (bug já corrigido, não reintroduzir).

### API JSON (`routes/api.php`)

Registrado via `bootstrap/app.php` (`api: __DIR__.'/../routes/api.php'`), prefixo `/api` automático + grupo de middleware `api` padrão do Laravel (sem Sanctum instalado). Espelha a galeria pública (mesmos slugs, `scopeBindings()`), mas sem auth e devolvendo JSON com os filhos diretos já aninhados (Eager loading + `App\Http\Resources\*`):

* `GET /api/{customer:slug}` → cliente + `stores[]`, cada uma já com `events[]`.
* `GET /api/{customer:slug}/{store:slug}` → unidade + `events[]`.
* `GET /api/{customer:slug}/{store:slug}/{event:slug}` → evento + `folders[]` (só as com `is_public = true`, mesma regra da seção 10).
* `GET /api/{customer:slug}/{store:slug}/{event:slug}/{folder:slug}` → pasta + `media_files[]` (sincroniza com o Google Drive via `MediaFileService::syncFolder`, igual à galeria pública; retorna 404 se `is_public = false`, mesma regra da seção 10).
* `bootstrap/app.php` já tinha `shouldRenderJsonWhen(fn ($request) => $request->is('api/*'))`, então erros (404 de slug inexistente etc.) nessas rotas já saem como JSON automaticamente.

### Google Drive

`GoogleDriveService::listFiles()` chama `GET https://www.googleapis.com/drive/v3/files` com a API key de `GOOGLE_DRIVE_API_KEY` (config `services.google_drive.api_key`). Sem API key configurada ou com a Drive API desabilitada no Google Cloud Console, retorna array vazio silenciosamente (grid mostra estado vazio, sem erro pro usuário — warning vai pro log). A pasta do Drive **precisa** estar compartilhada como "Qualquer pessoa com o link" — a API key não usa OAuth, então não enxerga pastas privadas mesmo que a chave seja válida.

`MediaFileService::syncFolder()` roda a cada `GET /admin/folder/{slug}` e `GET .../{folder:slug}` (público), fazendo upsert por `google_drive_file_id` e removendo registros de arquivos que já não estão mais na pasta do Drive.

---

## 6. Design System — Painel Admin

Inspirado no **Metronic (Keenthemes Tailwind Demo1)**, tema claro com dark mode funcional opcional.

* **Sidebar** (`x-sidebar`): 288px expandida / 80px colapsada (`w-72`/`w-20`), toggle de colapsar/expandir persistido em `localStorage` (`mh-sidebar-collapsed`), item ativo com texto/ícone indigo (sem fundo "boxed").
* **Header** (`x-navbar`): barra superior simples com menu mobile + dropdown de usuário no canto direito (avatar com iniciais, clique abre menu com **Editar perfil**, **Modo escuro** com toggle switch e **Sair**). O toggle de dark mode usa `localStorage` (`mh-dark-mode`) + classe `.dark` no `<html>`, com script anti-flash inline no `<head>` do `admin-layout.blade.php`.
* **Page header / toolbar** (`x-page-header`): breadcrumb com chevrons + título grande + subtítulo + slot de ações à direita. Usado no topo das 5 telas admin, substitui blocos manuais repetidos.
* **Modais** (`x-modal`, `x-modal-form`, `x-confirm-delete-modal`): tema **claro** (revertido de uma tentativa de dark mode), cabeçalho com título + X para fechar, rodapé com botões alinhados à esquerda (ação principal + Cancelar), asterisco vermelho automático em campos `required`. Input de arquivo estilo Flowbite (botão "Escolher arquivo" + texto de ajuda).
* **Dark mode:** ligado por padrão apenas no admin (variantes `dark:` do Tailwind, `darkMode: 'class'` no `tailwind.config.js`). Cor de fundo no dark mode é `black` puro (não `gray-900`) em body/sidebar/header. A galeria pública **não** tem esse toggle — ela é sempre escura por design (ver seção 7), independente do estado do admin.

⚠️ **Gotcha recorrente:** toda vez que uma classe Tailwind nova é usada pela primeira vez num arquivo Blade, é preciso rodar `npm run build` de novo — o Tailwind só gera CSS para classes que já apareceram num build anterior. Isso já causou bug de espaçamento "sumido" nesta sessão.

---

## 7. Design System — Galeria Pública

Redesenhada com base em referência real de um cliente (`eskinaorlando.com/copa`), mas **genérica** — dirigida pelas cores de cada `Customer` (`primary_color`, `secondary_color`, `tertiary_color`), não fixa num tema (ex: não é "tema Copa do Mundo").

* **`x-public-layout`**: fundo quase preto (`#06060a`) com 3 gradientes radiais sutis nas cores do cliente + textura de ruído leve. Fontes **Anton** (títulos, condensada/bold, uppercase) e **Manrope** (corpo), carregadas via Google Fonts.
* **Hero**: breadcrumb pequeno (opcional, slot `breadcrumb`) → selo com bolinha pulsante (slot `badge`) → **logo do cliente** (se houver, `object-contain` para não cortar logos não-quadradas) → título grande com gradiente de texto → subtítulo → faixa decorativa listrada nas 3 cores do cliente.
* **Cards de listagem** (`x-gallery-card`, `x-gallery-empty`): usados nas telas de unidades/eventos/pastas, translúcidos sobre o fundo escuro, hover com leve elevação.
* **Grid de mídia e lightbox públicos** (`x-public-media-grid`, `x-public-lightbox`): **componentes próprios**, separados dos usados no admin (`x-media-grid`/`x-lightbox`) — não compartilham estilo/lógica de dark-mode-toggle do admin. Lightbox com botões de fechar/anterior/próximo flutuando fora do conteúdo, botão de download em destaque na cor primária do cliente.
* **Botão "Baixar tudo (.zip)"**: na tela de pasta, abaixo do grid, alinhado à direita. Abre a pasta do Google Drive em nova aba (`$folder->google_drive_url`) — o Google gera o zip quando o usuário clica em "Baixar" na própria UI do Drive. **Não é um zip gerado pelo nosso servidor** (decisão consciente: Google Drive não oferece link público direto de zip; gerar no backend exigiria baixar todo arquivo da pasta e recomprimir, custoso e frágil). Só aparece se a pasta tiver arquivos sincronizados.

---

## 8. O que foi feito nesta sessão (histórico resumido)

1. MVP completo a partir do zero (migrations, models, services, controllers, rotas, componentes Blade, views admin e públicas), rodando via Docker.
2. Bug crítico de roteamento corrigido: rota pública `{customer:slug}` engolindo `/login`.
3. Modais redesenhados 2x: primeiro estilo "Tailwind Plus" (ícone + fundo cinza), depois **tema escuro** completo, depois **revertido para tema claro** a pedido — estrutura final (título+X, rodapé com botões à esquerda) mantida do redesign dark.
4. Input de arquivo adaptado ao padrão Flowbite (clássico, com classes Tailwind puras, já que a doc atual do Flowbite usa um design system próprio incompatível).
5. Bug real encontrado e corrigido: checkbox "Exibir na galeria pública" desmarcado nunca era enviado pelo HTML — pasta sempre nascia pública. Corrigido com padrão hidden-input + checkbox.
6. Bug real encontrado e corrigido: Alpine.js não vinculava `x-on:click` em botões sem nenhum ancestral `x-data` — corrigido com `x-data="{}"` no `<body>` dos layouts.
7. Painel admin redesenhado no estilo Metronic (sidebar colapsável, `x-page-header`, cards com borda).
8. Dropdown de usuário no header (perfil + dark mode + sair), removendo o card de perfil da sidebar.
9. Dark mode funcional implementado (não só decorativo) em todo o admin, com fundo preto puro.
10. Galeria pública redesenhada com hero de marca dirigido pelas cores do cliente (baseado em referência real, generalizado).
11. Logo do cliente: corrigido corte de logos não-quadradas (`object-cover` → `object-contain`) no hero público e nos cards/detalhe do admin.
12. Botão "Baixar tudo (.zip)" na pasta pública (link pro Drive, não zip real — ver seção 7).
13. Cor terciária (`tertiary_color`) adicionada ao Customer, usada na faixa decorativa e gradiente de fundo da galeria pública.
14. Rotas de API JSON criadas (`routes/api.php` + `App\Http\Controllers\Api\GalleryController` + `App\Http\Resources\*`), espelhando os 4 níveis da galeria pública (customer/store/event/folder), cada nível trazendo os filhos diretos aninhados — ver seção 5.
15. Campo `Event.cover_image` adicionado: obrigatório, só aceita `.webp` — ver regra de negócio na seção 10. Bug real encontrado e corrigido: diretiva `@required()` dentro de tag self-closing `<x-input />` quebra a compilação do componente Blade (ver gotcha na seção 11); trocado por `:required="$cond"`.

Três commits feitos até agora (`f73f64f` MVP, `3b0a3ee` redesign admin+público, `4866b47` cor terciária + zip). **Há mudanças não commitadas** no momento (checar `git status` — inclui as rotas de API e o campo `cover_image` desta sessão).

---

## 9. Fluxo Principal

```
Admin cadastra Cliente (nome, descrição, 3 cores, logo)
↓
Admin cadastra Unidade da empresa (endereço, telefone, e-mail, logo opcional)
↓
Admin cria Evento pra Unidade (herda endereço/telefone/e-mail/descrição/logo da Unidade automaticamente,
  só título é obrigatório)
↓
Admin cria Pastas dentro do Evento (link público do Google Drive, obrigatório; is_public controla
  visibilidade pública)
↓
Sistema sincroniza e exibe fotos/vídeos em grid (photo thumbnail, video com ícone de play)
↓
Usuários públicos navegam Cliente → Unidade → Evento → Pasta e veem galeria com lightbox,
  sem necessidade de login
```

---

## 10. Regras de Negócio (ainda válidas, confirmadas em teste)

* Slugs únicos e amigáveis, gerados automaticamente, com sufixo numérico em colisão.
* Pastas com `is_public = false` **não aparecem** na listagem pública do evento e retornam **404** se acessadas diretamente pela URL pública.
* Ao criar evento, campos de endereço/telefone/e-mail/descrição/logo só são copiados da unidade se o admin não os informar.
* Exclusões (cliente/unidade/evento/pasta) pedem confirmação em modal e fazem cascade delete no banco (FKs `cascadeOnDelete`).
* Link de pasta é validado como URL do Google Drive antes de salvar (`GoogleDriveService::isValidFolderUrl`).
* CRUD do admin é **sempre** via modal — nunca criar `create.blade.php`/`edit.blade.php`.
* Painel administrativo exige autenticação; galeria pública nunca exige.
* `Event.cover_image` é **obrigatório**, aceita **somente `.webp`** (`'mimes:webp'` em `EventRequest`), até 4MB, salvo em `storage/app/public/covers`. Coluna é `nullable` no schema (para não quebrar eventos legados criados antes do campo existir), mas a validação exige o arquivo sempre que o evento **ainda não tem** `cover_image` — em eventos que já têm, o campo vira opcional na edição (mesmo padrão de "opcional, substitui se enviado" já usado pra `logo`). Ver `EventRequest::rules()` e `EventController::storeCoverImage()`.

---

## 11. Notas para o Agente de IA

* Leia este arquivo antes de sugerir mudanças estruturais.
* Ambiente principal é **Docker** (`docker compose up -d`) — depois de qualquer alteração em Blade/CSS, rodar `php artisan view:clear` **e** `npm run build` dentro do container antes de testar (ver seção 3 e o gotcha da seção 6).
* Para testar visualmente, usar o `Preview` tool. O servidor `momenthub-debug` (local, fora do Docker) é útil pra screenshots, mas cuidado com o symlink de storage (seção 3) e com sessão/login persistido entre testes.
* `x-media-grid`/`x-lightbox` (admin) e `x-public-media-grid`/`x-public-lightbox` (público) são **intencionalmente separados** — não unificar sem repensar o dark-mode-toggle do admin vs. o tema sempre-escuro do público.
* Não reintroduzir o bug de ordem de rotas (seção 5) nem o bug do checkbox `is_public` (seção 8, item 5).
* Não commitar sem pedido explícito do usuário. Quando for commitar, escopar `git add` só pros arquivos do MomentHub — o repositório git é um monorepo (`~/Documents/Projects`) com vários outros projetos irmãos que não devem ser tocados.
* Evitar abstrações desnecessárias, manter controllers simples e regra de negócio nos Services, seguindo o que já está estabelecido.
* ⚠️ **Gotcha do Blade descoberto nesta sessão:** diretivas como `@required(...)`/`@checked(...)` **não podem** ser usadas dentro de uma tag de componente self-closing (`<x-input ... @required($cond) ... />`) — isso quebra o regex do `ComponentTagCompiler` e o componente inteiro é renderizado **literalmente** como texto (`<x-input>...</x-input>` cru no HTML, sem virar `<input>` real), sem erro visível no browser. Para atributos booleanos condicionais em componentes, usar o binding `:required="$cond"` — o `ComponentAttributeBag::__toString()` já trata `true`/`false` corretamente (renderiza `attr="attr"` ou omite). O componente `x-input` (`resources/views/components/input.blade.php`) checa `$attributes->has('required') && $attributes->get('required') !== false` pra decidir se mostra o asterisco — não voltar pra um `has()` simples, senão o asterisco aparece mesmo quando `:required="false"`.
