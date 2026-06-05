<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Reports & Analytics</h1>
                <p class="text-gray-500 mt-1">Full system financial and operational reports</p>
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
                <a href="{{ route('admin.reports') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">Clear</a>
            @endif
        </form>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <p class="text-2xl font-bold text-indigo-600">TSh{{ number_format($financial['totalRevenue'], 2) }}</p>
                <p class="text-xs text-gray-400">{{ $financial['totalPayments'] }} payments</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm">Avg Payment</p>
                <p class="text-2xl font-bold text-green-600">TSh{{ number_format($financial['avgPayment'] ?? 0, 2) }}</p>
                <p class="text-xs text-gray-400">per transaction</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
                <p class="text-gray-500 text-sm">Refunds</p>
                <p class="text-2xl font-bold text-red-600">TSh{{ number_format($refunds['total_refunds'], 2) }}</p>
                <p class="text-xs text-gray-400">{{ $refunds['refund_count'] }} transactions</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm">Pending Payments</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pending->count() }}</p>
                <p class="text-xs text-gray-400">awaiting confirmation</p>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <p class="text-2xl font-bold">{{ $bookings['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Confirmed</p>
                <p class="text-2xl font-bold text-green-600">{{ $bookings['confirmed'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Cancelled</p>
                <p class="text-2xl font-bold text-red-600">{{ $bookings['cancelled'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Conversion Rate</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $bookings['conversion'] }}%</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Daily Revenue</h2>
                    <a href="{{ route('admin.reports.export', ['type' => 'financial', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                <canvas id="revenueChart" height="150"></canvas>
                <div class="mt-4 space-y-1 text-sm">
                    @foreach ($financial['byMethod'] as $method)
                        <div class="flex justify-between">
                            <span class="capitalize">{{ str_replace('_', ' ', $method->method) }}</span>
                            <span class="font-medium">TSh{{ number_format($method->total, 2) }} ({{ $method->count }})</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Revenue by Room Type</h2>
                    <a href="{{ route('admin.reports.export', ['type' => 'revenue-by-type', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                <canvas id="roomTypeChart" height="150"></canvas>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Booking Trends</h2>
                    <a href="{{ route('admin.reports.export', ['type' => 'bookings', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                <canvas id="bookingTrendChart" height="150"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Room Performance</h2>
                    <a href="{{ route('admin.reports.export', ['type' => 'rooms', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Export PDF</a>
                </div>
                <div class="mb-4">
                    <div class="flex justify-between text-sm"><span>Occupancy Rate</span><span class="font-bold">{{ $rooms['occupancyRate'] }}%</span></div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $rooms['occupancyRate'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $rooms['occupied'] }} occupied / {{ $rooms['totalRooms'] }} total</p>
                </div>
                <canvas id="roomChart" height="120"></canvas>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Most Booked Rooms</h2>
                <div class="space-y-2">
                    @foreach ($rooms['mostBooked'] as $room)
                        <div class="flex justify-between text-sm border-b pb-1">
                            <span>{{ $room->name }}</span>
                            <span class="font-medium">{{ $room->bookings_count }} bookings</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Top Revenue Rooms</h2>
                <div class="space-y-2">
                    @foreach ($rooms['topRevenue'] as $room)
                        <div class="flex justify-between text-sm border-b pb-1">
                            <span>{{ $room->name }}</span>
                            <span class="font-medium">TSh{{ number_format($room->revenue ?? 0, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Payment Method Breakdown</h2>
                <canvas id="paymentMethodChart" height="150"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Reviews & Ratings</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div><p class="text-gray-500 text-sm">Total Reviews</p><p class="text-xl font-bold">{{ $reviews['total'] }}</p></div>
                    <div><p class="text-gray-500 text-sm">Avg Rating</p><p class="text-xl font-bold text-yellow-500">★ {{ number_format($reviews['avgRating'] ?? 0, 1) }}</p></div>
                    <div><p class="text-gray-500 text-sm">Pending Approval</p><p class="text-xl font-bold text-orange-500">{{ $reviews['pending'] }}</p></div>
                </div>
                @if ($reviews['total'] > 0)
                    <canvas id="ratingChart" height="100"></canvas>
                @endif
            </div>
        </div>

        @if ($failed['total_failed'] > 0)
            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h2 class="text-lg font-semibold mb-4 text-red-600">Failed Payments ({{ $failed['total_failed'] }})</h2>
                <p class="text-sm text-gray-500 mb-3">Total failed amount: TSh{{ number_format($failed['failed_amount'], 2) }}</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500"><th class="pb-2">Booking</th><th class="pb-2">Guest</th><th class="pb-2">Amount</th><th class="pb-2">Date</th></tr></thead>
                        <tbody>
                            @foreach ($failed['recent_failed'] as $p)
                                <tr class="border-t"><td class="py-1.5">{{ $p->booking?->booking_number ?? 'N/A' }}</td><td>{{ $p->booking?->guest?->name ?? 'N/A' }}</td><td>TSh{{ number_format($p->amount, 2) }}</td><td>{{ $p->created_at->format('M d, Y') }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($pending->count() > 0)
            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h2 class="text-lg font-semibold mb-4 text-yellow-600">Pending Payments ({{ $pending->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500"><th class="pb-2">Booking</th><th class="pb-2">Guest</th><th class="pb-2">Room</th><th class="pb-2">Amount</th><th class="pb-2">Method</th></tr></thead>
                        <tbody>
                            @foreach ($pending as $p)
                                <tr class="border-t"><td class="py-1.5">{{ $p->booking?->booking_number ?? 'N/A' }}</td><td>{{ $p->booking?->guest?->name ?? 'N/A' }}</td><td>{{ $p->booking?->room?->name ?? 'N/A' }}</td><td>TSh{{ number_format($p->amount, 2) }}</td><td class="capitalize">{{ str_replace('_', ' ', $p->method) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Booking Status Breakdown</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export', ['type' => 'bookings', 'start_date' => $startDate, 'end_date' => $endDate, 'format' => 'csv']) }}" class="text-gray-500 hover:text-gray-700 text-sm">Export CSV</a>
                </div>
            </div>
            <div class="grid grid-cols-5 gap-3 text-center text-sm">
                @foreach ($bookings['byStatus'] as $status => $count)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="font-bold text-lg {{ match($status) { 'pending' => 'text-yellow-600', 'confirmed' => 'text-green-600', 'checked_in' => 'text-blue-600', 'checked_out' => 'text-gray-600', 'cancelled' => 'text-red-600', default => '' } }}">{{ $count }}</p>
                        <p class="text-gray-500 capitalize">{{ str_replace('_', ' ', $status) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        const colors = ['#6366f1', '#22c55e', '#ef4444', '#f59e0b', '#3b82f6', '#ec4899'];

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
            new Chart(document.getElementById('bookingTrendChart'), {
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

        new Chart(document.getElementById('roomChart'), {
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

        @if ($financial['byMethod']->count() > 0)
            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'pie',
                data: {
                    labels: [@foreach ($financial['byMethod'] as $m) '{{ ucfirst(str_replace('_', ' ', $m->method)) }}', @endforeach],
                    datasets: [{
                        data: [@foreach ($financial['byMethod'] as $m) {{ $m->total }}, @endforeach],
                        backgroundColor: colors
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
    </script>

    @vite(['resources/js/chart.js'])
</x-app-layout>
