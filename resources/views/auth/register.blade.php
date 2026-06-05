<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Create account</h2>
        <p class="text-gray-500 mt-1">Join Bungestay and start booking</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-5">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Theris Pancras" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" class="block mt-1.5 w-full" type="text" name="phone" :value="old('phone')" placeholder="+2557XXXXXXXX" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="theris@bungestay.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="border-t border-gray-200 pt-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Additional Information</h3>
                <p class="text-xs text-gray-500 mb-4">Optional details to speed up future bookings</p>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="national_id" :value="__('National ID / Passport')" />
                        <x-text-input id="national_id" class="block mt-1.5 w-full" type="text" name="national_id" :value="old('national_id')" placeholder="20011209-34578-73458-37" />
                        <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="gender" :value="__('Gender')" />
                        <select id="gender" name="gender" class="block mt-1.5 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select gender</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other" @selected(old('gender') === 'other')>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                        <x-text-input id="date_of_birth" class="block mt-1.5 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="country" :value="__('Country')" />
                        <select id="country" name="country" class="block mt-1.5 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select country</option>
                            <option value="Tanzania" @selected(old('country') === 'Tanzania')>Tanzania</option>
                            <option value="Kenya" @selected(old('country') === 'Kenya')>Kenya</option>
                            <option value="Uganda" @selected(old('country') === 'Uganda')>Uganda</option>
                            <option value="Rwanda" @selected(old('country') === 'Rwanda')>Rwanda</option>
                            <option value="Burundi" @selected(old('country') === 'Burundi')>Burundi</option>
                            <option value="South Africa" @selected(old('country') === 'South Africa')>South Africa</option>
                            <option value="Nigeria" @selected(old('country') === 'Nigeria')>Nigeria</option>
                            <option value="Ghana" @selected(old('country') === 'Ghana')>Ghana</option>
                            <option value="Ethiopia" @selected(old('country') === 'Ethiopia')>Ethiopia</option>
                            <option value="Egypt" @selected(old('country') === 'Egypt')>Egypt</option>
                            <option value="United States" @selected(old('country') === 'United States')>United States</option>
                            <option value="United Kingdom" @selected(old('country') === 'United Kingdom')>United Kingdom</option>
                            <option value="India" @selected(old('country') === 'India')>India</option>
                            <option value="China" @selected(old('country') === 'China')>China</option>
                            <option value="Germany" @selected(old('country') === 'Germany')>Germany</option>
                            <option value="Other" @selected(old('country') === 'Other')>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="address" :value="__('Address')" />
                    <textarea id="address" name="address" rows="2" class="block mt-1.5 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Street, city, postal code">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800">
                {{ __('Create account') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Sign in</a>
        </p>
    </form>
</x-guest-layout>
