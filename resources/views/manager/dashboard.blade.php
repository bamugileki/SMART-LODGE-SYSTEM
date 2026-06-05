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

        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($quickLinks as $link)
                    <a href="{{ $link->url }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center gap-3">
                        @if ($link->icon)
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        @endif
                        <span class="font-medium text-sm">{{ $link->label }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
