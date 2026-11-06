{{-- Pagination ringkas, mengikuti token desain brand. --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman"
         class="flex flex-wrap items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost px-3 py-2 text-sm opacity-45" aria-disabled="true">‹ Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-ghost px-3 py-2 text-sm">‹ Sebelumnya</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-sm text-muted" aria-hidden="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page === $paginator->currentPage())
                        <span aria-current="page" class="btn btn-primary px-3 py-2 text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-ghost px-3 py-2 text-sm">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-ghost px-3 py-2 text-sm">Berikutnya ›</a>
        @else
            <span class="btn btn-ghost px-3 py-2 text-sm opacity-45" aria-disabled="true">Berikutnya ›</span>
        @endif
    </nav>
@endif
