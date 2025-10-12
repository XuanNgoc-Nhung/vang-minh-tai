# Components Directory

Thư mục này chứa các component Blade có thể tái sử dụng trong ứng dụng.

## Cách sử dụng

### 1. Navbar Component

Component navbar chính cho trang user:

```blade
@include('components.navbar')
```

**Tính năng:**
- Responsive design
- Hiển thị thông tin user khi đã đăng nhập
- Dropdown menu cho user
- Nút đăng ký cho user chưa đăng nhập

### 2. Admin Header Component

Component header cho trang admin:

```blade
@include('components.admin-header')
```

**Tính năng:**
- Logo admin
- Nút toggle sidebar cho mobile
- Responsive design

## Thêm Component mới

1. Tạo file `.blade.php` trong thư mục `components/`
2. Viết HTML và logic Blade trong file
3. Sử dụng `@include('components.tên-component')` để include vào layout

## Lưu ý

- Tất cả component đều sử dụng Bootstrap 5
- Component có thể sử dụng các biến global của Laravel
- Đảm bảo component có thể hoạt động độc lập
