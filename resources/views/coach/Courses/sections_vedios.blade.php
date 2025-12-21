<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

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
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300"
    x-data="uploadManager()" x-init="addSection()">
    <x-coachNav />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl md:text-3xl font-bold">Create Course Sections</h1>
                <a href="{{ url()->previous() }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Back</a>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Step 2 of 2: Add sections with titles and videos</p>
        </div>

        <!-- Sections Form -->
        <form id="sectionsForm" @submit.prevent="submitForm()"
            action="{{ route('coach.courses.sections.store', $course->id) }}" method="POST" class="space-y-6">
            @csrf

            <div id="sectionsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <template x-for="(section, index) in sections" :key="section.id">
                    <div class="section-card transition-all duration-500" x-transition:enter="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="font-semibold text-lg">Section <span x-text="index + 1"></span></h3>
                                    <button type="button" @click="removeSection(index)"
                                        class="text-gray-500 hover:text-red-600 transition-colors"
                                        title="Remove section">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Title</label>
                                        <input type="text" :name="`sections[${index}][title]`" x-model="section.title"
                                            required placeholder="e.g., Introduction to the Course"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-indigo-500 focus:border-indigo-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Video</label>
                                        <div class="relative group">
                                            <input type="file" accept="video/*"
                                                @change="handleFileUpload($event, index)" :disabled="section.uploading"
                                                class="w-full file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 file:font-medium border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-indigo-500 focus:border-indigo-500" />
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max size depends on
                                                your Cloudinary plan. MP4, MOV, AVI.</p>
                                        </div>

                                        <!-- Upload Progress -->
                                        <div x-show="section.uploading" class="mt-4 space-y-2">
                                            <div class="flex justify-between text-xs font-medium">
                                                <span class="text-indigo-600 dark:text-indigo-400">Uploading...</span>
                                                <span x-text="`${section.progress}%`"
                                                    class="text-gray-600 dark:text-gray-400"></span>
                                            </div>
                                            <div
                                                class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300"
                                                    :style="`width: ${section.progress}%`"></div>
                                            </div>
                                        </div>

                                        <!-- Success/Error Message -->
                                        <div x-show="section.status === 'success'"
                                            class="mt-2 text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Video uploaded successfully</span>
                                        </div>
                                        <div x-show="section.status === 'error'"
                                            class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span x-text="section.errorMessage"></span>
                                        </div>

                                        <!-- Hidden Metadata Inputs -->
                                        <input type="hidden" :name="`sections[${index}][video_url]`"
                                            x-model="section.video_url">
                                        <input type="hidden" :name="`sections[${index}][public_id]`"
                                            x-model="section.public_id">
                                        <input type="hidden" :name="`sections[${index}][original_name]`"
                                            x-model="section.original_name">
                                        <input type="hidden" :name="`sections[${index}][size]`" x-model="section.size">
                                        <input type="hidden" :name="`sections[${index}][mime_type]`"
                                            x-model="section.mime_type">
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                x-text="section.public_id ? 'Ready to save' : 'Upload a video to continue'"></span>
                                        </div>
                                        <button type="button" @click="duplicateSection(index)"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2M16 8h2a2 2 0 012 2v8a2 2 0 01-2 2H10a2 2 0 01-2-2v-2" />
                                            </svg>
                                            <span>Duplicate</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty state -->
            <div x-show="sections.length === 0" class="col-span-full">
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 text-center hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                    <svg class="w-10 h-10 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <h3 class="mt-3 font-semibold">No sections yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Click below to add your first section</p>
                    <button type="button" @click="addSection()"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        <span>Add Section</span>
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <button type="button" @click="addSection()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 text-gray-800 dark:text-gray-100 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Section</span>
                    </button>
                    <button type="button" @click="addMultiple(5)"
                        class="hidden md:inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 text-gray-800 dark:text-gray-100 rounded-lg transition-colors">
                        <span>Add 5</span>
                    </button>
                </div>
                <div class="flex items-center gap-3 sm:pr-32">
                    <button type="submit" :disabled="isUploading || sections.length === 0" class="px-5 py-2.5 rounded-xl font-medium transition-all duration-200
                             shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="isUploading ? 'bg-gray-300 text-gray-500' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                        <span x-text="isUploading ? 'Uploading Videos...' : 'Save & Continue'"></span>
                    </button>
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

                addSection() {
                    const id = Date.now() + Math.random();
                    this.sections.push({
                        id,
                        title: '',
                        uploading: false,
                        progress: 0,
                        status: 'idle', // idle, uploading, success, error
                        errorMessage: '',
                        video_url: '',
                        public_id: '',
                        original_name: '',
                        size: 0,
                        mime_type: ''
                    });
                },

                addMultiple(count) {
                    for (let i = 0; i < count; i++) this.addSection();
                },

                removeSection(index) {
                    this.sections.splice(index, 1);
                },

                duplicateSection(index) {
                    const original = this.sections[index];
                    const id = Date.now() + Math.random();
                    this.sections.splice(index + 1, 0, {
                        ...JSON.parse(JSON.stringify(original)),
                        id,
                        uploading: false,
                        progress: 0,
                        status: 'idle',
                        // Clear upload data if we want to force re-upload, or keep it if it's already successful
                    });
                },

                get isUploading() {
                    return this.sections.some(s => s.uploading);
                },

                async handleFileUpload(event, index) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const section = this.sections[index];
                    section.uploading = true;
                    section.progress = 0;
                    section.status = 'uploading';
                    section.errorMessage = '';
                    section.original_name = file.name;
                    section.size = file.size;
                    section.mime_type = file.type;

                    try {
                        // 1. Get Signature from Backend
                        const folder = `coursezy/courses/${this.courseId}/sections`;
                        const signatureParams = {
                            folder,
                            resource_type: 'video',
                            timestamp: Math.floor(Date.now() / 1000)
                        };

                        console.log('Fetching signature with params:', signatureParams);
                        const sigResponse = await fetch(`{{ route('coach.cloudinary.signature') }}?${new URLSearchParams(signatureParams)}`);
                        const sigData = await sigResponse.json();
                        console.log('Received signature data:', sigData);

                        if (!sigResponse.ok) {
                            throw new Error(sigData.error || 'Failed to get upload signature');
                        }

                        if (!sigData.cloud_name || sigData.cloud_name === 'null') {
                            throw new Error('Cloudinary Cloud Name is missing in configuration.');
                        }

                        // 2. Prepare FormData for Cloudinary
                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('api_key', sigData.api_key);
                        formData.append('timestamp', sigData.timestamp);
                        formData.append('signature', sigData.signature);
                        formData.append('folder', folder);

                        // 3. XHR to track progress
                        const uploadUrl = `https://api.cloudinary.com/v1_1/${sigData.cloud_name}/video/upload`;
                        console.log('Uploading to:', uploadUrl);

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', uploadUrl);

                        xhr.upload.onprogress = (e) => {
                            if (e.lengthComputable) {
                                section.progress = Math.round((e.loaded / e.total) * 100);
                            }
                        };

                        xhr.onload = () => {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (xhr.status >= 200 && xhr.status < 300) {
                                    console.log(`Upload success for section ${index + 1}:`, response);
                                    section.video_url = response.secure_url;
                                    section.public_id = response.public_id;
                                    section.status = 'success';
                                    section.uploading = false;
                                } else {
                                    console.error(`Upload failed for section ${index + 1} with status ${xhr.status}:`, response);
                                    section.status = 'error';
                                    section.errorMessage = response.error?.message || 'Upload failed';
                                    section.uploading = false;
                                }
                            } catch (e) {
                                console.error(`Error parsing response for section ${index + 1}:`, e);
                                section.status = 'error';
                                section.errorMessage = 'Invalid response from Cloudinary';
                                section.uploading = false;
                            }
                        };

                        xhr.onerror = (e) => {
                            console.error(`Network error for section ${index + 1}:`, e);
                            section.status = 'error';
                            section.errorMessage = 'Network error: Request failed. Check your connection or CORS settings.';
                            section.uploading = false;
                        };

                        xhr.onabort = () => {
                            console.warn(`Upload aborted for section ${index + 1}`);
                            section.status = 'error';
                            section.errorMessage = 'Upload aborted.';
                            section.uploading = false;
                        };

                        xhr.ontimeout = () => {
                            console.error(`Upload timed out for section ${index + 1}`);
                            section.status = 'error';
                            section.errorMessage = 'Upload timed out.';
                            section.uploading = false;
                        };

                        xhr.send(formData);

                    } catch (error) {
                        console.error('Upload manager error:', error);
                        section.status = 'error';
                        section.errorMessage = error.message;
                        section.uploading = false;
                    }
                },

                submitForm() {
                    if (this.isUploading) {
                        alert('Please wait for all videos to finish uploading.');
                        return;
                    }

                    // Check if all sections have a public_id
                    const incomplete = this.sections.find(s => !s.public_id);
                    if (incomplete) {
                        alert('Please upload a video for all sections.');
                        return;
                    }

                    document.getElementById('sectionsForm').submit();
                }
            };
        }
    </script>
</body>

</html>