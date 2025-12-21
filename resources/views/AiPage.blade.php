<meta name="csrf-token" content="{{ csrf_token() }}">
<title>AI Assistant - Coursezy</title>
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chat.js'])
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300 h-screen flex flex-col">
    @if ($user->role == "coach")
        <x-coachNav />

    @else
        <x-studentNav />

    @endif

    <!-- Chat Header -->
    <div
        class="bg-light-bg-secondary dark:bg-dark-bg-secondary shadow-md border-b border-light-border-subtle dark:border-dark-border-subtle px-4 sm:px-6 lg:px-8 py-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4">
                <!-- Back Button -->
                <button onclick="history.back()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-light-text-secondary dark:text-dark-text-secondary" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- AI Assistant Info -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div
                            class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary">AI
                            Assistant</h1>
                        <p class="text-sm text-green-600 dark:text-green-400">Online</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div id="chatContainer" data-user-id="{{ Auth::id() }}"
        class="flex-1 flex flex-col max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        <!-- Messages Area -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto py-6 space-y-4">
            <!-- Welcome Message -->
            <div class="flex justify-start mb-6 animate-fade-in-up welcome-message">
                <div class="flex items-start space-x-3 max-w-4xl">
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div
                        class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md p-4 max-w-md border border-light-border-subtle dark:border-dark-border-subtle">
                        <p class="text-light-text-primary dark:text-dark-text-primary">Hello
                            <b>{{ strtoupper($user->name) }}!</b> I'm your AI assistant. I'm here to help you with
                            course creation, student management, analytics, and any other questions you might have about
                            Coursezy. How can I assist you today?</p>
                        <span class="text-xs text-light-text-muted dark:text-dark-text-muted mt-2 block">Just now</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden User Avatar Source for chat.js -->
        <div class="hidden user-avatar-source">
            <x-profile-photo :user="Auth::user()" size="md" />
        </div>

        <!-- Input Area -->
        <div
            class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle p-4 mb-6">
            <div class="flex space-x-3">
                <input type="text" id="messageInput" placeholder="Type your message..."
                    class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-dark-border-default rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent text-light-text-primary dark:text-dark-text-primary placeholder-gray-500 dark:placeholder-gray-400 transition-colors"
                    maxlength="1000">
                <button id="sendButton"
                    class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator"
        class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-xl p-6 border border-light-border-subtle dark:border-dark-border-subtle z-50">
        <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-indigo-600 border-t-transparent"></div>
            <span class="text-light-text-secondary dark:text-dark-text-secondary font-medium">AI is thinking...</span>
        </div>
    </div>

</body>

</html>