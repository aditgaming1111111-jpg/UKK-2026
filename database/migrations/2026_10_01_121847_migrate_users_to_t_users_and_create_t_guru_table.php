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
        if (! Schema::hasTable('users')) {
            throw new RuntimeException('Tabel users Laravel tidak ditemukan; migration autentikasi dihentikan.');
        }

        if (DB::table('users')->exists()) {
            throw new RuntimeException('Tabel users berisi data. Tetapkan role dan strategi pemindahan akun sebelum migration ini dijalankan.');
        }

        Schema::rename('users', 't_users');

        Schema::table('t_users', function (Blueprint $table): void {
            $table->renameColumn('name', 'nama');
            $table->dropColumn(['email_verified_at', 'remember_token']);
            $table->string('role', 20);
        });

        Schema::create('t_guru', function (Blueprint $table): void {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('email');
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('user_id')->unique()->constrained('t_users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('t_users')->exists() || DB::table('t_guru')->exists()) {
            throw new RuntimeException('Migration dibatalkan agar akun autentikasi yang sudah tersimpan tidak hilang.');
        }

        Schema::drop('t_guru');

        Schema::table('t_users', function (Blueprint $table): void {
            $table->dropColumn('role');
            $table->renameColumn('nama', 'name');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
        });

        Schema::rename('t_users', 'users');
    }
};
