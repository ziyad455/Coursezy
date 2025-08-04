<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-coachNav/>


    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Create New Course
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Share your knowledge with the world
            </p>
        </div>

        <!-- Course Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-8">
            <form id="courseForm" class="space-y-6" method="POST" action="{{ route('courses.create') }}">
                @csrf
                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Title *</label>
                    <input type="text" id="title" name="title" required 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                           placeholder="e.g., Complete React.js Masterclass">
                </div>

                <!-- Course Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                              placeholder="Describe what students will learn in this course..."></textarea>
                </div>

                <!-- Course Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        <option value="">Select a category</option>
                        <option value="web-development">Web Development</option>
                        <option value="mobile-development">Mobile Development</option>
                        <option value="data-science">Data Science</option>
                        <option value="design">Design & UI/UX</option>
                        <option value="business">Business</option>
                        <option value="marketing">Digital Marketing</option>
                        <option value="photography">Photography</option>
                        <option value="music">Music</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Course Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Thumbnail *</label>
                    <div id="upload-area" class="relative border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg p-6 text-center hover:border-indigo-500 transition-colors cursor-pointer">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden" required>
                        <div id="upload-content">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG up to 5MB</p>
                        </div>
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-h-32 mx-auto rounded-lg">
                            <p id="file-name" class="mt-2 text-sm text-gray-600 dark:text-gray-400"></p>
                            <button type="button" id="remove-image" class="mt-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">Remove</button>
                        </div>
                    </div>
                </div>

                <!-- Course Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price ($) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                        </div>
                        <input type="number" id="price" name="price" min="0" step="0.01" required
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                               placeholder="29.99">
                    </div>
                </div>

                <!-- Course Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Course Status</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <input type="radio" name="status" value="draft" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Draft</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Save for later</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <input type="radio" name="status" value="published" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Published</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Make it live</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="cancelForm()" 
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg">
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Dark mode toggle functionality
        let isDarkMode = false;
        
        function toggleDarkMode() {
            const html = document.documentElement;
            isDarkMode = !isDarkMode;
            
            if (isDarkMode) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

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
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const courseData = Object.fromEntries(formData);
                
                // Here you would send the data to your backend
                console.log('Course data:', courseData);
                
                // Show success message
                alert('Course created successfully!');
                
                // Redirect or reset form
                // window.location.href = 'courses-overview.html';
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
            background: #94a3b8;background: #94a3b8;
}
</style>
</body>
</html>
