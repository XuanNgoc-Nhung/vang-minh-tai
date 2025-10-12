@extends('user.layouts.dashboard')

@section('content-dashboard')
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-4 text-center">
                                    <div class="user-avatar-large">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h4 class="mb-3">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>Thông tin cá nhân
                                    </h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Họ và tên</small>
                                                        <div class="fw-medium">{{ auth()->user()->name }}</div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Họ và tên: </span>
                                                        <span class="fw-medium">{{ auth()->user()->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-envelope me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Email</small>
                                                        <div class="fw-medium">{{ auth()->user()->email }}</div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Email: </span>
                                                        <span class="fw-medium">{{ auth()->user()->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-telephone me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Số điện thoại</small>
                                                        <div class="fw-medium">{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Số điện thoại: </span>
                                                        <span class="fw-medium">{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Ngày sinh</small>
                                                        <div class="fw-medium">{{ auth()->user()->date_of_birth ?? 'Chưa cập nhật' }}</div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Ngày sinh: </span>
                                                        <span class="fw-medium">{{ auth()->user()->date_of_birth ?? 'Chưa cập nhật' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-shield-check me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Vai trò</small>
                                                        <div>
                                                            <span class="badge bg-primary">
                                                                @if(auth()->user()->role == 1)
                                                                Quản trị viên
                                                                @else
                                                                Thành viên
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Vai trò: </span>
                                                        <span class="badge bg-primary">
                                                            @if(auth()->user()->role == 1)
                                                            Quản trị viên
                                                            @else
                                                            Thành viên
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock me-2 text-muted"></i>
                                                <div class="w-100">
                                                    <!-- Desktop: 2 rows -->
                                                    <div class="d-none d-md-block">
                                                        <small class="text-muted">Tham gia từ</small>
                                                        <div class="fw-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</div>
                                                    </div>
                                                    <!-- Mobile/Tablet: 1 row -->
                                                    <div class="d-md-none">
                                                        <span class="text-muted">Tham gia từ: </span>
                                                        <span class="fw-medium">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Stats Cards -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Tổng đầu tư</div>
                            <small class="text-muted">VND</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Lợi nhuận</div>
                            <small class="text-muted">VND</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Tiết kiệm</div>
                            <small class="text-muted">VND</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Tổng tài sản</div>
                            <small class="text-muted">VND</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Tài khoản</div>
                            <small class="text-muted">Số dư</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card dashboard-card stat-card compact-stat">
                        <div class="card-body text-center py-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Giao dịch</div>
                            <small class="text-muted">Hôm nay</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-8 mb-3">
                    <div class="card dashboard-card compact-card">
                        <div class="card-header py-2">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>Hoạt động gần đây
                            </h6>
                        </div>
                        <div class="card-body py-3">
                            <div class="text-center py-3">
                                <i class="bi bi-inbox display-6 text-muted"></i>
                                <p class="text-muted mt-2 mb-2">Chưa có hoạt động nào</p>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Bắt đầu đầu tư
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-4 mb-3">
                    <div class="card dashboard-card compact-card">
                        <div class="card-header py-2">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2"></i>Thao tác nhanh
                            </h6>
                        </div>
                        <div class="card-body py-3">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-graph-up-arrow me-1"></i>Đầu tư mới
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-piggy-bank me-1"></i>Tiết kiệm
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-wallet2 me-1"></i>Nạp tiền
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Rút tiền
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Investment Portfolio -->
                <div class="col-lg-6 mb-3">
                    <div class="card dashboard-card compact-card">
                        <div class="card-header py-2">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-pie-chart me-2"></i>Danh mục đầu tư
                            </h6>
                        </div>
                        <div class="card-body py-3">
                            <div class="text-center py-3">
                                <i class="bi bi-pie-chart display-6 text-muted"></i>
                                <p class="text-muted mt-2 mb-2">Chưa có danh mục đầu tư</p>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Tạo danh mục
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Savings Goals -->
                <div class="col-lg-6 mb-3">
                    <div class="card dashboard-card compact-card">
                        <div class="card-header py-2">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-bullseye me-2"></i>Mục tiêu tiết kiệm
                            </h6>
                        </div>
                        <div class="card-body py-3">
                            <div class="text-center py-3">
                                <i class="bi bi-bullseye display-6 text-muted"></i>
                                <p class="text-muted mt-2 mb-2">Chưa có mục tiêu tiết kiệm</p>
                                <a href="#" class="btn btn-success btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Đặt mục tiêu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
