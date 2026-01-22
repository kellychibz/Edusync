<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 to-blue-800 dark:from-gray-900 dark:to-gray-800 shadow-lg relative z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <!-- School Logo/Icon -->
                        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" transform="translate(0 7)" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.3" transform="translate(0 14)" />
                            </svg>
                        </div>
                        <div class="hidden md:block">
                            <span class="text-white font-bold text-lg tracking-tight">School</span>
                            <span class="text-blue-200 font-semibold text-lg tracking-tight">Management</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ml-10 space-x-1">
                    <!-- Dashboard for all users -->
                    <x-nav-link :href="route('dashboard')" 
                                :active="request()->routeIs('dashboard')" 
                                class="nav-item-gradient">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="text-blue-100">{{ __('Dashboard') }}</span>
                        </div>
                    </x-nav-link>

                    @auth
                        <!-- Admin Navigation -->
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Panel Dropdown -->
                            <div class="relative" x-data="{ adminOpen: false }">
                                <button @click="adminOpen = !adminOpen" 
                                        @click.outside="adminOpen = false"
                                        class="nav-item-gradient flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-blue-100 font-medium">Admin Panel</span>
                                    </div>
                                    <svg class="w-4 h-4 text-blue-100 transition-transform duration-200" 
                                         :class="{ 'rotate-180': adminOpen }" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Admin Dropdown Menu -->
                                <div x-show="adminOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden"
                                     style="display: none;">
                                    <!-- Dropdown Header -->
                                    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-600 border-b">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Administration</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-300">Manage school system</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="py-2 max-h-96 overflow-y-auto">
                                        <!-- Quick Stats -->
                                        <div class="px-4 py-2 border-b">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="text-center p-2 bg-blue-50 dark:bg-gray-700 rounded-lg">
                                                    <p class="text-xs text-gray-600 dark:text-gray-300">Students</p>
                                                    <p class="font-bold text-blue-600 dark:text-blue-300">{{ \App\Models\Student::count() }}</p>
                                                </div>
                                                <div class="text-center p-2 bg-green-50 dark:bg-gray-700 rounded-lg">
                                                    <p class="text-xs text-gray-600 dark:text-gray-300">Teachers</p>
                                                    <p class="font-bold text-green-600 dark:text-green-300">{{ \App\Models\Teacher::count() }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Admin Links -->
                                        <div class="space-y-1 p-2">
                                            <!-- Core Management -->
                                            <div class="px-2 pt-2">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Core Management</p>
                                            </div>
                                            <a href="{{ route('admin.dashboard') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                        </svg>
                                                    </div>
                                                    <span>Admin Dashboard</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.students.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                        </svg>
                                                    </div>
                                                    <span>Students</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.teachers.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </div>
                                                    <span>Teachers</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.class-groups.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.class-groups.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                        </svg>
                                                    </div>
                                                    <span>Class Groups</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.streams.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.streams.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                        </svg>
                                                    </div>
                                                    <span>Streams</span>
                                                </div>
                                            </a>

                                            <!-- Academic Management -->
                                            <div class="px-2 pt-4">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Academic</p>
                                            </div>
                                            <a href="{{ route('admin.class-allocations.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.class-allocations.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                    </div>
                                                    <span>Class Allocations</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.attendance.index') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.attendance.*') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <span>Attendance</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.attendance.reports') }}" 
                                               class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150 border-l-4 border-transparent hover:border-blue-500 {{ request()->routeIs('admin.attendance.reports') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-8 w-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <span>Reports</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- Teacher Navigation -->
                        @elseif(Auth::user()->isTeacher())
                            <x-nav-link :href="route('teacher.attendance.index')" 
                                        :active="request()->routeIs('teacher.attendance.*')" 
                                        class="nav-item-gradient">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-blue-100">{{ __('Attendance') }}</span>
                                </div>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('teacher.grades.index')" 
                                        :active="request()->routeIs('teacher.grades.*')" 
                                        class="nav-item-gradient">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-blue-100">{{ __('Grades') }}</span>
                                </div>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('teacher.assessments')" 
                                        :active="request()->routeIs('teacher.assessments.*')" 
                                        class="nav-item-gradient">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-blue-100">{{ __('Assessments') }}</span>
                                </div>
                            </x-nav-link>
                            
                            @if(Route::has('teacher.class-groups.index'))
                                <x-nav-link :href="route('teacher.class-groups.index')" 
                                            :active="request()->routeIs('teacher.class-groups.*')" 
                                            class="nav-item-gradient">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span class="text-blue-100">{{ __('My Classes') }}</span>
                                </div>
                                </x-nav-link>
                            @endif

                        <!-- Student Navigation -->
                        @elseif(Auth::user()->isStudent())
                            <x-nav-link :href="route('student.dashboard')" 
                                        :active="request()->routeIs('student.dashboard')" 
                                        class="nav-item-gradient">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-blue-100">{{ __('My Profile') }}</span>
                                </div>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('student.grades')" 
                                        :active="request()->routeIs('student.grades.*')" 
                                        class="nav-item-gradient">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-blue-100">{{ __('My Grades') }}</span>
                                </div>
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side - User Dropdown & Dark Mode Toggle -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-3">
                <!-- Dark Mode Toggle (Simplified - uses parent Alpine store) -->
                <button @click="toggleTheme()" 
                        class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200">
                    <!-- Sun icon (for dark mode - click to go to light) -->
                    <svg x-show="darkMode" class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon (for light mode - click to go to dark) -->
                    <svg x-show="!darkMode" class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Notifications (Optional) -->
                <button class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200 relative">
                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-3 px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all duration-200 group">
                            <!-- User Avatar -->
                            <div class="relative">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <!-- Online Status Indicator -->
                                <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-green-500 rounded-full border-2 border-blue-600"></div>
                            </div>
                            
                            <!-- User Info -->
                            <div class="text-left">
                                <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-blue-200 capitalize flex items-center space-x-1">
                                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">
                                        {{ Auth::user()->role }}
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Dropdown Icon -->
                            <svg class="w-5 h-5 text-blue-200 group-hover:rotate-180 transition-transform duration-200" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Profile Section -->
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->email }}</p>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ ucfirst(Auth::user()->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="menu-item">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span>Profile Settings</span>
                                </div>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('dashboard')" class="menu-item">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <span>Dashboard</span>
                                </div>
                            </x-dropdown-link>

                            <!-- Dark Mode Toggle in Dropdown (Simplified) -->
                            <button @click="toggleTheme()" 
                                    class="w-full menu-item">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg x-show="darkMode" class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <svg x-show="!darkMode" class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                        </svg>
                                    </div>
                                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                                </div>
                            </button>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="menu-item text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </div>
                                            <span>Log Out</span>
                                        </div>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center sm:hidden space-x-2">
                <!-- Mobile Dark Mode Toggle (Simplified) -->
                <button @click="toggleTheme()" 
                        class="p-2 rounded-lg bg-white/10 text-blue-100">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                
                <button @click="open = ! open" 
                        class="inline-flex items-center justify-center p-3 rounded-lg text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" 
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" 
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="sm:hidden bg-gradient-to-b from-blue-700 to-blue-800 dark:from-gray-900 dark:to-gray-800 shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Mobile Dashboard Link -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>{{ __('Dashboard') }}</span>
            </a>

            @auth
                <!-- Admin Mobile Navigation -->
                @if(Auth::user()->isAdmin())
                    <div class="px-4 pt-3">
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-white">Admin Panel</span>
                        </div>
                        
                        <div class="space-y-1 ml-3 border-l-2 border-blue-400 pl-3">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                                Admin Dashboard
                            </a>
                            <a href="{{ route('admin.students.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.students.*') ? 'bg-white/10 text-white' : '' }}">
                                Students
                            </a>
                            <a href="{{ route('admin.teachers.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.teachers.*') ? 'bg-white/10 text-white' : '' }}">
                                Teachers
                            </a>
                            <a href="{{ route('admin.class-groups.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.class-groups.*') ? 'bg-white/10 text-white' : '' }}">
                                Class Groups
                            </a>
                            <a href="{{ route('admin.streams.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.streams.*') ? 'bg-white/10 text-white' : '' }}">
                                Streams
                            </a>
                            <a href="{{ route('admin.class-allocations.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.class-allocations.*') ? 'bg-white/10 text-white' : '' }}">
                                Class Allocations
                            </a>
                            <a href="{{ route('admin.attendance.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.attendance.*') ? 'bg-white/10 text-white' : '' }}">
                                Attendance
                            </a>
                            <a href="{{ route('admin.attendance.reports') }}" 
                               class="flex items-center px-4 py-2 text-sm text-blue-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out {{ request()->routeIs('admin.attendance.reports') ? 'bg-white/10 text-white' : '' }}">
                                Reports
                            </a>
                        </div>
                    </div>

                <!-- Teacher Mobile Navigation -->
                @elseif(Auth::user()->isTeacher())
                    <a href="{{ route('teacher.attendance.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('teacher.attendance.*') ? 'bg-white/20 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('Attendance') }}</span>
                    </a>
                    
                    <a href="{{ route('teacher.grades.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('teacher.grades.*') ? 'bg-white/20 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>{{ __('Grades') }}</span>
                    </a>
                    
                    <a href="{{ route('teacher.assessments') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('teacher.assessments.*') ? 'bg-white/20 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>{{ __('Assessments') }}</span>
                    </a>
                    
                    @if(Route::has('teacher.class-groups.index'))
                        <a href="{{ route('teacher.class-groups.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('teacher.class-groups.*') ? 'bg-white/20 text-white' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>{{ __('My Classes') }}</span>
                        </a>
                    @endif

                <!-- Student Mobile Navigation -->
                @elseif(Auth::user()->isStudent())
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ __('My Profile') }}</span>
                    </a>
                    
                    <a href="{{ route('student.grades') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out {{ request()->routeIs('student.grades.*') ? 'bg-white/20 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>{{ __('My Grades') }}</span>
                    </a>
                @endif
            @endauth
        </div>

        <!-- Mobile User Profile -->
        <div class="pt-4 pb-3 border-t border-blue-500/30">
            <div class="px-4">
                <div class="flex items-center space-x-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-blue-200 capitalize">
                            <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center space-x-3 px-4 py-3 text-base font-medium text-red-300 hover:text-red-200 hover:bg-white/10 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Log Out') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Add custom styles -->
<style>
    .nav-item-gradient {
        @apply px-4 py-2 rounded-lg text-white hover:bg-white/20 transition-all duration-200 mx-1;
    }
    
    .nav-item-gradient.active {
        @apply bg-white/30 backdrop-blur-sm shadow-lg;
    }
    
    .menu-item {
        @apply flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150;
    }
</style>