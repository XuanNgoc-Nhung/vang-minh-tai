# Hướng dẫn sử dụng DataList từ GiaVangData

## Tổng quan
Dự án đã được cập nhật để lấy ra `dataList` từ `giaVangData` API BTMC. `dataList` chứa danh sách các sản phẩm vàng với thông tin chi tiết về giá mua vào, bán ra, và thời gian cập nhật.

## Cấu trúc dữ liệu

### GiaVangData
```json
{
  "DataList": {
    "Data": [
      {
        "@row": "1",
        "@n_1": "Tên sản phẩm",
        "@k_1": "24k",
        "@h_1": "999.9",
        "@pb_1": "12900000",
        "@ps_1": "13200000", 
        "@pt_1": "0",
        "@d_1": "26/09/2025 09:37"
      }
    ]
  }
}
```

### DataList
`dataList` là mảng các sản phẩm vàng với các trường:
- `@row`: Số thứ tự
- `@n_{row}`: Tên sản phẩm
- `@k_{row}`: Loại vàng (24k, 999.9, etc.)
- `@h_{row}`: Hàm lượng vàng
- `@pb_{row}`: Giá mua vào (VNĐ)
- `@ps_{row}`: Giá bán ra (VNĐ)
- `@pt_{row}`: Giá khác (thường là 0)
- `@d_{row}`: Thời gian cập nhật

## Cách sử dụng

### 1. Trong Controller
```php
// Lấy dataList từ giaVangData
$giaVangData = $this->getGiaVangDataDirect();
$dataList = $this->getDataListFromGiaVang($giaVangData);

// Kiểm tra dataList có tồn tại không
if ($dataList) {
    $count = count($dataList);
    // Xử lý dữ liệu...
}
```

### 2. Trong View (Blade)
```php
@if($dataList && count($dataList) > 0)
    @foreach($dataList as $item)
        <div class="gold-item">
            <h6>{{ $item['@n_' . $item['@row']] }}</h6>
            <p>Mua vào: {{ number_format($item['@pb_' . $item['@row']], 0, ',', '.') }} VNĐ</p>
            <p>Bán ra: {{ number_format($item['@ps_' . $item['@row']], 0, ',', '.') }} VNĐ</p>
            <small>{{ $item['@d_' . $item['@row']] }}</small>
        </div>
    @endforeach
@endif
```

### 3. API Endpoints

#### Lấy dataList qua API
```
GET /api/gia-vang-data-list
```

Response:
```json
{
  "success": true,
  "dataList": [...],
  "count": 14,
  "timestamp": "2025-09-26 05:42:20"
}
```

#### Test dataList
```
GET /test-data-list
```

Response:
```json
{
  "giaVangData_keys": ["DataList"],
  "dataList": [...],
  "dataList_count": 14,
  "first_item": {...}
}
```

## Các method helper

### `getDataListFromGiaVang($giaVangData)`
- **Mục đích**: Lấy dataList từ giaVangData
- **Tham số**: `$giaVangData` - dữ liệu từ API BTMC
- **Trả về**: Mảng dataList hoặc null nếu không tìm thấy

### `getGiaVangDataList()`
- **Mục đích**: API endpoint trả về dataList
- **Trả về**: JSON response với dataList và metadata

### `refreshGiaVang()`
- **Mục đích**: Làm mới dữ liệu giá vàng
- **Trả về**: JSON response với dataList mới (nếu AJAX) hoặc redirect

## Ví dụ sử dụng trong JavaScript

```javascript
// Lấy dataList qua AJAX
fetch('/api/gia-vang-data-list')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('DataList count:', data.count);
      data.dataList.forEach(item => {
        console.log('Product:', item['@n_' + item['@row']]);
        console.log('Buy price:', item['@pb_' + item['@row']]);
        console.log('Sell price:', item['@ps_' + item['@row']]);
      });
    }
  });

// Làm mới dữ liệu
function refreshGiaVang() {
  fetch('/refresh-gia-vang')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload(); // Reload trang để hiển thị dữ liệu mới
      }
    });
}
```

## Lưu ý quan trọng

1. **Kiểm tra null**: Luôn kiểm tra `$dataList` có tồn tại trước khi sử dụng
2. **Format số**: Sử dụng `number_format()` để hiển thị giá tiền
3. **Xử lý lỗi**: API có thể trả về null nếu có lỗi kết nối
4. **Cache**: Dữ liệu được cache trong 5 phút để tối ưu performance
5. **Encoding**: Dữ liệu từ API có thể chứa ký tự Unicode, cần xử lý đúng encoding

## Troubleshooting

### DataList trống
- Kiểm tra kết nối API
- Xem log trong `storage/logs/laravel.log`
- Test API trực tiếp: `curl http://api.btmc.vn/api/BTMCAPI/getpricebtmc?key=3kd8ub1llcg9t45hnoh8hmn7t5kc2v`

### Lỗi hiển thị
- Kiểm tra cú pháp Blade template
- Đảm bảo biến `$dataList` được truyền từ controller
- Kiểm tra encoding UTF-8

### Performance
- Sử dụng cache để giảm tải API
- Chỉ load dataList khi cần thiết
- Cân nhắc sử dụng AJAX để load dữ liệu động
