@props(['variant' => 'default'])
@php($styles = ['default' => 'bg-gray-100 text-gray-700', 'dark' => 'bg-gray-900 text-white', 'outline' => 'border border-gray-300 bg-white text-gray-700'])
<span
    {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium', $styles[$variant] ?? $styles['default']]) }}>{{ $slot }}</span>
