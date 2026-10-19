@props(['label', 'name', 'rows' => 4, 'required' => false])
<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }} @if ($required)
            <span class="text-gray-500">*</span>
        @endif
    </label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
        @class([
            'block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-gray-500 focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 motion-reduce:transition-none',
            'border-gray-300' => !$errors->has($name),
            'border-gray-500' => $errors->has($name),
        ]) {{ $attributes }}>{{ old($name, $slot) }}</textarea>
    <x-ui.form-error :name="$name" />
</div>
