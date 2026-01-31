<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #EC769A;">{{ __('Email') }}</label>
            <input id="email" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1.5px solid #FC8EAC; border-radius: 8px; background: linear-gradient(135deg, #f8c5c8 0%, #fc8eac 50%, #ec769a 100%); color: #222;" type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" style="margin-top: 16px;">
            <label for="password" class="auth-label" style="display: block; font-size: 14px; font-weight: 500; color: #EC769A;">{{ __('Password') }}</label>
            <input id="password" class="auth-input block mt-1 w-full" style="display: block; width: 100%; margin-top: 4px; padding: 8px 12px; border: 1.5px solid #FC8EAC; border-radius: 8px; background: linear-gradient(135deg, #f8c5c8 0%, #fc8eac 50%, #ec769a 100%); color: #222;" type="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4" style="margin-top: 16px;">
            <label for="remember_me" class="inline-flex items-center" style="display: inline-flex; align-items: center;">
                <input id="remember_me" type="checkbox" style="border-radius: 4px; border: 1.5px solid #FC8EAC;" name="remember">
                <span class="ms-2 text-sm" style="margin-left: 8px; font-size: 14px; color: #EC769A;">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4" style="display: flex; align-items: center; justify-content: flex-end; margin-top: 16px;">
            @if (Route::has('password.request'))
                <a class="auth-link underline text-sm" style="font-size: 14px; color: #EC769A; text-decoration: underline;" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="auth-btn ms-3" style="margin-left: 12px; display: inline-flex; align-items: center; padding: 10px 20px; background: linear-gradient(90deg, #FC8EAC 0%, #EC769A 100%); border: none; border-radius: 8px; font-weight: 600; font-size: 12px; color: white; text-transform: uppercase; cursor: pointer; box-shadow: 0 2px 8px rgba(252, 142, 172, 0.15);">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>

<style>
    /* Enhanced button animation for login button */
    button.auth-btn {
        animation: slideUpFade 0.6s ease-out 0.5s backwards, pulse 2s ease-in-out infinite 1.5s;
    }
    
    button.auth-btn:hover {
        animation: none;
    }
    
    /* Placeholder color styling for email and password inputs */
    input#email::placeholder,
    input#password::placeholder {
        color: #AFEEEE !important; /* Light turquoise */
        opacity: 1 !important; /* Firefox */
    }
    
    input#email::-webkit-input-placeholder,
    input#password::-webkit-input-placeholder {
        color: #AFEEEE !important; /* Chrome, Safari, Edge */
        opacity: 1 !important;
    }
    
    input#email::-moz-placeholder,
    input#password::-moz-placeholder {
        color: #AFEEEE !important; /* Firefox */
        opacity: 1 !important;
    }
    
    input#email:-ms-input-placeholder,
    input#password:-ms-input-placeholder {
        color: #AFEEEE !important; /* Internet Explorer */
        opacity: 1 !important;
    }
    
    input#email:-moz-placeholder,
    input#password:-moz-placeholder {
        color: #AFEEEE !important; /* Older Firefox */
        opacity: 1 !important;
    }
</style>
