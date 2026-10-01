@props(['label', 'value', 'note' => null, 'icon' => 'chart'])
<x-ui.card class="h-full">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950">{{ $value }}</p>
            @if ($note)
                <p class="mt-2 text-xs text-gray-500">{{ $note }}</p>
            @endif
        </div>
        <span class="grid size-10 place-items-center rounded-xl bg-gray-100 text-gray-700"><x-ui.icon :name="$icon"
                class="size-5" /></span>
    </div>
</x-ui.card>
