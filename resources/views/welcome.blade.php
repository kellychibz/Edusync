<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'School Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional Styles -->
        <style>
            .school-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            }
            
            .school-gradient-dark {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            }
            
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .glass-effect-dark {
                background: rgba(30, 41, 59, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .floating {
                animation: floating 3s ease-in-out infinite;
            }
            
            @keyframes floating {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            .pulse-ring {
                animation: pulse-ring 2s infinite;
            }
            
            @keyframes pulse-ring {
                0% { transform: scale(0.8); opacity: 0.8; }
                70% { transform: scale(1.2); opacity: 0; }
                100% { transform: scale(1.2); opacity: 0; }
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100">
        <!-- Animated Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-1/3 right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-1/3 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative min-h-screen flex flex-col items-center justify-center p-6 lg:p-8">
            <!-- Header -->
            <header class="w-full max-w-6xl mb-8 lg:mb-12">
                <nav class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" transform="translate(0 7)" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.3" transform="translate(0 14)" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                                {{ config('app.name', 'School Management System') }}
                            </span>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center space-x-4">
                        <a href="#features" class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hidden md:inline-block">
                            Features
                        </a>
                        <a href="#about" class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hidden md:inline-block">
                            About
                        </a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-5 py-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-sm font-medium">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col items-center justify-center w-full max-w-6xl">
                <div class="text-center mb-12 lg:mb-16">
                    <!-- Animated Badge -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 text-sm font-medium mb-6">
                        <span class="flex h-2 w-2 relative mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Modern Education Platform
                    </div>
                    
                    <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                        <span class="block">Streamline Your</span>
                        <span class="block bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                            School Management
                        </span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-8">
                        A comprehensive platform for administrators, teachers, students, and parents to manage academic activities, attendance, grades, and communications efficiently.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-medium text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                Start Free Trial
                            </a>
                        @endif
                        <a href="#demo" class="px-8 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-medium text-lg border border-gray-200 dark:border-gray-700 shadow hover:shadow-lg transition-all duration-300">
                            Watch Demo
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                        <div class="text-center p-4 glass-effect dark:glass-effect-dark rounded-xl">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">500+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Schools</div>
                        </div>
                        <div class="text-center p-4 glass-effect dark:glass-effect-dark rounded-xl">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">50K+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Students</div>
                        </div>
                        <div class="text-center p-4 glass-effect dark:glass-effect-dark rounded-xl">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">5K+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Teachers</div>
                        </div>
                        <div class="text-center p-4 glass-effect dark:glass-effect-dark rounded-xl">
                            <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">99%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="relative w-full max-w-5xl mb-16">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700">
                        <!-- Mock browser header -->
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 flex items-center space-x-2">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="flex-1 text-center text-sm text-gray-500 dark:text-gray-400">
                                school-management.test/dashboard
                            </div>
                        </div>
                        
                        <!-- Mock dashboard content -->
                        <div class="bg-white dark:bg-gray-900 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <!-- Stats cards -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-300">1,245</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Students</div>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-300">89</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Teachers</div>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-300">96.5%</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Attendance Rate</div>
                                </div>
                            </div>
                            
                            <!-- Mock chart -->
                            <div class="h-48 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl flex items-center justify-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span class="text-sm">Performance Analytics Dashboard</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating elements around dashboard -->
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-blue-500 rounded-full opacity-10 floating"></div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-purple-500 rounded-full opacity-10 floating" style="animation-delay: 1s;"></div>
                </div>

                <!-- Features Section -->
                <section id="features" class="w-full mb-16">
                    <h2 class="text-3xl font-bold text-center mb-12">Powerful Features</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Smart Attendance</h3>
                            <p class="text-gray-600 dark:text-gray-300">Track student and teacher attendance with automated reports and real-time notifications.</p>
                        </div>
                        
                        <!-- Feature 2 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Grade Management</h3>
                            <p class="text-gray-600 dark:text-gray-300">Comprehensive gradebook with automated calculations and customizable grading scales.</p>
                        </div>
                        
                        <!-- Feature 3 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Student Profiles</h3>
                            <p class="text-gray-600 dark:text-gray-300">Complete student profiles with academic history, behavior records, and parent contacts.</p>
                        </div>
                        
                        <!-- Feature 4 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Class Scheduling</h3>
                            <p class="text-gray-600 dark:text-gray-300">Automated class schedules with conflict detection and room allocation.</p>
                        </div>
                        
                        <!-- Feature 5 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Communication Hub</h3>
                            <p class="text-gray-600 dark:text-gray-300">Seamless communication between teachers, students, and parents via announcements and messaging.</p>
                        </div>
                        
                        <!-- Feature 6 -->
                        <div class="p-6 glass-effect dark:glass-effect-dark rounded-xl hover:transform hover:-translate-y-2 transition-all duration-300">
                            <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Detailed Reports</h3>
                            <p class="text-gray-600 dark:text-gray-300">Generate comprehensive reports on academics, attendance, and institutional performance.</p>
                        </div>
                    </div>
                </section>

                <!-- CTA Section -->
                <section class="w-full mb-16 text-center">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700 rounded-2xl p-8 lg:p-12">
                        <h2 class="text-3xl font-bold text-white mb-4">Ready to Transform Your School Management?</h2>
                        <p class="text-blue-100 mb-8 max-w-2xl mx-auto">
                            Join hundreds of educational institutions that have streamlined their operations with our platform.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-blue-600 hover:bg-blue-50 rounded-lg font-medium text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    Get Started Free
                                </a>
                            @endif
                            <a href="#contact" class="px-8 py-3 bg-transparent border-2 border-white text-white hover:bg-white/10 rounded-lg font-medium text-lg transition-all duration-300">
                                Schedule a Demo
                            </a>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="w-full max-w-6xl mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold">{{ config('app.name', 'School Management System') }}</span>
                    </div>

                    <!-- Links -->
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Privacy Policy
                        </a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Terms of Service
                        </a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Contact
                        </a>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        Designed for modern educational institutions
                    </p>
                </div>
            </footer>
        </div>

        <!-- Animations Script -->
        <script>
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