<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manage Services</h1>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Add Service</button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">Name</th><th class="p-4">Category</th><th class="p-4">Price</th><th class="p-4">Active</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr class="border-t">
                            <td class="p-4">{{ $service->name }}</td>
                            <td class="p-4">{{ $service->category ?? 'General' }}</td>
                            <td class="p-4">TSh{{ number_format($service->price, 2) }}</td>
                            <td class="p-4">{{ $service->is_active ? 'Yes' : 'No' }}</td>
                            <td class="p-4">
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this service?')">
                                    @csrf
                                    <button class="text-red-600 hover:underline text-sm">Deactivate</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $services->links() }}</div>
    </div>

    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-lg w-full mx-4">
            <h2 class="text-2xl font-bold mb-4">Add Service</h2>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="mb-4"><label class="block font-medium mb-1">Name</label><input type="text" name="name" required class="w-full border rounded px-3 py-2"></div>
                <div class="mb-4"><label class="block font-medium mb-1">Category</label><input type="text" name="category" class="w-full border rounded px-3 py-2" placeholder="e.g. Dining, Laundry"></div>
                <div class="mb-4"><label class="block font-medium mb-1">Price</label><input type="number" step="0.01" name="price" required class="w-full border rounded px-3 py-2"></div>
                <div class="mb-4"><label class="block font-medium mb-1">Description</label><textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea></div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
