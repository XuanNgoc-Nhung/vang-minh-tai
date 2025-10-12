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
        Schema::table('users', function (Blueprint $table) {
            // Bỏ chỉ mục unique ở cột email
            $table->dropUnique(['email']);
            
            // Thêm cột phone
            $table->string('phone')->nullable()->after('email');
            
            // Thêm cột role với giá trị mặc định là 0
            $table->integer('role')->default(0)->after('phone');
            
            // Thêm cột status với giá trị mặc định là 1
            $table->integer('status')->default(1)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn(['phone', 'role', 'status']);
            
            // Khôi phục chỉ mục unique cho cột email
            $table->unique('email');
        });
    }
};
