@extends('admin.layout.app')

@section('title', 'Người dùng')
@section('nav.users_active', 'active')

@section('breadcrumb')
<span class="text-secondary">Admin</span> / <span class="text-dark">Người dùng</span>
@endsection
@section('content')
<div class="mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <strong class="text-success">Bộ lọc</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-center" role="search">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nhập email hoặc số điện thoại">
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                    @if(request('q'))
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.users') }}">Xoá lọc</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-success">Danh sách người dùng</strong>
        <div class="d-flex align-items-center gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.export', ['q' => request('q')]) }}" title="Tải xuống CSV">
                <i class="bi bi-download"></i> Tải xuống CSV
            </a>
            <div class="text-muted small">Tổng: {{ $users->total() }}</div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <style>
            .admin-users-table th,
            .admin-users-table td { min-width:200px; }
            /* Exclude STT (col 1) and Hành động (col 7) from 200px min */
            .admin-users-table th:nth-child(1),
            .admin-users-table td:nth-child(1),
            .admin-users-table th:nth-child(7),
            .admin-users-table td:nth-child(7) { min-width:unset; }
            /* Bank column (col 4) min width 250px */
            .admin-users-table th:nth-child(4),
            .admin-users-table td:nth-child(4) { min-width:250px; }

            /* Sticky actions column */
            .admin-users-table th.sticky-actions,
            .admin-users-table td.sticky-actions {
                position: sticky;
                right: 0;
                background: #fff;
                z-index: 1;
            }
            .admin-users-table thead th.sticky-actions {
                z-index: 2; /* keep header above cells */
                background: #f8f9fa; /* match .table-light */
            }
            /* subtle divider to separate sticky column */
            .admin-users-table td.sticky-actions { box-shadow: inset 1px 0 0 rgba(0,0,0,.06); }

            /* Center align STT (col 1) and Actions (col 7) */
            .admin-users-table thead th:nth-child(1),
            .admin-users-table tbody td:nth-child(1),
            .admin-users-table thead th:nth-child(7),
            .admin-users-table tbody td:nth-child(7) { text-align: center; }

            /* Ensure visible border for Thông báo textarea to match button */
            .admin-users-table .tb-inline {
                border: 1px solid #ced4da;
                box-shadow: none;
            }
            .admin-users-table textarea.tb-inline { resize: vertical; min-height: 60px; }
        </style>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 admin-users-table">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px">STT</th>
                        <th>Thông tin người dùng</th>
                        <th style="width:260px">Hồ sơ</th>
                        <th>Ngân hàng</th>
                        <th>Thông báo</th>
                        <th style="width:220px">Giấy tờ</th>
                        <th class="sticky-actions" style="width:160px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                    <tr>
                        <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                        <td>
                            @php($p = $user->profile)
                            <div class="card border-0 bg-light-subtle">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-start gap-3">
                                        <div style="width:120px;height:160px;"
                                            class="flex-shrink-0 overflow-hidden bg-secondary-subtle d-flex align-items-center justify-content-center">
                                            @if(optional($p)->anh_chan_dung)
                                            <img src="{{ asset($p->anh_chan_dung) }}" alt="Ảnh chân dung"
                                                style="width:120px;height:160px;object-fit:cover;">
                                            @else
                                            <span class="text-muted small">—</span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $user->name ?? '—' }}</div>
                                            <div class="small text-muted">Email: {{ $user->email ?? '—' }}</div>
                                            <div class="small text-muted">SĐT: {{ $user->phone ?? '—' }}</div>
                                            <div class="small d-flex align-items-center gap-2">
                                                <span class="badge {{ $user->role== '1' ? 'text-bg-success' : 'text-bg-secondary' }} me-1">Vai trò:
                                                    {{ (string)($user->role== '1' ? 'Quản trị viên' : 'Thành viên') }}</span>
                                                <button type="button" class="{{ (int)($user->status ?? 0) === 1 ? 'btn btn-sm btn-outline-success px-2 py-0' : 'btn btn-sm btn-outline-secondary px-2 py-0' }}"
                                                    title="Đổi trạng thái nhanh"
                                                    onclick="toggleUserStatus({{ $user->id }}, {{ (int)($user->status ?? 0) }})">
                                                    <i class="bi bi-power"></i>
                                                </button>
                                                <span class="badge {{ (int)($user->status ?? 0) === 1 ? 'text-bg-success' : 'text-bg-secondary' }}">Trạng
                                                    thái:
                                                    {{ (int)($user->status ?? 0) === 1 ? 'Hoạt động' : 'Không hoạt động' }}</span>
                                            </div>
                                            <div class="mt-1 small"><span class="text-muted">Số dư:</span> <span
                                                    class="fw-semibold">{{ optional($p)->so_du !== null ? number_format((float) $p->so_du, 2) : '—' }}
                                                    đ</span></div>
                                            <div class="mt-1 small"><span class="text-muted">Tổng nạp (đã duyệt):</span> <span
                                                    class="fw-semibold">{{ number_format((float) ($user->total_nap_approved ?? 0), 2) }} đ</span></div>
                                            <div class="mt-1 small"><span class="text-muted">Tổng rút (đã duyệt):</span> <span
                                                    class="fw-semibold">{{ number_format((float) ($user->total_rut_approved ?? 0), 2) }} đ</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php($p = $user->profile)
                            <div class="card border-0 bg-light-subtle">
                                <div class="card-body py-2">
                                    <div class="small">
                                        <div class="mb-1"><span class="text-muted">Ngày sinh:</span> <span class="fw-semibold">{{ optional($p)->ngay_sinh?->format('d/m/Y') ?? '—' }}</span></div>
                                        <div class="mb-1"><span class="text-muted">Giới tính:</span> <span class="fw-semibold">{{ optional($p)->gioi_tinh ?? '—' }}</span></div>
                                        <div><span class="text-muted">Địa chỉ:</span> <span class="fw-semibold">{{ optional($p)->dia_chi ?? '—' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php($p = $user->profile)
                            <div class="card border-0 bg-light-subtle">
                                <div class="card-body py-2">
                                    <div class="small">
                                        <div class="mb-1"><span class="text-muted">Ngân hàng:</span> <span
                                                class="fw-semibold">{{ optional($p)->ngan_hang ?? '—' }}</span></div>
                                        <div class="mb-1"><span class="text-muted">Số TK:</span> <span
                                                class="fw-semibold">{{ optional($p)->so_tai_khoan ?? '—' }}</span></div>
                                            <div class="mb-1"><span class="text-muted">Chủ TK:</span> <span
                                                class="fw-semibold">{{ optional($p)->chu_tai_khoan ?? '—' }}</span>
                                        </div>
                                            <div><span class="text-muted">Mật khẩu rút tiền:</span> <span
                                                    class="fw-semibold">{{ optional($p)->mat_khau_rut_tien ?? '—' }}</span>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <textarea class="form-control form-control-sm tb-inline" placeholder="Nhập thông báo..." data-user-id="{{ $user->id }}">{{ $user->thong_bao ?? '' }}</textarea>
                                <div class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary tb-save" data-user-id="{{ $user->id }}">Lưu</button>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php($p = $user->profile)
                            <div class="card border-0 bg-light-subtle">
                                <div class="card-body py-2">
                                    <div class="d-flex gap-2">
                                        <div style="width:160px;height:120px;"
                                            class="border rounded overflow-hidden bg-secondary-subtle d-flex align-items-center justify-content-center">
                                            @if(optional($p)->anh_mat_truoc)
                                            <a href="{{ asset($p->anh_mat_truoc) }}" target="_blank" rel="noopener">
                                                <img src="{{ asset($p->anh_mat_truoc) }}" alt="Ảnh mặt trước"
                                                    style="width:160px;height:120px;object-fit:cover;">
                                            </a>
                                            @else
                                            <span class="text-muted small">—</span>
                                            @endif
                                        </div>
                                        <div style="width:160px;height:120px;"
                                            class="border rounded overflow-hidden bg-secondary-subtle d-flex align-items-center justify-content-center">
                                            @if(optional($p)->anh_mat_sau)
                                            <a href="{{ asset($p->anh_mat_sau) }}" target="_blank" rel="noopener">
                                                <img src="{{ asset($p->anh_mat_sau) }}" alt="Ảnh mặt sau"
                                                    style="width:160px;height:120px;object-fit:cover;">
                                            </a>
                                            @else
                                            <span class="text-muted small">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="sticky-actions">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-user"
                                    data-user-id="{{ $user->id }}" onclick='openEditUser(@json($user))'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning btn-change-balance"
                                        onclick='openChangeBalance(@json($user))'
                                        title="Thay đổi số dư">
                                    <i class="bi bi-cash-coin"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick='openDeleteUser(@json($user))' title="Xoá người dùng"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Không có người dùng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <div class="small text-muted">Hiển thị {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} / {{ $users->total() }} người dùng</div>
        <div>
            {{ $users->withQueryString()->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- dùng modal confirm dùng chung trong layout -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editUserForm">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Tên</label>
                            <input type="text" class="form-control" name="name" id="editUserName">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editUserEmail">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">SĐT</label>
                            <input type="text" class="form-control" name="phone" id="editUserPhone" disabled>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" id="editUserStatus">
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Vai trò</label>
                            <select class="form-select" name="role" id="editUserRole">
                                <option @if(old('role') === '0') selected @endif value="0">User</option>
                                <option @if(old('role') === '1') selected @endif value="1">Admin</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="profile[ngay_sinh]" id="editProfileNgaySinh">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="profile[gioi_tinh]" id="editProfileGioiTinh">
                                <option value="">—</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="profile[dia_chi]" id="editProfileDiaChi">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Ngân hàng</label>
                            <input type="text" class="form-control" name="profile[ngan_hang]" id="editProfileNganHang">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Số tài khoản</label>
                            <input type="text" class="form-control" name="profile[so_tai_khoan]"
                                id="editProfileSoTaiKhoan">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Chủ tài khoản</label>
                            <input type="text" class="form-control" name="profile[chu_tai_khoan]"
                                id="editProfileChuTaiKhoan">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Số dư</label>
                            <input type="number" step="0.01" class="form-control" name="profile[so_du]"
                                id="editProfileSoDu">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Mật khẩu rút tiền</label>
                            <input type="text" class="form-control" name="profile[mat_khau_rut_tien]"
                                id="editProfileMatKhauRutTien" placeholder="Nhập mật khẩu rút tiền">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Hình ảnh giấy tờ</label>
                            <div class="row g-3 align-items-start">
                                <div class="col-12 col-md-4">
                                    <div class="mb-2 d-flex justify-content-center">
                                        <div class="border rounded bg-secondary-subtle overflow-hidden" style="width:100%;max-width:260px;height:160px;display:flex;align-items:center;justify-content:center;">
                                            <img id="previewAnhMatTruoc" src="" alt="Ảnh mặt trước" style="width:100%;height:100%;object-fit:cover;display:none;">
                                            <span id="placeholderAnhMatTruoc" class="text-muted small">Không có ảnh</span>
                                        </div>
                                    </div>
                                    <label class="form-label">Link ảnh mặt trước</label>
                                    <input type="text" class="form-control" name="profile[anh_mat_truoc]" id="editProfileAnhMatTruoc" placeholder="Nhập URL ảnh mặt trước">
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-2 d-flex justify-content-center">
                                        <div class="border rounded bg-secondary-subtle overflow-hidden" style="width:100%;max-width:260px;height:160px;display:flex;align-items:center;justify-content:center;">
                                            <img id="previewAnhMatSau" src="" alt="Ảnh mặt sau" style="width:100%;height:100%;object-fit:cover;display:none;">
                                            <span id="placeholderAnhMatSau" class="text-muted small">Không có ảnh</span>
                                        </div>
                                    </div>
                                    <label class="form-label">Link ảnh mặt sau</label>
                                    <input type="text" class="form-control" name="profile[anh_mat_sau]" id="editProfileAnhMatSau" placeholder="Nhập URL ảnh mặt sau">
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-2 d-flex justify-content-center">
                                        <div class="border rounded bg-secondary-subtle overflow-hidden" style="width:100%;max-width:260px;height:160px;display:flex;align-items:center;justify-content:center;">
                                            <img id="previewAnhChanDung" src="" alt="Ảnh chân dung" style="width:100%;height:100%;object-fit:cover;display:none;">
                                            <span id="placeholderAnhChanDung" class="text-muted small">Không có ảnh</span>
                                        </div>
                                    </div>
                                    <label class="form-label">Link ảnh chân dung</label>
                                    <input type="text" class="form-control" name="profile[anh_chan_dung]" id="editProfileAnhChanDung" placeholder="Nhập URL ảnh chân dung">
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnSaveUser">Lưu</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeBalanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thay đổi số dư</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cbUserId">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Số dư hiện tại</label>
                        <input type="number" step="0.01" class="form-control" id="cbCurrent" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Số dư phát sinh</label>
                        <input type="number" step="0.01" class="form-control" id="cbDelta" placeholder="Nhập số âm hoặc dương">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Số dư mới</label>
                        <input type="number" step="0.01" class="form-control" id="cbNew" readonly>
                    </div>
                </div>
                <div class="form-text">Số dư mới = Số dư hiện tại + Số dư phát sinh</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="cbSaveBtn">Lưu</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.toggleUserStatus = async function(id, current){
            try{
                const url = "{{ route('admin.users.update') }}";
                const next = Number(current) === 1 ? 0 : 1;
                const res = await axios.post(url, { id: Number(id), status: next });
                const ok = !!res?.data?.success;
                const msg = res?.data?.message || (ok ? 'Đã cập nhật trạng thái' : 'Cập nhật thất bại');
                if(window.showToast) window.showToast(ok ? 'success' : 'error', msg);
                if(ok){ setTimeout(function(){ window.location.reload(); }, 400); }
            } catch(err){
                const msg = err?.response?.data?.message || 'Có lỗi xảy ra. Vui lòng thử lại';
                if(window.showToast) window.showToast('error', msg);
            }
        }
        console.groupCollapsed('[UsersAdmin] DOMContentLoaded');
        console.info('[UsersAdmin] Init scripts for admin users page');
        let bsModal = null;
            let bsChangeBalanceModal = null;

        function ensureModal() {
            const modalEl = document.getElementById('editUserModal');
            if (!modalEl) return null;
            if (!bsModal) {
                bsModal = bootstrap?.Modal ? new bootstrap.Modal(modalEl) : null;
                if (bsModal) {
                    console.debug('[UsersAdmin] Bootstrap modal instance created');
                } else {
                    console.warn('[UsersAdmin] Bootstrap modal unavailable');
                }
            }
            console.debug('[UsersAdmin] ensureModal ->', !!bsModal);
            return bsModal;
        }
        const form = document.getElementById('editUserForm');

        function ensureChangeBalanceModal(){
            const modalEl = document.getElementById('changeBalanceModal');
            if(!modalEl) return null;
            if(!bsChangeBalanceModal){
                bsChangeBalanceModal = bootstrap?.Modal ? new bootstrap.Modal(modalEl) : null;
            }
            return bsChangeBalanceModal;
        }

        // Confirm modal dùng chung đã có trong layout (globalConfirmModal)

        window.openEditUser = function (user) {
            console.group('[UsersAdmin] openEditUser');
            console.log('payload user:', user);
            if (!user) return;
            const modal = ensureModal();
            if (!modal) return;
            console.time('[UsersAdmin] fill-form');
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name ?? '';
            document.getElementById('editUserEmail').value = user.email ?? '';
            document.getElementById('editUserPhone').value = user.phone ?? '';
            document.getElementById('editUserRole').value = user.role ?? '0';
            // set selected option
            document.getElementById('editUserRole').selectedIndex = user.role === '0' ? 0 : 1;
            document.getElementById('editUserStatus').value = Number(user.status) === 1 ? '1' : '0';
            const p = user.profile || {};
            if (!user.profile) {
                console.warn('[UsersAdmin] user.profile is missing; using empty object');
            }
            document.getElementById('editProfileNgaySinh').value = p.ngay_sinh ? String(p.ngay_sinh).slice(
                0, 10) : '';
            document.getElementById('editProfileGioiTinh').value = p.gioi_tinh ?? '';
            // safe selected index
            const gender = p.gioi_tinh ?? '';
            document.getElementById('editProfileGioiTinh').selectedIndex = gender === '' ? 0 : (gender === 'Nam' ? 1 : (gender === 'Nữ' ? 2 : 3));
            document.getElementById('editProfileDiaChi').value = p.dia_chi ?? '';
            document.getElementById('editProfileMatKhauRutTien').value = p.mat_khau_rut_tien ?? '';
            document.getElementById('editProfileNganHang').value = p.ngan_hang ?? '';
            document.getElementById('editProfileSoTaiKhoan').value = p.so_tai_khoan ?? '';
            document.getElementById('editProfileChuTaiKhoan').value = p.chu_tai_khoan ?? '';
            document.getElementById('editProfileSoDu').value = p.so_du ?? '';
            // previews for images
            function setPreviewByValue(inputEl, imgEl, placeholderEl, url) {
                inputEl.value = url || '';
                if (url) {
                    imgEl.src = url;
                    imgEl.style.display = '';
                    placeholderEl.style.display = 'none';
                } else {
                    imgEl.src = '';
                    imgEl.style.display = 'none';
                    placeholderEl.style.display = '';
                }
            }
            setPreviewByValue(
                document.getElementById('editProfileAnhMatTruoc'),
                document.getElementById('previewAnhMatTruoc'),
                document.getElementById('placeholderAnhMatTruoc'),
                p.anh_mat_truoc || ''
            );
            setPreviewByValue(
                document.getElementById('editProfileAnhMatSau'),
                document.getElementById('previewAnhMatSau'),
                document.getElementById('placeholderAnhMatSau'),
                p.anh_mat_sau || ''
            );
            setPreviewByValue(
                document.getElementById('editProfileAnhChanDung'),
                document.getElementById('previewAnhChanDung'),
                document.getElementById('placeholderAnhChanDung'),
                p.anh_chan_dung || ''
            );
            console.timeEnd('[UsersAdmin] fill-form');
            console.info('[UsersAdmin] Showing modal');
            modal.show();
            console.groupEnd();
        }

        // New: open change balance by passing user object directly
        window.openChangeBalance = function(user){
            if(!user) return;
            const modal = ensureChangeBalanceModal();
            const current = Number(user?.profile?.so_du ?? 0);
            const userId = Number(user.id);
            document.getElementById('cbUserId').value = userId;
            document.getElementById('cbCurrent').value = current.toFixed(2);
            document.getElementById('cbDelta').value = '';
            document.getElementById('cbNew').value = current.toFixed(2);
            if(modal) modal.show();
        }

        // live preview on input change
        function bindPreview(inputId, imgId, placeholderId){
            const input = document.getElementById(inputId);
            const img = document.getElementById(imgId);
            const ph = document.getElementById(placeholderId);
            if(!input) return;
            input.addEventListener('input', function(){
                const url = input.value.trim();
                if(url){
                    img.src = url;
                    img.style.display = '';
                    ph.style.display = 'none';
                } else {
                    img.src = '';
                    img.style.display = 'none';
                    ph.style.display = '';
                }
            });
        }
        bindPreview('editProfileAnhMatTruoc','previewAnhMatTruoc','placeholderAnhMatTruoc');
        bindPreview('editProfileAnhMatSau','previewAnhMatSau','placeholderAnhMatSau');
        bindPreview('editProfileAnhChanDung','previewAnhChanDung','placeholderAnhChanDung');

        // Change balance handlers
        const deltaInput = document.getElementById('cbDelta');
        function recalcNew(){
            const current = Number(document.getElementById('cbCurrent').value || 0);
            const delta = Number(deltaInput.value || 0);
            const next = current + delta;
            document.getElementById('cbNew').value = Number.isFinite(next) ? next.toFixed(2) : '';
        }
        if(deltaInput){
            deltaInput.addEventListener('input', recalcNew);
        }

        // Removed attribute-based listener; open via openChangeBalance(user)

        document.getElementById('cbSaveBtn')?.addEventListener('click', async function(){
            const btn = this;
            if(btn.disabled) return;
            const userId = Number(document.getElementById('cbUserId').value);
            const newBalance = Number(document.getElementById('cbNew').value);
            const payload = { id: userId, profile: { so_du: newBalance } };
            const original = btn.innerHTML;
            try{
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Đang lưu...';
                const url = "{{ route('admin.users.update') }}";
                const res = await axios.post(url, payload);
                const ok = !!res.data?.success;
                const msg = res.data?.message || (ok ? 'Cập nhật thành công' : 'Cập nhật thất bại');
                if(ok){
                    showToast('success', msg);
                    const modal = ensureChangeBalanceModal();
                    if(modal) modal.hide();
                    setTimeout(function(){ window.location.reload(); }, 600);
                } else {
                    showToast('error', msg);
                }
            } catch(err){
                let msg = err?.response?.data?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                showToast('error', msg);
            } finally {
                btn.disabled = false;
                btn.innerHTML = original;
            }
        });

        // Save thong_bao explicitly on button click
        document.querySelectorAll('.tb-save').forEach(function(btn){
            btn.addEventListener('click', async function(){
                const userId = this.getAttribute('data-user-id');
                const input = document.querySelector('textarea.tb-inline[data-user-id="' + userId + '"]');
                const value = input ? input.value : '';
                const originalHtml = this.innerHTML;
                try{
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Lưu';
                    const url = "{{ route('admin.users.update') }}";
                    const res = await axios.post(url, { id: Number(userId), thong_bao: value });
                    const ok = !!res?.data?.success;
                    const msg = res?.data?.message || (ok ? 'Đã lưu thông báo' : 'Lưu thông báo thất bại');
                    if(window.showToast) window.showToast(ok ? 'success' : 'error', msg);
                }catch(err){
                    const msg = err?.response?.data?.message || 'Có lỗi xảy ra khi lưu thông báo';
                    if(window.showToast) window.showToast('error', msg);
                } finally {
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                }
            });
        });

        // Delete user
        let deleteContext = { id: null, label: '' };

        // New: open delete by passing user object directly (no data-* attributes needed)
        window.openDeleteUser = function(user){
            if(!user) return;
            const userId = Number(user.id);
            const label = String(user.name ?? user.email ?? ('ID#'+userId));
            deleteContext = { id: userId, label };
            showConfirm({
                title: 'Xác nhận xoá',
                message: 'Bạn có chắc chắn muốn xoá người dùng ' + label + ' ? Hành động này không thể hoàn tác.',
                confirmText: 'Xoá',
                onConfirm: async function(){
                    const url = "{{ route('admin.users.destroy') }}";
                    try{
                        const res = await axios.post(url, { id: deleteContext.id });
                        const ok = !!res.data?.success;
                        const msg = res.data?.message || (ok ? 'Đã xoá người dùng' : 'Xoá thất bại');
                        if(ok){
                            showToast('success', msg);
                            setTimeout(function(){ window.location.reload(); }, 600);
                        } else {
                            showToast('error', msg);
                        }
                    } catch(err){
                        let msg = err?.response?.data?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                        showToast('error', msg);
                    }
                }
            });
        }
        // Các nút có class .btn-delete-user (nếu có) sẽ dùng confirm chung
        document.querySelectorAll('.btn-delete-user').forEach(function(btn){
            btn.addEventListener('click', function(){
                const userId = Number(btn.getAttribute('data-user-id'));
                const label = String(btn.getAttribute('data-user-name') || ('ID#'+userId));
                deleteContext = { id: userId, label };
                showConfirm({
                    title: 'Xác nhận xoá',
                    message: 'Bạn có chắc chắn muốn xoá người dùng ' + label + ' ? Hành động này không thể hoàn tác.',
                    confirmText: 'Xoá',
                    onConfirm: async function(){
                        const url = "{{ route('admin.users.destroy') }}";
                        try{
                            const res = await axios.post(url, { id: deleteContext.id });
                            const ok = !!res.data?.success;
                            const msg = res.data?.message || (ok ? 'Đã xoá người dùng' : 'Xoá thất bại');
                            if(ok){
                                showToast('success', msg);
                                setTimeout(function(){ window.location.reload(); }, 600);
                            } else {
                                showToast('error', msg);
                            }
                        } catch(err){
                            let msg = err?.response?.data?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                            showToast('error', msg);
                        }
                    }
                });
            });
        });

        document.getElementById('btnSaveUser').addEventListener('click', async function () {
            console.group('[UsersAdmin] SaveUser click');
            const btn = this;
            if (btn.disabled) return;
            const id = document.getElementById('editUserId').value;
            const payload = {
                id: Number(id),
                name: document.getElementById('editUserName').value.trim() || null,
                email: document.getElementById('editUserEmail').value.trim() || null,
                phone: document.getElementById('editUserPhone').value.trim() || null,
                role: document.getElementById('editUserRole').value || null,
                status: Number(document.getElementById('editUserStatus').value),
                profile: {
                    ngay_sinh: document.getElementById('editProfileNgaySinh').value || null,
                    gioi_tinh: document.getElementById('editProfileGioiTinh').value || null,
                    dia_chi: document.getElementById('editProfileDiaChi').value.trim() || null,
                    mat_khau_rut_tien: document.getElementById('editProfileMatKhauRutTien')
                        .value.trim() || null,
                    ngan_hang: document.getElementById('editProfileNganHang').value.trim() ||
                        null,
                    so_tai_khoan: document.getElementById('editProfileSoTaiKhoan').value
                        .trim() || null,
                    chu_tai_khoan: document.getElementById('editProfileChuTaiKhoan').value
                        .trim() || null,
                    so_du: document.getElementById('editProfileSoDu').value !== '' ? Number(
                        document.getElementById('editProfileSoDu').value) : null,
                    anh_mat_truoc: document.getElementById('editProfileAnhMatTruoc').value.trim() || null,
                    anh_mat_sau: document.getElementById('editProfileAnhMatSau').value.trim() || null,
                    anh_chan_dung: document.getElementById('editProfileAnhChanDung').value.trim() || null,
                }
            };

            const originalBtnHtml = btn.innerHTML;
            try {
                console.time('[UsersAdmin] save-request');
                console.debug('[UsersAdmin] Payload built:', payload);
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Đang lưu...';
                const url = "{{ route('admin.users.update') }}";
                console.info('[UsersAdmin] POST', url);
                const res = await axios.post(url, payload);
                console.debug('[UsersAdmin] Response status:', res.data);
                let check = res.data.success;
                let msg = res.data.message;
                if(!check){
                    msg = res.data.message;
                }

                // Thông báo thành công, đóng modal và tải lại trang để lấy dữ liệu mới nhất
                if (check) {
                   showToast('success', msg);
                   setTimeout(function () {
                const modal = ensureModal();
                console.info('[UsersAdmin] Save flow completed successfully -> Reloading');
                console.info('[UsersAdmin] Save flow completed successfully -> Hiding modal');
                if (modal) modal.hide();
                    window.location.reload();
                    console.info('[UsersAdmin] Save flow completed successfully -> Reloading completed');
                  }, 600);
                }else{
                    showToast('error', msg);
                }
                
            } catch (err) {
                console.error('[UsersAdmin] Save failed:', err);
                let msg = err.response.data.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                showToast('error', msg);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
                console.timeEnd('[UsersAdmin] save-request');
                console.groupEnd();
            }
        });
        console.groupEnd();
    });

</script>
@endsection
