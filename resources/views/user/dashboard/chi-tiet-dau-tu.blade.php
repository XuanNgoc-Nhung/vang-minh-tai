@extends('user.layouts.app')

@section('title', 'Chi tiết đầu tư vàng')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-lg-3 col-md-4 content-sidebar">
            @include('user.layouts.menu-dashboard')
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <div class="row">
                <!-- Product Info Card -->
                <div class="col-12 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-gem me-2"></i>
                                Thông tin sản phẩm vàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-primary mb-3">{{ $sanPhamVang->ten_vang ?? 'Vàng SJC' }}</h4>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-tag me-2"></i>
                                        Mã sản phẩm: <strong>{{ $sanPhamVang->ma_vang ?? 'SJC-001' }}</strong>
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-shield-check me-2"></i>
                                        Trạng thái: <span class="badge bg-success">Hoạt động</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-end">
                                        <div class="mb-2">
                                            <small class="text-muted">Giá mua từ hệ thống</small>
                                            <div class="h4 text-success mb-0" id="currentBuyPrice">
                                                {{ number_format($giaHienTai->gia_ban ?? 0) }} VNĐ
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Giá bán cho hệ thống</small>
                                            <div class="h4 text-danger mb-0" id="currentSellPrice">
                                                {{ number_format($giaHienTai->gia_mua ?? 0) }} VNĐ
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Cập nhật: {{ $giaHienTai->thoi_gian ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Biểu đồ giá vàng 7 ngày qua
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm active" data-period="7d">7 ngày</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-period="30d">30 ngày</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-period="90d">90 ngày</button>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Dữ liệu được cập nhật mỗi 15 phút
                                    </small>
                                </div>
                            </div>
                            <div style="height: 400px; position: relative;">
                                <canvas id="priceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buy Form -->
                <div class="col-lg-4 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-cart-plus me-2"></i>
                                Mua vàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="buyGoldForm">
                                @csrf
                                <input type="hidden" name="san_pham_id" value="{{ $sanPhamVang->id ?? 1 }}">
                                
                                <!-- Current Price Display -->
                                <div class="alert alert-info mb-3">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Giá mua từ hệ thống</small>
                                            <strong class="text-success" id="formBuyPrice">{{ number_format($giaHienTai->gia_ban ?? 0) }} VNĐ</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Giá bán cho hệ thống</small>
                                            <strong class="text-danger" id="formSellPrice">{{ number_format($giaHienTai->gia_mua ?? 0) }} VNĐ</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Balance and Gold Holdings -->
                                <div class="alert alert-light mb-3">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-wallet2 me-1"></i>
                                                Số dư hiện tại
                                            </small>
                                            <strong class="text-primary" id="currentBalance">
                                                {{ number_format($profile->so_du ?? 0, 0, ',', '.') }} VNĐ
                                            </strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-gem me-1"></i>
                                                Số vàng hiện có
                                            </small>
                                            <strong class="text-warning" id="currentGold">
                                                {{ number_format($soVangHienCo, 2, ',', '.') }} chỉ
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Input -->
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">
                                        <i class="bi bi-scale me-1"></i>
                                        Số lượng (chỉ)
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="quantity" 
                                               name="quantity" 
                                               min="0.5" 
                                               step="0.5" 
                                               value="1"
                                               required>
                                        <span class="input-group-text">chỉ</span>
                                    </div>
                                    <div class="form-text">Tối thiểu: 0.5 chỉ</div>
                                </div>

                                <!-- Transaction Type -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Loại giao dịch
                                    </label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="transaction_type" id="buy" value="buy" checked>
                                        <label class="btn btn-outline-success" for="buy">
                                            <i class="bi bi-arrow-down-circle me-1"></i>
                                            Mua vào
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="transaction_type" id="sell" value="sell">
                                        <label class="btn btn-outline-danger" for="sell">
                                            <a style="text-decoration: none; color: inherit;" href="{{ route('dashboard.du-an-dau-tu-cua-toi') }}">
                                                <i class="bi bi-arrow-up-circle me-1"></i>
                                            Bán ra</a>   
                                        </label>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <small class="text-muted d-block">Tổng tiền</small>
                                            <h4 class="text-primary mb-0" id="totalAmount">
                                                {{ number_format($giaHienTai->gia_ban, 0, ',', '.') ?? 0 }} VNĐ
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- Withdrawal Password -->
                                <div class="mb-3">
                                    <label for="withdrawal_password" class="form-label">
                                        <i class="bi bi-lock me-1"></i>
                                        Mật khẩu rút tiền
                                    </label>
                                        <input type="password" 
                                        style="width: 100%;"
                                        placeholder="Nhập mật khẩu rút tiền"
                                               class="form-control" 
                                               id="withdrawal_password" 
                                               name="withdrawal_password" 
                                               required
                                               data-no-toggle>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Xác nhận giao dịch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Price History Table -->
                <div class="col-12">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-table me-2"></i>
                                Lịch sử giá vàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th class="text-end">Giá mua từ hệ thống</th>
                                            <th class="text-end">Giá bán cho hệ thống</th>
                                            <th class="text-end">Chênh lệch</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody id="priceHistoryTable">
                                        @forelse($lichSuGia as $gia)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($gia->thoi_gian)->format('d/m/Y H:i') }}</td>
                                            <td class="text-end text-success">{{ number_format($gia->gia_ban) }} VNĐ</td>
                                            <td class="text-end text-danger">{{ number_format($gia->gia_mua) }} VNĐ</td>
                                            <td class="text-end">
                                                @php
                                                    // Chênh lệch = Giá mua từ hệ thống - Giá bán cho hệ thống
                                                    $chenhLech = $gia->gia_ban - $gia->gia_mua;
                                                    $chenhLechPercent = ($chenhLech / $gia->gia_mua) * 100;
                                                @endphp
                                                <span class="badge bg-info">
                                                    {{ number_format($chenhLech) }} VNĐ
                                                    ({{ number_format($chenhLechPercent, 2) }}%)
                                                </span>
                                            </td>
                                            <td>{{ $gia->ghi_chu ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Chưa có dữ liệu</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
    .card.dashboard-card {
        transition: all 0.3s ease;
    }
    
    .card.dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .btn-group .btn-check:checked + .btn {
        box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
    }
    
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
    }
    
    .alert-info {
        background: linear-gradient(135deg, rgba(13, 202, 240, 0.1), rgba(13, 202, 240, 0.05));
        border: 1px solid rgba(13, 202, 240, 0.2);
    }
    
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to check if required elements exist
    function checkRequiredElements() {
        const requiredElements = [
            'priceChart',
            'buyGoldForm', 
            'quantity',
            'totalAmount',
            'submitBtn',
            'withdrawal_password'
        ];
        
        const missingElements = [];
        
        requiredElements.forEach(id => {
            if (!document.getElementById(id)) {
                missingElements.push(id);
            }
        });
        
        if (missingElements.length > 0) {
            console.error('Missing required elements:', missingElements);
            return false;
        }
        
        return true;
    }
    
    // Check if all required elements exist
    if (!checkRequiredElements()) {
        console.error('Some required elements are missing. Please check the HTML structure.');
        return;
    }
    
    // Chart configuration
    const ctx = document.getElementById('priceChart').getContext('2d');
    let priceChart;
    
    // Sample data - replace with real data from API
    const chartData = {
        labels: @json($chartLabels ?? []),
        datasets: [{
            label: 'Giá mua',
            data: @json($chartBuyPrices ?? []),
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Giá bán',
            data: @json($chartSellPrices ?? []),
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };
    
    const chartConfig = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + 
                                   new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Thời gian'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Giá (VNĐ)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    };
    
    // Initialize chart
    priceChart = new Chart(ctx, chartConfig);
    
    // Period buttons
    document.querySelectorAll('[data-period]').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('[data-period]').forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Fetch new data based on period
            const period = this.dataset.period;
            loadChartData(period);
        });
    });
    
    // Function to load chart data
    function loadChartData(period = '7d') {
        const sanPhamId = {{ $sanPhamVang->id ?? 1 }};
        
        fetch(`{{ route('dashboard.api.gia-vang-data') }}?san_pham_id=${sanPhamId}&period=${period}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update chart data
                    priceChart.data.labels = data.data.labels;
                    priceChart.data.datasets[0].data = data.data.buyPrices;
                    priceChart.data.datasets[1].data = data.data.sellPrices;
                    priceChart.update();
                } else {
                    console.error('Error loading chart data:', data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
            });
    }
    
    // Form handling
    const form = document.getElementById('buyGoldForm');
    const quantityInput = document.getElementById('quantity');
    const transactionTypeInputs = document.querySelectorAll('input[name="transaction_type"]');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const formBuyPrice = document.getElementById('formBuyPrice');
    const formSellPrice = document.getElementById('formSellPrice');
    
    // Current prices from server
    // currentBuyPrice: giá mua từ hệ thống (gia_ban của hệ thống)
    // currentSellPrice: giá bán cho hệ thống (gia_mua của hệ thống)
    const currentBuyPrice = {{ $giaHienTai->gia_ban ?? 0 }};
    const currentSellPrice = {{ $giaHienTai->gia_mua ?? 0 }};
    
    function updateTotalAmount() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const isBuy = document.getElementById('buy').checked;
        // Khi mua: sử dụng giá bán của hệ thống (người dùng mua từ hệ thống)
        // Khi bán: sử dụng giá mua của hệ thống (người dùng bán cho hệ thống)
        const price = isBuy ? currentBuyPrice : currentSellPrice;
        const total = quantity * price;
        
        totalAmountDisplay.textContent = new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
    }
    
    // Event listeners
    quantityInput.addEventListener('input', updateTotalAmount);
    transactionTypeInputs.forEach(input => {
        input.addEventListener('change', updateTotalAmount);
    });
    
    // Password toggle (only if toggle button exists)
    const togglePasswordBtn = document.getElementById('togglePassword');
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
            const passwordInput = document.getElementById('withdrawal_password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }
    
    // Form submission with improved axios handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang xử lý...';
        
        // Get form data
        const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
        const quantity = parseFloat(quantityInput.value);
        const withdrawalPassword = document.getElementById('withdrawal_password').value;
        
        // Validation
        if (quantity <= 0) {
            showToast('error', 'Lỗi', 'Số lượng phải lớn hơn 0');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            return;
        }
        
        if (!withdrawalPassword.trim()) {
            showToast('error', 'Lỗi', 'Vui lòng nhập mật khẩu rút tiền');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            return;
        }
        
        // Prepare data for axios request
        const transactionData = {
            san_pham_id: {{ $sanPhamVang->id ?? 1 }}, // id_vang (id sản phẩm vàng)
            quantity: quantity, // Số lượng mua (chỉ)
            transaction_type: transactionType,
            withdrawal_password: withdrawalPassword,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
        
        // Get current price for logging
        const currentPrice = transactionType === 'buy' ? currentBuyPrice : currentSellPrice;
        
        console.log('Gửi dữ liệu giao dịch:', {
            id_vang: transactionData.san_pham_id,
            so_luong: transactionData.quantity,
            gia_mua: currentPrice,
            loai_giao_dich: transactionType
        });
        
        // Make API call using axios (if available) or fetch
        const apiUrl = '{{ route("dashboard.api.vang-dau-tu.create") }}';
        
        // Try to use axios if available, otherwise use fetch
        if (typeof axios !== 'undefined') {
            // Using axios
            axios.post(apiUrl, transactionData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                handleTransactionResponse(response.data, submitBtn, originalText);
            })
            .catch(error => {
                handleTransactionError(error, submitBtn, originalText);
            });
        } else {
            // Using fetch as fallback
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': transactionData._token
                },
                body: JSON.stringify(transactionData)
            })
            .then(response => response.json())
            .then(data => {
                handleTransactionResponse(data, submitBtn, originalText);
            })
            .catch(error => {
                handleTransactionError(error, submitBtn, originalText);
            });
        }
    });
    
    // Handle successful transaction response
    function handleTransactionResponse(data, submitBtn, originalText) {
        if (data.success) {
            showToast('success', 'Thành công', data.message);
            
            // Update balance and gold holdings display
            if (data.data && data.data.new_balance !== undefined) {
                const balanceElement = document.getElementById('currentBalance');
                if (balanceElement) {
                    balanceElement.textContent = new Intl.NumberFormat('vi-VN').format(data.data.new_balance) + ' VNĐ';
                }
            }
            
            // Reset form
            form.reset();
            quantityInput.value = 1;
            document.getElementById('buy').checked = true;
            updateTotalAmount();
            
            // Refresh page to update balance
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Handle specific error cases
            let errorMessage = data.message || 'Có lỗi xảy ra khi xử lý giao dịch';
            
            if (data.error_code === 'INSUFFICIENT_BALANCE') {
                errorMessage = 'Số dư không đủ để thực hiện giao dịch';
            } else if (data.error_code === 'INVALID_WITHDRAWAL_PASSWORD') {
                errorMessage = 'Mật khẩu rút tiền không đúng';
            } else if (data.error_code === 'NO_PRICE_DATA') {
                errorMessage = 'Không có dữ liệu giá vàng hiện tại';
            } else if (data.error_code === 'PRODUCT_NOT_AVAILABLE') {
                errorMessage = 'Sản phẩm vàng không khả dụng';
            }
            
            showToast('error', 'Lỗi', errorMessage);
        }
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
    
    // Handle transaction error
    function handleTransactionError(error, submitBtn, originalText) {
        console.error('Transaction Error:', error);
        
        let errorMessage = 'Có lỗi xảy ra khi kết nối đến server';
        
        if (error.response) {
            // Server responded with error status
            if (error.response.status === 422) {
                errorMessage = 'Dữ liệu không hợp lệ';
            } else if (error.response.status === 500) {
                errorMessage = 'Lỗi hệ thống, vui lòng thử lại sau';
            }
        } else if (error.request) {
            // Network error
            errorMessage = 'Không thể kết nối đến server';
        }
        
        showToast('error', 'Lỗi', errorMessage);
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
    
    // Initialize total amount
    updateTotalAmount();
    
    // Auto-refresh prices every 5 minutes
    setInterval(function() {
        // Here you would fetch updated prices
        console.log('Refreshing prices...');
    }, 300000); // 5 minutes
});

// Toast function (if not already defined)
function showToast(type, title, message) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const toastHtml = `
        <div class="toast ${type}" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${type}">
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                <strong class="toast-title">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}
</script>
@endpush
