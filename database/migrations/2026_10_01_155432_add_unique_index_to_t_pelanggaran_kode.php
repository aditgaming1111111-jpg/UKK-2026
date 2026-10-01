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
        $duplicateCode = DB::table('t_pelanggaran')
            ->select('kode')
            ->groupBy('kode')
            ->havingRaw('COUNT(*) > 1')
            ->value('kode');

        if ($duplicateCode !== null) {
            throw new RuntimeException("Unique index kode pelanggaran tidak dapat dibuat. Perbaiki kode duplikat terlebih dahulu: {$duplicateCode}");
        }

        Schema::table('t_pelanggaran', function (Blueprint $table): void {
            $table->unique('kode', 't_pelanggaran_kode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_pelanggaran', function (Blueprint $table): void {
            $table->dropUnique('t_pelanggaran_kode_unique');
        });
    }
};
