<x-public-layout
    :customer="$customer"
    :title="$folder->name"
    :badge="$event->title"
    :subtitle="$folder->description"
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
        <a href="{{ route('gallery.event', [$customer, $store, $event]) }}" class="hover:text-gray-300">{{ $event->title }}</a>
        <svg class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-400">{{ $folder->name }}</span>
    </x-slot:breadcrumb>

    <x-public-media-grid :media-files="$mediaFiles" />

    @if ($mediaFiles->isNotEmpty())
        <div class="mt-6 flex justify-end">
            <a
                href="{{ $folder->google_drive_url }}"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                </svg>
                Baixar tudo (.zip)
            </a>
        </div>
    @endif
</x-public-layout>
