<x-public-layout
    :customer="$customer"
    :title="$customer->name"
    badge="Galeria de fotos & vídeos"
    subtitle="Escolha uma unidade para ver os eventos e as galerias disponíveis."
>
    @if ($stores->isEmpty())
        <x-gallery-empty>Nenhuma unidade disponível no momento.</x-gallery-empty>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($stores as $store)
                <x-gallery-card
                    :href="route('gallery.store', [$customer, $store])"
                    :title="$store->name"
                    :meta="$store->address"
                    cta="Ver eventos"
                />
            @endforeach
        </div>
    @endif
</x-public-layout>
