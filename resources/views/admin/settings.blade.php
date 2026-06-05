<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">System Settings</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Dashboard</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex overflow-x-auto" id="settingsTabs">
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600 whitespace-nowrap" data-tab="hotel">Hotel Info</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="currency">Currency & Pricing</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="booking">Booking Rules</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="checkinout">Check-in / Out</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="rooms">Room Config</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="payment">Payment</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="notification">Notifications</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="registration">Registration</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="security">Security</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="logging">Logging</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="housekeeping">Housekeeping</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="services">Services</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="performance">Performance</button>
                    <button type="button" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap" data-tab="language">Language</button>
                </nav>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="p-6">

                    {{-- Tab 1: Hotel Information --}}
                    <div class="tab-content" id="tab-hotel">
                        <h2 class="text-xl font-semibold mb-4">Hotel Information</h2>
                        <p class="text-gray-500 text-sm mb-6">Basic hotel identity and contact details that appear to guests.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Hotel Name</label>
                                <input type="text" name="hotel_name" value="{{ $settings['hotel_name'] ?? 'BungeStay' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Hotel Slogan / Tagline</label>
                                <input type="text" name="hotel_slogan" value="{{ $settings['hotel_slogan'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Contact Email</label>
                                <input type="email" name="hotel_email" value="{{ $settings['hotel_email'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Phone Number</label>
                                <input type="text" name="hotel_phone" value="{{ $settings['hotel_phone'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium mb-1">Hotel Description (About Us)</label>
                            <textarea name="hotel_description" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['hotel_description'] ?? '' }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium mb-1">Physical Address</label>
                            <textarea name="hotel_address" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['hotel_address'] ?? '' }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium mb-1">Google Maps Location Link</label>
                            <input type="url" name="google_maps_link" value="{{ $settings['google_maps_link'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="https://maps.google.com/...">
                        </div>
                    </div>

                    {{-- Tab 2: Currency & Pricing --}}
                    <div class="tab-content hidden" id="tab-currency">
                        <h2 class="text-xl font-semibold mb-4">Currency & Pricing Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Configure financial rules used across the entire system.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Default Currency</label>
                                <select name="default_currency" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="TZS" @selected(($settings['default_currency'] ?? 'TZS') === 'TZS')>TZS (Tanzanian Shilling)</option>
                                    <option value="USD" @selected(($settings['default_currency'] ?? 'TZS') === 'USD')>USD (US Dollar)</option>
                                    <option value="EUR" @selected(($settings['default_currency'] ?? 'TZS') === 'EUR')>EUR (Euro)</option>
                                    <option value="KES" @selected(($settings['default_currency'] ?? 'TZS') === 'KES')>KES (Kenyan Shilling)</option>
                                    <option value="UGX" @selected(($settings['default_currency'] ?? 'TZS') === 'UGX')>UGX (Ugandan Shilling)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Tax / VAT Rate (%)</label>
                                <input type="number" step="0.01" name="tax_rate" value="{{ $settings['tax_rate'] ?? '18' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Service Charge (%)</label>
                                <input type="number" step="0.01" name="service_charge_percentage" value="{{ $settings['service_charge_percentage'] ?? '0' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Booking Fee</label>
                                <input type="number" step="0.01" name="booking_fee" value="{{ $settings['booking_fee'] ?? '0' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Minimum Booking Price</label>
                                <input type="number" step="0.01" name="min_booking_price" value="{{ $settings['min_booking_price'] ?? '0' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Price Rounding</label>
                                <select name="price_rounding" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="none" @selected(($settings['price_rounding'] ?? 'none') === 'none')>No rounding</option>
                                    <option value="nearest" @selected(($settings['price_rounding'] ?? 'none') === 'nearest')>Round to nearest</option>
                                    <option value="ceil" @selected(($settings['price_rounding'] ?? 'none') === 'ceil')>Always round up</option>
                                    <option value="floor" @selected(($settings['price_rounding'] ?? 'none') === 'floor')>Always round down</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Booking Rules --}}
                    <div class="tab-content hidden" id="tab-booking">
                        <h2 class="text-xl font-semibold mb-4">Booking Rules Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Define hotel policies for guest reservations.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Minimum Stay (nights)</label>
                                <input type="number" min="1" name="min_stay" value="{{ $settings['min_stay'] ?? '1' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Maximum Stay (nights)</label>
                                <input type="number" min="1" name="max_stay" value="{{ $settings['max_stay'] ?? '30' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Cancellation Policy</label>
                                <select name="cancellation_policy" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="flexible" @selected(($settings['cancellation_policy'] ?? 'moderate') === 'flexible')>Flexible (free 24h before)</option>
                                    <option value="moderate" @selected(($settings['cancellation_policy'] ?? 'moderate') === 'moderate')>Moderate (free 48h before)</option>
                                    <option value="strict" @selected(($settings['cancellation_policy'] ?? 'moderate') === 'strict')>Strict (free 7 days before)</option>
                                    <option value="non_refundable" @selected(($settings['cancellation_policy'] ?? 'moderate') === 'non_refundable')>Non-refundable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Free Cancellation Window (hours before check-in)</label>
                                <input type="number" min="0" name="free_cancellation_window" value="{{ $settings['free_cancellation_window'] ?? '48' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Booking Confirmation</label>
                                <select name="booking_auto_confirm" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['booking_auto_confirm'] ?? '1') == '1')>Auto-confirm</option>
                                    <option value="0" @selected(($settings['booking_auto_confirm'] ?? '1') == '0')>Manual approval required</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Allow Same-Day Booking</label>
                                <select name="allow_same_day_booking" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['allow_same_day_booking'] ?? '1') == '1')>Yes</option>
                                    <option value="0" @selected(($settings['allow_same_day_booking'] ?? '1') == '0')>No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 4: Check-in / Check-out --}}
                    <div class="tab-content hidden" id="tab-checkinout">
                        <h2 class="text-xl font-semibold mb-4">Check-in / Check-out Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Operational rules for guest arrivals and departures.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Standard Check-in Time</label>
                                <input type="time" name="checkin_time" value="{{ $settings['checkin_time'] ?? '14:00' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Standard Check-out Time</label>
                                <input type="time" name="checkout_time" value="{{ $settings['checkout_time'] ?? '11:00' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Late Check-out Fee</label>
                                <input type="number" step="0.01" name="late_checkout_fee" value="{{ $settings['late_checkout_fee'] ?? '0' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Early Check-in Fee</label>
                                <input type="number" step="0.01" name="early_checkin_fee" value="{{ $settings['early_checkin_fee'] ?? '0' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Grace Period (minutes)</label>
                                <input type="number" min="0" name="grace_period_minutes" value="{{ $settings['grace_period_minutes'] ?? '30' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                <p class="text-xs text-gray-400 mt-1">Extra time allowed before late fees apply.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 5: Room Configuration --}}
                    <div class="tab-content hidden" id="tab-rooms">
                        <h2 class="text-xl font-semibold mb-4">Room Configuration Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Control how rooms behave in the system.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Default Room Status</label>
                                <select name="default_room_status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="available" @selected(($settings['default_room_status'] ?? 'available') === 'available')>Available</option>
                                    <option value="dirty" @selected(($settings['default_room_status'] ?? 'available') === 'dirty')>Dirty (needs cleaning)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Auto Room Availability</label>
                                <select name="auto_room_availability" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['auto_room_availability'] ?? '1') == '1')>Enabled</option>
                                    <option value="0" @selected(($settings['auto_room_availability'] ?? '1') == '0')>Disabled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Room Hold Time After Booking (minutes)</label>
                                <input type="number" min="0" name="room_hold_minutes" value="{{ $settings['room_hold_minutes'] ?? '60' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Allow Overbooking</label>
                                <select name="overbooking_allowance" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['overbooking_allowance'] ?? '0') == '1')>Yes</option>
                                    <option value="0" @selected(($settings['overbooking_allowance'] ?? '0') == '0')>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium mb-1">Room Cleaning Reset Rules</label>
                            <textarea name="room_cleaning_reset" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['room_cleaning_reset'] ?? 'Room status resets to dirty after check-out.' }}</textarea>
                        </div>
                    </div>

                    {{-- Tab 6: Payment Settings --}}
                    <div class="tab-content hidden" id="tab-payment">
                        <h2 class="text-xl font-semibold mb-4">Payment Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Configure payment methods and financial rules.</p>
                        <div class="space-y-4 mb-6">
                            <h3 class="font-medium text-gray-700">Enable Payment Methods</h3>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_cash" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_cash'] ?? '1') == '1')>
                                <span>Cash</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_mobile_money" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_mobile_money'] ?? '0') == '1')>
                                <span>Mobile Money (M-Pesa, Airtel Money)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_stripe" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_stripe'] ?? '0') == '1')>
                                <span>Stripe / Card Payments</span>
                            </label>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Payment Confirmation</label>
                                <select name="payment_auto_confirm" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['payment_auto_confirm'] ?? '1') == '1')>Automatic</option>
                                    <option value="0" @selected(($settings['payment_auto_confirm'] ?? '1') == '0')>Manual approval</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Refund Policy</label>
                                <select name="refund_policy" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="full" @selected(($settings['refund_policy'] ?? 'partial') === 'full')>Full refund within window</option>
                                    <option value="partial" @selected(($settings['refund_policy'] ?? 'partial') === 'partial')>Partial refund</option>
                                    <option value="none" @selected(($settings['refund_policy'] ?? 'partial') === 'none')>No refund</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Payment Timeout (minutes)</label>
                                <input type="number" min="1" name="payment_timeout_minutes" value="{{ $settings['payment_timeout_minutes'] ?? '30' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-6">
                            <h3 class="font-medium text-gray-700 mb-4">Hotel Payment Account Details</h3>
                            <p class="text-sm text-gray-500 mb-4">These details appear on guest receipts so they know where payments were sent.</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium mb-1">Bank Name</label>
                                    <input type="text" name="hotel_bank_name" value="{{ $settings['hotel_bank_name'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="e.g., CRDB, NMB, NBC">
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Account Number</label>
                                    <input type="text" name="hotel_account_number" value="{{ $settings['hotel_account_number'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="e.g., 0123456789">
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Account Holder Name</label>
                                    <input type="text" name="hotel_account_holder" value="{{ $settings['hotel_account_holder'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="Business name on account">
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Mobile Money Provider</label>
                                    <input type="text" name="hotel_mobile_provider" value="{{ $settings['hotel_mobile_provider'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="e.g., Vodacom, Airtel, Tigo">
                                </div>
                                <div>
                                    <label class="block font-medium mb-1">Mobile Money Number</label>
                                    <input type="text" name="hotel_mobile_number" value="{{ $settings['hotel_mobile_number'] ?? '' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500" placeholder="e.g., 0712345678">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 7: Notification Settings --}}
                    <div class="tab-content hidden" id="tab-notification">
                        <h2 class="text-xl font-semibold mb-4">Notification Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Control how the system communicates with guests and staff.</p>
                        <div class="space-y-4 mb-6">
                            <h3 class="font-medium text-gray-700">Enable Notification Channels</h3>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_email_notifications" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_email_notifications'] ?? '1') == '1')>
                                <span>Email Notifications</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_sms_notifications" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_sms_notifications'] ?? '0') == '1')>
                                <span>SMS Notifications</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_whatsapp_notifications" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_whatsapp_notifications'] ?? '0') == '1')>
                                <span>WhatsApp Notifications</span>
                            </label>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-medium text-gray-700 mb-2">Message Templates</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Booking Confirmation Template</label>
                                    <textarea name="template_booking_confirmation" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ $settings['template_booking_confirmation'] ?? 'Your booking #{booking_number} is confirmed. Check-in: {check_in}, Room: {room_name}.' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Payment Confirmation Template</label>
                                    <textarea name="template_payment_confirmation" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ $settings['template_payment_confirmation'] ?? 'Payment of {amount} received for booking #{booking_number}. Thank you!' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Check-in Reminder Template</label>
                                    <textarea name="template_checkin_reminder" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ $settings['template_checkin_reminder'] ?? 'Your check-in is tomorrow at {checkin_time}. We look forward to welcoming you!' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Check-out Reminder Template</label>
                                    <textarea name="template_checkout_reminder" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ $settings['template_checkout_reminder'] ?? 'Your check-out is tomorrow at {checkout_time}. Thank you for staying with us!' }}</textarea>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Use placeholders: <code>{booking_number}</code> <code>{check_in}</code> <code>{check_out}</code> <code>{room_name}</code> <code>{amount}</code> <code>{checkin_time}</code> <code>{checkout_time}</code></p>
                        </div>
                    </div>

                    {{-- Tab 8: User Registration --}}
                    <div class="tab-content hidden" id="tab-registration">
                        <h2 class="text-xl font-semibold mb-4">User Registration Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Control how users sign up and access the system.</p>
                        <div class="space-y-4 mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_registration" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_registration'] ?? '1') == '1')>
                                <span>Enable User Registration</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="require_email_verification" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['require_email_verification'] ?? '0') == '1')>
                                <span>Require Email Verification</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="require_phone_verification" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['require_phone_verification'] ?? '0') == '1')>
                                <span>Require Phone Verification (OTP)</span>
                            </label>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">New User Approval</label>
                                <select name="auto_approve_users" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['auto_approve_users'] ?? '1') == '1')>Auto-approve new users</option>
                                    <option value="0" @selected(($settings['auto_approve_users'] ?? '1') == '0')>Manual approval required</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Default Role for New Users</label>
                                <select name="default_user_role" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(($settings['default_user_role'] ?? '3') == $role->id)>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 9: Security --}}
                    <div class="tab-content hidden" id="tab-security">
                        <h2 class="text-xl font-semibold mb-4">Security Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Strengthen system protection against unauthorized access.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Session Timeout (minutes)</label>
                                <input type="number" min="1" name="session_timeout_minutes" value="{{ $settings['session_timeout_minutes'] ?? '120' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Login Attempt Limit</label>
                                <input type="number" min="1" name="login_attempt_limit" value="{{ $settings['login_attempt_limit'] ?? '5' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Account Lock After (failed attempts)</label>
                                <input type="number" min="1" name="account_lock_attempts" value="{{ $settings['account_lock_attempts'] ?? '5' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Password Strength</label>
                                <select name="password_strength_rules" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="low" @selected(($settings['password_strength_rules'] ?? 'medium') === 'low')>Low (min 6 chars)</option>
                                    <option value="medium" @selected(($settings['password_strength_rules'] ?? 'medium') === 'medium')>Medium (min 8 chars, mixed)</option>
                                    <option value="high" @selected(($settings['password_strength_rules'] ?? 'medium') === 'high')>High (min 12 chars, symbols required)</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-4 mt-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_2fa" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_2fa'] ?? '0') == '1')>
                                <span>Enable Two-Factor Authentication (2FA)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Tab 10: System Logging --}}
                    <div class="tab-content hidden" id="tab-logging">
                        <h2 class="text-xl font-semibold mb-4">System Logging Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Control what activities are tracked for accountability.</p>
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_audit_logs" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_audit_logs'] ?? '1') == '1')>
                                <span>Enable Audit Logs</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="log_login_activities" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['log_login_activities'] ?? '1') == '1')>
                                <span>Log Login Activities</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="log_booking_changes" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['log_booking_changes'] ?? '1') == '1')>
                                <span>Log Booking Changes</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="log_payment_changes" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['log_payment_changes'] ?? '1') == '1')>
                                <span>Log Payment Changes</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="log_admin_actions" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['log_admin_actions'] ?? '1') == '1')>
                                <span>Log Admin Actions</span>
                            </label>
                        </div>
                    </div>

                    {{-- Tab 11: Housekeeping --}}
                    <div class="tab-content hidden" id="tab-housekeeping">
                        <h2 class="text-xl font-semibold mb-4">Housekeeping Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Manage cleaning workflows and room maintenance.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Auto-Assign Cleaning After Check-out</label>
                                <select name="auto_assign_cleaning" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['auto_assign_cleaning'] ?? '1') == '1')>Yes</option>
                                    <option value="0" @selected(($settings['auto_assign_cleaning'] ?? '1') == '0')>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Cleaning Priority Rules</label>
                                <select name="cleaning_priority_rules" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="checkout" @selected(($settings['cleaning_priority_rules'] ?? 'checkout') === 'checkout')>Check-out first</option>
                                    <option value="vip" @selected(($settings['cleaning_priority_rules'] ?? 'checkout') === 'vip')>VIP guests first</option>
                                    <option value="arrival" @selected(($settings['cleaning_priority_rules'] ?? 'checkout') === 'arrival')>Upcoming arrivals first</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Cleaning Status Reset</label>
                                <select name="cleaning_status_reset" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="auto" @selected(($settings['cleaning_status_reset'] ?? 'auto') === 'auto')>Auto reset after check-out</option>
                                    <option value="manual" @selected(($settings['cleaning_status_reset'] ?? 'auto') === 'manual')>Manual reset by staff</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Cleaning Notification Triggers</label>
                                <select name="cleaning_notification_triggers" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="immediate" @selected(($settings['cleaning_notification_triggers'] ?? 'immediate') === 'immediate')>Immediate on check-out</option>
                                    <option value="scheduled" @selected(($settings['cleaning_notification_triggers'] ?? 'immediate') === 'scheduled')>Scheduled batch</option>
                                    <option value="manual" @selected(($settings['cleaning_notification_triggers'] ?? 'immediate') === 'manual')>Manual trigger only</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 12: Services Settings --}}
                    <div class="tab-content hidden" id="tab-services">
                        <h2 class="text-xl font-semibold mb-4">Services Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Configure extra hotel services offered to guests.</p>
                        <div class="space-y-4 mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_services_module" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(($settings['enable_services_module'] ?? '1') == '1')>
                                <span>Enable Services Module (Restaurant, Laundry, etc.)</span>
                            </label>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Default Service Pricing</label>
                                <select name="default_service_pricing" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="fixed" @selected(($settings['default_service_pricing'] ?? 'fixed') === 'fixed')>Fixed price</option>
                                    <option value="per_unit" @selected(($settings['default_service_pricing'] ?? 'fixed') === 'per_unit')>Per unit / quantity</option>
                                    <option value="per_person" @selected(($settings['default_service_pricing'] ?? 'fixed') === 'per_person')>Per person</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Service Tax Rules</label>
                                <select name="service_tax_rules" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="inclusive" @selected(($settings['service_tax_rules'] ?? 'inclusive') === 'inclusive')>Tax included in price</option>
                                    <option value="exclusive" @selected(($settings['service_tax_rules'] ?? 'inclusive') === 'exclusive')>Tax added to price</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Service Categories</label>
                                <select name="service_categories" multiple size="5" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    @php
                                        $selectedCats = explode(',', $settings['service_categories'] ?? 'restaurant,laundry,transport,spa');
                                    @endphp
                                    <option value="restaurant" @selected(in_array('restaurant', $selectedCats))>Restaurant</option>
                                    <option value="laundry" @selected(in_array('laundry', $selectedCats))>Laundry</option>
                                    <option value="transport" @selected(in_array('transport', $selectedCats))>Transport</option>
                                    <option value="spa" @selected(in_array('spa', $selectedCats))>Spa</option>
                                    <option value="conference" @selected(in_array('conference', $selectedCats))>Conference</option>
                                    <option value="gym" @selected(in_array('gym', $selectedCats))>Gym</option>
                                    <option value="bar" @selected(in_array('bar', $selectedCats))>Bar</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Ctrl+click to select multiple.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 13: System Performance --}}
                    <div class="tab-content hidden" id="tab-performance">
                        <h2 class="text-xl font-semibold mb-4">System Performance Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Optimize system behavior and maintenance.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Cache Settings</label>
                                <select name="cache_settings" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="enabled" @selected(($settings['cache_settings'] ?? 'enabled') === 'enabled')>Enabled</option>
                                    <option value="disabled" @selected(($settings['cache_settings'] ?? 'enabled') === 'disabled')>Disabled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Database Backup Frequency</label>
                                <select name="db_backup_frequency" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="daily" @selected(($settings['db_backup_frequency'] ?? 'daily') === 'daily')>Daily</option>
                                    <option value="weekly" @selected(($settings['db_backup_frequency'] ?? 'daily') === 'weekly')>Weekly</option>
                                    <option value="monthly" @selected(($settings['db_backup_frequency'] ?? 'daily') === 'monthly')>Monthly</option>
                                    <option value="manual" @selected(($settings['db_backup_frequency'] ?? 'daily') === 'manual')>Manual only</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Report Generation Schedule</label>
                                <select name="report_generation_schedule" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="daily" @selected(($settings['report_generation_schedule'] ?? 'daily') === 'daily')>Daily</option>
                                    <option value="weekly" @selected(($settings['report_generation_schedule'] ?? 'daily') === 'weekly')>Weekly</option>
                                    <option value="monthly" @selected(($settings['report_generation_schedule'] ?? 'daily') === 'monthly')>Monthly</option>
                                    <option value="on_demand" @selected(($settings['report_generation_schedule'] ?? 'daily') === 'on_demand')>On demand only</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">System Maintenance Mode</label>
                                <select name="maintenance_mode" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="0" @selected(($settings['maintenance_mode'] ?? '0') == '0')>Live (normal operation)</option>
                                    <option value="1" @selected(($settings['maintenance_mode'] ?? '0') == '1')>Maintenance Mode</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">API Rate Limit (requests per minute)</label>
                                <input type="number" min="1" name="api_rate_limits" value="{{ $settings['api_rate_limits'] ?? '60' }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- Tab 14: Language & Localization --}}
                    <div class="tab-content hidden" id="tab-language">
                        <h2 class="text-xl font-semibold mb-4">Language & Localization Settings</h2>
                        <p class="text-gray-500 text-sm mb-6">Configure language and regional preferences.</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Default System Language</label>
                                <select name="default_language" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="en" @selected(($settings['default_language'] ?? 'en') === 'en')>English</option>
                                    <option value="sw" @selected(($settings['default_language'] ?? 'en') === 'sw')>Swahili</option>
                                    <option value="fr" @selected(($settings['default_language'] ?? 'en') === 'fr')>French</option>
                                    <option value="ar" @selected(($settings['default_language'] ?? 'en') === 'ar')>Arabic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Multi-Language Support</label>
                                <select name="enable_multi_language" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="1" @selected(($settings['enable_multi_language'] ?? '0') == '1')>Enabled</option>
                                    <option value="0" @selected(($settings['enable_multi_language'] ?? '0') == '0')>Disabled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Date Format</label>
                                <select name="date_format" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="d/m/Y" @selected(($settings['date_format'] ?? 'd/m/Y') === 'd/m/Y')>DD/MM/YYYY</option>
                                    <option value="m/d/Y" @selected(($settings['date_format'] ?? 'd/m/Y') === 'm/d/Y')>MM/DD/YYYY</option>
                                    <option value="Y-m-d" @selected(($settings['date_format'] ?? 'd/m/Y') === 'Y-m-d')>YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Time Format</label>
                                <select name="time_format" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                                    <option value="12h" @selected(($settings['time_format'] ?? '12h') === '12h')>12-hour (AM/PM)</option>
                                    <option value="24h" @selected(($settings['time_format'] ?? '12h') === '24h')>24-hour</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="border-t border-gray-200 px-6 py-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('border-indigo-600', 'text-indigo-600');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-indigo-600', 'text-indigo-600');

                document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
                document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>
