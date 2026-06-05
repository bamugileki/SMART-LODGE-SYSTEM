<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Activity Log</h1>

        <div class="bg-white rounded-xl shadow p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium mb-1">Module</label>
                    <select name="module" class="border rounded px-3 py-2">
                        <option value="">All Modules</option>
                        @foreach ($modules as $m)
                            <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">Filter</button>
                <a href="{{ route('manager.audit-logs') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">Clear</a>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-left"><th class="p-4">Time</th><th class="p-4">User</th><th class="p-4">Module</th><th class="p-4">Action</th><th class="p-4">Description</th></tr></thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-4 text-xs">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td class="p-4">{{ $log->user->name ?? 'System' }}</td>
                            <td class="p-4">{{ $log->module }}</td>
                            <td class="p-4"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $log->action }}</span></td>
                            <td class="p-4 max-w-md truncate">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-400">No activity logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</x-app-layout>
