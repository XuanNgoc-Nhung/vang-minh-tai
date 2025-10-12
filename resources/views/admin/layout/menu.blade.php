<aside class="cms-sidebar">
  <nav class="cms-nav nav flex-column">
    <a class="nav-link @yield('nav.dashboard_active')" href="{{ route('admin.index') }}" title="Bảng điều khiển" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-grid"></i> <span class="label">Bảng điều khiển</span>
    </a>
    <a class="nav-link" href="{{ route('dashboard') }}" title="Trang người dùng" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-person"></i> <span class="label">Trang người dùng</span>
    </a>
    <a class="nav-link @yield('nav.users_active')" href="{{ route('admin.users') }}" title="Người dùng" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-people"></i> <span class="label">Người dùng</span>
    </a>
    <a class="nav-link @yield('nav.san-pham-tiet-kiem_active')" href="{{ route('admin.san-pham-tiet-kiem') }}" title="Sản phẩm tiết kiệm" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-cash-coin"></i> <span class="label">Sản phẩm tiết kiệm</span>
    </a>
    <a class="nav-link @yield('nav.lich-su-tiet-kiem_active')" href="{{ route('admin.lich-su-tiet-kiem') }}" title="Lịch sử tiết kiệm" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-cash-coin"></i> <span class="label">Lịch sử tiết kiệm</span>
    </a>
    {{-- <a class="nav-link @yield('nav.vang-dau-tu_active')" href="{{ route('admin.vang-dau-tu') }}" title="Vàng đầu tư" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-cash-coin"></i> <span class="label">Vàng đầu tư</span>
    </a> --}}
    <a class="nav-link @yield('nav.gia-vang_active')" href="{{ route('admin.gia-vang') }}" title="Giá vàng" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-cash-coin"></i> <span class="label">Giá vàng</span>
    </a>
    <a class="nav-link @yield('nav.dau-tu_active')" href="{{ route('admin.dau-tu') }}" title="Đầu tư" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-graph-up"></i> <span class="label">Đầu tư</span>
    </a>
    <a class="nav-link @yield('nav.ngan-hang-nap-tien_active')" href="{{ route('admin.ngan-hang-nap-tien') }}" title="Ngân hàng nạp tiền" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-credit-card"></i> <span class="label">Ngân hàng nạp tiền</span>
    </a>
    <a class="nav-link @yield('nav.nap-rut_active')" href="{{ route('admin.nap-rut') }}" title="Giao dịch" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-cash-coin"></i> <span class="label">Nạp rút</span>
    </a>
    <a class="nav-link @yield('nav.thong-bao_active')" href="{{ route('admin.thong-bao') }}" title="Thông báo" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-bell"></i> <span class="label">Thông báo</span>
    </a>
    <a class="nav-link @yield('nav.settings_active')" href="{{ route('admin.cau-hinh') }}" title="Cấu hình" data-bs-toggle="tooltip" data-bs-placement="right">
      <i class="bi bi-gear"></i> <span class="label">Cấu hình</span>
    </a>
    <div class="border-top border-light opacity-50 my-2"></div>
    <form method="post" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-link bg-transparent border-0 w-100 d-flex align-items-center gap-2 logout-btn" title="Đăng xuất" data-bs-toggle="tooltip" data-bs-placement="right">
        <i class="bi bi-box-arrow-right"></i> <span class="label">Đăng xuất</span>
      </button>
    </form>
  </nav>
</aside>


