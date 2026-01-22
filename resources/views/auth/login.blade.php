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
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Sign in to access your account</p>
                </div>
            </a>
        </div>

        <!-- Login Card -->
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 dark:border-gray-700/50">
            <!-- Welcome Message -->
            <div class="mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Welcome Back</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Enter your credentials to continue</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email Address')" class="!mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <x-text-input id="email" 
                                    class="block w-full pl-10 !py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
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
                                    autocomplete="current-password"
                                    placeholder="Enter your password" />
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <div class="relative">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="sr-only peer"
                                   name="remember">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 font-medium" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Sign In') }}
                    </div>
                </button>

                <!-- Demo Login Hint -->
                <div class="mt-6 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-300 text-center">
                        <strong>Demo Access:</strong> admin@school.com / password
                    </p>
                </div>

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Don\'t have an account?') }}
                            <a href="{{ route('register') }}" class="ml-1 font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                                {{ __('Create one now') }}
                            </a>
                        </p>
                    </div>
                @endif
            </form>

            <!-- Role-Based Login Info -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-3">{{ __('Sign in as:') }}</p>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ __('Admin') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Full Access') }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-green-600 dark:text-green-400">{{ __('Teacher') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Class Management') }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="text-xs font-medium text-purple-600 dark:text-purple-400">{{ __('Student') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('View Grades') }}</div>
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
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
    </script>
</x-guest-layout>