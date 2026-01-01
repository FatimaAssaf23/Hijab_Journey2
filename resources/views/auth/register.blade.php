<x-guest-layout>

    <form method="POST" action="{{ route('register') }}" style="max-width: 700px; margin: 0 auto; background: #FDEDEF; border-radius: 18px; padding: 40px 32px;">
        @csrf

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="first_name" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('First Name') }}</label>
                <input id="first_name" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div style="flex: 1;">
                <label for="last_name" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Last Name') }}</label>
                <input id="last_name" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>
        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="email" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Email') }}</label>
                <input id="email" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>



        <div style="display: flex; gap: 20px; margin-top: 16px;">
            <div style="flex: 1;">
                <label for="date_of_birth" class="auth-label">{{ __('Date of Birth') }}</label>
                <input id="date_of_birth" type="date" name="date_of_birth" class="auth-input block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>
            <div style="flex: 1;">
                <label for="phone_number" class="auth-label">{{ __('Phone Number') }}</label>
                <input id="phone_number" type="tel" name="phone_number" class="auth-input block mt-1 w-full" placeholder="e.g. 05XXXXXXXX" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>
        </div>


        <div style="display: flex; gap: 20px; margin-top: 16px;">
            <div style="flex: 1;">
                <label for="country" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Country') }}</label>
                <input id="country" type="text" name="country" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" required />
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>
            <div style="flex: 1;">
                <label for="language" class="auth-label">{{ __('Language') }}</label>
                <input id="language" type="text" name="language" class="auth-input block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('language')" class="mt-2" />
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-top: 16px;">
            <div style="flex: 1;">
                <label for="password" class="auth-label">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" class="auth-input block mt-1 w-full" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div style="flex: 1;">
                <label for="password_confirmation" class="auth-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="auth-input block mt-1 w-full" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4" style="display: flex; align-items: center; justify-content: flex-end; margin-top: 24px; gap: 16px;">
            <a class="auth-link underline text-sm text-gray-600" style="font-size: 15px; color: #4b5563; text-decoration: underline;" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button type="submit" class="auth-btn ms-4" style="margin-left: 0; display: inline-flex; align-items: center; padding: 12px 28px; background: linear-gradient(90deg, #F8C5C8 0%, #FC8EAC 50%, #EC769A 100%); border: none; border-radius: 8px; font-weight: 700; font-size: 15px; color: white; text-transform: uppercase; cursor: pointer; box-shadow: 0 2px 8px rgba(99,102,241,0.12); transition: background 0.2s;">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
