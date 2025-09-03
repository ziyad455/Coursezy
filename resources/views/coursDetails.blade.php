<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Coursezy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-studentNav/>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Course Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Course Header -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
    <div class="mb-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
            💻 {{ $course->category->name ?? 'Programming' }}
        </span>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
        {{ $course->title }}
    </h1>
    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
        {{ Str::limit($course->description, 200) }}
    </p>
    
    <!-- Instructor Info -->
    <div class="flex items-center space-x-4 mb-4">
        <img src="{{ $course->coach->profile_photo ? asset('storage/' . $course->coach->profile_photo) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face&auto=format' }}" 
             alt="{{ $course->coach->name }}" 
             class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm">
        <div>
            <p class="font-semibold text-gray-900 dark:text-white">{{ $course->coach->name }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $course->coach->bio ?? 'Expert Instructor' }}</p>
        </div>
    </div>

    <!-- Contact Coach Button -->
    <a href="/chatify/{{ $course->coach->id }}" 
       class="inline-block px-5 py-2 mt-2 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
        Contact the Coach
    </a>
</div>


                <!-- Course Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($course->ratings->avg('rating') ?? 4.8, 1) }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Rating</div>
                        <div class="flex justify-center mt-1">
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">({{ $course->ratings->count() }} reviews)</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->enrollments->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Students</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">32h+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Duration</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center transition-transform hover:scale-[1.02]">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->level ?? 'Beginner' }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Level</div>
                    </div>
                </div>

                <!-- Course Description -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Course Description</h2>
                    <div class="prose dark:prose-invert text-gray-700 dark:text-gray-300 max-w-none">
                        <p class="leading-relaxed">
                            {{ $course->description }}
                        </p>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Course Curriculum</h2>
                    <div class="space-y-3">
                        @forelse ($sections as $s)
                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-5 transition-colors">
                                <div class="flex items-start space-x-4">
                                    <div class="flex items-center justify-center bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-lg w-10 h-10 flex-shrink-0">
                                        <span class="font-medium">{{ $loop->iteration }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $s['title'] }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $s['description'] }}</p>
                                    </div>
                                </div>
                                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium bg-indigo-50 dark:bg-indigo-900/50 px-3 py-1 rounded-full">
                                    {{ $s['duration'] }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No sections available.</p>
                        @endforelse

                        <div class="border border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-5 text-center">
                            <p class="text-gray-600 dark:text-gray-400">+ 8 more sections with additional content</p>
                        </div>
                    </div>
                </div>

                <!-- Instructor Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Meet Your Instructor</h2>
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <img src="{{ $course->coach->profile_photo ? asset('storage/' . $course->coach->profile_photo) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face&auto=format' }}" 
                             alt="{{ $course->coach->name }}" 
                             class="w-24 h-24 rounded-full object-cover ring-4 ring-indigo-100 dark:ring-indigo-900/50 shadow-md">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $course->coach->name }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-400 mb-4">{{ $course->coach->bio ?? 'Expert Instructor' }}</p>
                            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                                {{ $course->coach->about_you ?? 'Experienced instructor passionate about sharing knowledge and helping students succeed in their learning journey.' }}
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center space-x-2 bg-yellow-50 dark:bg-yellow-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-yellow-500">⭐</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($course->coach->coursesTaught->avg('ratings.rating') ?? 4.9, 1) }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Rating</span>
                                </div>
                                <div class="flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-blue-500">👥</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $course->coach->coursesTaught->sum('enrollments_count') ?? 0 }}+</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Students</span>
                                </div>
                                <div class="flex items-center space-x-2 bg-green-50 dark:bg-green-900/30 px-4 py-2 rounded-lg">
                                    <span class="text-green-500">📚</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $course->coach->coursesTaught->count() }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Courses</span>
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
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                                    <span class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($course->price, 2) }}</span>
                                    @if($course->price < 100)
                                    <span class="text-lg text-gray-500 dark:text-gray-400 line-through">${{ number_format($course->price * 2, 2) }}</span>
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
                            <a href="/payment/{{ $course->id }}" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-3 px-4  rounded-lg mb-6 transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                🚀 Enroll Now
                            </a>

                            <!-- Course Includes -->
                            <div class="space-y-4 mb-6 mt-5">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-lg">This course includes:</h3>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>32+ hours of video content</span>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>12 coding exercises</span>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>5 real-world projects</span>
                                    </li>
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
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">32+ hours</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Level:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $course->level ?? 'Beginner' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Language:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">English</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Last updated:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $course->updated_at->format('M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Certificate:</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">✓ Yes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Access:</span>
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">Lifetime</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Course -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Share this course</h3>
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
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">More courses by {{ $course->coach->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedCourses as $relatedCourse)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                        <img src="{{ $relatedCourse->thumbnail ? asset('storage/' . $relatedCourse->thumbnail) : 'https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $relatedCourse->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 text-gray-800 dark:text-gray-200 text-xs font-medium rounded">
                                {{ $relatedCourse->category->name ?? 'Programming' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $relatedCourse->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ Str::limit($relatedCourse->description, 80) }}
                        </p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $relatedCourse->coach->profile_photo ? asset('storage/' . $relatedCourse->coach->profile_photo) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=24&h=24&fit=crop&crop=face&auto=format' }}" 
                                     alt="{{ $relatedCourse->coach->name }}" 
                                     class="w-6 h-6 rounded-full object-cover border border-white dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400 text-sm">{{ $relatedCourse->coach->name }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-yellow-400 text-sm">★</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">{{ number_format($relatedCourse->ratings->avg('rating') ?? 4.7, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($relatedCourse->price, 2) }}
                            </span>
                            <a href="{{ route('cours.details', $relatedCourse->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
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
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">Coursezy</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    Empowering learners worldwide with quality education and practical skills for the modern world.
                </p>
                <div class="flex justify-center space-x-6 text-gray-500 dark:text-gray-400 mb-8">
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">About</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Courses</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Instructors</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Contact</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Privacy</a>
                </div>
                <p class="text-gray-400 dark:text-gray-500 text-sm">© 2025 Coursezy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Dark mode toggle functionality
        let isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        function toggleDarkMode() {
            isDarkMode = !isDarkMode;
            localStorage.setItem('darkMode', isDarkMode);
            updateDarkMode();
        }

        function updateDarkMode() {
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Initialize dark mode on load
        document.addEventListener('DOMContentLoaded', function() {
            // Check system preference if no saved preference
            if (localStorage.getItem('darkMode') === null) {
                isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            updateDarkMode();
        });
    </script>
</body>
</html>