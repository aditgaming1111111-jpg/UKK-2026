@props(['items' => []])
<nav aria-label="Breadcrumb" class="mb-3">
    <ol class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
        @foreach ($items as $index => $item)
            <li class="flex items-center gap-2">
                @if ($index > 0)
                    <span aria-hidden="true">/</span>
                    @endif @if (isset($item['url']) && !$loop->last)
                        <a class="hover:text-gray-900 focus-visible:outline-2 focus-visible:outline-gray-900"
                        href="{{ $item['url'] }}">{{ $item['label'] }}</a>@else<span
                            @if ($loop->last) aria-current="page" class="font-medium text-gray-800" @endif>{{ $item['label'] }}</span>
                    @endif
            </li>
        @endforeach
    </ol>
</nav>
