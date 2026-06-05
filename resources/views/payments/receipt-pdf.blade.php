<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $payment->receipt_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-sm { font-size: 10px; }
        .text-xs { font-size: 9px; }
        .font-bold { font-weight: bold; }
        .font-medium { font-weight: 500; }
        .border-b { border-bottom: 1px solid #ddd; }
        .border-t { border-top: 1px solid #ddd; }
        .pb-2 { padding-bottom: 6px; }
        .pb-3 { padding-bottom: 10px; }
        .pb-4 { padding-bottom: 14px; }
        .mb-2 { margin-bottom: 6px; }
        .mb-3 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 14px; }
        .mt-2 { margin-top: 6px; }
        .mt-4 { margin-top: 14px; }
        .pt-2 { padding-top: 6px; }
        .pt-3 { padding-top: 10px; }
        .uppercase { text-transform: uppercase; }
        .tracking-wide { letter-spacing: 1px; }
        .text-gray-500 { color: #999; }
        .text-gray-600 { color: #888; }
        .text-indigo-700 { color: #4338ca; }
        .text-green-600 { color: #16a34a; }
        .text-orange-600 { color: #ea580c; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        td.label { color: #999; width: 40%; }
        td.value { font-weight: 500; }
        .section-title { font-weight: bold; font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="text-center pb-4" style="border-bottom: 2px solid #4338ca;">
        <h1 style="font-size: 18px; margin: 0;">{{ $settings['hotel_name'] ?? 'BungeStay' }}</h1>
        @if (!empty($settings['hotel_slogan']))
            <p class="text-sm" style="font-style: italic; color: #888;">{{ $settings['hotel_slogan'] }}</p>
        @endif
        @if (!empty($settings['hotel_address']))
            <p class="text-xs" style="color: #999;">{{ $settings['hotel_address'] }}</p>
        @endif
        @if (!empty($settings['hotel_email']) || !empty($settings['hotel_phone']))
            <p class="text-xs" style="color: #999;">
                {{ $settings['hotel_email'] ?? '' }}{{ !empty($settings['hotel_email']) && !empty($settings['hotel_phone']) ? ' | ' : '' }}{{ $settings['hotel_phone'] ?? '' }}
            </p>
        @endif
        <h2 style="font-size: 14px; margin: 6px 0 0;">PAYMENT RECEIPT</h2>
    </div>

    <table style="margin-top: 14px;">
        <tr><td class="label">Receipt Number</td><td class="value" style="color: #4338ca; font-size: 13px;">{{ $payment->receipt_number }}</td></tr>
        <tr><td class="label">Transaction ID</td><td class="value">{{ $payment->transaction_id }}</td></tr>
        <tr><td class="label">Date Issued</td><td class="value">{{ $payment->paid_at->format('M d, Y h:i A') }}</td></tr>
        <tr><td class="label">Status</td><td class="value" style="color: #16a34a;">{{ ucfirst($payment->status) }}</td></tr>
        @if ($payment->isVerified())
            <tr><td class="label">Verified By</td><td class="value">{{ $payment->verifier?->name ?? 'System' }} on {{ $payment->verified_at->format('M d, Y h:i A') }}</td></tr>
        @endif
    </table>

    <div class="section-title" style="margin-top: 14px;">Booking Reference</div>
    <table>
        <tr><td class="label">Booking #</td><td class="value">{{ $payment->booking->booking_number }}</td></tr>
        <tr><td class="label">Booking Status</td><td class="value">{{ ucfirst(str_replace('_', ' ', $payment->booking->status)) }}</td></tr>
        <tr><td class="label">Created</td><td class="value">{{ $payment->booking->created_at->format('M d, Y') }}</td></tr>
    </table>

    <div class="section-title" style="margin-top: 14px;">Guest Information</div>
    <table>
        <tr><td class="label">Full Name</td><td class="value">{{ $payment->booking->guest->name }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $payment->booking->guest->email }}</td></tr>
        @if ($payment->booking->guest->phone)
            <tr><td class="label">Phone</td><td>{{ $payment->booking->guest->phone }}</td></tr>
        @endif
    </table>

    <div class="section-title" style="margin-top: 14px;">Room & Stay Details</div>
    <table>
        <tr><td class="label">Room</td><td class="value">{{ $payment->booking->room->name }}</td></tr>
        <tr><td class="label">Room Type</td><td>{{ $payment->booking->room->roomType->name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Check-in</td><td>{{ $payment->booking->check_in->format('d M Y') }}</td></tr>
        <tr><td class="label">Check-out</td><td>{{ $payment->booking->check_out->format('d M Y') }}</td></tr>
        <tr><td class="label">Nights</td><td>{{ $payment->booking->total_nights }}</td></tr>
        <tr><td class="label">Guests</td><td>{{ $payment->booking->guests_count }}</td></tr>
    </table>

    <div class="section-title" style="margin-top: 14px;">Payment Breakdown</div>
    <table>
        <tr><td>Room charges ({{ $payment->booking->total_nights }} nights)</td><td class="text-right">TSh {{ number_format($payment->booking->subtotal, 2) }}</td></tr>
        @if ($payment->booking->services_total > 0)
            <tr><td>Services</td><td class="text-right">TSh {{ number_format($payment->booking->services_total, 2) }}</td></tr>
        @endif
        @if ($payment->booking->discount > 0)
            <tr><td style="color: #16a34a;">Discount</td><td class="text-right" style="color: #16a34a;">-TSh {{ number_format($payment->booking->discount, 2) }}</td></tr>
        @endif
    </table>

    <div style="border-top: 2px solid #4338ca; margin-top: 10px; padding-top: 6px;">
        <table>
            <tr>
                <td style="font-weight: bold; font-size: 13px;">Total Paid</td>
                <td class="text-right" style="font-weight: bold; font-size: 13px; color: #4338ca;">TSh {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <table style="margin-top: 10px;">
        <tr><td class="label">Payment Method</td><td class="value">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td></tr>
        <tr><td class="label">Processed By</td><td>{{ $payment->processor?->name ?? 'System' }}</td></tr>
    </table>

    @if ($payment->notes)
        <div style="margin-top: 10px; padding-top: 6px; border-top: 1px solid #ddd;">
            <div class="text-xs" style="font-weight: bold;">Notes:</div>
            <div class="text-xs" style="color: #888;">{{ $payment->notes }}</div>
        </div>
    @endif

    <div class="text-center text-xs" style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; color: #bbb;">
        <p>This is a computer-generated official receipt from {{ $settings['hotel_name'] ?? 'BungeStay' }}.</p>
        <p>Receipt #{{ $payment->receipt_number }} | {{ $payment->paid_at->format('Y-m-d h:i A') }}</p>
    </div>
</body>
</html>
