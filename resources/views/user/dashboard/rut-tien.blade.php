@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet mr-2"></i>
                        Rút tiền
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="withdrawalForm" autocomplete="off">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Thông tin ngân hàng rút tiền <span
                                            class="text-danger">*</span></label>
                                    <div id="bank_info" class="border rounded p-3 mb-4">
                                        @if($user->profile && $user->profile->ngan_hang)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold mb-1">Tên ngân hàng</label>
                                                    <input type="text" id="bank_name_input" class="form-control"
                                                        value="{{ $user->profile->ngan_hang }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold mb-1">Số tài khoản</label>
                                                    <input type="text" id="bank_account" class="form-control"
                                                        value="{{ $user->profile->so_tai_khoan }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold mb-1">Chủ tài khoản</label>
                                                    <input type="text" id="bank_holder" class="form-control"
                                                        value="{{ $user->profile->chu_tai_khoan }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold mb-1">Số dư hiện tại</label>
                                                    <input type="text" id="current_balance" class="form-control"
                                                        value="{{ number_format($user->profile->so_du ?? 0, 0, ',', '.') }} VND"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold">Số tiền muốn rút (VND) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="amount" class="form-control"
                                                        placeholder="Nhập số tiền muốn rút" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold">Mật khẩu rút tiền <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" id="withdrawal_password" class="form-control"
                                                        placeholder="Nhập mật khẩu rút tiền" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick amount selection -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold mb-2">Chọn nhanh số tiền:</label>
                                                    <div class="amount-buttons">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm amount-btn"
                                                            data-amount="50000">50K</button>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Bạn chưa cập nhật thông tin ngân hàng. Vui lòng <a
                                                href="{{ route('dashboard.ngan-hang') }}" class="alert-link">cập nhật
                                                thông tin ngân hàng</a> trước khi rút tiền.
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                @if($user->profile && $user->profile->ngan_hang)

                                <!-- Submit button centered in the form -->
                                <div class="d-flex justify-content-center mt-5">
                                    <button type="button" id="submitBtn" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i> Tạo yêu cầu rút tiền
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Lưu ý section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="fas fa-info-circle mr-1"></i> Lưu ý</h6>
                                <ul class="mb-0 pl-3">
                                    <li>Số tiền rút tối thiểu là 50,000 VND.</li>
                                    <li>Tiền sẽ được chuyển vào tài khoản ngân hàng đã đăng ký.</li>
                                    <li>Thời gian xử lý: 1-3 ngày làm việc.</li>
                                    <li>Vui lòng kiểm tra kỹ thông tin ngân hàng trước khi rút tiền.</li>
                                </ul>
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

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    .form-control[readonly] {
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

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .col-lg-4 {
            margin-top: 1.5rem;
        }
    }

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DEBUG: DOMContentLoaded - Bắt đầu khởi tạo trang rút tiền');

        const amountInput = document.getElementById('amount');
        const withdrawalPasswordInput = document.getElementById('withdrawal_password');
        const submitBtn = document.getElementById('submitBtn');

        console.log('DEBUG: Các element được tìm thấy:', {
            amountInput: !!amountInput,
            withdrawalPasswordInput: !!withdrawalPasswordInput,
            submitBtn: !!submitBtn
        });

        let currentBalance = {{ $user->profile->so_du ? $user->profile->so_du : 0 }};
        console.log('DEBUG: Số dư hiện tại:', currentBalance);

        function formatCurrency(value) {
            const digits = value.replace(/[^0-9]/g, '');
            if (!digits) return '';
            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // Handle amount input formatting
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
        });

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
            }
        });


        // Handle form submission
        if (submitBtn) {
            submitBtn.addEventListener('click', function () {
                console.log('DEBUG: Submit button clicked');
                let valid = true;

                // Clear previous validation errors
                amountInput.classList.remove('is-invalid');
                withdrawalPasswordInput.classList.remove('is-invalid');

                const amount = amountInput.value.replace(/[^0-9]/g, '');
                console.log('DEBUG: Amount after formatting:', amount);

                // Validate amount
                if (!amount || parseInt(amount) < 50000) {
                    console.log('DEBUG: Amount validation failed');
                    amountInput.classList.add('is-invalid');
                    valid = false;
                }

                // Check if amount exceeds balance
                if (parseInt(amount) > currentBalance) {
                    console.log('DEBUG: Amount exceeds balance');
                    amountInput.classList.add('is-invalid');
                    if (typeof showToast === 'function') {
                        console.log('DEBUG: Calling showToast for balance error');
                        showToast('error', 'Số tiền rút không được vượt quá số dư hiện tại');
                    } else {
                        console.log('DEBUG: showToast function not available, using alert');
                        alert('Số tiền rút không được vượt quá số dư hiện tại');
                    }
                    valid = false;
                }

                // Validate withdrawal password
                if (!withdrawalPasswordInput.value.trim()) {
                    console.log('DEBUG: Withdrawal password validation failed');
                    withdrawalPasswordInput.classList.add('is-invalid');
                    valid = false;
                }

                if (!valid) {
                    console.log('DEBUG: Form validation failed, stopping submission');
                    return;
                }

                // Show confirmation using toast.js confirm function
                console.log('DEBUG: Showing confirmation using toast.js confirm function');

                const bankName = document.getElementById('bank_name_input').value;
                const bankAccount = document.getElementById('bank_account').value;
                const bankHolder = document.getElementById('bank_holder').value;

                confirm({
                    title: 'Xác nhận rút tiền',
                    message: `Bạn có chắc chắn muốn rút tiền?\n\nSố tiền: ${formatCurrency(amount)} VND\nNgân hàng: ${bankName}\nSố tài khoản: ${bankAccount}\nChủ tài khoản: ${bankHolder}\n\nYêu cầu rút tiền sẽ được xử lý trong 3-15 phút làm việc.`,
                    confirmText: 'Xác nhận rút tiền',
                    onConfirm: () => {
                        console.log('DEBUG: User confirmed withdrawal');
                        createWithdrawalRequest(amount, withdrawalPasswordInput.value);
                    }
                });
            });
        }


        function createWithdrawalRequest(amount, password) {
            console.log('DEBUG: createWithdrawalRequest called with:', {
                amount,
                password: '***'
            });

            // Prepare data for API request
            const requestData = {
                so_tien: amount,
                mat_khau_rut_tien: password,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            console.log('DEBUG: Request data prepared:', {
                ...requestData,
                mat_khau_rut_tien: '***'
            });

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...';

            console.log('DEBUG: Sending axios request to /dashboard/rut-tien');

            // Send axios request
            axios.post('/dashboard/rut-tien', requestData, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    console.log('DEBUG: Axios response received:', response);
                    if (response.data.success) {
                        const token = '{{ $cauHinh->token_tele }}'
                        // Thay bằng token của bạn
                        const user = '{{ $user->name }}';
                        const chatId = '{{ $cauHinh->id_tele }}'; // Thay bằng chat ID của bạn
                        const message = '[Rút Tiền]: ' + user +
                            '\nSố tiền: ' + parseInt(amount).toLocaleString() + ' VND';
                        const url =
                            `https://api.telegram.org/bot${token}/sendMessage`;
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
                            .then(response => response.json())
                            .then(data => {
                                console.log('Tin nhắn đã gửi:', data);
                            })
                            .catch(error => {
                                console.error('Lỗi:', error);
                            });

                        console.log('DEBUG: Request successful, showing success message');
                        // Show success message with countdown
                        let countdown = 3;
                        const baseMessage = response.data.message;

                        // Tạo toast tùy chỉnh với countdown
                        const toastId = 'countdown-toast-' + Date.now();
                        const toastHtml = `
                        <div id="${toastId}" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong class="me-auto">Thành công</strong>
                                </div>
                                <div class="toast-body" id="${toastId}-body">
                                    ${baseMessage} Bạn sẽ được chuyển đến trang lịch sử sau <span id="${toastId}-countdown">${countdown}</span> giây...
                                </div>
                            </div>
                        </div>
                    `;

                        // Thêm toast vào body
                        document.body.insertAdjacentHTML('beforeend', toastHtml);

                        console.log('DEBUG: Yêu cầu rút tiền đã được tạo:', response.data.data);

                        // Show countdown and redirect
                        const countdownInterval = setInterval(function () {
                            countdown--;
                            const countdownElement = document.getElementById(toastId +
                                '-countdown');
                            if (countdownElement) {
                                countdownElement.textContent = countdown;
                            }

                            if (countdown <= 0) {
                                clearInterval(countdownInterval);
                                console.log('DEBUG: Redirecting to history page');
                                // Xóa toast trước khi chuyển trang
                                const toastElement = document.getElementById(toastId);
                                if (toastElement) {
                                    toastElement.remove();
                                }
                                window.location.href = '{{ route("dashboard.lich-su-nap-rut") }}';
                            }
                        }, 1000);
                    } else {
                        console.log('DEBUG: Request failed:', response.data.message);
                        throw new Error(response.data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('DEBUG: Lỗi khi tạo yêu cầu rút tiền:', error);

                    // Show error message
                    let errorMessage = 'Có lỗi xảy ra khi tạo yêu cầu rút tiền';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    console.log('DEBUG: Error message to show:', errorMessage);

                    if (typeof showToast === 'function') {
                        console.log('DEBUG: Calling showToast for error message');
                        showToast('error', errorMessage);
                    } else {
                        console.log('DEBUG: showToast not available, using alert');
                        alert(errorMessage);
                    }
                })
                .finally(() => {
                    console.log('DEBUG: Request completed, re-enabling submit button');
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Tạo yêu cầu rút tiền';
                });
        }
    });

</script>
@endpush
