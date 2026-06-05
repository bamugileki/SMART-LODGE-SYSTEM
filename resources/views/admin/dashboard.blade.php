<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.users') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Users</a>
                <a href="{{ route('admin.rooms') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">Rooms</a>
                <a href="{{ route('admin.bookings') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Bookings</a>
                <a href="{{ route('admin.checkins') }}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">Check-ins</a>
                <a href="{{ route('admin.payments') }}" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">Payments</a>
                <a href="{{ route('admin.services') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Services</a>
                <a href="{{ route('admin.reviews') }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition">Reviews</a>
                <a href="{{ route('admin.reports') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Reports</a>
                <a href="{{ route('admin.settings') }}" class="bg-slate-600 text-white px-4 py-2 rounded hover:bg-slate-700 transition">Settings</a>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Rooms</p>
                <p class="text-3xl font-bold">{{ $stats['total_rooms'] }}</p>
                <p class="text-sm text-green-600">{{ $stats['available_rooms'] }} available</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold">{{ $stats['total_bookings'] }}</p>
                <p class="text-sm text-yellow-600">{{ $stats['pending_bookings'] }} pending</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Revenue</p>
                <p class="text-3xl font-bold text-green-600">TSh{{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Occupancy Rate</p>
                <p class="text-3xl font-bold">{{ number_format($stats['occupancy_rate'], 1) }}%</p>
                <p class="text-sm text-blue-600">{{ $stats['active_guests'] }} active guests</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b"><h2 class="text-xl font-semibold">Recent Bookings</h2></div>
                <div class="p-4">
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500"><th class="pb-2">Guest</th><th class="pb-2">Room</th><th class="pb-2">Status</th><th class="pb-2">Total</th></tr></thead>
                        <tbody>
                            @foreach ($recentBookings as $booking)
                                <tr class="border-t">
                                    <td class="py-2">{{ $booking->guest->name }}</td>
                                    <td class="py-2">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="py-2"><span class="px-2 py-1 rounded text-xs {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ $booking->status }}</span></td>
                                    <td class="py-2">TSh{{ number_format($booking->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b"><h2 class="text-xl font-semibold">Monthly Revenue</h2></div>
                <div class="p-6">
                    @if ($revenueByMonth->count() > 0)
                        <div class="space-y-2">
                            @foreach ($revenueByMonth as $month => $total)
                                <div class="flex justify-between">
                                    <span>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</span>
                                    <span class="font-medium">TSh{{ number_format($total, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No revenue data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
