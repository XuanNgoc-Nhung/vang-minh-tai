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
        Schema::create('san_pham_vang', function (Blueprint $table) {
            $table->id();
            $table->string('ten_vang');
            $table->string('ma_vang');
            $table->unsignedTinyInteger('trang_thai')->default(1)->comment('0: Ngừng giao dịch, 1: Đang giao dịch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham_vang');
    }
};


