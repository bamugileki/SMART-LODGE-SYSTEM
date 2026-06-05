<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function checkIn(Request $request, Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['status' => 'Booking must be confirmed before check-in.']);
        }

        $booking->update(['status' => 'checked_in']);
        $booking->room->update(['status' => 'occupied']);

        CheckIn::create([
            'booking_id' => $booking->id,
            'guest_id' => $booking->guest_id,
            'room_id' => $booking->room_id,
            'receptionist_id' => Auth::id(),
            'checked_in_at' => now(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked in successfully.');
    }

    public function checkOut(Request $request, Booking $booking, BookingService $svc)
    {
        if ($booking->status !== 'checked_in') {
            return back()->withErrors(['status' => 'Guest must be checked in first.']);
        }

        $booking->update(['status' => 'checked_out']);

        $svc->markRoomAfterCheckOut($booking->room);

        $booking->checkIn->update([
            'checked_out_at' => now(),
            'notes' => $request->notes ?? $booking->checkIn->notes,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked out successfully.');
    }
}
