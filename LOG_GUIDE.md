# Hướng dẫn theo dõi Log cho trang Bảo mật

## Tổng quan
Đã bổ sung log chi tiết cho toàn bộ luồng xử lý của trang bảo mật để giúp theo dõi và debug.

## Các loại Log đã được bổ sung

### 1. Backend Log (Laravel)
**File:** `app/Http/Controllers/UserDashboardController.php`

#### Method: `baoMat()`
- Log khi bắt đầu hiển thị trang bảo mật
- Log thông tin user (ID, email)
- Log khi hiển thị thành công
- Log lỗi nếu có exception

#### Method: `changePassword()`
- Log khi bắt đầu thay đổi mật khẩu đăng nhập
- Log thông tin request (user_id, email, is_ajax, ip_address, user_agent)
- Log khi validation thất bại (với chi tiết lỗi)
- Log khi bắt đầu kiểm tra mật khẩu hiện tại
- Log khi mật khẩu hiện tại không đúng
- Log khi mật khẩu hiện tại đúng
- Log khi cập nhật mật khẩu thành công
- Log lỗi nếu có exception trong quá trình cập nhật

#### Method: `changeWithdrawalPassword()`
- Log khi bắt đầu thay đổi mật khẩu rút tiền
- Log thông tin request (user_id, email, is_ajax, ip_address, user_agent)
- Log khi validation thất bại (với chi tiết lỗi)
- Log khi bắt đầu kiểm tra mật khẩu đăng nhập
- Log khi mật khẩu đăng nhập không đúng
- Log khi mật khẩu đăng nhập đúng
- Log khi cập nhật mật khẩu rút tiền thành công
- Log lỗi nếu có exception trong quá trình cập nhật

### 2. Frontend Log (JavaScript)
**File:** `resources/views/user/dashboard/bao-mat.blade.php`

#### Khởi tạo trang
- Log khi trang được tải
- Log thông tin user (ID, email)
- Log timestamp

#### Function: `togglePassword()`
- Log khi bắt đầu toggle password
- Log khi hiển thị/ẩn mật khẩu
- Log lỗi nếu không tìm thấy field hoặc icon

#### Function: `setButtonLoading()`
- Log khi bắt đầu set loading cho button
- Log khi set button thành trạng thái loading
- Log khi restore button về trạng thái bình thường

#### Form: Change Password
- Log khi bắt đầu xử lý form
- Log khi lấy form data
- Log từng bước validation client-side
- Log khi validation thất bại (với chi tiết)
- Log khi validation thành công
- Log khi gửi POST request
- Log khi nhận response từ server
- Log khi thay đổi mật khẩu thành công
- Log khi reset form
- Log lỗi từ server (validation errors, general errors)
- Log lỗi network
- Log khi kết thúc xử lý

#### Form: Change Withdrawal Password
- Log tương tự như form Change Password
- Log riêng cho từng bước validation của mật khẩu rút tiền

#### Event Listeners
- Log khi khởi tạo event listeners
- Log khi hoàn thành khởi tạo
- Log khi trang sẵn sàng sử dụng

## Cách theo dõi Log

### 1. Backend Log (Laravel)
```bash
# Xem log real-time
tail -f storage/logs/laravel.log

# Tìm log liên quan đến bảo mật
grep "UserDashboardController" storage/logs/laravel.log

# Tìm log của user cụ thể
grep "user_id.*123" storage/logs/laravel.log
```

### 2. Frontend Log (Browser)
1. Mở Developer Tools (F12)
2. Chuyển đến tab Console
3. Thực hiện các thao tác trên trang bảo mật
4. Quan sát các log với prefix `[LOG]`

### 3. Filter Log theo loại
```bash
# Log thông tin
grep "Log::info" storage/logs/laravel.log

# Log cảnh báo
grep "Log::warning" storage/logs/laravel.log

# Log lỗi
grep "Log::error" storage/logs/laravel.log
```

## Cấu trúc Log

### Backend Log Format
```
[timestamp] local.INFO: UserDashboardController@methodName: Mô tả hành động {"user_id":123,"additional_data":"value"}
```

### Frontend Log Format
```
[LOG] ComponentName: Mô tả hành động
```

## Lợi ích của việc bổ sung Log

1. **Debug dễ dàng**: Theo dõi chính xác luồng xử lý
2. **Phát hiện lỗi nhanh**: Xác định chính xác vị trí lỗi
3. **Theo dõi bảo mật**: Log các thao tác thay đổi mật khẩu
4. **Phân tích hiệu suất**: Đo thời gian xử lý từng bước
5. **Audit trail**: Theo dõi hoạt động của user

## Lưu ý

- Log không chứa thông tin mật khẩu thực tế
- Log chứa thông tin IP và User Agent để bảo mật
- Có thể tắt log trong production bằng cách thay đổi LOG_LEVEL trong .env
- Log được lưu trong `storage/logs/laravel.log` (có thể rotate theo cấu hình)
