<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8" id="receipt">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow p-8 print:shadow-none print:p-0 relative">
            {{-- Verification Badge --}}
            @if ($payment->isVerified())
                <div class="absolute top-4 right-4 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 print:hidden">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                    Verified
                </div>
            @endif

            {{-- Header --}}
            <div class="text-center mb-6 print:mb-4 border-b pb-6 print:pb-4">
                <h1 class="text-2xl font-bold">{{ $settings['hotel_name'] ?? 'BungeStay' }}</h1>
                @if (!empty($settings['hotel_slogan']))
                    <p class="text-gray-500 text-sm italic">{{ $settings['hotel_slogan'] }}</p>
                @endif
                @if (!empty($settings['hotel_address']))
                    <p class="text-gray-500 text-xs">{{ $settings['hotel_address'] }}</p>
                @endif
                <div class="mt-3">
                    <h2 class="text-lg font-bold tracking-wider">PAYMENT RECEIPT</h2>
                    <p class="text-gray-400 text-xs">Official Payment Confirmation Document</p>
                </div>
            </div>

            {{-- Receipt Reference --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-semibold text-gray-700">Receipt Number</span>
                    <span class="text-lg font-bold text-indigo-700">{{ $payment->receipt_number }}</span>
                </div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Transaction ID</span><span>{{ $payment->transaction_id }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Date Issued</span><span>{{ $payment->paid_at->format('M d, Y h:i A') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Payment Status</span>
                    <span class="font-medium text-green-600">{{ ucfirst($payment->status) }}</span>
                </div>
                @if ($payment->isVerified())
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Verified By</span><span>{{ $payment->verifier?->name ?? 'System' }} on {{ $payment->verified_at->format('M d, Y h:i A') }}</span></div>
                @endif
            </div>

            {{-- Booking Reference --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <h3 class="font-semibold text-sm text-gray-700 mb-2 uppercase tracking-wide">Booking Reference</h3>
                <div class="grid grid-cols-2 gap-1 text-sm">
                    <span class="text-gray-500">Booking #</span><span class="font-medium">{{ $payment->booking->booking_number }}</span>
                    <span class="text-gray-500">Booking Status</span>
                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->booking->status)) }}</span>
                    <span class="text-gray-500">Created</span><span>{{ $payment->booking->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            {{-- Guest Information --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <h3 class="font-semibold text-sm text-gray-700 mb-2 uppercase tracking-wide">Guest Information</h3>
                <div class="grid grid-cols-2 gap-1 text-sm">
                    <span class="text-gray-500">Full Name</span><span class="font-medium">{{ $payment->booking->guest->name }}</span>
                    <span class="text-gray-500">Email</span><span>{{ $payment->booking->guest->email }}</span>
                    @if ($payment->booking->guest->phone)
                        <span class="text-gray-500">Phone</span><span>{{ $payment->booking->guest->phone }}</span>
                    @endif
                    @if ($payment->booking->guest->national_id)
                        <span class="text-gray-500">National ID</span><span>{{ $payment->booking->guest->national_id }}</span>
                    @endif
                </div>
            </div>

            {{-- Room & Stay Details --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <h3 class="font-semibold text-sm text-gray-700 mb-2 uppercase tracking-wide">Room & Stay Details</h3>
                <div class="grid grid-cols-2 gap-1 text-sm">
                    <span class="text-gray-500">Room</span><span class="font-medium">{{ $payment->booking->room->name }}</span>
                    <span class="text-gray-500">Room Type</span><span>{{ $payment->booking->room->roomType->name ?? 'N/A' }}</span>
                    <span class="text-gray-500">Check-in</span><span>{{ $payment->booking->check_in->format('d M Y') }}</span>
                    <span class="text-gray-500">Check-out</span><span>{{ $payment->booking->check_out->format('d M Y') }}</span>
                    <span class="text-gray-500">Nights</span><span>{{ $payment->booking->total_nights }}</span>
                    <span class="text-gray-500">Guests</span><span>{{ $payment->booking->guests_count }}</span>
                </div>
            </div>

            {{-- Payment Breakdown --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <h3 class="font-semibold text-sm text-gray-700 mb-2 uppercase tracking-wide">Payment Breakdown</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span>Room charges ({{ $payment->booking->total_nights }} nights \u00d7 TSh {{ number_format($payment->booking->price_per_night, 2) }})</span><span>TSh {{ number_format($payment->booking->subtotal, 2) }}</span></div>
                    @if ($payment->booking->services_total > 0)
                        <div class="flex justify-between"><span>Services</span><span>TSh {{ number_format($payment->booking->services_total, 2) }}</span></div>
                    @endif
                    @if ($payment->booking->discount > 0)
                        <div class="flex justify-between text-green-600"><span>Discount</span><span>-TSh {{ number_format($payment->booking->discount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between font-bold text-base border-t pt-2 mt-2">
                        <span>Total Paid</span>
                        <span class="text-indigo-600">TSh {{ number_format($payment->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Processed By</span>
                    <span>{{ $payment->processor?->name ?? 'System' }}</span>
                </div>
                @if ($payment->notes)
                    <div class="text-sm mt-2">
                        <span class="text-gray-500">Notes:</span>
                        <p class="text-gray-600 mt-1">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Account Details --}}
            @php $method = $payment->method; @endphp
            @if (in_array($method, ['bank_transfer', 'mobile_money', 'stripe']))
                <div class="border-b pb-4 mb-4 print:pb-3 print:mb-3">
                    <h3 class="font-semibold text-sm text-gray-700 mb-2 uppercase tracking-wide">Payment Account Details</h3>
                    <div class="text-sm space-y-1">
                        @if ($method === 'bank_transfer')
                            <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-medium">{{ $settings['hotel_bank_name'] ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Account Number</span><span class="font-medium">{{ $settings['hotel_account_number'] ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Account Holder</span><span>{{ $settings['hotel_account_holder'] ?? 'N/A' }}</span></div>
                        @elseif ($method === 'mobile_money')
                            <div class="flex justify-between"><span class="text-gray-500">Provider</span><span class="font-medium">{{ $settings['hotel_mobile_provider'] ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Mobile Number</span><span class="font-medium">{{ $settings['hotel_mobile_number'] ?? 'N/A' }}</span></div>
                        @elseif ($method === 'stripe')
                            <div class="flex justify-between"><span class="text-gray-500">Card / Online Payment</span><span class="font-medium">Stripe</span></div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Verification Section (staff only) --}}
            @if (Auth::user()->isAdmin() || Auth::user()->isReceptionist())
                <div class="border-t pt-4 mt-4 print:hidden">
                    <h3 class="font-semibold text-sm mb-3">Receipt Verification</h3>
                    <div class="bg-gray-50 rounded-lg p-4 text-sm space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Receipt #</span>
                            <span class="font-mono font-medium">{{ $payment->receipt_number }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Booking #</span>
                            <span>{{ $payment->booking->booking_number }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Amount</span>
                            <span class="font-medium">TSh {{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Guest</span>
                            <span>{{ $payment->booking->guest->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Room</span>
                            <span>{{ $payment->booking->room->name }} ({{ $payment->booking->check_in->format('d M') }} - {{ $payment->booking->check_out->format('d M') }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Status</span>
                            <span class="font-medium text-green-600">{{ ucfirst($payment->status) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-32 text-gray-500">Verified</span>
                            @if ($payment->isVerified())
                                <span class="text-green-600 font-medium">Yes — by {{ $payment->verifier?->name ?? 'System' }} on {{ $payment->verified_at->format('d M Y h:i A') }}</span>
                            @else
                                <span class="text-yellow-600 font-medium">Not yet verified</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2 mt-3">
                        @if (!$payment->isVerified())
                            <form action="{{ route('payments.verify', $payment) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">Verify Receipt</button>
                            </form>
                        @elseif (Auth::user()->isAdmin())
                            <form action="{{ route('payments.unverify', $payment) }}" method="POST" onsubmit="return confirm('Un-verify this receipt?')">
                                @csrf
                                <button type="submit" class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition text-sm font-medium">Un-verify</button>
                            </form>
                        @endif
                        <a href="{{ route('bookings.show', $payment->booking) }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">View Booking</a>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="text-center mt-6 flex justify-center gap-3 print:hidden">
                <a href="{{ route('payments.download-receipt', $payment) }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download PDF Receipt
                </a>
                <button onclick="window.print()" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Receipt
                </button>
            </div>

            <div class="text-center text-xs text-gray-400 mt-8 print:mt-6 border-t pt-4 print:pt-3">
                <p>This is a computer-generated official receipt from {{ $settings['hotel_name'] ?? 'BungeStay' }}.</p>
                <p>Receipt #{{ $payment->receipt_number }} | {{ $payment->paid_at->format('Y-m-d h:i A') }}</p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            nav, footer, [x-cloak] { display: none !important; }
            #receipt { max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</x-app-layout>
