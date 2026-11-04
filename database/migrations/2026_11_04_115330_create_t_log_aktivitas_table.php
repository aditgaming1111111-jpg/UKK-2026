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
        Schema::create('t_log_aktivitas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('t_users')->restrictOnDelete();
            $table->string('aktivitas', 100);
            $table->text('deskripsi');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['created_at', 'id'], 't_log_aktivitas_created_idx');
            $table->index(['user_id', 'created_at'], 't_log_aktivitas_user_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_log_aktivitas');
    }
};
