@extends('user.layouts.app')

@section('title', 'Web Đầu Tư - Landing Page')

@section('content')
@include('user.home.sections.banner')

@include('user.home.sections.hero')

@include('user.home.sections.gold-price')

@include('user.home.sections.projects')

@include('user.home.sections.testimonials')

@include('user.home.sections.about')

@include('user.home.sections.features')

@include('user.home.sections.steps')

@include('user.home.sections.faq')

@include('user.home.sections.contact')
    
    <!-- Modal: Yêu cầu đăng nhập để sử dụng dịch vụ -->
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="loginRequiredModalLabel">Cần đăng nhập</h5>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="mb-0">Bạn chưa đăng nhập. Vui lòng đăng nhập để sử dụng dịch vụ đầu tư.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Hủy
                    </button>
                    <a href="/login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>

    @auth
        @if (!empty(Auth::user()->thong_bao))
            <!-- Modal: Thông báo cho người dùng -->
            <div class="modal fade" id="userThongBaoModal" tabindex="-1" aria-labelledby="userThongBaoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userThongBaoModalLabel">Thông báo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {!! nl2br(e(Auth::user()->thong_bao)) !!}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đã hiểu</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@push('styles')
<style>
    .text-left {
        text-align: left !important;
    }

    .responsive-slogan {
        font-size: 2.5rem;
        line-height: 1.2;
        word-wrap: break-word;
        white-space: normal;
        max-width: 100%;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    /* Product Card Styles */
    .product-card {
        border-radius: 16px !important;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef !important;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        border-color: #007bff !important;
    }

    .product-image {
        height: 180px;
        width: 100%;
        object-fit: cover;
        border-radius: 6px;
        transition: transform 0.3s ease;
        border: 12px solid #f8f9fa;
        box-sizing: border-box;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* Badge positioning */
    .position-absolute .badge {
        font-size: 0.6rem;
        padding: 0.25rem 0.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    /* Card body styling */
    .product-card .card-body {
        background: #fff;
        padding: 0.75rem !important;
    }

    /* Card title styling for smaller cards */
    .product-card .card-title {
        font-size: 0.9rem;
        line-height: 1.3;
        margin-bottom: 0.5rem !important;
    }

    /* Card text styling */
    .product-card .card-text {
        font-size: 0.75rem;
        line-height: 1.4;
        margin-bottom: 0.75rem !important;
    }

    /* Info boxes styling */
    .bg-light {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        transition: all 0.2s ease;
        padding: 0.5rem !important;
        min-height: 60px;
        /* Chiều cao tối thiểu cố định */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .bg-light:hover {
        background-color: #e9ecef !important;
        border-color: #007bff !important;
    }

    .bg-light small {
        font-size: 0.65rem;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .bg-light .fw-bold,
    .bg-light .fw-semibold {
        font-size: 0.75rem;
        line-height: 1.2;
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    /* Mobile responsive - 2 sản phẩm/hàng */
    @media (max-width: 767.98px) {
        .responsive-slogan {
            font-size: 2rem;
            line-height: 1.3;
        }

        .product-image {
            height: 140px;
        }

        .product-card .card-body {
            padding: 0.6rem !important;
        }

        .product-card .card-title {
            font-size: 0.85rem;
        }

        .product-card .card-text {
            font-size: 0.72rem;
        }

        .bg-light {
            padding: 0.5rem !important;
            min-height: 55px;
            /* Chiều cao nhỏ hơn cho mobile */
        }

        .bg-light small {
            font-size: 0.65rem;
        }

        .bg-light .fw-bold,
        .bg-light .fw-semibold {
            font-size: 0.75rem;
        }

        .position-absolute .badge {
            font-size: 0.6rem;
            padding: 0.25rem 0.5rem;
        }
    }

    /* Tablet responsive - 2 sản phẩm/hàng */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .responsive-slogan {
            font-size: 2.2rem;
            line-height: 1.25;
        }

        .product-image {
            height: 160px;
        }

        .product-card .card-body {
            padding: 0.7rem !important;
        }

        .product-card .card-title {
            font-size: 0.9rem;
        }

        .product-card .card-text {
            font-size: 0.75rem;
        }

        .bg-light {
            min-height: 58px;
            /* Chiều cao cho tablet */
        }
    }

    /* Desktop responsive - 4 sản phẩm/hàng */
    @media (min-width: 992px) {
        .responsive-slogan {
            font-size: 2.5rem;
            line-height: 1.2;
        }

        .product-image {
            height: 180px;
        }

        .product-card .card-body {
            padding: 0.8rem !important;
        }

        .product-card .card-title {
            font-size: 1rem;
        }

        .product-card .card-text {
            font-size: 0.8rem;
        }

        .bg-light {
            min-height: 60px;
            /* Chiều cao cho desktop */
        }
    }

    /* Button đầu tư ngay styling */
    .product-card .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
    }

    .product-card .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #004085);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    .product-card .btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
    }

    /* Mobile responsive cho button */
    @media (max-width: 767.98px) {
        .product-card .btn-primary {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }
    }

    /* Gold Price Table Styles */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead th {
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 0.75rem;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table .h6 {
        font-size: 0.95rem;
        margin: 0;
        font-weight: 600;
    }

    /* Mobile responsive cho bảng */
    @media (max-width: 767.98px) {

        .table thead th,
        .table tbody td {
            padding: 0.5rem 0.25rem;
            font-size: 0.7rem;
        }

        .table .h6 {
            font-size: 0.75rem;
        }

        /* Điều chỉnh độ rộng cột cho mobile */
        .table thead th:nth-child(1),
        .table tbody td:nth-child(1) {
            width: 8%;
            min-width: 40px;
        }

        .table thead th:nth-child(2),
        .table tbody td:nth-child(2) {
            width: 25%;
            min-width: 80px;
        }

        .table thead th:nth-child(3),
        .table tbody td:nth-child(3) {
            width: 20%;
            min-width: 70px;
        }

        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            width: 20%;
            min-width: 70px;
        }

        .table thead th:nth-child(5),
        .table tbody td:nth-child(5) {
            width: 17%;
            min-width: 60px;
        }

        .table thead th:nth-child(6),
        .table tbody td:nth-child(6) {
            width: 10%;
            min-width: 50px;
        }

        /* Điều chỉnh nút Mua cho mobile */
        .table .btn-success {
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            white-space: nowrap;
        }

        .table .btn-success i {
            font-size: 0.6rem;
        }
    }

    /* Tablet responsive cho bảng */
    @media (min-width: 768px) and (max-width: 991.98px) {

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }

        .table .h6 {
            font-size: 0.85rem;
        }

        .table .btn-success {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }
    }

    /* Carousel Image Styles - Đảm bảo tất cả hình ảnh có cùng kích thước */
    .carousel-image {
        height: 460px;
        width: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
    }

    /* Responsive cho carousel images */
    @media (max-width: 767.98px) {
        .carousel-image {
            height: 300px;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .carousel-image {
            height: 380px;
        }
    }

    @media (min-width: 992px) {
        .carousel-image {
            height: 460px;
        }
    }

</style>
@endpush

@push('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Cảm ơn bạn! Chúng tôi sẽ liên hệ sớm.');
        this.reset();
    });


    // Function làm mới dữ liệu giá vàng
    function refreshGiaVang() {
        const button = event.target;
        const originalText = button.innerHTML;

        // Hiển thị loading
        button.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Đang tải...';
        button.disabled = true;

        fetch('/refresh-gia-vang', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload trang để hiển thị dữ liệu mới
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi cập nhật dữ liệu');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật dữ liệu');
            })
            .finally(() => {
                // Khôi phục button
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    // Function xử lý khi click nút "Mua" trong bảng giá vàng
    function buyGold(goldType, price) {
        // Kiểm tra xem user đã đăng nhập chưa
        @auth
        // Nếu đã đăng nhập, hiển thị thông báo xác nhận
        if (confirm('Bạn có muốn mua ' + goldType + ' với giá ' + price + ' VNĐ không?')) {
            // Ở đây có thể thêm logic xử lý mua vàng
            alert('Chức năng mua vàng đang được phát triển. Vui lòng liên hệ hotline để được hỗ trợ!');
        }
        @else
            // Nếu chưa đăng nhập, hiển thị modal Bootstrap yêu cầu đăng nhập
            if (window.bootstrap && document.getElementById('loginRequiredModal')) {
                var modal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
                modal.show();
            } else {
                alert('Bạn chưa đăng nhập. Vui lòng đăng nhập để sử dụng dịch vụ.');
                window.location.href = '/login';
            }
        @endauth
    }

    // Tự động hiển thị modal thông báo nếu người dùng đã đăng nhập và có thông báo
    @auth
    @if (!empty(Auth::user()->thong_bao))
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('userThongBaoModal');
        if (window.bootstrap && el) {
            var modal = new bootstrap.Modal(el);
            modal.show();
        }
    });
    @endif
    @endauth

</script>
@endpush
