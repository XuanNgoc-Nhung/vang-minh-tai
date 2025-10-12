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
        Schema::table('dau_tu', function (Blueprint $table) {
            $table->decimal('hoa_hong', 10, 2)->default(0)->after('so_chu_ky');
            $table->tinyInteger('trang_thai')->default(0)->after('hoa_hong');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dau_tu', function (Blueprint $table) {
            $table->dropColumn(['hoa_hong', 'trang_thai']);
        });
    }
};


