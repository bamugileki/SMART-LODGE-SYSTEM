<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold">Booking #{{ $booking->booking_number }}</h1>
                <p class="text-gray-600">Created {{ $booking->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-medium
                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-lg mb-4">Room Details</h2>
                <p><span class="font-medium">Room:</span> {{ $booking->room->name ?? 'N/A' }}</p>
                <p><span class="font-medium">Type:</span> {{ $booking->room->roomType->name ?? 'N/A' }}</p>
                <p><span class="font-medium">Capacity:</span> {{ $booking->room->capacity ?? 'N/A' }} guests</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-lg mb-4">Stay Details</h2>
                <p><span class="font-medium">Check-in:</span> {{ $booking->check_in->format('M d, Y') }}</p>
                <p><span class="font-medium">Check-out:</span> {{ $booking->check_out->format('M d, Y') }}</p>
                <p><span class="font-medium">Nights:</span> {{ $booking->total_nights }}</p>
                <p><span class="font-medium">Guests:</span> {{ $booking->guests_count }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-lg mb-4">Payment Summary</h2>
                <div class="flex justify-between mb-2"><span>Room subtotal</span><span>TSh{{ number_format($booking->subtotal, 2) }}</span></div>
                <div class="flex justify-between mb-2"><span>Services</span><span>TSh{{ number_format($booking->services_total, 2) }}</span></div>
                @if ($booking->discount > 0)
                    <div class="flex justify-between mb-2 text-green-600"><span>Discount</span><span>-TSh{{ number_format($booking->discount, 2) }}</span></div>
                @endif
                @if ($booking->extra_charges > 0)
                    <div class="flex justify-between mb-2 text-orange-600"><span>Extra charges</span><span>TSh{{ number_format($booking->extra_charges, 2) }}</span></div>
                @endif
                <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span>TSh{{ number_format($booking->total, 2) }}</span></div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-lg mb-4">Payments</h2>
                @forelse ($booking->payments as $payment)
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ $payment->status }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium">TSh{{ number_format($payment->amount, 2) }}</span>
                            <a href="{{ route('payments.receipt', $payment) }}" class="text-indigo-600 hover:text-indigo-800 text-sm" target="_blank">Receipt</a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No payments yet.</p>
                @endforelse
                @if ($booking->status === 'pending')
                    <a href="{{ route('payments.create', $booking) }}" class="block mt-4 bg-indigo-600 text-white text-center py-2 rounded hover:bg-indigo-700">Make Payment</a>
                @endif
            </div>
        </div>

        @if ($booking->cancellation_reason)
            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="font-medium text-red-700">Cancellation Reason:</p>
                <p class="text-red-600">{{ $booking->cancellation_reason }}</p>
            </div>
        @endif

        @if ($booking->status === 'checked_out' && !$booking->review)
            <div class="mt-6">
                <a href="{{ route('reviews.create', $booking) }}" class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600">Leave a Review</a>
            </div>
        @endif

        @if ($cancellation && $cancellation['allowed'])
            <div class="mt-6 bg-gray-50 border rounded-lg p-4">
                <h3 class="font-semibold mb-2">Cancellation Policy</h3>
                <p class="text-sm text-gray-600 mb-3">{{ app(\App\Services\CancellationService::class)->getPolicySummary() }}</p>
                <p class="text-sm text-gray-500 mb-3">{{ $cancellation['reason'] }}</p>
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Reason for cancellation (optional)</label>
                        <textarea name="reason" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Tell us why you're cancelling..."></textarea>
                    </div>
                    <button class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Cancel Booking</button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
