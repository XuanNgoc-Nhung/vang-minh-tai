<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\NganHangNapTien;
use App\Models\ThongBao;
use App\Models\NapRut;
use App\Models\SanPhamTietKiem;
use App\Models\TietKiem;
use App\Models\GiaVang;
use App\Models\VangDauTu;
use App\Models\DauTuVang;
use App\Models\DauTu;
use App\Models\CauHinh;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        // Tính tổng số tiền đã nạp (chỉ tính những giao dịch đã được duyệt)
        $tongTienDaNap = NapRut::where('user_id', $user->id)
            ->where('loai', 'nap')
            ->where('trang_thai', 1) // 1 = đã duyệt
            ->sum('so_tien');
        
        // Xác định cấp bậc theo tổng số tiền đã nạp
        if ($tongTienDaNap < 100000000) {
            $capBacNap = 'Vàng';
        } elseif ($tongTienDaNap <= 300000000) {
            $capBacNap = 'Bạch kim';
        } elseif ($tongTienDaNap <= 500000000) {
            $capBacNap = 'Kim cương';
        } elseif ($tongTienDaNap <= 1000000000) {
            $capBacNap = 'VIP';
        } else {
            $capBacNap = 'VIP +';
        }
        
        return view('user.dashboard.profiles', compact('user', 'profile', 'tongTienDaNap', 'capBacNap'));
    }
    public function baoMat()
    {
        Log::info('UserDashboardController@baoMat: Bắt đầu hiển thị trang bảo mật', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        
        try {
            $view = view('user.dashboard.bao-mat');
            Log::info('UserDashboardController@baoMat: Hiển thị trang bảo mật thành công', [
                'user_id' => Auth::id()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@baoMat: Lỗi khi hiển thị trang bảo mật', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function changePassword(Request $request)
    {
        Log::info('UserDashboardController@changePassword: Bắt đầu thay đổi mật khẩu đăng nhập', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@changePassword: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['current_password', 'new_password', 'new_password_confirmation'])
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        Log::info('UserDashboardController@changePassword: Bắt đầu kiểm tra mật khẩu hiện tại', [
            'user_id' => $user->id
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('UserDashboardController@changePassword: Mật khẩu hiện tại không đúng', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => ['current_password' => ['Mật khẩu hiện tại không đúng']]
            ]);
        }

        Log::info('UserDashboardController@changePassword: Mật khẩu hiện tại đúng, bắt đầu cập nhật mật khẩu mới', [
            'user_id' => $user->id
        ]);

        try {
            // Cập nhật mật khẩu mới
            $user->password = Hash::make($request->new_password);
            $user->save();

            Log::info('UserDashboardController@changePassword: Cập nhật mật khẩu thành công', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được cập nhật thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@changePassword: Lỗi khi cập nhật mật khẩu', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu'
            ], 500);
        }
    }

    public function changeWithdrawalPassword(Request $request)
    {
        Log::info('UserDashboardController@changeWithdrawalPassword: Bắt đầu thay đổi mật khẩu rút tiền', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'login_password' => 'required',
            'new_withdrawal_password' => 'required|min:4|confirmed',
        ], [
            'login_password.required' => 'Vui lòng nhập mật khẩu đăng nhập',
            'new_withdrawal_password.required' => 'Vui lòng nhập mật khẩu rút tiền mới',
            'new_withdrawal_password.min' => 'Mật khẩu rút tiền phải có ít nhất 4 ký tự',
            'new_withdrawal_password.confirmed' => 'Xác nhận mật khẩu rút tiền không khớp',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@changeWithdrawalPassword: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['login_password', 'new_withdrawal_password', 'new_withdrawal_password_confirmation'])
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        Log::info('UserDashboardController@changeWithdrawalPassword: Bắt đầu kiểm tra mật khẩu đăng nhập', [
            'user_id' => $user->id
        ]);

        // Kiểm tra mật khẩu đăng nhập
        if (!Hash::check($request->login_password, $user->password)) {
            Log::warning('UserDashboardController@changeWithdrawalPassword: Mật khẩu đăng nhập không đúng', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => ['login_password' => ['Mật khẩu đăng nhập không đúng']]
            ]);
        }

        Log::info('UserDashboardController@changeWithdrawalPassword: Mật khẩu đăng nhập đúng, bắt đầu cập nhật mật khẩu rút tiền', [
            'user_id' => $user->id
        ]);

        try {
            // Lấy hoặc tạo profile cho user
            $profile = $user->profile;
            if (!$profile) {
                $profile = $user->profile()->create([]);
            }
            
            // Cập nhật mật khẩu rút tiền vào profile (không mã hóa)
            $profile->mat_khau_rut_tien = $request->new_withdrawal_password;
            $profile->save();

            Log::info('UserDashboardController@changeWithdrawalPassword: Cập nhật mật khẩu rút tiền thành công', [
                'user_id' => $user->id,
                'profile_id' => $profile->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu rút tiền đã được cập nhật thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@changeWithdrawalPassword: Lỗi khi cập nhật mật khẩu rút tiền', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu rút tiền'
            ], 500);
        }
    }
    public function nganHang(){
        Log::info('UserDashboardController@nganHang: Bắt đầu hiển thị trang ngân hàng', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        
        try {
            $user = Auth::user();
            $view = view('user.dashboard.ngan-hang', compact('user'));
            Log::info('UserDashboardController@nganHang: Hiển thị trang ngân hàng thành công', [
                'user_id' => Auth::id()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@nganHang: Lỗi khi hiển thị trang ngân hàng', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateBankInfo(Request $request)
    {
        Log::info('UserDashboardController@updateBankInfo: Bắt đầu cập nhật thông tin ngân hàng', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
            'password' => 'required',
        ], [
            'bank_name.required' => 'Vui lòng chọn ngân hàng',
            'bank_name.max' => 'Tên ngân hàng không được vượt quá 255 ký tự',
            'account_number.required' => 'Vui lòng nhập số tài khoản',
            'account_number.max' => 'Số tài khoản không được vượt quá 50 ký tự',
            'account_holder.required' => 'Vui lòng nhập tên chủ tài khoản',
            'account_holder.max' => 'Tên chủ tài khoản không được vượt quá 255 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu để xác nhận',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@updateBankInfo: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['_token', '_method', 'password'])
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        Log::info('UserDashboardController@updateBankInfo: Bắt đầu cập nhật thông tin ngân hàng', [
            'user_id' => $user->id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder
        ]);

        // Kiểm tra mật khẩu đăng nhập trước khi cho phép cập nhật
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('UserDashboardController@updateBankInfo: Mật khẩu xác nhận không đúng', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không đúng',
                'errors' => ['password' => ['Mật khẩu không đúng']]
            ]);
        }

        try {
            // Lấy hoặc tạo profile cho user
            $profile = $user->profile;
            if (!$profile) {
                $profile = $user->profile()->create([]);
                Log::info('UserDashboardController@updateBankInfo: Tạo profile mới cho user', [
                    'user_id' => $user->id,
                    'profile_id' => $profile->id
                ]);
            }
            
            // Cập nhật thông tin ngân hàng
            $profile->ngan_hang = $request->bank_name;
            $profile->so_tai_khoan = $request->account_number;
            $profile->chu_tai_khoan = $request->account_holder;
            $profile->save();

            Log::info('UserDashboardController@updateBankInfo: Cập nhật thông tin ngân hàng thành công', [
                'user_id' => $user->id,
                'profile_id' => $profile->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thông tin ngân hàng đã được cập nhật thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@updateBankInfo: Lỗi khi cập nhật thông tin ngân hàng', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin ngân hàng'
            ], 500);
        }
    }
    public function napTien(){
        Log::info('UserDashboardController@napTien: Bắt đầu hiển thị trang nạp tiền', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]); 
        try {
            $user = Auth::user();
            $banks = NganHangNapTien::query()
                ->where('trang_thai', 1)
                ->orderBy('ten_ngan_hang')
                ->get();
            $cauHinh = CauHinh::first();
            $cauHinh = $cauHinh ? $cauHinh : new CauHinh();
            $view = view('user.dashboard.nap-tien', compact('user', 'banks', 'cauHinh'));
            Log::info('UserDashboardController@napTien: Hiển thị trang nạp tiền thành công', [
                'user_id' => Auth::id()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@napTien: Lỗi khi hiển thị trang nạp tiền', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createNapTienRequest(Request $request)
    {
        Log::info('UserDashboardController@createNapTienRequest: Bắt đầu tạo yêu cầu nạp tiền', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'so_tien' => 'required|numeric|min:10000',
            'ngan_hang' => 'required|string|max:255',
            'so_tai_khoan' => 'required|string|max:50',
            'chu_tai_khoan' => 'required|string|max:255',
            'noi_dung' => 'required|string|max:255',
        ], [
            'so_tien.required' => 'Vui lòng nhập số tiền',
            'so_tien.numeric' => 'Số tiền phải là số',
            'so_tien.min' => 'Số tiền tối thiểu là 10,000 VND',
            'ngan_hang.required' => 'Vui lòng chọn ngân hàng',
            'ngan_hang.max' => 'Tên ngân hàng không được vượt quá 255 ký tự',
            'so_tai_khoan.required' => 'Vui lòng nhập số tài khoản',
            'so_tai_khoan.max' => 'Số tài khoản không được vượt quá 50 ký tự',
            'chu_tai_khoan.required' => 'Vui lòng nhập tên chủ tài khoản',
            'chu_tai_khoan.max' => 'Tên chủ tài khoản không được vượt quá 255 ký tự',
            'noi_dung.required' => 'Vui lòng nhập nội dung chuyển khoản',
            'noi_dung.max' => 'Nội dung chuyển khoản không được vượt quá 255 ký tự',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@createNapTienRequest: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['_token'])
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        Log::info('UserDashboardController@createNapTienRequest: Bắt đầu tạo yêu cầu nạp tiền', [
            'user_id' => $user->id,
            'so_tien' => $request->so_tien,
            'ngan_hang' => $request->ngan_hang,
            'so_tai_khoan' => $request->so_tai_khoan,
            'chu_tai_khoan' => $request->chu_tai_khoan,
            'noi_dung' => $request->noi_dung
        ]);

        // Kiểm tra số lượng yêu cầu nạp chưa thành công
        $pendingDepositCount = NapRut::where('user_id', $user->id)
            ->where('loai', 'nap')
            ->where('trang_thai', 0) // 0: chờ xử lý
            ->count();

        if ($pendingDepositCount >= 3) {
            Log::warning('UserDashboardController@createNapTienRequest: User có quá nhiều yêu cầu nạp chưa thành công', [
                'user_id' => $user->id,
                'pending_count' => $pendingDepositCount,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã có 3 yêu cầu nạp tiền chưa được xử lý. Vui lòng hoàn thành giao dịch hoặc liên hệ admin để được hỗ trợ.'
            ]);
        }

        try {
            // Tạo yêu cầu nạp tiền
            $napRut = NapRut::create([
                'user_id' => $user->id,
                'loai' => 'nap', // Loại nạp tiền
                'so_tien' => $request->so_tien,
                'ngan_hang' => $request->ngan_hang,
                'so_tai_khoan' => $request->so_tai_khoan,
                'chu_tai_khoan' => $request->chu_tai_khoan,
                'noi_dung' => $request->noi_dung,
                'trang_thai' => 0 // 0: chờ xử lý, 1: thành công, 2: từ chối
            ]);

            Log::info('UserDashboardController@createNapTienRequest: Tạo yêu cầu nạp tiền thành công', [
                'user_id' => $user->id,
                'nap_rut_id' => $napRut->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu nạp tiền đã được tạo thành công!',
                'data' => [
                    'id' => $napRut->id,
                    'so_tien' => $napRut->so_tien,
                    'ngan_hang' => $napRut->ngan_hang,
                    'so_tai_khoan' => $napRut->so_tai_khoan,
                    'chu_tai_khoan' => $napRut->chu_tai_khoan,
                    'noi_dung' => $napRut->noi_dung,
                    'trang_thai' => $napRut->trang_thai,
                    'trang_thai_text' => $napRut->trang_thai == 0 ? 'Chờ xử lý' : ($napRut->trang_thai == 1 ? 'Thành công' : 'Từ chối'),
                    'created_at' => $napRut->created_at->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@createNapTienRequest: Lỗi khi tạo yêu cầu nạp tiền', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo yêu cầu nạp tiền'
            ], 500);
        }
    }

    public function lichSuNapRut(Request $request)
    {
        Log::info('UserDashboardController@lichSuNapRut: Bắt đầu hiển thị trang lịch sử nạp rút', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        
        try {
            $user = Auth::user();
            $query = NapRut::where('user_id', $user->id);

            // Bộ lọc theo loại giao dịch: 'nap' hoặc 'rut'
            if ($request->filled('loai') && in_array($request->loai, ['nap', 'rut'])) {
                $query->where('loai', $request->loai);
            }

            // Bộ lọc theo nội dung (tìm kiếm mơ hồ theo nội dung, số TK, ngân hàng, mã GD)
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('noi_dung', 'like', "%{$search}%")
                      ->orWhere('ngan_hang', 'like', "%{$search}%")
                      ->orWhere('so_tai_khoan', 'like', "%{$search}%")
                      ->orWhere('id', $search);
                });
            }

            $napRutHistory = $query
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->appends($request->only(['loai', 'search']));
            
            $view = view('user.dashboard.lich-su-nap-rut', compact('user', 'napRutHistory'));
            Log::info('UserDashboardController@lichSuNapRut: Hiển thị trang lịch sử nạp rút thành công', [
                'user_id' => Auth::id(),
                'total_records' => $napRutHistory->total()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@lichSuNapRut: Lỗi khi hiển thị trang lịch sử nạp rút', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function rutTien(){
        Log::info('UserDashboardController@rutTien: Bắt đầu hiển thị trang rút tiền', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        try {
            $user = Auth::user();
            $cauHinh = CauHinh::first();
            $view = view('user.dashboard.rut-tien', compact('user', 'cauHinh'));
            Log::info('UserDashboardController@rutTien: Hiển thị trang rút tiền thành công', [
                'user_id' => Auth::id()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@rutTien: Lỗi khi hiển thị trang rút tiền', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function thongBao(Request $request){
        Log::info('UserDashboardController@thongBao: Bắt đầu hiển thị trang thông báo', [
            'user_id' => Auth::id(),
            'q' => $request->q
        ]);

        try {
            $query = ThongBao::query()->where('trang_thai', 1);
            if ($request->filled('q')) {
                $keyword = trim($request->q);
                $query->where(function($q) use ($keyword) {
                    $q->where('tieu_de', 'like', "%{$keyword}%")
                      ->orWhere('noi_dung', 'like', "%{$keyword}%");
                });
            }

            $thongBao = $query
                ->orderByDesc('id')
                ->paginate(10)
                ->appends($request->only('q'));

            $view = view('user.dashboard.thong-bao', compact('thongBao'));
            Log::info('UserDashboardController@thongBao: Hiển thị trang thông báo thành công', [
                'user_id' => Auth::id(),
                'total_records' => $thongBao->total()
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@thongBao: Lỗi khi hiển thị trang thông báo', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function createRutTienRequest(Request $request)
    {
        Log::info('UserDashboardController@createRutTienRequest: Bắt đầu tạo yêu cầu rút tiền', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $validator = Validator::make($request->all(), [
            'so_tien' => 'required|numeric|min:50000',
            'mat_khau_rut_tien' => 'required|string',
        ], [
            'so_tien.required' => 'Vui lòng nhập số tiền',
            'so_tien.numeric' => 'Số tiền phải là số',
            'so_tien.min' => 'Số tiền tối thiểu là 50,000 VND',
            'mat_khau_rut_tien.required' => 'Vui lòng nhập mật khẩu rút tiền',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@createRutTienRequest: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['_token', 'mat_khau_rut_tien'])
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $profile = $user->profile;
        
        // Kiểm tra profile và thông tin ngân hàng
        if (!$profile || !$profile->ngan_hang || !$profile->so_tai_khoan || !$profile->chu_tai_khoan) {
            Log::warning('UserDashboardController@createRutTienRequest: User chưa cập nhật thông tin ngân hàng', [
                'user_id' => $user->id,
                'has_profile' => $profile ? true : false,
                'has_bank_info' => $profile && $profile->ngan_hang && $profile->so_tai_khoan && $profile->chu_tai_khoan
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cập nhật thông tin ngân hàng trước khi rút tiền'
            ]);
        }

        // Kiểm tra mật khẩu rút tiền
        if (!$profile->mat_khau_rut_tien || $profile->mat_khau_rut_tien !== $request->mat_khau_rut_tien) {
            Log::warning('UserDashboardController@createRutTienRequest: Mật khẩu rút tiền không đúng', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu rút tiền không đúng'
            ]);
        }

        // Kiểm tra số dư
        if ($profile->so_du < $request->so_tien) {
            Log::warning('UserDashboardController@createRutTienRequest: Số dư không đủ', [
                'user_id' => $user->id,
                'current_balance' => $profile->so_du,
                'requested_amount' => $request->so_tien
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Số dư không đủ để thực hiện giao dịch'
            ]);
        }

        Log::info('UserDashboardController@createRutTienRequest: Bắt đầu tạo yêu cầu rút tiền', [
            'user_id' => $user->id,
            'so_tien' => $request->so_tien,
            'ngan_hang' => $profile->ngan_hang,
            'so_tai_khoan' => $profile->so_tai_khoan,
            'chu_tai_khoan' => $profile->chu_tai_khoan,
            'current_balance' => $profile->so_du
        ]);

        try {
            // Trừ tiền ngay lập tức khi tạo yêu cầu rút tiền
            $profile->so_du = $profile->so_du - $request->so_tien;
            $profile->save();

            // Tạo yêu cầu rút tiền
            $napRut = NapRut::create([
                'user_id' => $user->id,
                'loai' => 'rut', // Loại rút tiền
                'so_tien' => $request->so_tien,
                'ngan_hang' => $profile->ngan_hang,
                'so_tai_khoan' => $profile->so_tai_khoan,
                'chu_tai_khoan' => $profile->chu_tai_khoan,
                'noi_dung' => $user->phone,
                'trang_thai' => 0 // 0: chờ xử lý, 1: thành công, 2: từ chối
            ]);

            Log::info('UserDashboardController@createRutTienRequest: Tạo yêu cầu rút tiền thành công và đã trừ tiền', [
                'user_id' => $user->id,
                'nap_rut_id' => $napRut->id,
                'so_tien_rut' => $request->so_tien,
                'so_du_con_lai' => $profile->so_du,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu rút tiền đã được tạo thành công!',
                'data' => [
                    'id' => $napRut->id,
                    'so_tien' => $napRut->so_tien,
                    'ngan_hang' => $napRut->ngan_hang,
                    'so_tai_khoan' => $napRut->so_tai_khoan,
                    'chu_tai_khoan' => $napRut->chu_tai_khoan,
                    'noi_dung' => $napRut->noi_dung,
                    'trang_thai' => $napRut->trang_thai,
                    'trang_thai_text' => $napRut->trang_thai == 0 ? 'Chờ xử lý' : ($napRut->trang_thai == 1 ? 'Thành công' : 'Từ chối'),
                    'created_at' => $napRut->created_at->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@createRutTienRequest: Lỗi khi tạo yêu cầu rút tiền', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo yêu cầu rút tiền'
            ], 500);
        }
    }
    public function kyc(){
        Log::info('UserDashboardController@kyc: Bắt đầu hiển thị trang kyc', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        
        $user = Auth::user();
        $profile = $user->profile;
        
        return view('user.dashboard.kyc', compact('user', 'profile'));
    }

    public function createKycRequest(Request $request)
    {
        Log::info('UserDashboardController@createKycRequest: Bắt đầu tạo yêu cầu KYC', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'is_ajax' => $request->ajax(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $user = Auth::user();
        $profile = $user->profile;
        
        // Check if user has existing KYC images
        $hasExistingPortrait = $profile && $profile->anh_chan_dung;
        $hasExistingIdFront = $profile && $profile->anh_mat_truoc;
        $hasExistingIdBack = $profile && $profile->anh_mat_sau;
        
        // Build validation rules based on existing images
        $rules = [
            'login_password' => 'required',
        ];
        
        $messages = [
            'login_password.required' => 'Vui lòng nhập mật khẩu đăng nhập',
        ];
        
        // Only require images if they don't exist
        if (!$hasExistingPortrait) {
            $rules['portrait_photo'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['portrait_photo.required'] = 'Vui lòng tải lên ảnh chân dung';
            $messages['portrait_photo.image'] = 'Ảnh chân dung phải là file hình ảnh';
            $messages['portrait_photo.mimes'] = 'Ảnh chân dung phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['portrait_photo.max'] = 'Ảnh chân dung không được vượt quá 5MB';
        } else {
            $rules['portrait_photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['portrait_photo.image'] = 'Ảnh chân dung phải là file hình ảnh';
            $messages['portrait_photo.mimes'] = 'Ảnh chân dung phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['portrait_photo.max'] = 'Ảnh chân dung không được vượt quá 5MB';
        }
        
        if (!$hasExistingIdFront) {
            $rules['id_front_photo'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['id_front_photo.required'] = 'Vui lòng tải lên ảnh giấy tờ mặt trước';
            $messages['id_front_photo.image'] = 'Ảnh giấy tờ mặt trước phải là file hình ảnh';
            $messages['id_front_photo.mimes'] = 'Ảnh giấy tờ mặt trước phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['id_front_photo.max'] = 'Ảnh giấy tờ mặt trước không được vượt quá 5MB';
        } else {
            $rules['id_front_photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['id_front_photo.image'] = 'Ảnh giấy tờ mặt trước phải là file hình ảnh';
            $messages['id_front_photo.mimes'] = 'Ảnh giấy tờ mặt trước phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['id_front_photo.max'] = 'Ảnh giấy tờ mặt trước không được vượt quá 5MB';
        }
        
        if (!$hasExistingIdBack) {
            $rules['id_back_photo'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['id_back_photo.required'] = 'Vui lòng tải lên ảnh giấy tờ mặt sau';
            $messages['id_back_photo.image'] = 'Ảnh giấy tờ mặt sau phải là file hình ảnh';
            $messages['id_back_photo.mimes'] = 'Ảnh giấy tờ mặt sau phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['id_back_photo.max'] = 'Ảnh giấy tờ mặt sau không được vượt quá 5MB';
        } else {
            $rules['id_back_photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $messages['id_back_photo.image'] = 'Ảnh giấy tờ mặt sau phải là file hình ảnh';
            $messages['id_back_photo.mimes'] = 'Ảnh giấy tờ mặt sau phải có định dạng jpeg, png, jpg, gif hoặc webp';
            $messages['id_back_photo.max'] = 'Ảnh giấy tờ mặt sau không được vượt quá 5MB';
        }
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@createKycRequest: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->except(['portrait_photo', 'id_front_photo', 'id_back_photo'])
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        Log::info('UserDashboardController@createKycRequest: Bắt đầu kiểm tra mật khẩu đăng nhập', [
            'user_id' => $user->id
        ]);

        // Kiểm tra mật khẩu đăng nhập
        if (!Hash::check($request->login_password, $user->password)) {
            Log::warning('UserDashboardController@createKycRequest: Mật khẩu đăng nhập không đúng', [
                'user_id' => $user->id,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => ['login_password' => ['Mật khẩu đăng nhập không đúng']]
            ]);
        }

        Log::info('UserDashboardController@createKycRequest: Mật khẩu đăng nhập đúng, bắt đầu xử lý yêu cầu KYC', [
            'user_id' => $user->id
        ]);

        try {
            // Lấy hoặc tạo profile cho user
            $profile = $user->profile;
            if (!$profile) {
                $profile = $user->profile()->create([]);
                Log::info('UserDashboardController@createKycRequest: Tạo profile mới cho user', [
                    'user_id' => $user->id,
                    'profile_id' => $profile->id
                ]);
            }

            // Xử lý upload ảnh vào public/uploads/kyc/
            // Chỉ cập nhật ảnh mới nếu có file được upload
            if ($request->hasFile('portrait_photo')) {
                $portraitFile = $request->file('portrait_photo');
                $portraitName = 'uploads/kyc/' . 'portrait_' . time() . '_' . $user->id . '.' . $portraitFile->getClientOriginalExtension();
                $portraitFile->move(public_path('uploads/kyc'), $portraitName);
                $profile->anh_chan_dung = $portraitName;
            }

            if ($request->hasFile('id_front_photo')) {
                $idFrontFile = $request->file('id_front_photo');
                $idFrontName = 'uploads/kyc/' . 'id_front_' . time() . '_' . $user->id . '.' . $idFrontFile->getClientOriginalExtension();
                $idFrontFile->move(public_path('uploads/kyc'), $idFrontName);
                $profile->anh_mat_truoc = $idFrontName;
            }

            if ($request->hasFile('id_back_photo')) {
                $idBackFile = $request->file('id_back_photo');
                $idBackName = 'uploads/kyc/' . 'id_back_' . time() . '_' . $user->id . '.' . $idBackFile->getClientOriginalExtension();
                $idBackFile->move(public_path('uploads/kyc'), $idBackName);
                $profile->anh_mat_sau = $idBackName;
            }

            // Lưu profile với hình ảnh KYC
            $profile->save();
            $user->save();

            Log::info('UserDashboardController@createKycRequest: Tạo yêu cầu KYC thành công', [
                'user_id' => $user->id,
                'profile_id' => $profile->id,
                'anh_chan_dung' => $profile->anh_chan_dung,
                'anh_mat_truoc' => $profile->anh_mat_truoc,
                'anh_mat_sau' => $profile->anh_mat_sau,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu xác thực danh tính đã được gửi thành công! Chúng tôi sẽ xem xét và phản hồi trong vòng 1-3 ngày làm việc.',
                'data' => [
                    'kyc_status' => 'pending'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@createKycRequest: Lỗi khi tạo yêu cầu KYC', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi yêu cầu xác thực danh tính'
            ], 500);
        }
    }
    public function tietKiem(\Illuminate\Http\Request $request){
        $search = trim((string) $request->get('search', ''));
        Log::info('UserDashboardController@dauTu: Bắt đầu hiển thị trang đầu tư', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'search' => $search !== '' ? $search : null
        ]);

        $query = SanPhamTietKiem::query()->where('trang_thai', 1);
        if ($search !== '') {
            $query->where('ten', 'like', '%'.$search.'%');
        }

        $sanPhamDauTu = $query->orderByDesc('id')->get();
        return view('user.dashboard.tiet-kiem', compact('sanPhamDauTu'));
    }
    public function duAnTietKiemCuaToi(){
        Log::info('UserDashboardController@duAnCuaToi: Bắt đầu hiển thị trang dự án của tôi', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        $user = Auth::user();
        $duAns = TietKiem::where('user_id', $user->id)->orderByDesc('id')->paginate(10);
        return view('user.dashboard.du-an-cua-toi', compact('duAns'));
    }
    public function chiTietTietKiem($slug){
        Log::info('UserDashboardController@chiTietTietKiem: Bắt đầu hiển thị trang chi tiết dự án', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'slug' => $slug
        ]);
        $sanPhamDauTu = SanPhamTietKiem::where('slug', $slug)->first();
        if (!$sanPhamDauTu) {
            return redirect()->route('dashboard.tiet-kiem')->with('error', 'Dự án không tồn tại');
        }
        if($sanPhamDauTu->trang_thai != 1){
            return redirect()->route('dashboard.tiet-kiem')->with('error', 'Dự án đã không hoạt động');
        }
        $user = Auth::user();
        $profile = $user ? $user->profile : null;
        
        // Kiểm tra xem user đã có mật khẩu rút tiền chưa
        $hasWithdrawalPassword = $profile && !empty($profile->mat_khau_rut_tien);
        
        return view('user.dashboard.chi-tiet-tiet-kiem', compact('sanPhamDauTu', 'user', 'profile', 'hasWithdrawalPassword'));
    }

    public function createTietKiem(Request $request)
    {
        Log::info('UserDashboardController@createDauTu: Bắt đầu xử lý đầu tư', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'input_data' => $request->except(['mat_khau_rut_tien'])
        ]);

        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'required|exists:san_pham_dau_tu,id',
            'so_tien' => 'required|numeric|min:1',
            'ngay_thanh_toan' => 'required|date|after:ngay_bat_dau',
            'mat_khau_rut_tien' => 'required'
        ], [
            'san_pham_id.required' => 'Sản phẩm đầu tư không được để trống',
            'san_pham_id.exists' => 'Sản phẩm đầu tư không tồn tại',
            'so_tien.required' => 'Số tiền đầu tư không được để trống',
            'so_tien.numeric' => 'Số tiền đầu tư phải là số',
            'so_tien.min' => 'Số tiền đầu tư phải lớn hơn 0',
            'ngay_thanh_toan.required' => 'Ngày thanh toán không được để trống',
            'ngay_thanh_toan.date' => 'Ngày thanh toán không hợp lệ',
            'ngay_thanh_toan.after' => 'Ngày thanh toán phải sau ngày bắt đầu',
            'mat_khau_rut_tien.required' => 'Mật khẩu rút tiền không được để trống'
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@createTietKiem: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'error_code' => 'VALIDATION_ERROR',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $profile = $user->profile;
            
            // Kiểm tra mật khẩu rút tiền
            if ($request->mat_khau_rut_tien != $profile->mat_khau_rut_tien) {
                Log::warning('UserDashboardController@createTietKiem: Mật khẩu rút tiền không đúng', [
                    'user_id' => $user->id
                ]);
                
                return response()->json([
                    'success' => false,
                    'error_code' => 'INVALID_WITHDRAWAL_PASSWORD',
                    'message' => 'Mật khẩu rút tiền không đúng'
                ]);
            }

            // Lấy thông tin sản phẩm đầu tư
            $sanPham = SanPhamTietKiem::find($request->san_pham_id);
            if (!$sanPham || $sanPham->trang_thai != 1) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'PRODUCT_NOT_AVAILABLE',
                    'message' => 'Sản phẩm đầu tư không khả dụng'
                ]);
            }

            // Kiểm tra số dư
            if ($profile->so_du < $request->so_tien) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'INSUFFICIENT_BALANCE',
                    'message' => 'Số dư không đủ để đầu tư',
                    'data' => [
                        'current_balance' => $profile->so_du,
                        'required_amount' => $request->so_tien,
                        'shortage' => $request->so_tien - $profile->so_du
                    ]
                ]);
            }

            // Kiểm tra giới hạn vốn
            if ($sanPham->von_toi_thieu && $request->so_tien < $sanPham->von_toi_thieu) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'MINIMUM_AMOUNT_NOT_MET',
                    'message' => 'Số tiền đầu tư phải tối thiểu ' . number_format($sanPham->von_toi_thieu) . ' VNĐ',
                    'data' => [
                        'minimum_amount' => $sanPham->von_toi_thieu,
                        'entered_amount' => $request->so_tien
                    ]
                ]);
            }

            if ($sanPham->von_toi_da && $request->so_tien > $sanPham->von_toi_da) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'MAXIMUM_AMOUNT_EXCEEDED',
                    'message' => 'Số tiền đầu tư không được vượt quá ' . number_format($sanPham->von_toi_da) . ' VNĐ',
                    'data' => [
                        'maximum_amount' => $sanPham->von_toi_da,
                        'entered_amount' => $request->so_tien
                    ]
                ]);
            }

            $dauTu = TietKiem::create([
                'user_id' => $user->id,
                'san_pham_id' => $request->san_pham_id,
                'so_chu_ky' => $sanPham->thoi_gian_mot_chu_ky,
                'so_tien' => $request->so_tien,
                'hoa_hong' => $request->hoa_hong,
                'trang_thai' => 1, // Trạng thái đang đầu tư
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addMonths($sanPham->thoi_gian_mot_chu_ky),
                'ghi_chu' => $request->ghi_chu??''
            ]);

            // Trừ số dư
            $profile->so_du -= $request->so_tien;
            $profile->save();

            Log::info('UserDashboardController@createDauTu: Đầu tư thành công', [
                'user_id' => $user->id,
                'dau_tu_id' => $dauTu->id,
                'so_tien' => $request->so_tien,
                'so_du_con_lai' => $profile->so_du
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đầu tư thành công!',
                'data' => [
                    'dau_tu_id' => $dauTu->id,
                    'so_tien' => $request->so_tien,
                    'hoa_hong' => $request->hoa_hong,
                    'so_du_con_lai' => $profile->so_du
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('UserDashboardController@createDauTu: Lỗi khi tạo đầu tư', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error_code' => 'SYSTEM_ERROR',
                'message' => 'Có lỗi xảy ra khi xử lý đầu tư. Vui lòng thử lại.'
            ], 500);
        }
    }
    public function dauTu(){
        Log::info('UserDashboardController@dauTu: Bắt đầu hiển thị trang đầu tư', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A'
        ]);
        
        try {
            // Lấy danh sách sản phẩm vàng
            $sanPhamVang = VangDauTu::where('trang_thai', 1)->get();
            
            // Lấy giá vàng mới nhất cho mỗi sản phẩm
            $giaVang = [];
            foreach ($sanPhamVang as $vang) {
                $giaMoiNhat = GiaVang::where('id_vang', $vang->id)
                    ->where('trang_thai', 1)
                    ->orderBy('thoi_gian', 'desc')
                    ->first();
                
                if ($giaMoiNhat) {
                    $giaVang[] = [
                        'san_pham' => $vang,
                        'gia_mua' => $giaMoiNhat->gia_mua,
                        'gia_ban' => $giaMoiNhat->gia_ban,
                        'thoi_gian' => $giaMoiNhat->thoi_gian,
                        'ghi_chu' => $giaMoiNhat->ghi_chu
                    ];
                }
            }
            
            $view = view('user.dashboard.dau-tu', compact('giaVang'));
            Log::info('UserDashboardController@dauTu: Hiển thị trang đầu tư thành công', [
                'user_id' => Auth::id(),
                'total_products' => count($giaVang)
            ]);
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@dauTu: Lỗi khi hiển thị trang đầu tư', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function chiTietDauTu($slug){
        Log::info('UserDashboardController@chiTietDauTu: Bắt đầu hiển thị trang chi tiết đầu tư vàng', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'slug' => $slug
        ]);
        
        try {
            // Lấy thông tin sản phẩm vàng
            $sanPhamVang = VangDauTu::where('ten_vang', $slug)
                ->where('trang_thai', 1)
                ->first();
                
            if (!$sanPhamVang) {
                Log::error('UserDashboardController@chiTietDauTu: Sản phẩm vàng không tồn tại', [
                    'user_id' => Auth::id(),
                    'slug' => $slug
                ]);     
                return redirect()->route('dashboard.dau-tu')->with('error', 'Sản phẩm vàng không tồn tại');
            }

            Log::info('UserDashboardController@chiTietDauTu: Sản phẩm vàng tồn tại', [
                'user_id' => Auth::id(),
                'slug' => $slug
            ]);
            // Lấy giá hiện tại
            $giaHienTai = GiaVang::where('id_vang', $sanPhamVang->id)
                ->where('trang_thai', 1)
                ->orderBy('thoi_gian', 'desc')
                ->first();
                
            Log::info('UserDashboardController@chiTietDauTu: Giá hiện tại', [
                'user_id' => Auth::id(),
                'slug' => $slug
            ]);
            // Lấy lịch sử giá 7 ngày qua
            $lichSuGia = GiaVang::where('id_vang', $sanPhamVang->id)
                ->where('trang_thai', 1)
                ->where('thoi_gian', '>=', now()->subDays(7))
                ->orderBy('thoi_gian', 'desc')
                ->get();
                
            Log::info('UserDashboardController@chiTietDauTu: Lịch sử giá', [
                'user_id' => Auth::id(),
                'slug' => $slug
            ]);
            // Chuẩn bị dữ liệu cho biểu đồ
            $chartLabels = [];
            $chartBuyPrices = [];
            $chartSellPrices = [];
            
            foreach ($lichSuGia->reverse() as $gia) {
                $chartLabels[] = \Carbon\Carbon::parse($gia->thoi_gian)->format('d/m H:i');
                $chartBuyPrices[] = $gia->gia_mua;
                $chartSellPrices[] = $gia->gia_ban;
            }
            
            // Nếu không có dữ liệu, tạo dữ liệu mẫu
            if (empty($chartLabels)) {
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->format('d/m H:i');
                    $chartBuyPrices[] = $giaHienTai ? $giaHienTai->gia_mua + rand(-50000, 50000) : 5000000;
                    $chartSellPrices[] = $giaHienTai ? $giaHienTai->gia_ban + rand(-50000, 50000) : 5100000;
                }
            }
            
            // Lấy thông tin user và profile
            $user = Auth::user();
            $profile = $user->profile;
            
            // Tính số vàng hiện có của user (tạm thời set = 0 vì chưa có bảng lưu trữ)
            $soVangHienCo = 0; // TODO: Implement gold holdings tracking
            
            $view = view('user.dashboard.chi-tiet-dau-tu', compact(
                'sanPhamVang', 
                'giaHienTai', 
                'lichSuGia', 
                'chartLabels', 
                'chartBuyPrices', 
                'chartSellPrices',
                'user',
                'profile',
                'soVangHienCo'
            ));
            
            Log::info('UserDashboardController@chiTietDauTu: Hiển thị trang chi tiết đầu tư vàng thành công', [
                'user_id' => Auth::id(),
                'san_pham_id' => $sanPhamVang->id,
                'total_price_records' => $lichSuGia->count()
            ]);
            
            return $view;
        } catch (\Exception $e) {
            Log::error('UserDashboardController@chiTietDauTu: Lỗi khi hiển thị trang chi tiết đầu tư vàng', [
                'user_id' => Auth::id(),
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function createDauTu(Request $request){
        return view('user.dashboard.create-dau-tu', compact('request'));
    }
    
    public function getGiaVangData(Request $request)
    {
        Log::info('UserDashboardController@getGiaVangData: Lấy dữ liệu giá vàng', [
            'user_id' => Auth::id(),
            'request_params' => $request->all()
        ]);
        
        try {
            $sanPhamId = $request->get('san_pham_id');
            $period = $request->get('period', '7d');
            
            // Tính số ngày dựa trên period
            $days = 7;
            switch ($period) {
                case '30d':
                    $days = 30;
                    break;
                case '90d':
                    $days = 90;
                    break;
                default:
                    $days = 7;
            }
            
            // Lấy dữ liệu giá vàng
            $query = GiaVang::where('trang_thai', 1)
                ->where('thoi_gian', '>=', now()->subDays($days))
                ->orderBy('thoi_gian', 'asc');
                
            if ($sanPhamId) {
                $query->where('id_vang', $sanPhamId);
            }
            
            $giaVangData = $query->get();
            
            // Chuẩn bị dữ liệu cho biểu đồ
            $labels = [];
            $buyPrices = [];
            $sellPrices = [];
            
            foreach ($giaVangData as $gia) {
                $labels[] = \Carbon\Carbon::parse($gia->thoi_gian)->format('d/m H:i');
                $buyPrices[] = $gia->gia_mua;
                $sellPrices[] = $gia->gia_ban;
            }
            
            // Nếu không có dữ liệu, tạo dữ liệu mẫu
            if (empty($labels)) {
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('d/m H:i');
                    $buyPrices[] = 5000000 + rand(-100000, 100000);
                    $sellPrices[] = 5100000 + rand(-100000, 100000);
                }
            }
            
            Log::info('UserDashboardController@getGiaVangData: Lấy dữ liệu giá vàng thành công', [
                'user_id' => Auth::id(),
                'total_records' => count($labels),
                'period' => $period
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'buyPrices' => $buyPrices,
                    'sellPrices' => $sellPrices,
                    'period' => $period
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('UserDashboardController@getGiaVangData: Lỗi khi lấy dữ liệu giá vàng', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu giá vàng'
            ], 500);
        }
    }
    
    public function createVangDauTu(Request $request)
    {
        Log::info('UserDashboardController@createVangDauTu: Bắt đầu tạo giao dịch vàng', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'input_data' => $request->except(['withdrawal_password'])
        ]);

        $validator = Validator::make($request->all(), [
            'san_pham_id' => 'required|exists:san_pham_vang,id',
            'quantity' => 'required|numeric|min:0.1',
            'transaction_type' => 'required|in:buy,sell',
            'withdrawal_password' => 'required'
        ], [
            'san_pham_id.required' => 'Sản phẩm vàng không được để trống',
            'san_pham_id.exists' => 'Sản phẩm vàng không tồn tại',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.numeric' => 'Số lượng phải là số',
            'quantity.min' => 'Số lượng tối thiểu là 0.1 gram',
            'transaction_type.required' => 'Loại giao dịch không được để trống',
            'transaction_type.in' => 'Loại giao dịch không hợp lệ',
            'withdrawal_password.required' => 'Mật khẩu rút tiền không được để trống'
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@createVangDauTu: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'error_code' => 'VALIDATION_ERROR',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $profile = $user->profile;
            
            // Kiểm tra mật khẩu rút tiền
            if (!$profile || !$profile->mat_khau_rut_tien || $request->withdrawal_password !== $profile->mat_khau_rut_tien) {
                Log::warning('UserDashboardController@createVangDauTu: Mật khẩu rút tiền không đúng', [
                    'user_id' => $user->id
                ]);
                
                return response()->json([
                    'success' => false,
                    'error_code' => 'INVALID_WITHDRAWAL_PASSWORD',
                    'message' => 'Mật khẩu rút tiền không đúng'
                ]);
            }

            // Lấy thông tin sản phẩm vàng
            $sanPhamVang = VangDauTu::find($request->san_pham_id);
            if (!$sanPhamVang || $sanPhamVang->trang_thai != 1) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'PRODUCT_NOT_AVAILABLE',
                    'message' => 'Sản phẩm vàng không khả dụng'
                ]);
            }

            // Lấy giá hiện tại
            $giaHienTai = GiaVang::where('id_vang', $sanPhamVang->id)
                ->where('trang_thai', 1)
                ->orderBy('thoi_gian', 'desc')
                ->first();
                
            if (!$giaHienTai) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'NO_PRICE_DATA',
                    'message' => 'Không có dữ liệu giá vàng hiện tại'
                ]);
            }

            $quantity = $request->quantity;
            $transactionType = $request->transaction_type;
            // Khi mua: sử dụng giá bán của hệ thống (người dùng mua từ hệ thống)
            // Khi bán: sử dụng giá mua của hệ thống (người dùng bán cho hệ thống)
            $price = $transactionType === 'buy' ? $giaHienTai->gia_ban : $giaHienTai->gia_mua;
            $totalAmount = $quantity * $price;

            // Kiểm tra số dư (chỉ khi mua)
            if ($transactionType === 'buy' && $profile->so_du < $totalAmount) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'INSUFFICIENT_BALANCE',
                    'message' => 'Số dư không đủ để thực hiện giao dịch',
                    'data' => [
                        'current_balance' => $profile->so_du,
                        'required_amount' => $totalAmount,
                        'shortage' => $totalAmount - $profile->so_du
                    ]
                ]);
            }

            // Thực hiện giao dịch
            if ($transactionType === 'buy') {
                // Trừ tiền khi mua
                $profile->so_du -= $totalAmount;
            } else {
                // Cộng tiền khi bán
                $profile->so_du += $totalAmount;
            }
            
            $profile->save();

            // Lưu bản ghi vào dau_tu_vang khi mua thành công
            if ($transactionType === 'buy') {
                DauTuVang::create([
                    'user_id' => $user->id,
                    'id_vang' => $sanPhamVang->id,
                    'so_luong' => $quantity,
                    'thoi_gian' => now(),
                    'gia_mua' => $price,
                    'trang_thai' => 1,
                    'ghi_chu' => ''
                ]);
            }

            Log::info('UserDashboardController@createVangDauTu: Giao dịch vàng thành công', [
                'user_id' => $user->id,
                'transaction_type' => $transactionType,
                'quantity' => $quantity,
                'price' => $price,
                'total_amount' => $totalAmount,
                'new_balance' => $profile->so_du
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Giao dịch vàng thành công!',
                'data' => [
                    'transaction_type' => $transactionType,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_amount' => $totalAmount,
                    'new_balance' => $profile->so_du
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('UserDashboardController@createVangDauTu: Lỗi khi tạo giao dịch vàng', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error_code' => 'SYSTEM_ERROR',
                'message' => 'Có lỗi xảy ra khi xử lý giao dịch. Vui lòng thử lại.'
            ]);
        }
    }
    public function duAnDauTuCuaToi(Request $request){
        Log::info('UserDashboardController@duAnDauTuCuaToi: Bắt đầu hiển thị trang dự án đầu tư của tôi', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ]);
        
        $user = Auth::user();
        $query = DauTuVang::with('sanPhamVang')
            ->where('user_id', $user->id);

        // Tìm kiếm theo tên sản phẩm hoặc mã sản phẩm
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('sanPhamVang', function($q) use ($search) {
                $q->where('ten_vang', 'like', "%{$search}%")
                  ->orWhere('ma_vang', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $duAns = $query->orderByDesc('id')->paginate(10)->appends($request->only(['search', 'status']));
        
        // Lấy giá hiện tại của vàng cho mỗi bản ghi
        foreach ($duAns as $duAn) {
            $giaHienTai = GiaVang::where('id_vang', $duAn->id_vang)
                ->where('trang_thai', 1)
                ->orderByDesc('thoi_gian')
                ->first();
            
            $duAn->gia_hien_tai = $giaHienTai ? $giaHienTai->gia_mua : 0;
            $duAn->tong_tien_hien_tai = $duAn->so_luong * $duAn->gia_hien_tai;
        }
        
        return view('user.dashboard.du-an-dau-tu-cua-toi', compact('duAns'));
    }

    /**
     * Lấy giá vàng hiện tại
     */
    public function getCurrentGoldPrice(Request $request)
    {
        try {
            $productId = $request->get('product_id');
            
            // Lấy giá vàng mới nhất
            $query = GiaVang::where('trang_thai', 1)
                ->orderBy('thoi_gian', 'desc');
                
            if ($productId) {
                $query->where('id_vang', $productId);
            }
            
            $latestPrice = $query->first();
            
            if (!$latestPrice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy giá vàng hiện tại'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'gia_mua' => $latestPrice->gia_mua,
                    'gia_ban' => $latestPrice->gia_ban,
                    'thoi_gian' => $latestPrice->thoi_gian,
                    'id_vang' => $latestPrice->id_vang
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('UserDashboardController@getCurrentGoldPrice: Lỗi khi lấy giá vàng hiện tại', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy giá vàng'
            ]);
        }
    }

    /**
     * Bán vàng
     */
    public function sellGold(Request $request)
    {
        Log::info('UserDashboardController@sellGold: Bắt đầu xử lý bán vàng', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'N/A',
            'request_data' => $request->except(['withdrawal_password']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'gold_id' => 'required|integer|exists:dau_tu_vang,id',
                'withdrawal_password' => 'required|string'
            ]);

            if ($validator->fails()) {
                Log::warning('UserDashboardController@sellGold: Validation thất bại', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->except(['withdrawal_password'])
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ]);
            }

            $goldId = $request->gold_id;
            $password = $request->withdrawal_password;

            // Kiểm tra đầu tư vàng
            $dauTuVang = DauTuVang::where('id', $goldId)
                ->where('user_id', Auth::id())
                ->where('trang_thai', 1) // Chỉ có thể bán khi đang nắm giữ
                ->with('sanPhamVang') // Load thông tin sản phẩm vàng
                ->first();

            if (!$dauTuVang) {
                Log::warning('UserDashboardController@sellGold: Không tìm thấy đầu tư vàng hoặc đã được bán', [
                    'user_id' => Auth::id(),
                    'gold_id' => $goldId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đầu tư vàng hoặc đã được bán'
                ]);
            }

            // Lấy số lượng từ bản ghi dau_tu_vang (bán toàn bộ số lượng đang nắm giữ)
            $quantity = $dauTuVang->so_luong;

            // Log thông tin chi tiết bản ghi đầu tư vàng
            Log::info('UserDashboardController@sellGold: Thông tin bản ghi đầu tư vàng', [
                'user_id' => Auth::id(),
                'investment_id' => $dauTuVang->id,
                'product_id' => $dauTuVang->id_vang,
                'product_name' => $dauTuVang->sanPhamVang->ten_vang ?? 'N/A',
                'product_code' => $dauTuVang->sanPhamVang->ma_vang ?? 'N/A',
                'current_quantity' => $dauTuVang->so_luong,
                'buy_price' => $dauTuVang->gia_mua,
                'total_buy_amount' => $dauTuVang->so_luong * $dauTuVang->gia_mua,
                'status' => $dauTuVang->trang_thai,
                'buy_time' => $dauTuVang->thoi_gian,
                'note' => $dauTuVang->ghi_chu,
            ]);
            $user = Auth::user();
            $profile = $user->profile;
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin rút tiền'
                ]);
            }   
            // Kiểm tra mật khẩu rút tiền
            if ($password != $profile->mat_khau_rut_tien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu rút tiền không đúng'
                ]);
            }

            // Lấy giá vàng hiện tại
            $currentPrice = GiaVang::where('trang_thai', 1)
                ->where('id_vang', $dauTuVang->id_vang)
                ->orderBy('thoi_gian', 'desc')
                ->first();

            if (!$currentPrice) {
                Log::error('UserDashboardController@sellGold: Không tìm thấy giá vàng hiện tại', [
                    'user_id' => Auth::id(),
                    'investment_id' => $dauTuVang->id,
                    'product_id' => $dauTuVang->id_vang
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy giá vàng hiện tại'
                ]);
            }

            // Tạo biến $goldData để lưu trữ dữ liệu gold và tái sử dụng
            $goldData = [
                'investment' => $dauTuVang,
                'current_price' => $currentPrice,
                'sell_price' => $currentPrice->gia_mua,
                'quantity' => $quantity,
                'total_amount' => $quantity * $currentPrice->gia_mua,
                'buy_price' => $dauTuVang->gia_mua,
                'total_buy_amount' => $quantity * $dauTuVang->gia_mua,
                'profit_loss' => ($quantity * $currentPrice->gia_mua) - ($quantity * $dauTuVang->gia_mua),
                'profit_loss_percentage' => round((($quantity * $currentPrice->gia_mua) - ($quantity * $dauTuVang->gia_mua)) / ($quantity * $dauTuVang->gia_mua) * 100, 2)
            ];

            // Sử dụng dữ liệu từ biến $goldData
            $sellPrice = $goldData['sell_price'];
            $totalAmount = $goldData['total_amount'];

            // Log thông tin giá vàng và tính toán giao dịch sử dụng dữ liệu từ $goldData
            Log::info('UserDashboardController@sellGold: Thông tin giá vàng và tính toán giao dịch', [
                'user_id' => Auth::id(),
                'investment_id' => $goldData['investment']->id,
                'current_price_id' => $goldData['current_price']->id,
                'current_sell_price' => $goldData['sell_price'],
                'sell_quantity' => $goldData['quantity'],
                'total_sell_amount' => $goldData['total_amount'],
                'buy_price' => $goldData['buy_price'],
                'total_buy_amount' => $goldData['total_buy_amount'],
                'profit_loss' => $goldData['profit_loss'],
                'profit_loss_percentage' => $goldData['profit_loss_percentage'],
                'price_time' => $goldData['current_price']->thoi_gian
            ]);

            // Bắt đầu transaction
            DB::beginTransaction();

            try {
                // Cập nhật trạng thái đầu tư - bán toàn bộ số lượng
                $dauTuVang->trang_thai = 2; // Đã bán
                $dauTuVang->save();

                // Cập nhật số dư tài khoản
                $user = Auth::user();
                $profile = $user->profile;
                if (!$profile) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy thông tin rút tiền'
                    ]);
                }
                $profile->so_du += $totalAmount;
                $profile->save();
                $user->save();
                DB::commit();

                // Log chi tiết sau khi bán thành công sử dụng dữ liệu từ $goldData
                Log::info('UserDashboardController@sellGold: Bán vàng thành công - Chi tiết giao dịch', [
                    'user_id' => Auth::id(),
                    'user_email' => Auth::user()->email ?? 'N/A',
                    'investment_id' => $goldId,
                    'product_name' => $goldData['investment']->sanPhamVang->ten_vang ?? 'N/A',
                    'product_code' => $goldData['investment']->sanPhamVang->ma_vang ?? 'N/A',
                    'sell_quantity' => $goldData['quantity'],
                    'sell_price' => $goldData['sell_price'],
                    'total_sell_amount' => $goldData['total_amount'],
                    'buy_price' => $goldData['buy_price'],
                    'total_buy_amount' => $goldData['total_buy_amount'],
                    'profit_loss' => $goldData['profit_loss'],
                    'profit_loss_percentage' => $goldData['profit_loss_percentage'],
                    'remaining_quantity' => 0, // Bán toàn bộ nên không còn lại
                    'is_full_sell' => true, // Luôn bán toàn bộ
                    'new_balance' => $profile->so_du,
                    'transaction_time' => now()->toDateTimeString(),
                    'ip_address' => $request->ip()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Bán vàng thành công',
                    'data' => [
                        'quantity' => $goldData['quantity'],
                        'price' => $goldData['sell_price'],
                        'total_amount' => $goldData['total_amount'],
                        'profit_loss' => $goldData['profit_loss'],
                        'profit_loss_percentage' => $goldData['profit_loss_percentage']
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('UserDashboardController@sellGold: Lỗi khi bán vàng', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi bán vàng'
            ]);
        }
    }
    public function taiSan(){
        try {
            $user = Auth::user();
            $profile = $user->profile;
            
            // 1. Số dư khả dụng từ profile->so_du
            $soDuHienTai = $profile ? $profile->so_du : 0;
            
            // 2. Tính tài sản đầu tư từ bảng dau_tu_vang (so_luong * gia_mua)
            $taiSanDauTu = DauTuVang::where('user_id', $user->id)
                ->where('trang_thai', 1) // Chỉ tính những khoản đang nắm giữ
                ->selectRaw('
                    SUM(so_luong * gia_mua) as tong_gia_tri_dau_tu,
                    COUNT(*) as so_giao_dich_dau_tu
                ')
                ->first();
            
            $tongGiaTriDauTu = $taiSanDauTu->tong_gia_tri_dau_tu ?? 0;
            $soGiaoDichDauTu = $taiSanDauTu->so_giao_dich_dau_tu ?? 0;
            
            // 3. Tính tài sản tiết kiệm từ bảng tiet_kiem
            $taiSanTietKiem = TietKiem::where('user_id', $user->id)
                ->where('trang_thai', 1) // Chỉ tính những gói đang hoạt động
                ->selectRaw('
                    SUM(so_tien) as tong_tien_tiet_kiem,
                    COUNT(*) as so_goi_tiet_kiem
                ')
                ->first();
            
            $tongTienTietKiem = $taiSanTietKiem->tong_tien_tiet_kiem ?? 0;
            $soGoiTietKiem = $taiSanTietKiem->so_goi_tiet_kiem ?? 0;
            
            // 4. Tính tổng tài sản
            $tongTaiSan = $soDuHienTai + $tongGiaTriDauTu + $tongTienTietKiem;
            
            Log::info('UserDashboardController@taiSan: Tính toán tài sản thành công', [
                'user_id' => $user->id,
                'so_du_hien_tai' => $soDuHienTai,
                'tong_gia_tri_dau_tu' => $tongGiaTriDauTu,
                'tong_tien_tiet_kiem' => $tongTienTietKiem,
                'tong_tai_san' => $tongTaiSan
            ]);
            
            return view('user.dashboard.tai-san', compact(
                'soDuHienTai',
                'tongGiaTriDauTu', 
                'soGiaoDichDauTu',
                'tongTienTietKiem',
                'soGoiTietKiem',
                'tongTaiSan'
            ));
            
        } catch (\Exception $e) {
            Log::error('UserDashboardController@taiSan: Lỗi khi lấy dữ liệu tài sản', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('user.dashboard.tai-san', [
                'soDuHienTai' => 0,
                'tongGiaTriDauTu' => 0,
                'soGiaoDichDauTu' => 0,
                'tongTienTietKiem' => 0,
                'soGoiTietKiem' => 0,
                'tongTaiSan' => 0
            ]);
        }
    }

    /**
     * Kiểm tra trạng thái thanh toán dựa trên nội dung chuyển khoản
     */
    public function checkPaymentStatus(Request $request)
    {
        Log::info('UserDashboardController@checkPaymentStatus: Bắt đầu kiểm tra trạng thái thanh toán', [
            'user_id' => Auth::id(),
            'transfer_content' => $request->noi_dung,
            'ip_address' => $request->ip()
        ]);

        $validator = Validator::make($request->all(), [
            'noi_dung' => 'required|string|max:255',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung chuyển khoản',
            'noi_dung.max' => 'Nội dung chuyển khoản không được vượt quá 255 ký tự',
        ]);

        if ($validator->fails()) {
            Log::warning('UserDashboardController@checkPaymentStatus: Validation thất bại', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $transferContent = $request->noi_dung;

            // Tìm bản ghi NapRut với loại 'nap', noi_dung khớp và trạng thái = 1 (thành công)
            $napRut = NapRut::where('user_id', $user->id)
                ->where('loai', 'nap')
                ->where('noi_dung', $transferContent)
                ->where('trang_thai', 1) // 1 = thành công
                ->first();

            if ($napRut) {
                Log::info('UserDashboardController@checkPaymentStatus: Tìm thấy giao dịch thành công', [
                    'user_id' => $user->id,
                    'nap_rut_id' => $napRut->id,
                    'so_tien' => $napRut->so_tien,
                    'noi_dung' => $transferContent
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'message' => 'Giao dịch đã được xử lý thành công!',
                    'data' => [
                        'id' => $napRut->id,
                        'so_tien' => $napRut->so_tien,
                        'ngan_hang' => $napRut->ngan_hang,
                        'so_tai_khoan' => $napRut->so_tai_khoan,
                        'chu_tai_khoan' => $napRut->chu_tai_khoan,
                        'noi_dung' => $napRut->noi_dung,
                        'trang_thai' => $napRut->trang_thai,
                        'created_at' => $napRut->created_at->format('d/m/Y H:i:s'),
                        'updated_at' => $napRut->updated_at->format('d/m/Y H:i:s')
                    ]
                ]);
            } else {
                // Kiểm tra xem có giao dịch nào với nội dung này chưa (để biết trạng thái)
                $pendingNapRut = NapRut::where('user_id', $user->id)
                    ->where('loai', 'nap')
                    ->where('noi_dung', $transferContent)
                    ->first();

                if ($pendingNapRut) {
                    Log::info('UserDashboardController@checkPaymentStatus: Tìm thấy giao dịch chưa thành công', [
                        'user_id' => $user->id,
                        'nap_rut_id' => $pendingNapRut->id,
                        'trang_thai' => $pendingNapRut->trang_thai,
                        'noi_dung' => $transferContent
                    ]);

                    $statusMessage = '';
                    switch ($pendingNapRut->trang_thai) {
                        case 0:
                            $statusMessage = 'Giao dịch đang chờ xử lý';
                            break;
                        case 2:
                            $statusMessage = 'Giao dịch đã bị từ chối';
                            break;
                        default:
                            $statusMessage = 'Giao dịch đang được xử lý';
                    }

                    return response()->json([
                        'success' => true,
                        'status' => 'pending',
                        'message' => $statusMessage,
                        'data' => [
                            'id' => $pendingNapRut->id,
                            'so_tien' => $pendingNapRut->so_tien,
                            'trang_thai' => $pendingNapRut->trang_thai,
                            'created_at' => $pendingNapRut->created_at->format('d/m/Y H:i:s')
                        ]
                    ]);
                } else {
                    Log::info('UserDashboardController@checkPaymentStatus: Không tìm thấy giao dịch nào', [
                        'user_id' => $user->id,
                        'noi_dung' => $transferContent
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => 'not_found',
                        'message' => 'Chưa tìm thấy giao dịch với nội dung này'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('UserDashboardController@checkPaymentStatus: Lỗi khi kiểm tra trạng thái thanh toán', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra trạng thái thanh toán'
            ], 500);
        }
    }
}
