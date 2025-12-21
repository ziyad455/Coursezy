@props(['course', 'currentLesson' => null, 'isOpen' => true])

<aside 
    x-data="{ 
        sidebarOpen: {{ $isOpen ? 'true' : 'false' }},
        expandedSections: {},
        
        toggleSection(sectionId) {
            this.expandedSections[sectionId] = !this.expandedSections[sectionId];
        },
        
        isSectionExpanded(sectionId) {
            // Expand section if it contains the current lesson
            if (this.expandedSections[sectionId] === undefined) {
                return {{ $currentLesson ? 'true' : 'false' }};
            }
            return this.expandedSections[sectionId];
        }
    }"
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
            
            <!-- Course Progress -->
            @php
                $totalLessons = $course->sections->sum(function($section) {
                    return $section->lessons->count();
                });
                $completedLessons = 0; // This would come from your database
                $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
            @endphp
            
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                    <span>Course Progress</span>
                    <span>{{ number_format($progressPercentage, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div 
                        class="bg-indigo-600 h-2 rounded-full transition-all duration-300" 
                        style="width: {{ $progressPercentage }}%">
                    </div>
                </div>
            </div>
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
        class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">
        
        @forelse($course->sections as $index => $section)
            @php
                $sectionLessons = $section->lessons;
                $sectionDuration = $sectionLessons->sum('duration');
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
                        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400 space-x-3">
                            <span>{{ $sectionLessonCount }} {{ Str::plural('lesson', $sectionLessonCount) }}</span>
                            @if($sectionDuration)
                                <span>•</span>
                                <span>{{ floor($sectionDuration / 60) }}m</span>
                            @endif
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
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-gray-50 dark:bg-gray-800/30">
                    
                    @foreach($sectionLessons as $lessonIndex => $lessonItem)
                        @php
                            $isCurrentLesson = $currentLesson && $currentLesson->id === $lessonItem->id;
                            $isCompleted = false; // This would come from your database
                        @endphp
                        
                        <a href="{{ route('student.learn.lesson', ['course' => $course->id, 'lesson' => $lessonItem->id]) }}"
                           class="flex items-center p-4 pl-8 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors group
                                  {{ $isCurrentLesson ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-600' : 'border-l-4 border-transparent' }}">
                            
                            <!-- Lesson Number / Icon -->
                            <div class="flex-shrink-0 mr-3">
                                @if($isCompleted)
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @elseif($lessonItem->type === 'video')
                                    <div class="w-6 h-6 rounded-full {{ $isCurrentLesson ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }} flex items-center justify-center">
                                        <svg class="w-3 h-3 {{ $isCurrentLesson ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full {{ $isCurrentLesson ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }} flex items-center justify-center">
                                        <svg class="w-3 h-3 {{ $isCurrentLesson ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Lesson Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium {{ $isCurrentLesson ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-900 dark:text-white' }} truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $lessonItem->title }}
                                </h4>
                                @if($lessonItem->duration)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                        {{ $lessonItem->formatted_duration }}
                                    </p>
                                @endif
                            </div>

                            <!-- Play Icon on Hover -->
                            @if(!$isCurrentLesson && $lessonItem->type === 'video')
                                <div class="flex-shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Current Playing Indicator -->
                            @if($isCurrentLesson)
                                <div class="flex-shrink-0 ml-2">
                                    <div class="flex space-x-0.5">
                                        <span class="w-1 h-3 bg-indigo-600 rounded-full animate-pulse"></span>
                                        <span class="w-1 h-3 bg-indigo-600 rounded-full animate-pulse" style="animation-delay: 0.2s;"></span>
                                        <span class="w-1 h-3 bg-indigo-600 rounded-full animate-pulse" style="animation-delay: 0.4s;"></span>
                                    </div>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-600 dark:text-gray-400">No lessons available yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Collapsed Sidebar Tooltip -->
    <div 
        x-show="!sidebarOpen"
        x-transition
        class="p-2 text-center">
        <button 
            @click="sidebarOpen = true"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors w-full"
            title="Expand course content">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</aside>

<style>
/* Custom Scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>
