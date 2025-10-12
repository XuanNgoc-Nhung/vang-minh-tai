<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_pham_dau_tu', function (Blueprint $table) {
            $table->id();
            $table->string('ten')->nullable();
            $table->string('slug')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->decimal('von_toi_thieu', 15, 2)->nullable();
            $table->decimal('von_toi_da', 15, 2)->nullable();
            $table->unsignedInteger('so_luong_chu_ky')->nullable();
            $table->unsignedInteger('thoi_gian_mot_chu_ky')->nullable();
            $table->string('nhan_dan')->nullable();
            $table->text('mo_ta')->nullable();
            $table->unsignedTinyInteger('trang_thai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham_dau_tu');
    }
};


