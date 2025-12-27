<!DOCTYPE html>
<html lang="en" class="scroll-smooth" @auth class="{{ (session('dark_mode', auth()->user()->dark_mode)) ? 'dark' : '' }}" @endauth>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sections - Coursezy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Upload animations */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
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
        
        .success-pulse::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: rgba(34, 197, 94, 0.3);
            animation: pulse-ring 1s ease-out forwards;
        }
        
        /* Progress bar glow */
        .progress-glow {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
        }
        
        /* Video card hover effects */
        .video-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .video-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300"
    x-data="uploadManager()" x-init="addSection()">
    <x-coachNav />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-28">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Create Course Sections
                    </h1>
                    <p class="text-light-text-secondary dark:text-dark-text-secondary flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-light-accent-secondary/10 dark:bg-dark-accent-secondary/20 text-light-accent-secondary dark:text-dark-accent-secondary">
                            Step 2 of 2
                        </span>
                        <span>Organize your content into sections with videos</span>
                    </p>
                </div>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-default dark:border-dark-border-default hover:border-light-accent-secondary dark:hover:border-dark-accent-secondary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back</span>
                </a>
            </div>
            
            <!-- Stats Bar -->
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-subtle dark:border-dark-border-subtle">
                    <div class="w-8 h-8 rounded-lg bg-light-accent-info/10 dark:bg-dark-accent-info/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-light-accent-info dark:text-dark-accent-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Sections</p>
                        <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary" x-text="sections.length"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-subtle dark:border-dark-border-subtle">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Total Videos</p>
                        <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary" x-text="getTotalVideosCount()"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-light-bg-secondary dark:bg-dark-bg-secondary border border-light-border-subtle dark:border-dark-border-subtle">
                    <div class="w-8 h-8 rounded-lg bg-light-accent-success/10 dark:bg-dark-accent-success/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-light-accent-success dark:text-dark-accent-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Uploaded</p>
                        <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary" x-text="getUploadedVideosCount()"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections Form -->
        <form id="sectionsForm" @submit.prevent="submitForm()"
            action="{{ route('coach.courses.sections.store', $course->id) }}" method="POST" class="space-y-6">
            @csrf

            <div id="sectionsContainer" class="space-y-6">
                <template x-for="(section, sectionIndex) in sections" :key="section.id">
                    <div class="section-card" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0 translate-y-4">
                        
                        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden">
                            <!-- Section Header -->
                            <div class="px-6 py-4 bg-light-bg-tertiary dark:bg-dark-bg-tertiary border-b border-light-border-subtle dark:border-dark-border-subtle">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-light-accent-secondary dark:bg-dark-accent-secondary flex items-center justify-center text-white font-bold text-sm">
                                            <span x-text="sectionIndex + 1"></span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-light-text-primary dark:text-dark-text-primary">Section <span x-text="sectionIndex + 1"></span></h3>
                                            <p class="text-xs text-light-text-muted dark:text-dark-text-muted" x-text="`${section.videos.length} video${section.videos.length !== 1 ? 's' : ''}`"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="duplicateSection(sectionIndex)"
                                            class="p-2 rounded-lg text-light-text-muted dark:text-dark-text-muted hover:text-light-accent-secondary dark:hover:text-dark-accent-secondary hover:bg-light-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors"
                                            title="Duplicate section">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="removeSection(sectionIndex)"
                                            class="p-2 rounded-lg text-light-text-muted dark:text-dark-text-muted hover:text-light-accent-error dark:hover:text-dark-accent-error hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            title="Remove section">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Section Title Input -->
                                <div>
                                    <label class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                                        Section Title
                                    </label>
                                    <input type="text" :name="`sections[${sectionIndex}][title]`" x-model="section.title"
                                        required placeholder="e.g., Getting Started with the Basics"
                                        class="w-full px-4 py-3 rounded-lg border border-light-border-default dark:border-dark-border-default bg-light-input-bg dark:bg-dark-input-bg text-light-text-primary dark:text-dark-text-primary placeholder:text-light-input-placeholder dark:placeholder:text-dark-input-placeholder focus:ring-2 focus:ring-light-accent-secondary/20 dark:focus:ring-dark-accent-secondary/20 focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary transition-colors" />
                                </div>

                                <!-- Videos Container -->
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">
                                            Videos
                                        </label>
                                        <span class="text-xs px-2 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 font-medium"
                                            x-text="`${section.videos.filter(v => v.public_id).length}/${section.videos.length} uploaded`"></span>
                                    </div>

                                    <!-- Video List -->
                                    <div class="space-y-3">
                                        <template x-for="(video, videoIndex) in section.videos" :key="video.id">
                                            <div class="video-card relative rounded-lg overflow-hidden"
                                                :class="{
                                                    'bg-light-accent-success/5 dark:bg-dark-accent-success/10': video.status === 'success',
                                                    'bg-light-accent-error/5 dark:bg-dark-accent-error/10': video.status === 'error',
                                                    'upload-shimmer': video.uploading,
                                                    'bg-light-bg-tertiary dark:bg-dark-bg-tertiary': video.status === 'idle' && !video.uploading
                                                }">
                                                
                                                <!-- Success pulse animation -->
                                                <div x-show="video.status === 'success'" class="success-pulse absolute inset-0 rounded-lg pointer-events-none" x-transition></div>
                                                
                                                <div class="relative border rounded-lg p-4"
                                                    :class="{
                                                        'border-light-accent-success/30 dark:border-dark-accent-success/30': video.status === 'success',
                                                        'border-light-accent-error/30 dark:border-dark-accent-error/30': video.status === 'error',
                                                        'border-light-accent-secondary/50 dark:border-dark-accent-secondary/50': video.uploading,
                                                        'border-light-border-default dark:border-dark-border-default': video.status === 'idle' && !video.uploading
                                                    }">
                                                    
                                                    <!-- Video Header -->
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-bold"
                                                                :class="{
                                                                    'bg-light-accent-success dark:bg-dark-accent-success text-white': video.status === 'success',
                                                                    'bg-light-accent-error dark:bg-dark-accent-error text-white': video.status === 'error',
                                                                    'bg-light-accent-secondary dark:bg-dark-accent-secondary text-white animate-pulse': video.uploading,
                                                                    'bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-muted dark:text-dark-text-muted border border-light-border-default dark:border-dark-border-default': video.status === 'idle' && !video.uploading
                                                                }">
                                                                <template x-if="video.status === 'success'">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </template>
                                                                <template x-if="video.status === 'error'">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </template>
                                                                <template x-if="video.uploading">
                                                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                    </svg>
                                                                </template>
                                                                <template x-if="video.status === 'idle' && !video.uploading">
                                                                    <span x-text="videoIndex + 1"></span>
                                                                </template>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">Video <span x-text="videoIndex + 1"></span></p>
                                                                <p class="text-xs" 
                                                                    :class="{
                                                                        'text-light-accent-success dark:text-dark-accent-success': video.status === 'success',
                                                                        'text-light-accent-error dark:text-dark-accent-error': video.status === 'error',
                                                                        'text-light-accent-secondary dark:text-dark-accent-secondary': video.uploading,
                                                                        'text-light-text-muted dark:text-dark-text-muted': video.status === 'idle' && !video.uploading
                                                                    }"
                                                                    x-text="video.uploading ? 'Uploading...' : (video.status === 'success' ? 'Ready' : (video.status === 'error' ? 'Failed' : 'Pending'))"></p>
                                                            </div>
                                                        </div>
                                                        <button type="button" @click="removeVideo(sectionIndex, videoIndex)"
                                                            x-show="section.videos.length > 1"
                                                            class="p-1.5 rounded-lg text-light-text-muted dark:text-dark-text-muted hover:text-light-accent-error dark:hover:text-dark-accent-error hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                            title="Remove video">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <!-- Video Title Input -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-light-text-muted dark:text-dark-text-muted mb-1.5">Video Title</label>
                                                            <input type="text" :name="`sections[${sectionIndex}][videos][${videoIndex}][title]`"
                                                                x-model="video.title" required
                                                                placeholder="e.g., Introduction"
                                                                class="w-full px-3 py-2.5 text-sm rounded-lg border border-light-border-default dark:border-dark-border-default bg-light-input-bg dark:bg-dark-input-bg text-light-text-primary dark:text-dark-text-primary placeholder:text-light-input-placeholder dark:placeholder:text-dark-input-placeholder focus:ring-2 focus:ring-light-accent-secondary/20 dark:focus:ring-dark-accent-secondary/20 focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary transition-colors" />
                                                        </div>

                                                        <!-- File Upload -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-light-text-muted dark:text-dark-text-muted mb-1.5">Video File</label>
                                                            <div class="relative">
                                                                <input type="file" accept="video/*"
                                                                    @change="handleFileUpload($event, sectionIndex, videoIndex)"
                                                                    :disabled="video.uploading"
                                                                    class="w-full text-sm file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-light-accent-secondary file:text-white file:font-medium file:cursor-pointer hover:file:bg-light-accent-secondary/90 dark:file:bg-dark-accent-secondary dark:hover:file:bg-dark-accent-secondary/90 border border-light-border-default dark:border-dark-border-default rounded-lg bg-light-input-bg dark:bg-dark-input-bg text-light-text-primary dark:text-dark-text-primary cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed transition-colors" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Upload Progress Bar -->
                                                    <div x-show="video.uploading" class="mt-4" x-transition>
                                                        <div class="flex justify-between text-xs font-medium mb-2">
                                                            <span class="text-light-accent-secondary dark:text-dark-accent-secondary flex items-center gap-1">
                                                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                Uploading to cloud...
                                                            </span>
                                                            <span class="text-light-text-secondary dark:text-dark-text-secondary font-bold" x-text="`${video.progress}%`"></span>
                                                        </div>
                                                        <div class="w-full bg-light-bg-tertiary dark:bg-dark-bg-tertiary rounded-full h-2 overflow-hidden">
                                                            <div class="h-2 rounded-full bg-light-accent-secondary dark:bg-dark-accent-secondary transition-all duration-300 progress-glow"
                                                                :style="`width: ${video.progress}%`"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Status Messages -->
                                                    <div x-show="video.status === 'success'" class="mt-3 flex items-center gap-2 text-sm text-light-accent-success dark:text-dark-accent-success" x-transition>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="font-medium">Video uploaded successfully!</span>
                                                    </div>
                                                    <div x-show="video.status === 'error'" class="mt-3 flex items-center gap-2 text-sm text-light-accent-error dark:text-dark-accent-error" x-transition>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="font-medium" x-text="video.errorMessage"></span>
                                                    </div>

                                                    <!-- Hidden Metadata Inputs -->
                                                    <input type="hidden" :name="`sections[${sectionIndex}][videos][${videoIndex}][video_url]`" x-model="video.video_url">
                                                    <input type="hidden" :name="`sections[${sectionIndex}][videos][${videoIndex}][public_id]`" x-model="video.public_id">
                                                    <input type="hidden" :name="`sections[${sectionIndex}][videos][${videoIndex}][original_name]`" x-model="video.original_name">
                                                    <input type="hidden" :name="`sections[${sectionIndex}][videos][${videoIndex}][size]`" x-model="video.size">
                                                    <input type="hidden" :name="`sections[${sectionIndex}][videos][${videoIndex}][mime_type]`" x-model="video.mime_type">
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Add Video Button -->
                                    <button type="button" @click="addVideo(sectionIndex)"
                                        class="w-full py-3 border-2 border-dashed border-light-border-default dark:border-dark-border-default rounded-lg text-sm font-medium text-light-text-muted dark:text-dark-text-muted hover:border-light-accent-secondary dark:hover:border-dark-accent-secondary hover:text-light-accent-secondary dark:hover:text-dark-accent-secondary hover:bg-light-accent-secondary/5 dark:hover:bg-dark-accent-secondary/10 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add Another Video</span>
                                    </button>
                                </div>

                                <!-- Section Status -->
                                <div class="pt-4 border-t border-light-border-subtle dark:border-dark-border-subtle">
                                    <div class="flex items-center gap-2 text-sm"
                                        :class="{
                                            'text-light-accent-success dark:text-dark-accent-success': section.videos.every(v => v.public_id),
                                            'text-light-accent-warning dark:text-dark-accent-warning': section.videos.some(v => v.public_id) && !section.videos.every(v => v.public_id),
                                            'text-light-text-muted dark:text-dark-text-muted': !section.videos.some(v => v.public_id)
                                        }">
                                        <template x-if="section.videos.every(v => v.public_id)">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </template>
                                        <template x-if="!section.videos.every(v => v.public_id)">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </template>
                                        <span x-text="getSectionStatus(section)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="sections.length === 0" x-transition class="text-center py-16">
                <div class="w-16 h-16 mx-auto rounded-xl bg-light-accent-secondary/10 dark:bg-dark-accent-secondary/20 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-light-accent-secondary dark:text-dark-accent-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">No sections yet</h3>
                <p class="text-light-text-muted dark:text-dark-text-muted mb-6 max-w-sm mx-auto">Start building your course by adding your first section with videos.</p>
                <button type="button" @click="addSection()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Your First Section</span>
                </button>
            </div>

            <!-- Bottom Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 z-40 bg-light-bg-secondary/95 dark:bg-dark-bg-secondary/95 backdrop-blur-sm border-t border-light-border-subtle dark:border-dark-border-subtle shadow-lg">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button type="button" @click="addSection()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-light-bg-tertiary dark:bg-dark-bg-tertiary border border-light-border-default dark:border-dark-border-default hover:border-light-accent-secondary dark:hover:border-dark-accent-secondary text-light-text-secondary dark:text-dark-text-secondary rounded-lg transition-colors font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Add Section</span>
                            </button>
                            <button type="button" @click="addMultiple(3)"
                                class="hidden md:inline-flex items-center gap-2 px-4 py-2.5 bg-light-bg-tertiary dark:bg-dark-bg-tertiary border border-light-border-default dark:border-dark-border-default hover:border-light-accent-secondary dark:hover:border-dark-accent-secondary text-light-text-secondary dark:text-dark-text-secondary rounded-lg transition-colors font-medium">
                                <span>Add 3 Sections</span>
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <!-- Upload Status -->
                            <div x-show="isUploading" class="flex items-center gap-2 text-sm text-light-accent-secondary dark:text-dark-accent-secondary">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="font-medium">Uploading...</span>
                            </div>
                            
                            <button type="submit" :disabled="isUploading || sections.length === 0"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="isUploading ? 'bg-light-bg-tertiary dark:bg-dark-bg-tertiary text-light-text-muted dark:text-dark-text-muted' : 'bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-white shadow-md hover:shadow-lg'">
                                <template x-if="!isUploading">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </template>
                                <span x-text="isUploading ? 'Please wait...' : 'Save & Continue'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-ai_chat />
    </main>

    <script>
        function uploadManager() {
            return {
                sections: [],
                courseId: {{ $course->id }},

                createVideo() {
                    return {
                        id: Date.now() + Math.random(),
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
                },

                addSection() {
                    const id = Date.now() + Math.random();
                    this.sections.push({
                        id,
                        title: '',
                        videos: [this.createVideo()]
                    });
                },

                addMultiple(count) {
                    for (let i = 0; i < count; i++) this.addSection();
                },

                removeSection(sectionIndex) {
                    this.sections.splice(sectionIndex, 1);
                },

                addVideo(sectionIndex) {
                    this.sections[sectionIndex].videos.push(this.createVideo());
                },

                removeVideo(sectionIndex, videoIndex) {
                    if (this.sections[sectionIndex].videos.length > 1) {
                        this.sections[sectionIndex].videos.splice(videoIndex, 1);
                    }
                },

                duplicateSection(sectionIndex) {
                    const original = this.sections[sectionIndex];
                    const id = Date.now() + Math.random();
                    const duplicatedVideos = original.videos.map(v => ({
                        ...JSON.parse(JSON.stringify(v)),
                        id: Date.now() + Math.random(),
                        uploading: false,
                        progress: 0,
                        status: 'idle'
                    }));
                    this.sections.splice(sectionIndex + 1, 0, {
                        id,
                        title: original.title,
                        videos: duplicatedVideos
                    });
                },

                getSectionStatus(section) {
                    const totalVideos = section.videos.length;
                    const uploadedVideos = section.videos.filter(v => v.public_id).length;
                    if (uploadedVideos === 0) {
                        return 'Upload videos to continue';
                    } else if (uploadedVideos < totalVideos) {
                        return `${uploadedVideos} of ${totalVideos} videos uploaded`;
                    } else {
                        return 'All videos uploaded - Ready to save';
                    }
                },

                getTotalVideosCount() {
                    return this.sections.reduce((total, section) => total + section.videos.length, 0);
                },

                getUploadedVideosCount() {
                    return this.sections.reduce((total, section) => 
                        total + section.videos.filter(v => v.public_id).length, 0);
                },

                get isUploading() {
                    return this.sections.some(s => s.videos.some(v => v.uploading));
                },

                async handleFileUpload(event, sectionIndex, videoIndex) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const video = this.sections[sectionIndex].videos[videoIndex];
                    video.uploading = true;
                    video.progress = 0;
                    video.status = 'uploading';
                    video.errorMessage = '';
                    video.original_name = file.name;
                    video.size = file.size;
                    video.mime_type = file.type;

                    if (!video.title) {
                        video.title = file.name.replace(/\.[^/.]+$/, '').replace(/[-_]/g, ' ');
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

                        if (!sigData.cloud_name || sigData.cloud_name === 'null') {
                            throw new Error('Cloudinary Cloud Name is missing in configuration.');
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
                                video.progress = Math.round((e.loaded / e.total) * 100);
                            }
                        };

                        xhr.onload = () => {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (xhr.status >= 200 && xhr.status < 300) {
                                    video.video_url = response.secure_url;
                                    video.public_id = response.public_id;
                                    video.status = 'success';
                                    video.uploading = false;
                                } else {
                                    video.status = 'error';
                                    video.errorMessage = response.error?.message || 'Upload failed';
                                    video.uploading = false;
                                }
                            } catch (e) {
                                video.status = 'error';
                                video.errorMessage = 'Invalid response from Cloudinary';
                                video.uploading = false;
                            }
                        };

                        xhr.onerror = () => {
                            video.status = 'error';
                            video.errorMessage = 'Network error. Check your connection.';
                            video.uploading = false;
                        };

                        xhr.onabort = () => {
                            video.status = 'error';
                            video.errorMessage = 'Upload aborted.';
                            video.uploading = false;
                        };

                        xhr.ontimeout = () => {
                            video.status = 'error';
                            video.errorMessage = 'Upload timed out.';
                            video.uploading = false;
                        };

                        xhr.send(formData);

                    } catch (error) {
                        video.status = 'error';
                        video.errorMessage = error.message;
                        video.uploading = false;
                    }
                },

                submitForm() {
                    if (this.isUploading) {
                        alert('Please wait for all videos to finish uploading.');
                        return;
                    }

                    for (let i = 0; i < this.sections.length; i++) {
                        const section = this.sections[i];
                        if (!section.title.trim()) {
                            alert(`Please enter a title for Section ${i + 1}.`);
                            return;
                        }
                        for (let j = 0; j < section.videos.length; j++) {
                            const video = section.videos[j];
                            if (!video.public_id) {
                                alert(`Please upload a video for Section ${i + 1}, Video ${j + 1}.`);
                                return;
                            }
                            if (!video.title.trim()) {
                                alert(`Please enter a title for Section ${i + 1}, Video ${j + 1}.`);
                                return;
                            }
                        }
                    }

                    document.getElementById('sectionsForm').submit();
                }
            };
        }
    </script>
</body>

</html>