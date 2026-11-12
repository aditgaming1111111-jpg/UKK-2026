{{-- Menyediakan wadah bersama untuk kontrol penyaring data. --}}
@props(['label' => 'Filter'])
<label class="flex items-center gap-2 text-sm text-gray-600"><span>{{ $label }}</span>{{ $slot }}</label>
