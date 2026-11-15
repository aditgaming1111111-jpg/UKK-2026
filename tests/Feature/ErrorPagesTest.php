<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    public function test_html_errors_use_friendly_pages_without_technical_details(): void
    {
        Route::get('/__test-error/{status}', static function (string $status): never {
            abort((int) $status);
        })->whereIn('status', ['403', '404', '419', '429', '500', '503']);

        $messages = [
            403 => 'Akses ditolak',
            404 => 'Halaman tidak ditemukan',
            419 => 'Sesi kedaluwarsa',
            429 => 'Terlalu banyak permintaan',
            500 => 'Terjadi kesalahan',
            503 => 'Layanan sementara tidak tersedia',
        ];

        foreach ($messages as $status => $message) {
            $this->get('/__test-error/'.$status)
                ->assertStatus($status)
                ->assertSee($message)
                ->assertSee('Ke Dashboard')
                ->assertDontSee('Illuminate\\')
                ->assertDontSee('Stack trace');
        }
    }
}
