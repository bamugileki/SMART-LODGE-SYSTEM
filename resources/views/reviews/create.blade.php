<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Review Your Stay</h1>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <p><span class="font-medium">Room:</span> {{ $booking->room->name }}</p>
            <p><span class="font-medium">Stay:</span> {{ $booking->check_in->format('M d, Y') }} - {{ $booking->check_out->format('M d, Y') }}</p>
        </div>
        <form action="{{ route('reviews.store', $booking) }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1">Rating</label>
                <select name="rating" class="w-full border rounded-lg px-3 py-2">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} {{ Str::plural('Star', $i) }}</option>
                    @endfor
                </select>
                @error('rating') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Comment</label>
                <textarea name="comment" rows="5" class="w-full border rounded-lg px-3 py-2" placeholder="Share your experience..."></textarea>
                @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold">Submit Review</button>
        </form>
    </div>
</x-app-layout>
