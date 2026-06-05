<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $rooms = Auth::user()->favoritedRooms()->with('roomType')->paginate(12);
        return view('wishlist.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate(['room_id' => 'required|exists:rooms,id']);

        $exists = Auth::user()->wishlists()->where('room_id', $request->room_id)->exists();
        if (!$exists) {
            Auth::user()->wishlists()->create(['room_id' => $request->room_id]);
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => true]);
        }

        return back()->with('success', 'Room saved to wishlist!');
    }

    public function destroy($roomId)
    {
        Auth::user()->wishlists()->where('room_id', $roomId)->delete();

        if (request()->wantsJson()) {
            return response()->json(['removed' => true]);
        }

        return back()->with('success', 'Room removed from wishlist.');
    }
}
