<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-in': 'slideIn 0.3s ease-out forwards',
                        'bounce-in': 'bounceIn 0.5s ease-out forwards',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-coachNav/>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Page Header with Breadcrumbs -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('coach.courses.index') }}" class="inline-flex items-center text-sm font-medium text-light-text-muted hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-dark-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Edit Course</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Enhanced Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2 animate-fade-in-up">
                        Edit Course
                    </h1>
                    <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary animate-fade-in-up" style="animation-delay: 0.1s;">
                        Update your course information and settings
                    </p>
                </div>
                <!-- Course Status Badge -->
                <div class="hidden md:flex items-center space-x-3">
                    <div class="flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $course->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                        <div class="w-2 h-2 rounded-full mr-2
                            {{ $course->status === 'published' ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                        {{ ucfirst($course->status) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Course Form with Sidebar Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-dark-text-primary flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Course Details
                        </h2>
                    </div>
                    
                    <div class="p-8">
                        <form id="courseForm" class="space-y-8" action="{{ route('coach.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <!-- Course Title with Character Counter -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Course Title *</label>
                                <div class="relative">
                                    <input type="text" id="title" name="title" required maxlength="100"
                                           class="w-full px-4 py-4 border border-light-border-default dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-all duration-300 text-lg font-medium"
                                           placeholder="e.g., Complete React.js Masterclass" 
                                           value="{{ old('title', $course->title) }}"
                                           oninput="updateCharCount('title', 'title-count', 100)">
                                    <div class="absolute top-2 right-3 text-xs text-dark-text-secondary">
                                        <span id="title-count">{{ strlen($course->title) }}</span>/100
                                    </div>
                                </div>
                                @error('title')
                                    <div class="flex items-center mt-2 text-sm text-red-600 animate-slide-in">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Course Description with Rich Formatting -->
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Course Description *</label>
                                <div class="relative">
                                    <textarea id="description" name="description" rows="6" required maxlength="2000"
                                              class="w-full px-4 py-4 border border-light-border-default dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-all duration-300 resize-none"
                                              placeholder="Describe what students will learn in this course. Include key topics, learning outcomes, and prerequisites..."
                                              oninput="updateCharCount('description', 'desc-count', 2000)">{{ old('description', $course->description) }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-dark-text-secondary">
                                        <span id="desc-count">{{ strlen($course->description) }}</span>/2000
                                    </div>
                                </div>
                                @error('description')
                                    <div class="flex items-center mt-2 text-sm text-red-600 animate-slide-in">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Enhanced Price Input with Validation -->
                            <div class="space-y-2">
                                <label for="price" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Course Price *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-light-text-muted dark:text-dark-text-muted text-lg font-medium">$</span>
                                    <input type="number" id="price" name="price" step="0.01" min="0" max="9999.99" required
                                           class="w-full pl-10 pr-4 py-4 border border-light-border-default dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-all duration-300 text-lg font-medium"
                                           placeholder="99.99" 
                                           value="{{ old('price', $course->price) }}"
                                           oninput="validatePrice(this)">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <span class="text-xs text-dark-text-secondary">USD</span>
                                    </div>
                                </div>
                                <div id="price-feedback" class="text-xs text-light-text-muted dark:text-dark-text-muted"></div>
                                @error('price')
                                    <div class="flex items-center mt-2 text-sm text-red-600 animate-slide-in">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Enhanced Category Selector -->
                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Category *</label>
                                <select id="category_id" name="category_id" required
                                        class="w-full px-4 py-4 border border-light-border-default dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary transition-all duration-300 text-lg appearance-none bg-white dark:bg-gray-700">
                                    <option value="">🎯 Select a category</option>
                                    <option value="1" {{ old('category_id', $course->category_id) == '1' ? 'selected' : '' }}>💻 Web Development</option>
                                    <option value="2" {{ old('category_id', $course->category_id) == '2' ? 'selected' : '' }}>📱 Mobile Development</option>
                                    <option value="3" {{ old('category_id', $course->category_id) == '3' ? 'selected' : '' }}>📊 Data Science</option>
                                    <option value="4" {{ old('category_id', $course->category_id) == '4' ? 'selected' : '' }}>🎨 Design & UI/UX</option>
                                    <option value="5" {{ old('category_id', $course->category_id) == '5' ? 'selected' : '' }}>💼 Business</option>
                                    <option value="6" {{ old('category_id', $course->category_id) == '6' ? 'selected' : '' }}>📈 Digital Marketing</option>
                                    <option value="7" {{ old('category_id', $course->category_id) == '7' ? 'selected' : '' }}>📸 Photography</option>
                                    <option value="8" {{ old('category_id', $course->category_id) == '8' ? 'selected' : '' }}>🎵 Music</option>
                                    <option value="9" {{ old('category_id', $course->category_id) == '9' ? 'selected' : '' }}>🎯 Other</option>
                                </select>
                                @error('category_id')
                                    <div class="flex items-center mt-2 text-sm text-red-600 animate-slide-in">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Enhanced Status Selector -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Course Status *</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="draft" 
                                               {{ old('status', $course->status) == 'draft' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="p-4 border-2 border-light-border-default dark:border-dark-border-default rounded-xl transition-all duration-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 hover:border-yellow-400">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">Draft</h3>
                                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Save for later editing</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="published" 
                                               {{ old('status', $course->status) == 'published' ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="p-4 border-2 border-light-border-default dark:border-dark-border-default rounded-xl transition-all duration-300 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">Published</h3>
                                                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">Make it live for students</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('status')
                                    <div class="flex items-center mt-2 text-sm text-red-600 animate-slide-in">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Enhanced Form Actions -->
                            <div class="flex items-center justify-between pt-8 border-t border-light-border-default dark:border-dark-border-default">
                                <button type="button" onclick="showDeleteConfirmation()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Course
                                </button>
                                
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('coach.courses.index') }}" class="px-6 py-3 border-2 border-light-border-default dark:border-dark-border-default text-light-text-secondary dark:text-dark-text-secondary font-medium rounded-xl hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-all duration-300">
                                        Cancel
                                    </a>
                                    <button type="submit" id="submit-btn" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-dark-text-primary font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span id="submit-text">Update Course</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Current Thumbnail -->
                @if($course->thumbnail)
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-4 py-3">
                        <h3 class="text-sm font-semibold text-dark-text-primary">Current Thumbnail</h3>
                    </div>
                    <div class="p-4">
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current thumbnail" class="w-full h-32 object-cover rounded-lg shadow-md">
                        <div class="mt-3 flex items-center text-xs text-light-text-muted dark:text-dark-text-muted">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Active thumbnail
                        </div>
                    </div>
                </div>
                @endif

                <!-- Enhanced Thumbnail Upload -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-4 py-3">
                        <h3 class="text-sm font-semibold text-dark-text-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Update Thumbnail
                        </h3>
                    </div>
                    <div class="p-4">
                        <div id="upload-area" class="relative border-2 border-light-border-default dark:border-dark-border-default border-dashed rounded-xl p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-all duration-300 cursor-pointer group">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden" form="courseForm">
                            <div id="upload-content" class="space-y-3">
                                <div class="mx-auto w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 transition-colors">
                                    <svg class="w-6 h-6 text-dark-text-secondary group-hover:text-indigo-500 dark:group-hover:text-indigo-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                        <span class="font-medium text-indigo-600 dark:text-indigo-400">Click to upload</span><br>
                                        or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-light-text-muted mt-1">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            <div id="image-preview" class="hidden space-y-3">
                                <img id="preview-img" src="" alt="Preview" class="mx-auto h-32 w-full object-cover rounded-lg">
                                <div class="text-center">
                                    <p id="file-name" class="text-sm text-light-text-secondary dark:text-dark-text-secondary"></p>
                                    <button type="button" id="remove-image" class="mt-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm transition-colors">Remove</button>
                                </div>
                            </div>
                        </div>
                        @error('thumbnail')
                            <div class="flex items-center mt-3 text-sm text-red-600 animate-slide-in">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Course Statistics -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-3">
                        <h3 class="text-sm font-semibold text-dark-text-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Course Statistics
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Students</span>
                            </div>
                            <span class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary">{{ $course->enrollments_count ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Rating</span>
                            </div>
                            <span class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary">{{ $course->average_rating ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Views</span>
                            </div>
                            <span class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary">{{ $course->views_count ?? 0 }}</span>
                        </div>
                        
                        <div class="pt-3 border-t border-light-border-default dark:border-dark-border-default">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Created</span>
                                <span class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">{{ $course->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="bg-gradient-to-r from-orange-500 to-red-600 px-4 py-3">
                        <h3 class="text-sm font-semibold text-dark-text-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors group">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">Preview Course</span>
                        </a>
                        
                        <a href="{{ route('coach.courses.manage-sections', $course->id) }}" class="flex items-center p-3 rounded-lg hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors group">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/50 transition-colors">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">Manage Lessons</span>
                        </a>
                        
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors group">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">View Analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Enhanced Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-light-bg-secondary dark:bg-dark-bg-secondary">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.832-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-light-text-primary dark:text-dark-text-primary mt-4">Delete Course</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">
                        Are you sure you want to delete this course? This action cannot be undone and all enrolled students will lose access.
                    </p>
                </div>
                <div class="flex items-center justify-center space-x-4 mt-6">
                    <button onclick="hideDeleteConfirmation()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-light-text-primary dark:text-dark-text-primary text-sm font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('coach.courses.destroy', $course) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-dark-text-primary text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Delete Course
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="success-toast" class="hidden fixed top-4 right-4 bg-green-500 text-dark-text-primary px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce-in">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Course updated successfully!</span>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Character counter function
        function updateCharCount(inputId, countId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            const currentLength = input.value.length;
            
            counter.textContent = currentLength;
            
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-dark-text-secondary');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-dark-text-secondary');
            }
        }

        // Price validation function
        function validatePrice(input) {
            const value = parseFloat(input.value);
            const feedback = document.getElementById('price-feedback');
            
            if (value === 0) {
                feedback.textContent = 'Free course';
                feedback.className = 'text-xs text-green-600 dark:text-green-400';
            } else if (value > 0 && value < 10) {
                feedback.textContent = 'Budget-friendly pricing';
                feedback.className = 'text-xs text-blue-600 dark:text-blue-400';
            } else if (value >= 10 && value < 100) {
                feedback.textContent = 'Standard pricing';
                feedback.className = 'text-xs text-light-text-muted dark:text-dark-text-muted';
            } else if (value >= 100) {
                feedback.textContent = 'Premium course pricing';
                feedback.className = 'text-xs text-purple-600 dark:text-purple-400';
            } else {
                feedback.textContent = '';
            }
        }

        // Delete confirmation modal
        function showDeleteConfirmation() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function hideDeleteConfirmation() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        // File upload handling
        document.addEventListener('DOMContentLoaded', function() {
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

            // Handle file selection
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFilePreview(file);
                }
            });

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            });

            uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        thumbnailInput.files = files;
                        handleFilePreview(file);
                    }
                }
            });

            // Handle file preview
            function handleFilePreview(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        fileName.textContent = file.name;
                        uploadContent.classList.add('hidden');
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Remove image
            if (removeBtn) {
                removeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    thumbnailInput.value = '';
                    uploadContent.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                });
            }

            // Form submission with loading state
            document.getElementById('courseForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                
                submitBtn.disabled = true;
                submitText.textContent = 'Updating...';
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                
                // Add loading spinner
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-dark-text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating Course...
                `;
            });

            // Initialize character counters
            updateCharCount('title', 'title-count', 100);
            updateCharCount('description', 'desc-count', 2000);
            
            // Initialize price validation
            const priceInput = document.getElementById('price');
            if (priceInput.value) {
                validatePrice(priceInput);
            }
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
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes bounceIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }
        
        .animate-bounce-in {
            animation: bounceIn 0.5s ease-out forwards;
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

        /* Enhanced form focus states */
        input:focus, textarea:focus, select:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.1);
        }
        
        /* Button hover effects */
        button:hover:not(:disabled) {
            transform: translateY(-2px);
        }
        
        /* Modal backdrop blur effect */
        #delete-modal {
            backdrop-filter: blur(4px);
        }
    </style>
</body>
</html>