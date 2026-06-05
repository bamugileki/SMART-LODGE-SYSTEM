<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Quick Links</h1>
            <a href="{{ route('admin.quick-links.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">+ Add Link</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        @foreach ($sections as $section)
            @php
                $grouped = $links->where('section', $section)->groupBy(fn($l) => $l->group ?? 'Ungrouped');
            @endphp
            <div class="bg-white rounded-xl shadow mb-6">
                <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-semibold capitalize">{{ str_replace('_', ' ', $section) }}</h2>
                </div>
                <div class="p-4">
                    @foreach ($grouped as $groupName => $groupLinks)
                        @if ($grouped->count() > 1)
                            <h3 class="text-sm font-medium text-gray-500 mt-3 mb-2 {{ $loop->first ? 'mt-0' : '' }}">{{ $groupName }}</h3>
                        @endif
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500">
                                    <th class="pb-2">Order</th>
                                    <th class="pb-2">Label</th>
                                    <th class="pb-2">URL</th>
                                    <th class="pb-2">Icon</th>
                                    <th class="pb-2">Roles</th>
                                    <th class="pb-2">Active</th>
                                    <th class="pb-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupLinks as $link)
                                    <tr class="border-t">
                                        <td class="py-2">{{ $link->order }}</td>
                                        <td class="py-2 font-medium">{{ $link->label }}</td>
                                        <td class="py-2 text-gray-500 max-w-xs truncate">{{ $link->url }}</td>
                                        <td class="py-2">{{ $link->icon ?? '-' }}</td>
                                        <td class="py-2">{{ $link->roles ?? 'Public' }}</td>
                                        <td class="py-2">
                                            @if ($link->is_active)
                                                <span class="text-green-600">Yes</span>
                                            @else
                                                <span class="text-red-600">No</span>
                                            @endif
                                        </td>
                                        <td class="py-2 flex gap-2">
                                            <a href="{{ route('admin.quick-links.edit', $link) }}" class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.quick-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this link?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>