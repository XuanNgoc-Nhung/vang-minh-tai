<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'ngan_hang',
        'so_tai_khoan',
        'chu_tai_khoan',
        'so_du',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi',
        'mat_khau_rut_tien',
        // KYC image fields only
        'anh_mat_truoc',
        'anh_mat_sau',
        'anh_chan_dung',
    ];

    protected $casts = [
        'so_du' => 'decimal:2',
        'ngay_sinh' => 'date',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
