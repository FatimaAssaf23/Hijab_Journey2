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
                <input id="date_of_birth" type="date" name="date_of_birth" class="auth-input block mt-1 w-full" required 
                       min="{{ date('Y-m-d', strtotime('-12 years')) }}" 
                       max="{{ date('Y-m-d', strtotime('-8 years')) }}" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                <small style="display: block; margin-top: 4px; font-size: 12px; color: #6b7280;">Age must be between 8 and 12 years</small>
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

                                <!-- Password Strength Checker UI -->
                                <div style="margin-top: 6px; background: #fff; border-radius: 7px; box-shadow: 0 1px 4px rgba(44,62,80,0.06); padding: 5px 8px; max-width: 220px; display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                    <div style="font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 1px;">Strength</div>
                                    <div style="height: 6px; background: #eee; border-radius: 3px; overflow: hidden; width: 100%; margin-bottom: 2px;">
                                        <div id="strengthFill" style="height: 100%; width: 10%; background: #e63946; transition: width 0.3s, background 0.3s;"></div>
                                    </div>
                                    <div id="strengthText" style="font-size: 11px; color: #374151; font-weight: 500; margin-bottom: 1px;">Very Weak</div>
                                    <div style="display: flex; gap: 6px; width: 100%; justify-content: space-between;">
                                        <span id="lengthIcon" style="font-size: 14px;">❌</span>
                                        <span id="uppercaseIcon" style="font-size: 14px;">❌</span>
                                        <span id="numberIcon" style="font-size: 14px;">❌</span>
                                        <span id="symbolIcon" style="font-size: 14px;">❌</span>
                                    </div>
                                    <div style="display: flex; gap: 6px; width: 100%; justify-content: space-between; font-size: 10px; color: #6b7280;">
                                        <span id="lengthText" class="requirement-not-met">8+ chars</span>
                                        <span id="uppercaseText" class="requirement-not-met">Upper</span>
                                        <span id="numberText" class="requirement-not-met">Num</span>
                                        <span id="symbolText" class="requirement-not-met">Sym</span>
                                    </div>
                                </div>
                                <style>
                                      .requirement-met { color: #2a9d8f !important; font-weight: 600; }
                                      .requirement-not-met { color: #e63946 !important; font-weight: 500; }
                                </style>
                                <script>
                                // ===== Password strength checker =====
                                const passwordInput = document.getElementById('password');
                                const strengthFill = document.getElementById('strengthFill');
                                const strengthText = document.getElementById('strengthText');
                                const lengthIcon = document.getElementById('lengthIcon');
                                const lengthText = document.getElementById('lengthText');
                                const uppercaseIcon = document.getElementById('uppercaseIcon');
                                const uppercaseText = document.getElementById('uppercaseText');
                                const numberIcon = document.getElementById('numberIcon');
                                const numberText = document.getElementById('numberText');
                                const symbolIcon = document.getElementById('symbolIcon');
                                const symbolText = document.getElementById('symbolText');

                                passwordInput.addEventListener('input', checkPasswordStrength);

                                function checkPasswordStrength() {
                                    const password = passwordInput.value;
                                    // ===== Password requirements =====
                                      const hasMinLength = password.length >= 8;
                                      const hasUppercase = /[A-Z]/.test(password);
                                      const hasNumber = /[0-9]/.test(password);
                                      const hasSymbol = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                                      // ===== Update requirement indicators =====
                                      updateRequirement(hasMinLength, lengthIcon, lengthText, "8+ chars");
                                      updateRequirement(hasUppercase, uppercaseIcon, uppercaseText, "Uppercase");
                                      updateRequirement(hasNumber, numberIcon, numberText, "Number");
                                      updateRequirement(hasSymbol, symbolIcon, symbolText, "Symbol");
                                    // ===== Strength score =====
                                    let score = 0;
                                    if (hasMinLength) score++;
                                    if (hasUppercase) score++;
                                    if (hasNumber) score++;
                                    if (hasSymbol) score++;
                                    updateStrengthIndicator(score);
                                }

                                // ===== Requirement color + icon =====
                                function updateRequirement(isValid, icon, text, message) {
                                    if (isValid) {
                                        icon.textContent = "✔";
                                        icon.style.color = "#2a9d8f";
                                        text.textContent = message;
                                        text.className = "requirement-met";
                                    } else {
                                        icon.textContent = "✖";
                                        icon.style.color = "#e63946";
                                        text.textContent = message;
                                        text.className = "requirement-not-met";
                                    }
                                }

                                // ===== Strength bar colors =====
                                function updateStrengthIndicator(score) {
                                    let strength = "";
                                    let color = "";
                                    let width = "";
                                    switch (score) {
                                        case 0:
                                            strength = "Very Weak";
                                            color = "#e63946";
                                            width = "10%";
                                            break;
                                        case 1:
                                            strength = "Weak";
                                            color = "#f4a261";
                                            width = "25%";
                                            break;
                                        case 2:
                                            strength = "Fair";
                                            color = "#f4a261";
                                            width = "50%";
                                            break;
                                        case 3:
                                            strength = "Good";
                                            color = "#2a9d8f";
                                            width = "75%";
                                            break;
                                        case 4:
                                            strength = "Strong";
                                            color = "#2a9d8f";
                                            width = "100%";
                                            break;
                                    }
                                    strengthFill.style.width = width;
                                    strengthFill.style.backgroundColor = color;
                                    strengthText.textContent = strength;
                                }
                                </script>
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
