@props(['customer' => null, 'title' => 'MomentHub', 'subtitle' => null, 'badge' => null])

@php
$primary = $customer->primary_color ?? '#4f46e5';
$secondary = $customer->secondary_color ?? '#1e1b4b';
$tertiary = $customer->tertiary_color ?? '#f59e0b';
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: {{ $primary }};
            --brand-secondary: {{ $secondary }};
            --brand-tertiary: {{ $tertiary }};
        }

        body.gallery-body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(1200px 600px at 12% -10%, color-mix(in srgb, var(--brand-primary) 30%, transparent), transparent 60%),
                radial-gradient(900px 550px at 110% 15%, color-mix(in srgb, var(--brand-secondary) 28%, transparent), transparent 55%),
                radial-gradient(800px 500px at 50% 120%, color-mix(in srgb, var(--brand-tertiary) 16%, transparent), transparent 60%),
                #06060a;
        }

        body.gallery-body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.05;
            z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        .gallery-title {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            letter-spacing: 0.01em;
            background: linear-gradient(180deg, #ffffff 0%, color-mix(in srgb, var(--brand-primary) 60%, white) 130%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gallery-stripe {
            background: repeating-linear-gradient(90deg,
                var(--brand-primary) 0 40px,
                var(--brand-secondary) 40px 80px,
                var(--brand-tertiary) 80px 120px);
        }

        .gallery-badge {
            color: color-mix(in srgb, var(--brand-primary) 65%, white);
            border-color: color-mix(in srgb, var(--brand-primary) 40%, transparent);
            background-color: color-mix(in srgb, var(--brand-primary) 12%, transparent);
        }

        .gallery-dot {
            background-color: var(--brand-primary);
        }

        .gallery-card:hover {
            border-color: color-mix(in srgb, var(--brand-primary) 45%, transparent);
        }

        .gallery-cta {
            color: color-mix(in srgb, var(--brand-primary) 70%, white);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="gallery-body min-h-screen antialiased text-gray-300" x-data="{}">
    <header class="relative overflow-hidden px-6 pb-10 pt-14 text-center sm:pt-20">
        @isset($breadcrumb)
            <nav class="relative z-10 mb-6 flex flex-wrap items-center justify-center gap-1.5 text-xs font-medium text-gray-500">
                {{ $breadcrumb }}
            </nav>
        @endisset

        @if ($badge)
            <div class="gallery-badge relative z-10 mb-5 inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-semibold uppercase tracking-widest">
                <span class="gallery-dot h-1.5 w-1.5 shrink-0 animate-pulse rounded-full"></span>
                {{ $badge }}
            </div>
        @endif

        @if ($customer?->logoUrl())
            <div class="relative z-10 mx-auto mb-5 flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl bg-white/5 shadow-lg ring-1 ring-white/10 sm:h-20 sm:w-20">
                <img
                    src="{{ $customer->logoUrl() }}"
                    alt="{{ $customer->name }}"
                    class="h-full w-full object-contain p-2.5"
                >
            </div>
        @endif

        <h1 class="gallery-title relative z-10 text-4xl uppercase leading-[0.95] sm:text-6xl lg:text-7xl">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="relative z-10 mx-auto mt-4 max-w-xl text-sm text-gray-400 sm:text-base">
                {{ $subtitle }}
            </p>
        @endif

        <div class="gallery-stripe absolute inset-x-0 bottom-0 h-1.5 opacity-80"></div>
    </header>

    <main class="relative z-10 mx-auto max-w-6xl px-4 pb-24 pt-24 sm:px-6">
        {{ $slot }}
    </main>

    <footer class="relative z-10 border-t border-white/10 px-6 py-10 text-center text-xs text-gray-500">
        {{ $customer?->name ?? 'MomentHub' }} &middot; galeria privada via MomentHub
    </footer>
</body>
</html>
