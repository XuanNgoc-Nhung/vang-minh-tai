@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-bell me-2"></i>
                        Thông báo
                    </h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.thong-bao') }}" class="mb-3">
                        <div class="form row align-items-center">
                            <div class="col-md-4 col-sm-8 mb-2">
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo tiêu đề hoặc nội dung...">
                            </div>
                            <div class="col-md-3 col-sm-4 mb-2">
                                <button type="submit" class="btn btn-primary btn-gap"><i class="fas fa-search me-1"></i>Lọc</button>
                                <a href="{{ route('dashboard.thong-bao') }}" class="btn btn-secondary"><i class="fas fa-undo me-1"></i>Reset</a>
                            </div>
                        </div>
                    </form>

                    @if($thongBao->count() > 0)
                        <div class="accordion" id="accordionThongBao">
                            @foreach($thongBao as $index => $tb)
                                <div class="accordion-item border-noti" style="border-color: #c5c5c5 !important; border-radius: 6px !important;">
                                    <h2 class="accordion-header" id="heading{{ $tb->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $tb->id }}" aria-expanded="false" aria-controls="collapse{{ $tb->id }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="me-3 fw-semibold">
                                                    <img src="{{ asset('noti-icon.gif') }}" alt="Noti" class="img-fluid me-2" style="width: 20px; height: 20px;">
                                                    {{ $index + 1 }}. {{ $tb->tieu_de }}</div>
                                                {{-- <small class="text-muted">#{{ str_pad($tb->id, 6, '0', STR_PAD_LEFT) }}</small> --}}
                                                <small class="text-muted">{{ $tb->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $tb->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $tb->id }}" data-bs-parent="#accordionThongBao">
                                        <div class="accordion-body">
                                            <div class="mb-2 text-muted small">Mã thông báo: <code>#NOTI{{ $tb->id }}</code></div>
                                            <div class="mb-2">{!! nl2br(e($tb->noi_dung)) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $thongBao->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có thông báo nào</h5>
                            <p class="text-muted">Khi có thông báo mới, chúng tôi sẽ hiển thị tại đây.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .accordion-button { font-weight: 500; }
    .accordion-item { border: 1px solid #ced4da; border-radius: .5rem; overflow: hidden; margin-bottom: .75rem; }
        .border-noti { border: 1.5px solid #303133 !important; border-radius: 6px; }
    .accordion-item .accordion-button { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; }
    .accordion-item .accordion-button.collapsed { border-bottom-color: #adb5bd; }
    .accordion-button:focus { box-shadow: none; }
    .accordion-body { background: #fff; }
    .btn-gap { margin-right: 12px; }
    code { background-color: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; }
    .card-title i { vertical-align: -2px; }
    @media (prefers-color-scheme: dark) {
        .accordion-item { border-color: rgba(255,255,255,.2); }
            .border-noti { border-color: rgba(255,255,255,.5) !important; }
        .accordion-item .accordion-button { background-color: rgba(255,255,255,.04); border-bottom-color: rgba(255,255,255,.08); }
        .accordion-item .accordion-button.collapsed { border-bottom-color: rgba(255,255,255,.24); }
        code { background-color: rgba(255,255,255,.08); }
    }
</style>
@endpush

