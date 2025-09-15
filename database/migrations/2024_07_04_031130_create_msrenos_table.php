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
        Schema::table('msrenos', function (Blueprint $table) {
            $table->id();
            $table->string('braco');
            $table->string('sreno');
            $table->string('srena');
            $table->string('steam');
            $table->text('address');
            $table->string('phone');
            $table->string('grade');
            $table->boolean('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msrenos');
    }
};
