<x-public-layout
    :customer="$customer"
    :title="$store->name"
    :badge="$customer->name"
    subtitle="Escolha um evento para ver as galerias de fotos e vídeos."
>
    <x-slot:breadcrumb>
        <a href="{{ route('gallery.customer', $customer) }}" class="hover:text-gray-300">{{ $customer->name }}</a>
        <svg class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-400">{{ $store->name }}</span>
    </x-slot:breadcrumb>

    @if ($events->isEmpty())
        <x-gallery-empty>Nenhum evento disponível no momento.</x-gallery-empty>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($events as $event)
                <x-gallery-card
                    :href="route('gallery.event', [$customer, $store, $event])"
                    :title="$event->title"
                    :meta="optional($event->event_date)->format('d/m/Y')"
                    cta="Ver galerias"
                />
            @endforeach
        </div>
    @endif
</x-public-layout>
