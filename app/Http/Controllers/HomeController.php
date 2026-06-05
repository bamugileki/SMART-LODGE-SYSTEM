<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();
        $featuredRooms = Room::where('is_active', true)
            ->with('roomType', 'reviews')
            ->take(6)
            ->get();
        return view('home', compact('roomTypes', 'featuredRooms'));
    }

    public function search()
    {
        $checkIn = request('check_in');
        $checkOut = request('check_out');
        $guests = request('guests', 1);
        $roomType = request('room_type');

        $rooms = Room::where('is_active', true)
            ->when($checkIn && $checkOut, fn($q) => $q->available($checkIn, $checkOut))
            ->when($guests, fn($q) => $q->where('capacity', '>=', $guests))
            ->when($roomType, fn($q) => $q->where('room_type_id', $roomType))
            ->with('roomType', 'reviews')
            ->get();

        $roomTypes = RoomType::all();

        return view('rooms.search', compact('rooms', 'roomTypes', 'checkIn', 'checkOut', 'guests', 'roomType'));
    }
}
