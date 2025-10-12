@extends('user.layouts.dashboard')

@section('title', 'Dự án đầu tư của tôi')

@push('scripts')
<script>
    var currentInvestment = null;
    // Modal Notification System - Define first to avoid reference errors
    function showModalNotification(type, message, options = {}) {
        const modal = $('#notificationModal');
        const header = $('#notificationModalHeader');
        const icon = $('#notificationModalIcon');
        const title = $('#notificationModalTitle');
        const body = $('#notificationModalBody');
        const confirmBtn = $('#notificationModalConfirmBtn');
        
        // Reset classes
        header.removeClass('success error warning info');
        icon.removeClass('mdi-check-circle mdi-alert-circle mdi-alert mdi-information');
        
        // Set type-specific styling
        let iconClass, titleText, headerClass;
        switch(type) {
            case 'success':
                iconClass = 'mdi-check-circle';
                titleText = 'Thành công';
                headerClass = 'success';
                break;
            case 'error':
                iconClass = 'mdi-alert-circle';
                titleText = 'Lỗi';
                headerClass = 'error';
                break;
            case 'warning':
                iconClass = 'mdi-alert';
                titleText = 'Cảnh báo';
                headerClass = 'warning';
                break;
            case 'info':
            default:
                iconClass = 'mdi-information';
                titleText = 'Thông tin';
                headerClass = 'info';
                break;
        }
        
        // Apply styling
        header.addClass(headerClass);
        icon.addClass(iconClass);
        title.text(titleText);
        body.html(message);
        
        // Handle confirm button
        if (options.showConfirm && options.onConfirm) {
            confirmBtn.removeClass('d-none');
            confirmBtn.off('click').on('click', function() {
                options.onConfirm();
                modal.modal('hide');
            });
        } else {
            confirmBtn.addClass('d-none');
        }
        
        // Auto close after delay if specified
        if (options.autoClose !== false && options.delay) {
            setTimeout(() => {
                modal.modal('hide');
            }, options.delay || 5000);
        }
        
        // Show modal with animation
        modal.addClass('notification-animation');
        modal.modal('show');
        
        // Remove animation class after animation completes
        setTimeout(() => {
            modal.removeClass('notification-animation');
        }, 300);
    }
    
    // Convenience functions
    function showSuccessModal(message, options = {}) {
        return showModalNotification('success', message, options);
    }
    
    function showErrorModal(message, options = {}) {
        return showModalNotification('error', message, options);
    }
    
    function showWarningModal(message, options = {}) {
        return showModalNotification('warning', message, options);
    }
    
    function showInfoModal(message, options = {}) {
        return showModalNotification('info', message, options);
    }
    
    // Make functions globally available
    window.showModalNotification = showModalNotification;
    window.showSuccessModal = showSuccessModal;
    window.showErrorModal = showErrorModal;
    window.showWarningModal = showWarningModal;
    window.showInfoModal = showInfoModal;

    // Define sellGold function globally to avoid reference errors
    function sellGold(gold) {
        //
        // This will be overridden by the full implementation in the scripts section
        console.log('sellGold called with ID:', gold);
        //gán các dữ liệu vào modal
        $('#sellGoldId').val(gold.id);
        $('#sellProductName').text(gold.san_pham_vang.ten_vang);
        $('#sellProductCode').text(gold.san_pham_vang.ma_vang);
        $('#sellCurrentQuantity').text(gold.so_luong);
        $('#sellCurrentMarketPrice').text((parseInt(gold.gia_hien_tai)).toLocaleString('vi-VN') + ' VNĐ/chỉ');
        $('#sellAmountReceived').text((parseInt(gold.gia_hien_tai * gold.so_luong)).toLocaleString('vi-VN') + ' VNĐ');
        $('#sellCurrentPrice').text((parseInt(gold.gia_mua)).toLocaleString('vi-VN') + ' VNĐ/chỉ');
        $('#sellTotalBought').text((parseInt(gold.so_luong * gold.gia_mua)).toLocaleString('vi-VN') + ' VNĐ');
        $('#sellGoldModal').modal('show');
    }
</script>
@endpush

@section('content-dashboard')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Danh sách đầu tư vàng</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('dashboard.dau-tu') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Đầu tư mới
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('dashboard.du-an-dau-tu-cua-toi') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Tên sản phẩm, mã sản phẩm...">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang nắm giữ</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đã bán</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-magnify"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('dashboard.du-an-dau-tu-cua-toi') }}"
                                    class="btn btn-outline-secondary">
                                    <i class="mdi mdi-refresh"></i> Làm mới
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if($duAns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead class="table-dark">
                                <tr style="font-size: 0.85rem;">
                                    <th class="text-center">STT</th>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng (chỉ)</th>
                                    <th>Giá mua</th>
                                    <th>Tổng tiền mua</th>
                                    {{-- <th>Lãi/Lỗ</th> --}}
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.8rem;">
                                @foreach($duAns as $index => $duAn)
                                <tr>
                                    <td class="text-center">{{ $duAns->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                style="width: 24px; height: 24px;">
                                                <i class="mdi mdi-gold mdi-14px text-white"></i>
                                            </div>
                                            <div>
                                                <div class="mb-0" style="font-size: 0.8rem;">
                                                    {{ $duAn->sanPhamVang->ten_vang ?? 'N/A' }}</div>
                                                <small class="text-muted"
                                                    style="font-size: 0.7rem;">{{ $duAn->sanPhamVang->ma_vang ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-primary">{{ number_format($duAn->so_luong, 2) }}</span>
                                    </td>
                                    <td>
                                        <span>{{ number_format($duAn->gia_mua) }} VNĐ</span>
                                        <i class="mdi mdi-gold mdi-12px">
                                            ({{ Carbon::parse($duAn->thoi_gian)->format('d/m/Y') }})
                                        </i>
                                    </td>
                                    <td>
                                        <span class="text-success">{{ number_format($duAn->so_luong * $duAn->gia_mua) }}
                                            VNĐ</span>
                                    </td>
                                    {{-- <td>
                                                @php
                                                    $laiLo = $duAn->tong_tien_hien_tai - ($duAn->so_luong * $duAn->gia_mua);
                                                @endphp
                                                @if($laiLo > 0)
                                                    <span class="text-success">+{{ number_format($laiLo) }} VNĐ</span>
                                    @elseif($laiLo < 0) <span class="text-danger">{{ number_format($laiLo) }} VNĐ</span>
                                        @else
                                        <span class="text-muted">0 VNĐ</span>
                                        @endif
                                        </td> --}}
                                        <td>
                                            @if($duAn->trang_thai == 1)
                                            <span class="badge bg-success">Đang nắm giữ</span>
                                            @elseif($duAn->trang_thai == 2)
                                            <span class="badge bg-warning">Đã bán</span>
                                            @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if($duAn->trang_thai == 1)
                                                <button type="button" class="btn btn-xs btn-primary"
                                                    onclick="sellGold({{ $duAn }})"
                                                    style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                    <i class="mdi mdi-cash mdi-12px"></i> Bán ngay
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-xs btn-primary" disabled
                                                    title="Chỉ có thể bán khi đang nắm giữ"
                                                    style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                    <i class="mdi mdi-cash mdi-12px"></i> Bán ngay
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Hiển thị {{ $duAns->firstItem() }} đến {{ $duAns->lastItem() }}
                                trong tổng số {{ $duAns->total() }} bản ghi
                            </p>
                        </div>
                        <div>
                            {{ $duAns->links() }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="mdi mdi-gold mdi-48px text-muted"></i>
                        </div>
                        <h5 class="text-muted">Chưa có đầu tư nào</h5>
                        <p class="text-muted">Bạn chưa thực hiện giao dịch đầu tư vàng nào.</p>
                        <a href="{{ route('dashboard.dau-tu') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Bắt đầu đầu tư
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
@foreach($duAns as $duAn)
<div class="modal fade" id="detailModal{{ $duAn->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $duAn->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $duAn->id }}">
                    Chi tiết đầu tư - {{ $duAn->sanPhamVang->ten_vang ?? 'N/A' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Thông tin sản phẩm</h6>
                        <table class="table table-sm" style="font-size: 0.85rem;">
                            <tr>
                                <td>Tên sản phẩm:</td>
                                <td>{{ $duAn->sanPhamVang->ten_vang ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Mã sản phẩm:</td>
                                <td>{{ $duAn->sanPhamVang->ma_vang ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Loại vàng:</td>
                                <td>{{ $duAn->sanPhamVang->loai_vang ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Thông tin giao dịch</h6>
                        <table class="table table-sm" style="font-size: 0.85rem;">
                            <tr>
                                <td>Số lượng:</td>
                                <td>{{ number_format($duAn->so_luong, 2) }} gram</td>
                            </tr>
                            <tr>
                                <td>Giá mua:</td>
                                <td>{{ number_format($duAn->gia_mua) }} VNĐ/gram</td>
                            </tr>
                            <tr>
                                <td>Tổng tiền mua:</td>
                                <td class="text-success">{{ number_format($duAn->so_luong * $duAn->gia_mua) }} VNĐ</td>
                            </tr>
                            <tr>
                                <td>Lãi/Lỗ:</td>
                                <td>
                                    @php
                                    $laiLo = $duAn->tong_tien_hien_tai - ($duAn->so_luong * $duAn->gia_mua);
                                    @endphp
                                    @if($laiLo > 0)
                                    <span class="text-success">+{{ number_format($laiLo) }} VNĐ</span>
                                    @elseif($laiLo < 0) <span class="text-danger">{{ number_format($laiLo) }} VNĐ</span>
                                        @else
                                        <span class="text-muted">0 VNĐ</span>
                                        @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Trạng thái:</td>
                                <td>
                                    @if($duAn->trang_thai == 1)
                                    <span class="badge bg-success">Đang nắm giữ</span>
                                    @elseif($duAn->trang_thai == 2)
                                    <span class="badge bg-warning">Đã bán</span>
                                    @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($duAn->ghi_chu)
                <div class="mt-3">
                    <h6 class="text-muted">Ghi chú</h6>
                    <p class="text-muted">{{ $duAn->ghi_chu }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                @if($duAn->trang_thai == 1)
                <button type="button" class="btn btn-warning" onclick="sellGold({{ $duAn->id }})">
                    <i class="mdi mdi-cash"></i> Bán vàng
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Include Modal Notification Component -->
@include('components.modal-notification')

<!-- Sell Gold Modal -->
<div class="modal fade" id="sellGoldModal" tabindex="-1" aria-labelledby="sellGoldModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellGoldModalLabel">
                    <i class="mdi mdi-cash text-warning"></i> Bán vàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="sellGoldForm">
                <div class="modal-body">
                    <input type="hidden" id="sellGoldId" name="gold_id">

                    <!-- Thông tin sản phẩm -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="mdi mdi-gold"></i> Thông tin sản phẩm
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Tên sản phẩm:</small>
                                            <div id="sellProductName" class="fw-bold"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Mã sản phẩm:</small>
                                            <div id="sellProductCode" class="fw-bold"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin đầu tư hiện tại -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-info mb-3">
                                <i class="mdi mdi-chart-line"></i> Thông tin đầu tư hiện tại
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted">Số lượng (chỉ)</small>
                                            <div id="sellCurrentQuantity" class="fw-bold text-primary"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted">Giá mua</small>
                                            <div id="sellCurrentPrice" class="fw-bold text-success"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted">Tổng tiền mua</small>
                                            <div id="sellTotalBought" class="fw-bold text-warning"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bán -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-warning mb-3">
                                <i class="mdi mdi-cash-multiple"></i> Thông tin bán
                            </h6>
                            <div class="alert alert-info">
                                <i class="mdi mdi-information"></i>
                                <strong>Lưu ý:</strong> Số lượng bán sẽ tự động lấy từ số lượng đầu tư hiện tại.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted">Giá bán hiện tại</small>
                                            <div id="sellCurrentMarketPrice" class="fw-bold text-info fs-5">
                                                <i class="mdi mdi-loading mdi-spin"></i> Đang tải...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-danger">
                                        <div class="card-body text-center py-2">
                                            <small class="text-muted">Số tiền nhận được</small>
                                            <div id="sellAmountReceived" class="fw-bold text-danger fs-5">
                                                <i class="mdi mdi-loading mdi-spin"></i> Đang tính...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form nhập liệu -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sellPassword" class="form-label">
                                    <i class="mdi mdi-lock"></i> Mật khẩu rút tiền
                                </label>
                                <input type="password" class="form-control" id="sellPassword" name="withdrawal_password"
                                    required placeholder="Nhập mật khẩu rút tiền">
                            </div>
                        </div>
                    </div>

                    <!-- Thông báo lãi/lỗ -->
                    <div id="profitLossInfo" class="alert alert-info d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Lãi/Lỗ dự kiến:</small>
                                <div id="expectedProfitLoss" class="fw-bold"></div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Tỷ lệ lãi/lỗ:</small>
                                <div id="profitLossPercentage" class="fw-bold"></div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="mdi mdi-information"></i>
                        <strong>Lưu ý:</strong> Giá bán được cập nhật theo thị trường hiện tại. Số tiền thực tế có thể
                        thay đổi tùy theo thời điểm giao dịch.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> Hủy
                    </button>
                    <button type="button" class="btn btn-warning" onclick="confirmSellBtn()">
                        <i class="mdi mdi-cash"></i> Xác nhận bán
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmSellBtn() {
        console.log('=== BẮT ĐẦU XỬ LÝ BÁN VÀNG ===');
        
        // Lấy dữ liệu từ form
        const goldId = $('#sellGoldId').val();
        const withdrawalPassword = $('#sellPassword').val();
        
        // Lấy số lượng từ thông tin đầu tư hiện tại (tự động lấy từ so_luong)
        const quantity = currentInvestment ? currentInvestment.so_luong : 0;
        
        console.log('Thông tin bán:', {
            goldId: goldId,
            withdrawalPassword: withdrawalPassword
        });
        
        // Validation
        if (!goldId) {
            showErrorModal('Không tìm thấy thông tin đầu tư');
            return;
        }
        
        
        if (!withdrawalPassword) {
            showErrorModal('Vui lòng nhập mật khẩu rút tiền');
            return;
        }
        
        // Disable button để tránh double click
        const confirmBtn = $('button[onclick="confirmSellBtn()"]');
        confirmBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Đang xử lý...');
        
        // Gửi dữ liệu đến server
        axios.post('{{ route("dashboard.api.vang-dau-tu.sell") }}', {
            gold_id: goldId,
            withdrawal_password: withdrawalPassword
        })
        .then(function(response) {
            console.log('Response từ server:', response);
            
            if (response.data.success) {
                
                showSuccessModal(response.data.message || 'Bán vàng thành công');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showErrorModal(response.data.message || 'Có lỗi xảy ra khi bán vàng');
            }
        })
        .catch(function(error) {
            console.error('Lỗi khi gửi request:', error);
            
            let errorMessage = 'Có lỗi xảy ra khi bán vàng';
            
            if (error.response) {
                // Server trả về lỗi
                if (error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.status === 422) {
                    errorMessage = 'Dữ liệu không hợp lệ';
                } else if (error.response.status === 401) {
                    errorMessage = 'Mật khẩu không đúng';
                } else if (error.response.status === 403) {
                    errorMessage = 'Không có quyền thực hiện giao dịch này';
                } else if (error.response.status === 404) {
                    errorMessage = 'Không tìm thấy thông tin đầu tư';
                }
            } else if (error.request) {
                // Không nhận được response
                errorMessage = 'Không thể kết nối đến server';
            }
            
            showErrorModal(errorMessage);
        })
        .finally(function() {
            // Re-enable button
            confirmBtn.prop('disabled', false).html('<i class="mdi mdi-cash"></i> Xác nhận bán');
        });
    }
    function numberFormat(number, decimals = 0) {
        return number.toLocaleString('vi-VN', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }
    function getCurrentGoldPrice(productId) {
        return axios.get('{{ route("dashboard.api.gia-vang.current") }}', {
            params: {
                product_id: productId
            }
        });
    }
    function calculateSellAmount(currentPrice) {
        return currentPrice * $('#sellQuantity').val();
    }
    // Dữ liệu đầu tư từ server
    const investmentData = @json($duAns -> items());

    // Override the global sellGold function with full implementation
    window.sellGold = function (goldId) {
        console.log('=== MỞ MODAL BÁN VÀNG ===');
        console.log('Gold ID:', goldId);
        console.log('Dữ liệu đầu tư có sẵn:', investmentData);

        // Tìm thông tin đầu tư
        const investment = investmentData.find(item => item.id === goldId);
        console.log('Thông tin đầu tư tìm được:', investment);

        if (!investment) {
            console.error('Không tìm thấy thông tin đầu tư với ID:', goldId);
            showErrorModal('Không tìm thấy thông tin đầu tư');
            return;
        }

        console.log('Hiển thị thông tin sản phẩm...');
        // Hiển thị thông tin sản phẩm
        $('#sellProductName').text(investment.san_pham_vang?.ten_vang || 'N/A');
        $('#sellProductCode').text(investment.san_pham_vang?.ma_vang || 'N/A');

        console.log('Hiển thị thông tin đầu tư...');
        // Hiển thị thông tin đầu tư hiện tại
        $('#sellCurrentQuantity').text(numberFormat(investment.so_luong, 2) + ' chỉ');
        $('#sellCurrentPrice').text(numberFormat(investment.gia_mua) + ' VNĐ/chỉ');
        $('#sellTotalBought').text(numberFormat(investment.so_luong * investment.gia_mua) + ' VNĐ');

        // Số lượng bán sẽ tự động lấy từ so_luong của bản ghi đầu tư

        console.log('Lưu thông tin đầu tư vào window.currentInvestment');
        // Lưu thông tin để tính toán
        window.currentInvestment = investment;

        console.log('Bắt đầu lấy giá vàng hiện tại...');
        // Lấy giá vàng hiện tại
        getCurrentGoldPrice(investment.id_vang);

        // Reset form
        $('#sellPassword').val('');

        $('#sellGoldId').val(goldId);
        console.log('Hiển thị modal bán vàng');
        $('#sellGoldModal').modal('show');
    };

    // Lấy giá vàng hiện tại
    function getCurrentGoldPrice(productId) {
        console.log('=== LẤY GIÁ VÀNG HIỆN TẠI ===');
        console.log('Product ID:', productId);

        if (!productId) {
            console.error('Không có Product ID');
            $('#sellCurrentMarketPrice').html('<span class="text-danger">Không có thông tin sản phẩm</span>');
            $('#sellAmountReceived').html('<span class="text-danger">Không thể tính toán</span>');
            return;
        }

        // Hiển thị loading
        $('#sellCurrentMarketPrice').html('<i class="mdi mdi-loading mdi-spin"></i> Đang tải...');
        $('#sellAmountReceived').html('<i class="mdi mdi-loading mdi-spin"></i> Đang tính...');

        console.log('Bắt đầu gọi API lấy giá vàng...');

        // Gọi API lấy giá hiện tại
        $.ajax({
            url: '{{ route("dashboard.api.gia-vang.current") }}',
            method: 'GET',
            data: {
                product_id: productId
            },
            success: function (response) {
                console.log('Response từ API giá vàng:', response);

                if (response.success && response.data) {
                    const currentPrice = response.data.gia_ban;
                    console.log('Giá vàng hiện tại:', currentPrice);

                    $('#sellCurrentMarketPrice').text(numberFormat(currentPrice) + ' VNĐ/chỉ');

                    // Tính toán số tiền nhận được
                    calculateSellAmount(currentPrice);
                } else {
                    console.error('API trả về lỗi:', response);
                    $('#sellCurrentMarketPrice').html('<span class="text-danger">Không thể lấy giá</span>');
                    $('#sellAmountReceived').html('<span class="text-danger">Không thể tính toán</span>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi gọi API giá vàng:', {
                    status: status,
                    error: error,
                    xhr: xhr
                });

                // Fallback: sử dụng giá mua + 5% (giả lập)
                const fallbackPrice = Math.round(window.currentInvestment.gia_mua * 1.05);
                console.log('Sử dụng giá fallback:', fallbackPrice);

                $('#sellCurrentMarketPrice').text(numberFormat(fallbackPrice) + ' VNĐ/chỉ (ước tính)');
                calculateSellAmount(fallbackPrice);
            }
        });
    }

    // Tính toán số tiền nhận được
    function calculateSellAmount(currentPrice) {
        console.log('=== TÍNH TOÁN SỐ TIỀN NHẬN ĐƯỢC ===');
        console.log('Giá hiện tại:', currentPrice);

        // Lấy số lượng từ thông tin đầu tư hiện tại (tự động lấy từ so_luong)
        const quantity = window.currentInvestment ? window.currentInvestment.so_luong : 0;
        const totalAmount = quantity * currentPrice;

        console.log('Số lượng:', quantity);
        console.log('Tổng tiền nhận được:', totalAmount);

        $('#sellAmountReceived').text(numberFormat(totalAmount) + ' VNĐ');

        // Tính lãi/lỗ
        if (window.currentInvestment) {
            const boughtAmount = quantity * window.currentInvestment.gia_mua;
            const profitLoss = totalAmount - boughtAmount;
            const profitLossPercent = ((profitLoss / boughtAmount) * 100).toFixed(2);

            console.log('Thông tin lãi/lỗ:', {
                boughtAmount: boughtAmount,
                profitLoss: profitLoss,
                profitLossPercent: profitLossPercent
            });

            if (profitLoss > 0) {
                $('#expectedProfitLoss').html('<span class="text-success">+' + numberFormat(profitLoss) +
                ' VNĐ</span>');
                $('#profitLossPercentage').html('<span class="text-success">+' + profitLossPercent + '%</span>');
            } else if (profitLoss < 0) {
                $('#expectedProfitLoss').html('<span class="text-danger">' + numberFormat(profitLoss) + ' VNĐ</span>');
                $('#profitLossPercentage').html('<span class="text-danger">' + profitLossPercent + '%</span>');
            } else {
                $('#expectedProfitLoss').html('<span class="text-muted">0 VNĐ</span>');
                $('#profitLossPercentage').html('<span class="text-muted">0%</span>');
            }

            $('#profitLossInfo').removeClass('d-none');
        }
    }

</script>  
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

@endsection
