<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use App\Services\CancellationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('room')
            ->where('guest_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request, CancellationService $cancellation)
    {
        $room = Room::with('roomType')->findOrFail($request->room_id);
        $policy = $cancellation->getPolicy();
        return view('bookings.create', compact('room', 'policy'));
    }

    public function store(Request $request, BookingService $svc)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
            'agreed_to_policy' => 'accepted',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($room->capacity < $validated['guests_count']) {
            return back()->withErrors(['guests_count' => 'Room capacity exceeded.']);
        }

        $errors = $svc->validateDates($validated['check_in'], $validated['check_out'], $room->id);
        if (!empty($errors)) {
            return back()->withErrors(['room_id' => $errors[0]]);
        }

        $pricing = $svc->calculateTotal($room, $validated['check_in'], $validated['check_out']);

        $booking = Booking::create([
            'booking_number' => 'BKG-' . strtoupper(uniqid()),
            'guest_id' => Auth::id(),
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
            'status' => 'pending',
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        return redirect()->route('payments.create', $booking)
            ->with('success', 'Booking created successfully! Proceed with payment.');
    }

    public function show(Booking $booking)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $booking->load('room.roomType', 'payments', 'services', 'checkIn');

        $cancellation = null;
        if (in_array($booking->status, ['pending', 'confirmed'])) {
            $cancellation = app(CancellationService::class)->canCancel($booking);
        }

        return view('bookings.show', compact('booking', 'cancellation'));
    }

    public function cancel(Request $request, Booking $booking, CancellationService $cancellation)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $result = $cancellation->cancel($booking, $request->reason);

        if ($result['refund'] > 0) {
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking cancelled. Full refund of TSh ' . number_format($result['refund'], 2) . ' has been processed.');
        }

        if ($result['penalty'] > 0) {
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking cancelled. A penalty of TSh ' . number_format($result['penalty'], 2) . ' applies. ' . ($result['refund'] > 0 ? 'Refund of TSh ' . number_format($result['refund'], 2) . ' issued.' : ''));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }
}
