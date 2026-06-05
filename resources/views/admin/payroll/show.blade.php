<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.payroll.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back to Payroll</a>
                <h1 class="text-2xl font-bold mt-1">Payroll Details</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.payroll.payslip', $payroll) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Download Payslip</a>
                @if ($payroll->status === 'pending')
                    <form action="{{ route('admin.payroll.approve', $payroll) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Approve</button>
                    </form>
                @endif
                @if ($payroll->status === 'approved')
                    <form action="{{ route('admin.payroll.paid', $payroll) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="return confirm('Mark as paid?')">Mark Paid</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Employee Information</h3>
                <p><span class="text-gray-500">Name:</span> {{ $payroll->user->name ?? 'N/A' }}</p>
                <p><span class="text-gray-500">Email:</span> {{ $payroll->user->email ?? 'N/A' }}</p>
                <p><span class="text-gray-500">Role:</span> <span class="capitalize">{{ $payroll->user->role->slug ?? 'N/A' }}</span></p>
                @if ($payroll->user->bankDetail)
                    <p><span class="text-gray-500">Payment Method:</span> {{ str_replace('_', ' ', ucfirst($payroll->user->bankDetail->payment_type)) }}</p>
                    @if ($payroll->user->bankDetail->bank_name)
                        <p><span class="text-gray-500">Bank:</span> {{ $payroll->user->bankDetail->bank_name }}</p>
                    @endif
                    @if ($payroll->user->bankDetail->mobile_number)
                        <p><span class="text-gray-500">Mobile:</span> {{ $payroll->user->bankDetail->mobile_number }}</p>
                    @endif
                @endif
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Salary Details</h3>
                <p><span class="text-gray-500">Month:</span> {{ $payroll->month }}</p>
                <p><span class="text-gray-500">Base Salary:</span> TSh{{ number_format($payroll->base_salary, 2) }}</p>
                <p><span class="text-gray-500">Bonus:</span> <span class="text-green-600">+TSh{{ number_format($payroll->bonus, 2) }}</span></p>
                <p><span class="text-gray-500">Deductions:</span> <span class="text-red-600">-TSh{{ number_format($payroll->deductions, 2) }}</span></p>
                <hr class="my-2">
                <p class="text-lg font-bold">Total: TSh{{ number_format($payroll->total_salary, 2) }}</p>
                <p class="mt-2">
                    <span class="text-gray-500">Status:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($payroll->status === 'paid') bg-green-100 text-green-800
                        @elseif($payroll->status === 'approved') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </p>
                @if ($payroll->processed_by)
                    <p class="mt-2"><span class="text-gray-500">Processed by:</span> {{ $payroll->processor->name ?? 'N/A' }}</p>
                @endif
                @if ($payroll->paid_at)
                    <p><span class="text-gray-500">Paid on:</span> {{ $payroll->paid_at->format('M d, Y h:i A') }}</p>
                @endif
            </div>
        </div>

        @if ($payroll->notes)
            <div class="bg-white rounded-xl shadow p-6 mt-6">
                <h3 class="font-semibold text-gray-700 mb-2">Notes</h3>
                <p class="text-gray-600">{{ $payroll->notes }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
