@props(['title' => 'Belum ada data', 'description' => 'Data yang Anda cari belum tersedia.'])
<div {{ $attributes->class(['rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center']) }}>
    <span
        class="mx-auto grid size-11 place-items-center rounded-full bg-white text-gray-500 ring-1 ring-gray-200"><x-ui.icon
            name="inbox" class="size-5" /></span>
    <h3 class="mt-4 text-sm font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">{{ $description }}</p>{{ $slot }}
</div>
