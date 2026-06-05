<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Our Rooms</h1>
                <p class="text-gray-500 mt-1">Find your perfect stay</p>
            </div>
            <div id="compare-bar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-white rounded-xl shadow-2xl border px-6 py-3 flex items-center gap-4">
                <span id="compare-count" class="text-sm font-medium text-gray-700">0 rooms selected</span>
                <a id="compare-btn" href="#" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Compare</a>
                <button onclick="clearCompare()" class="text-gray-400 hover:text-gray-600 text-sm">Clear</button>
            </div>
        </div>

        <form action="{{ route('rooms.search') }}" method="GET" class="bg-gray-50 p-4 rounded-lg mb-8 flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Check-in</label>
                <input type="date" name="check_in" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Check-out</label>
                <input type="date" name="check_out" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Guests</label>
                <select name="guests" class="w-full border rounded px-3 py-2">
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium mb-1">Room Type</label>
                <select name="room_type" class="w-full border rounded px-3 py-2">
                    <option value="">All Types</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">Search</button>
        </form>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($rooms as $room)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition group">
                    <div class="h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-gray-400 relative">
                        Room Photo
                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition">
                            <input type="checkbox" class="compare-checkbox w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" value="{{ $room->id }}" onchange="updateCompare()">
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                            <span class="text-sm bg-indigo-100 text-indigo-800 px-2 py-1 rounded font-medium">{{ $room->roomType->name ?? 'N/A' }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($room->description, 80) }}</p>
                        <div class="flex items-center gap-2 mb-3 text-sm text-gray-500">
                            <span>{{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}</span>
                            @if ($room->size_sqft)
                                <span>&middot;</span>
                                <span>{{ $room->size_sqft }} sq ft</span>
                            @endif
                            @php $avg = $room->average_rating; @endphp
                            @if ($avg)
                                <span>&middot;</span>
                                <span class="text-yellow-500">★ {{ number_format($avg, 1) }}</span>
                            @endif
                        </div>
                        @if ($room->amenities)
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach (array_slice($room->amenities, 0, 3) as $amenity)
                                    <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-xs">{{ $amenity }}</span>
                                @endforeach
                                @if (count($room->amenities) > 3)
                                    <span class="text-gray-400 text-xs">+{{ count($room->amenities) - 3 }} more</span>
                                @endif
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-2 border-t">
                            <div>
                                <span class="text-xl font-bold text-indigo-600">TSh{{ number_format($room->price_per_night, 2) }}</span>
                                <span class="text-gray-500 text-sm">/night</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="toggleCompare({{ $room->id }})" class="border border-indigo-600 text-indigo-600 px-3 py-1.5 rounded text-sm hover:bg-indigo-50 transition">Compare</button>
                                <a href="{{ route('rooms.show', $room) }}" class="bg-indigo-600 text-white px-4 py-1.5 rounded hover:bg-indigo-700 text-sm transition">View</a>
                                @auth
                                    <form action="{{ route('wishlist.store') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                                        <button type="submit" class="flex items-center gap-1 border border-gray-300 text-gray-500 px-2.5 py-1.5 rounded text-sm hover:text-red-500 hover:border-red-300 transition" title="Save to wishlist">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            Save
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center gap-1 border border-gray-300 text-gray-500 px-2.5 py-1.5 rounded text-sm hover:text-red-500 hover:border-red-300 transition" title="Log in to save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        Save
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $rooms->links() }}</div>
    </div>

    <script>
        let compareIds = new Set();

        function toggleCompare(id) {
            if (compareIds.has(id)) {
                compareIds.delete(id);
            } else {
                compareIds.add(id);
            }
            syncCheckboxes();
            updateCompareBar();
        }

        function updateCompare() {
            compareIds.clear();
            document.querySelectorAll('.compare-checkbox:checked').forEach(cb => {
                compareIds.add(parseInt(cb.value));
            });
            updateCompareBar();
        }

        function syncCheckboxes() {
            document.querySelectorAll('.compare-checkbox').forEach(cb => {
                cb.checked = compareIds.has(parseInt(cb.value));
            });
        }

        function updateCompareBar() {
            const bar = document.getElementById('compare-bar');
            const count = document.getElementById('compare-count');
            const btn = document.getElementById('compare-btn');

            count.textContent = compareIds.size + ' room' + (compareIds.size !== 1 ? 's' : '') + ' selected';

            if (compareIds.size >= 2) {
                bar.classList.remove('hidden');
                btn.href = '/rooms/compare?ids=' + Array.from(compareIds).join(',');
            } else {
                bar.classList.add('hidden');
            }
        }

        function clearCompare() {
            compareIds.clear();
            syncCheckboxes();
            updateCompareBar();
        }
    </script>
</x-app-layout>
