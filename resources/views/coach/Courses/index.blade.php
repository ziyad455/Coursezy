<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Overview - Coursezy</title>
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
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <!-- Navigation Bar -->
    <x-coachNav/>


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Your Courses
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Manage and track your course portfolio
            </p>
        </div>

        <!-- Create New Course Button -->
        <div class="mb-8">
            <a href="{{ route('coach.courses.add') }}" id="create-course-btn" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Create New Course
            </a>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Course Card 1 -->
            <div class="course-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Course Thumbnail -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=400&h=200&fit=crop" alt="React.js Complete Guide" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="status-badge px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">Active</span>
                    </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">React.js Complete Guide</h3>
                    
                    <!-- Course Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                847 students
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                24 lessons
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">4.9</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <button class="edit-btn flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit
                        </button>
                        <button class="view-btn flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            View
                        </button>
                        <button class="delete-btn px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 2 -->
            <div class="course-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Course Thumbnail -->
                <div class="h-48 bg-gradient-to-r from-green-500 to-teal-600 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1627398242454-45a1465c2479?w=400&h=200&fit=crop" alt="JavaScript Fundamentals" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="status-badge px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">Active</span>
                    </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">JavaScript Fundamentals</h3>
                    
                    <!-- Course Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                652 students
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                18 lessons
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">4.7</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <button class="edit-btn flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit
                        </button>
                        <button class="view-btn flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            View
                        </button>
                        <button class="delete-btn px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 3 -->
            <div class="course-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Course Thumbnail -->
                <div class="h-48 bg-gradient-to-r from-purple-500 to-pink-600 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=200&fit=crop" alt="Adobe Photoshop CC" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="status-badge px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">Draft</span>
                    </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Adobe Photoshop CC</h3>
                    
                    <!-- Course Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                423 students
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                15 lessons
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">4.8</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <button class="edit-btn flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit
                        </button>
                        <button class="view-btn flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            View
                        </button>
                        <button class="delete-btn px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 4 -->
            <div class="course-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Course Thumbnail -->
                <div class="h-48 bg-gradient-to-r from-indigo-500 to-blue-600 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=200&fit=crop" alt="Python for Beginners" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="status-badge px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">Active</span>
                    </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Python for Beginners</h3>
                    
                    <!-- Course Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                925 students
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                32 lessons
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">4.6</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <button class="edit-btn flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit
                        </button>
                        <button class="view-btn flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            View
                        </button>
                        <button class="delete-btn px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Course Modal -->
        <div id="create-course-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Create New Course</h3>
                        
                        <form id="course-form">
                            <div class="mb-4">
                                <label for="course-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Title</label>
                                <input type="text" id="course-title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="course-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea id="course-description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="course-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select id="course-category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    <option>Programming</option>
                                    <option>Design</option>
                                    <option>Business</option>
                                    <option>Marketing</option>
                                    <option>Photography</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="course-status" value="draft" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Draft</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="course-status" value="active" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" id="cancel-course-btn" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Delete Course</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this course? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                        <button type="button" id="cancel-delete-btn" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="button" id="confirm-delete-btn" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Toggle dark mode
        function toggleDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        }

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Modal handling
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const createCourseBtn = document.getElementById('create-course-btn');
            const createCourseModal = document.getElementById('create-course-modal');
            const cancelCourseBtn = document.getElementById('cancel-course-btn');
            const courseForm = document.getElementById('course-form');
            
            const deleteModal = document.getElementById('delete-modal');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            // Current course to delete
            let courseToDelete = null;

            // Event listeners
            darkModeToggle.addEventListener('click', toggleDarkMode);
            mobileMenuButton.addEventListener('click', toggleMobileMenu);

            // Create course modal
            createCourseBtn.addEventListener('click', () => {
                createCourseModal.classList.remove('hidden');
            });

            cancelCourseBtn.addEventListener('click', () => {
                createCourseModal.classList.add('hidden');
            });

            courseForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // Here you would typically handle form submission to your backend
                alert('Course created successfully!');
                createCourseModal.classList.add('hidden');
                courseForm.reset();
            });

            // Delete course modal
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    courseToDelete = e.target.closest('.course-card');
                    deleteModal.classList.remove('hidden');
                });
            });

            cancelDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
                courseToDelete = null;
            });

            confirmDeleteBtn.addEventListener('click', () => {
                if (courseToDelete) {
                    courseToDelete.remove();
                    deleteModal.classList.add('hidden');
                    courseToDelete = null;
                    // Here you would typically send a request to your backend to delete the course
                    alert('Course deleted successfully!');
                }
            });

            // Close modals when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === createCourseModal) {
                    createCourseModal.classList.add('hidden');
                }
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                    courseToDelete = null;
                }
            });

            // View and Edit buttons (just for demo)
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    alert('View course clicked!');
                });
            });

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    alert('Edit course clicked!');
                });
            });

            // Add animation to course cards
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fade-in-up');
            });
        });
    </script>
</body>
</html>