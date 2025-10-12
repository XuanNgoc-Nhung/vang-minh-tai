@extends('user.layouts.dashboard')

@section('title', 'Đầu tư vàng')

@section('content-dashboard')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bảng giá vàng đầu tư</h4>
                    <p class="text-muted mb-0">Cập nhật giá vàng mới nhất</p>
                </div>
                <div class="card-body">
                    @if(count($giaVang) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Tên sản phẩm</th>
                                        <th width="15%">Mã sản phẩm</th>
                                        <th width="15%">Giá mua (VNĐ)</th>
                                        <th width="15%">Giá bán (VNĐ)</th>
                                        <th width="15%">Thời gian</th>
                                        <th width="15%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($giaVang as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $item['san_pham']->ma_vang }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $item['san_pham']->ten_vang }}</span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">{{ number_format($item['gia_mua'], 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger fw-bold">{{ number_format($item['gia_ban'], 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($item['thoi_gian'])->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.chi-tiet-dau-tu', $item['san_pham']->ten_vang) }}" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-gold"></i> Đầu tư ngay
                                            </a>   
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5 class="alert-heading">Thông tin quan trọng:</h5>
                                    <ul class="mb-0">
                                        <li>Giá vàng được cập nhật liên tục theo thị trường</li>
                                        <li>Giá mua là giá bạn mua vàng từ hệ thống</li>
                                        <li>Giá bán là giá bạn bán vàng cho hệ thống</li>
                                        <li>Chênh lệch giá là cơ hội đầu tư của bạn</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-gold" style="font-size: 4rem; color: #f39c12;"></i>
                            </div>
                            <h4 class="text-muted">Chưa có dữ liệu giá vàng</h4>
                            <p class="text-muted">Hiện tại chưa có sản phẩm vàng nào được cập nhật giá.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ count($giaVang) }}</h4>
                            <p class="mb-0">Sản phẩm vàng</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-gold" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                @if(count($giaVang) > 0)
                                    {{ number_format(collect($giaVang)->avg('gia_mua'), 0, ',', '.') }}
                                @else
                                    0
                                @endif
                            </h4>
                            <p class="mb-0">Giá mua TB (VNĐ)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-trending-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                @if(count($giaVang) > 0)
                                    {{ number_format(collect($giaVang)->avg('gia_ban'), 0, ',', '.') }}
                                @else
                                    0
                                @endif
                            </h4>
                            <p class="mb-0">Giá bán TB (VNĐ)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-trending-down" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                @if(count($giaVang) > 0)
                                    {{ number_format(collect($giaVang)->avg('gia_ban') - collect($giaVang)->avg('gia_mua'), 0, ',', '.') }}
                                @else
                                    0
                                @endif
                            </h4>
                            <p class="mb-0">Chênh lệch TB (VNĐ)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-chart-line" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.table th {
    background-color: #343a40 !important;
    color: white !important;
    border: none !important;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.8rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-info .alert-heading {
    color: #0c5460;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}
</style>
@endsection
