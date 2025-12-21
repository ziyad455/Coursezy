<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course - Coursezy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-coachNav/>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                Create New Course
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Share your knowledge with the world
            </p>
            <a href="/ai" 
            class="inline-block text-md text-blue-600 dark:text-blue-400 font-medium mt-2 hover:underline">
                💡 Go to the chat bot to assist you when creating a new course
            </a>
        </div>


        <!-- Course Form -->
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle p-8">
            <form id="courseForm" class="space-y-6" action="{{ route('coach.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Course Title *</label>
                    <input type="text" id="title" name="title" required 
                           class="w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-colors"
                           placeholder="e.g., Complete React.js Masterclass" value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-colors"
                              placeholder="Describe what students will learn in this course...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Category *</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-colors">
                        <option value="">Select a category</option>
                        <option value="1" {{ old('category_id') == '1' ? 'selected' : '' }}>Web Development</option>
                        <option value="2" {{ old('category_id') == '2' ? 'selected' : '' }}>Mobile Apps</option>
                        <option value="3" {{ old('category_id') == '3' ? 'selected' : '' }}>Data Science</option>
                        <option value="4" {{ old('category_id') == '4' ? 'selected' : '' }}>Design</option>
                        <option value="5" {{ old('category_id') == '5' ? 'selected' : '' }}>Marketing</option>
                        <option value="6" {{ old('category_id') == '6' ? 'selected' : '' }}>Business</option>
                        <option value="7" {{ old('category_id') == '7' ? 'selected' : '' }}>Photography</option>
                        <option value="8" {{ old('category_id') == '8' ? 'selected' : '' }}>Music</option>
                        <option value="9" {{ old('category_id') == '9' ? 'selected' : '' }}>Health & Fitness</option>
                        <option value="10" {{ old('category_id') == '10' ? 'selected' : '' }}>Personal Development</option>
                        <option value="11" {{ old('category_id') == '11' ? 'selected' : '' }}>Artificial Intelligence</option>
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Course Thumbnail *</label>
                    <div id="upload-area" class="relative border-2 border-light-border-default dark:border-dark-border-default border-dashed rounded-lg p-6 text-center hover:border-indigo-500 transition-colors cursor-pointer">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden">
                        <div id="upload-content">
                            <svg class="mx-auto h-12 w-12 text-dark-text-secondary" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-light-text-muted">PNG, JPG up to 5MB</p>
                        </div>
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-h-32 mx-auto rounded-lg">
                            <p id="file-name" class="mt-2 text-sm text-light-text-secondary dark:text-dark-text-secondary"></p>
                            <button type="button" id="remove-image" class="mt-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">Remove</button>
                        </div>
                    </div>
                    @error('thumbnail')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">Price ($) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-light-text-muted dark:text-dark-text-muted text-sm">$</span>
                        </div>
                        <input type="number" id="price" name="price" min="0" step="0.01" required
                               class="w-full pl-8 pr-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-colors"
                               placeholder="29.99" value="{{ old('price') }}">
                    </div>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Status -->
                <div>
                    <label class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-3">Course Status</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center p-4 border border-light-border-default dark:border-dark-border-default rounded-lg cursor-pointer hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                            <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">Draft</span>
                                <span class="block text-xs text-light-text-muted dark:text-dark-text-muted">Save for later</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-light-border-default dark:border-dark-border-default rounded-lg cursor-pointer hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                            <input type="radio" name="status" value="published" {{ old('status', 'published') == 'published' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">Published</span>
                                <span class="block text-xs text-light-text-muted dark:text-dark-text-muted">Make it live</span>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-light-border-default dark:border-dark-border-default">
                    <button type="button" onclick="cancelForm()" 
                            class="px-6 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </main>

              <x-ai_chat />

    <!-- Scripts -->
    <script>
        // Dark mode toggle functionality


        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Initialize dark mode based on system preference
        function initDarkMode() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                isDarkMode = true;
                document.documentElement.classList.add('dark');
            }
        }

        // Cancel form function
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                // Redirect to courses overview page
                window.history.back();
            }
        }

        // File upload handling
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            
            const uploadArea = document.getElementById('upload-area');
            const thumbnailInput = document.getElementById('thumbnail');
            const uploadContent = document.getElementById('upload-content');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-image');

            // Click to upload
            uploadArea.addEventListener('click', () => {
                thumbnailInput.click();
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('border-indigo-500');
            });

            uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });

            // File input change
            thumbnailInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFile(e.target.files[0]);
                }
            });

            // Handle file preview
            function handleFile(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        fileName.textContent = file.name;
                        uploadContent.classList.add('hidden');
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Remove image
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                thumbnailInput.value = '';
                uploadContent.classList.remove('hidden');
                imagePreview.classList.add('hidden');
            });

            // Form submission
            document.getElementById('courseForm').addEventListener('submit', function(e) {
                const thumbnailInput = document.getElementById('thumbnail');
                
                // Check if thumbnail is required and not selected
                if (!thumbnailInput.files.length) {
                    e.preventDefault();
                    alert('Please select a course thumbnail image.');
                    return false;
                }
            });
        });
    </script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</body>
</html>
