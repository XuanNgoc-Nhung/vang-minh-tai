<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamTietKiem extends Model
{
    protected $table = 'san_pham_dau_tu';
    protected $fillable = [
        'ten',
        'slug',
        'hinh_anh',
        'von_toi_thieu',
        'von_toi_da',
        'lai_suat',
        'thoi_gian_mot_chu_ky',
        'nhan_dan',
        'mo_ta',
        'trang_thai',
    ];
}
