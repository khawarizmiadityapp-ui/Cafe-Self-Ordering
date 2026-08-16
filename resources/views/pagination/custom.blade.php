@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination">
        <div class="pagination-info">
            Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }} hasil
        </div>

        <div class="pagination-links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-btn disabled" aria-disabled="true"><span>{{ $element }}</span></span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-btn active" aria-current="page"><span>{{ $page }}</span></span>
                        @else
                            <a href="{{ $url }}" class="pagination-btn"><span>{{ $page }}</span></a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next">
                    <span>Berikutnya</span>
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true">
                    <span>Berikutnya</span>
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
