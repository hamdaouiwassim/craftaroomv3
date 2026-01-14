@php
    $profileUser = $user ?? auth()->user();
    $currencies = $currencies ?? \App\Models\Currency::all();
    $languages = $languages ?? [
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español',
        'ar' => 'العربية',
    ];
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $profileUser->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $profileUser->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($profileUser instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $profileUser->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="avatar" :value="__('Avatar')" />
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            @if($profileUser->photoUrl)
                <div class="mt-2 flex items-center gap-3">
                    <img src="{{ $profileUser->photoUrl }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border" />
                    <span class="text-sm text-gray-600">{{ __('Current avatar') }}</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $profileUser->phone)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="country" :value="__('Country')" />
                <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $profileUser->country)" autocomplete="country" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="language" :value="__('Language')" />
                <select id="language" name="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Select language') }}</option>
                    @foreach($languages as $code => $label)
                        <option value="{{ $code }}" {{ old('language', $profileUser->language) == $code ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('language')" />
            </div>

            <div>
                <x-input-label for="currency_id" :value="__('Currency')" />
                <select id="currency_id" name="currency_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Select currency') }}</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ old('currency_id', $profileUser->currency_id) == $currency->id ? 'selected' : '' }}>
                            {{ $currency->code ?? $currency->id }} @if(isset($currency->symbol)) - {{ $currency->symbol }} @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('currency_id')" />
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
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
