{{-- Membungkus konten terkait dalam wadah kartu dengan opsi jarak dalam. --}}
@props(['padding' => true])
{{-- Bagian ini mengelompokkan informasi yang saling berkaitan. --}}
<section {{ $attributes->class(['rounded-2xl border border-gray-200 bg-white shadow-sm', 'p-5 sm:p-6' => $padding]) }}>
    {{ $slot }}</section>
