<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'auditable_id' => $auditable?->getKey(),
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    public static function bookingCreated($booking): void
    {
        self::log('BOOKING_CREATED', 'Booking', "Booking {$booking->booking_number} created for {$booking->room->name}", $booking);
    }

    public static function bookingStatusChanged($booking, string $oldStatus, string $newStatus): void
    {
        self::log('BOOKING_STATUS_CHANGED', 'Booking', "Booking {$booking->booking_number} changed from {$oldStatus} to {$newStatus}", $booking, ['status' => $oldStatus], ['status' => $newStatus]);
    }

    public static function paymentProcessed($payment): void
    {
        $method = str_replace('_', ' ', $payment->method);
        self::log('PAYMENT_PROCESSED', 'Payment', "Payment of TSh{$payment->amount} via {$method} for booking {$payment->booking?->booking_number}", $payment);
    }

    public static function paymentVerified($payment): void
    {
        self::log('PAYMENT_VERIFIED', 'Payment', "Payment #{$payment->id} verified", $payment, ['status' => $payment->getOriginal('status')], ['status' => $payment->status]);
    }

    public static function checkIn($booking): void
    {
        self::log('CHECKIN', 'CheckIn', "Guest checked in to {$booking->room->name} (Booking {$booking->booking_number})", $booking);
    }

    public static function checkOut($booking): void
    {
        self::log('CHECKOUT', 'CheckIn', "Guest checked out from {$booking->room->name} (Booking {$booking->booking_number})", $booking);
    }

    public static function reviewSubmitted($review): void
    {
        self::log('REVIEW_SUBMITTED', 'Review', "Review submitted for {$review->room->name} by {$review->guest->name}", $review);
    }

    public static function userCreated($user): void
    {
        self::log('USER_CREATED', 'User', "User {$user->name} ({$user->email}) created with role {$user->role?->slug}", $user);
    }

    public static function userUpdated($user, array $changes): void
    {
        self::log('USER_UPDATED', 'User', "User {$user->name} updated", $user, null, $changes);
    }

    public static function payrollGenerated($payroll): void
    {
        self::log('PAYROLL_GENERATED', 'Payroll', "Payroll for {$payroll->user->name} for {$payroll->month}: TSh{$payroll->total_salary}", $payroll);
    }

    public static function payrollApproved($payroll): void
    {
        self::log('PAYROLL_APPROVED', 'Payroll', "Payroll for {$payroll->user->name} for {$payroll->month} approved", $payroll);
    }

    public static function payrollPaid($payroll): void
    {
        self::log('PAYROLL_PAID', 'Payroll', "Payroll for {$payroll->user->name} for {$payroll->month} marked as paid", $payroll);
    }

    public static function loginFailed(string $email, string $reason): void
    {
        self::log('LOGIN_FAILED', 'Security', "Failed login attempt for {$email}: {$reason}");
    }

    public static function reportGenerated(string $type, string $format): void
    {
        self::log('REPORT_GENERATED', 'Report', "{$type} report exported as {$format}");
    }
}
