@props(['id', 'title', 'description' => null])
<dialog id="{{ $id }}" data-modal
    class="w-[calc(100%-2rem)] max-w-md rounded-2xl border border-gray-200 p-0 text-gray-900 shadow-2xl backdrop:bg-gray-950/40">
    <div class="p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
                @if ($description)
                    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
                @endif
            </div><button type="button" data-modal-close
                class="rounded-md p-1 text-gray-500 hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-gray-900"
                aria-label="Tutup"><x-ui.icon name="close" class="size-5" /></button>
        </div>
        <div class="mt-5">{{ $slot }}</div>
    </div>
</dialog>
