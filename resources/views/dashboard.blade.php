<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">My Dashboard</h1>
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold">{{ $bookings->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Upcoming Stays</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $upcoming }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm">Member Since</p>
                <p class="text-lg font-bold">{{ Auth::user()->created_at->format('M Y') }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach ($quickLinks as $link)
                <a href="{{ $link->url }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition flex items-center gap-3">
                    @if ($link->icon)
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    @endif
                    <span class="font-medium text-sm">{{ $link->label }}</span>
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-xl font-semibold">Recent Bookings</h2>
                <a href="{{ route('bookings.index') }}" class="text-indigo-600 hover:underline text-sm">View All</a>
            </div>
            @forelse ($bookings as $booking)
                <div class="p-4 border-b last:border-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $booking->room->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium {{ $booking->status === 'confirmed' ? 'text-green-600' : ($booking->status === 'pending' ? 'text-yellow-600' : 'text-gray-600') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <p class="font-bold">TSh{{ number_format($booking->total, 2) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p>No bookings yet. <a href="{{ route('rooms.index') }}" class="text-indigo-600 hover:underline">Browse rooms</a></p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
