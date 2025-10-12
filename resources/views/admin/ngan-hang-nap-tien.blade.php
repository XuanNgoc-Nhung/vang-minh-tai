@extends('admin.layout.app')

@section('title', 'Ngân hàng nạp tiền')
@section('nav.ngan-hang-nap-tien_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Ngân hàng nạp tiền</span>
@endsection
@section('content')
<div class="mb-3">
	<div class="card border-0 shadow-sm">
		<div class="card-header bg-white">
			<strong class="text-success">Bộ lọc</strong>
		</div>
		<div class="card-body">
			<form method="GET" action="{{ route('admin.ngan-hang-nap-tien') }}" class="row g-2 align-items-center" role="search">
				<div class="col-12 col-md-6 col-lg-4">
					<input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tên NH/Số TK/Chủ TK/Chi nhánh">
				</div>
				<div class="col-12 col-md-auto d-flex gap-2">
					<button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
					@if(request('q'))
						<a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.ngan-hang-nap-tien') }}">Xoá lọc</a>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>

<div class="card border-0 shadow-sm">
	<div class="card-header bg-white d-flex justify-content-between align-items-center">
		<strong class="text-success">Danh sách ngân hàng nạp rút</strong>
		<div class="d-flex align-items-center gap-2">
			<div class="text-muted small me-2">Tổng: {{ $banks->total() }}</div>
			<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateBank">
				<i class="bi bi-plus-lg"></i> Thêm mới
			</button>
		</div>
	</div>
	<div class="card-body p-0">
		<style>
			.admin-banks-table th,
			.admin-banks-table td { min-width: 150px; }
			.admin-banks-table th:nth-child(1),
			.admin-banks-table td:nth-child(1) { min-width: 70px; text-align:center; }
			.admin-banks-table .image-cell { width: 140px; }
			.admin-banks-table .image-cell .thumb { width:120px; height:auto; }
			.admin-banks-table .action-col { position: sticky; right: 0; background: #fff; z-index: 2; min-width: 160px; }
			.admin-banks-table thead th.action-col { z-index: 3; }
			.admin-banks-table td.action-col { box-shadow: -4px 0 4px -4px rgba(0,0,0,.15); }
		</style>
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0 admin-banks-table">
				<thead class="table-light">
					<tr>
						<th>STT</th>
						<th>Tên ngân hàng</th>
						<th class="image-cell">Hình ảnh</th>
						<th>Số tài khoản</th>
						<th>Chủ tài khoản</th>
						<th>Chi nhánh</th>
						<th>Ghi chú</th>
						<th>Trạng thái</th>
						<th class="action-col text-center">Hành động</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($banks as $index => $b)
					<tr>
						<td class="text-muted">{{ $banks->firstItem() + $index }}</td>
						<td>{{ $b->ten_ngan_hang ?? '—' }}</td>
						<td class="image-cell">
						@if(!empty($b->hinh_anh))
							@php
								$__img = (string) $b->hinh_anh;
								$__src = preg_match('/^https?:\\/\\//', $__img) ? $__img : asset($__img);
							@endphp
							<img src="{{ $__src }}" alt="Logo" class="thumb border rounded">
							@else
								<span class="text-muted small">—</span>
							@endif
						</td>
						<td>{{ $b->so_tai_khoan ?? '—' }}</td>
						<td>{{ $b->chu_tai_khoan ?? '—' }}</td>
						<td>{{ $b->chi_nhanh ?? '—' }}</td>
						<td>
							@if(!empty($b->ghi_chu))
								<span class="d-inline-block text-truncate" style="max-width: 260px;" title="{{ $b->ghi_chu }}">{{ $b->ghi_chu }}</span>
							@else
								<span class="text-muted">—</span>
							@endif
						</td>
						<td>
							<div class="d-flex align-items-center gap-2">
								<button type="button" class="btn btn-sm {{ (int)($b->trang_thai ?? 0) === 1 ? 'btn-outline-success' : 'btn-outline-secondary' }} px-2 py-0" title="Đổi trạng thái"
									onclick="toggleBankStatus({{ $b->id }}, {{ (int)($b->trang_thai ?? 0) }})">
									<i class="bi bi-power"></i>
								</button>
								<span class="badge {{ (int)($b->trang_thai ?? 0) === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
									{{ (int)($b->trang_thai ?? 0) === 1 ? 'Hoạt động' : 'Không hoạt động' }}
								</span>
							</div>
						</td>
						<td class="action-col text-center">
							<div class="btn-group btn-group-sm" role="group">
								<button type="button" class="btn btn-outline-primary btn-edit-bank"
									data-bs-toggle="modal"
									data-bs-target="#modalEditBank"
									data-id="{{ $b->id }}"
									data-ten_ngan_hang="{{ $b->ten_ngan_hang }}"
									data-hinh_anh="{{ $b->hinh_anh }}"
									data-so_tai_khoan="{{ $b->so_tai_khoan }}"
									data-chu_tai_khoan="{{ $b->chu_tai_khoan }}"
									data-chi_nhanh="{{ $b->chi_nhanh }}"
									data-ghi_chu='{{ $b->ghi_chu }}'
									data-trang_thai="{{ $b->trang_thai }}">
									<i class="bi bi-pencil-square"></i>
								</button>
								<button type="button" class="btn btn-outline-danger btn-delete-bank" data-id="{{ $b->id }}">
									<i class="bi bi-trash"></i>
								</button>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="9" class="text-center text-muted py-4">Chưa có ngân hàng nào.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="card-footer bg-white d-flex justify-content-between align-items-center">
		<div class="small text-muted">Hiển thị {{ $banks->firstItem() ?? 0 }}–{{ $banks->lastItem() ?? 0 }} / {{ $banks->total() }} ngân hàng</div>
		<div>
			{{ $banks->withQueryString()->links('vendor.pagination.admin') }}
		</div>
	</div>
</div>

<!-- Modal: Create Bank -->
<div class="modal fade" id="modalCreateBank" tabindex="-1" aria-labelledby="modalCreateBankLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalCreateBankLabel">Thêm ngân hàng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formCreateBank" enctype="multipart/form-data">
					@csrf
					<div class="row g-3">
						<div class="col-12 col-md-6">
							<label class="form-label">Tên ngân hàng</label>
						<input type="text" name="ten_ngan_hang" class="form-control" placeholder="Ví dụ: Vietcombank" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Logo/Hình ảnh (URL)</label>
						<input type="url" name="hinh_anh" placeholder="https://example.com/logo.png" class="form-control">
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Số tài khoản</label>
						<input type="text" name="so_tai_khoan" class="form-control" placeholder="Ví dụ: 0123456789" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Chủ tài khoản</label>
						<input type="text" name="chu_tai_khoan" class="form-control" placeholder="Họ và tên chủ tài khoản" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Chi nhánh</label>
						<input type="text" name="chi_nhanh" class="form-control" placeholder="Ví dụ: CN Hà Nội">
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Trạng thái</label>
							<select name="trang_thai" class="form-select" required>
								<option value="1" selected>Hoạt động</option>
								<option value="0">Không hoạt động</option>
							</select>
						</div>
						<div class="col-12">
							<label class="form-label">Ghi chú</label>
						<textarea name="ghi_chu" class="form-control" rows="3" placeholder="Ghi chú thêm (tuỳ chọn)"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" id="btnCreateBankSubmit">Lưu</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal: Edit Bank -->
<div class="modal fade" id="modalEditBank" tabindex="-1" aria-labelledby="modalEditBankLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalEditBankLabel">Sửa ngân hàng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formEditBank" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" id="editBankId">
					<div class="row g-3">
						<div class="col-12 col-md-6">
							<label class="form-label">Tên ngân hàng</label>
						<input type="text" name="ten_ngan_hang" id="editTenNganHang" class="form-control" placeholder="Ví dụ: Vietcombank" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Logo/Hình ảnh (URL)</label>
						<input type="url" name="hinh_anh" id="editHinhAnhUrl" placeholder="https://example.com/logo.png" class="form-control">
							<div class="form-text">Để trống nếu không thay đổi.</div>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Số tài khoản</label>
						<input type="text" name="so_tai_khoan" id="editSoTaiKhoan" class="form-control" placeholder="Ví dụ: 0123456789" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Chủ tài khoản</label>
						<input type="text" name="chu_tai_khoan" id="editChuTaiKhoan" class="form-control" placeholder="Họ và tên chủ tài khoản" required>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Chi nhánh</label>
						<input type="text" name="chi_nhanh" id="editChiNhanh" class="form-control" placeholder="Ví dụ: CN Hà Nội">
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label">Trạng thái</label>
							<select name="trang_thai" id="editTrangThai" class="form-select" required>
								<option value="1">Hoạt động</option>
								<option value="0">Không hoạt động</option>
							</select>
						</div>
						<div class="col-12">
							<label class="form-label">Ghi chú</label>
						<textarea name="ghi_chu" id="editGhiChu" class="form-control" rows="3" placeholder="Ghi chú thêm (tuỳ chọn)"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" id="btnEditBankSubmit">Lưu thay đổi</button>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
	window.toggleBankStatus = async function(id, current){
		try{
			var url = "{{ route('admin.ngan-hang-nap-tien.update') }}";
			var next = Number(current) === 1 ? 0 : 1;
			var res = await axios.post(url, { id: id, trang_thai: next }, { headers: { 'Content-Type': 'application/json' } });
			var ok = !!(res && res.data && res.data.success);
			var msg = (res && res.data && res.data.message) || (ok ? 'Đã cập nhật trạng thái' : 'Cập nhật thất bại');
			if(window.showToast) window.showToast(ok ? 'success' : 'error', ok ? 'Thành công' : 'Lỗi', msg);
			if(ok){ setTimeout(function(){ window.location.reload(); }, 400); }
		}catch(err){
			var msg = (err && err.response && err.response.data && err.response.data.message) || 'Có lỗi xảy ra. Vui lòng thử lại';
			if(window.showToast) window.showToast('error', 'Lỗi', msg);
		}
	}

	function buildPayload(form){
		var payload = {};
		var els = form.querySelectorAll('input, textarea, select');
		els.forEach(function(el){
			if(!el.name) return;
			if(el.type === 'checkbox'){
				if(el.checked) payload[el.name] = el.value || '1';
			} else if(el.type !== 'file'){
				payload[el.name] = el.value;
			}
		});
		return payload;
	}

	var btnCreate = document.getElementById('btnCreateBankSubmit');
	var formCreate = document.getElementById('formCreateBank');
	if(btnCreate && formCreate){
		btnCreate.addEventListener('click', function(){
			if(!formCreate.checkValidity()){ formCreate.reportValidity(); return; }
			btnCreate.disabled = true;
			var data = buildPayload(formCreate);
			axios.post("{{ route('admin.ngan-hang-nap-tien.store') }}", data, { headers: { 'Content-Type': 'application/json' } })
				.then(function(res){
					if(res.data && res.data.success){
						if(window.showToast) window.showToast('success', 'Thành công', res.data.message || 'Đã tạo ngân hàng');
						setTimeout(function(){ window.location.reload(); }, 800);
					} else {
						if(window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể tạo ngân hàng');
					}
				})
				.catch(function(err){
					var msg = 'Không thể tạo ngân hàng';
					if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
					if (err.response && err.response.data && err.response.data.errors) {
						var firstKey = Object.keys(err.response.data.errors)[0];
						var firstMsg = err.response.data.errors[firstKey][0];
						msg = firstMsg || msg;
					}
					if(window.showToast) window.showToast('error', 'Lỗi', msg);
				})
				.finally(function(){ btnCreate.disabled = false; });
		});
	}

			document.querySelectorAll('.btn-edit-bank').forEach(function(btn){
		btn.addEventListener('click', function(){
			var d = btn.dataset;
			document.getElementById('editBankId').value = d.id || '';
			document.getElementById('editTenNganHang').value = d.ten_ngan_hang || '';
					var editUrlEl = document.getElementById('editHinhAnhUrl');
					if(editUrlEl){ editUrlEl.value = d.hinh_anh || ''; }
			document.getElementById('editSoTaiKhoan').value = d.so_tai_khoan || '';
			document.getElementById('editChuTaiKhoan').value = d.chu_tai_khoan || '';
			document.getElementById('editChiNhanh').value = d.chi_nhanh || '';
			document.getElementById('editTrangThai').value = typeof d.trang_thai !== 'undefined' ? d.trang_thai : '1';
			document.getElementById('editGhiChu').value = d.ghi_chu || '';
		});
	});

	var btnEdit = document.getElementById('btnEditBankSubmit');
	var formEdit = document.getElementById('formEditBank');
	if(btnEdit && formEdit){
		btnEdit.addEventListener('click', function(){
			if(!formEdit.checkValidity()){ formEdit.reportValidity(); return; }
			var doSubmit = function(){
				btnEdit.disabled = true;
				var data = buildPayload(formEdit);
				axios.post("{{ route('admin.ngan-hang-nap-tien.update') }}", data, { headers: { 'Content-Type': 'application/json' } })
					.then(function(res){
						if(res.data && res.data.success){
							if(window.showToast) window.showToast('success', 'Thành công', res.data.message || 'Đã cập nhật ngân hàng');
							setTimeout(function(){ window.location.reload(); }, 2000);
						} else {
							if(window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể cập nhật ngân hàng');
						}
					})
					.catch(function(err){
						var msg = 'Không thể cập nhật ngân hàng';
						if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
						if (err.response && err.response.data && err.response.data.errors) {
							var firstKey = Object.keys(err.response.data.errors)[0];
							var firstMsg = err.response.data.errors[firstKey][0];
							msg = firstMsg || msg;
						}
						if(window.showToast) window.showToast('error', 'Lỗi', msg);
					})
					.finally(function(){ btnEdit.disabled = false; });
			};
			if (window.showConfirm) {
				window.showConfirm({
					title: 'Cập nhật ngân hàng',
					message: 'Bạn có chắc chắn muốn lưu thay đổi?',
					confirmText: 'Lưu',
					onConfirm: doSubmit
				});
			} else {
				if (window.confirm('Bạn có chắc chắn muốn lưu thay đổi?')) { doSubmit(); }
			}
		});
	}

	document.querySelectorAll('.btn-delete-bank').forEach(function(btn){
		btn.addEventListener('click', function(){
			var id = btn.getAttribute('data-id');
			if(!id) return;
			if (window.showConfirm) {
				window.showConfirm({
					title: 'Xoá ngân hàng',
					message: 'Bạn có chắc chắn muốn xoá ngân hàng này? Hành động không thể hoàn tác.',
					confirmText: 'Xoá',
					onConfirm: function(){
						axios.post("{{ route('admin.ngan-hang-nap-tien.destroy') }}", { id: id })
							.then(function(res){
								if(res.data && res.data.success){
									if(window.showToast) window.showToast('success', 'Đã xoá', res.data.message || 'Đã xoá ngân hàng');
									setTimeout(function(){ window.location.reload(); }, 1200);
								}else{
									if(window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể xoá');
								}
							})
							.catch(function(err){
								var msg = 'Không thể xoá ngân hàng';
								if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
								if (window.showToast) window.showToast('error', 'Lỗi', msg);
							});
					}
				});
			} else if (window.confirm('Bạn có chắc chắn muốn xoá ngân hàng này?')) {
				axios.post("{{ route('admin.ngan-hang-nap-tien.destroy') }}", { id: id })
					.then(function(){ window.location.reload(); })
					.catch(function(){});
			}
		});
	});
});
</script>
@endsection


