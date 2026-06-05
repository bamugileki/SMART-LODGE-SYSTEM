<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;

class CancellationService
{
    private array $settings;

    public function __construct()
    {
        $this->settings = Setting::allToArray();
    }

    public function getPolicy(): array
    {
        return [
            'policy' => $this->settings['cancellation_policy'] ?? 'moderate',
            'free_window_hours' => (int)($this->settings['free_cancellation_window'] ?? 48),
            'summary' => $this->getPolicySummary(),
        ];
    }

    public function getPolicySummary(): string
    {
        $hours = (int)($this->settings['free_cancellation_window'] ?? 48);
        $policy = $this->settings['cancellation_policy'] ?? 'moderate';

        return match ($policy) {
            'flexible' => "Free cancellation up to {$hours} hours before check-in. Late cancellations may incur a penalty.",
            'moderate' => "Free cancellation up to {$hours} hours before check-in. Late cancellations are subject to a 50% charge.",
            'strict' => "Free cancellation up to {$hours} hours before check-in. Cancellations after this period are non-refundable.",
            'non_refundable' => 'All bookings are non-refundable once confirmed.',
            default => "Free cancellation up to {$hours} hours before check-in.",
        };
    }

    public function canCancel(Booking $booking): array
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return ['allowed' => false, 'reason' => 'This booking cannot be cancelled.', 'penalty' => 0, 'refund' => 0];
        }

        $now = Carbon::now();
        $checkIn = Carbon::parse($booking->check_in);
        $hoursUntilCheckIn = $now->diffInHours($checkIn, false);

        $policy = $this->settings['cancellation_policy'] ?? 'moderate';
        $freeWindow = (int)($this->settings['free_cancellation_window'] ?? 48);

        if ($hoursUntilCheckIn < 0) {
            $totalPaid = $booking->payments()->where('status', 'paid')->sum('amount');
            return [
                'allowed' => true,
                'reason' => 'No-show. Check-in date has passed. Non-refundable.',
                'penalty' => $totalPaid,
                'refund' => 0,
                'no_show' => true,
            ];
        }

        if ($policy === 'non_refundable') {
            $totalPaid = $booking->payments()->where('status', 'paid')->sum('amount');
            return [
                'allowed' => true,
                'reason' => 'Non-refundable policy. No refund will be issued.',
                'penalty' => $totalPaid,
                'refund' => 0,
            ];
        }

        if ($hoursUntilCheckIn >= $freeWindow) {
            $totalPaid = $booking->payments()->where('status', 'paid')->sum('amount');
            return [
                'allowed' => true,
                'reason' => 'Within free cancellation window. Full refund applicable.',
                'penalty' => 0,
                'refund' => $totalPaid,
            ];
        }

        $totalPaid = $booking->payments()->where('status', 'paid')->sum('amount');

        if ($policy === 'strict') {
            return [
                'allowed' => true,
                'reason' => 'Late cancellation. Non-refundable per strict policy.',
                'penalty' => $totalPaid,
                'refund' => 0,
            ];
        }

        $penaltyPercent = $policy === 'flexible' ? 0 : 50;
        $penalty = $totalPaid * ($penaltyPercent / 100);
        $refund = $totalPaid - $penalty;

        return [
            'allowed' => true,
            'reason' => "Late cancellation. {$penaltyPercent}% penalty applies.",
            'penalty' => round($penalty, 2),
            'refund' => round($refund, 2),
        ];
    }

    public function cancel(Booking $booking, ?string $reason = null): array
    {
        $result = $this->canCancel($booking);
        if (!$result['allowed']) {
            return $result;
        }

        \DB::transaction(function () use ($booking, $reason, $result) {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason ?? $result['reason'],
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
            ]);

            $booking->room->update(['status' => 'available']);

            if ($result['refund'] > 0) {
                foreach ($booking->payments()->where('status', 'paid')->get() as $payment) {
                    $payment->update([
                        'status' => 'refunded',
                        'refunded_at' => now(),
                        'notes' => 'Refunded due to cancellation: ' . ($reason ?? $result['reason']),
                    ]);
                }
            }
        });

        return $result;
    }
}
