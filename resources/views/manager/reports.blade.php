<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Business Reports</h1>
                <p class="text-gray-500 mt-1">Revenue, occupancy and booking performance</p>
            </div>
        </div>

        <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">Filter</button>
            @if ($startDate || $endDate)
                <a href="{{ route('manager.reports') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">Clear</a>
            @endif
        </form>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <p class="text-2xl font-bold text-indigo-600">TSh{{ number_format($financial['totalRevenue'], 2) }}</p>
                <p class="text-xs text-gray-400">{{ $financial['totalPayments'] }} transactions</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm">Avg Payment</p>
                <p class="text-2xl font-bold text-blue-600">TSh{{ number_format($financial['avgPayment'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm">Occupancy Rate</p>
                <p class="text-2xl font-bold text-green-600">{{ $rooms['occupancyRate'] }}%</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm">Conversion Rate</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $bookings['conversion'] }}%</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Revenue Trend</h2>
                    <a href="{{ route('manager.reports.export', ['type' => 'financial', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                @if ($financial['daily']->count() > 0)
                    <canvas id="revenueChart" height="150"></canvas>
                @else
                    <p class="text-gray-400 text-center py-8">No revenue data</p>
                @endif
                <div class="mt-4 space-y-1 text-sm">
                    @foreach ($financial['byMethod'] as $method)
                        <div class="flex justify-between">
                            <span class="capitalize">{{ str_replace('_', ' ', $method->method) }}</span>
                            <span class="font-medium">TSh{{ number_format($method->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Revenue by Room Type</h2>
                    <a href="{{ route('manager.reports.export', ['type' => 'revenue-by-type', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                @if ($revenueByType->count() > 0)
                    <canvas id="roomTypeChart" height="150"></canvas>
                @else
                    <p class="text-gray-400 text-center py-8">No data</p>
                @endif
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Booking Trends</h2>
                    <a href="{{ route('manager.reports.export', ['type' => 'bookings', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                @if ($bookings['daily']->count() > 0)
                    <canvas id="bookingChart" height="150"></canvas>
                @else
                    <p class="text-gray-400 text-center py-8">No booking data</p>
                @endif
                <div class="grid grid-cols-3 gap-2 mt-4 text-center text-sm">
                    <div class="bg-green-50 rounded p-2"><span class="font-bold text-green-600">{{ $bookings['confirmed'] }}</span><p class="text-gray-500">Confirmed</p></div>
                    <div class="bg-red-50 rounded p-2"><span class="font-bold text-red-600">{{ $bookings['cancelled'] }}</span><p class="text-gray-500">Cancelled</p></div>
                    <div class="bg-yellow-50 rounded p-2"><span class="font-bold text-yellow-600">{{ $bookings['pending'] }}</span><p class="text-gray-500">Pending</p></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Room Performance</h2>
                <div class="mb-4">
                    <div class="flex justify-between text-sm"><span>Occupancy Rate</span><span class="font-bold">{{ $rooms['occupancyRate'] }}%</span></div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $rooms['occupancyRate'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $rooms['occupied'] }} occupied / {{ $rooms['totalRooms'] }} total rooms</p>
                </div>
                <div class="space-y-2">
                    @foreach ($rooms['topRevenue'] as $room)
                        <div class="flex justify-between text-sm border-b pb-1">
                            <span>{{ $room->name }}</span>
                            <span class="font-medium">{{ $room->bookings_count }} bookings</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Customer Feedback</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div><p class="text-gray-500 text-sm">Total Reviews</p><p class="text-xl font-bold">{{ $reviews['total'] }}</p></div>
                    <div><p class="text-gray-500 text-sm">Avg Rating</p><p class="text-xl font-bold text-yellow-500">★ {{ number_format($reviews['avgRating'] ?? 0, 1) }}</p></div>
                    <div><p class="text-gray-500 text-sm">Pending</p><p class="text-xl font-bold text-orange-500">{{ $reviews['pending'] }}</p></div>
                </div>
                @if ($reviews['total'] > 0)
                    <canvas id="ratingChart" height="100"></canvas>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Most Booked Rooms</h2>
                <canvas id="roomBookingsChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <script>
        const colors = ['#6366f1', '#22c55e', '#ef4444', '#f59e0b', '#3b82f6'];

        @if ($financial['daily']->count() > 0)
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: [@foreach ($financial['daily'] as $d) '{{ $d->date }}', @endforeach],
                    datasets: [{
                        label: 'Revenue',
                        data: [@foreach ($financial['daily'] as $d) {{ $d->total }}, @endforeach],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                }
            });
        @endif

        @if ($revenueByType->count() > 0)
            new Chart(document.getElementById('roomTypeChart'), {
                type: 'bar',
                data: {
                    labels: [@foreach ($revenueByType as $r) '{{ $r->name }}', @endforeach],
                    datasets: [{
                        label: 'Revenue',
                        data: [@foreach ($revenueByType as $r) {{ $r->total }}, @endforeach],
                        backgroundColor: colors.slice(0, {{ $revenueByType->count() }})
                    }]
                }
            });
        @endif

        @if ($bookings['daily']->count() > 0)
            new Chart(document.getElementById('bookingChart'), {
                type: 'line',
                data: {
                    labels: [@foreach ($bookings['daily'] as $d) '{{ $d->date }}', @endforeach],
                    datasets: [{
                        label: 'Bookings',
                        data: [@foreach ($bookings['daily'] as $d) {{ $d->count }}, @endforeach],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                }
            });
        @endif

        @if ($reviews['total'] > 0)
            new Chart(document.getElementById('ratingChart'), {
                type: 'bar',
                data: {
                    labels: [@for ($i = 1; $i <= 5; $i++) '{{ $i }} Star', @endfor],
                    datasets: [{
                        label: 'Ratings',
                        data: [@for ($i = 1; $i <= 5; $i++) {{ $reviews['ratings'][$i] ?? 0 }}, @endfor],
                        backgroundColor: '#f59e0b'
                    }]
                }
            });
        @endif

        @if ($rooms['mostBooked']->count() > 0)
            new Chart(document.getElementById('roomBookingsChart'), {
                type: 'bar',
                data: {
                    labels: [@foreach ($rooms['mostBooked'] as $r) '{{ $r->name }}', @endforeach],
                    datasets: [{
                        label: 'Bookings',
                        data: [@foreach ($rooms['mostBooked'] as $r) {{ $r->bookings_count }}, @endforeach],
                        backgroundColor: '#6366f1'
                    }]
                }
            });
        @endif
    </script>

    @vite(['resources/js/chart.js'])
</x-app-layout>
