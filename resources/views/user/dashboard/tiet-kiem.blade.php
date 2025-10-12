@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-coins mr-2"></i>
                Sản phẩm tiết kiệm
            </h4>
        </div>
        <div class="card-body">
    <form method="GET" action="{{ route('dashboard.tiet-kiem') }}" class="mb-3">
        <div class="row align-items-center g-2">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo tên sản phẩm...">
            </div>
            <div class="col-sm-4 col-md-6 col-lg-4">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search mr-1"></i>Tìm kiếm</button>
                <a href="{{ route('dashboard.tiet-kiem') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    <div class="row g-3">
        @forelse($sanPhamDauTu as $sp)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 shadow-sm position-relative product-card product-bg-{{ $loop->index % 5 }}">
                @if(!empty($sp->nhan_dan))
                <span class="badge position-absolute product-label" style="top: 16px; right: 16px; z-index: 2;">{{ $sp->nhan_dan }}</span>
                @endif
                @if(!empty($sp->hinh_anh))
                <img src="{{ asset($sp->hinh_anh) }}" class="card-img-top product-card-img" alt="{{ $sp->ten }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">{{ $sp->ten }}</h5>
                    <ul class="list-unstyled mb-3">
                        <li>Vốn: <strong>
                            @php($min = $sp->von_toi_thieu)
                            @php($max = $sp->von_toi_da)
                            @if($min !== null && $max !== null)
                                {{ number_format($min) }} - {{ number_format($max) }}
                            @elseif($min !== null)
                                ≥ {{ number_format($min) }}
                            @elseif($max !== null)
                                ≤ {{ number_format($max) }}
                            @else
                                —
                            @endif
                        </strong></li>
                        <li>Lãi suất: <strong>{{ rtrim(rtrim(number_format($sp->lai_suat, 2), '0'), '.') }}%</strong></li>
                        
                        <li>Thời gian/chu kỳ: <strong>{{ TimeHelper::formatTimeFromHours($sp->thoi_gian_mot_chu_ky) }}</strong></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('dashboard.chi-tiet-tiet-kiem', $sp->slug) }}" class="btn btn-primary w-100">Gửi tiết kiệm ngay</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Hiện chưa có sản phẩm tiết kiệm nào.</div>
        </div>
        @endforelse
    </div>
    <div class="row mt-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book-open mr-2"></i>Hướng dẫn tiết kiệm</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0 pl-3">
                        <li>Chọn sản phẩm phù hợp và bấm "tiết kiệm ngay".</li>
                        <li>Đọc kỹ thông tin: vốn tối thiểu/tối đa, lãi suất và chu kỳ.</li>
                        <li>Nhập số tiền muốn tiết kiệm trong khoảng hợp lệ.</li>
                        <li>Chọn số chu kỳ mong muốn rồi xác nhận.</li>
                        <li>Theo dõi lợi nhuận trong mục "Tiết kiệm của tôi".</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i>Lưu ý quan trọng</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 pl-3">
                        <li>Lãi suất hiển thị là theo chu kỳ; tổng lợi nhuận phụ thuộc số chu kỳ.</li>
                        <li>Vui lòng hoàn tất KYC để rút lợi nhuận an toàn.</li>
                        <li>Nên đa dạng hóa danh mục thay vì dồn toàn bộ vốn vào 1 sản phẩm.</li>
                        <li>Kiểm tra lại số tiền trước khi xác nhận để tránh sai sót.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
    </div>
@endsection

@push('styles')
<style>
    .product-card-img {
        width: calc(100% - 16px);
        height: 180px; /* thu gọn một chút */
        object-fit: cover; /* giữ tỉ lệ, cắt phần thừa để lắp khung */
        margin: 8px; /* tạo khoảng cách xung quanh ảnh */
        border-radius: .5rem; /* bo góc nhẹ cho đẹp */
    }

    /* Nền rực rỡ (gradient) cho từng sản phẩm, luân phiên theo chỉ số */
    .product-card { border: 1px solid transparent; overflow: hidden; }
    .product-bg-0 { background: linear-gradient(135deg, #a8e6ff 0%, #2ec5ff 50%, #0084ff 100%); }
    .product-bg-1 { background: linear-gradient(135deg, #ffd1dc 0%, #ff7ca3 50%, #ff3d71 100%); }
    .product-bg-2 { background: linear-gradient(135deg, #c8ffb5 0%, #78e08f 50%, #20bf55 100%); }
    .product-bg-3 { background: linear-gradient(135deg, #ffe3a3 0%, #ffb347 50%, #ff8c00 100%); }
    .product-bg-4 { background: linear-gradient(135deg, #e2d4ff 0%, #b084ff 50%, #7a37ff 100%); }

    /* Tăng khả năng đọc nội dung bên trong */
    .product-card .card-body {
        background-color: rgba(255, 255, 255, 0.92);
        backdrop-filter: saturate(120%) blur(1px);
        margin: 8px; /* tạo khoảng cách với viền card */
        padding: 14px 16px; /* tăng đệm trong để nội dung thoáng hơn */
        border-radius: .5rem; /* bo đều 4 góc cho khối nội dung */
    }

    /* Nhãn dán rực rỡ + bo góc + hiệu ứng chuyển động nền */
    .product-label {
        background: linear-gradient(90deg, #ff7ca3, #ffb347, #2ec5ff, #7a37ff);
        background-size: 300% 300%;
        animation: productLabelGlow 6s ease infinite;
        color: #fff;
        border-radius: 999px;
        padding: 0.35rem 0.6rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-weight: 600;
        letter-spacing: .2px;
    }

    @keyframes productLabelGlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
@endpush