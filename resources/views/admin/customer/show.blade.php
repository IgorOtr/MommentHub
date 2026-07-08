<x-admin-layout :title="$customer->name">
    <x-page-header
        :title="$customer->name"
        :crumbs="[
            ['label' => 'Clientes', 'href' => route('admin.customers.index')],
            ['label' => $customer->name],
        ]"
    >
        <x-slot:actions>
            <x-button variant="secondary" x-on:click="$dispatch('open-modal', 'edit-customer')">
                Editar cliente
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="mb-8 flex flex-col sm:flex-row sm:items-center gap-5">
        <div
            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl text-white text-xl font-semibold overflow-hidden"
            style="background-color: {{ $customer->primary_color ?? '#4f46e5' }}"
        >
            @if ($customer->logoUrl())
                <img src="{{ $customer->logoUrl() }}" alt="{{ $customer->name }}" class="h-full w-full object-contain p-2.5">
            @else
                {{ Str::substr($customer->name, 0, 1) }}
            @endif
        </div>

        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $customer->name }} - <span class="text-sm text-gray-500 dark:text-gray-400">{{ "/api/" . $customer->slug }}</span></h2>
            @if ($customer->description)
                <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">{{ $customer->description }}</p>
            @endif
            <div class="flex items-center gap-2 mt-2">
                <span class="h-4 w-4 rounded-full ring-1 ring-black/10" style="background-color: {{ $customer->primary_color ?? '#4f46e5' }}"></span>
                <span class="h-4 w-4 rounded-full ring-1 ring-black/10" style="background-color: {{ $customer->secondary_color ?? '#1e1b4b' }}"></span>
                <span class="h-4 w-4 rounded-full ring-1 ring-black/10" style="background-color: {{ $customer->tertiary_color ?? '#f59e0b' }}"></span>
                <a href="{{ route('gallery.customer', $customer) }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 ml-2">
                    Ver galeria pública &rarr;
                </a>
            </div>
        </div>
    </x-card>

    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Unidades</h3>
        <x-button x-on:click="$dispatch('open-modal', 'create-store')">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nova Unidade
        </x-button>
    </div>

    @if ($stores->isEmpty())
        <x-card class="text-center py-16">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nenhuma unidade cadastrada.</p>
            <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">Cadastre a primeira unidade física deste cliente.</p>
        </x-card>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($stores as $store)
                <x-card class="flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $store->name }}</h4>
                        <div class="flex gap-1">
                            <button type="button" x-on:click="$dispatch('open-modal', 'edit-store-{{ $store->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300" aria-label="Editar">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                </svg>
                            </button>
                            <button type="button" x-on:click="$dispatch('open-modal', 'delete-store-{{ $store->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:text-gray-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" aria-label="Excluir">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($store->address)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $store->address }}</p>
                    @endif

                    <x-badge variant="indigo" class="w-fit">{{ $store->events_count }} {{ Str::plural('evento', $store->events_count) }}</x-badge>

                    <x-button variant="secondary" :href="route('admin.store.show', $store)" class="mt-auto justify-center">
                        Ver eventos
                    </x-button>
                </x-card>

                <x-modal-form
                    name="edit-store-{{ $store->id }}"
                    :action="route('admin.stores.update', $store)"
                    method="PUT"
                    title="Editar unidade"
                    submit-label="Salvar"
                    has-file
                >
                    <x-slot:hidden>
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    </x-slot:hidden>

                    <x-input name="name" label="Nome" :value="$store->name" required />
                    <x-textarea name="description" label="Descrição" :value="$store->description" />
                    <x-input name="address" label="Endereço" :value="$store->address" required />

                    <div class="grid grid-cols-2 gap-4">
                        <x-input name="phone" label="Telefone" :value="$store->phone" />
                        <x-input type="email" name="email" label="E-mail" :value="$store->email" />
                    </div>

                    <x-input type="file" name="logo" label="Logo (opcional)" accept="image/*" hint="PNG, JPG ou WEBP até 2MB." />
                </x-modal-form>

                <x-confirm-delete-modal
                    name="delete-store-{{ $store->id }}"
                    :action="route('admin.stores.destroy', $store)"
                    title="Excluir unidade"
                    :message="'Isso removerá \''.$store->name.'\' e todos os eventos e pastas relacionados.'"
                />
            @endforeach
        </div>
    @endif

    <x-modal-form
        name="edit-customer"
        :action="route('admin.customers.update', $customer)"
        method="PUT"
        title="Editar cliente"
        submit-label="Salvar"
        has-file
    >
        <x-input name="name" label="Nome" :value="$customer->name" required />
        <x-textarea name="description" label="Descrição" :value="$customer->description" />

        <div class="grid grid-cols-3 gap-4">
            <x-input type="color" name="primary_color" label="Cor primária" :value="$customer->primary_color ?? '#4f46e5'" />
            <x-input type="color" name="secondary_color" label="Cor secundária" :value="$customer->secondary_color ?? '#1e1b4b'" />
            <x-input type="color" name="tertiary_color" label="Cor terciária" :value="$customer->tertiary_color ?? '#f59e0b'" />
        </div>

        <x-input type="file" name="logo" label="Logo" accept="image/*" hint="PNG, JPG ou WEBP até 2MB." />
    </x-modal-form>

    <x-modal-form
        name="create-store"
        :action="route('admin.stores.store')"
        title="Nova unidade"
        submit-label="Criar unidade"
        has-file
    >
        <x-slot:hidden>
            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
        </x-slot:hidden>

        <x-input name="name" label="Nome" required />
        <x-textarea name="description" label="Descrição" />
        <x-input name="address" label="Endereço" required />

        <div class="grid grid-cols-2 gap-4">
            <x-input name="phone" label="Telefone" />
            <x-input type="email" name="email" label="E-mail" />
        </div>

        <x-input type="file" name="logo" label="Logo (opcional)" accept="image/*" hint="PNG, JPG ou WEBP até 2MB." />
    </x-modal-form>
</x-admin-layout>
