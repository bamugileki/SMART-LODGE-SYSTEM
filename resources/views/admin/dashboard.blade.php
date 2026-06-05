<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <div class="flex flex-wrap gap-2">
                @foreach ($quickLinks as $link)
                    <a href="{{ $link->url }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">{{ $link->label }}</a>
                @endforeach
                <a href="{{ route('admin.quick-links.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded hover:bg-slate-700 transition">Quick Links</a>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            @foreach ($cards as $card)
                <a href="{{ $card->url }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition block">
                    <p class="text-gray-500 text-sm">{{ $card->label }}</p>
                    <p class="text-3xl font-bold">{{ $card->value }}</p>
                    @if ($card->sub_text)
                        <p class="text-sm {{ $card->sub_color ?? 'text-gray-500' }}">{{ $card->sub_text }}</p>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            @php
                $groupedLinks = $groupedQuickLinks->groupBy(fn($l) => $l->group ?? 'General');
            @endphp
            @foreach ($groupedLinks as $groupName => $groupLinks)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ $groupName }}</h3>
                    <ul class="space-y-2">
                        @foreach ($groupLinks as $link)
                            <li>
                                <a href="{{ $link->url }}" class="text-gray-700 hover:text-indigo-600 transition flex items-center gap-2">
                                    @if ($link->icon)
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    @endif
                                    {{ $link->label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
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
