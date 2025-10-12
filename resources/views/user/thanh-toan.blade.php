@extends('user.layouts.app')

@section('title', 'Thanh Toán - Web Đầu Tư')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <div class="icon-circle mx-auto mb-3">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <h1 class="h2 fw-bold text-primary mb-3">Thanh Toán</h1>
                <p class="text-muted fs-5">Nhập thông tin thanh toán dưới dạng JSON để xử lý giao dịch</p>
            </div>

            <!-- Payment Form Card -->
            <div class="card dashboard-card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 me-2"></i>
                        <h5 class="mb-0 fw-semibold">Thông Tin Thanh Toán</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="paymentForm" method="POST" action="{{ route('thanh-toan.process') }}">
                        @csrf
                        
                        <!-- JSON Input Section -->
                        <div class="mb-4">
                            <label for="paymentData" class="form-label fw-semibold text-dark">
                                <i class="bi bi-code-slash me-2"></i>Dữ liệu thanh toán (JSON)
                            </label>
                            <div class="position-relative">
                                <textarea 
                                    id="paymentData" 
                                    name="payment_data" 
                                    class="form-control @error('payment_data') is-invalid @enderror" 
                                    rows="12" 
                                    value='{
  "gateway": "TPBank",
  "transactionDate": "2025-10-02 17:09:51",
  "accountNumber": "11223344",
  "subAccount": null,
  "code": null,
  "content": "MUA3IKG3VHAG",
  "transferType": "in",
  "description": "MUA3IKG3VHAG",
  "transferAmount": 200000,
  "referenceCode": "113121141",
  "accumulated": 268386,
  "id": 25046296
}'
                                    placeholder='{
  "gateway": "TPBank",
  "transactionDate": "2025-10-02 17:09:51",
  "accountNumber": "11223344",
  "subAccount": null,
  "code": null,
  "content": "MUA3IKG3VHAG",
  "transferType": "in",
  "description": "MUA3IKG3VHAG",
  "transferAmount": 200000,
  "referenceCode": "113121141",
  "accumulated": 268386,
  "id": 25046296
}'
                                    required
                                >
                            {
  "gateway": "TPBank",
  "transactionDate": "2025-10-02 17:09:51",
  "accountNumber": "11223344",
  "subAccount": null,
  "code": null,
  "content": "MUA3IKG3VHAG",
  "transferType": "in",
  "description": "MUA3IKG3VHAG",
  "transferAmount": 200000,
  "referenceCode": "113121141",
  "accumulated": 268386,
  "id": 25046296
}</textarea>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Nhập thông tin thanh toán theo định dạng JSON hợp lệ
                                </div>
                            </div>
                            @error('payment_data')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- JSON Validation Status -->
                        <div id="jsonValidation" class="mb-4" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span id="validationMessage">JSON hợp lệ</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <button type="button" id="validateJsonBtn" class="btn btn-outline-primary btn-lg px-4">
                                <i class="bi bi-check2-square me-2"></i>
                                Kiểm tra JSON
                            </button>
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg px-5" disabled>
                                <i class="bi bi-send me-2"></i>
                                Gửi Thông Tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Example JSON Structure -->
            <div class="card dashboard-card mt-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightbulb me-2"></i>
                        Cấu trúc JSON mẫu
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Thông tin cơ bản:</h6>
                            <ul class="list-unstyled">
                                <li><code>amount</code> - Số tiền thanh toán</li>
                                <li><code>currency</code> - Loại tiền tệ (VND, USD...)</li>
                                <li><code>payment_method</code> - Phương thức thanh toán</li>
                                <li><code>description</code> - Mô tả giao dịch</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Thông tin khách hàng:</h6>
                            <ul class="list-unstyled">
                                <li><code>customer_info</code> - Thông tin người dùng</li>
                                <li><code>bank_info</code> - Thông tin ngân hàng</li>
                                <li><code>metadata</code> - Dữ liệu bổ sung (tùy chọn)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Processing Status -->
            <div id="processingStatus" class="text-center mt-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang xử lý...</span>
                </div>
                <p class="mt-2 text-muted">Đang xử lý thông tin thanh toán...</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Thành công
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-success mb-2">Thông tin thanh toán đã được gửi thành công!</h6>
                <p class="text-muted mb-0">Chúng tôi sẽ xử lý giao dịch của bạn trong thời gian sớm nhất.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="bi bi-check me-2"></i>
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Lỗi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-danger mb-2">Có lỗi xảy ra!</h6>
                <p id="errorMessage" class="text-muted mb-0">Vui lòng kiểm tra lại thông tin và thử lại.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-x me-2"></i>
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--bs-primary), #20c997) !important;
    }
    
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
    }
    
    #paymentData {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.5;
        resize: vertical;
        min-height: 300px;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
    code {
        background-color: rgba(22, 163, 74, 0.1);
        color: var(--bs-primary);
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.9em;
    }
    
    .alert {
        border: none;
        border-radius: 0.75rem;
    }
    
    .alert-info {
        background: linear-gradient(135deg, rgba(13, 202, 240, 0.1), rgba(13, 202, 240, 0.05));
        color: #0c5460;
    }
    
    .alert-success {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1), rgba(25, 135, 84, 0.05));
        color: #0f5132;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
        color: #842029;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const paymentData = document.getElementById('paymentData');
    const validateJsonBtn = document.getElementById('validateJsonBtn');
    const submitBtn = document.getElementById('submitBtn');
    const jsonValidation = document.getElementById('jsonValidation');
    const validationMessage = document.getElementById('validationMessage');
    const processingStatus = document.getElementById('processingStatus');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const errorMessage = document.getElementById('errorMessage');

    let isValidJson = false;

    // Real-time JSON validation
    paymentData.addEventListener('input', function() {
        validateJson();
    });

    // Manual JSON validation
    validateJsonBtn.addEventListener('click', function() {
        validateJson();
    });

    function validateJson() {
        const jsonText = paymentData.value.trim();
        
        if (!jsonText) {
            hideValidation();
            return;
        }

        try {
            JSON.parse(jsonText);
            showValidation('success', 'JSON hợp lệ và có thể gửi!');
            isValidJson = true;
            submitBtn.disabled = false;
        } catch (error) {
            showValidation('danger', `JSON không hợp lệ: ${error.message}`);
            isValidJson = false;
            submitBtn.disabled = true;
        }
    }

    function showValidation(type, message) {
        jsonValidation.style.display = 'block';
        const alert = jsonValidation.querySelector('.alert');
        alert.className = `alert alert-${type} d-flex align-items-center`;
        
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        alert.innerHTML = `<i class="bi ${icon} me-2"></i><span>${message}</span>`;
    }

    function hideValidation() {
        jsonValidation.style.display = 'none';
        isValidJson = false;
        submitBtn.disabled = true;
    }

    // Form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!isValidJson) {
            showError('Vui lòng kiểm tra JSON trước khi gửi!');
            return;
        }

        // Show processing status
        processingStatus.style.display = 'block';
        submitBtn.disabled = true;
        validateJsonBtn.disabled = true;

        // Submit form via AJAX
        const formData = new FormData(paymentForm);
        
        fetch(paymentForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            processingStatus.style.display = 'none';
            
            if (data.success) {
                successModal.show();
                paymentForm.reset();
                hideValidation();
            } else {
                showError(data.message || 'Có lỗi xảy ra khi xử lý thanh toán!');
            }
        })
        .catch(error => {
            processingStatus.style.display = 'none';
            console.error('Error:', error);
            showError('Có lỗi xảy ra khi kết nối đến server!');
        })
        .finally(() => {
            submitBtn.disabled = false;
            validateJsonBtn.disabled = false;
        });
    });

    function showError(message) {
        errorMessage.textContent = message;
        errorModal.show();
    }

    // Auto-resize textarea
    paymentData.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    // Format JSON on paste
    paymentData.addEventListener('paste', function(e) {
        setTimeout(() => {
            try {
                const jsonText = this.value.trim();
                if (jsonText) {
                    const parsed = JSON.parse(jsonText);
                    this.value = JSON.stringify(parsed, null, 2);
                    validateJson();
                }
            } catch (error) {
                // Keep original text if not valid JSON
            }
        }, 100);
    });
});
</script>
@endpush
