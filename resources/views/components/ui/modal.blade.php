@props(['id', 'title', 'description' => null])
<dialog id="{{ $id }}" data-modal aria-labelledby="{{ $id }}-title"
    class="fixed inset-0 m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-md overflow-y-auto rounded-2xl border border-gray-200 bg-white p-0 text-gray-900 shadow-2xl">
    <div class="p-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="{{ $id }}-title" class="text-lg font-semibold">{{ $title }}</h2>
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
