@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Lịch sử nạp rút tiền
                    </h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.lich-su-nap-rut') }}" class="mb-3">
                        <div class="form row align-items-center">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <select name="loai" class="form-control">
                                    <option value="">Tất cả loại giao dịch</option>
                                    <option value="nap" {{ request('loai') === 'nap' ? 'selected' : '' }}>Nạp tiền</option>
                                    <option value="rut" {{ request('loai') === 'rut' ? 'selected' : '' }}>Rút tiền</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nhập từ khoá...">
                            </div>
                            <div class="col-md-3 col-sm-12 mb-2">
                                    <button type="submit" class="btn btn-primary btn-gap"><i class="fas fa-search mr-1"></i>Lọc</button>
                                    <a href="{{ route('dashboard.lich-su-nap-rut') }}" class="btn btn-secondary"><i class="fas fa-undo mr-1"></i>Reset</a>
                            </div>
                        </div>
                    </form>
                    @if($napRutHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="width: 70px">STT</th>
                                        <th>Mã giao dịch</th>
                                        <th>Loại</th>
                                        <th>Số tiền</th>
                                        <th>Ngân hàng</th>
                                        <th>Số tài khoản</th>
                                        <th>Nội dung</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($napRutHistory as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ ($napRutHistory->currentPage() - 1) * $napRutHistory->perPage() + $index + 1 }}</td>
                                            <td>
                                                <span class="badge badge-secondary">#{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                @if($item->loai == 'nap')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-arrow-down mr-1"></i>Nạp tiền
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-arrow-up mr-1"></i>Rút tiền
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-primary">
                                                    {{ number_format($item->so_tien, 0, ',', '.') }} VND
                                                </span>
                                            </td>
                                            <td>{{ $item->ngan_hang }}</td>
                                            <td>
                                                <code>{{ $item->so_tai_khoan }}</code>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $item->noi_dung }}</span>
                                            </td>
                                            <td>
                                                @if($item->trang_thai == 0)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock mr-1"></i>Chờ xử lý
                                                    </span>
                                                @elseif($item->trang_thai == 1)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check mr-1"></i>Thành công
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times mr-1"></i>Từ chối
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $item->created_at->format('d/m/Y H:i:s') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $napRutHistory->appends(request()->only(['loai','search']))->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có giao dịch nào</h5>
                            <p class="text-muted">Bạn chưa có lịch sử nạp rút tiền nào.</p>
                            <a href="{{ route('dashboard.nap-tien') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>Nạp tiền ngay
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
    
    code {
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
    
    .font-weight-bold {
        font-weight: 600 !important;
    }
    
    /* Space between filter and reset buttons */
    .btn-gap {
        margin-right: 12px;
    }
</style>
@endpush
