<x-admin-layout :title="$folder->name">
    <x-page-header
        :title="$folder->name"
        :crumbs="[
            ['label' => 'Clientes', 'href' => route('admin.customers.index')],
            ['label' => $folder->event->customer->name, 'href' => route('admin.customer.show', $folder->event->customer)],
            ['label' => $folder->event->store->name, 'href' => route('admin.store.show', $folder->event->store)],
            ['label' => $folder->event->title, 'href' => route('admin.event.show', $folder->event)],
            ['label' => $folder->name],
        ]"
    >
        <x-slot:actions>
            <x-button variant="secondary" x-on:click="$dispatch('open-modal', 'edit-folder')">Editar pasta</x-button>
            <x-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-folder')">Excluir</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="mb-8">
        <div class="flex items-center gap-2">
            <x-badge :variant="$folder->is_public ? 'green' : 'gray'">
                {{ $folder->is_public ? 'Pública' : 'Privada' }}
            </x-badge>
        </div>
        @if ($folder->description)
            <p class="text-sm text-gray-500 mt-2 dark:text-gray-400">{{ $folder->description }}</p>
        @endif
        <a href="{{ $folder->google_drive_url }}" target="_blank" rel="noopener" class="mt-1 inline-block text-xs text-indigo-600 hover:text-indigo-500 break-all">
            {{ $folder->google_drive_url }}
        </a>
    </x-card>

    <h3 class="text-lg font-semibold text-gray-900 mb-4 dark:text-gray-100">Arquivos</h3>

    <x-media-grid :media-files="$mediaFiles" />

    <x-modal-form
        name="edit-folder"
        :action="route('admin.folders.update', $folder)"
        method="PUT"
        title="Editar pasta"
        submit-label="Salvar"
    >
        <x-slot:hidden>
            <input type="hidden" name="event_id" value="{{ $folder->event_id }}">
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
        name="delete-folder"
        :action="route('admin.folders.destroy', $folder)"
        title="Excluir pasta"
        :message="'Isso removerá \''.$folder->name.'\' e todos os arquivos relacionados.'"
    />
</x-admin-layout>
