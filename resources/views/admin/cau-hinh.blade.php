@extends('admin.layout.app')

@section('title', 'Cấu hình hệ thống')
@section('nav.settings_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Cấu hình</span>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <strong class="text-success">Cấu hình hệ thống</strong>
  </div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.cau-hinh.store') }}" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
      @csrf
      <div class="col">
        <label class="form-label">Token Telegram</label>
        <input type="text" name="token_tele" value="{{ old('token_tele', optional($cauHinh)->token_tele) }}" class="form-control @error('token_tele') is-invalid @enderror" placeholder="Nhập token Telegram">
        @error('token_tele')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col">
        <label class="form-label">ID Telegram</label>
        <input type="text" name="id_tele" value="{{ old('id_tele', optional($cauHinh)->id_tele) }}" class="form-control @error('id_tele') is-invalid @enderror" placeholder="Nhập ID Telegram">
        @error('id_tele')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col">
        <label class="form-label">ID Live Chat</label>
        <input type="text" name="id_live_chat" value="{{ old('id_live_chat', optional($cauHinh)->id_live_chat) }}" class="form-control @error('id_live_chat') is-invalid @enderror" placeholder="Nhập ID Live Chat">
        @error('id_live_chat')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col">
        <label class="form-label">Link Facebook</label>
        <input type="text" name="link_facebook" value="{{ old('link_facebook', optional($cauHinh)->link_facebook) }}" class="form-control @error('link_facebook') is-invalid @enderror" placeholder="https://facebook.com/...">
        @error('link_facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col">
        <label class="form-label">Mã số doanh nghiệp</label>
        <input type="text" name="ma_so_doanh_nghiep" value="{{ old('ma_so_doanh_nghiep', optional($cauHinh)->ma_so_doanh_nghiep) }}" class="form-control @error('ma_so_doanh_nghiep') is-invalid @enderror" placeholder="Nhập mã số doanh nghiệp">
        @error('ma_so_doanh_nghiep')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="col-12 justify-content-center">
        <p class="form-label" style="color: transparent;">Lưu</p>
        <button class="btn btn-primary" type="submit">
          <i class="bi bi-save"></i> Lưu cấu hình
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

