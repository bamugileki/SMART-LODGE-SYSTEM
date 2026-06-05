<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Payment History</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            @if ($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="px-6 py-4">Receipt</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Booking</th>
                                <th class="px-6 py-4">Room</th>
                                <th class="px-6 py-4">Method</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $payment->receipt_number }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ $payment->booking?->booking_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $payment->booking?->room?->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $payment->method) }}</td>
                                    <td class="px-6 py-4 font-medium">TSh{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' : ($payment->status === 'refunded' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @if ($payment->verified_at)
                                            <span class="ml-1 text-xs text-blue-600">Verified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('payments.receipt', $payment) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">No payments yet</p>
                    <p class="text-gray-400 text-sm">Payments will appear here once you make a booking.</p>
                    <a href="{{ route('rooms.index') }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Browse Rooms</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>