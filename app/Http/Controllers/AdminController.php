<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Profile;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\SanPhamTietKiem;
use App\Models\NapRut;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use App\Models\NganHangNapTien;
use App\Models\ThongBao;
use App\Models\VangDauTu;
use App\Models\TietKiem;
use App\Models\GiaVang;
use App\Models\DauTuVang;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function users(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));

        $users = User::with('profile')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('email', 'like', $like)
                      ->orWhere('phone', 'like', $like);
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        // Bổ sung tổng nạp/rút đã duyệt (trang_thai = 1)
        $userIds = collect($users->items())->pluck('id')->all();
        if (!empty($userIds)) {
            $napTotals = NapRut::query()
                ->selectRaw('user_id, SUM(so_tien) as total')
                ->whereIn('user_id', $userIds)
                ->where('loai', 'nap')
                ->where('trang_thai', 1)
                ->groupBy('user_id')
                ->pluck('total', 'user_id');

            $rutTotals = NapRut::query()
                ->selectRaw('user_id, SUM(so_tien) as total')
                ->whereIn('user_id', $userIds)
                ->where('loai', 'rut')
                ->where('trang_thai', 1)
                ->groupBy('user_id')
                ->pluck('total', 'user_id');

            foreach ($users as $u) {
                $u->total_nap_approved = isset($napTotals[$u->id]) ? (float) $napTotals[$u->id] : 0.0;
                $u->total_rut_approved = isset($rutTotals[$u->id]) ? (float) $rutTotals[$u->id] : 0.0;
            }
        }

        return view('admin.users', compact('users'));
    }

    public function exportUsers(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));

        $query = User::with('profile')
            ->when($keyword !== '', function ($q) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $q->where(function ($sub) use ($like) {
                    $sub->where('email', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->orderByDesc('id');

        $filename = 'users_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ];

        $columns = [
            'ID', 'Tên', 'Email', 'SĐT', 'Vai trò', 'Trạng thái', 'Thông báo',
            'Số dư', 'Tổng nạp (đã duyệt)', 'Tổng rút (đã duyệt)', 'Ngày sinh', 'Giới tính', 'Địa chỉ',
            'Ngân hàng', 'Số tài khoản', 'Chủ tài khoản', 'Mật khẩu rút tiền',
        ];

        $callback = function () use ($query, $columns) {
            $handle = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            $query->chunk(500, function ($users) use ($handle) {
                // Tính tổng nạp/rút đã duyệt cho nhóm người dùng trong chunk hiện tại
                $userIds = collect($users)->pluck('id')->all();
                $napTotals = NapRut::query()
                    ->selectRaw('user_id, SUM(so_tien) as total')
                    ->whereIn('user_id', $userIds)
                    ->where('loai', 'nap')
                    ->where('trang_thai', 1)
                    ->groupBy('user_id')
                    ->pluck('total', 'user_id');
                $rutTotals = NapRut::query()
                    ->selectRaw('user_id, SUM(so_tien) as total')
                    ->whereIn('user_id', $userIds)
                    ->where('loai', 'rut')
                    ->where('trang_thai', 1)
                    ->groupBy('user_id')
                    ->pluck('total', 'user_id');

                foreach ($users as $user) {
                    $p = $user->profile;
                    $tongNap = isset($napTotals[$user->id]) ? (float) $napTotals[$user->id] : 0.0;
                    $tongRut = isset($rutTotals[$user->id]) ? (float) $rutTotals[$user->id] : 0.0;
                    $row = [
                        $user->id,
                        (string) ($user->name ?? ''),
                        (string) ($user->email ?? ''),
                        (string) ($user->phone ?? ''),
                        (string) ((string)($user->role) === '1' ? 'Quản trị viên' : 'Thành viên'),
                        (string) (((int)($user->status ?? 0)) === 1 ? 'Hoạt động' : 'Không hoạt động'),
                        (string) ($user->thong_bao ?? ''),
                        is_null(optional($p)->so_du) ? '' : (string) number_format((float) $p->so_du, 2, '.', ''),
                        (string) number_format($tongNap, 2, '.', ''),
                        (string) number_format($tongRut, 2, '.', ''),
                        optional($p)->ngay_sinh ? optional($p)->ngay_sinh->format('Y-m-d') : '',
                        (string) (optional($p)->gioi_tinh ?? ''),
                        (string) (optional($p)->dia_chi ?? ''),
                        (string) (optional($p)->ngan_hang ?? ''),
                        (string) (optional($p)->so_tai_khoan ?? ''),
                        (string) (optional($p)->chu_tai_khoan ?? ''),
                        (string) (optional($p)->mat_khau_rut_tien ?? ''),
                    ];
                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:users,id'],
                'name' => ['nullable','string','max:255'],
                'email' => ['nullable','email','max:255', Rule::unique('users')->ignore($request->input('id'))],
                // Không cho phép chỉnh sửa phone từ admin form
                'phone' => ['nullable','string','max:30'],
                'role' => ['nullable','in:0,1'],
                'status' => ['nullable','boolean'],
                'thong_bao' => ['nullable','string','max:255'],
                'profile.ngay_sinh' => ['nullable','date'],
                'profile.gioi_tinh' => ['nullable','string','max:20'],
                'profile.dia_chi' => ['nullable','string','max:255'],
                'profile.mat_khau_rut_tien' => ['nullable','string','max:255'],
                'profile.ngan_hang' => ['nullable','string','max:100'],
                'profile.so_tai_khoan' => ['nullable','string','max:100'],
                'profile.chu_tai_khoan' => ['nullable','string','max:100'],
                'profile.so_du' => ['nullable','numeric'],
                'profile.anh_mat_truoc' => ['nullable','string','max:2048'],
                'profile.anh_mat_sau' => ['nullable','string','max:2048'],
                'profile.anh_chan_dung' => ['nullable','string','max:2048'],
            ]);

            $user = User::findOrFail($validated['id']);
            if (array_key_exists('name', $validated)) {
                $user->name = $validated['name'];
            }
            if (array_key_exists('email', $validated)) {
                $user->email = $validated['email'];
            }
            // Bỏ qua cập nhật phone để đảm bảo không chỉnh sửa số điện thoại
            if (array_key_exists('role', $validated)) {
                $user->role = (int) $validated['role'];
            }
            if (array_key_exists('status', $validated)) {
                $user->status = (int) $validated['status'];
            }
            if (array_key_exists('thong_bao', $validated)) {
                $user->thong_bao = $validated['thong_bao'];
            }

            $user->save();

            // Save profile if provided
            $profilePayload = $request->input('profile');
            if (is_array($profilePayload)) {
                $profile = $user->profile ?: new Profile([ 'user_id' => $user->id ]);
                $allowed = [
                    'ngan_hang','so_tai_khoan','chu_tai_khoan','so_du','ngay_sinh','gioi_tinh','dia_chi','mat_khau_rut_tien','anh_mat_truoc','anh_mat_sau','anh_chan_dung'
                ];
                foreach ($allowed as $field) {
                    if (array_key_exists($field, $profilePayload)) {
                        $profile->{$field} = $profilePayload[$field];
                    }
                }
                $profile->user_id = $user->id;
                $profile->save();
                // refresh relation
                $user->setRelation('profile', $profile->fresh());
            } else {
                $user->loadMissing('profile');
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật người dùng thành công',
                'user' => array_merge(
                    $user->only(['id','name','email','phone','role','status','thong_bao']),
                    [ 'profile' => optional($user->profile)?->only([
                        'ngan_hang','so_tai_khoan','chu_tai_khoan','so_du','ngay_sinh','gioi_tinh','dia_chi','mat_khau_rut_tien','anh_mat_truoc','anh_mat_sau','anh_chan_dung'
                    ]) ]
                )
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật người dùng. Vui lòng thử lại sau.',
            ]);
        }
    }

    public function destroyUser(Request $request)
    {
        try {
            $id = (int) $request->input('id');
            $user = User::findOrFail($id);
            // Optional: prevent deleting self or last admin, add rules as needed
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xoá người dùng thành công'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xoá người dùng. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function sanPhamDauTu(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $sanPhamDauTu = SanPhamTietKiem::orderByDesc('id')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('ten', 'like', $like);
                });
            })
            ->paginate(10);
        return view('admin.san-pham-tiet-kiem', compact('sanPhamDauTu'));
    }
    public function updateSanPhamDauTu(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:san_pham_dau_tu,id'],
                'ten' => ['nullable','string','max:255'],
                'slug' => ['nullable','string','max:255'],
                // allow optional image upload on update, including webp
                'hinh_anh' => ['nullable','file','image','mimes:jpeg,jpg,png,webp','max:5120'],
                'von_toi_thieu' => ['nullable','numeric'],
                'von_toi_da' => ['nullable','numeric'],
                'thoi_gian_mot_chu_ky' => ['nullable','integer'],
                'nhan_dan' => ['nullable','string','max:255'],
                'mo_ta' => ['nullable','string','max:255'],
                'trang_thai' => ['nullable','boolean'],
            ]);
            $sanPhamDauTu = SanPhamTietKiem::findOrFail($validated['id']);
            if (array_key_exists('ten', $validated)) {
                $sanPhamDauTu->ten = $validated['ten'];
            }
            if (array_key_exists('slug', $validated)) {
                $newSlug = trim((string) $validated['slug']);
                if ($newSlug !== '') {
                    $newSlug = Str::slug($newSlug);
                    // uniqueness check, if conflicts then append random suffix
                    if (SanPhamTietKiem::where('slug', $newSlug)->where('id', '!=', $sanPhamDauTu->id)->exists()) {
                        $base = $newSlug;
                        $attempt = 0;
                        do {
                            $attempt++;
                            $candidate = $base . '-' . Str::lower(Str::random(6));
                        } while (SanPhamTietKiem::where('slug', $candidate)->where('id', '!=', $sanPhamDauTu->id)->exists() && $attempt < 60);
                        $newSlug = $candidate;
                    }
                    $sanPhamDauTu->slug = $newSlug;
                } else {
                    $sanPhamDauTu->slug = null;
                }
            }
            // Handle optional image upload
            if ($request->hasFile('hinh_anh')) {
                $file = $request->file('hinh_anh');
                $destinationPath = public_path('uploads/products');
                if (!is_dir($destinationPath)) {
                    @mkdir($destinationPath, 0755, true);
                }
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
                $sanPhamDauTu->hinh_anh = 'uploads/products/' . $filename;
            }
            if (array_key_exists('von_toi_thieu', $validated)) {
                $sanPhamDauTu->von_toi_thieu = $validated['von_toi_thieu'];
            }
            if (array_key_exists('von_toi_da', $validated)) {
                $sanPhamDauTu->von_toi_da = $validated['von_toi_da'];
            }
            
            if (array_key_exists('thoi_gian_mot_chu_ky', $validated)) {
                $sanPhamDauTu->thoi_gian_mot_chu_ky = $validated['thoi_gian_mot_chu_ky'];
            }
            if (array_key_exists('nhan_dan', $validated)) {
                $sanPhamDauTu->nhan_dan = $validated['nhan_dan'];
            }
            if (array_key_exists('mo_ta', $validated)) {
                $sanPhamDauTu->mo_ta = $validated['mo_ta'];
            }
            if (array_key_exists('trang_thai', $validated)) {
                $sanPhamDauTu->trang_thai = (int) $validated['trang_thai'];
            }
            $sanPhamDauTu->save();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm đầu tư thành công'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm đầu tư'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật sản phẩm đầu tư. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    public function storeSanPhamDauTu(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten' => ['required','string','max:255'],
                'slug' => ['nullable','string','max:255'],
                // accept webp as well
                'hinh_anh' => ['required','file','image','mimes:jpeg,jpg,png,webp','max:5120'],
                'von_toi_thieu' => ['required','numeric'],
                'von_toi_da' => ['required','numeric'],
                'thoi_gian_mot_chu_ky' => ['required','integer','min:0'],
                'lai_suat' => ['required','numeric'],
                'nhan_dan' => ['nullable','string','max:255'],
                'mo_ta' => ['required','string'],
                'trang_thai' => ['required','boolean'],
            ]);

            // Build slug; ensure uniqueness by appending random suffix if needed
            $baseSlug = Str::slug($validated['ten']);
            $providedSlug = isset($validated['slug']) && trim($validated['slug']) !== '' ? Str::slug($validated['slug']) : null;
            $slug = $providedSlug ?: $baseSlug;
            if ($slug === '') {
                $slug = Str::random(8);
            }
            // Ensure unique slug
            $original = $slug;
            $i = 0;
            while (SanPhamTietKiem::where('slug', $slug)->exists()) {
                $i++;
                $slug = $original . '-' . Str::lower(Str::random(6));
                if ($i > 50) { // safety guard
                    $slug = $original . '-' . time() . '-' . Str::lower(Str::random(4));
                }
                if ($i > 60) break;
            }

            $payload = [
                'ten' => $validated['ten'],
                'slug' => $slug,
                'von_toi_thieu' => $validated['von_toi_thieu'],
                'von_toi_da' => $validated['von_toi_da'],
                'lai_suat' => $validated['lai_suat'],
                'thoi_gian_mot_chu_ky' => $validated['thoi_gian_mot_chu_ky'],
                'nhan_dan' => $validated['nhan_dan'] ?? null,
                'mo_ta' => $validated['mo_ta'],
                'trang_thai' => (int) $validated['trang_thai'],
            ];

            if ($request->hasFile('hinh_anh')) {
                $file = $request->file('hinh_anh');
                $destinationPath = public_path('uploads/products');
                if (!is_dir($destinationPath)) {
                    @mkdir($destinationPath, 0755, true);
                }
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
                // Lưu đường dẫn tương đối tính từ public để dùng với asset()
                $payload['hinh_anh'] = 'uploads/products/' . $filename;
            }

            $created = SanPhamTietKiem::create($payload);

            return response()->json([
                'success' => true,
                'message' => 'Tạo sản phẩm đầu tư thành công',
                'data' => $created,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo sản phẩm đầu tư. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function destroySanPhamDauTu(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:san_pham_dau_tu,id']
            ]);

            $item = SanPhamTietKiem::findOrFail((int) $validated['id']);

            // Remove associated image file if exists
            $relativePath = (string) ($item->hinh_anh ?? '');
            if ($relativePath !== '') {
                $fullPath = public_path($relativePath);
                if (is_string($fullPath) && $fullPath !== '' && file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xoá sản phẩm đầu tư thành công'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm đầu tư'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xoá sản phẩm đầu tư. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function nganHangNapTien(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $banks = NganHangNapTien::orderByDesc('id')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('ten_ngan_hang', 'like', $like)
                      ->orWhere('so_tai_khoan', 'like', $like)
                      ->orWhere('chu_tai_khoan', 'like', $like)
                      ->orWhere('chi_nhanh', 'like', $like);
                });
            })
            ->paginate(10);
        return view('admin.ngan-hang-nap-tien', compact('banks'));
    }
    public function storeNganHangNapTien(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_ngan_hang' => ['required','string','max:255'],
                // accept URL string for image/logo
                'hinh_anh' => ['nullable','string','max:2048'],
                'so_tai_khoan' => ['required','string','max:100'],
                'chu_tai_khoan' => ['required','string','max:150'],
                'chi_nhanh' => ['nullable','string','max:255'],
                'ghi_chu' => ['nullable','string','max:255'],
                'trang_thai' => ['required','boolean'],
            ]);

            $payload = [
                'ten_ngan_hang' => $validated['ten_ngan_hang'],
                'so_tai_khoan' => $validated['so_tai_khoan'],
                'chu_tai_khoan' => $validated['chu_tai_khoan'],
                'chi_nhanh' => $validated['chi_nhanh'] ?? null,
                'ghi_chu' => $validated['ghi_chu'] ?? null,
                'trang_thai' => (int) $validated['trang_thai'],
            ];

            // If url provided, save as-is
            if (array_key_exists('hinh_anh', $validated)) {
                $url = trim((string) ($validated['hinh_anh'] ?? ''));
                $payload['hinh_anh'] = $url !== '' ? $url : null;
            }

            $created = NganHangNapTien::create($payload);

            return response()->json([
                'success' => true,
                'message' => 'Tạo ngân hàng nạp tiền thành công',
                'data' => $created,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo ngân hàng. Vui lòng thử lại sau.',
            ], 500);
        }
    }
    public function updateNganHangNapTien(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:ngan_hang_nap_tien,id'],
                'ten_ngan_hang' => ['nullable','string','max:255'],
                // accept URL string for image/logo
                'hinh_anh' => ['nullable','string','max:2048'],
                'so_tai_khoan' => ['nullable','string','max:100'],
                'chu_tai_khoan' => ['nullable','string','max:150'],
                'chi_nhanh' => ['nullable','string','max:255'],
                'ghi_chu' => ['nullable','string','max:255'],
                'trang_thai' => ['nullable','boolean'],
            ]);
            $item = NganHangNapTien::findOrFail((int) $validated['id']);

            foreach (['ten_ngan_hang','so_tai_khoan','chu_tai_khoan','chi_nhanh','ghi_chu'] as $field) {
                if (array_key_exists($field, $validated)) {
                    $item->{$field} = $validated[$field];
                }
            }
            if (array_key_exists('trang_thai', $validated)) {
                $item->trang_thai = (int) $validated['trang_thai'];
            }
            if (array_key_exists('hinh_anh', $validated)) {
                $url = trim((string) ($validated['hinh_anh'] ?? ''));
                $item->hinh_anh = $url !== '' ? $url : null;
            }

            $item->save();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ngân hàng thành công'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ngân hàng'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật ngân hàng. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function destroyNganHangNapTien(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:ngan_hang_nap_tien,id']
            ]);
            $item = NganHangNapTien::findOrFail((int) $validated['id']);
            $old = (string) ($item->hinh_anh ?? '');
            if ($old !== '') {
                $fullPath = public_path($old);
                if (is_string($fullPath) && $fullPath !== '' && file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xoá ngân hàng thành công'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy ngân hàng'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xoá ngân hàng. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function napRut(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $loai = $request->input('loai', '');
        $trangThai = $request->input('trang_thai', '');
        
        $transactions = NapRut::with('user')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('so_tai_khoan', 'like', $like)
                        ->orWhere('chu_tai_khoan', 'like', $like)
                        ->orWhere('ngan_hang', 'like', $like)
                        ->orWhere('noi_dung', 'like', $like)
                        ->orWhereHas('user', function ($userQuery) use ($like) {
                            $userQuery->where('name', 'like', $like)
                                     ->orWhere('email', 'like', $like);
                        });
                });
            })
            ->when($loai != '', function ($query) use ($loai) {
                $query->where('loai', $loai);
            })
            ->when($trangThai != '', function ($query) use ($trangThai) {
                $query->where('trang_thai', $trangThai);
            })
            ->orderByDesc('id')
            ->paginate(10);
            
        return view('admin.nap-rut', compact('transactions'));
    }

    public function updateNapRutStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required', 'integer', 'exists:nap_rut,id'],
                'trang_thai' => ['required', 'integer', 'in:0,1,2']
            ]);

            $transaction = NapRut::findOrFail($validated['id']);
            
            // Lưu trạng thái cũ để kiểm tra
            $oldStatus = $transaction->trang_thai;
            $transaction->trang_thai = $validated['trang_thai'];
            $transaction->save();

            // Xử lý cộng số dư khi duyệt giao dịch nạp tiền
            if ($validated['trang_thai'] == 1 && $transaction->loai == 'nap' && $oldStatus != 1) {
                $profile = Profile::where('user_id', $transaction->user_id)->first();
                if ($profile) {
                    $profile->so_du = $profile->so_du + $transaction->so_tien;
                    $profile->save();
                }
            }

            // Xử lý hoàn lại số dư khi từ chối giao dịch rút tiền (tiền đã bị trừ khi tạo yêu cầu)
            if ($validated['trang_thai'] == 2 && $transaction->loai == 'rut' && $oldStatus != 2) {
                $profile = Profile::where('user_id', $transaction->user_id)->first();
                if ($profile) {
                    $profile->so_du = $profile->so_du + $transaction->so_tien;
                    $profile->save();
                }
            }

            // Xử lý trường hợp admin thay đổi từ "từ chối" về "đã duyệt" cho giao dịch rút tiền
            if ($validated['trang_thai'] == 1 && $transaction->loai == 'rut' && $oldStatus == 2) {
                $profile = Profile::where('user_id', $transaction->user_id)->first();
                if ($profile) {
                    $profile->so_du = $profile->so_du - $transaction->so_tien;
                    $profile->save();
                }
            }

            // Xử lý trường hợp admin thay đổi từ "đã duyệt" về "từ chối" cho giao dịch rút tiền
            if ($validated['trang_thai'] == 2 && $transaction->loai == 'rut' && $oldStatus == 1) {
                $profile = Profile::where('user_id', $transaction->user_id)->first();
                if ($profile) {
                    $profile->so_du = $profile->so_du + $transaction->so_tien;
                    $profile->save();
                }
            }

            $statusText = '';
            switch($validated['trang_thai']) {
                case 0: $statusText = 'Chờ xử lý'; break;
                case 1: $statusText = 'Đã duyệt'; break;
                case 2: $statusText = 'Từ chối'; break;
            }

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái giao dịch thành '{$statusText}'"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy giao dịch'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái giao dịch. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function thongBao(Request $request){
        $keyword = trim((string) $request->input('q', ''));
        $trangThai = $request->input('trang_thai', '');

        $thongBaos = ThongBao::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('tieu_de', 'like', $like)
                      ->orWhere('noi_dung', 'like', $like);
                });
            })
            ->when($trangThai !== '', function ($query) use ($trangThai) {
                $query->where('trang_thai', (int) $trangThai);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.thong-bao', compact('thongBaos', 'keyword', 'trangThai'));
    }
    public function storeThongBao(Request $request)
    {
        try {
            $validated = $request->validate([
                'tieu_de' => ['required','string','max:255'],
                'noi_dung' => ['required','string'],
                'trang_thai' => ['required','boolean'],
            ]);

            $created = ThongBao::create([
                'tieu_de' => $validated['tieu_de'],
                'noi_dung' => $validated['noi_dung'],
                'trang_thai' => (int) $validated['trang_thai'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo thông báo thành công',
                'data' => $created,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo thông báo. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function updateThongBao(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:thong_baos,id'],
                'tieu_de' => ['required','string','max:255'],
                'noi_dung' => ['required','string'],
                'trang_thai' => ['required','boolean'],
            ]);

            $item = ThongBao::findOrFail((int) $validated['id']);
            $item->tieu_de = $validated['tieu_de'];
            $item->noi_dung = $validated['noi_dung'];
            $item->trang_thai = (int) $validated['trang_thai'];
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông báo thành công',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật thông báo. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function destroyThongBao(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required','integer','exists:thong_baos,id'],
            ]);

            $item = ThongBao::findOrFail((int) $validated['id']);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xoá thông báo thành công',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xoá thông báo. Vui lòng thử lại sau.',
            ], 500);
        }
    }
    public function dauTu(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $trangThai = $request->input('trang_thai', '');
        $sanPhamId = $request->input('san_pham_id', '');
        
        $dauTu = DauTuVang::with(['user', 'vangDauTu'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->whereHas('user', function ($userQuery) use ($like) {
                    $userQuery->where('name', 'like', $like)
                             ->orWhere('email', 'like', $like);
                });
            })
            ->when($trangThai != '', function ($query) use ($trangThai) {
                $query->where('trang_thai', $trangThai);
            })
            ->when($sanPhamId != '', function ($query) use ($sanPhamId) {
                $query->where('id_vang', $sanPhamId);
            })
            ->orderByDesc('id')
            ->paginate(10);
            
        // Lấy danh sách sản phẩm đầu tư cho dropdown filter
        $sanPhamDauTu = VangDauTu::where('trang_thai', 1)->orderBy('ten_vang')->get();
            
        return view('admin.dau-tu', compact('dauTu', 'sanPhamDauTu'));
    }

    public function updateDauTuStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required', 'integer', 'exists:dau_tu_vang,id'],
                'trang_thai' => ['required', 'integer', 'in:0,1,2,3']
            ]);

            $dauTu = DauTuVang::findOrFail($validated['id']);
            $dauTu->trang_thai = $validated['trang_thai'];
            $dauTu->save();

            $statusText = '';
            switch($validated['trang_thai']) {
                case 0: $statusText = 'Chờ xử lý'; break;
                case 1: $statusText = 'Đang hoạt động'; break;
                case 2: $statusText = 'Hoàn thành'; break;
                case 3: $statusText = 'Hủy bỏ'; break;
            }

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái đầu tư thành '{$statusText}'"
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đầu tư'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái đầu tư. Vui lòng thử lại sau.'
            ], 500);
        }
    }
    public function vangDauTu(Request $request)
    {
        $keyword = trim((string) $request->input('q', ''));
        $trangThai = $request->input('trang_thai', '');
        
        $vangDauTu = VangDauTu::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $keyword) . '%';
                $query->where('ten_vang', 'like', $like);
            })
            ->when($trangThai != '', function ($query) use ($trangThai) {
                $query->where('trang_thai', $trangThai);
            })
            ->orderByDesc('id')
            ->paginate(10);
            
        return view('admin.vang-dau-tu', compact('vangDauTu'));
    }
    public function storeVangDauTu(Request $request)
    {
        Log::info('storeVangDauTu', $request->all());
        $validated = $request->validate([
            'ten_vang' => ['required','string','max:255'],
            'ma_vang' => ['required','string','max:255'],
            'trang_thai' => ['required','boolean'],
        ]);
        $vangDauTu = VangDauTu::create([
            'ten_vang' => $validated['ten_vang'],
            'ma_vang' => $validated['ma_vang'],
            'trang_thai' => $validated['trang_thai'],
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm vàng đầu tư thành công',
        ]);
    }
    public function updateVangDauTu(Request $request)
    {
        Log::info('updateVangDauTu', $request->all());
        $validated = $request->validate([
            'id' => ['required','integer','exists:san_pham_vang,id'],
            'ten_vang' => ['nullable','string','max:255'],
            'ma_vang' => ['nullable','string','max:255'],
            'trang_thai' => ['nullable','integer','in:0,1'],
        ]);
        $vangDauTu = VangDauTu::findOrFail($validated['id']);
        if (array_key_exists('ten_vang', $validated)) {
            $vangDauTu->ten_vang = $validated['ten_vang'];
        }
        if (array_key_exists('ma_vang', $validated)) {
            $vangDauTu->ma_vang = $validated['ma_vang'];
        }
        if (array_key_exists('trang_thai', $validated)) {
            $vangDauTu->trang_thai = (int) $validated['trang_thai'];
        }
        $vangDauTu->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật sản phẩm vàng đầu tư thành công',
        ]);
    }
    public function destroyVangDauTu(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required','integer','exists:san_pham_vang,id'],
        ]);
        $item = VangDauTu::findOrFail($validated['id']);
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xoá sản phẩm vàng đầu tư thành công',
        ]);
    }
    public function giaVang(Request $request)
    {
        $idVang = (string) $request->input('id_vang', '');
        $dateFrom = (string) $request->input('date_from', '');
        $dateTo = (string) $request->input('date_to', '');

        $giaVang = GiaVang::query()
            ->when($idVang !== '', function ($query) use ($idVang) {
                $query->where('id_vang', (int) $idVang);
            })
            ->when($dateFrom !== '', function ($query) use ($dateFrom) {
                $query->whereDate('thoi_gian', '>=', $dateFrom);
            })
            ->when($dateTo !== '', function ($query) use ($dateTo) {
                $query->whereDate('thoi_gian', '<=', $dateTo);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        $dsVang = VangDauTu::orderBy('ten_vang')->get();
        $mapVang = $dsVang->keyBy('id');
        return view('admin.gia-vang', compact('giaVang', 'dsVang', 'mapVang', 'idVang', 'dateFrom', 'dateTo'));
    }
    public function storeGiaVang(Request $request)
    {
        $validated = $request->validate([
            'id_vang' => ['required','integer','exists:san_pham_vang,id'],
            'gia_mua' => ['required','numeric','min:0'],
            'gia_ban' => ['required','numeric','min:0'],
            'thoi_gian' => ['required','date'],
            'trang_thai' => ['required','integer','in:0,1'],
            'ghi_chu' => ['nullable','string','max:255'],
        ]);
        $giaVang = GiaVang::create([
            'id_vang' => $validated['id_vang'],
            'gia_mua' => $validated['gia_mua'],
            'gia_ban' => $validated['gia_ban'],
            'thoi_gian' => $validated['thoi_gian'],
            'trang_thai' => $validated['trang_thai'],
            'ghi_chu' => $validated['ghi_chu']??'',
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Thêm giá vàng thành công',
        ]);
    }
    public function updateGiaVang(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required','integer','exists:gia_vang,id'],
            'id_vang' => ['nullable','integer','exists:san_pham_vang,id'],
            'gia_mua' => ['nullable','numeric','min:0'],
            'gia_ban' => ['nullable','numeric','min:0'],
            'thoi_gian' => ['nullable','date'],
            'trang_thai' => ['nullable','integer','in:0,1'],
            'ghi_chu' => ['nullable','string','max:255'],
        ]);
        $giaVang = GiaVang::findOrFail($validated['id']);
        if (array_key_exists('id_vang', $validated)) {
            $giaVang->id_vang = $validated['id_vang'];
        }
        if (array_key_exists('gia_mua', $validated)) {
            $giaVang->gia_mua = $validated['gia_mua'];
        }
        if (array_key_exists('gia_ban', $validated)) {
            $giaVang->gia_ban = $validated['gia_ban'];
        }
        if (array_key_exists('thoi_gian', $validated)) {
            $giaVang->thoi_gian = $validated['thoi_gian'];
        }
        if (array_key_exists('trang_thai', $validated)) {
            $giaVang->trang_thai = (int) $validated['trang_thai'];
        }
        if (array_key_exists('ghi_chu', $validated)) {
            $giaVang->ghi_chu = $validated['ghi_chu'];
        }
        $giaVang->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật giá vàng thành công',
        ]);
    }
    public function destroyGiaVang(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required','integer','exists:gia_vang,id'],
        ]);
        $item = GiaVang::findOrFail($validated['id']);
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xoá giá vàng thành công',
        ]);
    }
    public function lichSuDauTu(Request $request)
    {
        $dauTu = TietKiem::with(['user', 'sanPham'])->withCount('chiTietDauTu')->orderByDesc('id')->paginate(10);
        return view('admin.lich-su-tiet-kiem', compact('dauTu'));
    }

    public function updateTietKiemStatus(Request $request)
    {
        try {
            // Log bắt đầu quá trình cập nhật trạng thái
            Log::info('Bắt đầu cập nhật trạng thái tiết kiệm', [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip()
            ]);

            $validated = $request->validate([
                'id' => 'required|integer',
                'trang_thai' => 'required|integer|in:0,1,2,3'
            ]);

            // Log validation thành công
            Log::info('Validation dữ liệu thành công', [
                'validated_data' => $validated,
                'user_id' => auth()->id()
            ]);

            $tietKiem = TietKiem::findOrFail($validated['id']);
            $oldStatus = $tietKiem->trang_thai;
            
            // Log thông tin bản ghi trước khi cập nhật
            Log::info('Thông tin bản ghi tiết kiệm trước khi cập nhật', [
                'tiet_kiem_id' => $tietKiem->id,
                'user_id' => $tietKiem->user_id,
                'old_status' => $oldStatus,
                'new_status' => $validated['trang_thai'],
                'so_tien' => $tietKiem->so_tien,
                'hoa_hong' => $tietKiem->hoa_hong
            ]);

            $tietKiem->trang_thai = $validated['trang_thai'];
            $tietKiem->save();

            // Log cập nhật trạng thái thành công
            Log::info('Cập nhật trạng thái tiết kiệm thành công', [
                'tiet_kiem_id' => $tietKiem->id,
                'old_status' => $oldStatus,
                'new_status' => $tietKiem->trang_thai,
                'user_id' => auth()->id()
            ]);

            // Nếu trạng thái được đổi thành 2 (hoàn thành), cộng tiền vào số dư
            if ($validated['trang_thai'] == 2 && $oldStatus != 2) {
                // Log bắt đầu quá trình hoàn thành tiết kiệm
                Log::info('Bắt đầu quá trình hoàn thành tiết kiệm', [
                    'tiet_kiem_id' => $tietKiem->id,
                    'user_id' => $tietKiem->user_id,
                    'so_tien' => $tietKiem->so_tien,
                    'hoa_hong' => $tietKiem->hoa_hong
                ]);

                $user = $tietKiem->user;
                if ($user && $user->profile) {
                    $soTienCong = $tietKiem->so_tien + $tietKiem->hoa_hong;
                    $soDuCu = $user->profile->so_du ?? 0;
                    $soDuMoi = $soDuCu + $soTienCong;
                    
                    // Log thông tin số dư trước khi cập nhật
                    Log::info('Thông tin số dư trước khi cập nhật', [
                        'user_id' => $user->id,
                        'so_du_cu' => $soDuCu,
                        'so_tien_cong' => $soTienCong,
                        'so_du_moi' => $soDuMoi,
                        'tiet_kiem_id' => $tietKiem->id
                    ]);

                    $user->profile->so_du = $soDuMoi;
                    $user->profile->save();

                    // Log cập nhật số dư thành công
                    Log::info('Cập nhật số dư tài khoản thành công', [
                        'user_id' => $user->id,
                        'so_du_moi' => $user->profile->so_du,
                        'tiet_kiem_id' => $tietKiem->id,
                        'admin_id' => auth()->id()
                    ]);

                    // Tạo thông báo cho người dùng
                    $thongBao = ThongBao::create([
                        'user_id' => $user->id,
                        'tieu_de' => 'Tiết kiệm hoàn thành',
                        'noi_dung' => "Tài khoản tiết kiệm của bạn đã hoàn thành. Số tiền {$soTienCong} VNĐ đã được cộng vào số dư tài khoản.",
                        'trang_thai' => 0
                    ]);

                    // Log tạo thông báo thành công
                    Log::info('Tạo thông báo hoàn thành tiết kiệm thành công', [
                        'thong_bao_id' => $thongBao->id,
                        'user_id' => $user->id,
                        'tiet_kiem_id' => $tietKiem->id,
                        'so_tien_cong' => $soTienCong
                    ]);
                } else {
                    // Log lỗi không tìm thấy user hoặc profile
                    Log::warning('Không tìm thấy user hoặc profile khi hoàn thành tiết kiệm', [
                        'tiet_kiem_id' => $tietKiem->id,
                        'user_id' => $tietKiem->user_id,
                        'user_exists' => $user ? true : false,
                        'profile_exists' => $user && $user->profile ? true : false
                    ]);
                }
            }

            // Log phản hồi thành công
            Log::info('Cập nhật trạng thái tiết kiệm hoàn tất thành công', [
                'tiet_kiem_id' => $tietKiem->id,
                'old_status' => $oldStatus,
                'new_status' => $tietKiem->trang_thai,
                'admin_id' => auth()->id(),
                'user_id' => $tietKiem->user_id,
                'is_completed' => $validated['trang_thai'] == 2
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công!'
            ]);

        } catch (ValidationException $e) {
            // Log lỗi validation
            Log::warning('Lỗi validation khi cập nhật trạng thái tiết kiệm', [
                'request_data' => $request->all(),
                'validation_errors' => $e->errors(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log lỗi hệ thống
            Log::error('Lỗi hệ thống khi cập nhật trạng thái tiết kiệm', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái!'
            ], 500);
        }
    }
}