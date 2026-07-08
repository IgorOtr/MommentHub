@props([
    'name',
    'action',
    'method' => 'POST',
    'title',
    'submitLabel' => 'Salvar',
    'hasFile' => false,
    'maxWidth' => 'lg',
])

<x-modal :name="$name" :max-width="$maxWidth">
    <form
        method="POST"
        action="{{ $action }}"
        @if ($hasFile) enctype="multipart/form-data" @endif
    >
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        {{ $hidden ?? '' }}

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>

            <button
                type="button"
                class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                x-on:click="$dispatch('close-modal', '{{ $name }}')"
                aria-label="Fechar"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-4 px-6 py-6">
            {{ $slot }}
        </div>

        <div class="flex items-center gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <x-button type="submit">
                {{ $submitLabel }}
            </x-button>
            <x-button
                type="button"
                variant="muted"
                x-on:click="$dispatch('close-modal', '{{ $name }}')"
            >
                Cancelar
            </x-button>
        </div>
    </form>
</x-modal>
