@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-coins mr-2"></i>
                <code>#{{ $sanPhamDauTu->ten }}</code>
            </h4>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-lg-7">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin sản phẩm</h6>
                        </div>
                        <div class="card-body position-relative d-flex flex-column">
                            @if(!empty($sanPhamDauTu->nhan_dan))
                            <span class="product-label position-absolute" style="top: 20px; right: 12px; z-index: 3;">{{ $sanPhamDauTu->nhan_dan }}</span>
                            @endif
                            @if(!empty($sanPhamDauTu->hinh_anh))
                            <img src="{{ asset($sanPhamDauTu->hinh_anh) }}" alt="{{ $sanPhamDauTu->ten }}" class="img-fluid rounded mb-3" style="max-height: 260px; object-fit: cover;">
                            @endif
                            <h5 class="mb-3 text-primary">{{ $sanPhamDauTu->ten }}</h5>
                            
                            <!-- Thông tin chính -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="bi bi-coin"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Vốn tiết kiệm</div>
                                            <div class="info-value">
                                                @php($min = $sanPhamDauTu->von_toi_thieu)
                                                @php($max = $sanPhamDauTu->von_toi_da)
                                                @if($min !== null && $max !== null)
                                                    {{ number_format($min) }} - {{ number_format($max) }} VNĐ
                                                @elseif($min !== null)
                                                    ≥ {{ number_format($min) }} VNĐ
                                                @elseif($max !== null)
                                                    ≤ {{ number_format($max) }} VNĐ
                                                @else
                                                    Không giới hạn
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="bi bi-percent"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Lãi suất/chu kỳ</div>
                                            <div class="info-value">{{ rtrim(rtrim(number_format($sanPhamDauTu->lai_suat, 2), '0'), '.') }}%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Thời gian/chu kỳ</div>
                                            <div class="info-value">{{ TimeHelper::formatTimeFromHours($sanPhamDauTu->thoi_gian_mot_chu_ky) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="bi bi-calendar-date"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Ngày triển khai</div>
                                            <div class="info-value">{{ \Carbon\Carbon::parse($sanPhamDauTu->created_at)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($sanPhamDauTu->mo_ta))
                            <div class="description-card">
                                <div class="description-header">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <h6 class="mb-0">Mô tả sản phẩm</h6>
                                </div>
                                <div class="description-content">
                                    {!! nl2br(e($sanPhamDauTu->mo_ta)) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">@if($hasWithdrawalPassword) tiết kiệm ngay @else Bổ sung thông tin cần thiết @endif</h6>
                        </div>
                        <div class="card-body">
                            @if($hasWithdrawalPassword)
                            <form id="investment-form" class="investment-form">
                                @csrf
                                <input type="hidden" name="san_pham_id" value="{{ $sanPhamDauTu->id }}">
                                <div class="form-group">
                                    <label for="so_du">Số dư hiện tại</label>
                                    <input type="text" id="so_du" class="form-control" value="{{ number_format($profile->so_du ?? 0) }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="ngay_bat_dau">Ngày bắt đầu tiết kiệm</label>
                                    <input type="date" name="ngay_bat_dau" readonly disabled id="ngay_bat_dau" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="ngay_thanh_toan">Ngày thanh toán</label>
                                    <input type="date" name="ngay_thanh_toan" id="ngay_thanh_toan" class="form-control" readonly style="background-color: #f8f9fa;">
                                    <small class="form-text text-muted">Tự động tính toán dựa trên thời gian hiện tại và chu kỳ tiết kiệm</small>
                                </div>
                                <div class="form-group">
                                    <label for="so_tien">Số tiền tiết kiệm</label>
                                    <input type="number" name="so_tien" id="so_tien" class="form-control" placeholder="Nhập số tiền"
                                           @if(!is_null($sanPhamDauTu->von_toi_thieu)) min="{{ (int) $sanPhamDauTu->von_toi_thieu }}" @endif
                                           @if(!is_null($sanPhamDauTu->von_toi_da)) max="{{ (int) $sanPhamDauTu->von_toi_da }}" @endif required>
                                    <small class="form-text text-muted">
                                        @php($min = $sanPhamDauTu->von_toi_thieu)
                                        @php($max = $sanPhamDauTu->von_toi_da)
                                        @if($min !== null && $max !== null)
                                            Khoảng vốn hợp lệ: {{ number_format($min) }} - {{ number_format($max) }}
                                        @elseif($min !== null)
                                            Vốn tối thiểu: {{ number_format($min) }}
                                        @elseif($max !== null)
                                            Vốn tối đa: {{ number_format($max) }}
                                        @else
                                            Không giới hạn cụ thể
                                        @endif
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="so_tien_thuc_nhan">Số tiền thực nhận sau khi kết thúc</label>
                                    <input type="text" id="so_tien_thuc_nhan" class="form-control" readonly style="background-color: #f8f9fa; color: #28a745;" placeholder="Nhập số tiền để tính">
                                    <small class="form-text text-muted">Bao gồm: Vốn gốc + Lãi suất (tự động tính toán)</small>
                                </div>
                                <div class="form-group">
                                    <label for="mat_khau_rut_tien">Mật khẩu rút tiền</label>
                                    <input type="password" name="mat_khau_rut_tien" id="mat_khau_rut_tien" class="form-control" placeholder="Nhập mật khẩu rút tiền để xác nhận" required>
                                    <input type="hidden" name="id_san_pham" id="id_san_pham" value="{{ $sanPhamDauTu->id }}">
                                </div>
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-block">
                                    <span class="btn-text">Xác nhận tiết kiệm</span>
                                    <span class="btn-loading d-none">
                                        <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
                                    </span>
                                </button>
                            </form>
                            @else
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="bi bi-shield-exclamation text-warning" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-warning mb-3">Thiếu thông tin bảo mật</h5>
                                <p class="text-muted mb-4">
                                    Để có thể tiết kiệm, bạn cần thiết lập mật khẩu rút tiền trước. 
                                    Điều này giúp bảo vệ tài khoản của bạn.
                                </p>
                                <a href="{{ route('dashboard.bao-mat') }}" class="btn btn-warning btn-lg">
                                    <i class="bi bi-shield-check me-2"></i>
                                    Bổ sung thông tin cần thiết
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div>
                <h6 class="mb-2"><i class="fas fa-book-open mr-2"></i>Hướng dẫn nhanh</h6>
                <ol class="mb-2 pl-3">
                    <li>Nhập số tiền trong khoảng hợp lệ ở trên.</li>
                    <li>Kiểm tra thông tin chu kỳ tiết kiệm (cố định).</li>
                    <li>Kiểm tra lại thông tin và bấm xác nhận.</li>
                </ol>
                <div class="alert alert-warning mb-0 p-2"><i class="fas fa-info-circle mr-1"></i>Lãi suất hiển thị là theo chu kỳ cố định. Chu kỳ tiết kiệm được xác định dựa trên thời gian một chu kỳ của sản phẩm.</div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .product-label {
        display: inline-block;
        color: #fff;
        font-weight: 700;
        letter-spacing: .2px;
        padding: 8px 14px;
        border-radius: 999px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        background: linear-gradient(90deg, #ff7ca3, #ffb347, #2ec5ff, #7a37ff);
        background-size: 300% 300%;
        animation: productLabelGlow 4.5s ease infinite;
        text-transform: uppercase;
        font-size: 0.95rem;
    }

    @keyframes productLabelGlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Tăng khoảng cách giữa các input trong form tiết kiệm */
    .investment-form .form-group {
        margin-bottom: 1rem;
    }

    .investment-form .form-group:last-child {
        margin-bottom: 0;
    }

    .investment-form .form-control {
        margin-top: 0.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .investment-form .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .investment-form .form-control:hover:not(:focus) {
        border-color: #cbd5e1;
    }

    .investment-form .form-text {
        margin-top: 0.5rem;
    }

    /* Info cards styling */
    .info-card {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border: 2px solid #e3f2fd;
        border-radius: 16px;
        padding: 1rem;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 
            0 4px 6px -1px rgba(0, 0, 0, 0.1),
            0 2px 4px -1px rgba(0, 0, 0, 0.06),
            0 0 0 1px rgba(59, 130, 246, 0.05);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }


    .info-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 
            0 20px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04),
            0 0 0 1px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
        color: #1f2937;
        box-shadow: 
            0 8px 16px rgba(209, 250, 229, 0.2),
            0 4px 8px rgba(209, 250, 229, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .info-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
        border-radius: 16px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-icon:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 
            0 12px 24px rgba(209, 250, 229, 0.3),
            0 6px 12px rgba(209, 250, 229, 0.2);
    }

    .info-icon:hover::before {
        opacity: 1;
    }

    /* Icon specific colors */
    .info-icon .bi-coin {
        color: #dc2626;
    }

    .info-icon .bi-percent {
        color: #2563eb;
    }

    .info-icon .bi-clock {
        color: #059669;
    }

    .info-icon .bi-calendar-date {
        color: #7c3aed;
    }

    .info-content {
        flex: 1;
        min-width: 0;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.3;
        word-break: break-word;
    }

    /* Description card styling */
    .description-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e3f2fd;
        border-radius: 16px;
        margin-top: 1.5rem;
        overflow: hidden;
        box-shadow: 
            0 4px 6px -1px rgba(0, 0, 0, 0.1),
            0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .description-card:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 10px 15px -3px rgba(0, 0, 0, 0.1),
            0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .description-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
    }

    .description-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .description-card:hover .description-header::before {
        opacity: 1;
    }

    .description-header i {
        font-size: 1.2rem;
        color: #ffffff;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    .description-header h6 {
        color: #ffffff;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
        z-index: 1;
    }

    .description-content {
        padding: 1rem;
        color: #4a5568;
        line-height: 1.7;
        font-size: 0.95rem;
        white-space: pre-line;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        position: relative;
        text-align: justify;
    }

    .description-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .info-card {
            padding: 0.75rem;
            gap: 0.5rem;
        }
        
        .info-icon {
            width: 42px;
            height: 42px;
            font-size: 1.2rem;
        }
        
        .info-label {
            font-size: 0.75rem;
        }
        
        .info-value {
            font-size: 0.85rem;
        }

        .description-header {
            padding: 0.75rem 1rem;
        }

        .description-header i {
            font-size: 1rem;
        }

        .description-header h6 {
            font-size: 0.9rem;
        }

        .description-content {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const investmentForm = document.getElementById('investment-form');
    
    // Chỉ chạy JavaScript nếu form tiết kiệm tồn tại (user đã có mật khẩu rút tiền)
    if (!investmentForm) {
        return;
    }
    
    const soTienInput = document.getElementById('so_tien');
    const soTienThucNhanInput = document.getElementById('so_tien_thuc_nhan');
    const ngayBatDauInput = document.getElementById('ngay_bat_dau');
    const ngayThanhToanInput = document.getElementById('ngay_thanh_toan');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    const laiSuat = {{ $sanPhamDauTu->lai_suat }};
    const soChuKy = 1; // Chu kỳ cố định là 1
    const thoiGianMotChuKy = {{ $sanPhamDauTu->thoi_gian_mot_chu_ky }}; // Thời gian một chu kỳ (giờ)

    // Thiết lập ngày hôm nay làm ngày mặc định
    const today = new Date();
    const todayString = today.toISOString().split('T')[0];
    ngayBatDauInput.value = todayString;

    function calculatePaymentDate() {
        // Tính ngày thanh toán = thời gian hiện tại + số giờ của chu kỳ
        const soGio = thoiGianMotChuKy;
        const ngayThanhToan = new Date(); // Sử dụng thời gian hiện tại
        ngayThanhToan.setHours(ngayThanhToan.getHours() + soGio);
        
        // Định dạng ngày thanh toán
        const ngayThanhToanString = ngayThanhToan.toISOString().split('T')[0];
        ngayThanhToanInput.value = ngayThanhToanString;
    }

    function calculateTotalAmount() {
        const soTien = parseFloat(soTienInput.value) || 0;
        
        if (soTien > 0) {
            // Tính lãi suất cho 1 chu kỳ
            const laiSuatTong = laiSuat * soChuKy;
            
            // Tính số tiền lãi
            const soTienLai = (soTien * laiSuatTong) / 100;
            
            // Tính tổng số tiền thực nhận (vốn + lãi)
            const tongTien = soTien + soTienLai;
            
            // Hiển thị kết quả với định dạng số
            soTienThucNhanInput.value = new Intl.NumberFormat('vi-VN').format(Math.round(tongTien));
        } else {
            soTienThucNhanInput.value = '';
        }
    }

    function showLoading() {
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    }

    function hideLoading() {
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
    }

    function showToast(message, type = 'success') {
        // Tạo toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    // Xử lý submit form
    investmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validation cơ bản
        const soTien = parseFloat(soTienInput.value) || 0;
        const matKhauRutTien = document.getElementById('mat_khau_rut_tien').value;
        
        if (soTien <= 0) {
            showToast('Vui lòng nhập số tiền tiết kiệm hợp lệ', 'error');
            return;
        }
        
        if (!matKhauRutTien) {
            showToast('Vui lòng nhập mật khẩu rút tiền', 'error');
            return;
        }

        showLoading();

        // Chuẩn bị dữ liệu gửi
        const formData = new FormData(investmentForm);
        //thêm thông tin hoa hồng
        formData.append('hoa_hong', laiSuat * soTien / 100);
        
        // Gửi request bằng axios
        axios.post('{{ route("dashboard.tiet-kiem.create") }}', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(function(response) {
            hideLoading();
            
            if (response.data.success) {
                showToast(response.data.message, 'success');
                
                // Cập nhật số dư hiển thị
                const soDuElement = document.getElementById('so_du');
                const soDuConLai = response.data.data.so_du_con_lai;
                soDuElement.value = new Intl.NumberFormat('vi-VN').format(soDuConLai);
                
                // Reset form
                investmentForm.reset();
                ngayBatDauInput.value = todayString;
                calculatePaymentDate();
                calculateTotalAmount();
                
                // Redirect sau 2 giây
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard.du-an-cua-toi") }}';
                }, 2000);
            } else {
                showToast(response.data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(function(error) {
            hideLoading();
            
            if (error.response && error.response.data) {
                const responseData = error.response.data;
                const errorCode = responseData.error_code;
                
                // Xử lý lỗi số dư không đủ
                if (errorCode === 'INSUFFICIENT_BALANCE') {
                    const data = responseData.data;
                    document.getElementById('currentBalance').textContent = new Intl.NumberFormat('vi-VN').format(data.current_balance) + ' VNĐ';
                    document.getElementById('requiredAmount').textContent = new Intl.NumberFormat('vi-VN').format(data.required_amount) + ' VNĐ';
                    document.getElementById('shortageAmount').textContent = new Intl.NumberFormat('vi-VN').format(data.shortage) + ' VNĐ';
                    
                    // Hiển thị modal
                    const modal = new bootstrap.Modal(document.getElementById('insufficientBalanceModal'));
                    modal.show();
                    return;
                }
                
                // Xử lý các lỗi khác
                const errors = responseData.errors;
                if (errors) {
                    // Hiển thị lỗi validation
                    let errorMessage = '';
                    for (const field in errors) {
                        errorMessage += errors[field][0] + '<br>';
                    }
                    showToast(errorMessage, 'error');
                } else {
                    showToast(responseData.message || 'Có lỗi xảy ra', 'error');
                }
            } else {
                showToast('Có lỗi xảy ra khi kết nối đến server', 'error');
            }
        });
    });
    
    // Tính toán khi thay đổi số tiền tiết kiệm
    soTienInput.addEventListener('input', calculateTotalAmount);
    
    // Tính toán ban đầu
    calculatePaymentDate();
    calculateTotalAmount();
    
    // Xử lý nút "Nạp tiền ngay" trong modal
    document.getElementById('goToDepositBtn').addEventListener('click', function() {
        // Đóng modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('insufficientBalanceModal'));
        modal.hide();
        
        // Chuyển hướng đến trang nạp tiền
        window.location.href = '{{ route("dashboard.nap-tien") }}';
    });
});
</script>

<!-- Modal thông báo số dư không đủ -->
<div class="modal fade" id="insufficientBalanceModal" tabindex="-1" aria-labelledby="insufficientBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="insufficientBalanceModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Số dư không đủ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-wallet text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">Số dư hiện tại của bạn không đủ để thực hiện giao dịch này.</p>
                
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-6">
                            <strong>Số dư hiện tại:</strong><br>
                            <span id="currentBalance" class="text-primary"></span>
                        </div>
                        <div class="col-6">
                            <strong>Số tiền cần:</strong><br>
                            <span id="requiredAmount" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <strong>Thiếu:</strong> <span id="shortageAmount" class="text-warning"></span>
                        </div>
                    </div>
                </div>
                
                <p class="text-center mb-0">Bạn có muốn nạp thêm tiền để sử dụng dịch vụ không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Hủy
                </button>
                <button type="button" class="btn btn-primary" id="goToDepositBtn">
                    <i class="fas fa-plus-circle me-1"></i>
                    Nạp tiền ngay
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
