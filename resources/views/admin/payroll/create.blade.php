<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.payroll.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back</a>
            <h1 class="text-2xl font-bold">Create Payroll Record</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Employee</label>
                <select name="user_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Employee</option>
                    @foreach ($employees as $emp)
                        @if (!in_array($emp->id, $existing))
                            <option value="{{ $emp->id }}" data-role="{{ $emp->role->slug ?? '' }}" data-salary="{{ match($emp->role?->slug) { 'admin' => 1500000, 'manager' => 800000, 'receptionist' => 400000, default => 300000 } }}">
                                {{ $emp->name }} ({{ ucfirst($emp->role->slug ?? 'N/A') }}) @if($emp->bankDetail) &check; @endif
                            </option>
                        @endif
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Employees already with payroll this month are hidden.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Month</label>
                <input type="month" name="month" value="{{ $currentMonth }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Base Salary (TSh)</label>
                    <input type="number" name="base_salary" step="0.01" min="0" id="base_salary" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Bonus (TSh)</label>
                    <input type="number" name="bonus" step="0.01" min="0" value="0" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deductions (TSh)</label>
                    <input type="number" name="deductions" step="0.01" min="0" value="0" class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" placeholder="Optional notes..."></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Create Payroll</button>
        </form>
    </div>

    @push('scripts')
    <script>
        document.querySelector('[name="user_id"]')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const salary = opt?.dataset?.salary;
            if (salary) document.getElementById('base_salary').value = salary;
        });
    </script>
    @endpush
</x-app-layout>
