<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Edit User</h1>
            <a href="{{ route('admin.users') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Back to Users</a>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-lg shadow p-6 max-w-2xl">
            @csrf

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-medium mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Role</label>
                    <select name="role_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                        <option value="suspended" @selected(old('status', $user->status) === 'suspended')>Suspended</option>
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Gender</label>
                    <select name="gender" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-medium mb-1">Country</label>
                    <select name="country" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select</option>
                        <option value="Tanzania" @selected(old('country', $user->country) === 'Tanzania')>Tanzania</option>
                        <option value="Kenya" @selected(old('country', $user->country) === 'Kenya')>Kenya</option>
                        <option value="Uganda" @selected(old('country', $user->country) === 'Uganda')>Uganda</option>
                        <option value="Rwanda" @selected(old('country', $user->country) === 'Rwanda')>Rwanda</option>
                        <option value="Other" @selected(old('country', $user->country) === 'Other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">National ID / Passport</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block font-medium mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users') }}" class="px-5 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update User</button>
            </div>
        </form>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mt-6">
            <h3 class="text-lg font-semibold mb-4">Reset Password</h3>
            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">New Password</label>
                        <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
