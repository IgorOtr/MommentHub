@props(['title', 'subtitle' => null, 'crumbs' => []])

<div class="mb-8 flex flex-col gap-4 border-b border-gray-200 pb-8 sm:flex-row sm:items-end sm:justify-between dark:border-gray-800">
    <div>
        @if (count($crumbs))
            <nav class="mb-2 flex flex-wrap items-center gap-1.5 text-xs font-medium text-gray-400 dark:text-gray-500">
                @foreach ($crumbs as $crumb)
                    @if (!$loop->first)
                        <svg class="h-3 w-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    @endif

                    @if (!empty($crumb['href']) && !$loop->last)
                        <a href="{{ $crumb['href'] }}" class="hover:text-gray-600 dark:hover:text-gray-300">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h1>

        @if ($subtitle)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>
