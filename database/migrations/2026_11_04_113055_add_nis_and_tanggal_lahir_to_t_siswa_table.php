<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan identitas NIS dan tanggal lahir sebagai data opsional siswa.
     */
    public function up(): void
    {
        Schema::table('t_siswa', function (Blueprint $table): void {
            $table->string('nis', 10)->nullable()->unique();
            $table->date('tanggal_lahir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom identitas tambahan dari tabel siswa.
     */
    public function down(): void
    {
        Schema::table('t_siswa', function (Blueprint $table): void {
            $table->dropUnique('t_siswa_nis_unique');
            $table->dropColumn(['nis', 'tanggal_lahir']);
        });
    }
};
