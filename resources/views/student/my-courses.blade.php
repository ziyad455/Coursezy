<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Courses - Coursezy</title>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .star-rating {
            display: flex;
            gap: 0.25rem;
        }
        .star {
            cursor: pointer;
            color: #d1d5db;
            transition: all 0.2s;
            font-size: 1.5rem;
        }
        .star:hover,
        .star.active {
            color: #fbbf24;
            transform: scale(1.1);
        }
        .star.filled {
            color: #fbbf24;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation -->
    <x-studentNav/>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                My Courses
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Access your purchased courses and track your learning progress
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md p-6 border border-light-border-subtle dark:border-dark-border-subtle">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Total Courses</p>
                        <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $enrollments->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md p-6 border border-light-border-subtle dark:border-dark-border-subtle">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">In Progress</p>
                        <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $enrollments->count() }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md p-6 border border-light-border-subtle dark:border-dark-border-subtle">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Completed</p>
                        <p class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">0</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($enrollments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($enrollments as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $userRating = $userRatings[$course->id] ?? 0;
                    @endphp
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-light-border-subtle dark:border-dark-border-subtle animate-fade-in-up">
                        <!-- Course Thumbnail -->
                        <div class="relative h-48 bg-gradient-to-br from-indigo-500 to-purple-600">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                     alt="{{ $course->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-16 h-16 text-dark-text-primary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Progress Badge -->
                            <div class="absolute top-2 right-2 px-3 py-1 bg-black/50 backdrop-blur-sm rounded-full text-xs text-dark-text-primary">
                                In Progress
                            </div>
                        </div>

                        <!-- Course Content -->
                        <div class="p-5">
                            <!-- Title & Category -->
                            <div class="mb-3">
                                <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary mb-1 line-clamp-2">
                                    {{ $course->title }}
                                </h3>
                                @if($course->category)
                                    <span class="inline-block px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs rounded-full">
                                        {{ $course->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Instructor -->
                            <div class="flex items-center mb-4">
                                @if($course->coach)
                                    <x-profile-photo :user="$course->coach" size="sm" />
                                    <span class="ml-2 text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                        {{ $course->coach->name }}
                                    </span>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 mr-2"></div>
                                    <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Unknown</span>
                                @endif
                            </div>

                            <!-- Rating Section -->
                            <div class="border-t border-light-border-default dark:border-dark-border-default pt-4">
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary mb-2">Rate this course:</p>
                                <div class="star-rating" data-course-id="{{ $course->id }}" data-current-rating="{{ $userRating }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $userRating ? 'filled' : '' }}" data-rating="{{ $i }}">★</span>
                                    @endfor
                                </div>
                                <p class="text-xs text-light-text-muted dark:text-dark-text-muted mt-2" id="rating-message-{{ $course->id }}">
                                    @if($userRating > 0)
                                        You rated this {{ $userRating }} star{{ $userRating > 1 ? 's' : '' }}
                                    @else
                                        Click to rate
                                    @endif
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('student.learn.course', $course->id) }}" 
                                   class="flex-1 px-4 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary text-sm font-medium rounded-lg text-center transition-colors">
                                    Continue Learning
                                </a>
                                <button onclick="showReviewModal({{ $course->id }}, '{{ $course->title }}')" 
                                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Purchase Date -->
                            <p class="text-xs text-light-text-muted dark:text-dark-text-muted mt-3">
                                Purchased: {{ $enrollment->purchased_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $enrollments->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-light-text-secondary mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">No courses yet</h3>
                <p class="text-light-text-secondary dark:text-dark-text-secondary mb-6">Start your learning journey by enrolling in a course</p>
                <a href="{{ route('student.dashboard') }}" 
                   class="inline-block px-6 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary font-medium rounded-lg transition-colors">
                    Browse Courses
                </a>
            </div>
        @endif
    </main>

    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeReviewModal()"></div>
            <div class="relative bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl max-w-md w-full p-6 shadow-xl">
                <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-4">Write a Review</h3>
                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary mb-4" id="reviewCourseTitle"></p>
                
                <form id="reviewForm">
                    <input type="hidden" id="reviewCourseId" name="course_id">
                    
                    <!-- Star Rating in Modal -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Your Rating</label>
                        <div class="star-rating modal-rating" id="modalRating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-rating="{{ $i }}">★</span>
                            @endfor
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-4">
                        <label for="reviewText" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            Your Review (optional)
                        </label>
                        <textarea id="reviewText" name="review" rows="4" 
                                  class="w-full px-3 py-2 border border-light-border-default dark:border-dark-border-default rounded-lg bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent"
                                  placeholder="Share your experience with this course..."></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary font-medium rounded-lg transition-colors">
                            Submit Review
                        </button>
                        <button type="button" onclick="closeReviewModal()" 
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-light-text-secondary dark:text-dark-text-secondary font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
                <x-ai_chat />

    <!-- Success Toast -->
    <div id="successToast" class="hidden fixed bottom-4 right-4 bg-green-500 text-dark-text-primary px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-full">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span id="toastMessage">Rating saved successfully!</span>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Star rating functionality
        document.querySelectorAll('.star-rating:not(.modal-rating)').forEach(rating => {
            const stars = rating.querySelectorAll('.star');
            const courseId = rating.dataset.courseId;
            const currentRating = parseInt(rating.dataset.currentRating) || 0;

            stars.forEach((star, index) => {
                star.addEventListener('click', async () => {
                    const ratingValue = index + 1;
                    
                    // Update visual state
                    stars.forEach((s, i) => {
                        s.classList.toggle('filled', i < ratingValue);
                    });

                    // Submit rating
                    await submitRating(courseId, ratingValue);
                });

                star.addEventListener('mouseenter', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('active', i <= index);
                    });
                });
            });

            rating.addEventListener('mouseleave', () => {
                stars.forEach(s => s.classList.remove('active'));
            });
        });

        // Modal star rating
        let modalRatingValue = 0;
        document.querySelectorAll('#modalRating .star').forEach((star, index) => {
            star.addEventListener('click', () => {
                modalRatingValue = index + 1;
                updateModalStars(modalRatingValue);
            });

            star.addEventListener('mouseenter', () => {
                document.querySelectorAll('#modalRating .star').forEach((s, i) => {
                    s.classList.toggle('active', i <= index);
                });
            });
        });

        document.getElementById('modalRating').addEventListener('mouseleave', () => {
            document.querySelectorAll('#modalRating .star').forEach(s => s.classList.remove('active'));
        });

        function updateModalStars(rating) {
            document.querySelectorAll('#modalRating .star').forEach((star, index) => {
                star.classList.toggle('filled', index < rating);
            });
        }

        // Submit rating via AJAX
        async function submitRating(courseId, rating, review = null) {
            try {
                const response = await fetch('{{ route("student.rate.course") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        rating: rating,
                        review: review
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    const messageEl = document.getElementById(`rating-message-${courseId}`);
                    if (messageEl) {
                        messageEl.textContent = `You rated this ${rating} star${rating > 1 ? 's' : ''}`;
                    }
                    
                    // Update star display
                    const ratingEl = document.querySelector(`.star-rating[data-course-id="${courseId}"]`);
                    if (ratingEl) {
                        ratingEl.dataset.currentRating = rating;
                    }
                    
                    showToast(review ? 'Review submitted successfully!' : 'Rating saved successfully!');
                }
            } catch (error) {
                console.error('Error submitting rating:', error);
                showToast('Error saving rating. Please try again.', 'error');
            }
        }

        // Review modal functions
        function showReviewModal(courseId, courseTitle) {
            document.getElementById('reviewCourseId').value = courseId;
            document.getElementById('reviewCourseTitle').textContent = courseTitle;
            document.getElementById('reviewModal').classList.remove('hidden');
            
            // Reset modal
            modalRatingValue = 0;
            updateModalStars(0);
            document.getElementById('reviewText').value = '';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        // Review form submission
        document.getElementById('reviewForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const courseId = document.getElementById('reviewCourseId').value;
            const review = document.getElementById('reviewText').value;
            
            if (modalRatingValue === 0) {
                showToast('Please select a rating', 'error');
                return;
            }

            await submitRating(courseId, modalRatingValue, review);
            closeReviewModal();
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('successToast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            
            if (type === 'error') {
                toast.classList.add('bg-red-500');
                toast.classList.remove('bg-green-500');
            } else {
                toast.classList.add('bg-green-500');
                toast.classList.remove('bg-red-500');
            }
            
            toast.classList.remove('hidden', 'translate-y-full');
            
            setTimeout(() => {
                toast.classList.add('translate-y-full');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
