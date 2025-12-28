<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Name') }}</label>
            <input id="name" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4" style="margin-top: 16px;">
            <label for="email" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Email') }}</label>
            <input id="email" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" style="margin-top: 16px;">
            <label for="password" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Password') }}</label>
            <input id="password" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4" style="margin-top: 16px;">
            <label for="password_confirmation" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #374151;">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4" style="display: flex; align-items: center; justify-content: flex-end; margin-top: 16px;">
            <a class="auth-link underline text-sm text-gray-600" style="font-size: 14px; color: #4b5563; text-decoration: underline;" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="auth-btn ms-4" style="margin-left: 16px; display: inline-flex; align-items: center; padding: 10px 20px; background-color: #1f2937; border: none; border-radius: 6px; font-weight: 600; font-size: 12px; color: white; text-transform: uppercase; cursor: pointer;">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
