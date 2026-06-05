<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-64 bg-gray-200 flex items-center justify-center text-gray-400 text-2xl">Room Gallery</div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $room->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $room->roomType->name ?? 'N/A' }} . Up to {{ $room->capacity }} guests</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-indigo-600">TSh{{ number_format($room->price_per_night, 2) }}</p>
                        <p class="text-gray-500">per night</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6">{{ $room->description }}</p>
                @if ($room->amenities)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3">Amenities</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($room->amenities as $amenity)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="flex items-center gap-4 mb-6">
                    <span class="font-semibold">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($room->status) }}
                    </span>
                    @if ($room->average_rating)
                        <span class="text-yellow-500">★ {{ number_format($room->average_rating, 1) }}</span>
                    @endif
                </div>

                @auth
                    <div class="bg-gray-50 p-6 rounded-lg mb-4">
                        <h3 class="font-semibold text-lg mb-2">Book This Room</h3>
                        <p class="text-sm text-gray-600 mb-4">Start your booking by clicking the button below. You'll select your dates on the next page.</p>
                        <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold transition">Book Now</a>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('rooms.calendar', $room) }}" class="border border-indigo-300 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Check Availability
                        </a>
                        <form action="{{ route('wishlist.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <button type="submit" class="border border-indigo-300 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                Save to Wishlist
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4 animate-pulse hover:animate-none">
                        <p><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a> or <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">register</a> to book this room.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('rooms.calendar', $room) }}" class="border border-indigo-300 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Check Availability
                        </a>
                        <a href="{{ route('login') }}" class="border border-indigo-300 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Save to Wishlist
                        </a>
                    </div>
                @endguest

                @if ($room->reviews->count() > 0)
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg mb-4">Reviews ({{ $room->reviews->count() }})</h3>
                        @foreach ($room->reviews as $review)
                            <div class="border-b pb-4 mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-medium">{{ $review->guest->name }}</span>
                                    <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                </div>
                                <p class="text-gray-600">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
