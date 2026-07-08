@props(['variant' => 'success'])

@php
$variants = [
    'success' => 'bg-green-50 text-green-800 ring-1 ring-green-200 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
    'error' => 'bg-red-50 text-red-800 ring-1 ring-red-200 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
    'info' => 'bg-blue-50 text-blue-800 ring-1 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg px-4 py-3 text-sm '.($variants[$variant] ?? $variants['info'])]) }}>
    {{ $slot }}
</div>
