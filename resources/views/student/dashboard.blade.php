<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Courses - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-studentNav />


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                Browse Courses
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Discover new skills and advance your career with our expert-led courses
            </p>
        </div>

        <!-- Category Filter Section -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary mb-4">Filter by
                Category</h2>
            <form method="GET" class="flex flex-wrap gap-3">
                @php
                    $categories = [
                        '' => 'All',
                        'programming' => 'Programming',
                        'design' => 'Design',
                        'marketing' => 'Marketing',
                        'data-science' => 'Data Science',
                        'business' => 'Business',
                        'music' => 'Music'
                    ];
                @endphp

                @foreach($categories as $value => $label)
                    @php
                        // Check if this button should be active
                        $isActive = ($value === '' && ($selectedCategory === '' || $selectedCategory === null))
                            || ($value !== '' && $selectedCategory === $value);
                    @endphp
                    <button type="submit" name="category" value="{{ $value }}" class="px-4 py-2 text-sm font-medium rounded-full border-2 transition-all duration-200 transform hover:scale-105
                            @if($isActive)
                                border-indigo-600 bg-light-accent-secondary text-dark-text-primary hover:bg-light-accent-secondary/90 hover:border-indigo-700 dark:border-indigo-500 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 dark:hover:border-indigo-600
                            @else
                                border-light-border-default dark:border-dark-border-default text-light-text-secondary dark:text-dark-text-secondary hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-light-bg-secondary dark:bg-dark-bg-secondary hover:bg-indigo-50 dark:hover:bg-indigo-900/20
                            @endif">
                        {{ $label }}
                    </button>
                @endforeach
            </form>
        </div>

        <!-- Course Grid Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($courses as $course)
                <!-- Course Card -->
                <div
                    class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle">
                    <div class="h-48 relative">
                        <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                            alt="{{ $course->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-3 py-1 glass text-dark-text-primary bg-opacity-70 bg-dark-bg-secondary text-xs font-semibold rounded-full">
                                {{ $course->category->name ?? 'General' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-light-text-primary dark:text-dark-text-primary mb-2">
                            {{ $course->title }}</h3>
                        <p class="text-light-text-secondary dark:text-dark-text-secondary text-sm mb-4 line-clamp-2">
                            {{ Str::limit($course->description, 100) }}
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <x-profile-photo :user="$course->coach" size="xs" />
                                <span
                                    class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ $course->coach->name }}</span>
                            </div>
                            <div class="flex items-center">
                                @php
                                    $rating = $course->ratings->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                @endphp
                                <div class="flex items-center text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            ★
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            ☆
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <span
                                    class="text-light-text-muted dark:text-dark-text-muted text-sm ml-1">{{ number_format($rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($course->price, 2) }}
                            </div>
                            <a href="/cours/{{ $course->id }}"
                                class="px-6 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-dark-text-primary text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    @if($selectedCategory)
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-light-text-muted dark:text-dark-text-muted mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <p class="text-light-text-muted dark:text-dark-text-muted text-lg mb-2">No courses found in
                                "{{ ucfirst(str_replace('-', ' ', $selectedCategory)) }}" category.</p>
                            <a href="{{ route('student.dashboard') }}"
                                class="mt-4 px-6 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-dark-text-primary text-sm font-medium rounded-lg transition-colors duration-200">
                                View All Courses
                            </a>
                        </div>
                    @else
                        <p class="text-light-text-muted dark:text-dark-text-muted">No courses available at the moment.</p>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
        <x-ai_chat />
        {{-- @if (session('success'))
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <!-- Modal Content -->
            <div class="bg-light-bg-secondary rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-light-border-default">
                    <div class="flex items-center justify-center">
                        <!-- Success Icon -->
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Modal Body -->
                <div class="px-6 py-6 text-center">
                    <h3 class="text-lg font-semibold text-light-text-primary mb-2">Payment Successful!</h3>
                    <p class="text-light-text-secondary mb-4">{{ session('success') }}</p>
                    <p class="text-sm text-light-text-muted">Your enrollment has been confirmed and you now have access
                        to the course.</p>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-light-border-default flex gap-3">
                    <button onclick="closeModal()"
                        class="flex-1 bg-green-600 text-dark-text-primary py-2 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                        Continue to Course
                    </button>
                    <button onclick="closeModal()"
                        class="flex-1 bg-gray-200 text-light-text-primary py-2 px-4 rounded-md hover:bg-gray-300 transition-colors font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <script>
            if (document.getElementById('modal')) {
                const modal = document.getElementById('modal');

                // Check if modal was already closed in this session
                if (sessionStorage.getItem('modalClosed')) {
                    modal.parentNode.removeChild(modal);
                    return;
                }

                function closeModal() {
                    // Set flag in sessionStorage so modal won't show again
                    sessionStorage.setItem('modalClosed', 'true');
                    modal.parentNode.removeChild(modal);
                }

                // Close modal when clicking backdrop
                modal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeModal();
                    }
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeModal();
                    }
                });
            }
        </script>
        @endif --}}





    </main>

    <!-- Footer -->
    <footer
        class="bg-light-bg-secondary dark:bg-dark-bg-secondary border-t border-light-border-default dark:border-dark-border-default transition-colors duration-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h2
                    class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4">
                    Coursezy
                </h2>
                <p class="text-light-text-secondary dark:text-dark-text-secondary mb-8">
                    Empowering learners worldwide with quality education
                </p>
                <div class="flex justify-center space-x-6 text-sm text-light-text-muted dark:text-dark-text-muted">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">About</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Courses</a>
                    <a href="#"
                        class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Instructors</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Contact</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy</a>
                </div>
                <div class="mt-8 pt-8 border-t border-light-border-default dark:border-dark-border-default">
                    <p class="text-gray-400 dark:text-light-text-muted text-sm">
                        © 2025 Coursezy. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>

        if (document.getElementById('modal')) {
            const modal = document.getElementById('modal');

            function closeModal() {
                modal.parentNode.removeChild(modal);
            }

            // Close modal when clicking backdrop
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        }
        // Dark mode toggle functionality


        // Add scroll animations for course cards
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

            // Apply to course cards
            document.querySelectorAll('.transform').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            initDarkMode();
            initScrollAnimations();
        });

        // Add ripple effect to buttons
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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