@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
$variants = [
    'primary' => 'bg-indigo-600 text-white hover:bg-indigo-500 focus-visible:outline-indigo-600',
    'secondary' => 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-500 focus-visible:outline-red-600',
    'ghost' => 'text-gray-600 hover:bg-gray-100 focus-visible:outline-gray-300 dark:text-gray-300 dark:hover:bg-gray-800',
    'muted' => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-200 focus-visible:outline-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:ring-gray-600 dark:hover:bg-gray-600',
];

$classes = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 disabled:cursor-not-allowed '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
