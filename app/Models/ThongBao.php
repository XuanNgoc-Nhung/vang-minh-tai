<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'thong_baos';
    protected $fillable = ['tieu_de', 'noi_dung', 'trang_thai'];
}
