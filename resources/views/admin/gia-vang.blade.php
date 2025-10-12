@extends('admin.layout.app')

@section('title', 'Quản lý giá vàng')
@section('nav.gia-vang_active', 'active')
@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Giá vàng</span>
@endsection

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <strong class="text-success">Danh sách giá vàng</strong>
      <div class="d-flex align-items-center gap-2">
        <div class="text-muted small me-2">Tổng: {{ $giaVang->total() }}</div>
        <button type="button" class="btn btn-sm btn-success" id="btnOpenCreate"><i class="bi bi-plus-lg"></i> Thêm mới</button>
      </div>
    </div>
    <div class="card-body p-0">
      <style>
        .form-label{
            margin-bottom: 0px;
        }
        .admin-gold-price-table th,
        .admin-gold-price-table td { min-width: 140px; }
        .admin-gold-price-table th:nth-child(1),
        .admin-gold-price-table td:nth-child(1) { min-width: 70px; text-align:center; }
        .admin-gold-price-table .action-col { position: sticky; right: 0; background: #fff; z-index: 2; min-width: 160px; }
        .admin-gold-price-table thead th.action-col { z-index: 3; }
        .admin-gold-price-table td.action-col { box-shadow: -4px 0 4px -4px rgba(0,0,0,.15); }

        /* Form spacing and input borders (scoped to this page) */
        #giaVangForm .form-control,
        #giaVangForm .form-select {
          margin-bottom: 16px;
          border: 1px solid #6c757d;
          border-radius: .375rem;
          box-shadow: none;
        }

        #giaVangForm .form-control:focus,
        #giaVangForm .form-select:focus {
          border-color: #198754;
          box-shadow: 0 0 0 .2rem rgba(25,135,84,.25);
        }

        #giaVangForm .form-check { margin-top: 4px; }
      </style>
      <div class="p-3 border-bottom bg-light">
        <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.gia-vang') }}">
          <div class="col-12 col-md-4">
            <label class="form-label">Loại vàng</label>
            <select name="id_vang" class="form-select">
              <option value="">-- Tất cả --</option>
              @foreach($dsVang as $vang)
                <option value="{{ $vang->id }}" {{ (isset($idVang) && (string)$idVang === (string)$vang->id) ? 'selected' : '' }}>
                  {{ $vang->ten_vang }} ({{ $vang->ma_vang }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Từ ngày</label>
            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-control">
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label">Đến ngày</label>
            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-control">
          </div>
          <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-funnel"></i> Lọc</button>
            <a href="{{ route('admin.gia-vang') }}" class="btn btn-outline-secondary" title="Xoá bộ lọc"><i class="bi bi-x-lg"></i></a>
          </div>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0 admin-gold-price-table">
          <thead class="table-light">
            <tr>
              <th style="width:70px">STT</th>
              <th>Loại vàng</th>
              <th style="width:140px">Giá mua</th>
              <th style="width:140px">Giá bán</th>
              <th style="width:180px">Thời gian</th>
              <th style="width:160px">Trạng thái</th>
              <th class="action-col text-center" style="width:160px">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($giaVang as $index => $item)
              <tr>
                <td class="text-muted">{{ $giaVang->firstItem() + $index }}</td>
                <td>
                  @php $vang = $mapVang->get($item->id_vang); @endphp
                  @if($vang)
                    <div class="fw-semibold">{{ $vang->ten_vang }}</div>
                    <div class="text-muted small">Mã: {{ $vang->ma_vang }}</div>
                  @else
                    <span class="text-muted">N/A</span>
                  @endif
                </td>
                <td>{{ number_format($item->gia_mua) }}</td>
                <td>{{ number_format($item->gia_ban) }}</td>
                <td>
                  @php
                    try { $dt = \Carbon\Carbon::parse($item->thoi_gian); }
                    catch (Exception $e) { $dt = null; }
                  @endphp
                  {{ $dt ? $dt->format('d/m/Y') : e($item->thoi_gian) }}
                </td>
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
                      <span class="badge text-bg-success mb-0">Hiển thị</span>
                    @else
                      <span class="badge text-bg-secondary mb-0">Ẩn</span>
                    @endif
                  </div>
                </td>
                <td class="action-col text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" data-action="edit"
                      data-id="{{ $item->id }}"
                      data-id_vang="{{ $item->id_vang }}"
                      data-gia_mua="{{ $item->gia_mua }}"
                      data-gia_ban="{{ $item->gia_ban }}"
                      data-thoi_gian="{{ $item->thoi_gian }}"
                      data-ghi_chu="{{ $item->ghi_chu }}"
                      data-trang_thai="{{ $item->trang_thai }}">
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
                <td colspan="7" class="text-center text-muted py-4">Không có bản ghi nào</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <div class="small text-muted">Hiển thị {{ $giaVang->firstItem() ?? 0 }}–{{ $giaVang->lastItem() ?? 0 }} / {{ $giaVang->total() }} mục</div>
      <div>
        {{ $giaVang->links('vendor.pagination.admin') }}
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <div class="modal fade" id="giaVangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="giaVangModalTitle">Thêm giá vàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="giaVangForm">
            <input type="hidden" name="id" id="price_id">
            <div class="mb-3">
              <label class="form-label">Loại vàng</label>
              <select name="id_vang" id="price_gold" class="form-select" required>
                <option value="">-- Chọn loại vàng --</option>
                @foreach($dsVang as $vang)
                  <option value="{{ $vang->id }}">{{ $vang->ten_vang }} ({{ $vang->ma_vang }})</option>
                @endforeach
              </select>
            </div>
            <div class="row g-2">
              <div class="col-12 col-md-4">
                <label class="form-label">Giá mua</label>
                <input type="number" name="gia_mua" id="price_buy" class="form-control" placeholder="Nhập giá mua" min="0" step="1" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Giá bán</label>
                <input type="number" name="gia_ban" id="price_sell" class="form-control" placeholder="Nhập giá bán" min="0" step="1" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Thời gian</label>
                <input type="date" name="thoi_gian" id="price_time" class="form-control" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Ghi chú</label>
              <input type="text" name="ghi_chu" id="price_note" class="form-control" placeholder="Ghi chú (tuỳ chọn)">
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="price_status">
              <label class="form-check-label" for="price_status">Hiển thị</label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="btnSavePrice"><i class="bi bi-check2-circle"></i> Lưu</button>
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

    const modalEl = document.getElementById('giaVangModal');
    const modal = new bootstrap.Modal(modalEl);
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnSave = document.getElementById('btnSavePrice');
    const form = document.getElementById('giaVangForm');

    function openCreate() {
      document.getElementById('giaVangModalTitle').textContent = 'Thêm giá vàng';
      form.reset();
      document.getElementById('price_id').value = '';
      document.getElementById('price_status').checked = true;
      // Enable editable fields on create
      document.getElementById('price_gold').removeAttribute('disabled');
      document.getElementById('price_time').removeAttribute('disabled');
      // Set current local date
      try {
        const now = new Date();
        document.getElementById('price_time').value = toLocalDateValue(now.toISOString());
      } catch (e) {
        document.getElementById('price_time').value = '';
      }
      modal.show();
    }

    function toLocalDateValue(value) {
      if (!value) return '';
      const d = new Date(value);
      if (isNaN(d.getTime())) return '';
      const pad = (n) => String(n).padStart(2, '0');
      const yyyy = d.getFullYear();
      const mm = pad(d.getMonth() + 1);
      const dd = pad(d.getDate());
      return `${yyyy}-${mm}-${dd}`;
    }

    function openEdit(btn) {
      document.getElementById('giaVangModalTitle').textContent = 'Sửa giá vàng';
      document.getElementById('price_id').value = btn.getAttribute('data-id');
      document.getElementById('price_gold').value = btn.getAttribute('data-id_vang') || '';
      document.getElementById('price_buy').value = btn.getAttribute('data-gia_mua') || '';
      document.getElementById('price_sell').value = btn.getAttribute('data-gia_ban') || '';
      document.getElementById('price_time').value = toLocalDateValue(btn.getAttribute('data-thoi_gian'));
      document.getElementById('price_note').value = btn.getAttribute('data-ghi_chu') || '';
      document.getElementById('price_status').checked = (btn.getAttribute('data-trang_thai') === '1');
      // Disable changing gold type and time when editing
      document.getElementById('price_gold').setAttribute('disabled', 'disabled');
      document.getElementById('price_time').setAttribute('disabled', 'disabled');
      modal.show();
    }

    function save() {
      const id = (document.getElementById('price_id').value || '').trim();
      const payload = new FormData();
      if (id) payload.append('id', id);
      // Only send id_vang on create
      if (!id) {
        payload.append('id_vang', (document.getElementById('price_gold').value || '').trim());
      }
      payload.append('gia_mua', (document.getElementById('price_buy').value || '').trim());
      payload.append('gia_ban', (document.getElementById('price_sell').value || '').trim());
      // Only send thoi_gian on create
      if (!id) {
        payload.append('thoi_gian', (document.getElementById('price_time').value || '').trim());
      }
      const note = (document.getElementById('price_note').value || '').trim();
      if (note) payload.append('ghi_chu', note);
      payload.append('trang_thai', document.getElementById('price_status').checked ? '1' : '0');

      const url = id ? '{{ route('admin.gia-vang.update') }}' : '{{ route('admin.gia-vang.store') }}';
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
      axios.post('{{ route('admin.gia-vang.destroy') }}', payload)
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

      axios.post('{{ route('admin.gia-vang.update') }}', payload)
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


