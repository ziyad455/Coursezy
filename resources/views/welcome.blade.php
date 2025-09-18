<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coursezy - Learn Anytime, Anywhere</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.7/swiper-bundle.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.7/swiper-bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Coursezy
                    </h1>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:block flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" placeholder="Search for courses..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-300">
                    </div>
                </div>

                <!-- Right Side - Auth Buttons & Dark Mode Toggle -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="relative inline-flex h-8 w-14 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="sr-only">Toggle dark mode</span>
                        <span class="inline-block h-6 w-6 transform rounded-full bg-white dark:bg-gray-900 shadow-lg transition-transform duration-300 translate-x-1 dark:translate-x-7 flex items-center justify-center">
                            <svg class="h-3 w-3 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                            </svg>
                            <svg class="h-3 w-3 text-blue-400 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Auth Buttons -->
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 transition-colors duration-500">
        <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-slate-700/25 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    Learn Anytime, Anywhere with 
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Expert-Led Courses
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    Thousands of courses in programming, design, marketing, and more.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    Get Started
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-purple-200 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-32 h-32 bg-indigo-200 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-2000"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-pink-200 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-4000"></div>
    </section>

<!-- Swiper Image Slider -->
<section class="py-16 bg-white dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Featured Courses</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Discover our most popular and trending courses</p>
        </div>
        
        <div class="swiper courseSwiper">
            <div class="swiper-wrapper">
                <!-- Web Development Course -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Web development code on screen" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 to-blue-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">Complete Web Development</h3>
                            <p class="text-blue-100 text-sm">Master HTML, CSS, JavaScript & React</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.9 (2,341 students)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- UI/UX Design Course -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1541462608143-67571c6738dd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="UI/UX design process" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-purple-900/80 to-pink-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">UI/UX Design Mastery</h3>
                            <p class="text-purple-100 text-sm">From wireframes to prototypes</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.8 (1,892 students)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Digital Marketing Course -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Digital marketing analytics" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-green-900/80 to-teal-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">Digital Marketing Pro</h3>
                            <p class="text-green-100 text-sm">SEO, Social Media & Analytics</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.7 (3,156 students)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Python Programming Course -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Python code on screen" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-orange-900/80 to-red-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">Python Programming</h3>
                            <p class="text-orange-100 text-sm">From basics to data science</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.9 (4,723 students)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Machine Learning Course -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Machine learning visualization" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/80 to-purple-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">Machine Learning</h3>
                            <p class="text-indigo-100 text-sm">AI and deep learning fundamentals</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.8 (1,567 students)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Course - Data Science -->
                <div class="swiper-slide">
                    <div class="relative rounded-xl shadow-lg overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Data science visualization" 
                             class="absolute inset-0 w-full h-full object-cover z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 to-teal-500/30 z-10"></div>
                        <div class="relative z-20 p-8 h-full flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white mb-2">Data Science Bootcamp</h3>
                            <p class="text-teal-100 text-sm">Python, SQL, and machine learning</p>
                            <div class="mt-4 flex items-center">
                                <span class="text-yellow-300 text-sm">★★★★★</span>
                                <span class="text-white text-sm ml-2">4.9 (3,245 students)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation buttons -->
            <div class="swiper-button-next text-white dark:text-indigo-300"></div>
            <div class="swiper-button-prev text-white dark:text-indigo-300"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination mt-8"></div>
        </div>
    </div>
</section>

    <!-- Featured Courses Grid -->
<section class="py-16 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Explore Our Courses</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Find the perfect course to advance your skills</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Course Card 1 - JavaScript -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="JavaScript code on screen" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                 alt="John Smith" 
                                 class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">John Smith</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.8</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Card 2 - Photoshop -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Adobe Photoshop interface" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                                 alt="Sarah Johnson" 
                                 class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sarah Johnson</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.9</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Card 3 - Social Media -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1611162616475-46b635cb6868?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Social media apps on phone" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" 
                                 alt="Mike Chen" 
                                 class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Mike Chen</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.7</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Card 4 - Data Science -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Data visualization charts" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            {{-- Using initials as we don't have actual user object for demo --}}
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs">
                                LW
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Dr. Lisa Wong</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.9</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Card 5 - Project Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Project management board" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs">
                                AR
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Alex Rodriguez</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.6</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Card 6 - Music Production -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 relative">
                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Music production studio" 
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs">
                                DM
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">DJ Marcus</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400 text-sm">★★★★★</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">4.8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 transition-colors duration-300">
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

        // Initialize Swiper
        function initSwiper() {
            const swiper = new Swiper('.courseSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            initSwiper();
        });

        // Handle mobile search toggle
        function toggleMobileSearch() {
            const searchBar = document.getElementById('mobile-search');
            searchBar.classList.toggle('hidden');
        }

        // Add smooth scrolling and animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
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
            
            .swiper-button-next,
            .swiper-button-prev {
                width: 44px !important;
                height: 44px !important;
                margin-top: -22px !important;
                background: rgba(255, 255, 255, 0.9) !important;
                border-radius: 50% !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                transition: all 0.3s ease !important;
            }
            
            .swiper-button-next:hover,
            .swiper-button-prev:hover {
                background: rgba(255, 255, 255, 1) !important;
                transform: scale(1.1) !important;
            }
            
            .dark .swiper-button-next,
            .dark .swiper-button-prev {
                background: rgba(31, 41, 55, 0.9) !important;
            }
            
            .dark .swiper-button-next:hover,
            .dark .swiper-button-prev:hover {
                background: rgba(31, 41, 55, 1) !important;
            }
            
            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 16px !important;
                font-weight: bold !important;
            }
            
            .swiper-pagination-bullet {
                width: 12px !important;
                height: 12px !important;
                opacity: 0.5 !important;
                background: #6366f1 !important;
                transition: all 0.3s ease !important;
            }
            
            .swiper-pagination-bullet-active {
                opacity: 1 !important;
                transform: scale(1.2) !important;
            }
            
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .bg-grid-slate-100 {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.04)'%3e%3cpath d='m0 .5h32m-32 32v-32'/%3e%3c/svg%3e");
            }
            
            .dark .bg-grid-slate-700\/25 {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(148 163 184 / 0.05)'%3e%3cpath d='m0 .5h32m-32 32v-32'/%3e%3c/svg%3e");
            }
        `;
        document.head.appendChild(style);
    </script>
    
    <!-- AI Chat Component -->
    <x-ai_chat />
</body>
</html>
