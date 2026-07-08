<div
    x-show="open"
    x-cloak
    x-on:keydown.escape.window="open = false"
    x-on:keydown.arrow-right.window="next()"
    x-on:keydown.arrow-left.window="prev()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/92 p-4 backdrop-blur-sm"
    style="display: none;"
>
    <button
        type="button"
        x-on:click="open = false"
        class="fixed right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white transition hover:bg-white/20"
        aria-label="Fechar"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <button
        type="button"
        x-on:click="prev()"
        x-show="files.length > 1"
        class="fixed left-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white transition hover:bg-white/20 sm:left-6"
        aria-label="Anterior"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </button>

    <button
        type="button"
        x-on:click="next()"
        x-show="files.length > 1"
        class="fixed right-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white transition hover:bg-white/20 sm:right-6"
        aria-label="Próximo"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>

    <div class="flex w-full max-w-4xl flex-col gap-4" x-on:click.self="open = false">
        <div class="aspect-video w-full overflow-hidden rounded-2xl bg-black shadow-2xl">
            <template x-if="files[index]">
                <iframe
                    :src="files[index].preview"
                    class="h-full w-full"
                    allow="autoplay; fullscreen"
                    allowfullscreen
                ></iframe>
            </template>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <span class="truncate text-sm text-gray-400" x-text="files[index]?.name"></span>

            <a
                :href="files[index]?.download || files[index]?.preview"
                target="_blank"
                rel="noopener"
                x-show="files[index]?.download"
                class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold shadow-lg transition hover:brightness-110"
                style="background-color: var(--brand-primary); color: #0a0a0a;"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Baixar
            </a>
        </div>
    </div>
</div>
