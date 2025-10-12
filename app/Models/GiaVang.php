<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaVang extends Model
{
    protected $table = 'gia_vang';
    protected $fillable = [
        'id_vang',
        'gia_mua',
        'gia_ban',
        'thoi_gian',
        'trang_thai',
        'ghi_chu',
    ];
}
