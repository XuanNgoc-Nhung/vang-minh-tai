<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietDauTu extends Model
{
    protected $table = 'chi_tiet_dau_tu';
    
    protected $fillable = [
        'dau_tu_id',
        'user_id',
        'san_pham_id',
        'chu_ky_thu',
        'tien_goc',
        'tien_lai',
        'tong_tien',
        'thoi_gian_nhan',
        'trang_thai',
        'ghi_chu'
    ];

    protected $casts = [
        'tien_goc' => 'decimal:2',
        'tien_lai' => 'decimal:2',
        'tong_tien' => 'decimal:2',
        'trang_thai' => 'integer',
        'thoi_gian_nhan' => 'datetime'
    ];

    public function dauTu(): BelongsTo
    {
        return $this->belongsTo(TietKiem::class, 'dau_tu_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sanPham(): BelongsTo
    {
        return $this->belongsTo(SanPhamTietKiem::class, 'san_pham_id');
    }
}