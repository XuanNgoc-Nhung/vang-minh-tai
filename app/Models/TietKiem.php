<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TietKiem extends Model
{
    protected $table = 'tiet_kiem';
    
    protected $fillable = [
        'user_id',
        'san_pham_id', 
        'so_chu_ky',
        'so_tien',
        'hoa_hong',
        'trang_thai',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ghi_chu'
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'hoa_hong' => 'decimal:2',
        'trang_thai' => 'integer',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sanPham(): BelongsTo
    {
        return $this->belongsTo(SanPhamTietKiem::class, 'san_pham_id');
    }

    public function chiTietDauTu(): HasMany
    {
        return $this->hasMany(ChiTietDauTu::class, 'dau_tu_id');
    }
}
