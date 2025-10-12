@extends('admin.layout.app')

@section('title', 'Quản lý thông báo')
@section('nav.thong-bao_active', 'active')
@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Thông báo</span>
@endsection

@section('content')
  <div class="mb-3">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <strong class="text-success">Bộ lọc</strong>
      </div>
      <div class="card-body">
        <form class="row g-2 align-items-center" method="get" action="{{ route('admin.thong-bao') }}">
          <div class="col-12 col-md-6 col-lg-4">
            <input type="text" name="q" value="{{ request('q', $keyword ?? '') }}" class="form-control form-control-sm" placeholder="Tìm theo tiêu đề/nội dung">
          </div>
          <div class="col-12 col-md-3 col-lg-2">
            <select name="trang_thai" class="form-select form-select-sm">
              <option value="">Tất cả trạng thái</option>
              <option value="1" @selected((string)request('trang_thai', $trangThai ?? '')==='1')>Đang hiển thị</option>
              <option value="0" @selected((string)request('trang_thai', $trangThai ?? '')==='0')>Ẩn</option>
            </select>
          </div>
          <div class="col-12 col-md-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            @if(request('q') || request('trang_thai', '')!=='')
              <a href="{{ route('admin.thong-bao') }}" class="btn btn-sm btn-outline-secondary">Xoá lọc</a>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <strong class="text-success">Danh sách thông báo</strong>
      <div class="d-flex align-items-center gap-2">
        <div class="text-muted small me-2">Tổng: {{ $thongBaos->total() }}</div>
        <button type="button" class="btn btn-sm btn-success" id="btnOpenCreate"><i class="bi bi-plus-lg"></i> Thêm mới</button>
      </div>
    </div>
    <div class="card-body p-0">
      <style>
        .admin-noti-table th,
        .admin-noti-table td { min-width: 140px; }
        .admin-noti-table th:nth-child(1),
        .admin-noti-table td:nth-child(1) { min-width: 70px; text-align:center; }
        .admin-noti-table .content-col { min-width: 280px; }
        .admin-noti-table .action-col { position: sticky; right: 0; background: #fff; z-index: 2; min-width: 160px; }
        .admin-noti-table thead th.action-col { z-index: 3; }
        .admin-noti-table td.action-col { box-shadow: -4px 0 4px -4px rgba(0,0,0,.15); }
      </style>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle mb-0 admin-noti-table">
          <thead class="table-light">
            <tr>
              <th style="width:70px">STT</th>
              <th>Tiêu đề</th>
              <th class="content-col">Nội dung</th>
              <th style="width:140px">Trạng thái</th>
              <th class="action-col text-center" style="width:160px">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($thongBaos as $index => $tb)
              <tr>
                <td class="text-muted">{{ $thongBaos->firstItem() + $index }}</td>
                <td class="fw-semibold">{{ $tb->tieu_de }}</td>
                <td class="text-muted">{{ Str::limit($tb->noi_dung, 120) }}</td>
                <td class="align-items-center gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    title="Ẩn/Hiển thị nhanh"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    data-action="toggle-status"
                    onclick='toggleThongBao({{ $tb }})'
                  >
                    @if($tb->trang_thai)
                      <i class="bi bi-eye-slash"></i>
                    @else
                      <i class="bi bi-eye"></i>
                    @endif
                  </button>
                  @if($tb->trang_thai)
                    <span class="badge text-bg-success mb-0">Đang hiển thị</span>
                  @else
                    <span class="badge text-bg-secondary mb-0">Ẩn</span>
                  @endif
                    </div>
                </td>
                <td class="action-col text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary" data-action="edit" data-id="{{ $tb->id }}" data-title="{{ $tb->tieu_de }}" data-content="{{ $tb->noi_dung }}" data-status="{{ $tb->trang_thai }}">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-action="delete" data-id="{{ $tb->id }}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Không có thông báo nào</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <div class="small text-muted">Hiển thị {{ $thongBaos->firstItem() ?? 0 }}–{{ $thongBaos->lastItem() ?? 0 }} / {{ $thongBaos->total() }} thông báo</div>
      <div>
        {{ $thongBaos->withQueryString()->links('vendor.pagination.admin') }}
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <div class="modal fade" id="thongBaoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="thongBaoModalTitle">Thêm thông báo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="thongBaoForm">
            <input type="hidden" name="id" id="tb_id">
            <div class="mb-3">
              <label class="form-label">Tiêu đề</label>
              <input type="text" name="tieu_de" id="tb_tieu_de" class="form-control" placeholder="Nhập tiêu đề thông báo" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nội dung</label>
              <textarea name="noi_dung" id="tb_noi_dung" rows="4" class="form-control" placeholder="Nhập nội dung thông báo..." required></textarea>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="tb_trang_thai">
              <label class="form-check-label" for="tb_trang_thai">Hiển thị</label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="btnSaveThongBao"><i class="bi bi-check2-circle"></i> Lưu</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  (function() {
    // init tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const modalEl = document.getElementById('thongBaoModal');
    const modal = new bootstrap.Modal(modalEl);
    const btnOpenCreate = document.getElementById('btnOpenCreate');
    const btnSave = document.getElementById('btnSaveThongBao');
    const form = document.getElementById('thongBaoForm');

    function openCreate() {
      document.getElementById('thongBaoModalTitle').textContent = 'Thêm thông báo';
      form.reset();
      document.getElementById('tb_id').value = '';
      document.getElementById('tb_trang_thai').checked = true;
      modal.show();
    }

    function openEdit(btn) {
      document.getElementById('thongBaoModalTitle').textContent = 'Sửa thông báo';
      document.getElementById('tb_id').value = btn.getAttribute('data-id');
      document.getElementById('tb_tieu_de').value = btn.getAttribute('data-title');
      document.getElementById('tb_noi_dung').value = btn.getAttribute('data-content');
      document.getElementById('tb_trang_thai').checked = btn.getAttribute('data-status') === '1';
      modal.show();
    }

    function save() {
      const id = document.getElementById('tb_id').value.trim();
      const payload = new FormData();
      if (id) payload.append('id', id);
      payload.append('tieu_de', document.getElementById('tb_tieu_de').value.trim());
      payload.append('noi_dung', document.getElementById('tb_noi_dung').value.trim());
      payload.append('trang_thai', document.getElementById('tb_trang_thai').checked ? '1' : '0');

      const url = id ? '{{ route('admin.thong-bao.update') }}' : '{{ route('admin.thong-bao.store') }}';
      axios.post(url, payload)
        .then(function(res) {
          const data = res.data || {};
          if (data.success) {
            // queue toast then reload so list updates
            if (typeof queueToast === 'function') {
              queueToast('success', data.message || 'Đã lưu thông báo');
            } else if (typeof showToast === 'function') {
              showToast('success', data.message || 'Đã lưu thông báo');
            }
            modal.hide();
            window.location.reload();
          } else {
            if (typeof showToast === 'function') {
              showToast('error', data.message || 'Không thể lưu thông báo');
            }
          }
        })
        .catch(function() {
          if (typeof showToast === 'function') {
            showToast('error', 'Không thể lưu thông báo');
          }
        });
    }

    function confirmDelete(id) {
      if (typeof showConfirm === 'function') {
        showConfirm({
          title: 'Xác nhận xoá',
          message: 'Bạn có chắc chắn muốn xoá thông báo này?',
          confirmText: 'Xoá',
          onConfirm: function() {
            const payload = new FormData();
            payload.append('id', id);
            axios.post('{{ route('admin.thong-bao.destroy') }}', payload)
              .then(function(res) {
                const data = res.data || {};
                if (data.success) {
                  if (typeof queueToast === 'function') {
                    queueToast('success', data.message || 'Đã xoá thông báo');
                  } else if (typeof showToast === 'function') {
                    showToast('success', data.message || 'Đã xoá thông báo');
                  }
                  window.location.reload();
                } else {
                  if (typeof showToast === 'function') {
                    showToast('error', data.message || 'Không thể xoá thông báo');
                  }
                }
              })
              .catch(function() {
                if (typeof showToast === 'function') {
                  showToast('error', 'Không thể xoá thông báo');
                }
              });
          }
        });
      } else if (window.confirm('Bạn có chắc chắn muốn xoá thông báo này?')) {
        const payload = new FormData();
        payload.append('id', id);
        axios.post('{{ route('admin.thong-bao.destroy') }}', payload)
          .then(function(res) {
            const data = res.data || {};
            if (data.success) {
              if (typeof queueToast === 'function') {
                queueToast('success', data.message || 'Đã xoá thông báo');
              } else if (typeof showToast === 'function') {
                showToast('success', data.message || 'Đã xoá thông báo');
              }
              window.location.reload();
            } else {
              if (typeof showToast === 'function') {
                showToast('error', data.message || 'Không thể xoá thông báo');
              }
            }
          })
          .catch(function() {
            if (typeof showToast === 'function') {
              showToast('error', 'Không thể xoá thông báo');
            }
          });
      }
    }

    function toggleStatus(btn) {
      let tb;
      try {
        const raw = btn.getAttribute('data-tb');
        tb = raw ? JSON.parse(raw) : null;
      } catch(_) { tb = null; }
      if (!tb) {
        tb = {
          id: btn.getAttribute('data-id'),
          tieu_de: btn.getAttribute('data-title'),
          noi_dung: btn.getAttribute('data-content'),
          trang_thai: parseInt(btn.getAttribute('data-status') || '0', 10)
        };
      }
      toggleThongBao(tb);
    }

    window.toggleThongBao = function(tb) {
      const id = tb.id;
      const currentStatus = String(tb.trang_thai) === '1';
      const newStatus = currentStatus ? '0' : '1';
      const tieuDe = tb.tieu_de;
      const noiDung = tb.noi_dung;

      const payload = new FormData();
      payload.append('id', id);
      payload.append('tieu_de', tieuDe);
      payload.append('noi_dung', noiDung);
      payload.append('trang_thai', newStatus);

      axios.post('{{ route('admin.thong-bao.update') }}', payload)
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
