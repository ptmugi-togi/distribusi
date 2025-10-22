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
            $table->string('created_by')->default('')->nullable(false);
            $table->timestamp('created_at');
            $table->string('updated_by')->default('')->nullable(false);
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_by');
            $table->dropColumn('updated_at');
        });
    }
};


