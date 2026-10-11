@props(['name' => 'q', 'placeholder' => 'Cari…', 'value' => null])
<label class="relative block self-start"><span class="sr-only">{{ $placeholder }}</span><x-ui.icon name="search"
        class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400" /><input type="search"
        name="{{ $name }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm outline-none placeholder:text-gray-400 focus:border-gray-500 focus:ring-2 focus:ring-gray-200']) }}></label>
