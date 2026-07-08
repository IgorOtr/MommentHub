<div class="overflow-x-auto rounded-xl ring-1 ring-gray-200 dark:ring-gray-700">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800']) }}>
        {{ $slot }}
    </table>
</div>
