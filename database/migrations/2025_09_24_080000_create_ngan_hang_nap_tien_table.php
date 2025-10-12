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
		Schema::create('ngan_hang_nap_tien', function (Blueprint $table) {
			$table->id();
			$table->string('ten_ngan_hang');
			$table->string('hinh_anh')->nullable();
			$table->string('so_tai_khoan');
			$table->string('chu_tai_khoan');
			$table->string('chi_nhanh')->nullable();
			$table->tinyInteger('trang_thai')->default(1);
			$table->string('ghi_chu')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ngan_hang_nap_tien');
	}
};


