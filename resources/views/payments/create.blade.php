<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Payment for #{{ $booking->booking_number }}</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6 space-y-2">
            <div class="flex justify-between"><span class="text-gray-600">Room</span><span class="font-medium">{{ $booking->room->name }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Check-in</span><span>{{ $booking->check_in->format('d M Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Check-out</span><span>{{ $booking->check_out->format('d M Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Nights</span><span>{{ $booking->total_nights }}</span></div>
            <div class="border-t pt-3 mt-3">
                <div class="flex justify-between text-lg font-bold"><span>Total Due</span><span class="text-indigo-600">TSh {{ number_format($booking->total, 2) }}</span></div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-start gap-2">
            <span class="font-bold text-lg leading-none">&#9888;</span>
            <span>You must pay the <strong>exact amount</strong> shown above. Partial or extra payments are <strong>not allowed</strong>.</span>
        </div>

        <form action="{{ route('payments.store', $booking) }}" method="POST" class="bg-white rounded-lg shadow p-6" id="paymentForm">
            @csrf
            <input type="hidden" name="amount" value="{{ $booking->total }}">
            <div class="mb-4">
                <label class="block font-medium mb-1">Amount to Pay</label>
                <div class="w-full border rounded-lg px-3 py-2 bg-gray-50 text-gray-700 font-semibold">TSh {{ number_format($booking->total, 2) }}</div>
                @error('amount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Payment Method</label>
                <select name="method" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="cash">Cash</option>
                    <option value="mobile_money">Mobile Money (M-Pesa, Airtel Money)</option>
                    <option value="stripe">Stripe (Card)</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold text-lg">Pay TSh {{ number_format($booking->total, 2) }}</button>
        </form>
    </div>
</x-app-layout>
