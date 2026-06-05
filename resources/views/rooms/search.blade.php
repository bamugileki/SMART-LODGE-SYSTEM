<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Search Results</h1>
        <form action="{{ route('rooms.search') }}" method="GET" class="bg-gray-50 p-4 rounded-lg mb-8 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Check-in</label>
                <input type="date" name="check_in" value="{{ $checkIn }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Check-out</label>
                <input type="date" name="check_out" value="{{ $checkOut }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Guests</label>
                <select name="guests" class="w-full border rounded px-3 py-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ $guests == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Room Type</label>
                <select name="room_type" class="w-full border rounded px-3 py-2">
                    <option value="">All</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->id }}" {{ $roomType == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Search</button>
            </div>
        </form>

        @if ($rooms->count() > 0)
            <p class="text-gray-600 mb-4">{{ $rooms->count() }} room(s) available</p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rooms as $room)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">Photo</div>
                        <div class="p-4">
                            <h3 class="font-semibold">{{ $room->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $room->roomType->name }} . {{ $room->capacity }} guests</p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-xl font-bold text-indigo-600">TSh{{ number_format($room->price_per_night, 2) }}<span class="text-sm text-gray-500">/night</span></span>
                                <div class="flex gap-1">
                                    <a href="{{ route('rooms.calendar', $room) }}" class="border border-indigo-300 text-indigo-600 px-2 py-1 rounded text-xs hover:bg-indigo-50 transition">Calendar</a>
                                    <a href="{{ route('rooms.show', $room) }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">Book</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-gray-500">No rooms available for your criteria.</p>
                <a href="{{ route('rooms.index') }}" class="text-indigo-600 hover:underline mt-2 inline-block">View all rooms</a>
            </div>
        @endif
    </div>
</x-app-layout>
