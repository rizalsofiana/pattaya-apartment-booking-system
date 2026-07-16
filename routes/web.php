<?php

use App\Http\Controllers\Guest\RoomController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{slug}', [RoomController::class, 'show'])->name('rooms.show');

// Rute Pendukung: Fitur Switcher Multi-Bahasa (EN / TH)
Route::get('/lang/{locale}', function ($locale) {
    // Validasi agar pilihan bahasa hanya boleh 'en' atau 'th'
    if (in_array($locale, ['en', 'th'])) {
        Session::put('locale', $locale);
    }

    // Kembalikan tamu ke halaman sebelumnya setelah bahasa diubah
    return redirect()->back();
})->name('lang.switch');
