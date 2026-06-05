<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Manage Reviews</h1>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50"><tr class="text-left text-gray-500"><th class="p-4">Guest</th><th class="p-4">Room</th><th class="p-4">Rating</th><th class="p-4">Comment</th><th class="p-4">Status</th><th class="p-4">Actions</th></tr></thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr class="border-t">
                            <td class="p-4">{{ $review->guest->name }}</td>
                            <td class="p-4">{{ $review->room->name ?? 'N/A' }}</td>
                            <td class="p-4">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                            <td class="p-4 text-sm max-w-xs truncate">{{ $review->comment }}</td>
                            <td class="p-4">{{ $review->is_approved ? 'Approved' : 'Pending' }}</td>
                            <td class="p-4">
                                @if (!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-green-600 hover:underline text-sm">Approve</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
    </div>
</x-app-layout>
