<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DauTu extends Model
{
    protected $table = 'dau_tu';

    protected $fillable = [
        'user_id',
        'san_pham_id',
        'so_luong',
        'gia_mua',
        'gia_ban',
        'thoi_gian',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sanPham(): BelongsTo
    {
        return $this->belongsTo(SanPhamDauTu::class);
    }
}
