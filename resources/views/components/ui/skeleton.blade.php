@props(['lines' => 3])
<div aria-label="Memuat" aria-busy="true"
    {{ $attributes->class(['animate-pulse space-y-3 rounded-xl border border-gray-200 bg-white p-5 motion-reduce:animate-none']) }}>
    <span class="block h-4 w-1/3 rounded bg-gray-200"></span>
    @for ($i = 0; $i < $lines; $i++)
        <span @class([
            'block h-3 rounded bg-gray-100',
            'w-full' => $i % 2 === 0,
            'w-4/5' => $i % 2 !== 0,
        ])></span>
    @endfor
</div>
