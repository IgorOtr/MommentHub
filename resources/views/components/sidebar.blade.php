@php
$sections = [
    [
        'label' => 'Menu principal',
        'links' => [
            ['route' => 'admin.customers.index', 'active' => 'admin.customer*', 'label' => 'Clientes', 'icon' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21'],
        ],
    ],
];
@endphp

<aside
    class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 border-r border-gray-200 bg-white transition-[width] duration-200 dark:border-gray-800 dark:bg-black"
    :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-72'"
>
    <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-gray-200 px-4 dark:border-gray-800" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div class="flex min-w-0 items-center gap-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold">M</div>
            <span x-show="!sidebarCollapsed" x-cloak class="truncate text-lg font-semibold text-gray-900 dark:text-gray-100">MomentHub</span>
        </div>

        <button
            type="button"
            x-show="!sidebarCollapsed"
            x-cloak
            x-on:click="sidebarCollapsed = true"
            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-gray-200 text-gray-400 transition hover:bg-gray-50 hover:text-gray-600 dark:border-gray-700 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-300"
            aria-label="Recolher menu"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <button
        type="button"
        x-show="sidebarCollapsed"
        x-cloak
        x-on:click="sidebarCollapsed = false"
        class="mx-auto mt-3 flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-gray-200 text-gray-400 transition hover:bg-gray-50 hover:text-gray-600 dark:border-gray-700 dark:text-gray-500 dark:hover:bg-gray-800 dark:hover:text-gray-300"
        aria-label="Expandir menu"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <nav class="flex-1 space-y-7 overflow-y-auto px-3 py-6">
        @foreach ($sections as $section)
            <div>
                <p x-show="!sidebarCollapsed" x-cloak class="mb-2.5 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    {{ $section['label'] }}
                </p>

                <div class="space-y-1">
                    @foreach ($section['links'] as $link)
                        @php $isActive = request()->routeIs($link['active']); @endphp
                        <a
                            href="{{ route($link['route']) }}"
                            title="{{ $link['label'] }}"
                            class="flex items-center gap-3 rounded-lg py-2.5 text-sm font-medium transition {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-gray-100' }}"
                            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                            </svg>

                            <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>
