<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Payment Details</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Payment Method</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="payment_type" value="mobile_money" {{ old('payment_type', $detail->payment_type) === 'mobile_money' ? 'checked' : '' }} onchange="togglePaymentType()">
                        Mobile Money
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="payment_type" value="bank" {{ old('payment_type', $detail->payment_type) === 'bank' ? 'checked' : '' }} onchange="togglePaymentType()">
                        Bank Transfer
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="payment_type" value="card" {{ old('payment_type', $detail->payment_type) === 'card' ? 'checked' : '' }} onchange="togglePaymentType()">
                        Card
                    </label>
                </div>
            </div>

            <div id="mobile-fields" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Mobile Provider</label>
                    <select name="mobile_provider" class="w-full border rounded px-3 py-2">
                        <option value="">Select Provider</option>
                        <option value="vodacom" {{ old('mobile_provider', $detail->mobile_provider) === 'vodacom' ? 'selected' : '' }}>Vodacom</option>
                        <option value="airtel" {{ old('mobile_provider', $detail->mobile_provider) === 'airtel' ? 'selected' : '' }}>Airtel</option>
                        <option value="tigo" {{ old('mobile_provider', $detail->mobile_provider) === 'tigo' ? 'selected' : '' }}>Tigo</option>
                        <option value="halotel" {{ old('mobile_provider', $detail->mobile_provider) === 'halotel' ? 'selected' : '' }}>Halotel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mobile Number</label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number', $detail->mobile_number) }}" class="w-full border rounded px-3 py-2" placeholder="e.g., 0712345678">
                </div>
            </div>

            <div id="bank-fields" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $detail->bank_name) }}" class="w-full border rounded px-3 py-2" placeholder="e.g., CRDB, NMB, NBC">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Account Number</label>
                    <input type="text" name="account_number" value="{{ old('account_number', $detail->account_number) }}" class="w-full border rounded px-3 py-2" placeholder="Account number">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Account Holder Name</label>
                    <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $detail->account_holder_name) }}" class="w-full border rounded px-3 py-2" placeholder="Full name on account">
                </div>
            </div>

            <div id="card-fields" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Card Last 4 Digits</label>
                    <input type="text" name="card_last_four" value="{{ old('card_last_four', $detail->card_last_four) }}" class="w-full border rounded px-3 py-2" maxlength="4" placeholder="1234">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Card Holder Name</label>
                    <input type="text" name="card_holder_name" value="{{ old('card_holder_name', $detail->card_holder_name) }}" class="w-full border rounded px-3 py-2" placeholder="Name on card">
                </div>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Save Payment Details</button>
        </form>
    </div>

    @push('scripts')
    <script>
        function togglePaymentType() {
            const type = document.querySelector('[name="payment_type"]:checked')?.value;
            document.getElementById('mobile-fields').style.display = type === 'mobile_money' ? 'block' : 'none';
            document.getElementById('bank-fields').style.display = type === 'bank' ? 'block' : 'none';
            document.getElementById('card-fields').style.display = type === 'card' ? 'block' : 'none';
        }
        togglePaymentType();
    </script>
    @endpush
</x-app-layout>
