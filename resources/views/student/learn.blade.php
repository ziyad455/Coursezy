<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($lesson) ? $lesson->title : $course->title }} - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white antialiased">
    <!-- Original Navigation -->
    <x-studentNav />



    <!-- Main Content Container -->
    <div class="flex pt-16" style="height: calc(100vh - 4rem);" x-data="{ sidebarVisible: true }">
        <!-- Course Sidebar Component -->
        <x-course-sidebar :course="$course" :currentLesson="$lesson ?? null" :isOpen="true" />

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-gray-900">
            @if(isset($lesson))
                <!-- Video Player Section -->
                <div class="bg-black" style="height: 60vh; min-height: 400px;">
                    @if($lesson->type === 'video' && $lesson->video_url)
                        <!-- Modern Video Player Component -->
                        <div class="w-full h-full">
                            <x-video-player :lesson="$lesson" :course="$course" :nextLesson="$nextLesson ?? null" />
                        </div>
                    @else
                        <!-- Text Content -->
                        <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-900 p-8">
                            <div class="max-w-4xl mx-auto">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $lesson->title }}</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ $lesson->section->title }}</p>

                                <div class="prose dark:prose-invert max-w-none">
                                    @if($lesson->content)
                                        {!! nl2br(e($lesson->content)) !!}
                                    @else
                                        <p class="text-gray-600 dark:text-gray-400">No content available for this lesson.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Bottom Lesson Info & Navigation -->
                <div class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                    <!-- Tabs for Overview, Resources, Q&A -->
                    <div class="border-b border-gray-200 dark:border-gray-800" x-data="{ activeTab: 'overview' }">
                        <div class="max-w-7xl mx-auto px-4">
                            <nav class="flex space-x-8" aria-label="Tabs">
                                <button @click="activeTab = 'overview'"
                                    :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Overview
                                </button>
                                <button @click="activeTab = 'resources'"
                                    :class="activeTab === 'resources' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Resources
                                </button>
                                <button @click="activeTab = 'notes'"
                                    :class="activeTab === 'notes' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Notes
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Content -->
                        <div class="max-w-7xl mx-auto px-4 py-6">
                            <!-- Overview Tab -->
                            <div x-show="activeTab === 'overview'" x-transition>
                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">About this lesson</h3>
                                        <p class="text-gray-700 dark:text-gray-300">
                                            {{ $lesson->description ?? 'No description available.' }}
                                        </p>
                                    </div>
                                    <div>
                                        <!-- Navigation Buttons -->
                                        <div class="space-y-3">
                                            @if($previousLesson)
                                                <a href="{{ route('student.learn.lesson', ['course' => $course->id, 'lesson' => $previousLesson->id]) }}"
                                                    class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-lg transition-colors font-medium">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                    Previous Lesson
                                                </a>
                                            @endif

                                            @if($nextLesson)
                                                <a href="{{ route('student.learn.lesson', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}"
                                                    class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium">
                                                    Next Lesson
                                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ route('my.courses') }}"
                                                    class="flex items-center justify-center w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Complete Course
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resources Tab -->
                            <div x-show="activeTab === 'resources'" x-transition>
                                <p class="text-gray-600 dark:text-gray-400">No resources available for this lesson yet.</p>
                            </div>

                            <!-- Notes Tab -->
                            <div x-show="activeTab === 'notes'" x-transition>
                                <p class="text-gray-600 dark:text-gray-400">Notes feature coming soon!</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Lesson Selected -->
                <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                    <div class="text-center max-w-md mx-auto p-8">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Welcome to {{ $course->title }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Select a lesson from the sidebar to begin your
                            learning journey</p>
                        <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Keyboard shortcuts available</span>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</body>

</html>