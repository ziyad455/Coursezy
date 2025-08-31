<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chat - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'slide-in-right': 'slideInRight 0.3s ease-out forwards',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950 text-gray-900 dark:text-gray-100 transition-all duration-500 h-screen flex flex-col font-sans">

    <!-- Navigation placeholder -->
    @if ($current->role == "student")

        <x-studentNav/>
    @else
    
    <x-coachNav/>
    @endif

    <!-- Chat Header with Glass Effect -->
    <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl border-b border-white/20 dark:border-gray-700/30 px-4 sm:px-6 lg:px-8 py-6">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/5 to-indigo-500/5 dark:from-primary-600/10 dark:to-indigo-600/10"></div>
        
        <div class="relative max-w-5xl mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Enhanced Back Button -->
                    <button onclick="history.back()" class="group p-3 hover:bg-white/50 dark:hover:bg-gray-700/50 rounded-xl transition-all duration-200 hover:shadow-lg hover:scale-105">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Enhanced User Info -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-400 to-indigo-600 p-0.5 shadow-lg">
                                <img class="w-full h-full object-cover rounded-full"
                                    src="{{ $user->profile_photo 
                                            ? asset('storage/' . $user->profile_photo) 
                                            : 'https://via.placeholder.com/150' }}" 
                                    alt="{{ $user->name }}">
                            </div>
                            <div id="onlineStatus" class="absolute -bottom-0.5 -right-0.5 w-5 h-5 bg-emerald-500 border-3 border-white dark:border-gray-800 rounded-full shadow-sm animate-pulse-slow"></div>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                            <p id="statusText" class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">Online now</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Options -->
                <div class="flex items-center space-x-2">
                    <!-- Connection status indicator -->
                    <div id="connectionStatus" class="flex items-center space-x-2 text-xs">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-gray-500 dark:text-gray-400">Connected</span>
                    </div>
                    
                    <button class="group p-3 hover:bg-white/50 dark:hover:bg-gray-700/50 rounded-xl transition-all duration-200 hover:shadow-lg">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Container with Enhanced Layout -->
    <div class="flex-1 flex flex-col max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
        <!-- Messages Area with Better Spacing -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto space-y-6 pr-2">
            @forelse($messages as $message)
                <!-- Date divider will be inserted by JavaScript -->
                @if($message->sender_id === $current->id)
                    <!-- Enhanced Current User Message -->
                    <div class="message-item flex items-start space-x-4 justify-end animate-fade-in-up" data-id="{{ $message->id }}" data-timestamp="{{ $message->created_at->timestamp }}" data-date="{{ $message->created_at->format('Y-m-d') }}">
                        <div class="bg-gradient-to-br from-primary-500 via-primary-600 to-indigo-600 text-white rounded-2xl rounded-br-md p-5 shadow-lg hover:shadow-xl transition-all duration-300 max-w-md relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-2xl rounded-br-md"></div>
                            <p class="relative font-medium leading-relaxed">{{ $message->content }}</p>
                            <div class="flex items-center justify-between mt-3 relative">
                                <span class="text-xs text-primary-100 font-medium">
                                    {{ $message->created_at->format('g:i A') }}
                                </span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-indigo-600 p-0.5 shadow-lg">
                                <img class="w-full h-full object-cover rounded-full"
                                    src="{{ $current->profile_photo 
                                            ? asset('storage/' . $current->profile_photo) 
                                            : 'https://via.placeholder.com/150' }}" 
                                    alt="{{ $current->name }}">
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Enhanced Other User Message -->
                    <div class="message-item flex items-start space-x-4 animate-fade-in-up" data-id="{{ $message->id }}" data-timestamp="{{ $message->created_at->timestamp }}" data-date="{{ $message->created_at->format('Y-m-d') }}">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 to-gray-600 p-0.5 shadow-lg">
                                <img class="w-full h-full object-cover rounded-full" 
                                     src="{{ $user->profile_photo 
                                            ? asset('storage/' . $user->profile_photo) 
                                            : 'https://via.placeholder.com/150' }}" 
                                     alt="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl rounded-bl-md shadow-lg hover:shadow-xl transition-all duration-300 p-5 max-w-md border border-gray-100/50 dark:border-gray-700/50 relative group">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-700/20 rounded-2xl rounded-bl-md"></div>
                            <p class="relative text-gray-900 dark:text-gray-100 font-medium leading-relaxed">{{ $message->content }}</p>
                            <div class="flex items-center justify-between mt-3 relative">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                   {{ $message->created_at->format('g:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <!-- Enhanced Empty State -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center space-y-4 max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-primary-100 to-indigo-100 dark:from-primary-900/30 dark:to-indigo-900/30 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Start the conversation</h3>
                            <p class="text-gray-500 dark:text-gray-400">Send a message to begin your chat with {{ $user->name }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Enhanced Input Area with Glass Effect -->
        <div class="relative mt-6">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-indigo-500/10 rounded-2xl blur-xl"></div>
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-gray-700/30 p-6">
                <div class="flex items-end space-x-4">
                    <!-- Enhanced Attachment Button -->
                    <div class="flex space-x-2">
                        <button class="group p-3 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-all duration-200 hover:shadow-md hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </button>
                        
                        <button class="group p-3 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-all duration-200 hover:shadow-md hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Enhanced Input Field -->
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            id="messageInput" 
                            placeholder="Type your message..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-600/50 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-300 text-base resize-none hover:shadow-md"
                            maxlength="1000"
                        >
                        <input type="hidden" id="receiver_id" value="{{ $user->id}}"/>
                    </div>
                    
                    <!-- Enhanced Send Button -->
                    <button 
                        id="sendButton" 
                        class="group bg-gradient-to-br from-primary-500 via-primary-600 to-indigo-600 hover:from-primary-600 hover:via-primary-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl transition-all duration-300 flex items-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed shadow-xl hover:shadow-2xl transform hover:-translate-y-1 hover:scale-105 relative overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative font-semibold">Send</span>
                        <svg class="relative w-5 h-5 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Character Count -->
                <div class="mt-3 flex justify-between items-center text-xs">
                    <div class="text-gray-400 dark:text-gray-500">
                        <span id="charCount">0</span>/1000
                    </div>
                    <div class="text-gray-400 dark:text-gray-500">
                        Press Enter to send
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Typing Indicator -->
    <div id="typingIndicator" class="hidden max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-6">
        <div class="flex items-start space-x-4 animate-fade-in-up">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 to-gray-600 p-0.5 shadow-lg">
                <img class="w-full h-full object-cover rounded-full" 
                     src="{{ $user->profile_photo 
                            ? asset('storage/' . $user->profile_photo) 
                            : 'https://via.placeholder.com/150' }}" 
                     alt="{{ $user->name }}">
            </div>
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl rounded-bl-md shadow-lg p-4 border border-gray-100/50 dark:border-gray-700/50">
                <div class="flex space-x-2">
                    <div class="w-2.5 h-2.5 bg-primary-400 dark:bg-primary-500 rounded-full animate-bounce"></div>
                    <div class="w-2.5 h-2.5 bg-primary-400 dark:bg-primary-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2.5 h-2.5 bg-primary-400 dark:bg-primary-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Global variables
    let lastMessageId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
    let pollingInterval = null;
    let isPolling = false;
    const POLLING_INTERVAL = 2000; // 2 seconds as requested
    
    // DOM Elements
    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const typingIndicator = document.getElementById('typingIndicator');
    const receiver_id = document.getElementById('receiver_id').value;
    const charCount = document.getElementById('charCount');
    const connectionStatus = document.getElementById('connectionStatus');
    const onlineStatus = document.getElementById('onlineStatus');
    const statusText = document.getElementById('statusText');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        messageInput.focus();
        initializeChat();
        updateCharCount();
        startPolling(); // Start polling immediately
        
        // Initialize date dividers for existing messages
        insertDateDividers();
    });

    // Initialize chat
    function initializeChat() {
        scrollToBottom();
    }

    // Start polling for new messages every 2 seconds
    function startPolling() {
        if (isPolling) return;
        
        isPolling = true;
        updateConnectionStatus('connected');
        
        // Clear any existing interval first
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        // Set up new interval for polling every 2 seconds
        pollingInterval = setInterval(() => {
            checkForNewMessages();
        }, POLLING_INTERVAL);
        
        // Also check immediately
        checkForNewMessages();
    }

    // Stop polling
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
        isPolling = false;
        updateConnectionStatus('disconnected');
    }

    // Check for new messages via AJAX
    function checkForNewMessages() {
        fetch(`/messages/check-new/${receiver_id}?after=${lastMessageId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            updateConnectionStatus('connected');
            
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    addMessageToChat(message, false); // false = don't scroll immediately
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
                
                // Scroll to bottom after all messages are added
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Error checking for new messages:', error);
            updateConnectionStatus('error');
            
            // Try to restart polling after a delay if there's an error
            setTimeout(() => {
                if (!isPolling) {
                    startPolling();
                }
            }, 5000); // Retry after 5 seconds on error
        });
    }

    // Update connection status
    function updateConnectionStatus(status) {
        const statusDot = connectionStatus.querySelector('.w-2');
        const statusText = connectionStatus.querySelector('span');
        
        switch(status) {
            case 'connected':
                statusDot.className = 'w-2 h-2 bg-green-500 rounded-full animate-pulse';
                statusText.textContent = 'Connected';
                break;
            case 'disconnected':
                statusDot.className = 'w-2 h-2 bg-gray-400 rounded-full';
                statusText.textContent = 'Disconnected';
                break;
            case 'error':
                statusDot.className = 'w-2 h-2 bg-red-500 rounded-full animate-pulse';
                statusText.textContent = 'Connection error';
                break;
        }
    }

    // Character count update
    messageInput.addEventListener('input', updateCharCount);
    
    function updateCharCount() {
        const count = messageInput.value.length;
        charCount.textContent = count;
        
        // Color coding for character count
        if (count > 900) {
            charCount.parentElement.className = 'text-red-500 dark:text-red-400';
        } else if (count > 700) {
            charCount.parentElement.className = 'text-amber-500 dark:text-amber-400';
        } else {
            charCount.parentElement.className = 'text-gray-400 dark:text-gray-500';
        }
    }

    // Event Listeners
    sendButton.addEventListener('click', handleSendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSendMessage();
        }
    });

    // Handle sending messages
    function handleSendMessage() {
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        // Disable send button temporarily
        sendButton.disabled = true;
        
        // Create temporary message object
        const tempMessage = {
            content: message,
            sender_id: {{ $current->id }},
            created_at: new Date().toISOString(),
            is_temp: true
        };
        
        // Add user message to chat UI immediately
        addMessageToChat(tempMessage, true);
        messageInput.value = '';
        updateCharCount();

        // Send request to Laravel
        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: message,
                receiver_id: receiver_id
            })
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable send button
            sendButton.disabled = false;
            
            // Update lastMessageId with the actual message ID
            if (data.message && data.message.id) {
                lastMessageId = Math.max(lastMessageId, data.message.id);
            }
        })
        .catch(error => {
            console.error('Error saving message:', error);
            sendButton.disabled = false;
            
            // Optionally show an error message to user
            showErrorNotification('Failed to send message. Please try again.');
        });
    }

    // Add message to chat
    function addMessageToChat(message, shouldScroll = true) {
        const messageDiv = document.createElement('div');
        const messageDate = new Date(message.created_at);
        const timestamp = formatTime(messageDate);
        const dateStr = formatDate(messageDate);
        
        messageDiv.className = 'message-item animate-slide-in-right';
        messageDiv.setAttribute('data-id', message.id || 0);
        messageDiv.setAttribute('data-date', dateStr);

        const isCurrentUser = message.sender_id === {{ $current->id }};
        
        if (isCurrentUser) {
            messageDiv.classList.add('flex', 'items-start', 'space-x-4', 'justify-end');
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-br from-primary-500 via-primary-600 to-indigo-600 text-white rounded-2xl rounded-br-md p-5 shadow-lg hover:shadow-xl transition-all duration-300 max-w-md relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-2xl rounded-br-md"></div>
                    <p class="relative font-medium leading-relaxed">${escapeHtml(message.content)}</p>
                    <div class="flex items-center justify-between mt-3 relative">
                        <span class="text-xs text-primary-100 font-medium">${timestamp}</span>

                    </div>
                </div>
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-indigo-600 p-0.5 shadow-lg">
                        <img class="w-full h-full object-cover rounded-full"
                            src="{{ $current->profile_photo 
                                    ? asset('storage/' . $current->profile_photo) 
                                    : 'https://via.placeholder.com/150' }}" 
                            alt="{{ $current->name }}">
                    </div>
                </div>
            `;
        } else {
            messageDiv.classList.add('flex', 'items-start', 'space-x-4');
            messageDiv.innerHTML = `
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 to-gray-600 p-0.5 shadow-lg">
                        <img class="w-full h-full object-cover rounded-full" 
                             src="{{ $user->profile_photo 
                                    ? asset('storage/' . $user->profile_photo) 
                                    : 'https://via.placeholder.com/150' }}" 
                             alt="{{ $user->name }}">
                    </div>
                </div>
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl rounded-bl-md shadow-lg hover:shadow-xl transition-all duration-300 p-5 max-w-md border border-gray-100/50 dark:border-gray-700/50 relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-700/20 rounded-2xl rounded-bl-md"></div>
                    <p class="relative text-gray-900 dark:text-gray-100 font-medium leading-relaxed">${escapeHtml(message.content)}</p>
                    <div class="flex items-center justify-between mt-3 relative">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">${timestamp}</span>
                    </div>
                </div>
            `;
        }
        
        // Insert date divider if needed
        insertDateDividerIfNeeded(messageDiv, dateStr);
        
        messagesContainer.appendChild(messageDiv);
        
        if (shouldScroll) {
            scrollToBottom();
        }
    }

    // Insert date dividers for existing messages
    function insertDateDividers() {
        const messages = document.querySelectorAll('.message-item');
        let lastDate = null;
        
        messages.forEach((message, index) => {
            const messageDate = message.getAttribute('data-date');
            
            if (messageDate !== lastDate) {
                insertDateDivider(message, messageDate);
                lastDate = messageDate;
            }
        });
    }

    // Insert date divider if needed for new message
    function insertDateDividerIfNeeded(messageElement, dateStr) {
        const allMessages = document.querySelectorAll('.message-item');
        const lastMessage = allMessages[allMessages.length - 1];
        
        if (!lastMessage || lastMessage.getAttribute('data-date') !== dateStr) {
            insertDateDivider(messageElement, dateStr, true);
        }
    }

    // Fixed: Insert date divider
    function insertDateDivider(messageElement, dateStr, isBeforeElement = false) {
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        const messageDate = new Date(dateStr);
        let displayDate;
        
        if (dateStr === formatDate(today)) {
            displayDate = 'Today';
        } else if (dateStr === formatDate(yesterday)) {
            displayDate = 'Yesterday';
        } else {
            displayDate = messageDate.toLocaleDateString('en-US', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
        }
        
        const divider = document.createElement('div');
        divider.className = 'date-divider flex items-center justify-center my-8';
        divider.innerHTML = `
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 py-2 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                        ${displayDate}
                    </span>
                </div>
            </div>
        `;
        
        if (isBeforeElement) {
            // Check if messageElement is a direct child of messagesContainer
            if (messageElement.parentNode === messagesContainer) {
                messagesContainer.insertBefore(divider, messageElement);
            } else {
                // If not, append the divider to the container first
                messagesContainer.appendChild(divider);
            }
        } else {
            // Insert after the message element
            if (messageElement.parentNode === messagesContainer) {
                if (messageElement.nextSibling) {
                    messagesContainer.insertBefore(divider, messageElement.nextSibling);
                } else {
                    messagesContainer.appendChild(divider);
                }
            } else {
                messagesContainer.appendChild(divider);
            }
        }
    }

    // Format date as YYYY-MM-DD
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    // Format time as h:mm AM/PM
    function formatTime(date) {
        return date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
    }

    // Show error notification
    function showErrorNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in-right';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin='round' stroke-width='2' d='M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Enhanced smooth scroll
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

    // Handle connection issues
    window.addEventListener('online', () => {
        if (!isPolling) {
            startPolling();
        }
    });

    window.addEventListener('offline', () => {
        stopPolling();
        updateConnectionStatus('disconnected');
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        stopPolling();
    });
</script>

    <style>
        /* Enhanced animations */
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
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out forwards;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Enhanced scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, 0.5);
            border-radius: 10px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: rgba(30, 41, 59, 0.5);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #2563eb, #4f46e5);
            transform: scale(1.1);
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #1e40af, #3730a3);
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #1d4ed8, #312e81);
        }

        /* Input focus effects */
        #messageInput:focus {
            transform: translateY(-1px);
            box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
        }

        /* Message hover effects */
        .group:hover {
            transform: translateY(-2px);
        }

        /* Glass morphism effects */
        .backdrop-blur-xl {
            backdrop-filter: blur(16px);
        }
        
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Date divider styling */
        .date-divider {
            margin: 2rem 0;
        }

        .date-divider span {
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced mobile responsiveness */
        @media (max-width: 640px) {
            .max-w-md {
                max-width: calc(100vw - 8rem);
            }
            
            #messageInput {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .date-divider {
                margin: 1.5rem 0;
            }
        }

        /* Connection status animations */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Notification styles */
        .notification-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .notification-enter-active {
            transform: translateX(0);
opacity: 1;
transition: all 0.3s ease;
}

.notification-leave {
transform: translateX(0);
opacity: 1;
}

.notification-leave-active {
transform: translateX(100%);
opacity: 0;
transition: all 0.3s ease;
}
</style>