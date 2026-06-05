<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Compare Rooms</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded-lg shadow-lg">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-4 text-left text-gray-500 w-40">Feature</th>
                        @foreach ($rooms as $room)
                            <th class="p-4 text-center min-w-[200px]">
                                <div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 mb-2">Room Photo</div>
                                <h3 class="font-bold text-lg">{{ $room->name }}</h3>
                                <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 text-sm hover:underline">View Details</a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-4 font-semibold text-gray-700">Type</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">{{ $room->roomType->name ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold text-gray-700">Price / Night</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">
                                <span class="text-xl font-bold text-indigo-600">TSh{{ number_format($room->price_per_night, 2) }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold text-gray-700">Capacity</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">{{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}</td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold text-gray-700">Size</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">{{ $room->size_sqft ? $room->size_sqft . ' sq ft' : 'N/A' }}</td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold text-gray-700">Status</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold text-gray-700">Rating</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">
                                @php $avg = $room->average_rating; @endphp
                                @if ($avg)
                                    <span class="text-yellow-500">{{ str_repeat('★', round($avg)) }}{{ str_repeat('☆', 5 - round($avg)) }}</span>
                                    <span class="text-gray-500 text-sm">({{ number_format($avg, 1) }})</span>
                                @else
                                    <span class="text-gray-400">No ratings</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold text-gray-700">Amenities</td>
                        @foreach ($rooms as $room)
                            <td class="p-4">
                                @if ($room->amenities)
                                    <div class="flex flex-wrap gap-1 justify-center">
                                        @foreach ($room->amenities as $amenity)
                                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">{{ $amenity }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-center block">None listed</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold text-gray-700">Description</td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-sm text-gray-600">{{ $room->description ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="p-4"></td>
                        @foreach ($rooms as $room)
                            <td class="p-4 text-center">
                                <a href="{{ route('rooms.show', $room) }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Book Now</a>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
