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
        Schema::create('t_proses_transisi_siswa', function (Blueprint $table): void {
            $table->id();
            $table->uuid('batch_id')->unique();
            $table->foreignId('tahun_ajaran_asal_id')->constrained('t_tahun_ajaran')->restrictOnDelete();
            $table->foreignId('tahun_ajaran_tujuan_id')->nullable()->constrained('t_tahun_ajaran')->restrictOnDelete();
            $table->string('jenis_proses', 20);
            $table->string('status', 20)->default('selesai');
            $table->unsignedInteger('jumlah_diproses');
            $table->unsignedInteger('jumlah_dikecualikan')->default(0);
            $table->foreignId('user_id')->constrained('t_users')->restrictOnDelete();
            $table->json('data_snapshot');
            $table->foreignId('dibatalkan_oleh_user_id')->nullable()->constrained('t_users')->restrictOnDelete();
            $table->timestamp('dibatalkan_pada')->nullable();
            $table->timestamps();
            $table->index(['tahun_ajaran_asal_id', 'created_at'], 't_proses_transisi_asal_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_proses_transisi_siswa');
    }
};
