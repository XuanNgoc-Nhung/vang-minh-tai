@extends('user.layouts.app')

@section('title', 'Đăng nhập - Web Đầu Tư')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <!-- Phần bên trái - Logo và khẩu hiệu -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="h-100 bg-soft-primary d-flex align-items-center justify-content-center">
                                <div class="text-center px-4">
                                    <!-- Logo -->
                                    <div class="mb-4">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 80px; height: 80px;">
                                            <i class="bi bi-graph-up-arrow display-4"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Khẩu hiệu -->
                                    <h3 class="fw-bold text-dark mb-3">{{ config('app.name') }}</h3>
                                    <p class="text-muted lead">
                                        {{ config('app.slogan') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Phần bên phải - Form đăng nhập -->
                        <div class="col-lg-6">
                            <div class="p-4 p-lg-5">
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-dark mb-2">Đăng nhập tài khoản</h3>
                                    <p class="text-muted">Chào mừng bạn quay trở lại</p>
                                </div>

                                <form id="loginForm" novalidate>
                                    @csrf
                                    
                                    <!-- Email hoặc Số điện thoại -->
                                    <div class="mb-3">
                                        <label for="login" class="form-label fw-semibold">
                                            <i class="bi bi-person me-1 text-primary"></i>Email hoặc Số điện thoại
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="login" 
                                               name="login" 
                                               value="{{ old('login') }}" 
                                               placeholder="Nhập email hoặc số điện thoại"
                                               required>
                                    </div>

                                    <!-- Mật khẩu -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="bi bi-lock me-1 text-primary"></i>Mật khẩu
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Nhập mật khẩu"
                                                   required>
                                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" onclick="togglePassword('password')">
                                                <i id="password-icon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quên mật khẩu -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-end">
                                            <a href="#" class="text-primary text-decoration-none fw-semibold">
                                                <i class="bi bi-question-circle me-1"></i>Quên mật khẩu?
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Nút đăng nhập -->
                                    <div class="d-grid mb-3">
                                        <button type="submit" id="loginBtn" class="btn btn-primary btn-lg fw-semibold">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                                        </button>
                                    </div>

                                    <!-- Đăng ký nếu chưa có tài khoản -->
                                    <div class="text-center">
                                        <p class="text-muted mb-0">
                                            Chưa có tài khoản? 
                                            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                                                Đăng ký ngay
                                            </a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    /* Highlight input with error */
    .form-control.error {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        animation: shake 0.5s ease-in-out;
    }
    
    .form-control.error:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    /* Success state for valid input */
    .form-control.valid {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    
    .form-control.valid:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    
    /* Shake animation for error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .btn-link {
        color: #6c757d;
        text-decoration: none;
    }
    
    .btn-link:hover {
        color: var(--bs-primary);
    }
    
    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .min-vh-100 {
            min-height: auto !important;
            padding: 2rem 0;
        }
        
        .card {
            margin: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
    
    // Form validation và submit với axios
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        // Real-time login field validation
        const loginField = document.getElementById('login');
        loginField.addEventListener('blur', function() {
            validateLoginField(this.value);
        });
        
        loginField.addEventListener('input', function() {
            // Xóa class error khi user bắt đầu nhập
            this.classList.remove('error');
        });
        
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Clear previous validation errors
            clearValidationErrors();
            
            // Validate form
            if (!validateForm()) {
                return;
            }
            
            // Show loading state
            const originalText = loginBtn.innerHTML;
            loginBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang đăng nhập...';
            loginBtn.disabled = true;
            
            try {
                // Collect form data
                const formData = new FormData(form);
                const data = {
                    login: formData.get('login'),
                    password: formData.get('password')
                };
                
                // Send data via axios
                const response = await axios.post('{{ route("post-login") }}', data, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                // Success
                if (response.data.success) {
                    showToast('success', 'Đăng nhập thành công!');
                    // Redirect to dashboard after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("home") }}';
                    }, 1500);
                } else {
                    showToast('error', response.data.message);
                }
            } catch (error) {
                console.error('Login error:', error);
                
                if (error.response && error.response.status === 422) {
                    // Validation errors from server
                    const errors = error.response.data.errors;
                    showValidationErrors(errors);
                } else {
                    showToast('error', error.response.data.message || 'Đăng nhập thất bại. Vui lòng thử lại.');
                }
            } finally {
                // Reset button state
                loginBtn.innerHTML = originalText;
                loginBtn.disabled = false;
            }
        });
        
        function validateForm() {
            let isValid = true;
            const fieldLabels = {
                'login': 'Email hoặc Số điện thoại',
                'password': 'Mật khẩu'
            };
            
            // 1. Kiểm tra các trường bắt buộc
            const requiredFields = ['login', 'password'];
            for (let fieldName of requiredFields) {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    showFieldError(fieldName, `${fieldLabels[fieldName]} là bắt buộc`);
                    isValid = false;
                    return isValid; // Dừng ngay khi gặp lỗi đầu tiên
                }
            }
            
            // 2. Validate login field format
            const login = document.getElementById('login').value.trim();
            if (!validateLoginFormat(login)) {
                isValid = false;
                return isValid;
            }
            
            // 3. Validate password length
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                showFieldError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
                return isValid;
            }
            
            return isValid;
        }
        
        function validateLoginFormat(login) {
            const loginField = document.getElementById('login');
            
            // Kiểm tra nếu là email
            if (login.includes('@')) {
                const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                
                if (!emailRegex.test(login)) {
                    showFieldError('login', 'Định dạng email không hợp lệ. Ví dụ: example@domain.com');
                    return false;
                }
                
                // Kiểm tra độ dài email
                if (login.length > 254) {
                    showFieldError('login', 'Email không được vượt quá 254 ký tự');
                    return false;
                }
                
                // Kiểm tra phần local (trước @) không quá 64 ký tự
                const localPart = login.split('@')[0];
                if (localPart.length > 64) {
                    showFieldError('login', 'Phần tên email không được vượt quá 64 ký tự');
                    return false;
                }
                
                // Kiểm tra không có dấu chấm liên tiếp
                if (login.includes('..')) {
                    showFieldError('login', 'Email không được chứa dấu chấm liên tiếp');
                    return false;
                }
                
                // Kiểm tra không bắt đầu hoặc kết thúc bằng dấu chấm
                if (localPart.startsWith('.') || localPart.endsWith('.')) {
                    showFieldError('login', 'Tên email không được bắt đầu hoặc kết thúc bằng dấu chấm');
                    return false;
                }
            } else {
                // Kiểm tra nếu là số điện thoại (Vietnamese phone number)
                const phoneRegex = /^(0|\+84)[0-9]{9,10}$/;
                if (!phoneRegex.test(login.replace(/\s/g, ''))) {
                    showFieldError('login', 'Số điện thoại không hợp lệ. Ví dụ: 0123456789 hoặc +84123456789');
                    return false;
                }
            }
            
            return true;
        }
        
        function validateLoginField(login) {
            const loginField = document.getElementById('login');
            const trimmedLogin = login.trim();
            
            // Nếu login trống, không hiển thị lỗi (để form validation xử lý)
            if (trimmedLogin === '') {
                loginField.classList.remove('error');
                return true;
            }
            
            // Kiểm tra định dạng login
            if (!validateLoginFormat(trimmedLogin)) {
                return false;
            }
            
            // Login hợp lệ
            loginField.classList.remove('error');
            loginField.classList.add('valid');
            
            // Xóa class valid sau 2 giây
            setTimeout(() => {
                loginField.classList.remove('valid');
            }, 2000);
            
            return true;
        }
        
        function showFieldError(fieldName, message) {
            // Show toast notification
            showToast('error', message);
            
            // Focus vào input có lỗi
            const field = document.getElementById(fieldName);
            if (field) {
                // Thêm class error để highlight input
                field.classList.add('error');
                
                // Scroll đến input có lỗi
                field.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Focus vào input sau một chút delay để đảm bảo scroll hoàn thành
                setTimeout(() => {
                    field.focus();
                }, 300);
                
                // Xóa class error khi user bắt đầu nhập
                field.addEventListener('input', function() {
                    this.classList.remove('error');
                }, { once: true });
            }
        }
        
        function clearValidationErrors() {
            // Xóa tất cả class error và valid từ các input
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('error', 'valid');
            });
        }
        
        function showValidationErrors(errors) {
            // Chỉ hiển thị lỗi của field đầu tiên
            const firstErrorField = Object.keys(errors)[0];
            if (firstErrorField) {
                showToast('error', errors[firstErrorField][0]);
                
                // Focus vào input có lỗi từ server
                const field = document.getElementById(firstErrorField);
                if (field) {
                    // Thêm class error để highlight input
                    field.classList.add('error');
                    
                    // Scroll đến input có lỗi
                    field.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    
                    // Focus vào input sau một chút delay để đảm bảo scroll hoàn thành
                    setTimeout(() => {
                        field.focus();
                    }, 300);
                    
                    // Xóa class error khi user bắt đầu nhập
                    field.addEventListener('input', function() {
                        this.classList.remove('error');
                    }, { once: true });
                }
            }
        }
        
    });
</script>
@endpush
@endsection
