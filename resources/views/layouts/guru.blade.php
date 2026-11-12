{{-- Menyiapkan kerangka bersama untuk merangkai bagian antarmuka dan konten halaman. --}}
@extends('layouts.app-shell', ['role' => 'guru', 'hasActiveWaliKelas' => $hasActiveWaliKelas ?? false])
