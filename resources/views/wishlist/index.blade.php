<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">My Wishlist</h1>
                <p class="text-gray-500 mt-1">Rooms you've saved for later</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="text-indigo-600 hover:underline text-sm">&larr; Browse Rooms</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        @forelse ($rooms as $room)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition mb-4">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-64 h-40 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-gray-400">
                        Room Photo
                    </div>
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                                    <span class="text-sm bg-indigo-100 text-indigo-800 px-2 py-1 rounded font-medium">{{ $room->roomType->name ?? 'N/A' }}</span>
                                </div>
                                <span class="text-xl font-bold text-indigo-600">TSh{{ number_format($room->price_per_night, 2) }}/night</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-2">{{ Str::limit($room->description, 120) }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-3 border-t">
                            <div class="flex gap-2">
                                <a href="{{ route('rooms.show', $room) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm transition">View Room</a>
                                <form action="{{ route('wishlist.destroy', $room) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="border border-red-300 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 text-sm transition">Remove</button>
                                </form>
                            </div>
                            @php $avg = $room->average_rating; @endphp
                            @if ($avg)
                                <span class="text-yellow-500 text-sm">★ {{ number_format($avg, 1) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No saved rooms yet</h3>
                <p class="text-gray-400 mb-6">Browse rooms and save your favorites for later.</p>
                <a href="{{ route('rooms.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">Browse Rooms</a>
            </div>
        @endforelse

        <div class="mt-6">{{ $rooms->links() }}</div>
    </div>
</x-app-layout>
