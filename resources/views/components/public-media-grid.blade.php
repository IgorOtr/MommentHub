@props(['mediaFiles'])

<div
    x-data="{
        open: false,
        index: 0,
        files: @js($mediaFiles->map(fn ($file) => [
            'id' => $file->id,
            'name' => $file->name,
            'type' => $file->file_type,
            'thumbnail' => $file->thumbnail_url,
            'preview' => $file->preview_url,
            'download' => $file->download_url,
        ])),
        show(i) { this.index = i; this.open = true },
        next() { this.index = (this.index + 1) % this.files.length },
        prev() { this.index = (this.index - 1 + this.files.length) % this.files.length },
    }"
>
    @if ($mediaFiles->isEmpty())
        <x-gallery-empty>
            Nenhum arquivo encontrado nesta pasta ainda.
        </x-gallery-empty>
    @else
        <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 lg:grid-cols-4">
            <template x-for="(file, i) in files" :key="file.id">
                <button
                    type="button"
                    x-on:click="show(i)"
                    class="gallery-card group relative aspect-square overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition hover:-translate-y-1 focus:outline-none"
                >
                    <img
                        x-show="file.thumbnail"
                        :src="file.thumbnail"
                        :alt="file.name"
                        loading="lazy"
                        class="h-full w-full object-cover opacity-0 transition duration-300 [&.gallery-loaded]:opacity-100"
                        x-on:load="$el.classList.add('gallery-loaded')"
                    >
                    <div x-show="!file.thumbnail" class="flex h-full w-full items-center justify-center text-gray-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                        </svg>
                    </div>

                    <div x-show="file.type === 'video'" class="absolute inset-0 flex items-center justify-center bg-black/25">
                        <svg class="h-9 w-9 text-white drop-shadow" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <span class="pointer-events-none absolute inset-x-0 bottom-0 truncate bg-gradient-to-t from-black/70 to-transparent px-2 py-1.5 text-[11px] text-white/90 opacity-0 transition group-hover:opacity-100" x-text="file.name"></span>
                </button>
            </template>
        </div>

        <x-public-lightbox />
    @endif
</div>
