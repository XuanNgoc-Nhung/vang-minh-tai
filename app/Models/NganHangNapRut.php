<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NganHangNapRut extends Model
{
    protected $table = 'ngan_hang_nap_tien';
    protected $fillable = ['ten_ngan_hang', 'hinh_anh', 'so_tai_khoan', 'chu_tai_khoan', 'chi_nhanh', 'trang_thai', 'ghi_chu'];
}
