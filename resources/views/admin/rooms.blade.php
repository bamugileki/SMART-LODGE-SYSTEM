<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manage Rooms</h1>
            <button onclick="openModal('createModal')" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">+ Add Room</button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">Name</th><th class="p-4">Type</th><th class="p-4">Price</th><th class="p-4">Status</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-4">{{ $room->name }}</td>
                            <td class="p-4">{{ $room->roomType->name ?? 'N/A' }}</td>
                            <td class="p-4">TSh{{ number_format($room->price_per_night) }}</td>
                            <td class="p-4"><span class="px-2 py-1 rounded text-xs {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : ($room->status === 'occupied' ? 'bg-blue-100 text-blue-800' : ($room->status === 'reserved' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">{{ ucfirst($room->status) }}</span></td>
                            <td class="p-4">
                                <button onclick='openEdit({{ $room->id }})' class="text-indigo-600 hover:text-indigo-800 mr-3 font-medium text-sm transition">Edit</button>
                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this room?')">
                                    @csrf
                                    <button class="text-red-600 hover:text-red-800 font-medium text-sm transition">Deactivate</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $rooms->links() }}</div>
    </div>

    <div id="createModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" @click.outside="closeModal('createModal')">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold">Add Room</h2>
                <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('admin.rooms.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div><label class="block font-medium mb-1">Name</label><input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></div>
                    <div><label class="block font-medium mb-1">Type</label><select name="room_type_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">@foreach ($roomTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
                    <div><label class="block font-medium mb-1">Price/Night (TSh)</label><input type="number" step="0.01" name="price_per_night" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block font-medium mb-1">Capacity</label><input type="number" name="capacity" value="2" class="w-full border rounded-lg px-3 py-2"></div>
                    <div><label class="block font-medium mb-1">Status</label><select name="status" class="w-full border rounded-lg px-3 py-2"><option value="available">Available</option><option value="maintenance">Maintenance</option><option value="reserved">Reserved</option></select></div>
                    <div><label class="block font-medium mb-1">Size (sq ft)</label><input type="number" name="size_sqft" class="w-full border rounded-lg px-3 py-2"></div>
                </div>
                <div class="mb-4"><label class="block font-medium mb-1">Description</label><textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea></div>
                <div class="mb-4">
                    <label class="block font-medium mb-2">Amenities</label>
                    <div class="grid grid-cols-3 gap-2">
                        @php $commonAmenities = ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Work Desk', 'Balcony', 'Living Room', 'Jacuzzi', 'Kitchen', 'Play Area', 'Safe', 'Hair Dryer', 'Iron', 'Telephone', 'Coffee Maker']; @endphp
                        @foreach ($commonAmenities as $amenity)
                            <label class="flex items-center gap-2 text-sm cursor-pointer hover:text-indigo-600">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $amenity }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" @click.outside="closeModal('editModal')">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold">Edit Room</h2>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div><label class="block font-medium mb-1">Name</label><input type="text" name="name" id="edit_name" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></div>
                    <div><label class="block font-medium mb-1">Type</label><select name="room_type_id" id="edit_room_type_id" class="w-full border rounded-lg px-3 py-2">@foreach ($roomTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
                    <div><label class="block font-medium mb-1">Price/Night (TSh)</label><input type="number" step="0.01" name="price_per_night" id="edit_price" required class="w-full border rounded-lg px-3 py-2"></div>
                    <div><label class="block font-medium mb-1">Capacity</label><input type="number" name="capacity" id="edit_capacity" class="w-full border rounded-lg px-3 py-2"></div>
                    <div><label class="block font-medium mb-1">Status</label><select name="status" id="edit_status" class="w-full border rounded-lg px-3 py-2"><option value="available">Available</option><option value="occupied">Occupied</option><option value="reserved">Reserved</option><option value="maintenance">Maintenance</option></select></div>
                    <div><label class="block font-medium mb-1">Size (sq ft)</label><input type="number" name="size_sqft" id="edit_size" class="w-full border rounded-lg px-3 py-2"></div>
                </div>
                <div class="mb-4"><label class="block font-medium mb-1">Description</label><textarea name="description" id="edit_description" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea></div>
                <div class="mb-4">
                    <label class="block font-medium mb-2">Amenities</label>
                    <div class="grid grid-cols-3 gap-2" id="edit_amenities_container">
                        @php $commonAmenities = ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Work Desk', 'Balcony', 'Living Room', 'Jacuzzi', 'Kitchen', 'Play Area', 'Safe', 'Hair Dryer', 'Iron', 'Telephone', 'Coffee Maker']; @endphp
                        @foreach ($commonAmenities as $amenity)
                            <label class="flex items-center gap-2 text-sm cursor-pointer hover:text-indigo-600">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity }}" class="amenity-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                {{ $amenity }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded">
                    <label for="edit_is_active" class="font-medium">Active</label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update Room</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(m => m.classList.add('hidden'));
            }
        });

        const roomData = @json($rooms->items());

        function openEdit(id) {
            const room = roomData.find(r => r.id === id);
            if (!room) return;

            document.getElementById('editForm').action = '/admin/rooms/' + id;
            document.getElementById('edit_name').value = room.name;
            document.getElementById('edit_room_type_id').value = room.room_type_id;
            document.getElementById('edit_price').value = room.price_per_night;
            document.getElementById('edit_capacity').value = room.capacity;
            document.getElementById('edit_status').value = room.status;
            document.getElementById('edit_size').value = room.size_sqft || '';
            document.getElementById('edit_description').value = room.description || '';
            document.querySelectorAll('#edit_amenities_container .amenity-checkbox').forEach(cb => {
                cb.checked = room.amenities && room.amenities.includes(cb.value);
            });
            document.getElementById('edit_is_active').checked = room.is_active;

            openModal('editModal');
        }
    </script>
</x-app-layout>
