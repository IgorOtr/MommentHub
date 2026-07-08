@props(['variant' => 'gray'])

@php
$variants = [
    'gray' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300',
    'green' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
    'red' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
    'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
    'yellow' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold '.($variants[$variant] ?? $variants['gray'])]) }}>
    {{ $slot }}
</span>
