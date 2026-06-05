<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.quick-links.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back</a>
            <h1 class="text-2xl font-bold">Add Quick Link</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.quick-links.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Label *</label>
                    <input type="text" name="label" value="{{ old('label') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">URL *</label>
                    <input type="text" name="url" value="{{ old('url') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Icon</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="w-full border rounded px-3 py-2" placeholder="e.g. home, users, calendar">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Section *</label>
                    <select name="section" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Section</option>
                        <option value="footer" {{ old('section') === 'footer' ? 'selected' : '' }}>Footer</option>
                        <option value="guest_dashboard" {{ old('section') === 'guest_dashboard' ? 'selected' : '' }}>Guest Dashboard</option>
                        <option value="receptionist_dashboard" {{ old('section') === 'receptionist_dashboard' ? 'selected' : '' }}>Receptionist Dashboard</option>
                        <option value="manager_dashboard" {{ old('section') === 'manager_dashboard' ? 'selected' : '' }}>Manager Dashboard</option>
                        <option value="admin_dashboard" {{ old('section') === 'admin_dashboard' ? 'selected' : '' }}>Admin Dashboard</option>
                        <option value="admin_cards" {{ old('section') === 'admin_cards' ? 'selected' : '' }}>Admin Dashboard Cards</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Group (for footer/dashboard grouping)</label>
                    <input type="text" name="group" value="{{ old('group') }}" class="w-full border rounded px-3 py-2" placeholder="e.g. Guest Services, Company">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Roles (comma-separated slugs)</label>
                    <input type="text" name="roles" value="{{ old('roles') }}" class="w-full border rounded px-3 py-2" placeholder="admin,manager,receptionist,guest">
                    <p class="text-xs text-gray-400 mt-1">Leave empty for public links.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                    <label for="is_active" class="text-sm font-medium">Active</label>
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Create</button>
        </form>
    </div>
</x-app-layout>