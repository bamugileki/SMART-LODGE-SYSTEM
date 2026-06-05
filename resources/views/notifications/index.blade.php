<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Notifications</h1>
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button class="text-indigo-600 hover:underline text-sm">Mark all as read</button>
            </form>
        </div>
        <div class="space-y-4">
            @forelse ($notifications as $notification)
                <div class="bg-white rounded-lg shadow p-4 {{ $notification->read_at ? '' : 'border-l-4 border-indigo-500' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ $notification->message }}</p>
                            <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (!$notification->read_at)
                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                @csrf
                                <button class="text-indigo-600 hover:underline text-sm">Mark read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-12">No notifications.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    </div>
</x-app-layout>
