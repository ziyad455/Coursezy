<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Courses - Coursezy</title>
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
    <x-studentNav/>


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Browse Courses
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Discover new skills and advance your career with our expert-led courses
            </p>
        </div>

        <!-- Category Filter Section -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter by Category</h2>
            <form method="GET" class="flex flex-wrap gap-3">
                <button type="submit" name="category" value="" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700 hover:border-indigo-700 dark:border-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:hover:border-indigo-600 transition-all duration-200 transform hover:scale-105">
                    All
                </button>
                <button type="submit" name="category" value="programming" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Programming
                </button>
                <button type="submit" name="category" value="design" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Design
                </button>
                <button type="submit" name="category" value="marketing" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Marketing
                </button>
                <button type="submit" name="category" value="data-science" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Data Science
                </button>
                <button type="submit" name="category" value="business" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Business
                </button>
                <button type="submit" name="category" value="music" class="px-4 py-2 text-sm font-medium rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 transform hover:scale-105">
                    Music
                </button>
            </form>
        </div>

        <!-- Course Grid Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Course Card 1 - JavaScript -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="JavaScript course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Programming
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">JavaScript Fundamentals</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Learn the core concepts of JavaScript programming from variables to advanced functions and DOM manipulation.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                 alt="John Smith" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">John Smith</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $119
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 2 - Photoshop -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Photoshop course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Design
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">Adobe Photoshop CC</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Master photo editing, digital art creation, and professional design techniques in Photoshop.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                                 alt="Sarah Johnson" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sarah Johnson</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $129
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 3 - Social Media -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1611162616475-46b635cb6868?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Social media course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Marketing
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">Social Media Strategy</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Build effective social media campaigns and grow your online presence across multiple platforms.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" 
                                 alt="Mike Chen" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Mike Chen</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.7</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $89
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 4 - Data Science -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Data science course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Data Science
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">Data Analysis with Python</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Learn to analyze and visualize data using Python, pandas, matplotlib, and other essential tools.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/women/63.jpg" 
                                 alt="Dr. Lisa Wong" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Dr. Lisa Wong</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $149
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 5 - Project Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Project management course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Business
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">Project Management</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Master Agile, Scrum, and traditional project management methodologies for successful project delivery.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" 
                                 alt="Alex Rodriguez" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Alex Rodriguez</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.6</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $99
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 6 - Music Production -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Music production course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Music
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">Music Production</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Create professional music tracks using digital audio workstations and modern production techniques.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/95.jpg" 
                                 alt="DJ Marcus" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">DJ Marcus</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $79
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 7 - React Development -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="React development course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Programming
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">React.js Complete Guide</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Build modern web applications with React, including hooks, context, and state management techniques.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/55.jpg" 
                                 alt="David Park" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">David Park</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $159
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>

            <!-- Course Card 8 - UI/UX Design -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="UI/UX design course" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                            Design
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2">UI/UX Design Mastery</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                        Learn user interface and experience design from wireframes to high-fidelity prototypes and user testing.
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/women/28.jpg" 
                                 alt="Emma Wilson" 
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Emma Wilson</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            $139
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            View Course
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Section -->
        <div class="text-center mt-12">
            <button class="px-8 py-3 bg-white dark:bg-gray-800 border-2 border-indigo-600 dark:border-indigo-500 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md">
                Load More Courses
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4">
                    Coursezy
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Empowering learners worldwide with quality education
                </p>
                <div class="flex justify-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">About</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Courses</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Instructors</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Contact</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy</a>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        © 2025 Coursezy. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
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

    // Initialize dark mode based on system preference
    function initDarkMode() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            isDarkMode = true;
            document.documentElement.classList.add('dark');
        }
    }

    // Add scroll animations for course cards
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Apply to course cards
        document.querySelectorAll('.transform').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(el);
        });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initDarkMode();
        initScrollAnimations();
    });

    // Add ripple effect to buttons
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
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
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        pointer-events: none;
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
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