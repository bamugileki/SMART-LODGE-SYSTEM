<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Create User</h1>
            <a href="{{ route('admin.users') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Back to Users</a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 max-w-2xl">
            @csrf

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-medium mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="+2557XXXXXXXX">
                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Role</label>
                    <select name="role_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Password</label>
                    <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Min. 8 characters">
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="border-t pt-4 mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Additional Information</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">National ID / Passport</label>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Gender</label>
                        <select name="gender" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other" @selected(old('gender') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Country</label>
                        <select name="country" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select</option>
                            <option value="Tanzania" @selected(old('country') === 'Tanzania')>Tanzania</option>
                            <option value="Kenya" @selected(old('country') === 'Kenya')>Kenya</option>
                            <option value="Uganda" @selected(old('country') === 'Uganda')>Uganda</option>
                            <option value="Rwanda" @selected(old('country') === 'Rwanda')>Rwanda</option>
                            <option value="Other" @selected(old('country') === 'Other')>Other</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block font-medium mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users') }}" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Create User</button>
            </div>
        </form>
    </div>
</x-app-layout>
