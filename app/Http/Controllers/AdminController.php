<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\Payment;
use App\Models\QuickLink;
use App\Models\Review;
use App\Models\Role;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_guests' => Booking::where('status', 'checked_in')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'total_users' => User::count(),
            'occupancy_rate' => Room::whereIn('status', ['occupied', 'reserved'])->count() / max(Room::count(), 1) * 100,
        ];

        $recentBookings = Booking::with('guest', 'room')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $revenueByMonth = Payment::where('status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $cardValues = [
            'Total Bookings' => ['value' => $stats['total_bookings'], 'sub' => "{$stats['pending_bookings']} pending", 'color' => 'text-yellow-600'],
            'Available Rooms' => ['value' => $stats['available_rooms'], 'sub' => "out of {$stats['total_rooms']} rooms", 'color' => 'text-green-600'],
            'Occupied Rooms' => ['value' => Room::where('status', 'occupied')->count(), 'sub' => 'currently occupied', 'color' => 'text-red-600'],
            "Today's Revenue" => ['value' => 'TSh' . number_format(Payment::whereDate('created_at', today())->where('status', 'paid')->sum('amount'), 2), 'sub' => 'today', 'color' => 'text-green-600'],
            'Total Guests' => ['value' => $stats['active_guests'], 'sub' => 'active stays', 'color' => 'text-blue-600'],
            'Pending Check-Ins' => ['value' => Booking::where('status', 'confirmed')->whereDate('check_in', today())->count(), 'sub' => 'today', 'color' => 'text-yellow-600'],
            'Pending Check-Outs' => ['value' => Booking::where('status', 'checked_in')->whereDate('check_out', today())->count(), 'sub' => 'today', 'color' => 'text-orange-600'],
            'System Alerts' => ['value' => \App\Models\AuditLog::whereDate('created_at', today())->count(), 'sub' => 'events today', 'color' => 'text-gray-600'],
        ];

        $cardLinks = QuickLink::section('admin_cards')->get();
        $cards = $cardLinks->map(function ($link) use ($cardValues) {
            $data = $cardValues[$link->label] ?? ['value' => '0', 'sub' => null, 'color' => null];
            return (object) [
                'label' => $link->label,
                'url' => $link->url,
                'value' => $data['value'],
                'sub_text' => $data['sub'],
                'sub_color' => $data['color'],
            ];
        });

        $quickLinks = QuickLink::section('admin_dashboard')->get();
        $groupedQuickLinks = $quickLinks;

        return view('admin.dashboard', compact('stats', 'recentBookings', 'revenueByMonth', 'cards', 'quickLinks', 'groupedQuickLinks'));
    }

    public function users()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate(['role_id' => 'required|exists:roles,id']);
        $user->update(['role_id' => $validated['role_id']]);
        return back()->with('success', 'User role updated.');
    }

    public function rooms()
    {
        $rooms = Room::with('roomType')->orderBy('name')->paginate(20);
        $roomTypes = RoomType::all();
        return view('admin.rooms', compact('rooms', 'roomTypes'));
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'size_sqft' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $validated['amenities'] = array_values($validated['amenities'] ?? []);

        Room::create($validated);

        return redirect()->route('admin.rooms')->with('success', 'Room created.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'size_sqft' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'is_active' => 'boolean',
        ]);

        $validated['amenities'] = array_values($validated['amenities'] ?? []);

        $room->update($validated);

        return redirect()->route('admin.rooms')->with('success', 'Room updated.');
    }

    public function destroyRoom(Room $room)
    {
        $room->update(['is_active' => false]);
        return back()->with('success', 'Room deactivated.');
    }

    public function bookings()
    {
        $bookings = Booking::with('guest', 'room')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.bookings', compact('bookings'));
    }

    public function services()
    {
        $services = Service::orderBy('name')->paginate(20);
        return view('admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = \Str::slug($validated['name']);
        Service::create($validated);

        return redirect()->route('admin.services')->with('success', 'Service created.');
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services')->with('success', 'Service updated.');
    }

    public function destroyService(Service $service)
    {
        $service->update(['is_active' => false]);
        return back()->with('success', 'Service deactivated.');
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users-create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'national_id' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users-edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'national_id' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $user->update(['is_active' => false, 'status' => 'inactive']);
        return back()->with('success', 'User deactivated.');
    }

    public function activateUser(User $user)
    {
        $user->update(['is_active' => true, 'status' => 'active']);
        return back()->with('success', 'User activated.');
    }

    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);

        $user->update(['password' => bcrypt($request->password)]);

        return back()->with('success', 'Password reset successfully.');
    }

    public function checkins()
    {
        $checkIns = CheckIn::with('booking.guest', 'room', 'receptionist')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.checkins', compact('checkIns'));
    }

    public function forceCheckout(CheckIn $checkIn)
    {
        if (!$checkIn->checked_out_at) {
            $checkIn->update(['checked_out_at' => now(), 'notes' => 'Force checked out by admin']);
            $checkIn->booking->update(['status' => 'checked_out']);
            $checkIn->room->update(['status' => 'available']);
            return back()->with('success', 'Guest force checked out.');
        }
        return back()->with('error', 'Guest already checked out.');
    }

    public function payments()
    {
        $payments = Payment::with('booking.guest', 'processor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.payments', compact('payments'));
    }

    public function confirmPayment(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment is not pending.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        $payment->booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Payment confirmed.');
    }

    public function refundPayment(Request $request, Payment $payment)
    {
        if ($payment->status !== 'paid') {
            return back()->with('error', 'Payment is not paid.');
        }

        $payment->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'notes' => $request->input('notes', $payment->notes),
        ]);

        $payment->booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Payment refunded.');
    }

    public function reviews()
    {
        $reviews = Review::with('guest', 'room', 'booking')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.reviews', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function settings()
    {
        $settings = Setting::allToArray();
        $roles = Role::all();
        return view('admin.settings', compact('settings', 'roles'));
    }

    public function updateSettings(Request $request)
    {
        $keys = [
            // Hotel Info
            'hotel_name', 'hotel_slogan', 'hotel_description', 'hotel_email', 'hotel_phone',
            'whatsapp_number', 'hotel_address', 'google_maps_link',
            // Currency & Pricing
            'default_currency', 'tax_rate', 'service_charge_percentage', 'booking_fee',
            'min_booking_price', 'price_rounding',
            // Booking Rules
            'min_stay', 'max_stay', 'cancellation_policy', 'free_cancellation_window',
            'booking_auto_confirm', 'allow_same_day_booking',
            // Check-in / Out
            'checkin_time', 'checkout_time', 'late_checkout_fee', 'early_checkin_fee', 'grace_period_minutes',
            // Room Config
            'default_room_status', 'auto_room_availability', 'room_hold_minutes', 'overbooking_allowance',
            'room_cleaning_reset',
            // Payment
            'enable_cash', 'enable_mobile_money', 'enable_stripe',
            'payment_auto_confirm', 'refund_policy', 'payment_timeout_minutes',
            'hotel_bank_name', 'hotel_account_number', 'hotel_account_holder',
            'hotel_mobile_provider', 'hotel_mobile_number',
            // Notifications
            'enable_email_notifications', 'enable_sms_notifications', 'enable_whatsapp_notifications',
            'template_booking_confirmation', 'template_payment_confirmation',
            'template_checkin_reminder', 'template_checkout_reminder',
            // Registration
            'enable_registration', 'require_email_verification', 'require_phone_verification',
            'auto_approve_users', 'default_user_role',
            // Security
            'session_timeout_minutes', 'login_attempt_limit', 'account_lock_attempts',
            'enable_2fa', 'password_strength_rules',
            // Logging
            'enable_audit_logs', 'log_login_activities', 'log_booking_changes',
            'log_payment_changes', 'log_admin_actions',
            // Housekeeping
            'auto_assign_cleaning', 'cleaning_status_reset', 'cleaning_notification_triggers', 'cleaning_priority_rules',
            // Services
            'enable_services_module', 'default_service_pricing', 'service_tax_rules', 'service_categories',
            // Performance
            'cache_settings', 'db_backup_frequency', 'report_generation_schedule',
            'maintenance_mode', 'api_rate_limits',
            // Language
            'default_language', 'enable_multi_language', 'date_format', 'time_format',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                Setting::set($key, $value);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function reports(Request $request, ReportService $svc)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $financial = $svc->financialSummary($startDate, $endDate);
        $refunds = $svc->refundSummary($startDate, $endDate);
        $pending = $svc->pendingPayments();
        $failed = $svc->failedPayments($startDate, $endDate);
        $bookings = $svc->bookingSummary($startDate, $endDate);
        $rooms = $svc->roomPerformance($startDate, $endDate);
        $users = $svc->userSummary($startDate, $endDate);
        $revenueByType = $svc->revenueByRoomType($startDate, $endDate);
        $reviews = $svc->reviewsSummary($startDate, $endDate);

        return view('admin.reports', compact(
            'financial', 'refunds', 'pending', 'failed', 'bookings',
            'rooms', 'users', 'revenueByType', 'reviews',
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
            'users' => $svc->userSummary($startDate, $endDate),
            'revenue-by-type' => ['items' => $svc->revenueByRoomType($startDate, $endDate)],
            'reviews' => $svc->reviewsSummary($startDate, $endDate),
            default => abort(404),
        };
        $data['type'] = $type;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['generated_at'] = now()->format('M d, Y h:i A');

        if ($format === 'csv') {
            return $this->downloadCsv($type, $data);
        }

        $pdf = Pdf::loadView("reports.admin-{$type}", $data);
        $filename = "{$type}-report-" . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    private function downloadCsv($type, $data)
    {
        $filename = "{$type}-report-" . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $data) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'financial':
                    fputcsv($handle, ['Date', 'Revenue', 'Transactions']);
                    foreach ($data['daily'] as $row) {
                        fputcsv($handle, [$row->date, $row->total, $row->count]);
                    }
                    break;
                case 'bookings':
                    fputcsv($handle, ['Status', 'Count']);
                    foreach ($data['byStatus'] as $status => $count) {
                        fputcsv($handle, [$status, $count]);
                    }
                    break;
                case 'rooms':
                    fputcsv($handle, ['Room', 'Bookings', 'Revenue']);
                    foreach ($data['rooms'] as $room) {
                        fputcsv($handle, [$room->name, $room->bookings_count, $room->revenue ?? 0]);
                    }
                    break;
                case 'revenue-by-type':
                    fputcsv($handle, ['Room Type', 'Revenue', 'Bookings']);
                    foreach ($data['items'] as $row) {
                        fputcsv($handle, [$row->name, $row->total, $row->count]);
                    }
                    break;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
