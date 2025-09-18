@php
      $current = request()->route()->getName() ?? request()->path();
      $user =  Auth::user();
    @endphp

    <!-- Navigation Bar -->
    <nav id="navbar" class="bg-transparent dark:bg-transparent border-b border-transparent dark:border-transparent transition-all duration-300 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Coursezy
                    </h1>
                    <span class="ml-3 px-2 py-1 text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full">
                        Coach
                    </span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('coach.dashboard') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition-colors
                       {{ $current == 'coach.dashboard' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('coach.courses.index') }}"
                        class="px-3 py-2 text-sm font-medium rounded-lg transition-colors
                        {{ in_array($current, ['coach.courses.index', 'coach.courses.add']) ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                         Courses Overview
                    </a>
                    <a href="/accont"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition-colors
                       {{ $current == 'coach.profile' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Profile
                    </a>
            <a href="/messages"
            class="px-3 py-2 rounded-md text-sm font-medium
                {{ request()->routeIs('chatify', 'user','ai') 
                    ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' 
                    : 'text-gray-900 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white transition-colors' }}">
                Inbox
            </a>
                </div>

                <!-- Mobile menu button & Dark mode toggle -->
                <div class="flex items-center space-x-4">
                    <button onclick="toggleDarkMode()" class="p-2 rounded-lg bg-gray-100/80 dark:bg-gray-700/80 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 transition-colors backdrop-blur-sm">
                        <svg class="w-5 h-5 text-gray-900 dark:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path class="dark:hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            <path class="hidden dark:block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                    </button>
                    <!-- Profile Photo with Dropdown -->
                    <x-profile-photo :user="auth()->user()" size="md" :dropdown="true" :showName="true" />

                    <!-- Mobile menu button -->
                    <button class="md:hidden p-2 rounded-lg hover:bg-gray-100/80 dark:hover:bg-gray-700/80 transition-colors backdrop-blur-sm" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6 text-gray-900 dark:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200/50 dark:border-gray-700/50 py-4">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('coach.dashboard') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.dashboard' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('coach.courses.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.courses' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Courses Overview
                    </a>
                    <a href="/accont"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.profile' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Profile
                    </a>
                            <a href="/messages"
                            class="px-3 py-2 rounded-md text-sm font-medium
                                {{ request()->routeIs('chatify', 'user','ai') 
                                    ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' 
                                    : 'text-gray-900 dark:text-gray-100 hover:text-gray-900 dark:hover:text-white transition-colors' }}">
                                Inbox
                            </a>
                </div>
            </div>

        </div>
    </nav>

    <!-- JavaScript -->
    <script>
        // Dark mode toggle functionality
        let isDarkMode = false;
        
        function toggleDarkMode() {
            const html = document.documentElement;
            isDarkMode = !isDarkMode;
            
            if (isDarkMode) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Profile dropdown toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const arrow = document.getElementById('dropdown-arrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                    arrow.classList.add('rotate-180');
                }, 10);
            } else {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                arrow.classList.remove('rotate-180');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }

        // Navbar scroll effect
        function handleNavbarScroll() {
            const navbar = document.getElementById('navbar');
            const scrollY = window.scrollY;
            
            if (scrollY > 20) {
                // Scrolled down - solid background with shadow
                navbar.className = navbar.className
                    .replace('bg-transparent dark:bg-transparent', 'bg-white/95 dark:bg-gray-800/95 backdrop-blur-md')
                    .replace('border-transparent dark:border-transparent', 'border-gray-200/50 dark:border-gray-700/50')
                    .replace('transition-all duration-300', 'transition-all duration-300 shadow-lg');
            } else {
                // At top - transparent
                navbar.className = navbar.className
                    .replace('bg-white/95 dark:bg-gray-800/95 backdrop-blur-md', 'bg-transparent dark:bg-transparent')
                    .replace('border-gray-200/50 dark:border-gray-700/50', 'border-transparent dark:border-transparent')
                    .replace('transition-all duration-300 shadow-lg', 'transition-all duration-300');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const profileButton = event.target.closest('[onclick="toggleProfileDropdown()"]');
            
            if (!profileButton && !dropdown.contains(event.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    toggleProfileDropdown();
                }
            }
        });

        // Initialize dark mode based on system preference
        function initDarkMode() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                isDarkMode = true;
                document.documentElement.classList.add('dark');
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            
            // Add scroll event listener for navbar effect
            window.addEventListener('scroll', handleNavbarScroll);
            
            // Initial call to set navbar state
            handleNavbarScroll();
        });
    </script>
</body>
</html>