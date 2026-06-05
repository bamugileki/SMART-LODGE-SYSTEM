<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Guest Reviews</h1>
        <div class="grid gap-6">
            @forelse ($reviews as $review)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold">{{ $review->guest->name }}</p>
                            <p class="text-sm text-gray-500">Room: {{ $review->room->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                            <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4">{{ $review->comment }}</p>
                    <div class="flex gap-2">
                        @if (!$review->is_approved)
                            <form action="{{ route('manager.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button class="bg-green-600 text-white px-4 py-1 rounded text-sm hover:bg-green-700">Approve</button>
                            </form>
                        @endif
                        <form action="{{ route('manager.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                            @csrf
                            <button class="bg-red-500 text-white px-4 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-12">No reviews yet.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
    </div>
</x-app-layout>
