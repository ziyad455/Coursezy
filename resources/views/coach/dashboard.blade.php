<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coach Dashboard - Coursezy</title>
    <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <x-coachNav/>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                Welcome back, Coach!
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Here's how you're doing this month
            </p>
        </div>

        <!-- Stats Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
            <!-- Total Courses Card -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">Total Courses</p>
                    <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary" id="totalCourses">{{ $totalCourses }}</p>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">Total Students</p>
                    <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary" id="totalStudents">{{ number_format($totalStudents) }}</p>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary" id="totalRevenue">${{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Average Rating Card -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">Avg. Rating</p>
                    <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary" id="avgRating">{{ $avgRating }}</p>
                </div>
            </div>

            <!-- Course Clicks Card -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">Course Clicks (30d)</p>
                    <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary" id="courseClicks">{{ number_format($courseClicks) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary">Recent Activity</h2>
                <span id="newActivityBadge" class="hidden px-2 py-1 bg-red-500 text-dark-text-primary text-xs rounded-full animate-pulse">New</span>
            </div>
            <div class="space-y-4" id="activityList">
                @forelse($recentActivities as $activity)
                    <div class="activity-item flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ !$activity->is_read ? 'border-l-4 border-indigo-500' : '' }}" data-activity-id="{{ $activity->id }}">
                        <div class="p-2 bg-{{ $activity->color }}-100 dark:bg-{{ $activity->color }}-900/30 rounded-full">
                            @if($activity->type == 'enrollment')
                                <svg class="w-4 h-4 text-{{ $activity->color }}-600 dark:text-{{ $activity->color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @elseif($activity->type == 'review')
                                <svg class="w-4 h-4 text-{{ $activity->color }}-600 dark:text-{{ $activity->color }}-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @elseif($activity->type == 'payment')
                                <svg class="w-4 h-4 text-{{ $activity->color }}-600 dark:text-{{ $activity->color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-{{ $activity->color }}-600 dark:text-{{ $activity->color }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">{{ $activity->description }}</p>
                            @if($activity->amount)
                                <p class="text-xs text-light-text-muted dark:text-dark-text-muted">${{ number_format($activity->amount, 2) }}</p>
                            @endif
                            @if($activity->rating)
                                <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Rating: {{ $activity->rating }} stars</p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 dark:text-light-text-muted">{{ $activity->time_ago }}</span>
                    </div>
                @empty
                    <div class="text-center py-8 text-light-text-muted dark:text-dark-text-muted">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-light-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Create New Course -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-xl p-6 text-dark-text-primary">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Create New Course</h3>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-indigo-100 text-sm mb-4">Start building your next course and reach more students</p>
                <button class="px-4 py-2 bg-light-bg-secondary/20 hover:bg-light-bg-secondary/30 text-dark-text-primary rounded-lg text-sm font-medium transition-colors">
                    Get Started
                </button>
            </div>

            <!-- Manage Students -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-xl p-6 text-dark-text-primary">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Manage Students</h3>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <p class="text-green-100 text-sm mb-4">View enrollments, track progress, and communicate with students</p>
                <button class="px-4 py-2 bg-light-bg-secondary/20 hover:bg-light-bg-secondary/30 text-dark-text-primary rounded-lg text-sm font-medium transition-colors">
                    View Students
                </button>
            </div>

            <!-- Analytics -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-xl p-6 text-dark-text-primary">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">View Analytics</h3>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <p class="text-purple-100 text-sm mb-4">Deep dive into your course performance and revenue metrics</p>
                <button class="px-4 py-2 bg-light-bg-secondary/20 hover:bg-light-bg-secondary/30 text-dark-text-primary rounded-lg text-sm font-medium transition-colors">
                    View Reports
                </button>
            </div>
        </div>

    </main>
                <x-ai_chat />

    <!-- Scripts -->
    <script>
        // Dark mode toggle functionality
        let isDarkMode = {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'true' : 'false' }};
        
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

        // Initialize dark mode based on session setting
        function initDarkMode() {
            // Dark mode is already set by the server-side template
            // Just sync the JavaScript state with the current HTML class
            isDarkMode = document.documentElement.classList.contains('dark');
        }

        // Add scroll animations for cards
        function initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 100);
                    }
                });
            }, observerOptions);

            // Apply to cards
            document.querySelectorAll('.transform').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            initScrollAnimations();
            initPusher();
        });

        // Initialize Pusher for real-time updates
        function initPusher() {
            // Initialize Pusher
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });

            // Subscribe to coach channel
            const channel = pusher.subscribe('private-coach.{{ Auth::id() }}');

            // Listen for new activities
            channel.bind('App\\Events\\NewActivity', function(data) {
                console.log('New activity:', data);
                
                // Show notification badge
                const badge = document.getElementById('newActivityBadge');
                if (badge) {
                    badge.classList.remove('hidden');
                    setTimeout(() => badge.classList.add('hidden'), 5000);
                }

                // Add new activity to the list
                addActivityToList(data);

                // Update statistics if needed
                updateStatistics(data);

                // Play notification sound (optional)
                playNotificationSound();
            });
        }

        // Add new activity to the activity list
        function addActivityToList(activity) {
            const activityList = document.getElementById('activityList');
            if (!activityList) return;

            // Remove "No recent activity" message if present
            const emptyMessage = activityList.querySelector('.text-center');
            if (emptyMessage) {
                emptyMessage.remove();
            }

            // Create activity HTML
            const activityHtml = createActivityHtml(activity);
            
            // Add to the beginning of the list
            activityList.insertAdjacentHTML('afterbegin', activityHtml);

            // Limit to 10 items
            const items = activityList.querySelectorAll('.activity-item');
            if (items.length > 10) {
                items[items.length - 1].remove();
            }
        }

        // Create HTML for activity item
        function createActivityHtml(activity) {
            const iconHtml = getActivityIcon(activity.type, activity.color);
            const amountHtml = activity.amount ? `<p class="text-xs text-light-text-muted dark:text-dark-text-muted">$${parseFloat(activity.amount).toFixed(2)}</p>` : '';
            const ratingHtml = activity.rating ? `<p class="text-xs text-light-text-muted dark:text-dark-text-muted">Rating: ${activity.rating} stars</p>` : '';
            
            return `
                <div class="activity-item flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-4 border-indigo-500 animate-fade-in-up" data-activity-id="${activity.id}">
                    <div class="p-2 bg-${activity.color}-100 dark:bg-${activity.color}-900/30 rounded-full">
                        ${iconHtml}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">${activity.description}</p>
                        ${amountHtml}
                        ${ratingHtml}
                    </div>
                    <span class="text-xs text-gray-400 dark:text-light-text-muted">${activity.time_ago || 'just now'}</span>
                </div>
            `;
        }

        // Get activity icon based on type
        function getActivityIcon(type, color) {
            const icons = {
                'enrollment': `<svg class="w-4 h-4 text-${color}-600 dark:text-${color}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>`,
                'review': `<svg class="w-4 h-4 text-${color}-600 dark:text-${color}-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>`,
                'payment': `<svg class="w-4 h-4 text-${color}-600 dark:text-${color}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`,
                'default': `<svg class="w-4 h-4 text-${color}-600 dark:text-${color}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>`
            };
            return icons[type] || icons.default;
        }

        // Update statistics based on activity type
        function updateStatistics(activity) {
            // Update total students if enrollment
            if (activity.type === 'enrollment') {
                const studentsEl = document.getElementById('totalStudents');
                if (studentsEl) {
                    const current = parseInt(studentsEl.textContent.replace(/,/g, ''));
                    studentsEl.textContent = (current + 1).toLocaleString();
                    animateValue(studentsEl);
                }
            }
            
            // Update revenue if payment
            if (activity.type === 'payment' && activity.amount) {
                const revenueEl = document.getElementById('totalRevenue');
                if (revenueEl) {
                    const current = parseFloat(revenueEl.textContent.replace(/[$,]/g, ''));
                    const newValue = current + parseFloat(activity.amount);
                    revenueEl.textContent = '$' + newValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    animateValue(revenueEl);
                }
            }
            
            // Update clicks if course_click
            if (activity.type === 'course_click') {
                const clicksEl = document.getElementById('courseClicks');
                if (clicksEl) {
                    const current = parseInt(clicksEl.textContent.replace(/,/g, ''));
                    clicksEl.textContent = (current + 1).toLocaleString();
                    animateValue(clicksEl);
                }
            }
        }

        // Animate value change
        function animateValue(element) {
            element.classList.add('animate-pulse');
            element.style.color = '#10b981'; // Green color
            setTimeout(() => {
                element.classList.remove('animate-pulse');
                element.style.color = '';
            }, 2000);
        }

        // Play notification sound
        function playNotificationSound() {
            // Create audio element if it doesn't exist
            let audio = document.getElementById('notificationSound');
            if (!audio) {
                audio = new Audio('/sounds/chatify/new-message-sound.mp3');
                audio.id = 'notificationSound';
                audio.volume = 0.3;
            }
            audio.play().catch(e => console.log('Could not play sound:', e));
        }

        // Add ripple effect to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</body>
</html>