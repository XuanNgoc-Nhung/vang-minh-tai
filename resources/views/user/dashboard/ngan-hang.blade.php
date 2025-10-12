@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-university mr-2"></i>
                        Thông tin ngân hàng
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form cập nhật thông tin ngân hàng -->
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-edit mr-2"></i>
                                Cập nhật thông tin ngân hàng
                            </h5>
                            <form id="bankInfoForm" method="POST" action="{{ route('user.bank.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Tên ngân hàng <span class="text-danger">*</span></label>
                                    <select @if($user->profile && $user->profile->ngan_hang) disabled @endif class="form-control @error('bank_name') is-invalid @enderror" 
                                            id="bank_name" 
                                            name="bank_name" 
                                            required>
                                        <option value="">-- Chọn ngân hàng --</option>
                                        <option value="Vietcombank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Vietcombank' ? 'selected' : '' }}>Vietcombank</option>
                                        <option value="VPBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VPBank' ? 'selected' : '' }}>VPBank</option>
                                        <option value="Techcombank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Techcombank' ? 'selected' : '' }}>Techcombank</option>
                                        <option value="BIDV" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'BIDV' ? 'selected' : '' }}>BIDV</option>
                                        <option value="MBBANK" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'MBBANK' ? 'selected' : '' }}>MBBANK</option>
                                        <option value="VietinBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VietinBank' ? 'selected' : '' }}>VietinBank</option>
                                        <option value="Agribank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Agribank' ? 'selected' : '' }}>Agribank</option>
                                        <option value="ACB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'ACB' ? 'selected' : '' }}>ACB</option>
                                        <option value="SHB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SHB' ? 'selected' : '' }}>SHB</option>
                                        <option value="HDBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'HDBank' ? 'selected' : '' }}>HDBank</option>
                                        <option value="LVBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'LVBank' ? 'selected' : '' }}>LVBank</option>
                                        <option value="VIB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VIB' ? 'selected' : '' }}>VIB</option>
                                        <option value="SeABank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SeABank' ? 'selected' : '' }}>SeABank</option>
                                        <option value="TPBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'TPBank' ? 'selected' : '' }}>TPBank</option>
                                        <option value="MSB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'MSB' ? 'selected' : '' }}>MSB</option>
                                        <option value="VBSP" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VBSP' ? 'selected' : '' }}>VBSP</option>
                                        <option value="OCB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'OCB' ? 'selected' : '' }}>OCB</option>
                                        <option value="Sacombank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Sacombank' ? 'selected' : '' }}>Sacombank</option>
                                        <option value="Eximbank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Eximbank' ? 'selected' : '' }}>Eximbank</option>
                                        <option value="SCB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SCB' ? 'selected' : '' }}>SCB</option>
                                        <option value="VDB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VDB' ? 'selected' : '' }}>VDB</option>
                                        <option value="Nam A Bank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Nam A Bank' ? 'selected' : '' }}>Nam A Bank</option>
                                        <option value="Woori" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Woori' ? 'selected' : '' }}>Woori</option>
                                        <option value="NCB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'NCB' ? 'selected' : '' }}>NCB</option>
                                        <option value="ABBANK" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'ABBANK' ? 'selected' : '' }}>ABBANK</option>
                                        <option value="UOB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'UOB' ? 'selected' : '' }}>UOB</option>
                                        <option value="Bac A Bank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Bac A Bank' ? 'selected' : '' }}>Bac A Bank</option>
                                        <option value="PVcomBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'PVcomBank' ? 'selected' : '' }}>PVcomBank</option>
                                        <option value="HSBC" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'HSBC' ? 'selected' : '' }}>HSBC</option>
                                        <option value="Vietbank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Vietbank' ? 'selected' : '' }}>Vietbank</option>
                                        <option value="PBVN" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'PBVN' ? 'selected' : '' }}>PBVN</option>
                                        <option value="SCBVL" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SCBVL' ? 'selected' : '' }}>SCBVL</option>
                                        <option value="SHBVN" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SHBVN' ? 'selected' : '' }}>SHBVN</option>
                                        <option value="BVBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'BVBank' ? 'selected' : '' }}>BVBank</option>
                                        <option value="VietABank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VietABank' ? 'selected' : '' }}>VietABank</option>
                                        <option value="ANZVL" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'ANZVL' ? 'selected' : '' }}>ANZVL</option>
                                        <option value="PGBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'PGBank' ? 'selected' : '' }}>PGBank</option>
                                        <option value="CIMB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'CIMB' ? 'selected' : '' }}>CIMB</option>
                                        <option value="Kienlongbank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Kienlongbank' ? 'selected' : '' }}>Kienlongbank</option>
                                        <option value="SAIGONBANK" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'SAIGONBANK' ? 'selected' : '' }}>SAIGONBANK</option>
                                        <option value="IVB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'IVB' ? 'selected' : '' }}>IVB</option>
                                        <option value="BAOVIET Bank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'BAOVIET Bank' ? 'selected' : '' }}>BAOVIET Bank</option>
                                        <option value="VRB" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VRB' ? 'selected' : '' }}>VRB</option>
                                        <option value="Co-opBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Co-opBank' ? 'selected' : '' }}>Co-opBank</option>
                                        <option value="HLBVN" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'HLBVN' ? 'selected' : '' }}>HLBVN</option>
                                        <option value="Vikki Bank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'Vikki Bank' ? 'selected' : '' }}>Vikki Bank</option>
                                        <option value="MBV" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'MBV' ? 'selected' : '' }}>MBV</option>
                                        <option value="GPBank" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'GPBank' ? 'selected' : '' }}>GPBank</option>
                                        <option value="VCBNeo" {{ old('bank_name', $user->profile->ngan_hang ?? '') == 'VCBNeo' ? 'selected' : '' }}>VCBNeo</option>
                                    </select>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Số tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('account_number') is-invalid @enderror" 
                                           id="account_number" 
                                           name="account_number" 
                                           @if($user->profile && $user->profile->so_tai_khoan) disabled @endif
                                           value="{{ old('account_number', $user->profile->so_tai_khoan ?? '') }}"
                                           placeholder="Nhập số tài khoản"
                                           required>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Chủ tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('account_holder') is-invalid @enderror" 
                                           id="account_holder" 
                                           @if($user->profile && $user->profile->chu_tai_khoan) disabled @endif
                                           name="account_holder" 
                                           value="{{ old('account_holder', $user->profile->chu_tai_khoan ?? '') }}"
                                           placeholder="Nhập tên chủ tài khoản"
                                           required>
                                    @error('account_holder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if(!$user->profile && !$user->profile->ngan_hang && !$user->profile->so_tai_khoan && !$user->profile->chu_tai_khoan)
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Mật khẩu xác nhận <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Nhập mật khẩu để xác nhận thay đổi"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                        <i class="fas fa-undo mr-1"></i>
                                        Làm mới
                                    </button> --}}
                                    <button type="submit" @if($user->profile && $user->profile->ngan_hang && $user->profile->so_tai_khoan && $user->profile->chu_tai_khoan) disabled @endif class="btn btn-primary" id="updateBtn">
                                        <i class="fas fa-save mr-1"></i>
                                        Cập nhật thông tin
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- Lưu ý bảo mật phía dưới -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Lưu ý quan trọng
                                </h6>
                                <hr>
                                <small>
                                    <ul class="mb-0">
                                        <li>Thông tin ngân hàng sẽ được sử dụng để xử lý các giao dịch rút tiền</li>
                                        <li>Vui lòng đảm bảo thông tin chính xác và đầy đủ</li>
                                        <li>Khi thay đổi thông tin, bạn cần nhập mật khẩu để xác nhận</li>
                                        <li>Thông tin sẽ được mã hóa và bảo mật theo tiêu chuẩn ngân hàng</li>
                                        <li>Nếu bạn đã cập nhật thông tin ngân hàng, bạn không thể thay đổi thông tin đó. Vui lòng liên hệ với bộ phận hỗ trợ để thay đổi thông tin</li>
                                    </ul>
                                </small>
                            </div>
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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .card {
        border: none;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
    }

    .card-header {
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 0.75rem 1.25rem;
    }

    .card-title {
        margin-bottom: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-control {
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .btn {
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    .alert {
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    /* Select2 Custom Styles */
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        background-color: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        line-height: 26px;
        padding-left: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 8px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff;
        color: white;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e9ecef;
        color: #495057;
    }

    /* Error state for Select2 */
    .select2-container--default .select2-selection--single.is-invalid {
        border-color: #dc3545;
    }

    .select2-container--default.select2-container--focus .select2-selection--single.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
    }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bankInfoForm');
        const updateBtn = document.getElementById('updateBtn');
        let pendingSubmit = false;
        
        // Initialize Select2 for bank selection
        $('#bank_name').select2({
            placeholder: '-- Chọn ngân hàng --',
            allowClear: false,
            language: {
                noResults: function() {
                    return "Không tìm thấy ngân hàng";
                },
                searching: function() {
                    return "Đang tìm kiếm...";
                }
            },
            width: '100%'
        });
        
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // HTML5 validation first
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Sử dụng confirm mới từ toast.js
            confirm({
                title: 'Xác nhận cập nhật',
                message: 'Bạn có chắc chắn muốn lưu thay đổi thông tin ngân hàng không?',
                confirmText: 'Xác nhận',
                onConfirm: () => submitBankForm()
            });
        });
        
        function submitBankForm() {
            pendingSubmit = true;
            // Disable button and show loading
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Đang cập nhật...';

            // Get form data
            const formData = new FormData(form);

            // Convert FormData to object for axios
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            axios.post(form.action, data)
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        showToast('success', data.message);
                        document.getElementById('password').value = '';
                        setTimeout(() => { window.location.reload(); }, 1500);
                    } else {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    // Handle Select2 validation styling
                                    if (field === 'bank_name') {
                                        $('#bank_name').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                    }
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = data.errors[field][0];
                                    }
                                }
                            });
                        }
                        showToast('error', data.message || 'Có lỗi xảy ra khi cập nhật thông tin');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Có lỗi xảy ra khi cập nhật thông tin';
                    if (error.response && error.response.data) {
                        if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.response.data.errors) {
                            Object.keys(error.response.data.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    // Handle Select2 validation styling
                                    if (field === 'bank_name') {
                                        $('#bank_name').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                    }
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = error.response.data.errors[field][0];
                                    }
                                }
                            });
                            errorMessage = 'Vui lòng kiểm tra lại thông tin đã nhập';
                        }
                    }
                    showToast('error', errorMessage);
                })
                .finally(() => {
                    pendingSubmit = false;
                    updateBtn.disabled = false;
                    updateBtn.innerHTML = '<i class="fas fa-save mr-1"></i>Cập nhật thông tin';
                });
        }

        // Clear validation errors on input
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                // Clear Select2 validation styling
                if (this.name === 'bank_name') {
                    $('#bank_name').next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                }
            });
        });

        // Handle Select2 validation error clearing
        $('#bank_name').on('select2:select', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        });
    });
    
    function resetForm() {
        const form = document.getElementById('bankInfoForm');
        form.reset();
        
        // Reset Select2 to default
        $('#bank_name').val('').trigger('change');
        
        // Clear validation errors
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            // Clear Select2 validation styling
            if (input.name === 'bank_name') {
                $('#bank_name').next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
    }
</script>
@endpush