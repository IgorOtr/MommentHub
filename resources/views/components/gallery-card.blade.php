@props(['href', 'title', 'meta' => null, 'cta'])

<a
    href="{{ $href }}"
    class="gallery-card group block rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition hover:-translate-y-1 hover:bg-white/[0.07]"
>
    <h3 class="font-semibold text-white">{{ $title }}</h3>

    @if ($meta)
        <p class="mt-1 text-sm text-gray-400">{{ $meta }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-1 text-sm text-gray-400">{{ $slot }}</div>
    @endif

    <span class="gallery-cta mt-4 inline-flex items-center gap-1 text-sm font-medium">
        {{ $cta }}
        <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
        </svg>
    </span>
</a>
