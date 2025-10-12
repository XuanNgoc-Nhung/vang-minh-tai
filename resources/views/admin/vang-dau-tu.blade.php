@extends('admin.layout.app')

@section('title', 'Quản lý vàng đầu tư')
@section('nav.vang-dau-tu_active', 'active')
@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Vàng đầu tư</span>
@endsection

@section('content')
  <div class="mb-3">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <strong class="text-success">Bộ lọc</strong>
      </div>
      <div class="card-body">
        <form class="row g-2 align-items-center" method="get" action="{{ route('admin.vang-dau-tu') }}">
          <div class="col-12 col-md-6 col-lg-4">
            <input type="text" name="q" value="{{ request('q', $keyword ?? '') }}" class="form-control form-control-sm" placeholder="Tìm theo mã vàng/tên vàng">
          </div>
          <div class="col-12 col-md-3 col-lg-2">
            <select name="trang_thai" class="form-select form-select-sm">
              <option value="">Tất cả trạng thái</option>
              <option value="1" @selected((string)request('trang_thai', $trangThai ?? '')==='1')>Đang giao dịch</option>
              <option value="0" @selected((string)request('trang_thai', $trangThai ?? '')==='0')>Ngừng giao dịch</option>
            </select>
          </div>
          <div class="col-12 col-md-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            @if(request('q') || request('trang_thai', '')!=='')
              <a href="{{ route('admin.vang-dau-tu') }}" class="btn btn-sm btn-outline-secondary">Xoá lọc</a>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <strong class="text-success">Danh sách vàng đầu tư</strong>
      <div class="d-flex align-items-center gap-2">
        <div class="text-muted small me-2">Tổng: {{ $vangDauTu->total() }}</div>
        <button type="button" class="btn btn-sm btn-success" id="btnOpenCreate"><i class="bi bi-plus-lg"></i> Thêm mới</button>
      </div>
    </div>
    <div class="card-body p-0">
      <style>
        .admin-gold-table th,
        .admin-gold-table td { min-width: 140px; }
        .admin-gold-table th:nth-child(1),
        .admin-gold-table td:nth-child(1) { min-width: 70px; text-align:center; }
        .admin-gold-table .action-col { position: sticky; right: 0; background: #fff; z-index: 2; min-width: 160px; }
        .admin-gold-table thead th.action-col { z-index: 3; }
        .admin-gold-table td.action-col { box-shadow: -4px 0 4px -4px rgba(0,0,0,.15); }
      </style>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0 admin-gold-table">
          <thead class="table-light">
            <tr>
              <th style="width:70px">STT</th>
              <th style="width:140px">Mã vàng</th>
              <th>Tên vàng</th>
              <th style="width:160px">Trạng thái</th>
              <th class="action-col text-center" style="width:160px">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($vangDauTu as $index => $item)
              <tr>
                <td class="text-muted">{{ $vangDauTu->firstItem() + $index }}</td>
                <td class="fw-semibold">{{ $item->ma_vang }}</td>
                <td>{{ $item->ten_vang }}</td>
                <td class="align-items-center">
                  <div class="d-flex align-items-center gap-2">
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-secondary"
                      title="Ẩn/Hiển thị nhanh"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      data-action="toggle-status"
                      data-id="{{ $item->id }}"
                      data-status="{{ $item->trang_thai }}"
                    >
                      @if($item->trang_thai)
                        <i class="bi bi-eye-slash"></i>
                      @else
                        <i class="bi bi-eye"></i>
                      @endif
                    </button>
                    @if($item->trang_thai)
                      <span class="badge text-bg-success mb-0">Đang giao dịch</span>
                    @else
                      <span class="badge text-bg-secondary mb-0">Ngừng giao dịch</span>
                    @endif
                  </div>
                </td>
                <td class="action-col text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" data-action="edit"
                      data-id="{{ $item->id }}"
                      data-code="{{ $item->ma_vang }}"
                      data-name="{{ $item->ten_vang }}"
                      data-status="{{ $item->trang_thai }}">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-action="delete" data-id="{{ $item->id }}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Không có bản ghi nào</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <div class="small text-muted">Hiển thị {{ $vangDauTu->firstItem() ?? 0 }}–{{ $vangDauTu->lastItem() ?? 0 }} / {{ $vangDauTu->total() }} mục</div>
      <div>
        {{ $vangDauTu->withQueryString()->links('vendor.pagination.admin') }}
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <div class="modal fade" id="vangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vangModalTitle">Thêm vàng đầu tư</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="vangForm">
            <input type="hidden" name="id" id="gold_id">
            <div class="mb-3">
              <label class="form-label">Mã vàng</label>
              <input type="text" name="ma_vang" id="gold_code" class="form-control" placeholder="Nhập mã vàng" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tên vàng</label>
              <input type="text" name="ten_vang" id="gold_name" class="form-control" placeholder="Nhập tên vàng" required>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="gold_status">
              <label class="form-check-label" for="gold_status">Đang giao dịch</label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="btnSaveGold"><i class="bi bi-check2-circle"></i> Lưu</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  (function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) { new bootstrap.Tooltip(tooltipTriggerEl); });

    const modalEl = document.getElementById('vangModal');
    const modal = new bootstrap.Modal(modalEl);
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnSave = document.getElementById('btnSaveGold');
    const form = document.getElementById('vangForm');

    function openCreate() {
      document.getElementById('vangModalTitle').textContent = 'Thêm vàng đầu tư';
      form.reset();
      document.getElementById('gold_id').value = '';
      document.getElementById('gold_status').checked = true;
      modal.show();
    }

    function openEdit(btn) {
      document.getElementById('vangModalTitle').textContent = 'Sửa vàng đầu tư';
      document.getElementById('gold_id').value = btn.getAttribute('data-id');
      document.getElementById('gold_code').value = btn.getAttribute('data-code');
      document.getElementById('gold_name').value = btn.getAttribute('data-name');
      document.getElementById('gold_status').checked = btn.getAttribute('data-status') === '1';
      modal.show();
    }

    function save() {
      const id = (document.getElementById('gold_id').value || '').trim();
      const payload = new FormData();
      if (id) payload.append('id', id);
      payload.append('ma_vang', (document.getElementById('gold_code').value || '').trim());
      payload.append('ten_vang', (document.getElementById('gold_name').value || '').trim());
      payload.append('trang_thai', document.getElementById('gold_status').checked ? '1' : '0');

      const url = id ? '{{ route('admin.vang-dau-tu.update') }}' : '{{ route('admin.vang-dau-tu.store') }}';
      axios.post(url, payload)
        .then(function(res) {
          const data = res.data || {};
          if (data.success) {
            if (typeof queueToast === 'function') {
              queueToast('success', data.message || 'Đã lưu');
            } else if (typeof showToast === 'function') {
              showToast('success', data.message || 'Đã lưu');
            }
            modal.hide();
            window.location.reload();
          } else {
            if (typeof showToast === 'function') {
              showToast('error', data.message || 'Không thể lưu');
            }
          }
        })
        .catch(function() {
          if (typeof showToast === 'function') {
            showToast('error', 'Không thể lưu');
          }
        });
    }

    function confirmDelete(id) {
      if (typeof showConfirm === 'function') {
        showConfirm({
          title: 'Xác nhận xoá',
          message: 'Bạn có chắc chắn muốn xoá mục này?',
          confirmText: 'Xoá',
          onConfirm: function() { doDelete(id); }
        });
      } else if (window.confirm('Bạn có chắc chắn muốn xoá mục này?')) {
        doDelete(id);
      }
    }

    function doDelete(id) {
      const payload = new FormData();
      payload.append('id', id);
      axios.post('{{ route('admin.vang-dau-tu.destroy') }}', payload)
        .then(function(res) {
          const data = res.data || {};
          if (data.success) {
            if (typeof queueToast === 'function') {
              queueToast('success', data.message || 'Đã xoá');
            } else if (typeof showToast === 'function') {
              showToast('success', data.message || 'Đã xoá');
            }
            window.location.reload();
          } else {
            if (typeof showToast === 'function') {
              showToast('error', data.message || 'Không thể xoá');
            }
          }
        })
        .catch(function() {
          if (typeof showToast === 'function') {
            showToast('error', 'Không thể xoá');
          }
        });
    }

    function toggleStatus(btn) {
      const id = btn.getAttribute('data-id');
      const currentStatus = String(btn.getAttribute('data-status')) === '1';
      const newStatus = currentStatus ? '0' : '1';

      const payload = new FormData();
      payload.append('id', id);
      payload.append('trang_thai', newStatus);

      axios.post('{{ route('admin.vang-dau-tu.update') }}', payload)
        .then(function(res) {
          const data = res.data || {};
          if (data.success) {
            if (typeof queueToast === 'function') {
              queueToast('success', 'Đã cập nhật trạng thái');
            } else if (typeof showToast === 'function') {
              showToast('success', 'Đã cập nhật trạng thái');
            }
            window.location.reload();
          } else {
            if (typeof showToast === 'function') {
              showToast('error', data.message || 'Không thể cập nhật trạng thái');
            }
          }
        })
        .catch(function() {
          if (typeof showToast === 'function') {
            showToast('error', 'Không thể cập nhật trạng thái');
          }
        });
    }

    btnOpenCreate.addEventListener('click', openCreate);
    btnSave.addEventListener('click', save);

    document.addEventListener('click', function(e) {
      const target = e.target.closest('[data-action]');
      if (!target) return;
      const action = target.getAttribute('data-action');
      if (action === 'edit') openEdit(target);
      if (action === 'delete') confirmDelete(target.getAttribute('data-id'));
      if (action === 'toggle-status') toggleStatus(target);
    });
  })();
</script>
@endsection


