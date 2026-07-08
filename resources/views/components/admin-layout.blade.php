@props(['title' => 'MomentHub'])

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} · MomentHub Admin</title>

    <script>
        if (localStorage.getItem('mh-dark-mode') === '1') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-gray-50 antialiased dark:bg-black"
    x-data="{ sidebarCollapsed: localStorage.getItem('mh-sidebar-collapsed') === '1' }"
    x-init="$watch('sidebarCollapsed', value => localStorage.setItem('mh-sidebar-collapsed', value ? '1' : '0'))"
>
    <x-sidebar />

    <div class="transition-[padding] duration-200" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
        <x-navbar :title="$title" />

        <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-10 lg:py-10">
            @if (session('success'))
                <x-alert variant="success" class="mb-6">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="error" class="mb-6">{{ session('error') }}</x-alert>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
