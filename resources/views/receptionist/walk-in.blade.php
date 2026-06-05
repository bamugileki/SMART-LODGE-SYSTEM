<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Walk-in Booking</h1>
        <form action="{{ route('receptionist.walk-in.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <h2 class="text-xl font-semibold mb-4">Guest Details</h2>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div><label class="block font-medium mb-1">Full Name</label><input type="text" name="guest_name" required class="w-full border rounded px-3 py-2"></div>
                <div><label class="block font-medium mb-1">Email</label><input type="email" name="guest_email" required class="w-full border rounded px-3 py-2"></div>
                <div><label class="block font-medium mb-1">Phone</label><input type="text" name="guest_phone" class="w-full border rounded px-3 py-2"></div>
            </div>

            <h2 class="text-xl font-semibold mb-4">Booking Details</h2>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div><label class="block font-medium mb-1">Room</label><select name="room_id" required class="w-full border rounded px-3 py-2">@foreach ($rooms as $room)<option value="{{ $room->id }}">{{ $room->name }} (TSh{{ number_format($room->price_per_night, 2) }}/night)</option>@endforeach</select></div>
                <div><label class="block font-medium mb-1">Guests</label><input type="number" name="guests_count" value="1" min="1" class="w-full border rounded px-3 py-2"></div>
                <div><label class="block font-medium mb-1">Check-in</label><input type="date" name="check_in" id="wi_check_in" required class="w-full border rounded px-3 py-2" min="{{ date('Y-m-d') }}"></div>
                <div><label class="block font-medium mb-1">Check-out</label><input type="date" name="check_out" id="wi_check_out" required class="w-full border rounded px-3 py-2"></div>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold">Create Booking</button>
        </form>
    </div>

    <script>
        document.getElementById('wi_check_in')?.addEventListener('change', function() {
            document.getElementById('wi_check_out').min = this.value;
        });
    </script>
</x-app-layout>
