@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination pagination-sm mb-0 justify-content-end align-items-center" style="gap:.25rem;">
            {{-- Inline info: range and page position --}}
            <li class="page-item disabled d-none d-md-block" aria-disabled="true">
                <span class="page-link" style="opacity:.8;">
                    {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} • Trang {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>
            </li>
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="Trang đầu">
                    <span class="page-link" aria-hidden="true">««</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" rel="first" aria-label="Trang đầu">««</a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="Trang trước">
                    <span class="page-link" aria-hidden="true">«</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">«</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">»</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="Trang sau">
                    <span class="page-link" aria-hidden="true">»</span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @php($last = $paginator->lastPage())
            @if ($paginator->currentPage() == $last)
                <li class="page-item disabled" aria-disabled="true" aria-label="Trang cuối">
                    <span class="page-link" aria-hidden="true">»»</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}" aria-label="Trang cuối">»»</a>
                </li>
            @endif
        </ul>
    </nav>
@endif


