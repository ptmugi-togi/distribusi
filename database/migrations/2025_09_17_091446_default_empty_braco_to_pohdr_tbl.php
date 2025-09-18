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
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            if (!Schema::hasColumn('pohdr_tbl', 'braco')) {
                $table->string('braco', 50)->default('');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            if (Schema::hasColumn('pohdr_tbl', 'braco')) {
                $table->dropColumn('braco');
            }
        });
    }
};

