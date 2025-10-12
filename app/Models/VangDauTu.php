<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VangDauTu extends Model
{
    protected $table = 'san_pham_vang';
    protected $fillable = [
        'ten_vang',
        'ma_vang',
        'trang_thai',
    ];
}
