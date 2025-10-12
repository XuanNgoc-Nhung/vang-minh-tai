@extends('admin.layout.app')

@section('title', 'Bảng điều khiển')
@section('nav.dashboard_active', 'active')

@section('breadcrumb')
  <span class="text-secondary">Admin</span> / <span class="text-dark">Bảng điều khiển</span>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-people fs-3"></i></div>
          <div>
            <div class="text-muted small">Người dùng</div>
            <div class="h4 mb-0">--</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-cash-coin fs-3"></i></div>
          <div>
            <div class="text-muted small">Giao dịch hôm nay</div>
            <div class="h4 mb-0">--</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-graph-up fs-3"></i></div>
          <div>
            <div class="text-muted small">Doanh thu</div>
            <div class="h4 mb-0">--</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-activity fs-3"></i></div>
          <div>
            <div class="text-muted small">Tỷ lệ hoạt động</div>
            <div class="h4 mb-0">--</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-12 col-xl-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white">
          <strong class="text-success">Tổng quan hệ thống</strong>
        </div>
        <div class="card-body">
          <p class="text-muted mb-0">Nội dung biểu đồ/summary sẽ hiển thị ở đây.</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white">
          <strong class="text-success">Hoạt động gần đây</strong>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0 small">
            <li class="mb-2"><span class="badge text-bg-success">Mới</span> Đã cập nhật cấu hình hệ thống</li>
            <li class="mb-2"><span class="badge text-bg-success">Mới</span> Người dùng A vừa đăng ký</li>
            <li class="mb-2">Cronjob chạy lúc 03:00</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection


