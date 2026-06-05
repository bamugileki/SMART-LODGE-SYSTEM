<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function financialSummary($startDate = null, $endDate = null)
    {
        $query = Payment::where('status', 'paid');
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        $totalRevenue = (clone $query)->sum('amount');
        $totalPayments = (clone $query)->count();
        $avgPayment = (clone $query)->avg('amount');

        $byMethod = (clone $query)
            ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return compact('totalRevenue', 'totalPayments', 'avgPayment', 'byMethod', 'daily');
    }

    public function refundSummary($startDate = null, $endDate = null)
    {
        $query = Payment::where('status', 'refunded');
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        return [
            'total_refunds' => (clone $query)->sum('amount'),
            'refund_count' => (clone $query)->count(),
        ];
    }

    public function pendingPayments()
    {
        return Payment::where('status', 'pending')
            ->with('booking.guest', 'booking.room')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function failedPayments($startDate = null, $endDate = null)
    {
        $query = Payment::where('status', 'failed');
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        return [
            'total_failed' => (clone $query)->count(),
            'failed_amount' => (clone $query)->sum('amount'),
            'recent_failed' => (clone $query)->with('booking.guest')->orderBy('created_at', 'desc')->take(20)->get(),
        ];
    }

    public function bookingSummary($startDate = null, $endDate = null)
    {
        $query = Booking::query();
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        $total = (clone $query)->count();
        $byStatus = (clone $query)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $confirmed = $byStatus->get('confirmed', 0) + $byStatus->get('checked_in', 0) + $byStatus->get('checked_out', 0);
        $cancelled = $byStatus->get('cancelled', 0);
        $pending = $byStatus->get('pending', 0);

        $monthlyTrend = (clone $query)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $conversion = $total > 0 ? round(($confirmed / $total) * 100, 1) : 0;

        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return compact('total', 'byStatus', 'confirmed', 'cancelled', 'pending', 'monthlyTrend', 'conversion', 'daily');
    }

    public function roomPerformance($startDate = null, $endDate = null)
    {
        $bookingQuery = Booking::whereIn('status', ['confirmed', 'checked_in', 'checked_out']);
        if ($startDate) $bookingQuery->whereDate('check_in', '>=', $startDate);
        if ($endDate) $bookingQuery->whereDate('check_out', '<=', $endDate);

        $rooms = Room::with('roomType')
            ->withCount(['bookings' => function ($q) {
                $q->whereIn('status', ['confirmed', 'checked_in', 'checked_out']);
            }])
            ->withSum(['bookings as revenue' => function ($q) {
                $q->whereIn('status', ['confirmed', 'checked_in', 'checked_out']);
            }], 'total')
            ->get();

        $totalRooms = $rooms->count();
        $occupied = Room::whereIn('status', ['occupied', 'reserved'])->count();
        $available = Room::where('status', 'available')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100, 1) : 0;

        $mostBooked = $rooms->sortByDesc('bookings_count')->take(5)->values();
        $leastBooked = $rooms->sortBy('bookings_count')->take(5)->values();
        $topRevenue = $rooms->sortByDesc('revenue')->take(5)->values();

        return compact('rooms', 'totalRooms', 'occupied', 'available', 'occupancyRate', 'mostBooked', 'leastBooked', 'topRevenue');
    }

    public function userSummary($startDate = null, $endDate = null)
    {
        $query = User::query();
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        $total = (clone $query)->count();
        $active = (clone $query)->where('is_active', true)->count();
        $guests = (clone $query)->whereHas('role', fn($q) => $q->where('slug', 'guest'))->count();

        $trend = (clone $query)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return compact('total', 'active', 'guests', 'trend');
    }

    public function revenueByRoomType($startDate = null, $endDate = null)
    {
        $query = Payment::where('payments.status', 'paid')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id');

        if ($startDate) $query->whereDate('payments.created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('payments.created_at', '<=', $endDate);

        return $query->selectRaw('room_types.name, SUM(payments.amount) as total, COUNT(*) as count')
            ->groupBy('room_types.id', 'room_types.name')
            ->orderByDesc('total')
            ->get();
    }

    public function reviewsSummary($startDate = null, $endDate = null)
    {
        $query = Review::where('is_approved', true);
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);

        $total = (clone $query)->count();
        $avgRating = (clone $query)->avg('rating');
        $pending = Review::where('is_approved', false)->count();
        $perRoom = (clone $query)
            ->selectRaw('room_id, COUNT(*) as count, AVG(rating) as avg_rating')
            ->groupBy('room_id')
            ->with('room')
            ->get();

        $ratings = (clone $query)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating');

        return compact('total', 'avgRating', 'pending', 'perRoom', 'ratings');
    }
}
