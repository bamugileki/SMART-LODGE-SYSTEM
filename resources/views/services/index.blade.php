<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Hotel Services</h1>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($services as $service)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">TSh{{ number_format($service->price, 2) }}</span>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $service->category ?? 'General' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-xl text-gray-500">No services currently available.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
