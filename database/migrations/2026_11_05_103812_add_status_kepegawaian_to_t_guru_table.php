<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $characterLengthFunction = DB::connection()->getDriverName() === 'sqlite' ? 'LENGTH' : 'CHAR_LENGTH';

        if (DB::table('t_guru')->whereNotNull('nip')->whereRaw($characterLengthFunction.'(nip) > ?', [18])->exists()) {
            throw new RuntimeException('Migration dibatalkan: terdapat NIP lebih dari 18 karakter. Periksa data Guru sebelum mengubah kolom.');
        }

        Schema::table('t_guru', function (Blueprint $table): void {
            $table->string('status_kepegawaian', 20)->nullable()->after('nip');
            $table->string('nip', 18)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('t_guru')->whereNotNull('status_kepegawaian')->exists()
            || DB::table('t_guru')->whereNull('nip')->exists()) {
            throw new RuntimeException('Rollback dibatalkan agar status kepegawaian dan NIP kosong yang sudah tersimpan tidak hilang.');
        }

        Schema::table('t_guru', function (Blueprint $table): void {
            $table->dropColumn('status_kepegawaian');
            $table->string('nip', 255)->nullable(false)->change();
        });
    }
};
