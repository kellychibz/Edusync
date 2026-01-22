<x-guest-layout>
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 right-10 w-64 h-64 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-10 left-1/3 w-64 h-64 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo and Brand -->
        <div class="w-full max-w-md text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center">
                <!-- Animated Logo Container -->
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full opacity-20 pulse-ring"></div>
                    <div class="relative h-20 w-20 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-2xl">
                        <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" transform="translate(0 7)" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.3" transform="translate(0 14)" />
                        </svg>
                    </div>
                </div>
                
                <div class="text-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                        {{ config('app.name', 'School Management System') }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Create your account</p>
                </div>
            </a>
        </div>

        <!-- Register Card -->
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 dark:border-gray-700/50">
            <!-- Welcome Message -->
            <div class="mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Join Our School Community</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fill in your details to get started</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="font-medium text-red-600 dark:text-red-400">
                        {{ __('Whoops! Something went wrong.') }}
                    </div>
                    
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <x-input-label for="name" :value="__('Full Name')" class="!mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <x-text-input id="name" 
                                    class="block w-full pl-10 !py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Enter your full name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email Address')" class="!mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <x-text-input id="email" 
                                    class="block w-full pl-10 !py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="email"
                                    placeholder="Enter your email address" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <x-input-label for="password" :value="__('Password')" class="!mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password" 
                                    class="block w-full pl-10 !py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                    type="password"
                                    name="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Create a password (min. 8 characters)" />
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg id="eye-icon-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        • Minimum 8 characters<br>
                        • Include uppercase & lowercase letters<br>
                        • Include at least one number
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="!mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <x-text-input id="password_confirmation" 
                                    class="block w-full pl-10 !py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                    type="password"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Re-enter your password" />
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg id="eye-icon-confirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-1 mr-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            I agree to the 
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Terms of Service</a> 
                            and 
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ __('Create Account') }}
                    </div>
                </button>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="ml-1 font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                            {{ __('Sign in here') }}
                        </a>
                    </p>
                </div>
            </form>

            <!-- Role-Based Registration Info -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-3">{{ __('Register as:') }}</p>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ __('Admin') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('School Staff') }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-green-600 dark:text-green-400">{{ __('Teacher') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Educator') }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-purple-600 dark:text-purple-400">{{ __('Student') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Learner') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="w-full max-w-md mt-8 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. {{ __('All rights reserved.') }}
            </p>
            <div class="mt-2 flex items-center justify-center space-x-4 text-xs text-gray-500 dark:text-gray-500">
                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ __('Privacy Policy') }}</a>
                <span>•</span>
                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ __('Terms of Service') }}</a>
                <span>•</span>
                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ __('Help Center') }}</a>
            </div>
        </div>
    </div>

    <!-- Animations and Scripts -->
    <style>
        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.8; }
            70% { transform: scale(1.2); opacity: 0; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Password strength indicator
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('password-strength');
            
            if (!strengthIndicator) {
                const indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'mt-2 text-xs';
                this.parentNode.parentNode.appendChild(indicator);
            }
            
            let strength = 0;
            let message = '';
            let color = 'text-red-500';
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    message = 'Weak password';
                    color = 'text-red-500';
                    break;
                case 2:
                    message = 'Fair password';
                    color = 'text-yellow-500';
                    break;
                case 3:
                    message = 'Good password';
                    color = 'text-blue-500';
                    break;
                case 4:
                    message = 'Strong password';
                    color = 'text-green-500';
                    break;
            }
            
            document.getElementById('password-strength').innerHTML = `
                <span class="${color} font-medium">${message}</span>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                    <div class="h-1.5 rounded-full ${color.replace('text-', 'bg-')}" style="width: ${strength * 25}%"></div>
                </div>
            `;
        });
    </script>
</x-guest-layout>