<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $course->title }} - Coursezy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white antialiased">
    <!-- Navigation -->
    <x-coachNav/>

    <!-- Preview Banner -->
    <div class="fixed top-16 left-0 right-0 z-40 bg-yellow-500 dark:bg-yellow-600 text-gray-900 dark:text-white py-2 px-4 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="font-semibold">Preview Mode - This is how students see your course</span>
            </div>
            <a href="{{ route('coach.courses.index') }}" class="px-4 py-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors text-sm font-medium">
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="flex pt-28" style="height: calc(100vh - 7rem);" x-data="{ 
        sidebarOpen: true,
        expandedSections: {},
        selectedLesson: null,
        showAddSectionModal: false,
        
        toggleSection(sectionId) {
            this.expandedSections[sectionId] = !this.expandedSections[sectionId];
        },
        
        isSectionExpanded(sectionId) {
            if (this.expandedSections[sectionId] === undefined) {
                return true;
            }
            return this.expandedSections[sectionId];
        },
        
        selectLesson(lesson) {
            this.selectedLesson = lesson;
        },
        
        openAddSectionModal() {
            this.showAddSectionModal = true;
        },
        
        closeAddSectionModal() {
            this.showAddSectionModal = false;
        }
    }">
        <!-- Custom Course Sidebar for Preview -->
        <aside 
            class="course-sidebar bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-300 h-full"
            :class="sidebarOpen ? 'w-96' : 'w-16'">
            
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                        {{ $course->title }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                        by {{ $course->coach->name }}
                    </p>
                </div>
                
                <!-- Toggle Button -->
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="ml-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors flex-shrink-0">
                    <svg 
                        class="w-5 h-5 text-gray-600 dark:text-gray-400 transition-transform duration-300"
                        :class="sidebarOpen ? '' : 'rotate-180'"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Sections & Lessons List -->
            <div 
                x-show="sidebarOpen" 
                x-transition
                class="flex-1 overflow-y-auto">
                
                @forelse($course->sections as $index => $section)
                    @php
                        $sectionLessons = $section->lessons;
                        $sectionLessonCount = $sectionLessons->count();
                    @endphp
                    
                    <div class="border-b border-gray-200 dark:border-gray-800 last:border-b-0">
                        <!-- Section Header -->
                        <button 
                            @click="toggleSection({{ $section->id }})"
                            class="w-full p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors text-left">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        Section {{ $index + 1 }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1 truncate">
                                    {{ $section->title }}
                                </h3>
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <span>{{ $sectionLessonCount }} {{ Str::plural('lesson', $sectionLessonCount) }}</span>
                                </div>
                            </div>
                            <svg 
                                class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-2"
                                :class="isSectionExpanded({{ $section->id }}) ? 'rotate-180' : ''"
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Lessons List -->
                        <div 
                            x-show="isSectionExpanded({{ $section->id }})"
                            x-transition
                            class="bg-gray-50 dark:bg-gray-800/30">
                            
                            @foreach($sectionLessons as $lessonIndex => $lessonItem)
                                <button 
                                    @click="selectLesson({{ json_encode([
                                        'id' => $lessonItem->id,
                                        'title' => $lessonItem->title,
                                        'description' => $lessonItem->description,
                                        'content' => $lessonItem->content,
                                        'type' => $lessonItem->type,
                                        'video_url' => $lessonItem->video_url,
                                        'section_title' => $section->title
                                    ]) }})"
                                    class="w-full flex items-center p-4 pl-8 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors group text-left"
                                    :class="selectedLesson && selectedLesson.id === {{ $lessonItem->id }} ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-600' : 'border-l-4 border-transparent'">
                                    
                                    <!-- Lesson Icon -->
                                    <div class="flex-shrink-0 mr-3">
                                        @if($lessonItem->type === 'video')
                                            <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Lesson Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $lessonItem->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($lessonItem->type) }}
                                        </p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <p>No sections added yet</p>
                    </div>
                @endforelse
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-gray-900">
            <!-- Course Overview - Default View (No Lesson Selected) -->
            <div x-show="!selectedLesson" class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                <div class="text-center max-w-2xl mx-auto p-8">
                    <div class="w-24 h-24 mx-auto mb-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $course->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">This is how students will see your course. Click on any lesson in the sidebar to preview how it appears to students.</p>
                    
                    <!-- Course Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-8">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $course->sections->sum(fn($s) => $s->lessons->count()) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Lessons</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $course->sections->count() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Sections</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($course->price, 2) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Price</div>
                        </div>
                    </div>

                    <!-- Course Description -->
                    <div class="mt-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-left">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Course Description</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $course->description }}
                        </p>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Preview Mode: You're viewing this as a student would. All interactive features are disabled.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lesson Preview (When Lesson Selected) -->
            <div x-show="selectedLesson" x-transition class="flex-1 flex flex-col">
                <!-- Video/Content Section -->
                <div class="bg-black" style="height: 60vh; min-height: 400px;">
                    <template x-if="selectedLesson && selectedLesson.type === 'video' && selectedLesson.video_url">
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center text-white p-8">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg mb-2">Video Player Preview</p>
                                <p class="text-sm text-gray-400" x-text="'Video URL: ' + selectedLesson.video_url"></p>
                                <p class="text-xs text-gray-500 mt-4">Students will see the actual video player here</p>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="selectedLesson && selectedLesson.type !== 'video'">
                        <div class="w-full h-full overflow-y-auto bg-white dark:bg-gray-900 p-8">
                            <div class="max-w-4xl mx-auto">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4" x-text="selectedLesson.title"></h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6" x-text="selectedLesson.section_title"></p>
                                
                                <div class="prose dark:prose-invert max-w-none">
                                    <template x-if="selectedLesson.content">
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap" x-text="selectedLesson.content"></p>
                                    </template>
                                    <template x-if="!selectedLesson.content">
                                        <p class="text-gray-600 dark:text-gray-400">No content available for this lesson.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Lesson Info Section -->
                <div class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-6">
                    <div class="max-w-7xl mx-auto">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">About this lesson</h3>
                        <p class="text-gray-700 dark:text-gray-300" x-text="selectedLesson ? (selectedLesson.description || 'No description available.') : ''"></p>
                        
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Preview Mode: Navigation buttons and interactive features are disabled in this preview.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Floating Action Button -->
    <button 
        @click="openAddSectionModal()"
        class="fixed bottom-8 right-8 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center z-50 group">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="absolute right-16 bg-gray-900 text-white text-sm px-3 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
            Add Video Section
        </span>
    </button>

    <!-- Add Section Modal -->
    <div 
        x-show="showAddSectionModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeAddSectionModal()"></div>
        
        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                x-show="showAddSectionModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full p-6"
                @click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Video Section</h3>
                    <button 
                        @click="closeAddSectionModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('coach.courses.sections.store', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Section Title -->
                    <div>
                        <label for="section_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Section Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="section_title"
                            name="sections[0][title]" 
                            required
                            placeholder="e.g., Introduction to the Course"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                    </div>

                    <!-- Video Upload -->
                    <div>
                        <label for="section_video" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Video File <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                id="section_video"
                                name="sections[0][video]" 
                                accept="video/*"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 transition-colors">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Supported formats: MP4, MOV, AVI, WMV. Maximum size: 2GB
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <p class="font-medium mb-1">How it works:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>A new section will be created with your title</li>
                                    <li>The video will be uploaded to Cloudinary</li>
                                    <li>A video lesson will be automatically created in the section</li>
                                    <li>The section will be published and visible to students</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button"
                            @click="closeAddSectionModal()"
                            class="px-5 py-2.5 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-5 py-2.5 rounded-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm hover:shadow-md flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span>Upload & Create Section</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-8 left-8 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 7000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-8 left-8 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-semibold mb-1">Error:</p>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</body>
</html>
