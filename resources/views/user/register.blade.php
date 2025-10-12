@extends('user.layouts.app')

@section('title', 'Đăng ký tài khoản - Web Đầu Tư')

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
                        
                        <!-- Phần bên phải - Form đăng ký -->
                        <div class="col-lg-6">
                            <div class="p-4 p-lg-5">
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-dark mb-2">Tạo tài khoản mới</h3>
                                    <p class="text-muted">Điền thông tin để bắt đầu đầu tư</p>
                                </div>

                                <form id="registerForm" novalidate>
                                    @csrf
                                    
                                    <!-- Họ tên -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-person me-1 text-primary"></i>Họ và tên
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Nhập họ và tên của bạn"
                                               required>
                                    </div>

                                    <!-- Số điện thoại -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="bi bi-telephone me-1 text-primary"></i>Số điện thoại
                                        </label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="Nhập số điện thoại"
                                               required>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-1 text-primary"></i>Email
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Nhập địa chỉ email"
                                               required>
                                    </div>

                                    <!-- Mật khẩu -->
                                    <div class="mb-3">
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

                                    <!-- Xác nhận mật khẩu -->
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            <i class="bi bi-lock-fill me-1 text-primary"></i>Xác nhận mật khẩu
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   placeholder="Nhập lại mật khẩu"
                                                   required>
                                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" onclick="togglePassword('password_confirmation')">
                                                <i id="password_confirmation-icon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Checkbox đồng ý điều khoản -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="terms" 
                                                   name="terms" 
                                                   {{ old('terms') ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label text-muted" for="terms">
                                                Tôi đồng ý với <a href="#" class="text-primary text-decoration-none">Điều khoản sử dụng</a> 
                                                và <a href="#" class="text-primary text-decoration-none">Chính sách bảo mật</a>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Nút đăng ký -->
                                    <div class="d-grid mb-3">
                                        <button type="submit" id="registerBtn" class="btn btn-primary btn-lg fw-semibold">
                                            <i class="bi bi-person-plus me-2"></i>Đăng ký tài khoản
                                        </button>
                                    </div>

                                    <!-- Đăng nhập nếu đã có tài khoản -->
                                    <div class="text-center">
                                        <p class="text-muted mb-0">
                                            Đã có tài khoản? 
                                            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                                                Đăng nhập ngay
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
    
    /* Success state for valid email */
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
    
    // Hàm chuyển đổi ký tự tiếng Việt sang tiếng Anh
    function convertVietnameseToEnglish(text) {
        const vietnameseMap = {
            'à': 'a', 'á': 'a', 'ạ': 'a', 'ả': 'a', 'ã': 'a', 'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ậ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ă': 'a', 'ằ': 'a', 'ắ': 'a', 'ặ': 'a', 'ẳ': 'a', 'ẵ': 'a',
            'è': 'e', 'é': 'e', 'ẹ': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ê': 'e', 'ề': 'e', 'ế': 'e', 'ệ': 'e', 'ể': 'e', 'ễ': 'e',
            'ì': 'i', 'í': 'i', 'ị': 'i', 'ỉ': 'i', 'ĩ': 'i',
            'ò': 'o', 'ó': 'o', 'ọ': 'o', 'ỏ': 'o', 'õ': 'o', 'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ộ': 'o', 'ổ': 'o', 'ỗ': 'o', 'ơ': 'o', 'ờ': 'o', 'ớ': 'o', 'ợ': 'o', 'ở': 'o', 'ỡ': 'o',
            'ù': 'u', 'ú': 'u', 'ụ': 'u', 'ủ': 'u', 'ũ': 'u', 'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ự': 'u', 'ử': 'u', 'ữ': 'u',
            'ỳ': 'y', 'ý': 'y', 'ỵ': 'y', 'ỷ': 'y', 'ỹ': 'y',
            'đ': 'd', 'Đ': 'D'
        };
        
        return text.replace(/[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]/g, function(match) {
            return vietnameseMap[match] || match;
        });
    }
    
    // Form validation và submit với axios
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        
        // Thêm validation chỉ cho phép tiếng Anh cho các trường name và email
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        
        // Chuyển đổi ký tự tiếng Việt sang tiếng Anh cho trường name
        nameField.addEventListener('input', function(e) {
            // Xóa class error khi user bắt đầu nhập
            this.classList.remove('error');
            
            // Chuyển đổi ký tự tiếng Việt sang tiếng Anh
            const originalValue = this.value;
            const convertedValue = convertVietnameseToEnglish(originalValue);
            
            if (originalValue !== convertedValue) {
                this.value = convertedValue;
                // showToast('info', 'Đã chuyển đổi ký tự tiếng Việt sang tiếng Anh');
            }
        });
        
        // Chuyển đổi ký tự tiếng Việt sang tiếng Anh cho trường email
        emailField.addEventListener('input', function(e) {
            // Xóa class error khi user bắt đầu nhập
            this.classList.remove('error');
            
            // Chuyển đổi ký tự tiếng Việt sang tiếng Anh
            const originalValue = this.value;
            const convertedValue = convertVietnameseToEnglish(originalValue);
            
            if (originalValue !== convertedValue) {
                this.value = convertedValue;
                // showToast('info', 'Đã chuyển đổi ký tự tiếng Việt sang tiếng Anh');
            }
        });
        
        // Real-time email validation
        emailField.addEventListener('blur', function() {
            validateEmailRealTime(this.value);
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
            const originalText = registerBtn.innerHTML;
            registerBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang xử lý...';
            registerBtn.disabled = true;
            
            try {
                // Collect form data
                const formData = new FormData(form);
                const data = {
                    name: formData.get('name'),
                    phone: formData.get('phone'),
                    email: formData.get('email'),
                    password: formData.get('password'),
                    password_confirmation: formData.get('password_confirmation'),
                    terms: formData.get('terms') ? true : false
                };
                
                // Send data via axios
                const response = await axios.post('{{ route("post-register") }}', data, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                // Success
                if (response.data.success) {
                    showToast('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                    form.reset();
                    // Redirect to login after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                }else{
                    showToast('error', response.data.message);
                }
            } catch (error) {
                console.error('Registration error:', error);
                
                if (error.response && error.response.status === 422) {
                    // Validation errors from server
                    const errors = error.response.data.errors;
                    showValidationErrors(errors);
                } else {
                    showToast('error', error.response.data.message);
                }
            } finally {
                // Reset button state
                registerBtn.innerHTML = originalText;
                registerBtn.disabled = false;
            }
        });
        
        function validateForm() {
            let isValid = true;
            const fieldLabels = {
                'name': 'Họ và tên',
                'phone': 'Số điện thoại',
                'email': 'Email',
                'password': 'Mật khẩu',
                'password_confirmation': 'Xác nhận mật khẩu'
            };
            
            // 1. Kiểm tra các trường bắt buộc (trừ email - sẽ kiểm tra riêng)
            const requiredFields = ['name', 'phone', 'password', 'password_confirmation'];
            for (let fieldName of requiredFields) {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    showFieldError(fieldName, `${fieldLabels[fieldName]} là bắt buộc`);
                    isValid = false;
                    return isValid; // Dừng ngay khi gặp lỗi đầu tiên
                }
            }
            
            // 2. Kiểm tra email bắt buộc
            const email = document.getElementById('email').value.trim();
            if (email === '') {
                showFieldError('email', 'Email là bắt buộc');
                isValid = false;
                return isValid;
            }
            
            // 3. Kiểm tra định dạng email (chỉ khi email không trống)
            if (!validateEmailFormat(email)) {
                isValid = false;
                return isValid;
            }
            
            // 4. Validate password length (chỉ khi email đã hợp lệ)
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                showFieldError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
                return isValid;
            }
            
            // 5. Validate password match (chỉ khi password đã hợp lệ)
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            if (password !== passwordConfirmation) {
                showFieldError('password_confirmation', 'Xác nhận mật khẩu không khớp với mật khẩu');
                isValid = false;
                return isValid;
            }
            
            // 6. Kiểm tra terms checkbox
            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                showFieldError('terms', 'Bạn phải đồng ý với điều khoản sử dụng');
                isValid = false;
                return isValid;
            }
            
            // 7. Validate name (không được để trống hoặc quá ngắn)
            const name = document.getElementById('name').value.trim();
            if (name.length < 2) {
                showFieldError('name', 'Họ và tên phải có ít nhất 2 ký tự');
                isValid = false;
                return isValid;
            }
            
            // 7.1. Chuyển đổi ký tự tiếng Việt trong name sang tiếng Anh
            const convertedName = convertVietnameseToEnglish(name);
            if (name !== convertedName) {
                document.getElementById('name').value = convertedName;
                showToast('info', 'Đã chuyển đổi ký tự tiếng Việt trong họ tên sang tiếng Anh');
            }
            
            // 8. Validate phone format (Vietnamese phone number)
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^(0|\+84)[0-9]{9,10}$/;
            if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
                showFieldError('phone', 'Số điện thoại không hợp lệ');
                isValid = false;
                return isValid;
            }
            
            return isValid;
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
        
        function validateEmailFormat(email) {
            const emailField = document.getElementById('email');
            
            // Chuyển đổi ký tự tiếng Việt trong email sang tiếng Anh
            const convertedEmail = convertVietnameseToEnglish(email);
            if (email !== convertedEmail) {
                emailField.value = convertedEmail;
                showToast('info', 'Đã chuyển đổi ký tự tiếng Việt trong email sang tiếng Anh');
                // Tiếp tục validation với email đã được chuyển đổi
                email = convertedEmail;
            }
            
            // Kiểm tra định dạng email chặt chẽ hơn
            const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            
            if (!emailRegex.test(email)) {
                showFieldError('email', 'Định dạng email không hợp lệ. Ví dụ: example@domain.com');
                return false;
            }
            
            // Kiểm tra độ dài email
            if (email.length > 254) {
                showFieldError('email', 'Email không được vượt quá 254 ký tự');
                return false;
            }
            
            // Kiểm tra phần local (trước @) không quá 64 ký tự
            const localPart = email.split('@')[0];
            if (localPart.length > 64) {
                showFieldError('email', 'Phần tên email không được vượt quá 64 ký tự');
                return false;
            }
            
            // Kiểm tra không có dấu chấm liên tiếp
            if (email.includes('..')) {
                showFieldError('email', 'Email không được chứa dấu chấm liên tiếp');
                return false;
            }
            
            // Kiểm tra không bắt đầu hoặc kết thúc bằng dấu chấm
            if (localPart.startsWith('.') || localPart.endsWith('.')) {
                showFieldError('email', 'Tên email không được bắt đầu hoặc kết thúc bằng dấu chấm');
                return false;
            }
            
            return true;
        }
        
        function validateEmailRealTime(email) {
            const emailField = document.getElementById('email');
            const trimmedEmail = email.trim();
            
            // Nếu email trống, không hiển thị lỗi (để form validation xử lý)
            if (trimmedEmail === '') {
                emailField.classList.remove('error');
                return true;
            }
            
            // Chuyển đổi ký tự tiếng Việt trong email sang tiếng Anh
            const convertedEmail = convertVietnameseToEnglish(trimmedEmail);
            if (trimmedEmail !== convertedEmail) {
                emailField.value = convertedEmail;
                showToast('info', 'Đã chuyển đổi ký tự tiếng Việt trong email sang tiếng Anh');
                // Tiếp tục validation với email đã được chuyển đổi
                email = convertedEmail;
            }
            
            // Kiểm tra định dạng email
            const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            
            if (!emailRegex.test(trimmedEmail)) {
                emailField.classList.add('error');
                showToast('error', 'Định dạng email không hợp lệ. Ví dụ: example@domain.com');
                return false;
            }
            
            // Kiểm tra độ dài email
            if (trimmedEmail.length > 254) {
                emailField.classList.add('error');
                showToast('error', 'Email không được vượt quá 254 ký tự');
                return false;
            }
            
            // Kiểm tra phần local (trước @) không quá 64 ký tự
            const localPart = trimmedEmail.split('@')[0];
            if (localPart.length > 64) {
                emailField.classList.add('error');
                showToast('error', 'Phần tên email không được vượt quá 64 ký tự');
                return false;
            }
            
            // Kiểm tra không có dấu chấm liên tiếp
            if (trimmedEmail.includes('..')) {
                emailField.classList.add('error');
                showToast('error', 'Email không được chứa dấu chấm liên tiếp');
                return false;
            }
            
            // Kiểm tra không bắt đầu hoặc kết thúc bằng dấu chấm
            if (localPart.startsWith('.') || localPart.endsWith('.')) {
                emailField.classList.add('error');
                showToast('error', 'Tên email không được bắt đầu hoặc kết thúc bằng dấu chấm');
                return false;
            }
            
            // Email hợp lệ
            emailField.classList.remove('error');
            emailField.classList.add('valid');
            
            // Xóa class valid sau 2 giây
            setTimeout(() => {
                emailField.classList.remove('valid');
            }, 2000);
            
            return true;
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
