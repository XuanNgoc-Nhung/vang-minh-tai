<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NapRut extends Model
{
    protected $table = 'nap_rut';
    protected $fillable = [
        'user_id', 
        'loai', 
        'so_tien', 
        'ngan_hang', 
        'so_tai_khoan', 
        'chu_tai_khoan', 
        'noi_dung', 
        'trang_thai'
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
