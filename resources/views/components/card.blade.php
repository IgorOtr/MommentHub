@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 '.($padding ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>
