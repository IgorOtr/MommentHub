<x-admin-layout :title="$event->title">
    <x-page-header
        :title="$event->title"
        :subtitle="$event->event_date?->format('d/m/Y')"
        :crumbs="[
            ['label' => 'Clientes', 'href' => route('admin.customers.index')],
            ['label' => $event->customer->name, 'href' => route('admin.customer.show', $event->customer)],
            ['label' => $event->store->name, 'href' => route('admin.store.show', $event->store)],
            ['label' => $event->title],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('gallery.event', [$event->customer, $event->store, $event]) }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                Ver galeria pública &rarr;
            </a>
            <x-button variant="secondary" x-on:click="$dispatch('open-modal', 'edit-event')">Editar evento</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="mb-8">
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <dt class="text-gray-400 dark:text-gray-500">Endereço</dt>
                <dd class="text-gray-700 dark:text-gray-300">{{ $event->address ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 dark:text-gray-500">Telefone</dt>
                <dd class="text-gray-700 dark:text-gray-300">{{ $event->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 dark:text-gray-500">E-mail</dt>
                <dd class="text-gray-700 dark:text-gray-300">{{ $event->email ?? '—' }}</dd>
            </div>
        </dl>
    </x-card>

    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pastas</h3>
        <x-button x-on:click="$dispatch('open-modal', 'create-folder')">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nova Pasta
        </x-button>
    </div>

    @if ($folders->isEmpty())
        <x-card class="text-center py-16">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nenhuma pasta cadastrada.</p>
            <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">Adicione um link público do Google Drive para exibir fotos e vídeos.</p>
        </x-card>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($folders as $folder)
                <x-card class="flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $folder->name }}</h4>
                        <div class="flex gap-1">
                            <button type="button" x-on:click="$dispatch('open-modal', 'edit-folder-{{ $folder->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300" aria-label="Editar">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                </svg>
                            </button>
                            <button type="button" x-on:click="$dispatch('open-modal', 'delete-folder-{{ $folder->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:text-gray-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" aria-label="Excluir">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($folder->description)
                        <p class="text-sm text-gray-500 line-clamp-2 dark:text-gray-400">{{ $folder->description }}</p>
                    @endif

                    <div class="flex items-center gap-2">
                        <x-badge variant="indigo">{{ $folder->media_files_count }} {{ Str::plural('arquivo', $folder->media_files_count) }}</x-badge>
                        <x-badge :variant="$folder->is_public ? 'green' : 'gray'">
                            {{ $folder->is_public ? 'Pública' : 'Privada' }}
                        </x-badge>
                    </div>

                    <x-button variant="secondary" :href="route('admin.folder.show', $folder)" class="mt-auto justify-center">
                        Ver arquivos
                    </x-button>
                </x-card>

                <x-modal-form
                    name="edit-folder-{{ $folder->id }}"
                    :action="route('admin.folders.update', $folder)"
                    method="PUT"
                    title="Editar pasta"
                    submit-label="Salvar"
                >
                    <x-slot:hidden>
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                    </x-slot:hidden>

                    <x-input name="name" label="Nome" :value="$folder->name" required />
                    <x-textarea name="description" label="Descrição" :value="$folder->description" />
                    <x-input type="url" name="google_drive_url" label="Link público do Google Drive" :value="$folder->google_drive_url" required />

                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1" @checked($folder->is_public) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Exibir na galeria pública
                    </label>
                </x-modal-form>

                <x-confirm-delete-modal
                    name="delete-folder-{{ $folder->id }}"
                    :action="route('admin.folders.destroy', $folder)"
                    title="Excluir pasta"
                    :message="'Isso removerá \''.$folder->name.'\' e todos os arquivos relacionados.'"
                />
            @endforeach
        </div>
    @endif

    <x-modal-form
        name="edit-event"
        :action="route('admin.events.update', $event)"
        method="PUT"
        title="Editar evento"
        submit-label="Salvar"
        has-file
    >
        <x-slot:hidden>
            <input type="hidden" name="store_id" value="{{ $event->store_id }}">
        </x-slot:hidden>

        <x-input name="title" label="Título" :value="$event->title" required />
        <x-input type="date" name="event_date" label="Data do evento" :value="optional($event->event_date)->format('Y-m-d')" />
        <x-textarea name="description" label="Descrição" :value="$event->description" />

        <div class="grid grid-cols-2 gap-4">
            <x-input name="address" label="Endereço" :value="$event->address" />
            <x-input name="phone" label="Telefone" :value="$event->phone" />
        </div>

        <x-input type="email" name="email" label="E-mail" :value="$event->email" />
        <x-input type="file" name="logo" label="Logo (opcional)" accept="image/*" hint="PNG, JPG ou WEBP até 2MB." />
        <x-input type="file" name="cover_image" label="Imagem de capa" accept="image/webp" :required="! $event->cover_image" hint="Apenas .webp, até 4MB." />
    </x-modal-form>

    <x-modal-form
        name="create-folder"
        :action="route('admin.folders.store')"
        title="Nova pasta"
        submit-label="Criar pasta"
    >
        <x-slot:hidden>
            <input type="hidden" name="event_id" value="{{ $event->id }}">
        </x-slot:hidden>

        <x-input name="name" label="Nome" required />
        <x-textarea name="description" label="Descrição (opcional)" />
        <x-input type="url" name="google_drive_url" label="Link público do Google Drive" placeholder="https://drive.google.com/drive/folders/..." required />

        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="hidden" name="is_public" value="0">
            <input type="checkbox" name="is_public" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            Exibir na galeria pública
        </label>
    </x-modal-form>
</x-admin-layout>
