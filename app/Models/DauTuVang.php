<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DauTuVang extends Model
{
    protected $table = 'dau_tu_vang';

    protected $fillable = [
        'user_id',
        'id_vang',
        'so_luong',
        'thoi_gian',
        'gia_mua',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'thoi_gian' => 'datetime',
        'so_luong' => 'decimal:3',
        'gia_mua' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vangDauTu(): BelongsTo
    {
        return $this->belongsTo(VangDauTu::class, 'id_vang');
    }

    public function sanPhamVang(): BelongsTo
    {
        return $this->belongsTo(VangDauTu::class, 'id_vang', 'id');
    }
}
