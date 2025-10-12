@extends('admin.layout.app')

@section('title', 'Lịch sử tiết kiệm')
@section('nav.lich-su-tiet-kiem_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Lịch sử tiết kiệm</span>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-success">Danh sách tài khoản tiết kiệm</strong>
        <div class="text-muted small">Tổng: {{ $dauTu->total() }}</div>
    </div>
    
    <div class="card-body p-0">
        <style>
            .admin-tiet-kiem-table th,
            .admin-tiet-kiem-table td { 
                min-width: 120px; 
                vertical-align: middle;
            }
            .admin-tiet-kiem-table th:nth-child(1),
            .admin-tiet-kiem-table td:nth-child(1) { min-width: 60px; }
            .admin-tiet-kiem-table th:nth-child(2),
            .admin-tiet-kiem-table td:nth-child(2) { min-width: 200px; }
            .admin-tiet-kiem-table th:nth-child(3),
            .admin-tiet-kiem-table td:nth-child(3) { min-width: 150px; }
            .admin-tiet-kiem-table th:nth-child(4),
            .admin-tiet-kiem-table td:nth-child(4) { min-width: 120px; }
            .admin-tiet-kiem-table th:nth-child(5),
            .admin-tiet-kiem-table td:nth-child(5) { min-width: 100px; }
            .admin-tiet-kiem-table th:nth-child(6),
            .admin-tiet-kiem-table td:nth-child(6) { min-width: 100px; }
            .admin-tiet-kiem-table th:nth-child(7),
            .admin-tiet-kiem-table td:nth-child(7) { min-width: 120px; }
            .admin-tiet-kiem-table th:nth-child(8),
            .admin-tiet-kiem-table td:nth-child(8) { min-width: 120px; }
            .admin-tiet-kiem-table th:nth-child(9),
            .admin-tiet-kiem-table td:nth-child(9) { min-width: 100px; }
            .admin-tiet-kiem-table th:nth-child(10),
            .admin-tiet-kiem-table td:nth-child(10) { min-width: 80px; }
        </style>
        
        <div class="table-responsive">
            <table class="table table-hover table-bordered admin-tiet-kiem-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">STT</th>
                        <th>Người dùng</th>
                        <th>Sản phẩm</th>
                        <th class="text-end">Số chu kỳ</th>
                        <th class="text-end">Số tiền</th>
                        <th class="text-end">Hoa hồng</th>
                        <th class="text-center">Ngày bắt đầu</th>
                        <th class="text-center">Ngày kết thúc</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dauTu as $index => $item)
                    <tr>
                        <td class="text-center">{{ $dauTu->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($item->user->email ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $item->user->email ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $item->user->phone ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-medium">{{ $item->sanPham->ten ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $item->sanPham->slug ?? '' }}</small>
                            </div>
                        </td>
                        <td class="text-end">
                            <span>{{ $item->so_chu_ky ?? 0 }} tháng</span>
                        </td>
                        <td class="text-end">
                            <span class="fw-medium text-success">{{ number_format($item->so_tien ?? 0, 0, ',', '.') }} VNĐ</span>
                        </td>
                        <td class="text-end">
                            <span class="fw-medium text-warning">{{ number_format($item->hoa_hong ?? 0, 0, ',', '.') }} VNĐ</span>
                        </td>
                        <td class="text-center">
                            @if($item->ngay_bat_dau)
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->ngay_bat_dau)->format('d/m/Y') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->ngay_ket_thuc)
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->ngay_ket_thuc)->format('d/m/Y') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = '';
                                $statusText = '';
                                switch($item->trang_thai) {
                                    case 0:
                                        $statusClass = 'bg-warning';
                                        $statusText = 'Chờ duyệt';
                                        break;
                                    case 1:
                                        $statusClass = 'bg-success';
                                        $statusText = 'Đang chạy';
                                        break;
                                    case 2:
                                        $statusClass = 'bg-info';
                                        $statusText = 'Hoàn thành';
                                        break;
                                    case 3:
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Hủy bỏ';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        $statusText = 'Không xác định';
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $item->id }}"
                                        title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($item->trang_thai == 1)
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="updateStatus({{ $item->id }}, 2)"
                                        title="Đánh dấu đã hoàn thành">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Modal chi tiết -->
                    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chi tiết tiết kiệm</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Thông tin người dùng</h6>
                                            <p><strong>Email:</strong> {{ $item->user->email ?? 'N/A' }}</p>
                                            <p><strong>Số điện thoại:</strong> {{ $item->user->phone ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Thông tin sản phẩm</h6>
                                            <p><strong>Tên sản phẩm:</strong> {{ $item->sanPham->ten ?? 'N/A' }}</p>
                                            <p><strong>Lãi suất:</strong> {{ number_format($item->sanPham->lai_suat ?? 0, 2, ',', '.') }}%</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Thông tin đầu tư</h6>
                                            <p><strong>Số chu kỳ:</strong> {{ $item->so_chu_ky ?? 0 }} tháng</p>
                                            <p><strong>Số tiền:</strong> {{ number_format($item->so_tien ?? 0, 0, ',', '.') }} VNĐ</p>
                                            <p><strong>Hoa hồng:</strong> {{ number_format($item->hoa_hong ?? 0, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Thời gian</h6>
                                            <p><strong>Ngày bắt đầu:</strong> {{ $item->ngay_bat_dau ? \Carbon\Carbon::parse($item->ngay_bat_dau)->format('d/m/Y H:i') : 'N/A' }}</p>
                                            <p><strong>Ngày kết thúc:</strong> {{ $item->ngay_ket_thuc ? \Carbon\Carbon::parse($item->ngay_ket_thuc)->format('d/m/Y H:i') : 'N/A' }}</p>
                                            <p><strong>Trạng thái:</strong> 
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    @if($item->ghi_chu)
                                    <hr>
                                    <div>
                                        <h6 class="text-muted">Ghi chú</h6>
                                        <p>{{ $item->ghi_chu }}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p class="mb-0">Chưa có dữ liệu tiết kiệm nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($dauTu->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Hiển thị {{ $dauTu->firstItem() }} đến {{ $dauTu->lastItem() }} trong tổng số {{ $dauTu->total() }} kết quả
            </div>
            <div>
                {{ $dauTu->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function updateStatus(id, status) {
    let confirmTitle = 'Xác nhận cập nhật trạng thái';
    let confirmMessage = 'Bạn có chắc chắn muốn cập nhật trạng thái này?';
    let confirmButtonText = 'Cập nhật';
    
    if (status == 2) {
        confirmTitle = 'Xác nhận hoàn thành';
        confirmMessage = 'Bạn có chắc chắn muốn đánh dấu hoàn thành? Số tiền sẽ được cộng vào số dư tài khoản của người dùng.';
        confirmButtonText = 'Hoàn thành';
    }
    
    // Sử dụng confirm Bootstrap thay vì confirm gốc
    confirm({
        title: confirmTitle,
        message: confirmMessage,
        confirmText: confirmButtonText,
        onConfirm: function() {
            fetch('{{ route("admin.lich-su-tiet-kiem.update-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: id,
                    trang_thai: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorToast(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Có lỗi xảy ra khi cập nhật trạng thái!');
            });
        }
    });
}
</script>
@endsection
