<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::with([
            'property',
            'images' => function ($query) {
                $query->where('is_primary', true)->orWhere('sort_order', 0);
            }
        ])
        ->where('is_active', true)
        ->get();

        return view('welcome', compact('rooms'));
    }

}
