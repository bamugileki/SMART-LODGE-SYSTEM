<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Book {{ $room->name }}</h1>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <p class="text-gray-600">{{ $room->description }}</p>
            <p class="text-2xl font-bold text-indigo-600 mt-2">TSh{{ number_format($room->price_per_night, 2) }} <span class="text-sm text-gray-500">/ night</span></p>
        </div>
        <form action="{{ route('bookings.store') }}" method="POST" class="bg-white rounded-lg shadow p-6" id="bookingForm">
            @csrf
            <input type="hidden" name="room_id" value="{{ request('room_id', $room->id) }}">
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-medium mb-1">Check-in Date</label>
                    <input type="date" name="check_in" id="check_in" value="{{ request('check_in') }}" required class="w-full border rounded-lg px-3 py-2" min="{{ date('Y-m-d') }}">
                    @error('check_in') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Check-out Date</label>
                    <input type="date" name="check_out" id="check_out" value="{{ request('check_out') }}" required class="w-full border rounded-lg px-3 py-2" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('check_out') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div id="pricePreview" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between text-sm"><span id="nightCount"></span></div>
                <div class="flex justify-between text-sm mt-1"><span>Subtotal</span><span id="subtotalDisplay"></span></div>
                <div class="flex justify-between font-bold text-lg mt-2 border-t pt-2"><span>Total</span><span id="totalDisplay" class="text-indigo-600"></span></div>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Number of Guests</label>
                <select name="guests_count" class="w-full border rounded-lg px-3 py-2">
                    @for ($i = 1; $i <= $room->capacity; $i++)
                        <option value="{{ $i }}" {{ request('guests_count') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('guests_count') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Special Requests (optional)</label>
                <textarea name="special_requests" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Any special requests..."></textarea>
            </div>
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                <h3 class="font-semibold text-sm mb-2">Cancellation Policy</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $policy['summary'] ?? 'Free cancellation up to 48 hours before check-in.' }}</p>
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="agreed_to_policy" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" required>
                    <span class="text-sm text-gray-700">I have read and agree to the cancellation policy above.</span>
                </label>
                @error('agreed_to_policy') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold">Confirm Booking</button>
        </form>
    </div>

    <script>
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const pricePreview = document.getElementById('pricePreview');
        const nightCount = document.getElementById('nightCount');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        const pricePerNight = {{ $room->price_per_night }};

        function updatePrice() {
            if (!checkIn.value || !checkOut.value) { pricePreview.classList.add('hidden'); return; }
            const d1 = new Date(checkIn.value), d2 = new Date(checkOut.value);
            if (d2 <= d1) { pricePreview.classList.add('hidden'); return; }
            const nights = Math.round((d2 - d1) / (86400000));
            if (nights < 1) { pricePreview.classList.add('hidden'); return; }
            nightCount.textContent = nights + ' night(s) × TSh' + pricePerNight.toLocaleString();
            subtotalDisplay.textContent = 'TSh' + (nights * pricePerNight).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalDisplay.textContent = 'TSh' + (nights * pricePerNight).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            pricePreview.classList.remove('hidden');
        }

        checkIn.addEventListener('change', function() {
            checkOut.min = this.value;
            if (checkOut.value && checkOut.value <= this.value) checkOut.value = '';
            updatePrice();
        });
        checkOut.addEventListener('change', updatePrice);
    </script>
</x-app-layout>
