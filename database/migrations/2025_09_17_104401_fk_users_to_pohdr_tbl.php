<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // simpan SQL_MODE lama
        DB::statement("SET @OLD_SQL_MODE=@@SESSION.sql_mode");

        // longgarkan session: hapus STRICT & zero-date checks (jika ada)
        DB::statement("
            SET SESSION sql_mode = (
                SELECT REPLACE(
                    REPLACE(
                        REPLACE(@@SESSION.sql_mode, 'STRICT_TRANS_TABLES', ''),
                    'NO_ZERO_DATE',''),
                'NO_ZERO_IN_DATE','')
            )
        ");
        // (opsi paling aman kalau ragu versi MySQL): DB::statement("SET SESSION sql_mode=''");

        // tambah kolom kalau belum ada
        Schema::table('pohdr_tbl', function (Blueprint $t) {
            if (!Schema::hasColumn('pohdr_tbl','user_id')) {
                $t->foreignId('user_id')->nullable()->after('supno');
            }
        });

        // pasang FK (pakai raw agar dieksekusi dalam sesi SQL_MODE longgar)
        DB::statement("
            ALTER TABLE `pohdr_tbl`
            ADD CONSTRAINT `pohdr_tbl_user_id_foreign`
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            ON DELETE SET NULL ON UPDATE CASCADE
        ");

        // kembalikan SQL_MODE seperti semula
        DB::statement("SET SESSION sql_mode=@OLD_SQL_MODE");
    }

    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropColumn('user_id');
        });
    }
};
