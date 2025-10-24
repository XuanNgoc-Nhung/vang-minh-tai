# Hướng dẫn SEO cho Minh Tài Jewelry

## Các thẻ Meta đã được bổ sung

### 1. Meta Tags cơ bản
- **Meta Description**: Mô tả ngắn gọn về trang web
- **Meta Keywords**: Từ khóa liên quan đến dịch vụ
- **Meta Author**: Tác giả/Chủ sở hữu website
- **Meta Robots**: Hướng dẫn bot tìm kiếm
- **Meta Language**: Ngôn ngữ của website
- **Meta Revisit**: Tần suất bot quay lại
- **Meta Distribution**: Phạm vi phân phối
- **Meta Rating**: Đánh giá nội dung

### 2. Open Graph Tags (Facebook, LinkedIn)
- **og:title**: Tiêu đề khi chia sẻ
- **og:description**: Mô tả khi chia sẻ
- **og:type**: Loại nội dung (website)
- **og:url**: URL của trang
- **og:site_name**: Tên website
- **og:locale**: Ngôn ngữ địa phương
- **og:image**: Hình ảnh khi chia sẻ
- **og:image:width/height**: Kích thước hình ảnh
- **og:image:alt**: Mô tả hình ảnh

### 3. Twitter Card Tags
- **twitter:card**: Loại card Twitter
- **twitter:title**: Tiêu đề cho Twitter
- **twitter:description**: Mô tả cho Twitter
- **twitter:image**: Hình ảnh cho Twitter
- **twitter:site**: Tài khoản Twitter chính
- **twitter:creator**: Tác giả Twitter

### 4. Structured Data (JSON-LD)
- Thông tin tổ chức chi tiết
- Địa chỉ và thông tin liên hệ
- Các dịch vụ cung cấp
- Mạng xã hội liên kết

## Cách tùy chỉnh cho từng trang

### Trang chủ
```php
@section('title', 'Minh Tài Jewelry - Đầu tư vàng và kim cương uy tín')
@section('description', 'Đầu tư vàng và kim cương an toàn, uy tín với lãi suất hấp dẫn. Dịch vụ chuyên nghiệp, bảo mật cao. Đăng ký ngay!')
@section('keywords', 'đầu tư vàng, kim cương, đầu tư tài chính, lãi suất cao, an toàn, uy tín')
```

### Trang sản phẩm
```php
@section('title', 'Đầu tư vàng - Minh Tài Jewelry')
@section('description', 'Đầu tư vàng an toàn với lãi suất hấp dẫn. Bảo đảm uy tín, minh bạch. Đăng ký ngay!')
@section('keywords', 'đầu tư vàng, vàng, đầu tư, lãi suất, an toàn')
```

### Trang về chúng tôi
```php
@section('title', 'Về chúng tôi - Minh Tài Jewelry')
@section('description', 'Tìm hiểu về Minh Tài Jewelry - Công ty đầu tư vàng và kim cương uy tín hàng đầu Việt Nam')
@section('keywords', 'về chúng tôi, công ty, lịch sử, uy tín, giấy phép')
```

## Hình ảnh cần chuẩn bị

### 1. Open Graph Image (og:image)
- **Kích thước**: 1200x630 pixels
- **Định dạng**: JPG hoặc PNG
- **Nội dung**: Logo + tên công ty + slogan
- **Đường dẫn**: `public/images/og-image.jpg`

### 2. Twitter Card Image
- **Kích thước**: 1200x600 pixels
- **Định dạng**: JPG hoặc PNG
- **Nội dung**: Tương tự Open Graph
- **Đường dẫn**: `public/images/twitter-card.jpg`

### 3. Logo chính
- **Kích thước**: Tối thiểu 200x200 pixels
- **Định dạng**: PNG (có nền trong suốt)
- **Đường dẫn**: `public/images/logo.png`

## Các thẻ Meta bổ sung có thể thêm

### 1. Geo Tags (nếu cần)
```html
<meta name="geo.region" content="VN-SG">
<meta name="geo.placename" content="Ho Chi Minh City">
<meta name="geo.position" content="10.8231;106.6297">
<meta name="ICBM" content="10.8231, 106.6297">
```

### 2. Mobile App Meta
```html
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="Minh Tài Jewelry">
```

### 3. Security Headers
```html
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="DENY">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
```

## Kiểm tra SEO

### 1. Google Search Console
- Đăng ký và xác minh website
- Theo dõi hiệu suất tìm kiếm
- Kiểm tra lỗi crawl

### 2. Google PageSpeed Insights
- Kiểm tra tốc độ tải trang
- Tối ưu hình ảnh
- Cải thiện Core Web Vitals

### 3. Công cụ kiểm tra Meta Tags
- [Meta Tags Generator](https://metatags.io/)
- [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
- [Twitter Card Validator](https://cards-dev.twitter.com/validator)

## Lưu ý quan trọng

1. **Nội dung unique**: Mỗi trang cần có title và description riêng
2. **Từ khóa**: Sử dụng từ khóa tự nhiên, không spam
3. **Hình ảnh**: Tối ưu kích thước và định dạng
4. **Tốc độ**: Đảm bảo trang tải nhanh
5. **Mobile**: Tối ưu cho thiết bị di động
6. **HTTPS**: Sử dụng SSL certificate
7. **Sitemap**: Tạo sitemap.xml
8. **Robots.txt**: Cấu hình robots.txt

## Cập nhật định kỳ

- Kiểm tra và cập nhật nội dung thường xuyên
- Theo dõi thứ hạng từ khóa
- Phân tích đối thủ cạnh tranh
- Cải thiện trải nghiệm người dùng
