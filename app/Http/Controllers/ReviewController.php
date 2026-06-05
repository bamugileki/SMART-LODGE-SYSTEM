<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->guest_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'checked_out') {
            return back()->withErrors(['status' => 'You can only review after check-out.']);
        }

        if ($booking->review) {
            return back()->withErrors(['status' => 'You have already reviewed this booking.']);
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->guest_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'guest_id' => Auth::id(),
            'room_id' => $booking->room_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => false,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Review submitted! Awaiting approval.');
    }
}
