@props(['current' => 1, 'last' => 1])
<nav aria-label="Pagination" class="flex items-center justify-between gap-3 text-sm">
    <p class="text-gray-500">Halaman <span class="font-medium text-gray-800">{{ $current }}</span> dari
        {{ $last }}</p>
    <div class="flex gap-2"><x-ui.button variant="secondary" size="sm"
            :disabled="$current <= 1">Sebelumnya</x-ui.button><x-ui.button variant="secondary" size="sm"
            :disabled="$current >= $last">Berikutnya</x-ui.button></div>
</nav>
