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
        Schema::create('nap_rut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('loai'); // 'nap' hoặc 'rut'
            $table->decimal('so_tien', 15, 2);
            $table->string('ngan_hang');
            $table->string('so_tai_khoan');
            $table->string('chu_tai_khoan');
            $table->text('noi_dung')->nullable();
            $table->tinyInteger('trang_thai')->default(0); // 0: chờ xử lý, 1: đã xử lý, 2: từ chối
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nap_rut');
    }
};
