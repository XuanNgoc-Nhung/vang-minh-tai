<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CauHinhController;
use App\Http\Controllers\UserDashboardController;

Route::get('/test', [HomeController::class, 'test'])->name('test');
Route::get('/thanh-toan', [HomeController::class, 'thanhToan'])->name('thanh-toan');
Route::options('/thanh-toan/process', function () {
    return response('', 200);
})->middleware('cors');
Route::post('/thanh-toan/process', [HomeController::class, 'processPayment'])->middleware('cors')->name('thanh-toan.process');
Route::post('/test/add-data', [HomeController::class, 'addTestData'])->name('test.add-data');
Route::get('/test-api', function () {
    return response()->file(public_path('../test_api.html'));
})->name('test.api');

Route::get('/test-payment-api', function () {
    return response()->file(public_path('../test_payment_api.html'));
})->name('test.payment.api');

// API Routes với CORS middleware và bỏ qua CSRF
Route::prefix('api')->middleware(['cors'])->group(function () {
    Route::options('/add-data', function () {
        return response('', 200);
    });
    Route::post('/add-data', [HomeController::class, 'addTestDataApi'])->name('api.add-data');
    
    Route::options('/thanh-toan/process', function () {
        return response('', 200);
    });
    Route::post('/thanh-toan/process', [HomeController::class, 'processPaymentApi'])->name('api.thanh-toan.process');
});
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    // Người dùng
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    Route::post('/users/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/delete-user', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    // Sản phẩm tiết kiệm
    Route::get('/san-pham-tiet-kiem', [AdminController::class, 'sanPhamDauTu'])->name('admin.san-pham-tiet-kiem');
    Route::get('/lich-su-tiet-kiem', [AdminController::class, 'lichSuDauTu'])->name('admin.lich-su-tiet-kiem');
    Route::post('/lich-su-tiet-kiem/update-status', [AdminController::class, 'updateTietKiemStatus'])->name('admin.lich-su-tiet-kiem.update-status');
    Route::post('/san-pham-tiet-kiem', [AdminController::class, 'storeSanPhamDauTu'])->name('admin.san-pham-tiet-kiem.store');
    Route::post('/san-pham-tiet-kiem/update', [AdminController::class, 'updateSanPhamDauTu'])->name('admin.san-pham-tiet-kiem.update');
    Route::post('/delete-san-pham-tiet-kiem', [AdminController::class, 'destroySanPhamDauTu'])->name('admin.san-pham-tiet-kiem.destroy');
    // end sản phẩm tiết kiệm
    //Ngân hàng nạp tiền
    Route::get('/ngan-hang-nap-tien', [AdminController::class, 'nganHangNapTien'])->name('admin.ngan-hang-nap-tien');
    Route::post('/ngan-hang-nap-tien', [AdminController::class, 'storeNganHangNapTien'])->name('admin.ngan-hang-nap-tien.store');
    Route::post('/ngan-hang-nap-tien/update', [AdminController::class, 'updateNganHangNapTien'])->name('admin.ngan-hang-nap-tien.update');
    Route::post('/delete-ngan-hang-nap-tien', [AdminController::class, 'destroyNganHangNapTien'])->name('admin.ngan-hang-nap-tien.destroy');
    // end Ngân hàng nạp tiền
    // Nạp rút
    Route::get('/nap-rut', [AdminController::class, 'napRut'])->name('admin.nap-rut');
    Route::post('/nap-rut/update-status', [AdminController::class, 'updateNapRutStatus'])->name('admin.nap-rut.update-status');
    // end Nạp rút
    //Đầu tư
    Route::get('/dau-tu', [AdminController::class, 'dauTu'])->name('admin.dau-tu');
    Route::post('/dau-tu/update-status', [AdminController::class, 'updateDauTuStatus'])->name('admin.dau-tu.update-status');
    Route::post('/dau-tu', [AdminController::class, 'storeDauTu'])->name('admin.dau-tu.store');
    Route::post('/dau-tu/update', [AdminController::class, 'updateDauTu'])->name('admin.dau-tu.update');
    Route::post('/delete-dau-tu', [AdminController::class, 'destroyDauTu'])->name('admin.dau-tu.destroy');
    // end Đầu tư
    //Vàng đầu tư
    Route::get('/vang-dau-tu', [AdminController::class, 'vangDauTu'])->name('admin.vang-dau-tu');
    Route::post('/vang-dau-tu', [AdminController::class, 'storeVangDauTu'])->name('admin.vang-dau-tu.store');
    Route::post('/vang-dau-tu/update', [AdminController::class, 'updateVangDauTu'])->name('admin.vang-dau-tu.update');
    Route::post('/delete-vang-dau-tu', [AdminController::class, 'destroyVangDauTu'])->name('admin.vang-dau-tu.destroy');
    // end Vàng đầu tư
    //Giá vàng
    Route::get('/gia-vang', [AdminController::class, 'giaVang'])->name('admin.gia-vang');
    Route::post('/gia-vang', [AdminController::class, 'storeGiaVang'])->name('admin.gia-vang.store');
    Route::post('/gia-vang/update', [AdminController::class, 'updateGiaVang'])->name('admin.gia-vang.update');
    Route::post('/delete-gia-vang', [AdminController::class, 'destroyGiaVang'])->name('admin.gia-vang.destroy');
    // end Giá vàng
    
    //Thông báo
    Route::get('/thong-bao', [AdminController::class, 'thongBao'])->name('admin.thong-bao');
    Route::post('/thong-bao', [AdminController::class, 'storeThongBao'])->name('admin.thong-bao.store');
    Route::post('/thong-bao/update', [AdminController::class, 'updateThongBao'])->name('admin.thong-bao.update');
    Route::post('/delete-thong-bao', [AdminController::class, 'destroyThongBao'])->name('admin.thong-bao.destroy');
    // end Thông báo
    //cấu hình
    Route::get('/cau-hinh', [CauHinhController::class, 'cauHinh'])->name('admin.cau-hinh');
    Route::post('/cau-hinh', [CauHinhController::class, 'storeCauHinh'])->name('admin.cau-hinh.store');
    Route::post('/cau-hinh/update', [CauHinhController::class, 'updateCauHinh'])->name('admin.cau-hinh.update');
    Route::post('/delete-cau-hinh', [CauHinhController::class, 'destroyCauHinh'])->name('admin.cau-hinh.destroy');
    // end Cấu hình
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/refresh-gia-vang', [HomeController::class, 'refreshGiaVang'])->name('refresh-gia-vang');
Route::middleware('cors')->group(function () {
    Route::get('/api/gia-vang-data-list', [HomeController::class, 'getGiaVangDataList'])->name('api.gia-vang-data-list');
    Route::get('/api/gia-vang-history', [HomeController::class, 'getGiaVangHistory'])->name('api.gia-vang-history');
});
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'postLogin'])->name('post-login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'postRegister'])->name('post-register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth','prefix' => 'dashboard'], function () {
    Route::get('/', [UserDashboardController::class, 'taiSan'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('dashboard.profile');
    // bao mat
    Route::get('/bao-mat', [UserDashboardController::class, 'baoMat'])->name('dashboard.bao-mat');
    Route::post('/change-password', [UserDashboardController::class, 'changePassword'])->name('user.change-password');
    Route::post('/change-withdrawal-password', [UserDashboardController::class, 'changeWithdrawalPassword'])->name('user.change-withdrawal-password');
    // end bao mat
    //kyc tài khoản
    Route::get('/kyc', [UserDashboardController::class, 'kyc'])->name('dashboard.kyc');
    Route::post('/kyc', [UserDashboardController::class, 'createKycRequest'])->name('user.kyc.submit');
    // Ngân hàng
    Route::get('/ngan-hang', [UserDashboardController::class, 'nganHang'])->name('dashboard.ngan-hang');
    Route::put('/ngan-hang/update', [UserDashboardController::class, 'updateBankInfo'])->name('user.bank.update');
    // end Ngân hàng
    // Tài sản
    Route::get('/tai-san', [UserDashboardController::class, 'taiSan'])->name('dashboard.tai-san');
    
    // Nạp tiền
    Route::get('/nap-tien', [UserDashboardController::class, 'napTien'])->name('dashboard.nap-tien');
    Route::post('/nap-tien', [UserDashboardController::class, 'createNapTienRequest'])->name('dashboard.nap-tien.create');
    Route::post('/check-payment-status', [UserDashboardController::class, 'checkPaymentStatus'])->name('dashboard.check-payment-status');
    // end Nạp tiền
    // Rút tiền
    Route::get('/rut-tien', [UserDashboardController::class, 'rutTien'])->name('dashboard.rut-tien');
    Route::post('/rut-tien', [UserDashboardController::class, 'createRutTienRequest'])->name('dashboard.rut-tien.create');
    // end Rút tiền
    // Lịch sử nạp rút
    Route::get('/lich-su-nap-rut', [UserDashboardController::class, 'lichSuNapRut'])->name('dashboard.lich-su-nap-rut');
    // end Lịch sử nạp rút
    // Thông báo
    Route::get('/thong-bao', [UserDashboardController::class, 'thongBao'])->name('dashboard.thong-bao');
    // end Thông báo
    // Tiết kiệm
    Route::get('/tiet-kiem', [UserDashboardController::class, 'tietKiem'])->name('dashboard.tiet-kiem');
    Route::get('/chi-tiet-tiet-kiem/{slug}', [UserDashboardController::class, 'chiTietTietKiem'])->name('dashboard.chi-tiet-tiet-kiem');
    Route::post('/tiet-kiem', [UserDashboardController::class, 'createTietKiem'])->name('dashboard.tiet-kiem.create');
    Route::get('/du-an-cua-toi', [UserDashboardController::class, 'duAnTietKiemCuaToi'])->name('dashboard.du-an-cua-toi');
    // end Tiết kiệm
    // Đầu tư
    Route::get('/dau-tu', [UserDashboardController::class, 'dauTu'])->name('dashboard.dau-tu');
    Route::get('/chi-tiet-dau-tu/{slug}', [UserDashboardController::class, 'chiTietDauTu'])->name('dashboard.chi-tiet-dau-tu');
    Route::post('/dau-tu', [UserDashboardController::class, 'createDauTu'])->name('dashboard.dau-tu.create');
    Route::get('/du-an-dau-tu-cua-toi', [UserDashboardController::class, 'duAnDauTuCuaToi'])->name('dashboard.du-an-dau-tu-cua-toi');
    // API cho giá vàng với CORS middleware
    Route::middleware('cors')->group(function () {
        Route::get('/api/gia-vang-data', [UserDashboardController::class, 'getGiaVangData'])->name('dashboard.api.gia-vang-data');
        Route::get('/api/gia-vang/current', [UserDashboardController::class, 'getCurrentGoldPrice'])->name('dashboard.api.gia-vang.current');
        Route::post('/api/vang-dau-tu', [UserDashboardController::class, 'createVangDauTu'])->name('dashboard.api.vang-dau-tu.create');
        Route::post('/api/vang-dau-tu/sell', [UserDashboardController::class, 'sellGold'])->name('dashboard.api.vang-dau-tu.sell');
    });
    // end Đầu tư
});