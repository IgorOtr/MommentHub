<x-admin-layout title="Clientes">
    <x-page-header
        title="Clientes"
        subtitle="Empresas que utilizam a plataforma MomentHub."
        :crumbs="[['label' => 'Clientes']]"
    >
        <x-slot:actions>
            <x-button x-on:click="$dispatch('open-modal', 'create-customer')">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Novo Cliente
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if ($customers->isEmpty())
        <x-card class="text-center py-16">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nenhum cliente cadastrado ainda.</p>
            <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">Clique em "Novo Cliente" para começar.</p>
        </x-card>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($customers as $customer)
                <x-card class="flex flex-col gap-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-lg text-white font-semibold overflow-hidden"
                                style="background-color: {{ $customer->primary_color ?? '#4f46e5' }}"
                            >
                                @if ($customer->logoUrl())
                                    <img src="{{ $customer->logoUrl() }}" alt="{{ $customer->name }}" class="h-full w-full object-contain p-1.5">
                                @else
                                    {{ Str::substr($customer->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $customer->name }}</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $customer->slug }}</p>
                            </div>
                        </div>

                        <div class="flex gap-1">
                            <button type="button" x-on:click="$dispatch('open-modal', 'edit-customer-{{ $customer->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300" aria-label="Editar">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" />
                                </svg>
                            </button>
                            <button type="button" x-on:click="$dispatch('open-modal', 'delete-customer-{{ $customer->id }}')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:text-gray-500 dark:hover:bg-red-500/10 dark:hover:text-red-400" aria-label="Excluir">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($customer->description)
                        <p class="text-sm text-gray-500 line-clamp-2 dark:text-gray-400">{{ $customer->description }}</p>
                    @endif

                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <x-badge variant="indigo">{{ $customer->stores_count }} {{ Str::plural('unidade', $customer->stores_count) }}</x-badge>
                        <x-badge>{{ $customer->events_count }} {{ Str::plural('evento', $customer->events_count) }}</x-badge>
                    </div>

                    <x-button variant="secondary" :href="route('admin.customer.show', $customer)" class="mt-auto justify-center">
                        Ver unidades
                    </x-button>
                </x-card>

                <x-modal-form
                    name="edit-customer-{{ $customer->id }}"
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

                <x-confirm-delete-modal
                    name="delete-customer-{{ $customer->id }}"
                    :action="route('admin.customers.destroy', $customer)"
                    title="Excluir cliente"
                    :message="'Isso removerá \''.$customer->name.'\' e todas as unidades, eventos e pastas relacionadas. Esta ação não pode ser desfeita.'"
                />
            @endforeach
        </div>
    @endif

    <x-modal-form
        name="create-customer"
        :action="route('admin.customers.store')"
        title="Novo cliente"
        submit-label="Criar cliente"
        has-file
    >
        <x-input name="name" label="Nome" required />
        <x-textarea name="description" label="Descrição" />

        <div class="grid grid-cols-3 gap-4">
            <x-input type="color" name="primary_color" label="Cor primária" value="#4f46e5" />
            <x-input type="color" name="secondary_color" label="Cor secundária" value="#1e1b4b" />
            <x-input type="color" name="tertiary_color" label="Cor terciária" value="#f59e0b" />
        </div>

        <x-input type="file" name="logo" label="Logo" accept="image/*" hint="PNG, JPG ou WEBP até 2MB." />
    </x-modal-form>
</x-admin-layout>
