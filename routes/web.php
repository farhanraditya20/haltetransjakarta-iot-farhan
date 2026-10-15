<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HalteController;

// Rute utama wajib memanggil HalteController agar $haltes terkirim ke view 'penumpang'
Route::get('/', [HalteController::class, 'index'])->name('penumpang.index');

// Endpoint API untuk memuat data halte realtime via JavaScript Fetch
Route::get('/api/halte', [HalteController::class, 'index']);

// Halaman Dashboard Admin Simulator Kepadatan
Route::get('/admin', [HalteController::class, 'admin'])->name('admin.index');

// Proses Update Kepadatan Halte oleh Admin
Route::put('/admin/halte/{id}', [HalteController::class, 'update'])->name('admin.update');