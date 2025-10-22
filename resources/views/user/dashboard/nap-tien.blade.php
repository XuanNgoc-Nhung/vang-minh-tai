@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet mr-2"></i>
                        Nạp tiền
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <form id="depositForm" autocomplete="off">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Chọn ngân hàng <span
                                            class="text-danger">*</span></label>
                                    <div id="bank_logo_grid" class="bank-grid">
                                        @foreach(($banks ?? []) as $bank)
                                        <div class="bank-logo-item" data-bank-id="{{ $bank->id }}"
                                            data-ten="{{ $bank->ten_ngan_hang }}" data-stk="{{ $bank->so_tai_khoan }}"
                                            data-chu="{{ $bank->chu_tai_khoan }}" data-cn="{{ $bank->chi_nhanh }}"
                                            data-img="{{ $bank->hinh_anh }}">
                                            <img src="{{ $bank->hinh_anh ? (str_starts_with($bank->hinh_anh, 'http') ? $bank->hinh_anh : '/storage/' . ltrim($bank->hinh_anh, '/')) : '/images/default-bank.png' }}"
                                                alt="{{ $bank->ten_ngan_hang }}" class="bank-logo-img">
                                            <span class="bank-name">{{ $bank->ten_ngan_hang }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div id="bank_info" class="border rounded p-3 mb-3 is-disabled">
                                    <div class="d-flex justify-content-center align-items-center mb-3">
                                        <img id="bank_logo" src="" alt="bank" style="height:52px;width:auto;"
                                            class="d-none">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Tên ngân hàng</label>
                                                <input type="text" id="bank_name_input" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Số tài khoản</label>
                                                <div class="input-group">
                                                    <input type="text" id="bank_account" class="form-control" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="copy_stk">Sao chép</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Chủ tài khoản</label>
                                                <input type="text" id="bank_holder" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Chi nhánh</label>
                                                <input type="text" id="bank_branch" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Số tiền muốn nạp (VND) <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="amount" class="form-control"
                                                    placeholder="Nhập số tiền" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-1">Nội dung chuyển khoản <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="transfer_content" class="form-control"
                                                    placeholder="Nội dung chuyển khoản" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick amount selection -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="amount-buttons">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="100000">100K</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="200000">200K</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="500000">500K</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="1000000">1M</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="2000000">2M</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="5000000">5M</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="10000000">10M</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="15000000">15M</button>
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm amount-btn"
                                                        data-amount="20000000">20M</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit button centered in the form -->
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" id="submitBtn" class="btn btn-primary" disabled>
                                            <i class="fas fa-paper-plane mr-1"></i> Tạo yêu cầu nạp
                                        </button>
                                        <!-- Test button for debugging -->
                                        <button type="button" id="testCheckBtn" class="btn btn-warning ml-2"
                                            style="display: none;">
                                            <i class="fas fa-bug mr-1"></i> Test Check
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 col-lg-4">
                            <!-- QR Code Section -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        QR Chuyển khoản
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="qr-placeholder" class="qr-placeholder">
                                        <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Chọn ngân hàng để hiển thị QR</p>
                                    </div>
                                    <div id="qr-loading" class="qr-loading d-none">
                                        <div class="spinner-border text-primary mb-3" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="text-muted mb-0">Đang tạo QR code...</p>
                                    </div>
                                    <div id="qr-code-container" class="d-none">
                                        <div id="qr-code" class="mb-3"></div>
                                        <p class="text-muted small mb-0">Quét mã QR để chuyển khoản nhanh</p>

                                        <!-- QR Code Notes -->
                                        <div class="qr-notes mt-3 text-left">
                                            <div class="alert alert-warning alert-sm mb-2">
                                                <h6 class="mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> Lưu ý
                                                    quan trọng:</h6>
                                                <ul class="mb-0 pl-3 small">
                                                    <li><strong>Vui lòng chuyển khoản đúng với nội dung:</strong> <span
                                                            id="qr-transfer-content"
                                                            class="text-primary font-weight-bold"></span></li>
                                                    <li>Chuyển đúng số tiền: <span id="qr-amount"
                                                            class="text-success font-weight-bold"></span> VND</li>
                                                    <li>Chuyển đến đúng tài khoản: <span id="qr-account"
                                                            class="text-info font-weight-bold"></span></li>
                                                    <li>Sau khi chuyển khoản, tiền sẽ được cộng vào ví trong vòng 5-10
                                                        phút</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lưu ý section moved to bottom -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="fas fa-info-circle mr-1"></i> Lưu ý</h6>
                                <ul class="mb-0 pl-3">
                                    <li>Chọn đúng ngân hàng và chuyển khoản theo thông tin hiển thị.</li>
                                    <li>Nội dung chuyển khoản ghi rõ: Nap tien + số điện thoại.</li>
                                    <li>Sau khi chuyển, hệ thống sẽ kiểm tra và cộng tiền vào ví.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Notifications -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">
                    <i class="fas fa-bell mr-2"></i>
                    Thông báo
                </h5>
            </div>
            <div class="modal-body">
                <div id="notificationContent">
                    <!-- Nội dung thông báo sẽ được chèn vào đây -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalBtn">
                    <i class="fas fa-times mr-1"></i>
                    Đóng
                </button>
                <button type="button" class="btn btn-primary" id="viewHistoryBtn">
                    <i class="fas fa-history mr-1"></i>
                    Xem lịch sử
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .input-group-text {
        min-width: 70px;
        justify-content: center;
    }

    .d-none {
        display: none !important;
    }

    .mr-2 {
        margin-right: .5rem;
    }

    .mb-1 {
        margin-bottom: .25rem;
    }

    .pl-3 {
        padding-left: 1rem;
    }

    .border {
        border: 1px solid #dee2e6;
    }

    .rounded {
        border-radius: .25rem;
    }

    .p-3 {
        padding: 1rem;
    }

    .alert-info {
        color: #055160;
        background-color: #cff4fc;
        border-color: #b6effb;
    }

    .input-group-append .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group .form-control[readonly] {
        background-color: #e9ecef;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    /* Enhance input borders for clearer visuals */
    .form-control {
        border: 1.5px solid #b6b9be;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
    }

    .form-control:disabled,
    .form-control[readonly] {
        border-color: #bfc5ca;
        color: #000;
    }

    .input-group-text {
        border: 1.5px solid #b6b9be;
    }

    /* Ensure input-group elements have consistent strong borders */
    .input-group .form-control {
        border-width: 1.5px;
    }

    .input-group-append .btn {
        border-width: 1.5px;
    }

    /* Bank logo border */
    #bank_logo {
        border: 1.5px solid #b6b9be;
        border-radius: .375rem;
        background-color: #fff;
        padding: .25rem;
    }

    /* Bank logo grid styles */
    .bank-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: .5rem;
    }

    .bank-logo-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: .5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }

    .bank-logo-item:hover {
        border-color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        transform: translateY(-2px);
    }

    .bank-logo-item.selected {
        border-color: #0d6efd;
        background-color: #f8f9ff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
    }

    .bank-logo-img {
        height: 48px;
        width: auto;
        max-width: 100px;
        object-fit: contain;
        margin-bottom: .5rem;
        border-radius: .25rem;
    }

    .bank-name {
        font-size: .8rem;
        font-weight: 500;
        text-align: center;
        color: #495057;
        line-height: 1.2;
    }

    .is-disabled {
        opacity: .6;
        pointer-events: none;
    }

    /* QR Code Section Styles */
    .qr-placeholder {
        padding: 2rem 1rem;
        border: 2px dashed #dee2e6;
        border-radius: .5rem;
        background-color: #f8f9fa;
    }

    .qr-placeholder i {
        color: #6c757d;
    }

    .qr-loading {
        padding: 2rem 1rem;
        border: 2px solid #0d6efd;
        border-radius: .5rem;
        background-color: #f8f9ff;
    }

    .qr-loading .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    .qr-notes .alert-sm {
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .qr-notes .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }

    .qr-notes ul li {
        margin-bottom: 0.25rem;
    }

    .qr-notes .alert {
        text-align: left;
    }

    .qr-notes h6 {
        text-align: left;
    }

    #qr-code {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }

    #qr-code img {
        max-width: 100%;
        height: auto;
        border-radius: .5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .col-lg-4 {
            margin-top: 1.5rem;
        }
    }

    /* Bank info form adjustments */
    #bank_info .form-group {
        margin-bottom: 1rem;
    }

    #bank_info small.text-muted {
        font-size: 0.75rem;
        line-height: 1.2;
    }

    /* Action buttons styling */
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }

    /* Amount buttons styling */
    .amount-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
    }

    .amount-btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
        min-width: 60px;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .amount-btn:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }

    .amount-btn.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }

    /* Modal notification styles */
    #notificationModal .modal-content {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    #notificationModal .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem 1rem;
    }

    #notificationModal .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    #notificationModal .modal-body {
        padding: 1.5rem;
    }

    #notificationModal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem 1.25rem;
    }

    #notificationModal .alert {
        border: none;
        border-radius: 0.5rem;
        margin-bottom: 0;
    }

    #notificationModal .alert h6 {
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    #notificationModal .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
    }

    #notificationModal .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    #notificationModal .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    #notificationModal .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    #notificationModal .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #565e64;
    }

    /* Payment checking status styles */
    #payment-checking-status {
        border-left: 4px solid #0d6efd;
        background-color: #f8f9ff;
    }

    #payment-checking-status .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    #payment-checking-status .progress {
        background-color: rgba(13, 110, 253, 0.1);
    }

    #payment-checking-status .progress-bar {
        background-color: #0d6efd;
    }

    /* Success modal styles */
    .alert-success {
        border-left: 4px solid #198754;
    }

    .alert-warning {
        border-left: 4px solid #ffc107;
    }

    /* Countdown Timer Styles */
    .countdown-timer {
        background-color: rgba(255, 193, 7, 0.1);
        border: 1px solid #ffc107;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }

    .countdown-display {
        display: flex;
        align-items: center;
    }

    .countdown-display .badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
        min-width: 2rem;
        text-align: center;
    }

    .countdown-display .badge.badge-warning {
        background-color: #ffc107;
        color: #000;
        font-weight: 600;
    }

    .countdown-display .badge.badge-danger {
        background-color: #dc3545;
        color: #fff;
        font-weight: 600;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Countdown warning styles */
    .countdown-warning {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: #dc3545;
    }

    .countdown-warning .countdown-display .badge {
        background-color: #dc3545;
        color: #fff;
    }

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bankGrid = document.getElementById('bank_logo_grid');
        const bankInfo = document.getElementById('bank_info');
        const bankLogo = document.getElementById('bank_logo');
        const bankNameInput = document.getElementById('bank_name_input');
        const bankAccount = document.getElementById('bank_account');
        const bankHolder = document.getElementById('bank_holder');
        const bankBranch = document.getElementById('bank_branch');
        const copyStkBtn = document.getElementById('copy_stk');
        const amountInput = document.getElementById('amount');
        const submitBtn = document.getElementById('submitBtn');
        const qrPlaceholder = document.getElementById('qr-placeholder');
        const qrLoading = document.getElementById('qr-loading');
        const qrCodeContainer = document.getElementById('qr-code-container');
        const qrCode = document.getElementById('qr-code');
        const transferContent = document.getElementById('transfer_content');

        // Modal elements
        const notificationModal = document.getElementById('notificationModal');
        const notificationContent = document.getElementById('notificationContent');
        const viewHistoryBtn = document.getElementById('viewHistoryBtn');

        let selectedBankId = null;

        function formatCurrency(value) {
            const digits = value.replace(/[^0-9]/g, '');
            if (!digits) return '';
            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function generateRandomContent() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = 'MUA';

            // Generate 7 random characters in the middle (12 total - 3 for MUA - 2 for AG = 7)
            for (let i = 0; i < 7; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            result += 'AG';
            return result;
        }

        function setFormEnabled(enabled) {
            // Toggle disabled state for interactive elements
            submitBtn.disabled = !enabled;
            copyStkBtn.disabled = !enabled;
            bankNameInput.disabled = !enabled;
            bankAccount.disabled = !enabled;
            bankHolder.disabled = !enabled;
            bankBranch.disabled = !enabled;
            // Visual cue
            bankInfo.classList.toggle('is-disabled', !enabled);
        }

        function hideQRCode() {
            // Hide QR code and loading, show placeholder
            qrPlaceholder.classList.remove('d-none');
            qrLoading.classList.add('d-none');
            qrCodeContainer.classList.add('d-none');
        }

        // Initialize state: visible but disabled until a bank is selected
        setFormEnabled(false);
        bankLogo.classList.add('d-none');
        bankNameInput.value = '';
        bankAccount.value = '';
        bankHolder.value = '';
        bankBranch.value = '';
        if (transferContent) {
            transferContent.value = '';
        }

        amountInput.addEventListener('input', function () {
            const selStart = this.selectionStart;
            const lenBefore = this.value.length;
            this.value = formatCurrency(this.value);
            const lenAfter = this.value.length;
            const diff = lenAfter - lenBefore;
            this.setSelectionRange(selStart + diff, selStart + diff);
            this.classList.remove('is-invalid');

            // Clear active state of amount buttons when user types manually
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Generate new transfer content when amount changes
            if (selectedBankId && this.value.replace(/[^0-9]/g, '') && transferContent) {
                const newContent = generateRandomContent();
                transferContent.value = newContent;
                transferContent.classList.remove('is-invalid');
            }

            // Hide QR code when amount changes
            hideQRCode();
        });

        // Clear validation for transfer content when it changes
        if (transferContent) {
            transferContent.addEventListener('input', function () {
                this.classList.remove('is-invalid');
                // Hide QR code when transfer content changes
                hideQRCode();
            });
        }

        // Handle amount buttons click
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('amount-btn')) {
                const amount = e.target.getAttribute('data-amount');
                amountInput.value = formatCurrency(amount);
                amountInput.classList.remove('is-invalid');

                // Update button states
                document.querySelectorAll('.amount-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                e.target.classList.add('active');

                // Generate new transfer content when amount button is clicked
                if (selectedBankId && transferContent) {
                    const newContent = generateRandomContent();
                    transferContent.value = newContent;
                    transferContent.classList.remove('is-invalid');
                }

                // Hide QR code when amount button is clicked
                hideQRCode();
            }
        });

        // Handle bank logo clicks
        bankGrid.addEventListener('click', function (e) {
            const bankItem = e.target.closest('.bank-logo-item');
            if (!bankItem) return;

            // Remove previous selection
            document.querySelectorAll('.bank-logo-item').forEach(item => {
                item.classList.remove('selected');
            });

            // Add selection to clicked item
            bankItem.classList.add('selected');

            // Get bank data
            selectedBankId = bankItem.getAttribute('data-bank-id');
            const bankName = bankItem.getAttribute('data-ten') || '';
            const bankStk = bankItem.getAttribute('data-stk') || '';
            const bankChu = bankItem.getAttribute('data-chu') || '';
            const bankCn = bankItem.getAttribute('data-cn') || '';
            const bankImg = bankItem.getAttribute('data-img') || '';

            // Update form fields
            bankNameInput.value = bankName;
            bankAccount.value = bankStk;
            bankHolder.value = bankChu;
            bankBranch.value = bankCn;

            // Update logo in info section
            if (bankImg) {
                bankLogo.src = bankImg.startsWith('http') ? bankImg : ('/storage/' + bankImg.replace(
                    /^\/+/, ''));
                bankLogo.classList.remove('d-none');
            } else {
                bankLogo.classList.add('d-none');
            }

            setFormEnabled(true);

            // Generate transfer content automatically
            if (transferContent) {
                const transferContentValue = generateRandomContent();
                transferContent.value = transferContentValue;
            }

            // Hide QR code when bank changes
            hideQRCode();
        });

        function showQRCode(accountNumber, bankName, transferContent, amount) {
            // Show loading state
            qrPlaceholder.classList.add('d-none');
            qrLoading.classList.remove('d-none');
            qrCodeContainer.classList.add('d-none');

            // Send data to backend via axios
            sendNapTienRequest(accountNumber, bankName, transferContent, amount);
        }

        function sendNapTienRequest(accountNumber, bankName, transferContent, amount) {
            // Prepare data for API request
            const requestData = {
                so_tien: amount,
                ngan_hang: bankName,
                so_tai_khoan: accountNumber,
                chu_tai_khoan: bankHolder.value,
                noi_dung: transferContent,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Send axios request
            axios.post('/dashboard/nap-tien', requestData, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    if (response.data.success) {

                        // Gửi thông báo Telegram (không chặn UI nếu lỗi)
                        try {
                            const token = '{{ $cauHinh->token_tele }}';
                            const user = '{{ $user->name }}';
                            const chatId = '{{ $cauHinh->id_tele }}';
                            const message = '[Nạp Tiền]: ' + user +
                                '\nSố tiền: ' + parseInt(amount).toLocaleString() + ' VND';
                            const url = `https://api.telegram.org/bot${token}/sendMessage`;

                            // Gửi tin nhắn Telegram bất đồng bộ, không chặn UI
                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        chat_id: chatId,
                                        text: message
                                    })
                                })
                                .then(response => {
                                    if (response.ok) {
                                        console.log('Tin nhắn Telegram đã gửi thành công');
                                    } else {
                                        console.warn('Gửi tin nhắn Telegram thất bại:', response
                                        .status);
                                    }
                                })
                                .catch(error => {
                                    console.warn('Lỗi gửi tin nhắn Telegram:', error);
                                    // Không hiển thị lỗi cho user vì đây không phải chức năng chính
                                });
                        } catch (error) {
                            console.warn('Lỗi khi gửi thông báo Telegram:', error);
                        }
                        // Hide loading and show QR container
                        qrLoading.classList.add('d-none');
                        qrCodeContainer.classList.remove('d-none');

                        // Generate QR code data
                        const qrData = generateQRData(accountNumber, bankName, transferContent, amount);

                        // Generate QR code image
                        generateQRCodeImage(qrData);

                        // Update QR notes with actual values
                        updateQRNotes(transferContent, amount, accountNumber);

                        // Hiển thị thông báo modal thành công
                        showNotificationModal('success', 'Yêu cầu nạp tiền đã được tạo thành công!',
                            'Vui lòng làm theo hướng dẫn trên màn hình để chuyển khoản đúng thông tin. Sau khi chuyển khoản, tiền sẽ được cộng vào ví trong vòng 5-10 phút.',
                            false);

                        // Hiển thị thông báo hướng dẫn ngay trên màn hình (không chuyển hướng)
                        const baseMessage = response.data.message;
                        const existingNotice = document.getElementById('deposit-instruction');
                        if (existingNotice) {
                            existingNotice.remove();
                        }
                        const noticeHtml = `
                        <div id="deposit-instruction" class="alert alert-success mt-3">
                            <h6 class="mb-2"><i class="fas fa-check-circle mr-1"></i> ${baseMessage}</h6>
                            <ul class="mb-0 pl-3">
                                <li><strong>Vui lòng chuyển khoản theo đúng thông tin hiển thị bên phải</strong> (ngân hàng, số tài khoản, số tiền, nội dung).</li>
                                <li>Sau khi chuyển, hệ thống sẽ tự động kiểm tra và cộng tiền vào ví trong 5-10 phút.</li>
                                <li>Bạn có thể mở trang <a href="{{ route('dashboard.lich-su-nap-rut') }}" target="_blank">lịch sử nạp/rút</a> để theo dõi trạng thái.</li>
                            </ul>
                        </div>
                    `;
                        // Chèn thông báo ngay dưới form thông tin ngân hàng
                        const formEl = document.getElementById('bank_info');
                        if (formEl) {
                            formEl.insertAdjacentHTML('afterend', noticeHtml);
                        } else {
                            document.body.insertAdjacentHTML('afterbegin', noticeHtml);
                        }

                        console.log('Yêu cầu nạp tiền đã được tạo:', response.data.data);
                        transferContent = response.data.data.noi_dung;
                        console.log('transferContent:', transferContent);

                        // Bắt đầu kiểm tra trạng thái thanh toán sau khi tạo yêu cầu thành công
                        if (transferContent) {
                            const transferContentValue = transferContent.trim();
                            console.log('=== KIỂM TRA TRƯỚC KHI GỌI HÀM KIỂM TRA ===');
                            console.log('transferContent:', transferContent);
                            console.log(
                                '✅ Có nội dung chuyển khoản, bắt đầu kiểm tra trạng thái thanh toán');
                            console.log('Nội dung:', transferContentValue);
                            startPaymentStatusCheck(transferContentValue,
                            31); // Kiểm tra tối đa 5 phút (300 giây)
                        } else {
                            console.log('❌ Element transferContent không tồn tại hoặc không có giá trị');
                        }
                    } else {
                        throw new Error(response.data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi tạo yêu cầu nạp tiền:', error);

                    // Hide loading and show error
                    qrLoading.classList.add('d-none');
                    qrPlaceholder.classList.remove('d-none');

                    // Show error message
                    let errorMessage = 'Có lỗi xảy ra khi tạo yêu cầu nạp tiền';
                    let errorType = 'error';

                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;

                        // Kiểm tra nếu là lỗi về quá nhiều yêu cầu nạp chưa thành công
                        if (errorMessage.includes('3 yêu cầu nạp tiền chưa được xử lý')) {
                            errorType = 'warning';

                            // Hiển thị thông báo chi tiết hơn
                            const existingNotice = document.getElementById('deposit-limit-notice');
                            if (existingNotice) {
                                existingNotice.remove();
                            }

                            const noticeHtml = `
                            <div id="deposit-limit-notice" class="alert alert-warning mt-3">
                                <h6 class="mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> Không thể tạo yêu cầu nạp tiền mới</h6>
                                <p class="mb-2">${errorMessage}</p>
                                <div class="mt-2">
                                    <a href="{{ route('dashboard.lich-su-nap-rut') }}" class="btn btn-sm btn-outline-primary mr-2">
                                        <i class="fas fa-history mr-1"></i> Xem lịch sử giao dịch
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="showContactInfo()">
                                        <i class="fas fa-phone mr-1"></i> Liên hệ hỗ trợ
                                    </button>
                                </div>
                            </div>
                        `;

                            // Chèn thông báo ngay dưới form thông tin ngân hàng
                            const formEl = document.getElementById('bank_info');
                            if (formEl) {
                                formEl.insertAdjacentHTML('afterend', noticeHtml);
                            } else {
                                document.body.insertAdjacentHTML('afterbegin', noticeHtml);
                            }
                        }
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    // Hiển thị thông báo lỗi bằng modal
                    showNotificationModal(errorType, 'Có lỗi xảy ra', errorMessage, true);
                });
        }

        function generateQRData(accountNumber, bankName, transferContent, amount) {
            // This is a simplified example - in reality, you'd generate proper bank transfer data
            return `acc=${accountNumber}&bank=${bankName}&amount=${amount}&des=${transferContent}`;
        }

        function generateQRCodeImage(data) {
            // Using a free QR code API for demo purposes
            // In production, consider using a proper QR code library
            const qrUrl =
                `https://qr.sepay.vn/img?${data}`;

            qrCode.innerHTML = `<img src="${qrUrl}" alt="QR Code" class="img-fluid">`;
        }

        function updateQRNotes(transferContent, amount, accountNumber) {
            // Update the notes with actual values
            document.getElementById('qr-transfer-content').textContent = transferContent;
            document.getElementById('qr-amount').textContent = formatCurrency(amount);
            document.getElementById('qr-account').textContent = accountNumber;
        }

        // Function to show notification modal
        function showNotificationModal(type, title, message, showHistoryBtn = true) {
            // Set modal title and icon based on type
            const modalTitle = document.getElementById('notificationModalLabel');
            let iconClass = 'fas fa-bell';
            let titleText = 'Thông báo';

            switch (type) {
                case 'success':
                    iconClass = 'fas fa-check-circle text-success';
                    titleText = 'Thành công';
                    break;
                case 'error':
                    iconClass = 'fas fa-exclamation-circle text-danger';
                    titleText = 'Lỗi nạp tiền';
                    break;
                case 'warning':
                    iconClass = 'fas fa-exclamation-triangle text-warning';
                    titleText = 'Cảnh báo';
                    break;
                case 'info':
                    iconClass = 'fas fa-info-circle text-info';
                    titleText = 'Thông tin';
                    break;
            }

            modalTitle.innerHTML = `<i class="${iconClass} mr-2"></i>${titleText}`;

            // Set modal content
            notificationContent.innerHTML = `
            <div class="alert alert-${type === 'error' ? 'danger' : type}">
                <h6 class="mb-2">${title}</h6>
                <p class="mb-0">${message}</p>
            </div>
        `;

            // Show/hide history button
            viewHistoryBtn.style.display = showHistoryBtn ? 'inline-block' : 'none';

            // Show modal
            $(notificationModal).modal('show');
        }

        // Handle close modal button click
        const closeModalBtn = document.getElementById('closeModalBtn');
        closeModalBtn.addEventListener('click', function () {
            // Close modal safely
            $(notificationModal).modal('hide');
        });

        // Handle modal close events
        $(notificationModal).on('hidden.bs.modal', function () {
            // Clear any pending operations when modal is closed
            console.log('Modal đã được đóng');
        });

        // Handle ESC key to close modal
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && $(notificationModal).hasClass('show')) {
                $(notificationModal).modal('hide');
            }
        });

        // Handle view history button click
        viewHistoryBtn.addEventListener('click', function () {
            // Close modal first
            $(notificationModal).modal('hide');

            // Navigate to history page
            window.open('{{ route("dashboard.lich-su-nap-rut"). "?loai=nap" }}', '_blank');
        });

        copyStkBtn.addEventListener('click', function () {
            bankAccount.select();
            document.execCommand('copy');
            showNotificationModal('success', 'Đã sao chép',
                'Số tài khoản đã được sao chép vào clipboard', false);
        });


        submitBtn.addEventListener('click', function () {
            let valid = true;

            // Clear previous validation errors
            amountInput.classList.remove('is-invalid');
            if (transferContent) {
                transferContent.classList.remove('is-invalid');
            }

            if (!selectedBankId) {
                // Show error for bank selection
                const firstBankItem = document.querySelector('.bank-logo-item');
                if (firstBankItem) {
                    firstBankItem.style.borderColor = '#dc3545';
                    setTimeout(() => firstBankItem.style.borderColor = '', 2000);
                }
                valid = false;
            }

            if (!amountInput.value.replace(/[^0-9]/g, '')) {
                amountInput.classList.add('is-invalid');
                valid = false;
            }

            if (!transferContent || !transferContent.value || !transferContent.value.trim()) {
                if (transferContent) {
                    transferContent.classList.add('is-invalid');
                }
                valid = false;
            }

            if (!valid) return;

            // Show QR code when creating deposit request
            const amount = amountInput.value.replace(/[^0-9]/g, '');
            const transferContentValue = transferContent ? transferContent.value : '';
            showQRCode(bankAccount.value, bankNameInput.value, transferContentValue, amount);
        });

        // Function to show contact information
        window.showContactInfo = function () {
            const contactInfo = `
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle mr-1"></i> Thông tin liên hệ hỗ trợ</h6>
                <p class="mb-2">Nếu bạn cần hỗ trợ về giao dịch nạp tiền, vui lòng liên hệ:</p>
                <ul class="mb-0">
                    <li><strong>Email:</strong> support@example.com</li>
                    <li><strong>Hotline:</strong> 1900-xxxx</li>
                    <li><strong>Thời gian hỗ trợ:</strong> 8:00 - 22:00 (Thứ 2 - Chủ nhật)</li>
                </ul>
            </div>
        `;

            // Remove existing contact info if any
            const existingContact = document.getElementById('contact-info');
            if (existingContact) {
                existingContact.remove();
            }

            // Add contact info
            const contactDiv = document.createElement('div');
            contactDiv.id = 'contact-info';
            contactDiv.innerHTML = contactInfo;

            const depositLimitNotice = document.getElementById('deposit-limit-notice');
            if (depositLimitNotice) {
                depositLimitNotice.insertAdjacentElement('afterend', contactDiv);
            } else {
                document.body.insertAdjacentElement('afterbegin', contactDiv);
            }
        };

        // Biến để lưu trữ interval ID và trạng thái kiểm tra
        let paymentCheckInterval = null;
        let countdownInterval = null;
        let isCheckingPayment = false;
        let currentTransferContent = null;

        /**
         * Hàm kiểm tra trạng thái thanh toán
         * @param {string} transferContent - Nội dung chuyển khoản
         * @param {number} maxAttempts - Số lần kiểm tra tối đa (mặc định 300 = 5 phút)
         */
        function startPaymentStatusCheck(transferContent, maxAttempts = 300) {
            console.log('=== BẮT ĐẦU KIỂM TRA TRẠNG THÁI THANH TOÁN ===');
            console.log('Nội dung chuyển khoản:', transferContent);
            console.log('Số lần kiểm tra tối đa:', maxAttempts);
            console.log('Đang kiểm tra:', isCheckingPayment);

            if (isCheckingPayment) {
                console.log('Đang kiểm tra trạng thái thanh toán, bỏ qua yêu cầu mới');
                return;
            }

            isCheckingPayment = true;
            currentTransferContent = transferContent;
            let attemptCount = 0;

            console.log('Bắt đầu kiểm tra trạng thái thanh toán:', transferContent);

            // Hiển thị thông báo đang kiểm tra
            showPaymentCheckingStatus();

            // Bắt đầu đếm ngược thời gian (5 phút = 300 giây)
            startCountdownTimer(300);

            // Tạo interval kiểm tra mỗi 1 giây
            paymentCheckInterval = setInterval(() => {
                attemptCount++;

                console.log(`Kiểm tra lần ${attemptCount}/${maxAttempts}`);

                // Gọi API kiểm tra trạng thái
                checkPaymentStatus(transferContent)
                    .then(response => {
                        if (response.success) {
                            switch (response.status) {
                                case 'completed':
                                    // Giao dịch thành công
                                    clearPaymentCheckInterval();
                                    showPaymentSuccessModal(response.data);
                                    break;
                                case 'pending':
                                    // Vẫn đang chờ xử lý
                                    updatePaymentCheckingStatus(attemptCount, maxAttempts, response
                                        .message);
                                    break;
                                case 'not_found':
                                    // Chưa tìm thấy giao dịch
                                    updatePaymentCheckingStatus(attemptCount, maxAttempts,
                                        'Đang chờ giao dịch...');
                                    break;
                            }
                        } else {
                            console.error('Lỗi khi kiểm tra trạng thái:', response.message);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gọi API kiểm tra trạng thái:', error);
                    });

                // Kiểm tra nếu đã hết số lần thử
                if (attemptCount >= maxAttempts) {
                    clearPaymentCheckInterval();
                    showPaymentTimeoutModal();
                }
            }, 1000); // Kiểm tra mỗi 1 giây
        }

        /**
         * Gọi API kiểm tra trạng thái thanh toán
         * @param {string} transferContent - Nội dung chuyển khoản
         * @returns {Promise} - Promise trả về kết quả API
         */
        function checkPaymentStatus(transferContent) {
            console.log('Gọi API kiểm tra trạng thái với nội dung:', transferContent);

            return axios.post('/dashboard/check-payment-status', {
                    noi_dung: transferContent,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    console.log('API response:', response.data);
                    return response.data;
                })
                .catch(error => {
                    console.error('Lỗi API:', error);
                    throw error;
                });
        }

        /**
         * Dừng việc kiểm tra trạng thái thanh toán
         */
        function clearPaymentCheckInterval() {
            if (paymentCheckInterval) {
                clearInterval(paymentCheckInterval);
                paymentCheckInterval = null;
            }
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            isCheckingPayment = false;
            currentTransferContent = null;
            hidePaymentCheckingStatus();
        }

        /**
         * Bắt đầu đếm ngược thời gian
         * @param {number} totalSeconds - Tổng số giây đếm ngược
         */
        function startCountdownTimer(totalSeconds) {
            let remainingSeconds = totalSeconds;
            
            // Cập nhật hiển thị ban đầu
            updateCountdownDisplay(remainingSeconds);
            
            countdownInterval = setInterval(() => {
                remainingSeconds--;
                updateCountdownDisplay(remainingSeconds);
                
                // Kiểm tra nếu hết thời gian
                if (remainingSeconds <= 0) {
                    clearCountdownTimer();
                    handlePaymentTimeout();
                }
            }, 1000);
        }

        /**
         * Cập nhật hiển thị đếm ngược
         * @param {number} seconds - Số giây còn lại
         */
        function updateCountdownDisplay(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            
            const minutesEl = document.getElementById('countdown-minutes');
            const secondsEl = document.getElementById('countdown-seconds');
            const countdownTimer = document.querySelector('.countdown-timer');
            
            if (minutesEl && secondsEl) {
                minutesEl.textContent = minutes.toString().padStart(2, '0');
                secondsEl.textContent = remainingSeconds.toString().padStart(2, '0');
                
                // Thay đổi màu sắc khi còn ít thời gian
                if (seconds <= 60) { // Còn 1 phút
                    minutesEl.className = 'badge badge-danger mr-1';
                    secondsEl.className = 'badge badge-danger ml-1';
                    if (countdownTimer) {
                        countdownTimer.classList.add('countdown-warning');
                    }
                } else if (seconds <= 120) { // Còn 2 phút
                    minutesEl.className = 'badge badge-warning mr-1';
                    secondsEl.className = 'badge badge-warning ml-1';
                }
            }
        }

        /**
         * Dừng đếm ngược
         */
        function clearCountdownTimer() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        }

        /**
         * Xử lý khi hết thời gian thanh toán
         */
        function handlePaymentTimeout() {
            console.log('Hết thời gian thanh toán, bắt đầu hủy giao dịch');
            
            // Dừng kiểm tra trạng thái
            clearPaymentCheckInterval();
            
            // Gọi API hủy giao dịch nếu có nội dung chuyển khoản
            if (currentTransferContent) {
                cancelPaymentRequest(currentTransferContent);
            } else {
                showPaymentTimeoutModal();
            }
        }

        /**
         * Gọi API hủy giao dịch
         * @param {string} transferContent - Nội dung chuyển khoản
         */
        function cancelPaymentRequest(transferContent) {
            console.log('Gọi API hủy giao dịch với nội dung:', transferContent);
            
            axios.post('/dashboard/cancel-payment', {
                noi_dung: transferContent,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Hủy giao dịch thành công:', response.data);
                showPaymentCancelledModal();
            })
            .catch(error => {
                console.error('Lỗi khi hủy giao dịch:', error);
                showPaymentTimeoutModal();
            });
        }

        /**
         * Hiển thị trạng thái đang kiểm tra thanh toán
         */
        function showPaymentCheckingStatus() {
            // Xóa thông báo cũ nếu có
            const existingStatus = document.getElementById('payment-checking-status');
            if (existingStatus) {
                existingStatus.remove();
            }

            const statusHtml = `
            <div id="payment-checking-status" class="alert alert-info mt-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-1"><i class="fas fa-search mr-1"></i> Vui lòng thanh toán...</h6>
                            <p class="mb-0 small" id="payment-check-message">Đang chờ giao dịch...</p>
                            
                            <!-- Countdown Timer -->
                            <div class="countdown-timer mt-2 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-warning mr-2"></i>
                                    <span class="text-muted small mr-2">Thời gian còn lại:</span>
                                    <div class="countdown-display">
                                        <span id="countdown-minutes" class="badge badge-warning mr-1">05</span>
                                        <span class="text-muted small">:</span>
                                        <span id="countdown-seconds" class="badge badge-warning ml-1">00</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%" id="payment-check-progress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ml-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="stop-payment-check">
                            <i class="fas fa-stop mr-1"></i> Dừng
                        </button>
                    </div>
                </div>
            </div>
        `;

            // Chèn thông báo vào sau form thông tin ngân hàng
            const formEl = document.getElementById('bank_info');
            if (formEl) {
                formEl.insertAdjacentHTML('afterend', statusHtml);

                // Thêm event listener cho nút dừng kiểm tra
                const stopBtn = document.getElementById('stop-payment-check');
                if (stopBtn) {
                    stopBtn.addEventListener('click', function () {
                        clearPaymentCheckInterval();
                        showNotificationModal('info', 'Đã dừng kiểm tra',
                            'Bạn đã dừng việc kiểm tra trạng thái thanh toán. Bạn có thể kiểm tra lịch sử giao dịch để theo dõi trạng thái.',
                            true);
                    });
                }
            }
        }

        /**
         * Cập nhật trạng thái kiểm tra thanh toán
         * @param {number} current - Số lần kiểm tra hiện tại
         * @param {number} total - Tổng số lần kiểm tra
         * @param {string} message - Thông báo trạng thái
         */
        function updatePaymentCheckingStatus(current, total, message) {
            const messageEl = document.getElementById('payment-check-message');
            const progressEl = document.getElementById('payment-check-progress');

            if (messageEl) {
                messageEl.textContent = message;
            }

            if (progressEl) {
                const percentage = (current / total) * 100;
                progressEl.style.width = percentage + '%';
            }
        }

        /**
         * Ẩn trạng thái kiểm tra thanh toán
         */
        function hidePaymentCheckingStatus() {
            const statusEl = document.getElementById('payment-checking-status');
            if (statusEl) {
                statusEl.remove();
            }
        }

        /**
         * Hiển thị modal thông báo thanh toán thành công
         * @param {Object} data - Dữ liệu giao dịch
         */
        function showPaymentSuccessModal(data) {
            const successMessage = `
            <div class="alert alert-success">
                <h6 class="mb-2"><i class="fas fa-check-circle mr-1"></i> Thanh toán thành công!</h6>
                <p class="mb-2">Giao dịch nạp tiền của bạn đã được xử lý thành công.</p>
                <div class="row">
                    <div class="col-6">
                        <strong>Số tiền:</strong><br>
                        <span class="text-success">${parseInt(data.so_tien).toLocaleString()} VND</span>
                    </div>
                    <div class="col-6">
                        <strong>Thời gian:</strong><br>
                        <span class="text-info">${data.updated_at}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <strong>Nội dung:</strong> ${data.noi_dung}
                </div>
            </div>
        `;

            showNotificationModal('success', 'Thanh toán thành công', successMessage, true);

            // Xóa nội dung form khi giao dịch thành công
            clearFormData();

            // Tự động chuyển hướng đến trang lịch sử sau 3 giây
            setTimeout(() => {
                window.location.href = '{{ route("dashboard.lich-su-nap-rut") }}?loai=nap';
            }, 3000);
        }

        /**
         * Hiển thị modal thông báo hết thời gian chờ
         */
        function showPaymentTimeoutModal() {
            const timeoutMessage = `
            <div class="alert alert-warning">
                <h6 class="mb-2"><i class="fas fa-clock mr-1"></i> Hết thời gian chờ</h6>
                <p class="mb-2">Hệ thống đã kiểm tra trong 5 phút nhưng chưa phát hiện giao dịch.</p>
                <p class="mb-0">Vui lòng kiểm tra lại thông tin chuyển khoản hoặc liên hệ hỗ trợ nếu bạn đã thực hiện giao dịch.</p>
            </div>
        `;

            showNotificationModal('warning', 'Hết thời gian chờ', timeoutMessage, true);

            // Xóa nội dung form khi giao dịch quá hạn
            clearFormData();
        }

        /**
         * Hiển thị modal thông báo giao dịch bị hủy
         */
        function showPaymentCancelledModal() {
            const cancelledMessage = `
            <div class="alert alert-danger">
                <h6 class="mb-2"><i class="fas fa-times-circle mr-1"></i> Giao dịch đã bị hủy</h6>
                <p class="mb-2">Giao dịch nạp tiền đã bị hủy do hết thời gian chờ thanh toán (5 phút).</p>
                <p class="mb-2">Nếu bạn đã thực hiện chuyển khoản, vui lòng liên hệ hỗ trợ để được xử lý.</p>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="createNewDepositRequest()">
                        <i class="fas fa-plus mr-1"></i> Tạo yêu cầu mới
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="showContactInfo()">
                        <i class="fas fa-phone mr-1"></i> Liên hệ hỗ trợ
                    </button>
                </div>
            </div>
        `;

            showNotificationModal('error', 'Giao dịch bị hủy', cancelledMessage, true);

            // Xóa nội dung form khi giao dịch bị hủy
            clearFormData();
        }

        /**
         * Tạo yêu cầu nạp tiền mới
         */
        window.createNewDepositRequest = function() {
            // Sử dụng hàm clearFormData để xóa toàn bộ nội dung form
            clearFormData();
            
            // Close modal
            $(notificationModal).modal('hide');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        // Hàm để bắt đầu kiểm tra trạng thái thanh toán (sẽ được gọi từ sendNapTienRequest)
        window.startPaymentStatusCheck = startPaymentStatusCheck;

        /**
         * Xóa nội dung form chuyển khoản
         */
        function clearFormData() {
            console.log('Đang xóa nội dung form chuyển khoản...');
            
            // Xóa nội dung các trường input
            if (amountInput) {
                amountInput.value = '';
                amountInput.classList.remove('is-invalid');
            }
            
            if (transferContent) {
                transferContent.value = '';
                transferContent.classList.remove('is-invalid');
            }
            
            // Xóa lựa chọn ngân hàng
            document.querySelectorAll('.bank-logo-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Reset form về trạng thái ban đầu
            setFormEnabled(false);
            bankLogo.classList.add('d-none');
            bankNameInput.value = '';
            bankAccount.value = '';
            bankHolder.value = '';
            bankBranch.value = '';
            
            // Xóa trạng thái active của các nút amount
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Ẩn QR code
            hideQRCode();
            
            // Xóa các thông báo trạng thái
            const existingStatus = document.getElementById('payment-checking-status');
            if (existingStatus) {
                existingStatus.remove();
            }
            
            const existingNotice = document.getElementById('deposit-instruction');
            if (existingNotice) {
                existingNotice.remove();
            }
            
            const existingLimitNotice = document.getElementById('deposit-limit-notice');
            if (existingLimitNotice) {
                existingLimitNotice.remove();
            }
            
            console.log('Đã xóa nội dung form chuyển khoản thành công');
        }

        // Test button functionality
        const testCheckBtn = document.getElementById('testCheckBtn');
        if (testCheckBtn) {
            testCheckBtn.addEventListener('click', function () {
                if (transferContent && transferContent.value) {
                    const transferContentValue = transferContent.value.trim();
                    if (transferContentValue) {
                        console.log('🧪 TEST: Bắt đầu kiểm tra trạng thái với nội dung:',
                            transferContentValue);
                        startPaymentStatusCheck(transferContentValue, 10); // Test với 10 lần kiểm tra
                    } else {
                        alert('Vui lòng nhập nội dung chuyển khoản trước khi test');
                    }
                } else {
                    alert('Không tìm thấy trường nội dung chuyển khoản');
                }
            });
        }

        // Hiển thị nút test khi có nội dung chuyển khoản
        if (transferContent) {
            transferContent.addEventListener('input', function () {
                const testBtn = document.getElementById('testCheckBtn');
                if (testBtn) {
                    testBtn.style.display = this.value.trim() ? 'inline-block' : 'none';
                }
            });
        }
    });

</script>
@endpush
