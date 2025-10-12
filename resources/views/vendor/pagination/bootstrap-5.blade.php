@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Phân trang" class="w-100">
        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 w-100">
            <div class="small text-muted">
                @php
                    $from = $paginator->firstItem();
                    $to = $paginator->lastItem();
                    $total = $paginator->total();
                @endphp
                @if($total > 0)
                    Hiển thị {{ $from }}–{{ $to }} trên {{ $total }} kết quả
                @else
                    Không có kết quả
                @endif
                <span class="ms-2">(Trang {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }})</span>
            </div>

            <ul class="pagination mb-0 ms-sm-auto">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="Trước">
                        <span class="page-link" aria-hidden="true">&laquo; Trước</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trước">&laquo; Trước</a>
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
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Sau">Sau &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="Sau">
                        <span class="page-link" aria-hidden="true">Sau &raquo;</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif


