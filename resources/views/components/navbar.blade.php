<!-- Navbar Component -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
                </li>
                @if(request()->routeIs('home'))
                <li class="nav-item me-3"><a class="nav-link text-white" href="#gia-vang">Đầu tư</a></li>
                <li class="nav-item me-3"><a class="nav-link text-white" href="#du-an">Tiết kiệm</a></li>
                <li class="nav-item me-3"><a class="nav-link text-white" href="#ve-chung-toi">Về chúng tôi</a></li>
                <li class="nav-item me-3"><a class="nav-link text-white" href="#loi-the">Lợi thế</a></li>
                <li class="nav-item me-3"><a class="nav-link text-white" href="#buoc-thanh-vien">Thành viên</a></li>
                <li class="nav-item me-3"><a class="nav-link text-white" href="#faq">Hỏi đáp</a></li>
                @endif
                @if(request()->routeIs('dashboard*'))
                {{-- Menu chỉ hiển thị khi đang ở trong dashboard --}}
                <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard.tiet-kiem') ? 'active' : '' }}" href="{{ route('dashboard.tiet-kiem') }}">Đầu tư</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard.nap-tien') ? 'active' : '' }}" href="{{ route('dashboard.nap-tien') }}">Nạp tiền</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard.rut-tien') ? 'active' : '' }}" href="{{ route('dashboard.rut-tien') }}">Rút tiền</a>
                </li>
                {{-- <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard.du-an-cua-toi') ? 'active' : '' }}" href="{{ route('dashboard.du-an-cua-toi') }}">Gói tiết kiệm</a>
                </li> --}}
                @else
                {{-- Menu hiển thị khi không ở dashboard --}}
                {{-- <li class="nav-item me-3">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard.tiet-kiem') ? 'active' : '' }}" href="{{ route('dashboard.tiet-kiem') }}">Gói tiết kiệm</a>
                </li> --}}
                @endif
                @if(auth()->check())    
                <li class="nav-item me-3">
                    <span class="btn btn-info btn-sm px-3 fw-semibold">
                        $ {{ number_format(optional(auth()->user()->profile)->so_du ?? 0, 0, ',', '.') }}
                    </span>
                </li>
                <li class="nav-item dropdown me-2">
                    <a class="btn btn-warning btn-sm px-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->phone ?? 'Tài khoản' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}">Cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item me-2">
                    <a class="btn btn-warning btn-sm px-3" href="{{ route('register') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Tài khoản
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
