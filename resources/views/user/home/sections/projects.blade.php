<!-- Projects -->
<section id="du-an" class="py-5 bg-tint-green">
    <div class="container">
        <div class="text-center mb-4">
            <span class="text-primary fw-bold text-uppercase small">Danh mục</span>
            <h2 class="mt-2">Dự án tiêu biểu</h2>
            <p class="text-muted">Tổng hợp các dự án đang được quan tâm nhiều nhất.</p>
        </div>
        <div class="row g-3">
            @forelse($sanPhamDauTu as $index => $sanPham)
            <div class="col-6 col-md-6 col-lg-3 wow animate__animated animate__fadeInUp"
                data-wow-delay="{{ ($index + 1) * 0.1 }}s">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <div class="position-relative">
                        @if($sanPham->hinh_anh)
                        <img src="{{ asset($sanPham->hinh_anh) }}" class="card-img-top product-image"
                            style="border-radius:24px" alt="{{ $sanPham->ten }}">
                        @else
                        <img src="https://images.unsplash.com/photo-1451976426598-a7593bd6d0b2?q=80&w=1200&auto=format&fit=crop"
                            class="card-img-top product-image" style="border-radius:6px" alt="{{ $sanPham->ten }}">
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($sanPham->nhan_dan)
                            <span class="badge text-bg-info border border-white">{{ $sanPham->nhan_dan }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-dark">{{ $sanPham->ten }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($sanPham->mo_ta, 80) }}</p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="border rounded-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1 fw-bold" style="font-size: 0.6rem;">Vốn tối
                                        thiểu</small>
                                    <div class="text-primary fw-bold" style="font-size: 0.7rem;">
                                        {{ number_format($sanPham->von_toi_thieu, 0, ',', '.') }}$</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1 fw-bold" style="font-size: 0.6rem;">Vốn tối
                                        đa</small>
                                    <div class="text-danger fw-bold" style="font-size: 0.7rem;">
                                        {{ number_format($sanPham->von_toi_da, 0, ',', '.') }}$</div>
                                </div>
                            </div>
                        </div>

                        <!-- Thời gian và Lợi nhuận cùng hàng -->
                        <div class="row g-2 mb-3">
                            @if($sanPham->thoi_gian_mot_chu_ky)
                            <div class="col-6">
                                <div class="border rounded-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1 fw-bold">Thời gian</small>
                                    <div class="fw-bold text-dark">
                                        {{ TimeHelper::formatTimeFromHours($sanPham->thoi_gian_mot_chu_ky) }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-6">
                                <div class="border rounded-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1 fw-bold">Lợi nhuận</small>
                                    <div class="fw-bold text-success">{{ number_format($sanPham->lai_suat, 2) }}%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Thời gian mở bán một mình một hàng -->
                        {{-- <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="border rounded-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1 fw-bold">Mở bán</small>
                                    <div class="fw-bold text-info">
                                        {{ \Carbon\Carbon::parse($sanPham->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Button đầu tư ngay -->
        <div class="d-grid">
            @auth
                <a href="{{ route('dashboard.chi-tiet-dau-tu', $sanPham->slug) }}" class="btn btn-primary btn-sm fw-semibold">
                    <i class="bi bi-cash-coin me-1"></i>Đầu tư ngay
                </a>
            @else
            <button class="btn btn-primary btn-sm fw-semibold" onclick="showModalInvestNow('{{ $sanPham->slug }}')">
                <i class="bi bi-cash-coin me-1"></i>Đầu tư ngay
            </button>
            @endauth
        </div>
    </div>
    </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h4 class="text-muted mt-3">Chưa có dự án nào</h4>
            <p class="text-muted">Hiện tại chưa có dự án đầu tư nào được hiển thị.</p>
        </div>
    </div>
    @endforelse
    </div>
    </div>
</section>

<script>
    // Function xử lý khi click button "Đầu tư ngay"
    function showModalInvestNow(productSlug = '') {
        // Nếu chưa đăng nhập, hiển thị modal Bootstrap yêu cầu đăng nhập
        if (window.bootstrap && document.getElementById('loginRequiredModal')) {
            var modal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
            modal.show();
        } else {
            alert('Bạn chưa đăng nhập. Vui lòng đăng nhập để sử dụng dịch vụ.');
            window.location.href = '/login';
        }
    }

</script>
