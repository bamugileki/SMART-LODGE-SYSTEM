<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Check-in / Check-out</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Dashboard</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-500">
                        <th class="p-4">Guest</th>
                        <th class="p-4">Room</th>
                        <th class="p-4">Checked In</th>
                        <th class="p-4">Checked Out</th>
                        <th class="p-4">Processed By</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checkIns as $checkIn)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-4">{{ $checkIn->guest->name }}</td>
                            <td class="p-4">{{ $checkIn->room->name ?? 'N/A' }}</td>
                            <td class="p-4 text-sm">{{ $checkIn->checked_in_at->format('M d, Y H:i') }}</td>
                            <td class="p-4 text-sm">{{ $checkIn->checked_out_at?->format('M d, Y H:i') ?? '—' }}</td>
                            <td class="p-4 text-sm">{{ $checkIn->receptionist->name }}</td>
                            <td class="p-4">
                                @if ($checkIn->checked_out_at)
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">Checked Out</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Checked In</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if (!$checkIn->checked_out_at)
                                    <form action="{{ route('admin.checkins.force-checkout', $checkIn) }}" method="POST" class="inline" onsubmit="return confirm('Force check out this guest?')">
                                        @csrf
                                        <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">Force Checkout</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-8 text-center text-gray-500">No check-ins found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $checkIns->links() }}</div>
    </div>
</x-app-layout>
