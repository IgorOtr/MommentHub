@props(['title' => null])

<header
    x-data="{ mobileOpen: false }"
    class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8 dark:border-gray-800 dark:bg-black/90"
>
    <button
        type="button"
        x-on:click="mobileOpen = true"
        class="text-gray-500 hover:text-gray-700 lg:hidden dark:text-gray-400 dark:hover:text-gray-200"
        aria-label="Abrir menu"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="flex-1"></div>

    <div
        x-data="{ userMenuOpen: false, darkMode: localStorage.getItem('mh-dark-mode') === '1' }"
        class="relative"
    >
        <button
            type="button"
            x-on:click="userMenuOpen = !userMenuOpen"
            class="flex items-center gap-2 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
        >
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                {{ Illuminate\Support\Str::substr(auth()->user()->name, 0, 1) }}
            </span>
        </button>

        <div
            x-show="userMenuOpen"
            x-cloak
            x-on:click.outside="userMenuOpen = false"
            x-transition.origin.top.right
            class="absolute right-0 z-40 mt-3 w-64 overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
        >
            <div class="flex items-center gap-3 px-4 py-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-base font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                    {{ Illuminate\Support\Str::substr(auth()->user()->name, 0, 1) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="border-t border-gray-100 py-1 dark:border-gray-700">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                    <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Editar perfil
                </a>
            </div>

            <div class="border-t border-gray-100 py-1 dark:border-gray-700">
                <button
                    type="button"
                    x-on:click="
                        darkMode = !darkMode;
                        document.documentElement.classList.toggle('dark', darkMode);
                        localStorage.setItem('mh-dark-mode', darkMode ? '1' : '0');
                    "
                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50"
                >
                    <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                    <span class="flex-1 text-left">Modo escuro</span>
                    <span
                        class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition"
                        :class="darkMode ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600'"
                    >
                        <span
                            class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition"
                            :class="darkMode ? 'translate-x-[18px]' : 'translate-x-0.5'"
                        ></span>
                    </span>
                </button>
            </div>

            <div class="border-t border-gray-100 py-1 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 transition hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3H21" />
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div
        x-show="mobileOpen"
        x-cloak
        x-on:keydown.escape.window="mobileOpen = false"
        class="fixed inset-0 z-40 lg:hidden"
    >
        <div class="fixed inset-0 bg-gray-900/50" x-on:click="mobileOpen = false"></div>

        <div class="fixed inset-y-0 left-0 w-72 bg-white p-4 shadow-xl dark:bg-black">
            <div class="mb-6 flex items-center gap-2.5 px-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold">M</div>
                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">MomentHub</span>
            </div>

            <p class="mb-2.5 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Menu principal</p>

            <a
                href="{{ route('admin.customers.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.customer*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-gray-100' }}"
            >
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.customer*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
                Clientes
            </a>
        </div>
    </div>
</header>
