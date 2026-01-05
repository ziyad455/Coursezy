<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary font-sans transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-studentNav/>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Course Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Course Header -->
<div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-8">
    <div class="mb-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
            💻 {{ $course->category->name ?? 'Programming' }}
        </span>
    </div>
    <h1 class="text-3xl font-bold text-light-text-primary dark:text-dark-text-primary mb-4 leading-tight">
        {{ $course->title }}
    </h1>
    <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary mb-6">
        {{ Str::limit($course->description, 200) }}
    </p>
    
    <!-- Instructor Info -->
    <div class="flex items-center space-x-4 mb-4">
        <x-profile-photo :user="$course->coach" size="lg" />
        <div>
            <p class="font-semibold text-light-text-primary dark:text-dark-text-primary">{{ $course->coach->name }}</p>
            <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ $course->coach->bio ?? 'Expert Instructor' }}</p>
        </div>
    </div>

    <!-- Contact Coach Button -->
    <a href="/messages?user={{ $course->coach->id }}" 
       class="inline-block px-5 py-2 mt-2 text-sm font-medium text-dark-text-primary bg-light-accent-secondary rounded-lg shadow hover:bg-light-accent-secondary/90 focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
        Contact the Coach
    </a>
</div>


                <!-- Course Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $course->average_rating > 0 ? number_format($course->average_rating, 1) : 'N/A' }}</div>
                        <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Rating</div>
                        @if($course->average_rating > 0)
                            <div class="flex justify-center mt-1">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($course->average_rating))
                                            ★
                                        @elseif($i - 0.5 <= $course->average_rating)
                                            <span class="relative inline-block">
                                                <span class="text-gray-300 dark:text-gray-600">★</span>
                                                <span class="absolute inset-0 overflow-hidden" style="width: 50%">★</span>
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-light-text-muted mt-1">({{ $course->ratings->count() }} {{ Str::plural('review', $course->ratings->count()) }})</div>
                        @else
                            <div class="text-xs text-gray-500 dark:text-light-text-muted mt-1">No reviews yet</div>
                        @endif
                    </div>
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ number_format($course->total_students) }}</div>
                        <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ Str::plural('Student', $course->total_students) }}</div>
                    </div>
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $course->formatted_duration ?: 'N/A' }}</div>
                        <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Duration</div>
                    </div>
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $course->level }}</div>
                        <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Level</div>
                    </div>
                </div>

                <!-- Course Description -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-8">
                    <h2 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-6">Course Description</h2>
                    <div class="prose dark:prose-invert text-light-text-secondary dark:text-dark-text-secondary max-w-none">
                        <p class="leading-relaxed">
                            {{ $course->description }}
                        </p>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-8">
                    <h2 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-6">Course Curriculum</h2>
                    <div class="space-y-3">
                        @forelse ($sections as $section)
                            <div class="bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-5 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <div class="flex items-center justify-center bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-lg w-10 h-10 flex-shrink-0">
                                            <span class="font-medium">{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-medium text-light-text-primary dark:text-dark-text-primary mb-1">{{ $section->title }}</h3>
                                            @if($section->description)
                                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ $section->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium bg-indigo-50 dark:bg-indigo-900/50 px-3 py-1 rounded-full whitespace-nowrap ml-4">
                                        {{ $section->lessons->count() }} {{ Str::plural('lesson', $section->lessons->count()) }}
                                    </span>
                                </div>
                                
                                <!-- Display lessons if available -->
                                @if($section->lessons->isNotEmpty())
                                    <div class="ml-14 space-y-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        @foreach($section->lessons as $lesson)
                                            <div class="flex items-center justify-between text-sm py-2 px-3 rounded hover:bg-white dark:hover:bg-gray-600/50 transition-colors">
                                                <div class="flex items-center space-x-3">
                                                    @if($lesson->type === 'video')
                                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @endif
                                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">{{ $lesson->title }}</span>
                                                    @if($lesson->is_preview)
                                                        <span class="text-xs bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 px-2 py-0.5 rounded">Preview</span>
                                                    @endif
                                                </div>
                                                @if($lesson->duration)
                                                    <span class="text-xs text-light-text-muted dark:text-dark-text-muted">{{ $lesson->duration }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-light-text-muted dark:text-dark-text-muted">No course content available yet.</p>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1">The instructor is working on adding content to this course.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Instructor Details -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-8">
                    <h2 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-6">Meet Your Instructor</h2>
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <div class="ring-4 ring-indigo-100 dark:ring-indigo-900/50 rounded-full shadow-md">
                            <x-profile-photo :user="$course->coach" size="2xl" />
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-light-text-primary dark:text-dark-text-primary">{{ $course->coach->name }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-400 mb-4">{{ $course->coach->bio ?? 'Expert Instructor' }}</p>
                            <p class="text-light-text-secondary dark:text-dark-text-secondary mb-6 leading-relaxed">
                                {{ $course->coach->about_you ?? 'Experienced instructor passionate about sharing knowledge and helping students succeed in their learning journey.' }}
                            </p>
                            <div class="flex flex-wrap gap-3">
                                @if($coachAverageRating > 0)
                                <div class="flex items-center space-x-2 bg-yellow-50 dark:bg-yellow-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-yellow-500">⭐</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ number_format($coachAverageRating, 1) }}</span>
                                    <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Rating</span>
                                </div>
                                @endif
                                <div class="flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-blue-500">👥</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ number_format($coachTotalStudents) }}{{ $coachTotalStudents > 0 ? '+' : '' }}</span>
                                    <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ Str::plural('Student', $coachTotalStudents) }}</span>
                                </div>
                                <div class="flex items-center space-x-2 bg-green-50 dark:bg-green-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-green-500">📚</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->coach->coursesTaught->count() }}</span>
                                    <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ Str::plural('Course', $course->coach->coursesTaught->count()) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar - Course Purchase -->
            <div class="lg:col-span-1">
                <div class="top-8 space-y-6">
                    <!-- Course Preview Card -->
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default overflow-hidden">
                        <!-- Course Preview -->
                        <div class="aspect-video bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                            <img 
                                src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"  
                                alt="Course Thumbnail" 
                                class="w-full h-full object-cover transition-transform duration-500 ease-in-out hover:scale-110"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        </div>

                        <div class="p-6">
                            <!-- Price -->
                            <div class="mb-6">
                                <div class="flex items-baseline space-x-2 mb-2">
                                    <span class="text-3xl font-bold text-light-text-primary dark:text-dark-text-primary">${{ number_format($course->price, 2) }}</span>
                                    @if($course->price < 100)
                                    <span class="text-lg text-light-text-muted dark:text-dark-text-muted line-through">${{ number_format($course->price * 2, 2) }}</span>
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-medium rounded">50% OFF</span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 text-green-600 dark:text-green-400 text-sm">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>30-day money-back guarantee</span>
                                </div>
                            </div>

                            <!-- Enroll Button -->
                            <a href="/payment/{{ $course->id }}" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-dark-text-primary font-semibold py-3 px-4  rounded-lg mb-6 transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                🚀 Enroll Now
                            </a>

                            <!-- Course Includes -->
                            <div class="space-y-4 mb-6 mt-5">
                                <h3 class="font-semibold text-light-text-primary dark:text-dark-text-primary text-lg">This course includes:</h3>
                                <ul class="space-y-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                    @if($course->formatted_duration && $course->formatted_duration != '0h')
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $course->formatted_duration }} of video content</span>
                                    </li>
                                    @endif
                                    @if($course->total_published_lessons > 0)
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $course->total_published_lessons }} {{ Str::plural('lesson', $course->total_published_lessons) }}</span>
                                    </li>
                                    @endif
                                    @if($course->total_exercises > 0)
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $course->total_exercises }} coding {{ Str::plural('exercise', $course->total_exercises) }}</span>
                                    </li>
                                    @endif
                                    @if($course->total_projects > 0)
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $course->total_projects }} real-world {{ Str::plural('project', $course->total_projects) }}</span>
                                    </li>
                                    @endif
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Certificate of completion</span>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Lifetime access</span>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Access on mobile and desktop</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Course Details -->
                            <div class="border-t border-light-border-default dark:border-dark-border-default pt-4 space-y-3 text-sm">
                                @if($course->formatted_duration && $course->formatted_duration != '0h')
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Duration:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->formatted_duration }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Level:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->level }}</span>
                                </div>
                                @if($course->total_published_lessons > 0)
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Total Lessons:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->total_published_lessons }}</span>
                                </div>
                                @endif
                                @if($sections->count() > 0)
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Sections:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $sections->count() }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Language:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">English</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Last updated:</span>
                                    <span class="font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->updated_at->format('M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Certificate:</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">✓ Yes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-light-text-secondary dark:text-dark-text-secondary">Access:</span>
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">Lifetime</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Course -->
                    <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default p-6">
                        <h3 class="font-semibold text-light-text-primary dark:text-dark-text-primary mb-3">Share this course</h3>
                        <div class="flex space-x-3">
                            <button class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 flex items-center justify-center hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Courses -->
        @if($relatedCourses->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-8">More courses by {{ $course->coach->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedCourses as $relatedCourse)
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-sm border border-light-border-default dark:border-dark-border-default overflow-hidden hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                        <img src="{{ $relatedCourse->thumbnail ? asset('storage/' . $relatedCourse->thumbnail) : 'https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $relatedCourse->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-light-bg-secondary dark:bg-dark-bg-secondary bg-opacity-90 dark:bg-opacity-90 text-light-text-primary dark:text-dark-text-primary text-xs font-medium rounded">
                                {{ $relatedCourse->category->name ?? 'Programming' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-light-text-primary dark:text-dark-text-primary mb-2 line-clamp-2">{{ $relatedCourse->title }}</h3>
                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary mb-3 line-clamp-2">
                            {{ Str::limit($relatedCourse->description, 80) }}
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <x-profile-photo :user="$relatedCourse->coach" size="xs" />
                                <span class="text-light-text-secondary dark:text-dark-text-secondary text-sm">{{ $relatedCourse->coach->name }}</span>
                            </div>
                            @if($relatedCourse->ratings->avg('rating'))
                            <div class="flex items-center space-x-1">
                                <span class="text-yellow-400 text-sm">★</span>
                                <span class="text-light-text-muted dark:text-dark-text-muted text-sm">{{ number_format($relatedCourse->ratings->avg('rating'), 1) }}</span>
                            </div>
                            @else
                            <div class="flex items-center space-x-1">
                                <span class="text-gray-400 text-sm">★</span>
                                <span class="text-light-text-muted dark:text-dark-text-muted text-sm">New</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($relatedCourse->price, 2) }}
                            </span>
                            <a href="{{ route('cours.details', $relatedCourse->id) }}" class="px-4 py-2 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary text-sm font-medium rounded-lg transition-colors">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-light-bg-secondary dark:bg-dark-bg-secondary border-t border-light-border-default dark:border-dark-border-default mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">Coursezy</h2>
                <p class="text-light-text-secondary dark:text-dark-text-secondary mb-8 max-w-2xl mx-auto">
                    Empowering learners worldwide with quality education and practical skills for the modern world.
                </p>
                <div class="flex justify-center space-x-6 text-light-text-muted dark:text-dark-text-muted mb-8">
                    <a href="#" class="hover:text-gray-700 dark:hover:text-dark-text-secondary transition-colors">About</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-dark-text-secondary transition-colors">Courses</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-dark-text-secondary transition-colors">Instructors</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-dark-text-secondary transition-colors">Contact</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-dark-text-secondary transition-colors">Privacy</a>
                </div>
                <p class="text-gray-400 dark:text-light-text-muted text-sm">© 2025 Coursezy. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>