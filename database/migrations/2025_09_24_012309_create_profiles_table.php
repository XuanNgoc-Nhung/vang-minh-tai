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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ngan_hang')->nullable();
            $table->string('so_tai_khoan')->nullable();
            $table->string('chu_tai_khoan')->nullable();
            $table->decimal('so_du', 15, 2)->default(0);
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('mat_khau_rut_tien')->nullable();
            $table->string('anh_mat_truoc')->nullable();
            $table->string('anh_mat_sau')->nullable();
            $table->string('anh_chan_dung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
