<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">{{ $room->name }} Availability</h1>
                <p class="text-gray-500 text-sm">{{ $room->roomType->name ?? 'N/A' }} &middot; TSh{{ number_format($room->price_per_night, 2) }}/night</p>
            </div>
            <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 hover:underline text-sm">&larr; Back to Room</a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b">
                <a href="{{ route('rooms.calendar', ['id' => $room->id, 'year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="text-lg font-semibold">{{ $startOfMonth->format('F Y') }}</h2>
                <a href="{{ route('rooms.calendar', ['id' => $room->id, 'year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="p-4">
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">{{ $day }}</div>
                    @endforeach
                </div>

                @php
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                @endphp

                <div class="grid grid-cols-7 gap-1">
                    @for ($i = 0; $i < $startDayOfWeek; $i++)
                        <div class="min-h-[60px]"></div>
                    @endfor

                    @foreach ($days as $day)
                        @php
                            $d = $day['date'];
                            $status = $day['status'];
                            $isToday = $d->isToday();

                            $bgColor = match($status) {
                                'available' => 'bg-green-50 hover:bg-green-100 border-green-200',
                                'booked' => 'bg-yellow-50 hover:bg-yellow-100 border-yellow-200',
                                'occupied' => 'bg-red-50 hover:bg-red-100 border-red-200',
                                'maintenance' => 'bg-gray-100 hover:bg-gray-200 border-gray-300',
                                'past' => 'bg-gray-50 text-gray-400 border-gray-100',
                                default => 'bg-white hover:bg-gray-50 border-gray-200',
                            };

                            $statusDot = match($status) {
                                'available' => 'bg-green-500',
                                'booked' => 'bg-yellow-500',
                                'occupied' => 'bg-red-500',
                                'maintenance' => 'bg-gray-400',
                                'past' => 'bg-gray-300',
                                default => 'bg-gray-300',
                            };

                            $statusLabel = match($status) {
                                'available' => 'Available',
                                'booked' => 'Booked',
                                'occupied' => 'Occupied',
                                'maintenance' => 'Cleaning',
                                'past' => '',
                                default => '',
                            };
                        @endphp
                        <div class="min-h-[60px] border rounded-lg p-1.5 {{ $bgColor }} {{ $isToday ? 'ring-2 ring-indigo-400' : '' }} relative">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium {{ $isToday ? 'text-indigo-600' : '' }}">{{ $d->day }}</span>
                                @if ($status !== 'past')
                                    <span class="w-2 h-2 rounded-full {{ $statusDot }}"></span>
                                @endif
                            </div>
                            @if ($statusLabel)
                                <p class="text-xs mt-1 {{ $status === 'past' ? 'text-gray-300' : 'text-gray-600' }}">{{ $statusLabel }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6 mt-4 text-sm text-gray-600">
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Available</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Booked</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Occupied</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Cleaning</div>
        </div>

        <div class="mt-6">
            <a href="{{ route('rooms.show', $room) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold inline-block">Book This Room</a>
        </div>
    </div>
</x-app-layout>
