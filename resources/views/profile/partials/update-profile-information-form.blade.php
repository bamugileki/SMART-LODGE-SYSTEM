<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="+2557XXXXXXXX" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Additional Information</h3>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="national_id" :value="__('National ID / Passport')" />
                    <x-text-input id="national_id" name="national_id" type="text" class="mt-1 block w-full" :value="old('national_id', $user->national_id)" />
                    <x-input-error class="mt-2" :messages="$errors->get('national_id')" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d'))" />
                    <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                </div>

                <div>
                    <x-input-label for="country" :value="__('Country')" />
                    <select id="country" name="country" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Select country</option>
                        <option value="Tanzania" @selected(old('country', $user->country) === 'Tanzania')>Tanzania</option>
                        <option value="Kenya" @selected(old('country', $user->country) === 'Kenya')>Kenya</option>
                        <option value="Uganda" @selected(old('country', $user->country) === 'Uganda')>Uganda</option>
                        <option value="Rwanda" @selected(old('country', $user->country) === 'Rwanda')>Rwanda</option>
                        <option value="Burundi" @selected(old('country', $user->country) === 'Burundi')>Burundi</option>
                        <option value="South Africa" @selected(old('country', $user->country) === 'South Africa')>South Africa</option>
                        <option value="Nigeria" @selected(old('country', $user->country) === 'Nigeria')>Nigeria</option>
                        <option value="Ghana" @selected(old('country', $user->country) === 'Ghana')>Ghana</option>
                        <option value="Ethiopia" @selected(old('country', $user->country) === 'Ethiopia')>Ethiopia</option>
                        <option value="Egypt" @selected(old('country', $user->country) === 'Egypt')>Egypt</option>
                        <option value="United States" @selected(old('country', $user->country) === 'United States')>United States</option>
                        <option value="United Kingdom" @selected(old('country', $user->country) === 'United Kingdom')>United Kingdom</option>
                        <option value="India" @selected(old('country', $user->country) === 'India')>India</option>
                        <option value="China" @selected(old('country', $user->country) === 'China')>China</option>
                        <option value="Germany" @selected(old('country', $user->country) === 'Germany')>Germany</option>
                        <option value="Other" @selected(old('country', $user->country) === 'Other')>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('country')" />
                </div>
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('Address')" />
                <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
