@props(['title' => 'Terjadi kendala', 'description' => 'Permintaan belum dapat diproses. Silakan coba kembali.'])
<div role="alert" {{ $attributes->class(['rounded-xl border border-gray-300 bg-gray-100 p-5']) }}>
    <p class="text-sm font-semibold text-gray-900">{{ $title }}</p>
    <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>{{ $slot }}
</div>
