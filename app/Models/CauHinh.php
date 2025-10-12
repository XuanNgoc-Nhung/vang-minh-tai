<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinh extends Model
{
    protected $table = 'cau_hinh';
    protected $fillable = ['token_tele','id_tele','id_live_chat','link_facebook','ma_so_doanh_nghiep'];
}
