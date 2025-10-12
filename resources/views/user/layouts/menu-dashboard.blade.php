<!-- Sidebar Menu -->
<div class="sidebar-menu">
    <div class="menu-section">
        <h6 class="menu-title">Tài khoản</h6>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('dashboard.profile') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.profile') }}" class="menu-item"><i class="bi bi-person me-2"></i>Hồ sơ cá nhân</a></li>
            <li class="{{ request()->routeIs('dashboard.bao-mat') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.bao-mat') }}" class="menu-item"><i class="bi bi-shield-check me-2"></i>Bảo mật</a></li>
            <li class="{{ request()->routeIs('dashboard.kyc') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.kyc') }}" class="menu-item"><i class="bi bi-file-earmark-check me-2"></i>KYC</a></li>
            <li class="{{ request()->routeIs('dashboard.thong-bao') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.thong-bao') }}" class="menu-item"><i class="bi bi-bell me-2"></i>Thông báo</a></li>
        </ul>
    </div>

    <div class="menu-section">
        <h6 class="menu-title">Tài chính</h6>
        <ul class="menu-list">

            <li class="{{ request()->routeIs('dashboard.ngan-hang') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.ngan-hang') }}" class="menu-item"><i class="bi bi-credit-card me-2"></i>Thẻ ngân hàng</a></li>
            <li class="{{ request()->routeIs('dashboard.tai-san') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.tai-san') }}" class="menu-item"><i class="bi bi-wallet2 me-2"></i>Tài sản</a></li>
            <li class="{{ request()->routeIs('dashboard.nap-tien') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.nap-tien') }}" class="menu-item"><i class="bi bi-wallet2 me-2"></i>Nạp tiền</a></li>
            <li class="{{ request()->routeIs('dashboard.rut-tien') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.rut-tien') }}" class="menu-item"><i class="bi bi-wallet2 me-2"></i>Rút tiền</a></li>
            <li class="{{ request()->routeIs('dashboard.lich-su-nap-rut') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.lich-su-nap-rut') }}" class="menu-item"><i class="bi bi-clock-history me-2"></i>Lịch sử nạp rút</a></li>
        </ul>
    </div>

    <div class="menu-section">
        <h6 class="menu-title">Đầu tư</h6>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('dashboard.dau-tu') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.dau-tu') }}" class="menu-item"><i class="bi bi-gem me-2"></i>Vàng đầu tư</a>
            </li>
            <li class="{{ request()->routeIs('dashboard.du-an-dau-tu-cua-toi') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.du-an-dau-tu-cua-toi') }}" class="menu-item"><i class="bi bi-pie-chart me-2"></i>Đầu tư của tôi</a></li>
        </ul>
    </div>
    <div class="menu-section">
        <h6 class="menu-title">Tiết kiệm</h6>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('dashboard.tiet-kiem') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.tiet-kiem') }}" class="menu-item"><i class="bi bi-graph-up-arrow me-2"></i>Gói tiết kiệm</a>
            </li>
            <li class="{{ request()->routeIs('dashboard.du-an-cua-toi') ? 'menu-item active' : '' }}"><a href="{{ route('dashboard.du-an-cua-toi') }}" class="menu-item"><i class="bi bi-pie-chart me-2"></i>Tiết kiệm của tôi</a></li>
        </ul>
    </div>

    @if(auth()->user()->role == 1)
    <div class="menu-section">
        <h6 class="menu-title">Quản trị</h6>
        <ul class="menu-list">
            <li><a href="{{ route('admin.index') }}" class="menu-item"><i class="bi bi-people me-2"></i>Quản lý hệ thống</a></li>
            <li><a href="#" class="menu-item"><i class="bi bi-gear me-2"></i>Cài đặt hệ thống</a></li>
            <li><a href="#" class="menu-item"><i class="bi bi-bar-chart me-2"></i>Thống kê</a></li>
        </ul>
    </div>
    @endif
</div>
