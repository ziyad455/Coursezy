<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Assistant - Coursezy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 h-screen flex flex-col">
@if ($user->role == "coach")
<x-coachNav/>

@else
<x-studentNav/>
    
@endif

    <!-- Chat Header -->
    <div class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-100 dark:border-gray-700 px-4 sm:px-6 lg:px-8 py-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center space-x-4">
                <!-- Back Button -->
                <button onclick="history.back()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <!-- AI Assistant Info -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">AI Assistant</h1>
                        <p class="text-sm text-green-600 dark:text-green-400">Online</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="flex-1 flex flex-col max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        <!-- Messages Area -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto py-6 space-y-4">
            <!-- Welcome Message -->
            <div class="flex justify-start mb-6 animate-fade-in-up welcome-message">
                <div class="flex items-start space-x-3 max-w-4xl">
                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 max-w-md border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-900 dark:text-gray-100">Hello <b> @php echo strtoupper($user->name); @endphp!</b> I'm your AI assistant. I'm here to help you with course creation, student management, analytics, and any other questions you might have about Coursezy. How can I assist you today?</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-2 block">Just now</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <div class="flex space-x-3">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Type your message..." 
                    class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 transition-colors"
                    maxlength="1000"
                >
                <button 
                    id="sendButton" 
                    class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                >
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-100 dark:border-gray-700 z-50">
        <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-2 border-indigo-600 border-t-transparent"></div>
            <span class="text-gray-700 dark:text-gray-300 font-medium">AI is thinking...</span>
        </div>
    </div>

    <script>
        // Configuration - Replace these with your actual API details
        const API_URL = 'YOUR_API_ENDPOINT_HERE';
        const API_KEY = 'YOUR_API_KEY_HERE';

        // DOM Elements
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const loadingIndicator = document.getElementById('loadingIndicator');

        // State
        let isLoading = false;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            messageInput.focus();
            scrollToBottom();
        });

        // Event Listeners
        sendButton.addEventListener('click', handleSendMessage);
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage();
            }
        });

        // Handle sending messages
        async function handleSendMessage() {
            const message = messageInput.value.trim();
            
            if (!message || isLoading) return;
            
            // Add user message to chat
            addMessage(message, 'user');
            messageInput.value = '';
            
            // Show loading state
            setLoading(true);
            
            try {
                // Send message to API
                const response = await sendMessageToAPI(message);
                
                // Add bot response to chat
                addMessage(response, 'bot');
            } catch (error) {
                console.error('Error sending message:', error);
                addMessage('Sorry, I encountered an error. Please try again.', 'bot', true);
            } finally {
                setLoading(false);
                messageInput.focus();
            }
        }

        // Send message to API
        async function sendMessageToAPI(message) {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    user_id: {{ Auth::id() }} // Add the authenticated user's ID
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            return data.response || data.reply || 'No response received';
        }

        // Add message to chat
        function addMessage(text, sender, isError = false) {
            const messageDiv = document.createElement('div');
            const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            // Add animation class
            messageDiv.className = 'animate-fade-in-up';
            
            if (sender === 'user') {
                messageDiv.classList.add('flex', 'items-start', 'space-x-3', 'justify-end');
                messageDiv.innerHTML = `
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl p-4 shadow-md max-w-md">
                        <p>${escapeHtml(text)}</p>
                        <span class="text-xs text-indigo-200 mt-2 block">${timestamp}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                `;
            } else {
                messageDiv.classList.add('flex', 'items-start', 'space-x-3');
                const bgColor = isError ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700';
                const textColor = isError ? 'text-red-800 dark:text-red-200' : 'text-gray-900 dark:text-gray-100';
                const timestampColor = isError ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400';
                
                messageDiv.innerHTML = `
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="${bgColor} rounded-xl p-4 shadow-md max-w-md border">
                        <p class="${textColor}">${escapeHtml(text)}</p>
                        <span class="text-xs ${timestampColor} mt-2 block">${timestamp}</span>
                    </div>
                `;
            }
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // Set loading state
        function setLoading(loading) {
            isLoading = loading;
            sendButton.disabled = loading;
            messageInput.disabled = loading;
            
            if (loading) {
                loadingIndicator.classList.remove('hidden');
                sendButton.innerHTML = `
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                    <span>Sending...</span>
                `;
            } else {
                loadingIndicator.classList.add('hidden');
                sendButton.innerHTML = `
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                `;
            }
        }

        // Smooth scroll to bottom
        function scrollToBottom() {
            setTimeout(() => {
                messagesContainer.scrollTo({
                    top: messagesContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
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
