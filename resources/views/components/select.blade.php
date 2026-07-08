@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5 dark:text-gray-300">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-gray-300 bg-white text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 '.($errors->has($name) ? 'border-red-500' : '')]) }}
    >
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $selected) === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
