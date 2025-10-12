@extends('user.layouts.dashboard')

@section('content-dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Bảo mật tài khoản
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Form thay đổi mật khẩu đăng nhập -->
                        <div class="col-lg-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary">
                                    <h6 class="mb-0">
                                        <i class="bi bi-key me-2"></i>
                                        Thay đổi mật khẩu đăng nhập
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form id="changePasswordForm" method="POST" action="{{ route('user.change-password') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">
                                                Mật khẩu hiện tại <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   placeholder="Nhập mật khẩu hiện tại"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">
                                                Mật khẩu mới <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">
                                                Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_password_confirmation" 
                                                   name="new_password_confirmation" 
                                                   placeholder="Nhập lại mật khẩu mới"
                                                   required>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" id="changePasswordBtn" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>
                                                Cập nhật mật khẩu
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Form thay đổi mật khẩu rút tiền -->
                        <div class="col-lg-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="bi bi-credit-card me-2"></i>
                                        Thay đổi mật khẩu rút tiền
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form id="changeWithdrawalPasswordForm" method="POST" action="{{ route('user.change-withdrawal-password') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="login_password" class="form-label">
                                                Mật khẩu đăng nhập <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="login_password" 
                                                   name="login_password" 
                                                   placeholder="Nhập mật khẩu đăng nhập để xác thực"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_withdrawal_password" class="form-label">
                                                Mật khẩu rút tiền mới <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_withdrawal_password" 
                                                   name="new_withdrawal_password" 
                                                   placeholder="Nhập mật khẩu rút tiền mới (tối thiểu 4 ký tự)"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_withdrawal_password_confirmation" class="form-label">
                                                Xác nhận mật khẩu rút tiền <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_withdrawal_password_confirmation" 
                                                   name="new_withdrawal_password_confirmation" 
                                                   placeholder="Nhập lại mật khẩu rút tiền"
                                                   required>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" id="changeWithdrawalPasswordBtn" class="btn btn-warning">
                                                <i class="bi bi-shield-check me-2"></i>
                                                Cập nhật mật khẩu rút tiền
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bảo mật -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Lưu ý bảo mật
                                </h6>
                                <ul class="mb-0">
                                    <li><strong>Mật khẩu đăng nhập:</strong> Được mã hóa và bảo mật cao, dùng để đăng nhập vào hệ thống</li>
                                    <li><strong>Mật khẩu rút tiền:</strong> Không được mã hóa, dùng để xác thực khi thực hiện giao dịch rút tiền</li>
                                    <li>Để thay đổi mật khẩu rút tiền, bạn cần nhập đúng mật khẩu đăng nhập hiện tại</li>
                                    <li>Khuyến nghị sử dụng mật khẩu mạnh và không chia sẻ với bất kỳ ai</li>
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
@endpush

@push('scripts')
<script>
    // Log khởi tạo trang
    console.log('[LOG] Page Init: Trang bảo mật đã được tải');
    console.log('[LOG] Page Init: User ID:', '{{ Auth::id() }}');
    console.log('[LOG] Page Init: User Email:', '{{ Auth::user()->email ?? "N/A" }}');
    console.log('[LOG] Page Init: Timestamp:', new Date().toISOString());

    function setButtonLoading(button, loading = true) {
        console.log(`[LOG] setButtonLoading: Bắt đầu set loading cho button: ${button.id}, loading: ${loading}`);
        
        if (loading) {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang xử lý...';
            console.log(`[LOG] setButtonLoading: Đã set button ${button.id} thành trạng thái loading`);
        } else {
            button.disabled = false;
            // Restore original button text based on button type
            if (button.id === 'changePasswordBtn') {
                button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Cập nhật mật khẩu';
                console.log(`[LOG] setButtonLoading: Đã restore button ${button.id} về trạng thái bình thường`);
            } else if (button.id === 'changeWithdrawalPasswordBtn') {
                button.innerHTML = '<i class="bi bi-shield-check me-2"></i>Cập nhật mật khẩu rút tiền';
                console.log(`[LOG] setButtonLoading: Đã restore button ${button.id} về trạng thái bình thường`);
            }
        }
    }


    // Log khởi tạo event listeners
    console.log('[LOG] Event Listeners: Bắt đầu khởi tạo event listeners');
    
    // Change Password Form
    console.log('[LOG] Event Listeners: Khởi tạo event listener cho changePasswordForm');
    document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
        console.log('[LOG] changePasswordForm: Bắt đầu xử lý form thay đổi mật khẩu đăng nhập');
        e.preventDefault();
        
        const form = this;
        const button = document.getElementById('changePasswordBtn');
        const formData = new FormData(form);
        
        console.log('[LOG] changePasswordForm: Đã lấy form data, bắt đầu validation client-side');
        
        // Client-side validation
        const currentPassword = formData.get('current_password');
        const newPassword = formData.get('new_password');
        const confirmPassword = formData.get('new_password_confirmation');
        
        console.log('[LOG] changePasswordForm: Validation - Kiểm tra mật khẩu hiện tại');
        if (!currentPassword) {
            console.warn('[LOG] changePasswordForm: Validation thất bại - Thiếu mật khẩu hiện tại');
            showErrorToast('Vui lòng nhập mật khẩu hiện tại');
            return;
        }
        
        console.log('[LOG] changePasswordForm: Validation - Kiểm tra mật khẩu mới');
        if (!newPassword) {
            console.warn('[LOG] changePasswordForm: Validation thất bại - Thiếu mật khẩu mới');
            showErrorToast('Vui lòng nhập mật khẩu mới');
            return;
        }
        
        console.log('[LOG] changePasswordForm: Validation - Kiểm tra độ dài mật khẩu mới');
        if (newPassword.length < 6) {
            console.warn('[LOG] changePasswordForm: Validation thất bại - Mật khẩu mới quá ngắn');
            showErrorToast('Mật khẩu mới phải có ít nhất 6 ký tự');
            return;
        }
        
        console.log('[LOG] changePasswordForm: Validation - Kiểm tra xác nhận mật khẩu');
        if (newPassword !== confirmPassword) {
            console.warn('[LOG] changePasswordForm: Validation thất bại - Xác nhận mật khẩu không khớp');
            showErrorToast('Xác nhận mật khẩu không khớp');
            return;
        }
        
        console.log('[LOG] changePasswordForm: Client-side validation thành công, yêu cầu xác nhận người dùng');

        confirm({
            title: 'Xác nhận thay đổi',
            message: 'Bạn có chắc chắn muốn thay đổi mật khẩu đăng nhập?',
            confirmText: 'Xác nhận',
            onConfirm: async () => {
                console.log('[LOG] changePasswordForm: Người dùng đã xác nhận, bắt đầu gửi request');
                setButtonLoading(button, true);
                try {
                    console.log('[LOG] changePasswordForm: Gửi POST request đến server');
                    const response = await axios.post('{{ route("user.change-password") }}', {
                        current_password: currentPassword,
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword
                    });
                    
                    console.log('[LOG] changePasswordForm: Nhận response từ server:', response.data);
                    
                    if (response.data.success) {
                        console.log('[LOG] changePasswordForm: Thay đổi mật khẩu thành công');
                        showSuccessToast(response.data.message || 'Mật khẩu đã được cập nhật thành công!');
                        form.reset();
                        console.log('[LOG] changePasswordForm: Đã reset form');
                    }
                } catch (error) {
                    console.error('[LOG] changePasswordForm: Có lỗi xảy ra:', error);
                    
                    if (error.response && error.response.data) {
                        console.log('[LOG] changePasswordForm: Lỗi từ server:', error.response.data);
                        const errors = error.response.data.errors;
                        if (errors) {
                            console.log('[LOG] changePasswordForm: Hiển thị lỗi validation từ server');
                            Object.keys(errors).forEach(field => {
                                showErrorToast(errors[field][0]);
                            });
                        } else {
                            console.log('[LOG] changePasswordForm: Hiển thị lỗi chung từ server');
                            showErrorToast(error.response.data.message || 'Có lỗi xảy ra khi cập nhật mật khẩu');
                        }
                    } else {
                        console.log('[LOG] changePasswordForm: Lỗi network hoặc không xác định');
                        showErrorToast('Có lỗi xảy ra khi cập nhật mật khẩu');
                    }
                } finally {
                    console.log('[LOG] changePasswordForm: Kết thúc xử lý, restore button');
                    setButtonLoading(button, false);
                }
            }
        });
    });

    // Change Withdrawal Password Form
    console.log('[LOG] Event Listeners: Khởi tạo event listener cho changeWithdrawalPasswordForm');
    document.getElementById('changeWithdrawalPasswordForm').addEventListener('submit', async function(e) {
        console.log('[LOG] changeWithdrawalPasswordForm: Bắt đầu xử lý form thay đổi mật khẩu rút tiền');
        e.preventDefault();
        
        const form = this;
        const button = document.getElementById('changeWithdrawalPasswordBtn');
        const formData = new FormData(form);
        
        console.log('[LOG] changeWithdrawalPasswordForm: Đã lấy form data, bắt đầu validation client-side');
        
        // Client-side validation
        const loginPassword = formData.get('login_password');
        const newWithdrawalPassword = formData.get('new_withdrawal_password');
        const confirmWithdrawalPassword = formData.get('new_withdrawal_password_confirmation');
        
        console.log('[LOG] changeWithdrawalPasswordForm: Validation - Kiểm tra mật khẩu đăng nhập');
        if (!loginPassword) {
            console.warn('[LOG] changeWithdrawalPasswordForm: Validation thất bại - Thiếu mật khẩu đăng nhập');
            showErrorToast('Vui lòng nhập mật khẩu đăng nhập');
            return;
        }
        
        console.log('[LOG] changeWithdrawalPasswordForm: Validation - Kiểm tra mật khẩu rút tiền mới');
        if (!newWithdrawalPassword) {
            console.warn('[LOG] changeWithdrawalPasswordForm: Validation thất bại - Thiếu mật khẩu rút tiền mới');
            showErrorToast('Vui lòng nhập mật khẩu rút tiền mới');
            return;
        }
        
        console.log('[LOG] changeWithdrawalPasswordForm: Validation - Kiểm tra độ dài mật khẩu rút tiền');
        if (newWithdrawalPassword.length < 4) {
            console.warn('[LOG] changeWithdrawalPasswordForm: Validation thất bại - Mật khẩu rút tiền quá ngắn');
            showErrorToast('Mật khẩu rút tiền phải có ít nhất 4 ký tự');
            return;
        }
        
        console.log('[LOG] changeWithdrawalPasswordForm: Validation - Kiểm tra xác nhận mật khẩu rút tiền');
        if (newWithdrawalPassword !== confirmWithdrawalPassword) {
            console.warn('[LOG] changeWithdrawalPasswordForm: Validation thất bại - Xác nhận mật khẩu rút tiền không khớp');
            showErrorToast('Xác nhận mật khẩu rút tiền không khớp');
            return;
        }
        
        console.log('[LOG] changeWithdrawalPasswordForm: Client-side validation thành công, yêu cầu xác nhận người dùng');

        confirm({
            title: 'Xác nhận thay đổi',
            message: 'Bạn có chắc chắn muốn thay đổi mật khẩu rút tiền?',
            confirmText: 'Xác nhận',
            onConfirm: async () => {
                console.log('[LOG] changeWithdrawalPasswordForm: Người dùng đã xác nhận, bắt đầu gửi request');
                setButtonLoading(button, true);
                
                try {
                    console.log('[LOG] changeWithdrawalPasswordForm: Gửi POST request đến server');
                    const response = await axios.post('{{ route("user.change-withdrawal-password") }}', {
                        login_password: loginPassword,
                        new_withdrawal_password: newWithdrawalPassword,
                        new_withdrawal_password_confirmation: confirmWithdrawalPassword
                    });
                    
                    console.log('[LOG] changeWithdrawalPasswordForm: Nhận response từ server:', response.data);
                    
                    if (response.data.success) {
                        console.log('[LOG] changeWithdrawalPasswordForm: Thay đổi mật khẩu rút tiền thành công');
                        showSuccessToast(response.data.message || 'Mật khẩu rút tiền đã được cập nhật thành công!');
                        form.reset();
                        console.log('[LOG] changeWithdrawalPasswordForm: Đã reset form');
                    }
                } catch (error) {
                    console.error('[LOG] changeWithdrawalPasswordForm: Có lỗi xảy ra:', error);
                    
                    if (error.response && error.response.data) {
                        console.log('[LOG] changeWithdrawalPasswordForm: Lỗi từ server:', error.response.data);
                        const errors = error.response.data.errors;
                        if (errors) {
                            console.log('[LOG] changeWithdrawalPasswordForm: Hiển thị lỗi validation từ server');
                            Object.keys(errors).forEach(field => {
                                showErrorToast(errors[field][0]);
                            });
                        } else {
                            console.log('[LOG] changeWithdrawalPasswordForm: Hiển thị lỗi chung từ server');
                            showErrorToast(error.response.data.message || 'Có lỗi xảy ra khi cập nhật mật khẩu rút tiền');
                        }
                    } else {
                        console.log('[LOG] changeWithdrawalPasswordForm: Lỗi network hoặc không xác định');
                        showErrorToast('Có lỗi xảy ra khi cập nhật mật khẩu rút tiền');
                    }
                } finally {
                    console.log('[LOG] changeWithdrawalPasswordForm: Kết thúc xử lý, restore button');
                    setButtonLoading(button, false);
                }
            }
        });
    });

    // Log hoàn thành khởi tạo
    console.log('[LOG] Event Listeners: Đã hoàn thành khởi tạo tất cả event listeners');
    console.log('[LOG] Page Ready: Trang bảo mật đã sẵn sàng sử dụng');

</script>
@endpush
