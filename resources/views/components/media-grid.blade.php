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
        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 py-16 text-center dark:border-gray-700">
            <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">Nenhum arquivo encontrado nesta pasta</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">Verifique se o link do Google Drive está público e contém fotos ou vídeos.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <template x-for="(file, i) in files" :key="file.id">
                <button
                    type="button"
                    x-on:click="show(i)"
                    class="group relative aspect-square overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <img
                        x-show="file.thumbnail"
                        :src="file.thumbnail"
                        :alt="file.name"
                        loading="lazy"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105"
                    >
                    <div x-show="!file.thumbnail" class="flex h-full w-full items-center justify-center bg-gray-200 text-gray-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                        </svg>
                    </div>

                    <div x-show="file.type === 'video'" class="absolute inset-0 flex items-center justify-center bg-black/20">
                        <svg class="h-9 w-9 text-white drop-shadow" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <span class="pointer-events-none absolute inset-x-0 bottom-0 truncate bg-gradient-to-t from-black/60 to-transparent px-2 py-1 text-[11px] text-white opacity-0 transition group-hover:opacity-100" x-text="file.name"></span>
                </button>
            </template>
        </div>

        <x-lightbox />
    @endif
</div>
