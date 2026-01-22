<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="globalStore()" x-init="initTheme()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'School Management System') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional Styles -->
        <style>
            .page-transition {
                opacity: 0;
                transform: translateY(10px);
                animation: pageFadeIn 0.3s ease-out forwards;
            }
            
            @keyframes pageFadeIn {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
            }
            
            .gradient-bg-dark {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .glass-card-dark {
                background: rgba(30, 41, 59, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            /* Dark mode transition */
            .dark-mode-transition * {
                transition: background-color 0.3s ease, border-color 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 dark-mode-transition">
        <div class="min-h-screen flex flex-col">
            <!-- Enhanced Navigation -->
            @include('layouts.navigation')

            <!-- Page Header with Gradient -->
            @if(isset($header))
                <header class="relative overflow-hidden">
                    <!-- Animated background -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 via-purple-600/10 to-pink-600/10 dark:from-blue-900/20 dark:via-purple-900/20 dark:to-pink-900/20"></div>
                    <!-- Animated dots -->
                    <div class="absolute inset-0 opacity-30">
                        <div class="absolute top-10 left-10 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <div class="absolute top-20 right-20 w-3 h-3 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                        <div class="absolute bottom-10 left-20 w-2 h-2 bg-pink-500 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                    </div>
                    
                    <div class="relative max-w-8xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        <div class="page-transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                        {{ $header }}
                                    </h1>
                                    @if(isset($subheader))
                                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                                            {{ $subheader }}
                                        </p>
                                    @endif
                                </div>
                                
                                @if(isset($headerActions))
                                    <div class="flex items-center space-x-3">
                                        {{ $headerActions }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Breadcrumb -->
                            @if(isset($breadcrumbs))
                                <div class="mt-4">
                                    <nav class="flex" aria-label="Breadcrumb">
                                        <ol class="flex items-center space-x-2">
                                            {{ $breadcrumbs }}
                                        </ol>
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </header>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 page-transition">
                <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <!-- Content wrapper with subtle animation -->
                    <div class="relative">
                        <!-- Floating background elements -->
                        <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
                        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
                        
                        <!-- Main content -->
                        <div class="relative">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>

            <!-- Enhanced Footer -->
            <footer class="mt-auto bg-gradient-to-r from-blue-600/90 to-blue-800/90 dark:from-gray-900 dark:to-gray-800 border-t border-blue-500/20 dark:border-gray-700">
                <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <!-- Brand -->
                        <div class="flex items-center space-x-3 mb-4 md:mb-0">
                            <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" transform="translate(0 7)" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.3" transform="translate(0 14)" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-white font-bold text-lg tracking-tight">School</span>
                                <span class="text-blue-200 font-semibold text-lg tracking-tight">Management System</span>
                            </div>
                        </div>

                        <!-- Links -->
                        <div class="flex items-center space-x-6">
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-200 text-sm">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Support</span>
                                </span>
                            </a>
                            <a href="#" class="text-blue-200 hover:text-white transition-colors duration-200 text-sm">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Settings</span>
                                </span>
                            </a>
                            <div class="text-blue-200 text-sm">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>v{{ config('app.version', '1.0.0') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Copyright -->
                    <div class="mt-6 pt-6 border-t border-blue-500/30 text-center">
                        <p class="text-blue-200 text-sm">
                            © {{ date('Y') }} School Management System. All rights reserved.
                        </p>
                        <p class="text-blue-300/70 text-xs mt-1">
                            Designed with ❤️ for education
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Floating Action Button (Optional) -->
        @auth
            @if(Auth::user()->isTeacher() || Auth::user()->isAdmin())
                <div class="fixed bottom-6 right-6 z-40">
                    <div class="relative group">
                        <button class="h-14 w-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        
                        <!-- Quick Actions Tooltip -->
                        <div class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-2">
                            <div class="space-y-1">
                                @if(Auth::user()->isTeacher())
                                    <a href="{{ route('teacher.attendance.create') }}" 
                                       class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md text-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Mark Attendance</span>
                                    </a>
                                    <a href="{{ route('teacher.grades.create') }}" 
                                       class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md text-sm">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Add Grade</span>
                                    </a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.students.create') }}" 
                                       class="flex items-center space-x-2 px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-md text-sm">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                        <span>Add Student</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Global Alpine Store and Theme Management -->
        <script>
            function globalStore() {
                return {
                    // Theme state
                    darkMode: false,
                    
                    // Initialize theme
                    initTheme() {
                        // Check localStorage first
                        const stored = localStorage.getItem('darkMode');
                        
                        if (stored !== null) {
                            this.darkMode = stored === 'true';
                        } else {
                            // Check system preference if no stored preference
                            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                            this.darkMode = prefersDark;
                            localStorage.setItem('darkMode', prefersDark);
                        }
                        
                        // Apply theme
                        this.applyTheme();
                        
                        // Watch for changes
                        this.$watch('darkMode', (value) => {
                            localStorage.setItem('darkMode', value);
                            this.applyTheme();
                            
                            // Dispatch event for other components
                            window.dispatchEvent(new CustomEvent('themeChanged', {
                                detail: { darkMode: value }
                            }));
                        });
                        
                        // Listen for theme changes from other tabs/windows
                        window.addEventListener('storage', (e) => {
                            if (e.key === 'darkMode') {
                                this.darkMode = e.newValue === 'true';
                            }
                        });
                    },
                    
                    // Apply theme to DOM
                    applyTheme() {
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    },
                    
                    // Toggle theme
                    toggleTheme() {
                        this.darkMode = !this.darkMode;
                    },
                    
                    // Get current theme status
                    isDarkMode() {
                        return this.darkMode;
                    }
                }
            }

            // Add blob animation classes
            if (!document.querySelector('style#animations')) {
                const style = document.createElement('style');
                style.id = 'animations';
                style.textContent = `
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
                `;
                document.head.appendChild(style);
            }
        </script>
    </body>
</html>