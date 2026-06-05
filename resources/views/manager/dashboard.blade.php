<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manager Dashboard</h1>
            <div class="space-x-2">
                <a href="{{ route('manager.reports') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Reports</a>
                <a href="{{ route('manager.occupancy') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Occupancy</a>
                <a href="{{ route('manager.reviews') }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Reviews</a>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Revenue</p>
                <p class="text-3xl font-bold text-green-600">TSh{{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Occupancy Rate</p>
                <p class="text-3xl font-bold">{{ number_format($stats['occupancy_rate'], 1) }}%</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b"><h2 class="text-xl font-semibold">Recent Bookings</h2></div>
                <div class="p-4">
                    @foreach ($recentBookings as $booking)
                        <div class="flex justify-between py-2 border-b last:border-0">
                            <div><p class="font-medium">{{ $booking->guest->name }}</p><p class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</p></div>
                            <span class="text-sm font-medium">TSh{{ number_format($booking->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b"><h2 class="text-xl font-semibold">Monthly Revenue</h2></div>
                <div class="p-4">
                    @foreach ($monthlyRevenue as $row)
                        <div class="flex justify-between py-2 border-b last:border-0">
                            <span>{{ $row->month }}</span>
                            <span class="font-medium">TSh{{ number_format($row->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
