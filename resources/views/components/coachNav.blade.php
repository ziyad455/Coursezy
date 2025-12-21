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
                       {{ $current == 'coach.dashboard' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('coach.courses.index') }}"
                        class="px-3 py-2 text-sm font-medium rounded-lg transition-colors
                        {{ in_array($current, ['coach.courses.index', 'coach.courses.add']) ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                         Courses Overview
                    </a>
                    <a href="/accont"
                       class="px-3 py-2 text-sm font-medium rounded-lg transition-colors
                       {{ $current == 'coach.profile' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Profile
                    </a>
            <a href="/messages"
            class="px-3 py-2 rounded-md text-sm font-medium
                {{ request()->routeIs('chatify', 'user','ai') 
                    ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' 
                    : 'text-light-text-primary dark:text-dark-text-primary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                Inbox
            </a>
                </div>

                <!-- Profile and Mobile menu -->
                <div class="flex items-center space-x-4">
                    <!-- Profile Photo with Dropdown -->
                    <x-profile-photo :user="auth()->user()" size="md" :dropdown="true" :showName="true" />

                    <!-- Mobile menu button -->
                    <button class="md:hidden p-2 rounded-lg hover:bg-light-bg-tertiary/80 dark:hover:bg-gray-700/80 transition-colors backdrop-blur-sm" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6 text-light-text-primary dark:text-dark-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-light-border-default/50 dark:border-dark-border-default/50 py-4">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('coach.dashboard') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.dashboard' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('coach.courses.index') }}"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.courses' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Courses Overview
                    </a>
                    <a href="/accont"
                       class="px-3 py-2 text-sm font-medium rounded-lg
                       {{ $current == 'coach.profile' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-light-text-primary dark:text-dark-text-primary hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Profile
                    </a>
                            <a href="/messages"
                            class="px-3 py-2 rounded-md text-sm font-medium
                                {{ request()->routeIs('chatify', 'user','ai') 
                                    ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' 
                                    : 'text-light-text-primary dark:text-dark-text-primary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                                Inbox
                            </a>
                </div>
            </div>

        </div>
    </nav>

    <!-- JavaScript -->
    <script>
        // (Dark mode functionality removed - now managed in Settings page)

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
                    .replace('bg-transparent dark:bg-transparent', 'bg-light-bg-secondary/95 dark:bg-dark-bg-secondary/95 backdrop-blur-md')
                    .replace('border-transparent dark:border-transparent', 'border-light-border-default/50 dark:border-dark-border-default/50')
                    .replace('transition-all duration-300', 'transition-all duration-300 shadow-lg');
            } else {
                // At top - transparent
                navbar.className = navbar.className
                    .replace('bg-light-bg-secondary/95 dark:bg-dark-bg-secondary/95 backdrop-blur-md', 'bg-transparent dark:bg-transparent')
                    .replace('border-light-border-default/50 dark:border-dark-border-default/50', 'border-transparent dark:border-transparent')
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

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add scroll event listener for navbar effect
            window.addEventListener('scroll', handleNavbarScroll);
            
            // Initial call to set navbar state
            handleNavbarScroll();
        });
    </script>
</body>
</html>