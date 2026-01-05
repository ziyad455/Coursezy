<!DOCTYPE html>
<html lang="en" class="scroll-smooth" @auth
class="{{ (session('dark_mode', auth()->user()->dark_mode)) ? 'dark' : '' }}" @endauth>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Sections - {{ $course->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .upload-shimmer {
            background: linear-gradient(90deg,
                    rgba(99, 102, 241, 0.05) 25%,
                    rgba(99, 102, 241, 0.15) 50%,
                    rgba(99, 102, 241, 0.05) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        .dark .upload-shimmer {
            background: linear-gradient(90deg,
                    rgba(99, 102, 241, 0.1) 25%,
                    rgba(99, 102, 241, 0.25) 50%,
                    rgba(99, 102, 241, 0.1) 75%);
            background-size: 200% 100%;
        }
    </style>
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300"
    x-data="sectionManager()">
    <x-coachNav />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('coach.courses.index') }}"
                            class="inline-flex items-center text-sm font-medium text-light-text-muted hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('coach.courses.edit', $course->id) }}"
                                class="ml-1 text-sm font-medium text-light-text-muted hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                                {{ Str::limit($course->title, 30) }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-light-text-primary dark:text-dark-text-primary">Manage
                                Sections</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Manage Sections
                    </h1>
                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                        Add, remove, or reorganize sections and videos for <span
                            class="font-medium">{{ $course->title }}</span>
                    </p>
                </div>
                <a href="{{ route('coach.courses.edit', $course->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default hover:border-light-accent-secondary dark:hover:border-dark-accent-secondary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Edit</span>
                </a>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="flex flex-wrap gap-3 mb-8">
            <div
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-subtle dark:border-dark-border-subtle">
                <div
                    class="w-8 h-8 rounded-lg bg-light-accent-info/10 dark:bg-dark-accent-info/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-light-accent-info dark:text-dark-accent-info" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Sections</p>
                    <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary"
                        x-text="sections.length"></p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-subtle dark:border-dark-border-subtle">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Total Videos</p>
                    <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary"
                        x-text="getTotalVideosCount()"></p>
                </div>
            </div>
        </div>

        <!-- Sections List -->
        <div class="space-y-6">
            <template x-for="(section, sectionIndex) in sections" :key="section.id">
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0">

                    <!-- Section Header -->
                    <div
                        class="px-6 py-4 bg-light-bg-tertiary dark:bg-dark-bg-tertiary border-b border-light-border-subtle dark:border-dark-border-subtle">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 cursor-pointer"
                                @click="section.expanded = !section.expanded">
                                <div
                                    class="w-10 h-10 rounded-lg bg-light-accent-secondary dark:bg-dark-accent-secondary flex items-center justify-center text-white font-bold text-sm">
                                    <span x-text="sectionIndex + 1"></span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-light-text-primary dark:text-dark-text-primary"
                                        x-text="section.title"></h3>
                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted"
                                        x-text="`${section.lessons.length} video${section.lessons.length !== 1 ? 's' : ''}`">
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-light-text-muted dark:text-dark-text-muted transition-transform"
                                    :class="{ 'rotate-180': section.expanded }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="openAddVideoModal(section)"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-light-accent-secondary dark:text-dark-accent-secondary hover:bg-light-accent-secondary/10 dark:hover:bg-dark-accent-secondary/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Video
                                </button>
                                <button type="button" @click="confirmDeleteSection(section)"
                                    class="p-2 rounded-lg text-light-text-muted dark:text-dark-text-muted hover:text-light-accent-error dark:hover:text-dark-accent-error hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                    title="Delete section">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Content (Videos) -->
                    <div x-show="section.expanded" x-collapse>
                        <div class="p-6 space-y-3">
                            <template x-if="section.lessons.length === 0">
                                <div class="text-center py-8 text-light-text-muted dark:text-dark-text-muted">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p>No videos in this section</p>
                                    <button @click="openAddVideoModal(section)"
                                        class="mt-2 text-sm text-light-accent-secondary dark:text-dark-accent-secondary hover:underline">
                                        Add your first video
                                    </button>
                                </div>
                            </template>
                            <template x-for="(lesson, lessonIndex) in section.lessons" :key="lesson.id">
                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-light-bg-tertiary dark:bg-dark-bg-tertiary border border-light-border-subtle dark:border-dark-border-subtle">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-sm font-medium text-purple-600 dark:text-purple-400">
                                            <span x-text="lessonIndex + 1"></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-light-text-primary dark:text-dark-text-primary"
                                                x-text="lesson.title"></p>
                                            <p class="text-xs text-light-text-muted dark:text-dark-text-muted"
                                                x-text="lesson.metadata?.original_name || 'Video'"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="confirmDeleteVideo(section, lesson)"
                                        class="p-2 rounded-lg text-light-text-muted dark:text-dark-text-muted hover:text-light-accent-error dark:hover:text-dark-accent-error hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Delete video">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="sections.length === 0">
                <div
                    class="text-center py-16 bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl border border-light-border-subtle dark:border-dark-border-subtle">
                    <div
                        class="w-16 h-16 mx-auto rounded-xl bg-light-accent-secondary/10 dark:bg-dark-accent-secondary/20 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-light-accent-secondary dark:text-dark-accent-secondary" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">No
                        sections yet</h3>
                    <p class="text-light-text-muted dark:text-dark-text-muted mb-6 max-w-sm mx-auto">
                        This course doesn't have any sections. Go to the course creation page to add sections.
                    </p>
                    <a href="{{ route('coach.courses.sections', $course->id) }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Sections
                    </a>
                </div>
            </template>
        </div>
    </main>

    <!-- Add Video Modal -->
    <div x-show="showAddVideoModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                @click="closeAddVideoModal()"></div>

            <div class="relative bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-xl max-w-lg w-full mx-auto p-6 text-left"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary mb-4">
                    Add Video to <span x-text="selectedSection?.title"></span>
                </h3>

                <div class="space-y-4">
                    <!-- Video Title -->
                    <div>
                        <label
                            class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1.5">Video
                            Title</label>
                        <input type="text" x-model="newVideo.title" placeholder="e.g., Introduction"
                            class="w-full px-4 py-3 rounded-lg border border-light-border-default dark:border-dark-border-default bg-light-input-bg dark:bg-dark-input-bg text-light-text-primary dark:text-dark-text-primary placeholder:text-light-input-placeholder dark:placeholder:text-dark-input-placeholder focus:ring-2 focus:ring-light-accent-secondary/20 dark:focus:ring-dark-accent-secondary/20 focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary transition-colors" />
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label
                            class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1.5">Video
                            File</label>
                        <input type="file" accept="video/*" @change="handleVideoUpload($event)"
                            :disabled="newVideo.uploading"
                            class="w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-light-accent-secondary file:text-white file:font-medium file:cursor-pointer hover:file:bg-light-accent-secondary/90 dark:file:bg-dark-accent-secondary dark:hover:file:bg-dark-accent-secondary/90 border border-light-border-default dark:border-dark-border-default rounded-lg bg-light-input-bg dark:bg-dark-input-bg text-light-text-primary dark:text-dark-text-primary cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed transition-colors" />
                    </div>

                    <!-- Upload Progress -->
                    <div x-show="newVideo.uploading" class="space-y-2">
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-light-accent-secondary dark:text-dark-accent-secondary">Uploading to
                                cloud...</span>
                            <span class="text-light-text-secondary dark:text-dark-text-secondary"
                                x-text="`${newVideo.progress}%`"></span>
                        </div>
                        <div
                            class="w-full bg-light-bg-tertiary dark:bg-dark-bg-tertiary rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full bg-light-accent-secondary dark:bg-dark-accent-secondary transition-all duration-300"
                                :style="`width: ${newVideo.progress}%`"></div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <div x-show="newVideo.status === 'success'"
                        class="flex items-center gap-2 text-sm text-light-accent-success dark:text-dark-accent-success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Video uploaded successfully!</span>
                    </div>
                    <div x-show="newVideo.status === 'error'"
                        class="flex items-center gap-2 text-sm text-light-accent-error dark:text-dark-accent-error">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span x-text="newVideo.errorMessage"></span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeAddVideoModal()"
                        class="px-4 py-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="button" @click="saveNewVideo()"
                        :disabled="!newVideo.public_id || !newVideo.title.trim() || newVideo.uploading || saving"
                        class="px-4 py-2 text-sm font-medium text-white bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="saving ? 'Saving...' : 'Add Video'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"
                @click="closeDeleteModal()"></div>

            <div
                class="relative bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-xl max-w-md w-full mx-auto p-6 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-light-text-primary dark:text-dark-text-primary mb-2"
                    x-text="deleteModalTitle"></h3>
                <p class="text-sm text-light-text-muted dark:text-dark-text-muted mb-6" x-text="deleteModalMessage"></p>

                <div class="flex justify-center gap-3">
                    <button type="button" @click="closeDeleteModal()"
                        class="px-4 py-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-tertiary dark:bg-dark-bg-tertiary hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="button" @click="executeDelete()" :disabled="deleting"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-50">
                        <span x-text="deleting ? 'Deleting...' : 'Delete'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg"
        :class="toast.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
        <div class="flex items-center gap-2">
            <template x-if="toast.type === 'success'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </template>
            <span x-text="toast.message"></span>
        </div>
    </div>

    @php
        $sectionsData = $course->sections->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'order' => $s->order,
                'lessons' => $s->lessons,
                'expanded' => true
            ];
        });
    @endphp
    <script>
        function sectionManager() {
            return {
                courseId: {{ $course->id }},
                sections: @json($sectionsData),

                // Add Video Modal
                showAddVideoModal: false,
                selectedSection: null,
                newVideo: {
                    title: '',
                    uploading: false,
                    progress: 0,
                    status: 'idle',
                    errorMessage: '',
                    video_url: '',
                    public_id: '',
                    original_name: '',
                    size: 0,
                    mime_type: ''
                },
                saving: false,

                // Delete Modal
                showDeleteModal: false,
                deleteModalTitle: '',
                deleteModalMessage: '',
                deleteType: null,
                deleteTarget: null,
                deleteSection: null,
                deleting: false,

                // Toast
                toast: { show: false, message: '', type: 'success' },

                getTotalVideosCount() {
                    return this.sections.reduce((total, section) => total + section.lessons.length, 0);
                },

                openAddVideoModal(section) {
                    this.selectedSection = section;
                    this.newVideo = {
                        title: '',
                        uploading: false,
                        progress: 0,
                        status: 'idle',
                        errorMessage: '',
                        video_url: '',
                        public_id: '',
                        original_name: '',
                        size: 0,
                        mime_type: ''
                    };
                    this.showAddVideoModal = true;
                },

                closeAddVideoModal() {
                    this.showAddVideoModal = false;
                    this.selectedSection = null;
                },

                async handleVideoUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.newVideo.uploading = true;
                    this.newVideo.progress = 0;
                    this.newVideo.status = 'uploading';
                    this.newVideo.errorMessage = '';
                    this.newVideo.original_name = file.name;
                    this.newVideo.size = file.size;
                    this.newVideo.mime_type = file.type;

                    if (!this.newVideo.title) {
                        this.newVideo.title = file.name.replace(/\.[^/.]+$/, '').replace(/[-_]/g, ' ');
                    }

                    try {
                        const folder = `coursezy/courses/${this.courseId}/sections`;
                        const signatureParams = {
                            folder,
                            resource_type: 'video',
                            timestamp: Math.floor(Date.now() / 1000)
                        };

                        const sigResponse = await fetch(`{{ route('coach.cloudinary.signature') }}?${new URLSearchParams(signatureParams)}`);
                        const sigData = await sigResponse.json();

                        if (!sigResponse.ok) {
                            throw new Error(sigData.error || 'Failed to get upload signature');
                        }

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('api_key', sigData.api_key);
                        formData.append('timestamp', sigData.timestamp);
                        formData.append('signature', sigData.signature);
                        formData.append('folder', folder);

                        const uploadUrl = `https://api.cloudinary.com/v1_1/${sigData.cloud_name}/video/upload`;

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', uploadUrl);

                        xhr.upload.onprogress = (e) => {
                            if (e.lengthComputable) {
                                this.newVideo.progress = Math.round((e.loaded / e.total) * 100);
                            }
                        };

                        xhr.onload = () => {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (xhr.status >= 200 && xhr.status < 300) {
                                    this.newVideo.video_url = response.secure_url;
                                    this.newVideo.public_id = response.public_id;
                                    this.newVideo.status = 'success';
                                    this.newVideo.uploading = false;
                                } else {
                                    this.newVideo.status = 'error';
                                    this.newVideo.errorMessage = response.error?.message || 'Upload failed';
                                    this.newVideo.uploading = false;
                                }
                            } catch (e) {
                                this.newVideo.status = 'error';
                                this.newVideo.errorMessage = 'Invalid response from Cloudinary';
                                this.newVideo.uploading = false;
                            }
                        };

                        xhr.onerror = () => {
                            this.newVideo.status = 'error';
                            this.newVideo.errorMessage = 'Network error. Check your connection.';
                            this.newVideo.uploading = false;
                        };

                        xhr.send(formData);

                    } catch (error) {
                        this.newVideo.status = 'error';
                        this.newVideo.errorMessage = error.message;
                        this.newVideo.uploading = false;
                    }
                },

                async saveNewVideo() {
                    if (!this.selectedSection || !this.newVideo.public_id) return;

                    this.saving = true;
                    try {
                        const response = await fetch(`/courses/${this.courseId}/sections/${this.selectedSection.id}/videos`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.newVideo.title,
                                video_url: this.newVideo.video_url,
                                public_id: this.newVideo.public_id,
                                original_name: this.newVideo.original_name,
                                size: this.newVideo.size,
                                mime_type: this.newVideo.mime_type
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Add the new lesson to the section
                            const section = this.sections.find(s => s.id === this.selectedSection.id);
                            if (section) {
                                section.lessons.push(data.lesson);
                            }
                            this.showToast('Video added successfully!', 'success');
                            this.closeAddVideoModal();
                        } else {
                            this.showToast(data.message || 'Failed to add video', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to add video. Please try again.', 'error');
                    } finally {
                        this.saving = false;
                    }
                },

                confirmDeleteSection(section) {
                    this.deleteType = 'section';
                    this.deleteTarget = section;
                    this.deleteModalTitle = 'Delete Section';
                    this.deleteModalMessage = `Are you sure you want to delete "${section.title}"? This will permanently delete all ${section.lessons.length} video(s) in this section.`;
                    this.showDeleteModal = true;
                },

                confirmDeleteVideo(section, lesson) {
                    this.deleteType = 'video';
                    this.deleteTarget = lesson;
                    this.deleteSection = section;
                    this.deleteModalTitle = 'Delete Video';
                    this.deleteModalMessage = `Are you sure you want to delete "${lesson.title}"? This action cannot be undone.`;
                    this.showDeleteModal = true;
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                    this.deleteTarget = null;
                    this.deleteSection = null;
                    this.deleteType = null;
                },

                async executeDelete() {
                    this.deleting = true;
                    try {
                        let url, method = 'DELETE';

                        if (this.deleteType === 'section') {
                            url = `/courses/${this.courseId}/sections/${this.deleteTarget.id}`;
                        } else {
                            url = `/courses/${this.courseId}/sections/${this.deleteSection.id}/videos/${this.deleteTarget.id}`;
                        }

                        const response = await fetch(url, {
                            method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            if (this.deleteType === 'section') {
                                this.sections = this.sections.filter(s => s.id !== this.deleteTarget.id);
                                this.showToast('Section deleted successfully!', 'success');
                            } else {
                                const section = this.sections.find(s => s.id === this.deleteSection.id);
                                if (section) {
                                    section.lessons = section.lessons.filter(l => l.id !== this.deleteTarget.id);
                                }
                                this.showToast('Video deleted successfully!', 'success');
                            }
                            this.closeDeleteModal();
                        } else {
                            this.showToast(data.message || 'Failed to delete', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to delete. Please try again.', 'error');
                    } finally {
                        this.deleting = false;
                    }
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                }
            };
        }
    </script>
</body>

</html>