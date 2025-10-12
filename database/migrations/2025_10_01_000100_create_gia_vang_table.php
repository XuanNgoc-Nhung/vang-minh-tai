<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gia_vang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_vang')->index();
            $table->decimal('gia_mua', 15, 2);
            $table->decimal('gia_ban', 15, 2);
            $table->timestamp('thoi_gian');
            $table->tinyInteger('trang_thai')->default(0)->comment('0: chưa xác nhận, 1: đã xác nhận');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gia_vang');
    }
};
