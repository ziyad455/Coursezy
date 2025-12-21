<nav class="top-0 z-50 bg-light-bg-secondary/90 dark:bg-dark-bg-primary/90 backdrop-blur-md border-b border-light-border-default dark:border-dark-border-default transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Coursezy
                    </h1>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                      <a href="{{ route('student.dashboard') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('student.dashboard') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-light-text-secondary dark:text-dark-text-secondary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                        Browse Courses
                      </a>

                      <a href="{{ route('my.courses') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('my.courses') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-light-text-secondary dark:text-dark-text-secondary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                        My Courses
                      </a>

                            <a href="/messages"
                            class="px-3 py-2 rounded-md text-sm font-medium
                                {{ request()->routeIs('messages', 'user','ai') 
                                    ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' 
                                    : 'text-light-text-secondary dark:text-dark-text-secondary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                                Inbox
                            </a>


                      <a href="/student/accont"
                        class="px-3 py-2 rounded-md text-sm font-medium
                            {{ request()->routeIs('student.accont') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-light-text-secondary dark:text-dark-text-secondary hover:text-gray-900 dark:hover:text-dark-text-primary transition-colors' }}">
                        Profile
                      </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:block flex-1 max-w-lg mx-8">
                    <form action="/search" method="GET" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input id="searchInput" name="q" type="text" placeholder="Search for courses..." 
                               value="{{ request('q') }}"
                               class="block w-full pl-10 pr-12 py-2 border border-light-border-default dark:border-dark-border-default rounded-full bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-primary dark:text-dark-text-primary placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent transition-colors duration-300">
                        <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-4 w-4 text-dark-text-secondary hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Right Side - User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Profile Photo with Dropdown -->
                    <x-profile-photo :user="auth()->user()" size="md" :dropdown="true" :showName="true" />
                </div>
            </div>
        </div>
    </nav>

    <!-- JavaScript -->
    <script>
        // Profile dropdown toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
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
    </script>
