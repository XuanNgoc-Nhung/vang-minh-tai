@extends('admin.layout.app')

@section('title', 'Gói tiết kiệm')
@section('nav.san-pham-tiet-kiem_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Gói tiết kiệm</span>
@endsection
@section('content')
<div class="mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <strong class="text-success">Bộ lọc</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.san-pham-tiet-kiem') }}" class="row g-2 align-items-center" role="search">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nhập tên Gói">
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                    @if(request('q'))
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.san-pham-tiet-kiem') }}">Xoá lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    </div>
<div class="card border-0 shadow-sm">
	<div class="card-header bg-white d-flex justify-content-between align-items-center">
		<strong class="text-success">Danh sách Gói tiết kiệm</strong>
		<div class="d-flex align-items-center gap-2">
			<div class="text-muted small me-2">Tổng: {{ $sanPhamDauTu->total() }}</div>
			<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateProduct">
				<i class="bi bi-plus-lg"></i> Thêm mới
			</button>
		</div>
	</div>
    <div class="card-body p-0">
        <style>
            .admin-products-table th,
            .admin-products-table td { min-width: 160px; }
            .admin-products-table th:nth-child(1),
            .admin-products-table td:nth-child(1) { min-width: 70px; text-align:center; }
            .admin-products-table th:nth-child(2),
            .admin-products-table td:nth-child(2) { min-width: 200px; }
            .admin-products-table .image-cell { width: 140px; }
            .admin-products-table .image-cell .thumb { width:120px; height:80px; object-fit:cover; }
            /* Sticky action column on the right */
            .admin-products-table .action-col {
                position: sticky;
                right: 0;
                background: #ffffff;
                z-index: 2;
                min-width: 160px;
            }
            .admin-products-table thead th.action-col {
                z-index: 3; /* above td */
            }
            .admin-products-table td.action-col {
                box-shadow: -4px 0 4px -4px rgba(0,0,0,.15);
            }
        </style>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 admin-products-table">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px">STT</th>
                        <th>Tên</th>
                        <th class="image-cell">Hình ảnh</th>
                        <th>Khoảng vốn</th>
                        <th>Thời gian 1 chu kỳ</th>
                        <th>Lãi suất</th>
                        <th>Nhãn dán</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th class="action-col text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sanPhamDauTu as $index => $sp)
                    <tr>
                        <td class="text-muted">{{ $sanPhamDauTu->firstItem() + $index }}</td>
                        <td>{{ $sp->ten ?? '—' }}</td>
                        <td class="image-cell">
                            @if(!empty($sp->hinh_anh))
                                <img src="{{ asset($sp->hinh_anh) }}" alt="Hình ảnh" class="thumb border rounded">
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $min = $sp->von_toi_thieu;
                                $max = $sp->von_toi_da;
                            @endphp
                            @if($min !== null && $max !== null)
                                {{ number_format((float)$min, 2) }} - {{ number_format((float)$max, 2) }}
                            @elseif($min !== null)
                                ≥ {{ number_format((float)$min, 2) }}
                            @elseif($max !== null)
                                ≤ {{ number_format((float)$max, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $sp->thoi_gian_mot_chu_ky ? TimeHelper::formatTimeFromMonths($sp->thoi_gian_mot_chu_ky) : '—' }}</td>
                        <td>{{ $sp->lai_suat !== null ? rtrim(rtrim(number_format((float)$sp->lai_suat, 2, '.', ''), '0'), '.') . '%' : '—' }}</td>
                        <td>{{ $sp->nhan_dan ?? '—' }}</td>
                        <td>
                            @if(!empty($sp->mo_ta))
                                <span class="d-inline-block text-truncate" style="max-width: 260px;" title="{{ $sp->mo_ta }}">{{ $sp->mo_ta }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="{{ (int)($sp->trang_thai ?? 0) === 1 ? 'btn btn-sm btn-outline-success px-2 py-0' : 'btn btn-sm btn-outline-secondary px-2 py-0' }}" title="Đổi trạng thái nhanh"
                                    onclick="toggleProductStatus({{ $sp->id }}, {{ (int)($sp->trang_thai ?? 0) }})">
                                    <i class="bi bi-power"></i>
                                </button>
                                <span class="badge {{ (int)($sp->trang_thai ?? 0) === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ (int)($sp->trang_thai ?? 0) === 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>
                        </td>
                        <td class="action-col text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary btn-edit-product"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditProduct"
                                    data-id="{{ $sp->id }}"
                                    data-ten="{{ $sp->ten }}"
                                    data-hinh_anh="{{ $sp->hinh_anh }}"
                                    data-von_toi_thieu="{{ $sp->von_toi_thieu }}"
                                    data-von_toi_da="{{ $sp->von_toi_da }}"
                                    data-thoi_gian_mot_chu_ky="{{ $sp->thoi_gian_mot_chu_ky }}"
                                    data-lai_suat="{{ $sp->lai_suat }}"
                                    data-nhan_dan="{{ $sp->nhan_dan }}"
                                    data-trang_thai="{{ $sp->trang_thai }}"
                                    data-mo_ta='{{ $sp->mo_ta }}'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-delete-product" data-id="{{ $sp->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Không có Gói tiết kiệm nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <div class="small text-muted">Hiển thị {{ $sanPhamDauTu->firstItem() ?? 0 }}–{{ $sanPhamDauTu->lastItem() ?? 0 }} / {{ $sanPhamDauTu->total() }} Gói</div>
        <div>
            {{ $sanPhamDauTu->withQueryString()->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>
<!-- Modal: Create Product -->
<div class="modal fade" id="modalCreateProduct" tabindex="-1" aria-labelledby="modalCreateProductLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalCreateProductLabel">Thêm Gói tiết kiệm</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formCreateProduct" enctype="multipart/form-data">
					@csrf
					<div class="mb-3">
						<style>
							.image-dropzone { background-color:#fafafa; transition: border-color .2s, background-color .2s; }
							.image-dropzone.dragover { background-color:#f0f8ff; border-color:#0d6efd !important; }
						</style>
					<div id="imageDropzone" class="image-dropzone border border-2 rounded d-flex align-items-center justify-content-center flex-column text-muted mx-auto" style="height: 220px; cursor: pointer; width: 100%; max-width: 520px;">
							<img id="createImagePreview" src="" alt="Xem trước" class="border rounded" style="display:none; max-height: 100%; max-width: 100%; object-fit: contain;">
							<div id="imageDropzoneText" class="text-center small">
								<div class="mb-1"><i class="bi bi-image" style="font-size: 1.5rem;"></i></div>
								<div><strong>Kéo & thả</strong> ảnh vào đây hoặc <u>bấm để chọn</u></div>
								<div class="text-muted">Hỗ trợ PNG, JPG, JPEG, WEBP</div>
							</div>
						<input type="file" name="hinh_anh" id="createImage" accept="image/*" class="d-none" required>
						</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Tên</label>
						<input type="text" name="ten" id="createTen" class="form-control" placeholder="Nhập tên Gói" required>
						</div>

						
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Vốn tối thiểu</label>
						<input type="number" step="0.01" name="von_toi_thieu" class="form-control" placeholder="0" required>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Vốn tối đa</label>
						<input type="number" step="0.01" name="von_toi_da" class="form-control" placeholder="0" required>
						</div>

						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Thời gian 1 chu kỳ</label>
							<select name="thoi_gian_mot_chu_ky" class="form-select" required>
								<option value="">Chọn thời gian</option>
								<option value="1">1 tháng</option>
								<option value="2">2 tháng</option>
								<option value="3">3 tháng</option>
								<option value="6">6 tháng</option>
								<option value="12">12 tháng</option>
								<option value="24">24 tháng</option>
							</select>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Lãi suất (%)</label>
						<input type="number" step="0.01" name="lai_suat" class="form-control" placeholder="ví dụ: 12.5" required>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Nhãn dán</label>
							<input type="text" name="nhan_dan" class="form-control" placeholder="Hot, New,...">
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<label class="form-label">Trạng thái</label>
							<select name="trang_thai" id="createTrangThai" class="form-select" required>
								<option value="1" selected>Hoạt động</option>
								<option value="0">Không hoạt động</option>
							</select>
						</div>
						<div class="col-12">
							<label class="form-label">Mô tả</label>
						<textarea name="mo_ta" class="form-control" rows="3" placeholder="Mô tả ngắn về Gói" required></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" id="btnCreateProductSubmit">Lưu</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal: Edit Product -->
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-labelledby="modalEditProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProductLabel">Sửa Gói tiết kiệm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditProduct" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <div id="editImageDropzone" class="image-dropzone border border-2 rounded d-flex align-items-center justify-content-center flex-column text-muted mx-auto" style="height: 140px; cursor: pointer; width: 100%; max-width: 360px;">
                            <img id="editImagePreview" src="" alt="Xem trước" class="border rounded" style="display:none; max-height: 100%; max-width: 100%; object-fit: contain;">
                            <div id="editImageDropzoneText" class="text-center small">
                                <div class="mb-1"><i class="bi bi-image" style="font-size: 1.5rem;"></i></div>
                                <div><strong>Kéo & thả</strong> ảnh vào đây hoặc <u>bấm để chọn</u></div>
								<div class="text-muted">Hỗ trợ PNG, JPG, JPEG, WEBP</div>
                            </div>
                            <input type="file" name="hinh_anh" id="editImage" accept="image/*" class="d-none">
                        </div>
                        <div class="form-text text-center">Để trống nếu không thay đổi.</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Tên</label>
                            <input type="text" name="ten" id="editTen" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Vốn tối thiểu</label>
                            <input type="number" step="0.01" name="von_toi_thieu" id="editVonToiThieu" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Vốn tối đa</label>
                            <input type="number" step="0.01" name="von_toi_da" id="editVonToiDa" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Thời gian 1 chu kỳ</label>
                            <select name="thoi_gian_mot_chu_ky" id="editThoiGianMotChuKy" class="form-select" required>
                                <option value="">Chọn thời gian</option>
                                <option value="1">1 tháng</option>
                                <option value="2">2 tháng</option>
                                <option value="3">3 tháng</option>
                                <option value="6">6 tháng</option>
                                <option value="12">12 tháng</option>
                                <option value="24">24 tháng</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Lãi suất (%)</label>
                            <input type="number" step="0.01" name="lai_suat" id="editLaiSuat" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Nhãn dán</label>
                            <input type="text" name="nhan_dan" id="editNhanDan" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" id="editTrangThai" class="form-select" required>
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" id="editMoTa" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnEditProductSubmit">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.toggleProductStatus = async function(id, current){
        try{
            var url = "{{ route('admin.san-pham-tiet-kiem.update') }}";
            var next = Number(current) === 1 ? 0 : 1;
            var fd = new FormData();
            fd.append('id', String(id));
            fd.append('trang_thai', String(next));
            var res = await axios.post(url, fd, { headers: { 'Content-Type': 'multipart/form-data' } });
            var ok = !!(res && res.data && res.data.success);
            var msg = (res && res.data && res.data.message) || (ok ? 'Đã cập nhật trạng thái' : 'Cập nhật thất bại');
            if(window.showToast) window.showToast(ok ? 'success' : 'error', ok ? 'Thành công' : 'Lỗi', msg);
            if(ok){ setTimeout(function(){ window.location.reload(); }, 400); }
        } catch(err){
            var msg = (err && err.response && err.response.data && err.response.data.message) || 'Có lỗi xảy ra. Vui lòng thử lại';
            if(window.showToast) window.showToast('error', 'Lỗi', msg);
        }
    }
    // No client-side slug handling; slug is generated on server
    var fileInput = document.getElementById('createImage');
    var previewImg = document.getElementById('createImagePreview');
    var dropzone = document.getElementById('imageDropzone');
    var dropzoneText = document.getElementById('imageDropzoneText');
    var createPreviewUrl = null; // track blob URL to revoke

    function showPreview(file) {
        if (!file) return;
        if (createPreviewUrl) {
            try { URL.revokeObjectURL(createPreviewUrl); } catch (e) {}
        }
        createPreviewUrl = URL.createObjectURL(file);
        previewImg.src = createPreviewUrl;
        previewImg.style.display = '';
        if (dropzoneText) dropzoneText.style.display = 'none';
    }

    function resetPreview() {
        if (createPreviewUrl) {
            try { URL.revokeObjectURL(createPreviewUrl); } catch (e) {}
            createPreviewUrl = null;
        }
        previewImg.src = '';
        previewImg.style.display = 'none';
        if (dropzoneText) dropzoneText.style.display = '';
        if (fileInput) fileInput.value = '';
    }

    if (fileInput && previewImg) {
        fileInput.addEventListener('change', function (e) {
            var file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
            if (file) {
                showPreview(file);
            } else {
                resetPreview();
            }
        });
    }

    if (dropzone) {
        ['dragenter','dragover'].forEach(function(evt) {
            dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.add('dragover'); });
        });
        ['dragleave','drop'].forEach(function(evt) {
            dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.remove('dragover'); });
        });
        dropzone.addEventListener('drop', function(e){
            var file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0] ? e.dataTransfer.files[0] : null;
            if (file && file.type && file.type.startsWith('image/')) {
                if (fileInput) {
                    // set file to input for form submission
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                }
                showPreview(file);
            }
        });
        dropzone.addEventListener('click', function(){ if (fileInput) { fileInput.value = ''; fileInput.click(); } });
    }

    // Reset preview when modal closes
    var modal = document.getElementById('modalCreateProduct');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function(){ resetPreview(); });
    }

    // Handle create submit via axios
    var submitBtn = document.getElementById('btnCreateProductSubmit');
    var formEl = document.getElementById('formCreateProduct');
    function toFormData(form) {
        var fd = new FormData();
        var elements = form.querySelectorAll('input, textarea, select');
        elements.forEach(function(el) {
            if (!el.name) return;
            if (el.type === 'file') {
                if (el.files && el.files[0]) fd.append(el.name, el.files[0]);
            } else if (el.type === 'checkbox') {
                if (el.checked) fd.append(el.name, el.value || '1');
            } else {
                fd.append(el.name, el.value);
            }
        });
        return fd;
    }
    if (submitBtn && formEl) {
        function clientValidate() {
            // HTML5 required flags will highlight missing fields
            if (!formEl.checkValidity()) {
                formEl.reportValidity();
                return false;
            }
            return true;
        }
        submitBtn.addEventListener('click', function() {
            if (!clientValidate()) return;
            submitBtn.disabled = true;
            var fd = toFormData(formEl);
            axios.post("{{ route('admin.san-pham-tiet-kiem.store') }}", fd, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(function(res){
                    if (res.data && res.data.success) {
                        if (window.showToast) window.showToast('success', 'Thành công', res.data.message || 'Đã tạo Gói');
                        // Reload to show new item after 1s
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    } else {
                        if (window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể tạo Gói');
                    }
                })
                .catch(function(err){
                    var msg = 'Không thể tạo Gói';
                    if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                    if (err.response && err.response.data && err.response.data.errors) {
                        // Try to show the first validation error
                        var firstKey = Object.keys(err.response.data.errors)[0];
                        var firstMsg = err.response.data.errors[firstKey][0];
                        msg = firstMsg || msg;
                    }
                    if (window.showToast) window.showToast('error', 'Lỗi', msg);
                })
                .finally(function(){ submitBtn.disabled = false; });
        });
    }

    // Edit: populate modal
    var editModalEl = document.getElementById('modalEditProduct');
    var editId = document.getElementById('editId');
    var editTen = document.getElementById('editTen');
    var editImage = document.getElementById('editImage');
    var editImagePreview = document.getElementById('editImagePreview');
    var editVonToiThieu = document.getElementById('editVonToiThieu');
    var editVonToiDa = document.getElementById('editVonToiDa');
    
    var editThoiGianMotChuKy = document.getElementById('editThoiGianMotChuKy');
    var editLaiSuat = document.getElementById('editLaiSuat');
    var editNhanDan = document.getElementById('editNhanDan');
    var editTrangThai = document.getElementById('editTrangThai');
    var editMoTa = document.getElementById('editMoTa');
    var editSubmitBtn = document.getElementById('btnEditProductSubmit');
    var editFormEl = document.getElementById('formEditProduct');
    var editPreviewBlobUrl = null; // track blob URL to revoke for edit

    function setEditPreview(url) {
        if (url) {
			// Accept absolute URLs (http/https), root-relative (/), and blob:/data: URLs without prefixing
			editImagePreview.src = /^(https?:|\/|blob:|data:)/.test(url)
				? url
				: (window.location.origin.replace(/\/$/, '') + '/' + url.replace(/^\//, ''));
            editImagePreview.style.display = '';
            if (editDropzoneText) editDropzoneText.style.display = 'none';
        } else {
            editImagePreview.src = '';
            editImagePreview.style.display = 'none';
            if (editDropzoneText) editDropzoneText.style.display = '';
        }
    }

    function setEditPreviewFromFile(file) {
        if (!file) return;
        if (editPreviewBlobUrl) {
            try { URL.revokeObjectURL(editPreviewBlobUrl); } catch (e) {}
        }
        editPreviewBlobUrl = URL.createObjectURL(file);
        setEditPreview(editPreviewBlobUrl);
    }

    document.querySelectorAll('.btn-edit-product').forEach(function(btn){
        btn.addEventListener('click', function(){
            var d = btn.dataset;
            if (editId) editId.value = d.id || '';
            if (editTen) editTen.value = d.ten || '';
            if (editVonToiThieu) editVonToiThieu.value = d.von_toi_thieu || '';
            if (editVonToiDa) editVonToiDa.value = d.von_toi_da || '';
            
            if (editThoiGianMotChuKy) {
                // Set the selected option for time period dropdown
                var timeValue = d.thoi_gian_mot_chu_ky || '';
                editThoiGianMotChuKy.value = timeValue;
            }
            if (editLaiSuat) editLaiSuat.value = d.lai_suat || '';
            if (editNhanDan) editNhanDan.value = d.nhan_dan || '';
            if (editTrangThai) editTrangThai.value = typeof d.trang_thai !== 'undefined' ? d.trang_thai : '1';
            if (editMoTa) editMoTa.value = d.mo_ta || '';
            if (editImage) editImage.value = '';
            if (editPreviewBlobUrl) { try { URL.revokeObjectURL(editPreviewBlobUrl); } catch (e) {} editPreviewBlobUrl = null; }
            setEditPreview(d.hinh_anh || '');
        });
    });

    if (editImage) {
        editImage.addEventListener('change', function(e){
            var file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
            if (file) {
                setEditPreviewFromFile(file);
            } else {
                // no file selected, keep current preview (existing image)
            }
        });
    }

    // Edit dropzone events (drag & drop + click to select)
    var editDropzone = document.getElementById('editImageDropzone');
    var editDropzoneText = document.getElementById('editImageDropzoneText');
    function toggleEditDropzoneText(show) {
        if (!editDropzoneText) return;
        editDropzoneText.style.display = show ? '' : 'none';
    }
    if (editDropzone) {
        ['dragenter','dragover'].forEach(function(evt) {
            editDropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); editDropzone.classList.add('dragover'); });
        });
        ['dragleave','drop'].forEach(function(evt) {
            editDropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); editDropzone.classList.remove('dragover'); });
        });
        editDropzone.addEventListener('drop', function(e){
            var file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0] ? e.dataTransfer.files[0] : null;
            if (file && file.type && file.type.startsWith('image/')) {
                if (editImage) {
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    editImage.files = dt.files;
                }
                setEditPreviewFromFile(file);
                toggleEditDropzoneText(false);
            }
        });
        editDropzone.addEventListener('click', function(){ if (editImage) { editImage.value = ''; editImage.click(); } });
    }

    // Reset edit preview when edit modal closes
    if (editModalEl) {
        editModalEl.addEventListener('hidden.bs.modal', function(){
            if (editPreviewBlobUrl) {
                try { URL.revokeObjectURL(editPreviewBlobUrl); } catch (e) {}
                editPreviewBlobUrl = null;
            }
            if (editImage) editImage.value = '';
        });
    }

    function toFormDataFrom(form) {
        var fd = new FormData();
        var elements = form.querySelectorAll('input, textarea, select');
        elements.forEach(function(el) {
            if (!el.name) return;
            if (el.type === 'file') {
                if (el.files && el.files[0]) fd.append(el.name, el.files[0]);
            } else if (el.type === 'checkbox') {
                if (el.checked) fd.append(el.name, el.value || '1');
            } else {
                fd.append(el.name, el.value);
            }
        });
        return fd;
    }

    if (editSubmitBtn && editFormEl) {
        editSubmitBtn.addEventListener('click', function(){
            if (!editFormEl.checkValidity()) { editFormEl.reportValidity(); return; }
            editSubmitBtn.disabled = true;
            var fd = toFormDataFrom(editFormEl);
            axios.post("{{ route('admin.san-pham-tiet-kiem.update') }}", fd, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(function(res){
                    if (res.data && res.data.success) {
                        if (window.showToast) window.showToast('success', 'Thành công', res.data.message || 'Đã cập nhật Gói');
                        window.location.reload();
                    } else {
                        if (window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể cập nhật Gói');
                    }
                })
                .catch(function(err){
                    var msg = 'Không thể cập nhật Gói';
                    if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                    if (err.response && err.response.data && err.response.data.errors) {
                        var firstKey = Object.keys(err.response.data.errors)[0];
                        var firstMsg = err.response.data.errors[firstKey][0];
                        msg = firstMsg || msg;
                    }
                    if (window.showToast) window.showToast('error', 'Lỗi', msg);
                })
                .finally(function(){ editSubmitBtn.disabled = false; });
        });
    }

    // Delete
    document.querySelectorAll('.btn-delete-product').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = btn.getAttribute('data-id');
            if (!id) return;
            if (window.showConfirm) {
                window.showConfirm({
                    title: 'Xoá Gói',
                    message: 'Bạn có chắc chắn muốn xoá Gói này? Hành động không thể hoàn tác.',
                    confirmText: 'Xoá',
                    onConfirm: function(){
                        axios.post("{{ route('admin.san-pham-tiet-kiem.destroy') }}", { id: id })
                            .then(function(res){
                                if (res.data && res.data.success) {
                                    if (window.showToast) window.showToast('success', 'Đã xoá', res.data.message || 'Đã xoá Gói');
                                    setTimeout(function(){ window.location.reload(); }, 2000);
                                } else {
                                    if (window.showToast) window.showToast('error', 'Lỗi', (res.data && res.data.message) || 'Không thể xoá Gói');
                                }
                            })
                            .catch(function(err){
                                var msg = 'Không thể xoá Gói';
                                if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                                if (window.showToast) window.showToast('error', 'Lỗi', msg);
                            });
                    }
                });
            } else if (window.confirm('Bạn có chắc chắn muốn xoá Gói này?')) {
                axios.post("{{ route('admin.san-pham-tiet-kiem.destroy') }}", { id: id })
                    .then(function(){ window.location.reload(); })
                    .catch(function(){});
            }
        });
    });
});
</script>
@endsection