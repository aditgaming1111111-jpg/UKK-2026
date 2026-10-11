@props(['id' => 'app-toast', 'message' => 'Perubahan berhasil disimpan.'])
<div id="{{ $id }}" data-toast role="status" aria-live="polite"
    class="pointer-events-none fixed right-4 top-4 z-50 flex max-w-sm items-center gap-3 rounded-xl bg-gray-900 px-4 py-3 text-sm text-white opacity-100 shadow-xl transition duration-200 motion-reduce:transition-none">
    <x-ui.icon name="check" class="size-5 shrink-0" /><span>{{ $slot->isNotEmpty() ? $slot : $message }}</span><button type="button"
        data-toast-close class="pointer-events-auto ml-2 rounded p-1 hover:bg-white/10"
        aria-label="Tutup notifikasi"><x-ui.icon name="close" class="size-4" /></button></div>
