<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Receptionist Dashboard</h1>
            <div class="flex gap-2">
                <a href="{{ route('receptionist.search') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Search Bookings</a>
                <a href="{{ route('receptionist.walk-in') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">+ Walk-in</a>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Today's Arrivals</p>
                <p class="text-3xl font-bold text-blue-600">{{ $todayBookings->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Active Stays</p>
                <p class="text-3xl font-bold text-green-600">{{ $activeStays->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Check-outs Today</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $upcomingCheckouts->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Available Rooms</p>
                <p class="text-3xl font-bold">{{ $availableRooms }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Today's Arrivals</h2>
                    <span class="text-sm text-gray-500">{{ $todayBookings->where('status', 'confirmed')->count() }} ready</span>
                </div>
                <div class="p-4">
                    @forelse ($todayBookings as $booking)
                        <div class="flex justify-between items-center py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $booking->guest->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }} &middot; {{ $booking->guests_count }} guests &middot; {{ $booking->booking_number }}</p>
                                @if ($booking->status === 'pending')
                                    <div class="flex gap-2 mt-1">
                                        <form action="{{ route('receptionist.approve', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-green-600 text-xs hover:underline">Approve</button>
                                        </form>
                                        <form action="{{ route('receptionist.reject', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Reject this booking?')">
                                            @csrf
                                            <button class="text-red-600 text-xs hover:underline">Reject</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($booking->status === 'confirmed')
                                    <button onclick="openCheckIn({{ $booking->id }})" class="bg-green-600 text-white px-4 py-1 rounded text-sm hover:bg-green-700 transition">Check In</button>
                                @elseif ($booking->status === 'checked_in')
                                    <span class="text-green-600 text-sm font-medium">Checked In</span>
                                @elseif ($booking->status === 'pending')
                                    <span class="text-yellow-600 text-sm">Pending</span>
                                @else
                                    <span class="text-gray-400 text-sm">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No arrivals today.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Active Stays</h2>
                    <span class="text-sm text-gray-500">{{ $activeStays->count() }} guests</span>
                </div>
                <div class="p-4">
                    @forelse ($activeStays as $booking)
                        <div class="flex justify-between items-center py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium">{{ $booking->guest->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }} &middot; Check-out: {{ $booking->check_out->format('M d, Y') }}</p>
                            </div>
                            <button onclick="openCheckOut({{ $booking->id }})" class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700 transition">Check Out</button>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No active stays.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mt-6">
            <div class="p-6 border-b"><h2 class="text-xl font-semibold">All Rooms Status</h2></div>
            <div class="p-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @php $rooms = \App\Models\Room::with('roomType')->where('is_active', true)->get(); @endphp
                @foreach ($rooms as $room)
                    <div class="p-3 rounded-lg border text-center {{ $room->status === 'available' ? 'bg-green-50 border-green-200' : ($room->status === 'occupied' ? 'bg-red-50 border-red-200' : ($room->status === 'maintenance' ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200')) }}">
                        <p class="font-semibold text-sm">{{ $room->name }}</p>
                        <p class="text-xs text-gray-500">{{ $room->roomType->name ?? '' }}</p>
                        <span class="text-xs font-medium {{ $room->status === 'available' ? 'text-green-700' : ($room->status === 'occupied' ? 'text-red-700' : ($room->status === 'maintenance' ? 'text-yellow-700' : 'text-gray-700')) }}">{{ ucfirst($room->status) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="checkInModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold mb-4">Check In Guest</h2>
            <form id="checkInForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-medium mb-1">National ID / Passport</label>
                    <input type="text" name="national_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Verify guest identity">
                </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Any notes about check-in"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('checkInModal')" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Confirm Check In</button>
                </div>
            </form>
        </div>
    </div>

    <div id="checkOutModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold mb-4">Check Out Guest</h2>
            <form id="checkOutForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-medium mb-1">Extra Charges (TSh)</label>
                    <input type="number" name="extra_charges" value="0" min="0" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">Reason for Extra Charges</label>
                    <input type="text" name="extra_charges_reason" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Damages, late check-out, services">
                </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Check-out notes"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('checkOutModal')" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Confirm Check Out</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCheckIn(id) {
            document.getElementById('checkInForm').action = '/receptionist/bookings/' + id + '/checkin';
            document.getElementById('checkInModal').classList.remove('hidden');
        }

        function openCheckOut(id) {
            document.getElementById('checkOutForm').action = '/receptionist/bookings/' + id + '/checkout';
            document.getElementById('checkOutModal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="Modal"]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</x-app-layout>
