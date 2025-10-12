@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-id-card mr-2"></i>
                        Xác thực danh tính (KYC)
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Form KYC - Chỉ upload hình ảnh -->
                    <form id="kycForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Upload hình ảnh -->
                        <div class="row">
                            <!-- Ảnh chân dung -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="portrait_photo" class="text-center d-block mb-3">
                                        <strong>Ảnh chân dung</strong> <span class="text-danger">*</span>
                                    </label>
                                    <div class="upload-area {{ $profile && $profile->anh_chan_dung ? 'has-image' : '' }}" onclick="document.getElementById('portrait_photo').click()">
                                        <input type="file" id="portrait_photo" name="portrait_photo" 
                                               class="d-none" accept="image/*,.webp" onchange="previewImage(this, 'portrait_preview')">
                                        <div id="portrait_preview" class="preview-container">
                                            @if($profile && $profile->anh_chan_dung)
                                                <img src="{{ asset( $profile->anh_chan_dung) }}" alt="Ảnh chân dung hiện tại">
                                            @else
                                                <i class="fas fa-user fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">Nhấp để chọn ảnh chân dung</p>
                                                <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ảnh giấy tờ mặt trước -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_front_photo" class="text-center d-block mb-3">
                                        <strong>Ảnh giấy tờ mặt trước</strong> <span class="text-danger">*</span>
                                    </label>
                                    <div class="upload-area {{ $profile && $profile->anh_mat_truoc ? 'has-image' : '' }}" onclick="document.getElementById('id_front_photo').click()">
                                        <input type="file" id="id_front_photo" name="id_front_photo" 
                                               class="d-none" accept="image/*,.webp" onchange="previewImage(this, 'id_front_preview')">
                                        <div id="id_front_preview" class="preview-container">
                                            @if($profile && $profile->anh_mat_truoc)
                                                <img src="{{ asset( $profile->anh_mat_truoc) }}" alt="Ảnh giấy tờ mặt trước hiện tại">
                                            @else
                                                <i class="fas fa-id-card fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">Nhấp để chọn ảnh mặt trước</p>
                                                <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ảnh giấy tờ mặt sau -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_back_photo" class="text-center d-block mb-3">
                                        <strong>Ảnh giấy tờ mặt sau</strong> <span class="text-danger">*</span>
                                    </label>
                                    <div class="upload-area {{ $profile && $profile->anh_mat_sau ? 'has-image' : '' }}" onclick="document.getElementById('id_back_photo').click()">
                                        <input type="file" id="id_back_photo" name="id_back_photo" 
                                               class="d-none" accept="image/*,.webp" onchange="previewImage(this, 'id_back_preview')">
                                        <div id="id_back_preview" class="preview-container">
                                            @if($profile && $profile->anh_mat_sau)
                                                <img src="{{ asset( $profile->anh_mat_sau) }}" alt="Ảnh giấy tờ mặt sau hiện tại">
                                            @else
                                                <i class="fas fa-id-card fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">Nhấp để chọn ảnh mặt sau</p>
                                                <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Xác thực mật khẩu và nút gửi yêu cầu -->
                        <div class="row mt-4">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="login_password" class="form-label">
                                        <i class="fas fa-lock mr-2"></i>
                                        Mật khẩu đăng nhập <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('login_password') is-invalid @enderror" 
                                           id="login_password" 
                                           name="login_password" 
                                           placeholder="Nhập mật khẩu đăng nhập để xác thực"
                                           required>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Vui lòng nhập mật khẩu đăng nhập để xác thực danh tính
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex align-items-end h-100">
                                        <button type="submit" id="submitKycBtn" class="btn btn-primary w-100">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            <span class="btn-text">Gửi yêu cầu xác thực</span>
                                            <span class="btn-loading d-none">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                                Đang xử lý...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lưu ý -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>Lưu ý quan trọng:</h6>
                                    <ul class="mb-0">
                                        <li>Ảnh phải rõ nét, không bị mờ hoặc bị che khuất</li>
                                        <li>Thông tin trên giấy tờ phải đọc được rõ ràng</li>
                                        <li>Ảnh chân dung phải là ảnh chụp trực tiếp, không qua chỉnh sửa</li>
                                        <li>Quá trình xác thực có thể mất 1-3 giờ làm việc</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.preview-container {
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.preview-container img {
    max-width: 100%;
    max-height: 180px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    object-fit: cover;
}

.upload-area.has-image {
    border-style: solid;
    border-color: #28a745;
    background-color: #f8fff9;
}

.upload-area.has-image:hover {
    border-color: #28a745;
    background-color: #f8fff9;
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .upload-area {
        min-height: 150px;
        padding: 20px 15px;
    }
    
    .preview-container img {
        max-height: 120px;
    }
    
    /* Mobile layout for password and button */
    .form-group .d-flex {
        margin-top: 0.5rem;
    }
    
    .btn {
        margin-top: 0;
    }
}
</style>

<script>
// Initialize existing KYC images on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if there are existing images and set has-image class
    @if($profile && $profile->anh_chan_dung)
        document.querySelector('#portrait_photo').closest('.upload-area').classList.add('has-image');
    @endif
    
    @if($profile && $profile->anh_mat_truoc)
        document.querySelector('#id_front_photo').closest('.upload-area').classList.add('has-image');
    @endif
    
    @if($profile && $profile->anh_mat_sau)
        document.querySelector('#id_back_photo').closest('.upload-area').classList.add('has-image');
    @endif
});

// Function to preview uploaded images
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const uploadArea = input.closest('.upload-area');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            uploadArea.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    } else {
        // Reset to appropriate icon based on input type
        if (input.id === 'portrait_photo') {
            preview.innerHTML = `
                <i class="fas fa-user fa-3x text-muted"></i>
                <p class="mt-2 text-muted">Nhấp để chọn ảnh chân dung</p>
                <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
            `;
        } else {
            preview.innerHTML = `
                <i class="fas fa-id-card fa-3x text-muted"></i>
                <p class="mt-2 text-muted">Nhấp để chọn ảnh</p>
                <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
            `;
        }
        uploadArea.classList.remove('has-image');
    }
}

// Validate file size
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.size > 5 * 1024 * 1024) { // 5MB
            showErrorToast('Kích thước file không được vượt quá 5MB');
            this.value = '';
            return false;
        }
    });
});


// Function to clear validation errors
function clearValidationErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
}

// Function to set loading state
function setLoadingState(loading) {
    const submitBtn = document.getElementById('submitKycBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    if (loading) {
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    } else {
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
    }
}

// Handle form submission with axios
document.getElementById('kycForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearValidationErrors();
    
    // Set loading state
    setLoadingState(true);
    
    try {
        // Validate required images (check if new files uploaded or existing images available)
        const portraitPhoto = document.getElementById('portrait_photo').files[0];
        const idFrontPhoto = document.getElementById('id_front_photo').files[0];
        const idBackPhoto = document.getElementById('id_back_photo').files[0];
        
        // Check if we have existing images or new uploads
        const hasPortrait = portraitPhoto || {{ $profile && $profile->anh_chan_dung ? 'true' : 'false' }};
        const hasIdFront = idFrontPhoto || {{ $profile && $profile->anh_mat_truoc ? 'true' : 'false' }};
        const hasIdBack = idBackPhoto || {{ $profile && $profile->anh_mat_sau ? 'true' : 'false' }};
        
        const missingImages = [];
        if (!hasPortrait) missingImages.push('Ảnh chân dung');
        if (!hasIdFront) missingImages.push('Ảnh giấy tờ mặt trước');
        if (!hasIdBack) missingImages.push('Ảnh giấy tờ mặt sau');
        
        if (missingImages.length > 0) {
            showErrorToast(`Vui lòng tải lên: ${missingImages.join(', ')}`);
            setLoadingState(false);
            return;
        }
        
        // Validate login password
        const loginPassword = document.getElementById('login_password').value;
        if (!loginPassword) {
            showErrorToast('Vui lòng nhập mật khẩu đăng nhập');
            setLoadingState(false);
            return;
        }
        
        // Create FormData object
        const formData = new FormData();
        formData.append('login_password', loginPassword);
        
        // Only append new photos if they exist
        if (portraitPhoto) {
            formData.append('portrait_photo', portraitPhoto);
        }
        if (idFrontPhoto) {
            formData.append('id_front_photo', idFrontPhoto);
        }
        if (idBackPhoto) {
            formData.append('id_back_photo', idBackPhoto);
        }
        
        // Add CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Send request
        const response = await axios.post('{{ route("user.kyc.submit") }}', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.data.success) {
            showSuccessToast(response.data.message);
            
            // Reset form
            document.getElementById('kycForm').reset();
            
            // Reset image previews
            document.querySelectorAll('.preview-container').forEach((preview, index) => {
                if (index === 0) {
                    // Portrait photo
                    preview.innerHTML = `
                        <i class="fas fa-user fa-3x text-muted"></i>
                        <p class="mt-2 text-muted">Nhấp để chọn ảnh chân dung</p>
                        <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
                    `;
                } else {
                    // ID photos
                    preview.innerHTML = `
                        <i class="fas fa-id-card fa-3x text-muted"></i>
                        <p class="mt-2 text-muted">Nhấp để chọn ảnh</p>
                        <small class="text-muted">JPG, PNG, WebP, tối đa 5MB</small>
                    `;
                }
            });
            
            document.querySelectorAll('.upload-area').forEach(area => {
                area.classList.remove('has-image');
            });
            
            // Reload page after 2 seconds to update KYC status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
        
    } catch (error) {
        console.error('KYC submission error:', error);
        
        if (error.response && error.response.status === 422) {
            // Validation errors - show first error message
            const errors = error.response.data.errors;
            const firstError = Object.values(errors)[0][0];
            showErrorToast(firstError);
        } else if (error.response && error.response.data.message) {
            // Server error with message
            showErrorToast(error.response.data.message);
        } else {
            // Generic error
            showErrorToast('Có lỗi xảy ra khi gửi yêu cầu xác thực. Vui lòng thử lại.');
        }
    } finally {
        // Reset loading state
        setLoadingState(false);
    }
});
</script>
@endsection
