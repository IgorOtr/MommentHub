<div
    x-show="open"
    x-cloak
    x-on:keydown.escape.window="open = false"
    x-on:keydown.arrow-right.window="next()"
    x-on:keydown.arrow-left.window="prev()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
    style="display: none;"
>
    <button
        type="button"
        x-on:click="open = false"
        class="absolute right-4 top-4 z-10 rounded-full bg-white/10 p-2 text-white hover:bg-white/20"
        aria-label="Fechar"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <button
        type="button"
        x-on:click="prev()"
        x-show="files.length > 1"
        class="absolute left-2 sm:left-4 z-10 rounded-full bg-white/10 p-2 text-white hover:bg-white/20"
        aria-label="Anterior"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </button>

    <button
        type="button"
        x-on:click="next()"
        x-show="files.length > 1"
        class="absolute right-2 sm:right-16 z-10 rounded-full bg-white/10 p-2 text-white hover:bg-white/20"
        aria-label="Próximo"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>

    <div class="flex h-full w-full max-w-5xl flex-col items-center justify-center gap-3" x-on:click.self="open = false">
        <div class="flex w-full flex-1 items-center justify-center overflow-hidden">
            <template x-if="files[index]">
                <iframe
                    :src="files[index].preview"
                    class="aspect-video w-full max-h-[80vh] rounded-lg bg-black"
                    allow="autoplay; fullscreen"
                    allowfullscreen
                ></iframe>
            </template>
        </div>

        <div class="flex w-full items-center justify-between text-sm text-white/80">
            <span class="truncate" x-text="files[index]?.name"></span>

            <a
                :href="files[index]?.download || files[index]?.preview"
                target="_blank"
                rel="noopener"
                x-show="files[index]?.download"
                class="ml-4 inline-flex shrink-0 items-center gap-1 rounded-lg bg-white/10 px-3 py-1.5 font-medium hover:bg-white/20"
            >
                Baixar
            </a>
        </div>
    </div>
</div>
