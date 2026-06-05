<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        $todayBookings = Booking::with('guest', 'room')
            ->whereDate('check_in', today())
            ->orderBy('created_at')
            ->get();

        $activeStays = Booking::with('guest', 'room')
            ->where('status', 'checked_in')
            ->get();

        $upcomingCheckouts = Booking::with('guest', 'room')
            ->whereDate('check_out', today())
            ->where('status', 'checked_in')
            ->get();

        $availableRooms = Room::where('status', 'available')->count();

        return view('receptionist.dashboard', compact('todayBookings', 'activeStays', 'upcomingCheckouts', 'availableRooms'));
    }

    public function searchBooking(Request $request)
    {
        $query = $request->get('query');
        $bookings = Booking::with('guest', 'room')
            ->where('booking_number', 'LIKE', "%{$query}%")
            ->orWhereHas('guest', fn($q) => $q->where('name', 'LIKE', "%{$query}%")->orWhere('email', 'LIKE', "%{$query}%"))
            ->paginate(20);

        return view('receptionist.bookings', compact('bookings'));
    }

    public function walkInCreate()
    {
        $rooms = Room::where('status', 'available')->with('roomType')->get();
        return view('receptionist.walk-in', compact('rooms'));
    }

    public function walkInStore(Request $request, BookingService $svc)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'nullable|string|max:20',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests_count' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($room->capacity < $validated['guests_count']) {
            return back()->withErrors(['guests_count' => 'Room capacity exceeded.']);
        }

        $errors = $svc->validateDates($validated['check_in'], $validated['check_out'], $room->id);
        if (!empty($errors)) {
            return back()->withErrors(['room_id' => $errors[0]]);
        }

        $guest = User::firstOrCreate(
            ['email' => $validated['guest_email']],
            [
                'name' => $validated['guest_name'],
                'phone' => $validated['guest_phone'],
                'password' => bcrypt('password123'),
                'role_id' => 1,
            ]
        );

        $pricing = $svc->calculateTotal($room, $validated['check_in'], $validated['check_out']);

        $booking = Booking::create([
            'booking_number' => 'BKG-' . strtoupper(uniqid()),
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests_count' => $validated['guests_count'],
            'price_per_night' => $room->price_per_night,
            'total_nights' => $pricing['nights'],
            'subtotal' => $pricing['subtotal'],
            'services_total' => 0,
            'discount' => 0,
            'total' => $pricing['total'],
            'status' => 'confirmed',
        ]);

        return redirect()->route('receptionist.dashboard')
            ->with('success', "Walk-in booking created for {$guest->name}.");
    }

    public function processCheckIn(Request $request, Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['status' => 'Booking must be confirmed before check-in.']);
        }

        $validated = $request->validate([
            'national_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->update(['status' => 'checked_in']);
        $booking->room->update(['status' => 'occupied']);

        if ($validated['national_id'] && $booking->guest) {
            $booking->guest->update(['national_id' => $validated['national_id']]);
        }

        CheckIn::create([
            'booking_id' => $booking->id,
            'guest_id' => $booking->guest_id,
            'room_id' => $booking->room_id,
            'receptionist_id' => Auth::id(),
            'checked_in_at' => now(),
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked in successfully.');
    }

    public function processCheckOut(Request $request, Booking $booking, BookingService $svc)
    {
        if ($booking->status !== 'checked_in') {
            return back()->withErrors(['status' => 'Guest must be checked in first.']);
        }

        $validated = $request->validate([
            'extra_charges' => 'nullable|numeric|min:0',
            'extra_charges_reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $extraCharges = $validated['extra_charges'] ?? 0;

        $booking->update([
            'status' => 'checked_out',
            'extra_charges' => $extraCharges,
            'total' => $booking->total + $extraCharges,
        ]);

        $svc->markRoomAfterCheckOut($booking->room);

        $booking->checkIn->update([
            'checked_out_at' => now(),
            'notes' => $validated['notes'] ?? $booking->checkIn->notes,
        ]);

        if ($extraCharges > 0) {
            Payment::create([
                'transaction_id' => 'EXT-' . strtoupper(uniqid()),
                'booking_id' => $booking->id,
                'amount' => $extraCharges,
                'method' => 'cash',
                'status' => 'paid',
                'paid_at' => now(),
                'notes' => 'Extra charges: ' . ($validated['extra_charges_reason'] ?? 'N/A'),
                'processed_by' => Auth::id(),
            ]);
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked out successfully.');
    }

    public function approveBooking(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking is not pending.');
        }

        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking approved.');
    }

    public function rejectBooking(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking is not pending.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'Rejected by receptionist',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
        ]);

        return back()->with('success', 'Booking rejected.');
    }
}
