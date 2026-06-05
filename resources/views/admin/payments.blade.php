<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Payment Management</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Total Payments</p>
                <p class="text-2xl font-bold">{{ $payments->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Payment::where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Paid</p>
                <p class="text-2xl font-bold text-green-600">{{ \App\Models\Payment::where('status', 'paid')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Refunded</p>
                <p class="text-2xl font-bold text-red-600">{{ \App\Models\Payment::where('status', 'refunded')->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-500">
                        <th class="p-4">Transaction ID</th>
                        <th class="p-4">Guest</th>
                        <th class="p-4">Booking</th>
                        <th class="p-4">Amount</th>
                        <th class="p-4">Method</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-4 text-sm font-mono">{{ $payment->transaction_id ?? 'N/A' }}</td>
                            <td class="p-4">{{ $payment->booking->guest->name ?? 'N/A' }}</td>
                            <td class="p-4">#{{ $payment->booking_id }}</td>
                            <td class="p-4 font-medium">TSh{{ number_format($payment->amount, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $payment->method === 'cash' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payment->method === 'mobile_money' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $payment->method === 'stripe' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $payment->method === 'bank_transfer' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($payment->method)) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $payment->status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    @if ($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.confirm', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Confirm this payment?')">
                                            @csrf
                                            <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">Confirm</button>
                                        </form>
                                    @endif
                                    @if ($payment->status === 'paid')
                                        <form action="{{ route('admin.payments.refund', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Refund this payment?')">
                                            @csrf
                                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">Refund</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('payments.receipt', $payment) }}" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition">Receipt</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-8 text-center text-gray-500">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $payments->links() }}</div>
    </div>
</x-app-layout>
