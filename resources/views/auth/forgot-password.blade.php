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
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Reset your password</p>
                </div>
            </a>
        </div>

        <!-- Reset Password Card -->
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 dark:border-gray-700/50">
            <!-- Welcome Message -->
            <div class="mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Reset Your Password</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">We'll send you a link to reset your password</p>
            </div>

            <!-- Instruction -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    {{ __('Forgot your password? No problem. Just enter your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg" :status="session('status')" />

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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
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

                <!-- Reset Button -->
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ __('Send Reset Link') }}
                    </div>
                </button>

                <!-- What to Expect -->
                <div class="mt-6 p-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('What happens next?') }}</h4>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            We'll send an email with a reset link
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Click the link in the email (valid for 60 minutes)
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create a new password for your account
                        </li>
                    </ul>
                </div>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to login') }}
                    </a>
                </div>
            </form>

            <!-- Need Help? -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Need help?') }}</p>
                    <a href="mailto:support@schoolmanagement.com" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 font-medium">
                        {{ __('Contact Support') }}
                    </a>
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
</x-guest-layout>