@props(['name' => 'q', 'placeholder' => 'Cari…', 'value' => null, 'label' => 'Pencarian'])

<div {{ $attributes->class(['block min-w-0 space-y-1.5']) }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="relative">
        <x-ui.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400" />
        <input id="{{ $name }}" type="search" name="{{ $name }}" value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="block min-h-11 w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 motion-reduce:transition-none">
    </div>
</div>
