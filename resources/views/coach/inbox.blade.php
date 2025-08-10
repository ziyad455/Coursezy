<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Coursezy</title>
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
    <x-coachNav/>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Messages
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Stay connected with your students and colleagues
            </p>
        </div>

        <!-- Pinned Conversation Section -->
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">
                Pinned
            </h2>
            <a href="/ai" class="block bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700 p-4 mb-6 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <div class="flex items-center space-x-4">
                    <!-- AI Assistant Icon -->
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <!-- Pin indicator -->
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 dark:bg-yellow-400 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <!-- Online status -->
                        <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                    </div>
                    
                    <!-- Message content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                AI Assistant
                            </h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Online
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                            I'm here to help you with course creation, student management, and more...
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Regular Conversations Section -->
        <div>
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">
                Recent Conversations
            </h2>
            
            <div class="space-y-2">
                <!-- Contact 1: Sarah Johnson -->
                <a href="/conversation/1" class="block bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" 
                                 src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&h=100&fit=crop&crop=face" 
                                 alt="Sarah Johnson">
                            <!-- Online status -->
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                        
                        <!-- Message content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                    Sarah Johnson
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        2h ago
                                    </span>
                                    <!-- Unread indicator -->
                                    <div class="w-3 h-3 bg-indigo-600 dark:bg-indigo-500 rounded-full"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                Thank you for the feedback on my React assignment! Could you please...
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Contact 2: Mike Chen -->
                <a href="/conversation/2" class="block bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" 
                                 src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" 
                                 alt="Mike Chen">
                            <!-- Offline status -->
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-gray-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                        
                        <!-- Message content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                    Mike Chen
                                </h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    1d ago
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                The JavaScript course is amazing! Just finished module 3 and...
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Contact 3: Emma Wilson -->
                <a href="/conversation/3" class="block bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" 
                                 src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face" 
                                 alt="Emma Wilson">
                            <!-- Online status -->
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                        
                        <!-- Message content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                    Emma Wilson
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        3d ago
                                    </span>
                                    <!-- Unread indicator -->
                                    <div class="w-3 h-3 bg-indigo-600 dark:bg-indigo-500 rounded-full"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                Hi! I'm having trouble with the Photoshop layers exercise. When I...
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Add scroll animations for conversation cards
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

            // Apply to conversation cards
            document.querySelectorAll('a[href^="/conversation"]').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                el.style.transitionDelay = `${index * 100}ms`;
                observer.observe(el);
            });
        }

        // Add ripple effect to conversation links
        function addRippleEffect() {
            const conversationLinks = document.querySelectorAll('a[href^="/conversation"]');
            conversationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
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
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initScrollAnimations();
            addRippleEffect();
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
            background: rgba(99, 102, 241, 0.2);
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