@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Dự án của tôi
                    </h3>
                </div>
                <div class="card-body">
                    {{-- <form method="GET" action="{{ route('dashboard.du-an-cua-toi') }}" class="mb-3">
                        <div class="form row align-items-center">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <select name="trang_thai" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="dang_dau_tu"
                                        {{ request('trang_thai') === 'dang_dau_tu' ? 'selected' : '' }}>Đang đầu tư
                                    </option>
                                    <option value="da_hoan_thanh"
                                        {{ request('trang_thai') === 'da_hoan_thanh' ? 'selected' : '' }}>Đã hoàn thành
                                    </option>
                                    <option value="da_huy" {{ request('trang_thai') === 'da_huy' ? 'selected' : '' }}>Đã
                                        huỷ</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Tìm theo mã dự án / sản phẩm...">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-2">
                                <button type="submit" class="btn btn-primary btn-gap"><i
                                        class="fas fa-search mr-1"></i>Lọc</button>
                                <a href="{{ route('dashboard.du-an-cua-toi') }}" class="btn btn-secondary"><i
                                        class="fas fa-undo mr-1"></i>Reset</a>
                            </div>
                        </div>
                    </form> --}}

                    @if(isset($duAns) && $duAns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 70px">STT</th>
                                    <th>Sản phẩm</th>
                                    <th>Số tiền</th>
                                    <th>Hoa hồng</th>
                                    <th>Thực nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($duAns as $index => $duAn)
                                <tr>
                                    <td class="text-center">
                                        {{ ($duAns->currentPage() - 1) * $duAns->perPage() + $index + 1 }}</td>
                                    <td>
                                        <span class="">{{ optional($duAn->sanPham)->ten ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class=" text-primary">{{ number_format($duAn->so_tien ?? 0, 0, ',', '.') }}
                                            VND</span>
                                    </td>
                                    <td>
                                        <span> {{ number_format($duAn->hoa_hong ?? 0, 0, ',', '.') }} vnđ</span>
                                        <span
                                            style="font-size: 12px; color: #ea750e;">({{ optional($duAn->sanPham)->lai_suat ? number_format($duAn->sanPham->lai_suat, 2, ',', '.') . '%' : '—' }})</span>
                                    </td>
                                    <td>
                                        <span
                                            class=" text-success">{{ number_format($duAn->so_tien + $duAn->hoa_hong ?? 0, 0, ',', '.') }}
                                            VND</span>
                                    </td>
                                    <td>
                                        @php($status = $duAn->trang_thai ?? 'dang_dau_tu')
                                        @if($status === 'dang_dau_tu' || $status === 1)
                                        <span class="badge badge-success"><i class="fas fa-clock mr-1"></i>Đang đầu
                                            tư</span>
                                        @elseif($status === 'da_hoan_thanh' || $status === 2)
                                        <span class="badge badge-danger"><i class="fas fa-check mr-1"></i>Đã hoàn
                                            thành</span>
                                        @else
                                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Đã huỷ</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="">{{ $duAn->so_chu_ky ?? '—' }} tháng</span>
                                        <small class="text-muted" style="font-size: 12px; color: #0400ff;">{{ optional($duAn->ngay_bat_dau)->format('d/m/Y') }} - {{ optional($duAn->ngay_ket_thuc)->format('d/m/Y') ?? '—' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $duAns->appends(request()->only(['trang_thai','search']))->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có dự án nào</h5>
                        <p class="text-muted">Bạn chưa tham gia dự án đầu tư nào.</p>
                        <a href="{{ route('dashboard.tiet-kiem') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Đầu tư ngay
                        </a>
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
    .table th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
        color: #495057;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .table-responsive {
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    . {
        font-weight: 600 !important;
    }

    .btn-gap {
        margin-right: 12px;
    }

</style>
@endpush
