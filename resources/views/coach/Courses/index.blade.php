<!DOCTYPE html>
<html lang="en" class="scroll-smooth" @auth
class="{{ (session('dark_mode', auth()->user()->dark_mode)) ? 'dark' : '' }}" @endauth>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Overview - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-coachNav />


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                Your Courses
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Manage and track your course portfolio
            </p>
        </div>

        <!-- Create New Course Button -->
        <div class="mb-8">
            <a href="{{ route('coach.courses.add') }}" id="create-course-btn"
                class="inline-flex items-center px-6 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-dark-text-primary font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Course
            </a>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <!-- Course Card -->
                <div
                    class="course-card bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle">
                    <!-- Course Thumbnail -->
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative overflow-hidden">
                        <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=400&h=200&fit=crop' }}"
                            alt="{{ $course->title }}" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4">
                            <span
                                class="status-badge px-2 py-1 {{ $course->status === 'published' ? 'bg-green-500' : 'bg-yellow-500' }} text-dark-text-primary text-xs font-medium rounded-full">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">
                            {{ $course->title }}
                        </h3>

                        <!-- Course Stats -->
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="flex items-center space-x-4 text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    {{ $course->enrollments_count ?? 0 }} students
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $course->lessons_count ?? 0 }} lessons
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <span
                                    class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">{{ number_format($course->rating ?? 0, 1) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('coach.courses.edit', $course) }}"
                                class="edit-btn flex-1 px-3 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary text-sm font-medium rounded-lg transition-colors text-center">
                                Edit
                            </a>
                            <a href="{{ route('coach.courses.show', $course) }}"
                                class="view-btn flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary text-sm font-medium rounded-lg transition-colors text-center">
                                View
                            </a>
                            <button type="button"
                                onclick="showDeleteModal('{{ route('coach.courses.destroy', $course) }}', '{{ $course->title }}')"
                                class="delete-btn px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- No Courses Message -->
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-dark-text-secondary" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-light-text-primary dark:text-dark-text-primary">No courses</h3>
                    <p class="mt-1 text-sm text-light-text-muted dark:text-dark-text-muted">Get started by creating your
                        first course.</p>
                    <div class="mt-6">
                        <a href="{{ route('coach.courses.add') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-dark-text-primary bg-light-accent-secondary hover:bg-light-accent-secondary/90">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Course
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="flex items-center space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($courses->onFirstPage())
                        <span
                            class="px-3 py-2 text-sm font-medium text-light-text-muted bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default rounded-l-md cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $courses->previousPageUrl() }}"
                            class="px-3 py-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default rounded-l-md hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                            Previous
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                        @if ($page == $courses->currentPage())
                            <span
                                class="px-3 py-2 text-sm font-medium text-dark-text-primary bg-light-accent-secondary border border-indigo-600">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($courses->hasMorePages())
                        <a href="{{ $courses->nextPageUrl() }}"
                            class="px-3 py-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default rounded-r-md hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                            Next
                        </a>
                    @else
                        <span
                            class="px-3 py-2 text-sm font-medium text-light-text-muted bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default rounded-r-md cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-dark-bg-primary opacity-75"></div>
                </div>

                <!-- Modal content -->
                <div
                    class="inline-block align-bottom bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3
                                    class="text-lg leading-6 font-medium text-light-text-primary dark:text-dark-text-primary">
                                    Delete Course</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Are you sure you
                                        want to delete this course? This action cannot be undone. All sections, videos,
                                        and cloud storage files will be permanently removed, and enrolled students will
                                        lose access.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                        <button type="button" id="cancel-delete-btn"
                            class="px-4 py-2 border border-light-border-default dark:border-dark-border-default rounded-md shadow-sm text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <form id="delete-form" method="POST" action="" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-dark-text-primary text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                Delete Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-ai_chat />
    </main>

    <script>
        // Toggle dark mode
        function toggleDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        }

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Delete modal functions
        function showDeleteModal(deleteUrl, courseTitle) {
            const modal = document.getElementById('delete-modal');
            const backdrop = document.querySelector('#delete-modal .fixed.inset-0');
            const content = document.querySelector('#delete-modal .inline-block');
            const form = document.getElementById('delete-form');

            form.action = deleteUrl;
            modal.classList.remove('hidden');

            // Set initial state
            backdrop.style.opacity = '0';
            content.style.opacity = '0';
            content.style.transform = 'translateY(16px) scale(0.95)';

            // Trigger animations
            setTimeout(() => {
                backdrop.style.transition = 'opacity 300ms ease-out';
                content.style.transition = 'opacity 300ms ease-out, transform 300ms ease-out';

                backdrop.style.opacity = '1';
                content.style.opacity = '1';
                content.style.transform = 'translateY(0) scale(1)';
            }, 10);
        }

        function hideDeleteModal() {
            const modal = document.getElementById('delete-modal');
            const backdrop = document.querySelector('#delete-modal .fixed.inset-0');
            const content = document.querySelector('#delete-modal .inline-block');

            // Animate out
            backdrop.style.transition = 'opacity 300ms ease-in';
            content.style.transition = 'opacity 300ms ease-in, transform 300ms ease-in';

            backdrop.style.opacity = '0';
            content.style.opacity = '0';
            content.style.transform = 'translateY(16px) scale(0.95)';

            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Modal handling
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('delete-modal');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

            // Cancel delete button
            cancelDeleteBtn.addEventListener('click', hideDeleteModal);

            // Close modal when clicking outside
            deleteModal.addEventListener('click', function (e) {
                if (e.target === this) {
                    hideDeleteModal();
                }
            });

            // Add animation to course cards
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fade-in-up');
            });
        });
    </script>
</body>

</html>