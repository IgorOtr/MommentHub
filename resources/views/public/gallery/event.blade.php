<x-public-layout
    :customer="$customer"
    :title="$event->title"
    :badge="$store->name"
    :subtitle="$event->description ?? optional($event->event_date)?->format('d/m/Y')"
>
    <x-slot:breadcrumb>
        <a href="{{ route('gallery.customer', $customer) }}" class="hover:text-gray-300">{{ $customer->name }}</a>
        <svg class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <a href="{{ route('gallery.store', [$customer, $store]) }}" class="hover:text-gray-300">{{ $store->name }}</a>
        <svg class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-400">{{ $event->title }}</span>
    </x-slot:breadcrumb>

    @if ($folders->isEmpty())
        <x-gallery-empty>Nenhuma galeria disponível para este evento.</x-gallery-empty>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($folders as $folder)
                <x-gallery-card
                    :href="route('gallery.folder', [$customer, $store, $event, $folder])"
                    :title="$folder->name"
                    :meta="$folder->description"
                    cta="Ver fotos e vídeos"
                />
            @endforeach
        </div>
    @endif
</x-public-layout>
