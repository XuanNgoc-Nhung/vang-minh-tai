@extends('admin.layout.app')

@section('title', 'Nạp rút')
@section('nav.nap-rut_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Nạp rút</span>
@endsection
@section('content')
<div class="mb-3">
	<div class="card border-0 shadow-sm">
		<div class="card-header bg-white">
			<strong class="text-success">Bộ lọc</strong>
		</div>
		<div class="card-body">
			<form method="GET" action="{{ route('admin.nap-rut') }}" class="row g-2 align-items-center" role="search">
				<div class="col-12 col-md-3 col-lg-2">
					<select name="loai" class="form-select form-select-sm">
						<option value="">Tất cả loại</option>
						<option value="nap" {{ request('loai') == 'nap' ? 'selected' : '' }}>Nạp tiền</option>
						<option value="rut" {{ request('loai') == 'rut' ? 'selected' : '' }}>Rút tiền</option>
					</select>
				</div>
				<div class="col-12 col-md-3 col-lg-2">
					<select name="trang_thai" class="form-select form-select-sm">
						<option value="">Tất cả trạng thái</option>
						<option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Chờ xử lý</option>
						<option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Đã duyệt</option>
						<option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Từ chối</option>
					</select>
				</div>
				<div class="col-12 col-md-6 col-lg-4">
					<input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm theo tên, số TK, chủ TK, nội dung...">
				</div>
				<div class="col-12 col-md-auto d-flex gap-2">
					<button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
					@if(request('q') || request('loai') || request('trang_thai'))
						<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.nap-rut') }}">Xoá lọc</a>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>

<div class="card border-0 shadow-sm">
	<div class="card-header bg-white d-flex justify-content-between align-items-center">
		<strong class="text-success">Danh sách giao dịch nạp rút</strong>
		<div class="d-flex align-items-center gap-2">
			<div class="text-muted small me-2">Tổng: {{ $transactions->total() }}</div>
		</div>
	</div>
	<div class="card-body p-0">
		<style>
			.admin-nap-rut-table th,
			.admin-nap-rut-table td { min-width: 120px; }
			.admin-nap-rut-table th:nth-child(1),
			.admin-nap-rut-table td:nth-child(1) { min-width: 70px; text-align:center; }
			.admin-nap-rut-table .action-col { position: sticky; right: 0; background: #fff; z-index: 2; min-width: 180px; }
			.admin-nap-rut-table thead th.action-col { z-index: 3; }
			.admin-nap-rut-table td.action-col { box-shadow: -4px 0 4px -4px rgba(0,0,0,.15); }
		</style>
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0 admin-nap-rut-table">
				<thead class="table-light">
					<tr>
						<th>STT</th>
						<th>Người dùng</th>
						<th>Loại</th>
						<th>Ngân hàng</th>
						<th>Số tài khoản</th>
						<th>Chủ tài khoản</th>
						<th>Số tiền</th>
						<th>Nội dung</th>
						<th>Trạng thái</th>
						<th>Thời gian</th>
						<th class="action-col text-center">Hành động</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($transactions as $index => $t)
					<tr>
						<td class="text-muted">{{ $transactions->firstItem() + $index }}</td>
						<td>
							<div class="d-flex flex-column">
								<strong>{{ $t->user->name ?? '—' }}</strong>
								<small class="text-muted">{{ $t->user->email ?? '—' }}</small>
							</div>
						</td>
						<td>
							<span class="badge {{ $t->loai == 'nap' ? 'text-bg-success' : 'text-bg-warning' }}">
								{{ $t->loai == 'nap' ? 'Nạp tiền' : 'Rút tiền' }}
							</span>
						</td>
						<td>{{ $t->ngan_hang ?? '—' }}</td>
						<td>{{ $t->so_tai_khoan ?? '—' }}</td>
						<td>{{ $t->chu_tai_khoan ?? '—' }}</td>
						<td>
							<strong class="{{ $t->loai == 'nap' ? 'text-success' : 'text-warning' }}">
								{{ $t->loai == 'nap' ? '+' : '-' }}{{ number_format($t->so_tien, 0, ',', '.') }} VNĐ
							</strong>
						</td>
						<td>
							@if(!empty($t->noi_dung))
								<span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $t->noi_dung }}">{{ $t->noi_dung }}</span>
							@else
								<span class="text-muted">—</span>
							@endif
						</td>
						<td>
							@php
								$statusConfig = [
									0 => ['class' => 'text-bg-warning', 'text' => 'Chờ xử lý'],
									1 => ['class' => 'text-bg-success', 'text' => 'Đã duyệt'],
									2 => ['class' => 'text-bg-danger', 'text' => 'Từ chối']
								];
								$status = $statusConfig[$t->trang_thai] ?? $statusConfig[0];
							@endphp
							<span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
						</td>
						<td>
							<div class="d-flex flex-column">
								<small>{{ $t->created_at->format('d/m/Y') }}</small>
								<small class="text-muted">{{ $t->created_at->format('H:i:s') }}</small>
							</div>
						</td>
						<td class="action-col text-center">
							<div class="btn-group btn-group-sm" role="group">
								<button type="button" class="btn {{ $t->trang_thai == 0 ? 'btn-outline-success' : 'btn-success' }}" 
									data-id="{{ $t->id }}" 
									{{ $t->trang_thai != 0 ? 'disabled' : '' }}
									title="{{ $t->trang_thai == 1 ? 'Đã duyệt' : ($t->trang_thai == 2 ? 'Đã từ chối' : 'Duyệt') }}"
									onclick="approveTransaction({{ $t->id }}, this)">
									<i class="bi bi-check-lg"></i>
								</button>
								<button type="button" class="btn {{ $t->trang_thai == 0 ? 'btn-outline-danger' : 'btn-danger' }}" 
									data-id="{{ $t->id }}" 
									{{ $t->trang_thai != 0 ? 'disabled' : '' }}
									title="{{ $t->trang_thai == 1 ? 'Đã duyệt' : ($t->trang_thai == 2 ? 'Đã từ chối' : 'Từ chối') }}"
									onclick="rejectTransaction({{ $t->id }}, this)">
									<i class="bi bi-x-lg"></i>
								</button>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="11" class="text-center text-muted py-4">Chưa có giao dịch nào.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="card-footer bg-white d-flex justify-content-between align-items-center">
		<div>
			{{ $transactions->withQueryString()->links('vendor.pagination.admin') }}
		</div>
	</div>
</div>


<script>
// Approve transaction function
function approveTransaction(id, button) {
	// Không xử lý nếu button bị disabled
	if(button.disabled) return;
	
	if (window.showConfirm) {
		window.showConfirm({
			title: 'Duyệt giao dịch',
			message: 'Bạn có chắc chắn muốn duyệt giao dịch này?',
			confirmText: 'Duyệt',
			onConfirm: function(){
				updateTransactionStatus(id, 1);
			}
		});
	} else if (window.confirm('Bạn có chắc chắn muốn duyệt giao dịch này?')) {
		updateTransactionStatus(id, 1);
	}
}

// Reject transaction function
function rejectTransaction(id, button) {
	// Không xử lý nếu button bị disabled
	if(button.disabled) return;
	
	if (window.showConfirm) {
		window.showConfirm({
			title: 'Từ chối giao dịch',
			message: 'Bạn có chắc chắn muốn từ chối giao dịch này?',
			confirmText: 'Từ chối',
			onConfirm: function(){
				updateTransactionStatus(id, 2);
			}
		});
	} else if (window.confirm('Bạn có chắc chắn muốn từ chối giao dịch này?')) {
		updateTransactionStatus(id, 2);
	}
}

// Update transaction status
async function updateTransactionStatus(id, status) {
	try {
		var url = "{{ route('admin.nap-rut.update-status') }}";
		var res = await axios.post(url, { 
			id: id, 
			trang_thai: status 
		}, { 
			headers: { 'Content-Type': 'application/json' } 
		});
		
		var ok = !!(res && res.data && res.data.success);
		var msg = (res && res.data && res.data.message) || (ok ? 'Đã cập nhật trạng thái' : 'Cập nhật thất bại');
		
		if(window.showToast) {
			window.showToast(ok ? 'success' : 'error', ok ? 'Thành công' : 'Lỗi', msg);
		}
		
		if(ok) { 
			setTimeout(function(){ window.location.reload(); }, 800); 
		}
	} catch(err) {
		var msg = (err && err.response && err.response.data && err.response.data.message) || 'Có lỗi xảy ra. Vui lòng thử lại';
		if(window.showToast) window.showToast('error', 'Lỗi', msg);
	}
}
</script>
@endsection