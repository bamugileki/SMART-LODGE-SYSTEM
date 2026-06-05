<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;

class BookingService
{
    public function validateDates(string $checkIn, string $checkOut, int $roomId, ?int $excludeBookingId = null): array
    {
        $errors = [];
        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);

        $settings = Setting::allToArray();

        if ($checkInDate->isPast()) {
            $errors[] = 'Check-in date cannot be in the past.';
        }

        if ($checkOutDate->isPast()) {
            $errors[] = 'Check-out date cannot be in the past.';
        }

        if (!$checkInDate->lt($checkOutDate)) {
            $errors[] = 'Check-in must be before check-out.';
        }

        $minNights = max(1, (int)($settings['min_stay'] ?? 1));
        $nights = $checkInDate->diffInDays($checkOutDate);
        if ($nights < $minNights) {
            $errors[] = "Minimum stay is {$minNights} night(s).";
        }

        $maxNights = (int)($settings['max_stay'] ?? 30);
        if ($nights > $maxNights) {
            $errors[] = "Maximum stay is {$maxNights} nights.";
        }

        $allowSameDay = ($settings['allow_same_day_booking'] ?? '1') === '1';
        if ($checkInDate->isToday() && !$allowSameDay) {
            $errors[] = 'Same-day booking is not allowed.';
        }

        if ($this->hasOverlap($roomId, $checkIn, $checkOut, $excludeBookingId)) {
            $errors[] = 'This room is not available for the selected dates.';
        }

        return $errors;
    }

    public function hasOverlap(int $roomId, string $checkIn, string $checkOut, ?int $excludeBookingId = null): bool
    {
        return Booking::where('room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                });
            })
            ->when($excludeBookingId, fn($q, $id) => $q->where('id', '!=', $id))
            ->exists();
    }

    public function calculateNights(string $checkIn, string $checkOut): int
    {
        return Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
    }

    public function calculateTotal(Room $room, string $checkIn, string $checkOut): array
    {
        $nights = $this->calculateNights($checkIn, $checkOut);
        $settings = Setting::allToArray();
        $taxRate = (float)($settings['tax_rate'] ?? 0);
        $serviceCharge = (float)($settings['service_charge_percentage'] ?? 0);
        $bookingFee = (float)($settings['booking_fee'] ?? 0);

        $subtotal = $room->price_per_night * $nights;
        $taxAmount = $subtotal * ($taxRate / 100);
        $serviceAmount = $subtotal * ($serviceCharge / 100);

        $total = $subtotal + $taxAmount + $serviceAmount + $bookingFee;

        $rounding = $settings['price_rounding'] ?? 'none';
        if ($rounding === 'nearest') {
            $total = round($total);
        } elseif ($rounding === 'ceil') {
            $total = ceil($total);
        } elseif ($rounding === 'floor') {
            $total = floor($total);
        }

        return [
            'nights' => $nights,
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'service_amount' => round($serviceAmount, 2),
            'booking_fee' => round($bookingFee, 2),
            'total' => round($total, 2),
        ];
    }

    public function markRoomAfterCheckOut(Room $room): void
    {
        $settings = Setting::allToArray();
        $defaultStatus = $settings['default_room_status'] ?? 'available';
        $room->update(['status' => $defaultStatus]);
    }
}
