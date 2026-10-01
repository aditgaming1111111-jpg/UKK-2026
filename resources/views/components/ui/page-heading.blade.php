@props(['title', 'description' => null])
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-gray-950 sm:text-3xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1.5 max-w-2xl text-sm text-gray-500">{{ $description }}</p>
        @endif
    </div>{{ $slot }}
</div>
