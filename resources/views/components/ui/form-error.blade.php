@props(['name'])
@error($name)
    <p id="{{ $name }}-error" class="text-xs font-medium text-gray-700" role="alert">{{ $message }}</p>
@enderror
