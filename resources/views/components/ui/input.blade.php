@props(['label', 'name', 'type' => 'text', 'value' => null, 'hint' => null, 'required' => false])
<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }} @if ($required)
            <span class="text-gray-500">*</span>
        @endif
    </label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        @required($required) @class([
            'block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-gray-500 focus:ring-2 focus:ring-gray-200',
            'border-gray-300' => !$errors->has($name),
            'border-gray-500' => $errors->has($name),
        ]) {{ $attributes }}>
    @if ($hint && !$errors->has($name))
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif
    <x-ui.form-error :name="$name" />
</div>
