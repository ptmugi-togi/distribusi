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
            $table->double('pph')->default(0)->after('pono');
            // pakai after('pono') biar posisinya setelah kolom pono
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->dropColumn('pph');
        });
    }
};
