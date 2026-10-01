@props(['padding' => true])
<section {{ $attributes->class(['rounded-2xl border border-gray-200 bg-white shadow-sm', 'p-5 sm:p-6' => $padding]) }}>
    {{ $slot }}</section>
