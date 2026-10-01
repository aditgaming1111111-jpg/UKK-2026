@props(['name' => 'circle'])

@php
    $icons = [
        'grid' => 'M4 4h6v6H4z M14 4h6v6h-6z M4 14h6v6H4z M14 14h6v6h-6z',
        'users' =>
            'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M20 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',
        'user' => 'M20 21a8 8 0 0 0-16 0 M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10',
        'user-check' => 'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M16 11l2 2 4-4',
        'calendar' =>
            'M8 2v4 M16 2v4 M3 10h18 M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2z M8 14h.01 M12 14h.01 M16 14h.01 M8 18h.01 M12 18h.01',
        'tag' => 'm20.59 13.41-7.17 7.18a2 2 0 0 1-2.83 0L3 13V3h10l7.59 7.59a2 2 0 0 1 0 2.82z M7.5 7.5h.01',
        'warning' =>
            'M10.3 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.7 3.86a2 2 0 0 0-3.4 0z M12 9v4 M12 17h.01',
        'document' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M8 13h8 M8 17h8',
        'chart' => 'M3 3v18h18 M18 17V9 M13 17V5 M8 17v-3',
        'clock' => 'M12 8v4l3 2 M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0z',
        'plus' => 'M12 5v14 M5 12h14',
        'logout' => 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M16 17l5-5-5-5 M21 12H9',
        'search' => 'm21 21-4.34-4.34 M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0z',
        'close' => 'M18 6 6 18 M6 6l12 12',
        'check' => 'm5 12 4 4L19 6',
        'inbox' => 'M4 4h16v16H4z M4 13h4l2 3h4l2-3h4',
        'chevron-down' => 'm6 9 6 6 6-6',
        'circle' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z',
    ];
@endphp

<svg
    {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.7', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    <path d="{{ $icons[$name] ?? $icons['circle'] }}" />
</svg>
