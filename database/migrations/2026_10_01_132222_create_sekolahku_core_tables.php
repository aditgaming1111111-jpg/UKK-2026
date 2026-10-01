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
        Schema::create('t_siswa', function (Blueprint $table): void {
            $table->id();
            $table->string('nisn', 10)->unique();
            $table->string('nama');
            $table->char('jenis_kelamin', 1);
            $table->text('alamat')->nullable();
            $table->unsignedSmallInteger('tahun_masuk');
            $table->string('status_siswa', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('t_kelas', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_kelas', 50);
            $table->string('tingkat', 20);
            $table->string('jurusan')->nullable();
            $table->timestamps();
        });

        Schema::create('t_tahun_ajaran', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_tahun_ajaran', 20);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('status_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('t_pelanggaran_kategori', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('t_kelas_siswa', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('siswa_id')->constrained('t_siswa')->restrictOnDelete();
            $table->foreignId('kelas_id')->constrained('t_kelas')->restrictOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('t_tahun_ajaran')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->index(['siswa_id', 'tahun_ajaran_id']);
            $table->index(['kelas_id', 'tahun_ajaran_id']);
        });

        Schema::create('t_wali_kelas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('guru_id')->constrained('t_guru')->restrictOnDelete();
            $table->foreignId('kelas_id')->constrained('t_kelas')->restrictOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('t_tahun_ajaran')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->index(['kelas_id', 'tahun_ajaran_id', 'status_aktif']);
            $table->index(['guru_id', 'tahun_ajaran_id']);
        });

        Schema::create('t_pelanggaran', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kategori_id')->constrained('t_pelanggaran_kategori')->restrictOnDelete();
            $table->string('kode', 30);
            $table->string('nama_pelanggaran');
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('poin');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('t_pelanggaran_siswa', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kelas_siswa_id')->constrained('t_kelas_siswa')->restrictOnDelete();
            $table->foreignId('pelanggaran_id')->constrained('t_pelanggaran')->restrictOnDelete();
            $table->foreignId('guru_id')->constrained('t_guru')->restrictOnDelete();
            $table->date('tanggal_pelanggaran');
            $table->dateTime('tanggal_dicatat');
            $table->unsignedSmallInteger('poin_saat_dicatat');
            $table->text('keterangan')->nullable();
            $table->text('tindakan')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->index(['kelas_siswa_id', 'status', 'tanggal_pelanggaran'], 't_ps_kelas_status_tanggal_idx');
            $table->index(['status', 'created_at'], 't_ps_status_created_at_idx');
        });

        Schema::create('t_log_pelanggaran', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pelanggaran_siswa_id')->constrained('t_pelanggaran_siswa')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('t_users')->restrictOnDelete();
            $table->string('aksi', 20);
            $table->text('data_sebelum')->nullable();
            $table->text('data_sesudah')->nullable();
            $table->text('alasan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['pelanggaran_siswa_id', 'created_at'], 't_log_ps_created_at_idx');
            $table->index(['user_id', 'created_at'], 't_log_user_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ([
            't_log_pelanggaran',
            't_pelanggaran_siswa',
            't_pelanggaran',
            't_wali_kelas',
            't_kelas_siswa',
            't_pelanggaran_kategori',
            't_tahun_ajaran',
            't_kelas',
            't_siswa',
        ] as $table) {
            if (Schema::hasTable($table) && DB::table($table)->exists()) {
                throw new RuntimeException("Rollback dibatalkan agar data pada {$table} tidak dihapus.");
            }
        }

        Schema::dropIfExists('t_log_pelanggaran');
        Schema::dropIfExists('t_pelanggaran_siswa');
        Schema::dropIfExists('t_pelanggaran');
        Schema::dropIfExists('t_wali_kelas');
        Schema::dropIfExists('t_kelas_siswa');
        Schema::dropIfExists('t_pelanggaran_kategori');
        Schema::dropIfExists('t_tahun_ajaran');
        Schema::dropIfExists('t_kelas');
        Schema::dropIfExists('t_siswa');
    }
};
