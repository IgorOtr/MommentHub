@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5 dark:text-gray-300">
            {{ $label }}
            @if ($attributes->has('required') && $attributes->get('required') !== false)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if ($type === 'file')
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge(['class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:bg-gray-100 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:file:bg-gray-600 dark:file:text-gray-200 dark:hover:file:bg-gray-500 '.($errors->has($name) ? 'border-red-500' : '')]) }}
        />
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-500 '.($errors->has($name) ? 'border-red-500' : '')]) }}
        />
    @endif

    @if ($hint)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
