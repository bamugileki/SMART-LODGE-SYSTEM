<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

        {{-- Header with greeting and quick actions --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome back, <span class="text-indigo-600">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.quick-links.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Manage Links
                </a>
                <a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reports
                </a>
            </div>
        </div>

        {{-- Stat Cards Row --}}
        @php
            $cardStyles = [
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'gradient' => 'linear-gradient(135deg, #3b82f6, #2563eb)', 'iconBg' => '#eff6ff', 'iconColor' => '#2563eb'],
                ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'gradient' => 'linear-gradient(135deg, #10b981, #059669)', 'iconBg' => '#ecfdf5', 'iconColor' => '#059669'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'gradient' => 'linear-gradient(135deg, #f59e0b, #d97706)', 'iconBg' => '#fffbeb', 'iconColor' => '#d97706'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'gradient' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)', 'iconBg' => '#f5f3ff', 'iconColor' => '#7c3aed'],
            ];
        @endphp
        <div class="grid md:grid-cols-4 gap-6">
            @foreach ($cards as $i => $card)
                @php $s = $cardStyles[$i % count($cardStyles)]; @endphp
                <a href="{{ $card->url }}" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden" style="border-left: 4px solid {{ $s['iconColor'] }};">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: {{ $s['iconBg'] }};">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: {{ $s['iconColor'] }};">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $card->value }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $card->label }}</p>
                        @if ($card->sub_text)
                            <p class="text-xs font-medium mt-1" style="color: {{ $s['iconColor'] }};">{{ $card->sub_text }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Quick Links Grouped --}}
        @php
            $groupedLinks = $groupedQuickLinks->groupBy(fn($l) => $l->group ?? 'General');
            $groupPalettes = [
                ['dot' => '#6366f1', 'header' => '#eef2ff', 'hover' => '#eef2ff'],
                ['dot' => '#10b981', 'header' => '#ecfdf5', 'hover' => '#ecfdf5'],
                ['dot' => '#f59e0b', 'header' => '#fffbeb', 'hover' => '#fffbeb'],
                ['dot' => '#f43f5e', 'header' => '#fff1f2', 'hover' => '#fff1f2'],
                ['dot' => '#06b6d4', 'header' => '#ecfeff', 'hover' => '#ecfeff'],
                ['dot' => '#8b5cf6', 'header' => '#f5f3ff', 'hover' => '#f5f3ff'],
            ];
        @endphp
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-gray-900">Quick Access</h2>
                <a href="{{ route('admin.quick-links.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                    Manage
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($groupedLinks as $groupName => $groupLinks)
                    @php $p = $groupPalettes[$loop->index % count($groupPalettes)]; @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="px-5 py-3 border-b border-gray-100" style="background: linear-gradient(90deg, {{ $p['header'] }}, transparent);">
                            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full inline-block" style="background: {{ $p['dot'] }};"></span>
                                {{ $groupName }}
                            </h3>
                        </div>
                        <div class="p-3">
                            @foreach ($groupLinks as $link)
                                <a href="{{ $link->url }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors duration-150 group/link">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 transition-colors group-hover/link:bg-gray-200">
                                        <svg class="w-4 h-4 text-gray-500 transition-colors group-hover/link:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 group-hover/link:text-gray-900 transition-colors">{{ $link->label }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Bookings & Monthly Revenue --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Recent Bookings</h2>
                    <a href="{{ route('admin.bookings') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs uppercase tracking-wider">
                                <th class="px-6 py-3 font-medium">Guest</th>
                                <th class="px-6 py-3 font-medium">Room</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($recentBookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <span class="font-medium text-gray-900">{{ $booking->guest->name }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-600">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3.5">
                                        @php
                                            $statusStyles = ['confirmed' => 'bg-green-100 text-green-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'checked_in' => 'bg-blue-100 text-blue-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
                                            $style = $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $style }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-medium text-gray-900">TSh{{ number_format($booking->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Monthly Revenue</h2>
                </div>
                <div class="p-6">
                    @if ($revenueByMonth->count() > 0)
                        <div class="space-y-1">
                            @foreach ($revenueByMonth as $month => $total)
                                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                                    <span class="text-sm font-medium text-gray-700">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            @php $max = $revenueByMonth->max(); @endphp
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all" style="width: {{ $max > 0 ? ($total / $max) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900 w-28 text-right">TSh{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <p class="text-gray-400 text-sm">No revenue data yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
