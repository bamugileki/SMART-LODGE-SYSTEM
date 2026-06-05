<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();
        $rooms = Room::where('is_active', true)
            ->with('roomType', 'reviews')
            ->paginate(12);
        return view('rooms.index', compact('rooms', 'roomTypes'));
    }

    public function show($id)
    {
        $room = Room::with(['roomType', 'reviews.guest', 'reviews' => function ($q) {
            $q->where('is_approved', true);
        }])->findOrFail($id);

        return view('rooms.show', compact('room'));
    }

    public function compare(Request $request)
    {
        $ids = $request->query('ids', []);
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $rooms = Room::whereIn('id', $ids)
            ->where('is_active', true)
            ->with('roomType')
            ->get();

        if ($rooms->count() < 2) {
            return redirect()->route('rooms.index')->with('error', 'Select at least 2 rooms to compare.');
        }

        return view('rooms.compare', compact('rooms'));
    }

    public function calendar(Request $request, $id)
    {
        $room = Room::with('roomType')->findOrFail($id);

        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $startOfMonth = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endOfMonth = now()->setYear($year)->setMonth($month)->endOfMonth();

        $bookings = Booking::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in', 'checked_out'])
            ->where('check_in', '<=', $endOfMonth)
            ->where('check_out', '>=', $startOfMonth)
            ->get();

        $days = [];
        $date = $startOfMonth->copy();
        while ($date <= $endOfMonth) {
            $dayStr = $date->format('Y-m-d');
            $status = 'available';

            foreach ($bookings as $booking) {
                if ($dayStr >= $booking->check_in->format('Y-m-d') && $dayStr < $booking->check_out->format('Y-m-d')) {
                    if ($booking->status === 'checked_in') {
                        $status = 'occupied';
                    } elseif (in_array($booking->status, ['pending', 'confirmed'])) {
                        $status = 'booked';
                    } elseif ($booking->status === 'checked_out') {
                        $status = 'maintenance';
                    }
                    break;
                }
            }

            if ($date->isPast() && $status === 'available') {
                $status = 'past';
            }

            $days[] = [
                'date' => $date->copy(),
                'status' => $status,
            ];

            $date->addDay();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'year' => $year,
                'month' => $month,
                'days' => $days,
                'room' => ['id' => $room->id, 'name' => $room->name],
            ]);
        }

        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();

        return view('rooms.calendar', compact('room', 'days', 'year', 'month', 'startOfMonth', 'prevMonth', 'nextMonth'));
    }
}
