<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Occupancy Report</h1>
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Total Rooms</p>
                <p class="text-2xl font-bold">{{ $totalRooms }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Occupied</p>
                <p class="text-2xl font-bold text-blue-600">{{ $occupied }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Available</p>
                <p class="text-2xl font-bold text-green-600">{{ $available }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Maintenance</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $maintenance }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">Room</th><th class="p-4">Type</th><th class="p-4">Status</th><th class="p-4">Total Bookings</th></tr></thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr class="border-t">
                            <td class="p-4">{{ $room->name }}</td>
                            <td class="p-4">{{ $room->roomType->name ?? 'N/A' }}</td>
                            <td class="p-4"><span class="px-2 py-1 rounded text-xs {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : ($room->status === 'occupied' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($room->status) }}</span></td>
                            <td class="p-4">{{ $room->bookings_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
