<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Search Bookings</h1>
        <form action="{{ route('receptionist.search') }}" method="GET" class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
                <input type="text" name="query" placeholder="Search by booking #, guest name, or email..." value="{{ request('query') }}" class="flex-1 border rounded px-4 py-2">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Search</button>
            </div>
        </form>
        <div class="bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">#</th><th class="p-4">Guest</th><th class="p-4">Room</th><th class="p-4">Check In</th><th class="p-4">Check Out</th><th class="p-4">Status</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="border-t">
                            <td class="p-4 text-sm">{{ $booking->booking_number }}</td>
                            <td class="p-4">{{ $booking->guest->name }}</td>
                            <td class="p-4">{{ $booking->room->name ?? 'N/A' }}</td>
                            <td class="p-4">{{ $booking->check_in->format('M d') }}</td>
                            <td class="p-4">{{ $booking->check_out->format('M d') }}</td>
                            <td class="p-4"><span class="px-2 py-1 rounded text-xs {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'checked_in' ? 'bg-blue-100 text-blue-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                            <td class="p-4">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
