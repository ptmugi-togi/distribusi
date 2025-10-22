<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('pohdr_tbl')->whereNull('braco')->update(['braco' => '']);

        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->string('braco', 50)->default('')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->string('braco', 50)->nullable()->default(null)->change();
        });
    }
};
