<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">My Bookings</h1>
        @forelse ($bookings as $booking)
            <div class="bg-white rounded-lg shadow p-6 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Booking #{{ $booking->booking_number }}</p>
                        <p class="font-semibold text-lg">{{ $booking->room->name ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $booking->check_in->format('M d, Y') }} - {{ $booking->check_out->format('M d, Y') }}</p>
                        <p class="text-lg font-bold text-indigo-600 mt-2">TSh{{ number_format($booking->total, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                            @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                        <div class="mt-2 space-x-2">
                            <a href="{{ route('bookings.show', $booking) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                            @if (in_array($booking->status, ['pending', 'confirmed']))
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    <button class="text-red-600 hover:underline text-sm">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-xl text-gray-500">No bookings yet.</p>
                <a href="{{ route('rooms.index') }}" class="text-indigo-600 hover:underline mt-2 inline-block">Browse rooms</a>
            </div>
        @endforelse
        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
