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
        Schema::table('t_pelanggaran_siswa', function (Blueprint $table): void {
            $table->uuid('idempotency_key')->nullable();
            $table->unique(['guru_id', 'idempotency_key'], 't_ps_guru_idempotency_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_pelanggaran_siswa', function (Blueprint $table): void {
            $table->dropUnique('t_ps_guru_idempotency_unique');
            $table->dropColumn('idempotency_key');
        });
    }
};
