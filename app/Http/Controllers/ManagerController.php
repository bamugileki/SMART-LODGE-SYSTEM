<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'active_stays' => Booking::where('status', 'checked_in')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'occupancy_rate' => Room::whereIn('status', ['occupied', 'reserved'])->count() / max(Room::count(), 1) * 100,
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];

        $recentBookings = Booking::with('guest', 'room')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $monthlyRevenue = Payment::where('status', 'paid')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('manager.dashboard', compact('stats', 'recentBookings', 'monthlyRevenue'));
    }

    public function occupancy()
    {
        $rooms = Room::withCount(['bookings' => function ($q) {
            $q->whereIn('status', ['confirmed', 'checked_in']);
        }])->with('roomType')->get();

        $totalRooms = $rooms->count();
        $occupied = $rooms->whereIn('status', ['occupied', 'reserved'])->count();
        $available = $rooms->where('status', 'available')->count();
        $maintenance = $rooms->where('status', 'maintenance')->count();

        return view('manager.occupancy', compact('rooms', 'totalRooms', 'occupied', 'available', 'maintenance'));
    }

    public function reviews()
    {
        $reviews = Review::with('guest', 'room', 'booking')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manager.reviews', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function destroyReview(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    public function reports(Request $request, ReportService $svc)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $financial = $svc->financialSummary($startDate, $endDate);
        $bookings = $svc->bookingSummary($startDate, $endDate);
        $rooms = $svc->roomPerformance($startDate, $endDate);
        $revenueByType = $svc->revenueByRoomType($startDate, $endDate);
        $reviews = $svc->reviewsSummary($startDate, $endDate);

        return view('manager.reports', compact(
            'financial', 'bookings', 'rooms', 'revenueByType', 'reviews',
            'startDate', 'endDate'
        ));
    }

    public function exportReport($type, Request $request, ReportService $svc)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $format = $request->format ?? 'pdf';

        $data = match ($type) {
            'financial' => $svc->financialSummary($startDate, $endDate),
            'bookings' => $svc->bookingSummary($startDate, $endDate),
            'rooms' => $svc->roomPerformance($startDate, $endDate),
            'revenue-by-type' => ['items' => $svc->revenueByRoomType($startDate, $endDate)],
            'reviews' => $svc->reviewsSummary($startDate, $endDate),
            default => abort(404),
        };
        $data['type'] = $type;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['generated_at'] = now()->format('M d, Y h:i A');

        $pdf = Pdf::loadView("reports.manager-{$type}", $data);
        $filename = "{$type}-report-" . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
