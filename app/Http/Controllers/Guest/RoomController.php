<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi query dengan Eager Loading untuk mencegah masalah N+1 Query Performance
        $query = Room::with([
            'property',
            'amenities',
            'images' => function ($q) {
                $q->where('is_primary', true); // Hanya ambil 1 foto utama untuk efisiensi list
            }
        ])->where('is_active', true); // Pastikan hanya kamar aktif yang muncul

        // 2. Filter berdasarkan Jumlah Tamu (Adults)
        if ($request->filled('guests')) {
            $query->where('capacity_adults', '>=', $request->input('guests'));
        }

        // 3. Eksekusi query dan ambil data terbaru
        $rooms = $query->latest()->get();

        // 4. Kirim data kamar ke file view resources/views/rooms/index.blade.php
        return view('rooms.index', compact('rooms'));
    }

    public function show($slug)
    {
        // Ambil kamar berdasarkan slug, pastikan aktif, dan muat semua relasinya
        $room = Room::with(['property', 'amenities', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('rooms.show', compact('room'));
    }
}
