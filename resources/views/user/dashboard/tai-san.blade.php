@extends('user.layouts.dashboard')

@section('title', 'Tài sản')

@section('content-dashboard')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Tài sản của tôi</h2>
                    <p class="text-muted mb-0">Tổng quan về tài sản hiện tại</p>
                </div>
                <div class="text-end">
                    <h3 class="text-primary mb-0">{{ number_format($tongTaiSan, 0, ',', '.') }} VNĐ</h3>
                    <small class="text-muted">Tổng tài sản</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài sản hiện tại -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary ">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-0">Tài sản hiện tại</h5>
                            <small>Số dư khả dụng</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <h2 class="text-success mb-0">{{ number_format($soDuHienTai, 0, ',', '.') }} VNĐ</h2>
                                    <small class="text-muted">Số dư hiện tại</small>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-success fs-6">Khả dụng</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-grid gap-2">
                                <a href="{{ route('dashboard.nap-tien') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Nạp tiền
                                </a>
                                <a href="{{ route('dashboard.rut-tien') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-dash-circle me-2"></i>Rút tiền
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài sản đầu tư và Tiết kiệm -->
    <div class="row">
        <!-- Tài sản đầu tư -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gem me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-0">Tài sản đầu tư</h5>
                            <small>Vàng và các khoản đầu tư</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-warning mb-1">{{ number_format($tongGiaTriDauTu, 0, ',', '.') }} VNĐ</h2>
                        <small class="text-muted">Tổng giá trị đầu tư</small>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $soGiaoDichDauTu }}</h4>
                                <small class="text-muted">Giao dịch</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-1">{{ number_format($tongGiaTriDauTu / max($soGiaoDichDauTu, 1), 0, ',', '.') }}</h4>
                            <small class="text-muted">TB/Giao dịch</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard.du-an-dau-tu-cua-toi') }}" class="btn btn-warning w-100">
                            <i class="bi bi-gem me-2"></i>Xem chi tiết đầu tư
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tài sản tiết kiệm -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info ">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-0">Tài sản tiết kiệm</h5>
                            <small>Gói tiết kiệm đang hoạt động</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-info mb-1">{{ number_format($tongTienTietKiem, 0, ',', '.') }} VNĐ</h2>
                        <small class="text-muted">Tổng tiền tiết kiệm</small>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $soGoiTietKiem }}</h4>
                                <small class="text-muted">Gói tiết kiệm</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ number_format($tongTienTietKiem / max($soGoiTietKiem, 1), 0, ',', '.') }}</h4>
                            <small class="text-muted">TB/Gói</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard.du-an-cua-toi') }}" class="btn btn-info w-100">
                            <i class="bi bi-graph-up-arrow me-2"></i>Xem chi tiết tiết kiệm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Phân bổ tài sản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $tongTaiSan > 0 ? ($soDuHienTai / $tongTaiSan) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-success">Tài sản hiện tại</h6>
                                <p class="text-muted mb-0">{{ $tongTaiSan > 0 ? number_format(($soDuHienTai / $tongTaiSan) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ $tongTaiSan > 0 ? ($tongGiaTriDauTu / $tongTaiSan) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-warning">Tài sản đầu tư</h6>
                                <p class="text-muted mb-0">{{ $tongTaiSan > 0 ? number_format(($tongGiaTriDauTu / $tongTaiSan) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ $tongTaiSan > 0 ? ($tongTienTietKiem / $tongTaiSan) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-info">Tài sản tiết kiệm</h6>
                                <p class="text-muted mb-0">{{ $tongTaiSan > 0 ? number_format(($tongTienTietKiem / $tongTaiSan) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Các hành động nhanh -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Hành động nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.nap-tien') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle d-block fs-3 mb-2"></i>
                                <span>Nạp tiền</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.dau-tu') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-gem d-block fs-3 mb-2"></i>
                                <span>Đầu tư vàng</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.tiet-kiem') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-graph-up-arrow d-block fs-3 mb-2"></i>
                                <span>Tiết kiệm</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.lich-su-nap-rut') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-clock-history d-block fs-3 mb-2"></i>
                                <span>Lịch sử</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
