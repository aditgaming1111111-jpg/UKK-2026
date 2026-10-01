@props(['variant' => 'primary', 'type' => 'button', 'href' => null, 'size' => 'md'])
@php
    $styles = [
        'primary' => 'bg-gray-900 text-white hover:bg-gray-700 focus-visible:outline-gray-900',
        'secondary' => 'border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 focus-visible:outline-gray-700',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus-visible:outline-gray-700',
    ];
    $sizes = ['sm' => 'px-3 py-2 text-xs', 'md' => 'px-4 py-2.5 text-sm'];
    $classes =
        'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50 motion-reduce:transition-none ' .
        ($styles[$variant] ?? $styles['primary']) .
        ' ' .
        ($sizes[$size] ?? $sizes['md']);
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
