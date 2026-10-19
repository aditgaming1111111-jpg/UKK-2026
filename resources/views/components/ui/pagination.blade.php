@props(['paginator' => null, 'current' => 1, 'last' => 1])

@if ($paginator && $paginator->total() > 0)
    <div class="flex flex-col gap-4 text-sm sm:flex-row sm:items-center sm:justify-between">
        <p class="text-gray-600" aria-live="polite">
            Menampilkan <span class="font-medium text-gray-900">{{ number_format($paginator->firstItem() ?? 0, 0, ',', '.') }}–{{ number_format($paginator->lastItem() ?? 0, 0, ',', '.') }}</span>
            dari <span class="font-medium text-gray-900">{{ number_format($paginator->total(), 0, ',', '.') }}</span> data
        </p>

        @if ($paginator->hasPages())
            @php
                $paginator->onEachSide(1);
                $elements = \Illuminate\Pagination\UrlWindow::make($paginator);
                $lastRenderedPage = 0;
            @endphp

            <nav aria-label="Navigasi halaman" class="flex flex-wrap items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="cursor-not-allowed rounded-lg border border-gray-200 px-3 py-2 text-gray-400">Sebelumnya</span>
                @else
                    <a rel="prev" href="{{ $paginator->previousPageUrl() }}" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 transition hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500">Sebelumnya</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($lastRenderedPage > 0 && $page > $lastRenderedPage + 1)
                                <span aria-hidden="true" class="px-2 py-2 text-gray-500">…</span>
                            @endif

                            @if ($page === $paginator->currentPage())
                                <span aria-current="page" class="min-w-10 rounded-lg bg-gray-900 px-3 py-2 text-center font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="min-w-10 rounded-lg border border-gray-300 px-3 py-2 text-center text-gray-700 transition hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500">{{ $page }}</a>
                            @endif

                            @php $lastRenderedPage = $page; @endphp
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a rel="next" href="{{ $paginator->nextPageUrl() }}" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 transition hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500">Berikutnya</a>
                @else
                    <span aria-disabled="true" class="cursor-not-allowed rounded-lg border border-gray-200 px-3 py-2 text-gray-400">Berikutnya</span>
                @endif
            </nav>
        @endif
    </div>
@elseif (! $paginator)
    <nav aria-label="Pagination" class="flex items-center justify-between gap-3 text-sm">
        <p class="text-gray-500">Halaman <span class="font-medium text-gray-800">{{ $current }}</span> dari {{ $last }}</p>
        <div class="flex gap-2"><x-ui.button variant="secondary" size="sm" :disabled="$current <= 1">Sebelumnya</x-ui.button><x-ui.button variant="secondary" size="sm" :disabled="$current >= $last">Berikutnya</x-ui.button></div>
    </nav>
@endif
