<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Payroll Management</h1>
                <p class="text-gray-500 mt-1">Employee salary records</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.payroll.generate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" onclick="return confirm('Generate payroll for all employees this month?')">Auto-Generate</button>
                </form>
                <a href="{{ route('admin.payroll.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add Record</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                <p class="text-gray-500 text-sm">This Month Total</p>
                <p class="text-2xl font-bold text-indigo-600">TSh{{ number_format($stats['total_this_month'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <p class="text-gray-500 text-sm">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_count'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <p class="text-gray-500 text-sm">Paid</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['paid_count'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                <p class="text-gray-500 text-sm">Employees</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['employee_count'] }}</p>
            </div>
        </div>

        <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Month</label>
                <select name="month" class="border rounded px-3 py-2">
                    <option value="">All Months</option>
                    @foreach ($months as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">Filter</button>
            <a href="{{ route('admin.payroll.index') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">Clear</a>
        </form>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-left"><th class="p-4">Employee</th><th class="p-4">Role</th><th class="p-4">Month</th><th class="p-4">Base</th><th class="p-4">Bonus</th><th class="p-4">Deductions</th><th class="p-4">Total</th><th class="p-4">Status</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($payrolls as $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-4 font-medium">{{ $p->user->name ?? 'N/A' }}</td>
                            <td class="p-4 capitalize">{{ $p->user->role->slug ?? 'N/A' }}</td>
                            <td class="p-4">{{ $p->month }}</td>
                            <td class="p-4">TSh{{ number_format($p->base_salary, 2) }}</td>
                            <td class="p-4 text-green-600">TSh{{ number_format($p->bonus, 2) }}</td>
                            <td class="p-4 text-red-600">-TSh{{ number_format($p->deductions, 2) }}</td>
                            <td class="p-4 font-bold">TSh{{ number_format($p->total_salary, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($p->status === 'paid') bg-green-100 text-green-800
                                    @elseif($p->status === 'approved') bg-blue-100 text-blue-800
                                    @elseif($p->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="p-4 flex gap-1">
                                <a href="{{ route('admin.payroll.show', $p) }}" class="text-indigo-600 hover:underline text-xs">View</a>
                                @if ($p->status === 'pending')
                                    <form action="{{ route('admin.payroll.approve', $p) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline text-xs">Approve</button>
                                    </form>
                                @endif
                                @if ($p->status === 'approved')
                                    <form action="{{ route('admin.payroll.paid', $p) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline text-xs">Mark Paid</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $payrolls->links() }}</div>
    </div>
</x-app-layout>
