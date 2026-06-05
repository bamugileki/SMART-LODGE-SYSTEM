<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">User Management</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">+ Add User</a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">Name</th><th class="p-4">Email</th><th class="p-4">Phone</th><th class="p-4">Role</th><th class="p-4">Status</th><th class="p-4">Joined</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-4 font-medium">{{ $user->name }}</td>
                            <td class="p-4 text-sm">{{ $user->email }}</td>
                            <td class="p-4 text-sm">{{ $user->phone ?? '—' }}</td>
                            <td class="p-4"><span class="px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-800">{{ $user->role->name ?? 'N/A' }}</span></td>
                            <td class="p-4">
                                @if ($user->status === 'active' && $user->is_active)
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Active</span>
                                @elseif ($user->status === 'suspended')
                                    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Suspended</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition">Edit</a>
                                    @if ($user->is_active)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate {{ $user->name }}?')">
                                            @csrf
                                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">Deactivate</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">Activate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</x-app-layout>
