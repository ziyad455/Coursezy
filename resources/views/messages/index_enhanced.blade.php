<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-in': 'bounceIn 0.5s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        bounceIn: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for reactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom scrollbar styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        /* Typing indicator animation */
        @keyframes typing {

            0%,
            60%,
            100% {
                opacity: 0.3;
            }

            30% {
                opacity: 1;
            }
        }

        .typing-dot {
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Message animations */
        .message-enter {
            animation: slideIn 0.3s ease-out;
        }

        /* Unread indicator pulse */
        .unread-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300"
    x-data="messagingApp()" x-init="init()">

    <!-- Navigation -->
    @if (Auth::user()->role == "coach")
        <x-coachNav />
    @else
        <x-studentNav />
    @endif

    <!-- Main Messaging Container -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-lg shadow-lg dark:shadow-xl overflow-hidden"
            style="height: 80vh;">
            <div class="flex h-full">

                <!-- Conversations List -->
                <div class="w-1/3 border-r border-light-border-default dark:border-dark-border-default flex flex-col">
                    <!-- Header -->
                    <div
                        class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 text-dark-text-primary">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">Messages</h3>
                            <span x-show="totalUnread > 0" x-text="totalUnread"
                                class="bg-red-500 text-dark-text-primary rounded-full px-2 py-1 text-xs font-bold unread-pulse">
                            </span>
                        </div>

                        <!-- Search -->
                        <input type="text" x-model="searchQuery" @input="filterConversations()"
                            placeholder="Search conversations..."
                            class="w-full mt-3 px-3 py-2 rounded-lg bg-light-bg-secondary/20 placeholder-white/70 text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-white/30">
                    </div>

                    <!-- Conversations List -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <template x-for="conversation in filteredConversations" :key="conversation.user.id">
                            <div @click="selectUser(conversation.user.id)"
                                :class="{'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500': selectedUserId === conversation.user.id}"
                                class="px-4 py-3 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary cursor-pointer transition-all duration-200 border-b border-light-border-default dark:border-dark-border-default">
                                <div class="flex items-start space-x-3">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0 relative">
                                        <template
                                            x-if="conversation.user.profile_photo && conversation.user.profile_photo.startsWith('https://')">
                                            <img :src="conversation.user.profile_photo" :alt="conversation.user.name"
                                                class="w-12 h-12 rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                        </template>
                                        <template
                                            x-if="conversation.user.profile_photo && !conversation.user.profile_photo.startsWith('https://')">
                                            <img :src="`/storage/${conversation.user.profile_photo}`"
                                                :alt="conversation.user.name"
                                                class="w-12 h-12 rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                        </template>
                                        <template x-if="!conversation.user.profile_photo">
                                            <div
                                                class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-dark-text-primary font-semibold shadow-sm">
                                                <span
                                                    x-text="conversation.user.name.charAt(0).toUpperCase() + (conversation.user.name.split(' ')[1] ? conversation.user.name.split(' ')[1].charAt(0).toUpperCase() : '')"></span>
                                            </div>
                                        </template>
                                        <!-- Online indicator -->
                                        <span x-show="conversation.is_online"
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-light-text-primary dark:text-dark-text-primary truncate"
                                                x-text="conversation.user.name"></p>
                                            <span x-show="conversation.last_message"
                                                x-text="formatTime(conversation.last_message.created_at)"
                                                class="text-xs text-light-text-muted dark:text-dark-text-muted"></span>
                                        </div>

                                        <!-- Last message preview -->
                                        <div class="flex items-center justify-between mt-1">
                                            <p
                                                class="text-sm text-light-text-secondary dark:text-dark-text-secondary truncate">
                                                <span x-show="conversation.last_message">
                                                    <span
                                                        x-show="conversation.last_message.from_user_id === {{ Auth::id() }}"
                                                        class="text-light-text-muted">You: </span>
                                                    <span
                                                        x-text="conversation.last_message ? conversation.last_message.message : ''"></span>
                                                </span>
                                                <span x-show="!conversation.last_message"
                                                    class="italic text-dark-text-secondary">No messages yet</span>
                                            </p>

                                            <!-- Unread badge -->
                                            <span x-show="conversation.unread_count > 0"
                                                x-text="conversation.unread_count"
                                                class="bg-blue-500 text-dark-text-primary rounded-full px-2 py-0.5 text-xs font-bold ml-2">
                                            </span>
                                        </div>

                                        <!-- Typing indicator -->
                                        <div x-show="typingUsers[conversation.user.id]"
                                            class="flex items-center space-x-1 mt-1">
                                            <span
                                                class="text-xs text-light-text-muted dark:text-dark-text-muted italic">typing</span>
                                            <div class="flex space-x-0.5">
                                                <div class="w-1 h-1 bg-gray-500 rounded-full typing-dot"></div>
                                                <div class="w-1 h-1 bg-gray-500 rounded-full typing-dot"></div>
                                                <div class="w-1 h-1 bg-gray-500 rounded-full typing-dot"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div x-show="filteredConversations.length === 0"
                            class="p-8 text-center text-light-text-muted dark:text-dark-text-muted">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p>No conversations found</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col" x-show="selectedUserId">
                    <!-- Chat Header -->
                    <div
                        class="px-6 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-b border-light-border-default dark:border-dark-border-default">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <template
                                    x-if="selectedUser?.profile_photo && selectedUser.profile_photo.startsWith('https://')">
                                    <img :src="selectedUser.profile_photo" :alt="selectedUser.name"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                </template>
                                <template
                                    x-if="selectedUser?.profile_photo && !selectedUser.profile_photo.startsWith('https://')">
                                    <img :src="`/storage/${selectedUser.profile_photo}`" :alt="selectedUser.name"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                </template>
                                <template x-if="!selectedUser?.profile_photo">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-dark-text-primary font-semibold shadow-sm">
                                        <span
                                            x-text="selectedUser?.name?.charAt(0).toUpperCase() + (selectedUser?.name?.split(' ')[1] ? selectedUser.name.split(' ')[1].charAt(0).toUpperCase() : '')"></span>
                                    </div>
                                </template>
                                <div>
                                    <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary"
                                        x-text="selectedUser?.name"></h3>
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">
                                        <span x-show="isUserOnline(selectedUserId)" class="text-green-500">●
                                            Online</span>
                                        <span x-show="!isUserOnline(selectedUserId)">Last seen <span
                                                x-text="selectedUser?.last_seen"></span></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                <button @click="toggleSound()"
                                    class="p-2 text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    <svg x-show="soundEnabled" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                                        </path>
                                    </svg>
                                    <svg x-show="!soundEnabled" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"
                                            clip-rule="evenodd"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-light-bg-primary dark:bg-dark-bg-primary custom-scrollbar"
                        id="messages-container" x-ref="messagesContainer">
                        <template x-for="message in messages" :key="message.id">
                            <div :class="message.from_user_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'"
                                class="flex message-enter group">
                                <div class="max-w-xs lg:max-w-md">
                                    <div :class="message.from_user_id === {{ Auth::id() }} ? 
                                                'bg-blue-500 dark:bg-blue-600 text-dark-text-primary' : 
                                                'bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-primary dark:text-dark-text-primary'"
                                        class="rounded-lg px-4 py-2 shadow dark:shadow-md relative">
                                        <p class="text-sm break-words" x-text="message.message"></p>
                                        <!-- Delete button for own messages -->
                                        <button x-show="message.from_user_id === {{ Auth::id() }}"
                                            @click="deleteMessage(message)"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
                                            title="Delete">
                                            ×
                                        </button>
                                    </div>
                                    <div class="flex items-center mt-1 space-x-2"
                                        :class="message.from_user_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted"
                                            x-text="formatTime(message.created_at)"></p>
                                        <!-- Read status for sent messages -->
                                        <span x-show="message.from_user_id === {{ Auth::id() }} && message.is_read"
                                            class="text-xs text-blue-500">✓✓</span>
                                        <span x-show="message.from_user_id === {{ Auth::id() }} && !message.is_read"
                                            class="text-xs text-dark-text-secondary">✓</span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Typing indicator in chat -->
                        <div x-show="typingUsers[selectedUserId]" class="flex justify-start">
                            <div
                                class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-lg px-4 py-2 shadow dark:shadow-md">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-gray-500 rounded-full typing-dot"></div>
                                    <div class="w-2 h-2 bg-gray-500 rounded-full typing-dot"></div>
                                    <div class="w-2 h-2 bg-gray-500 rounded-full typing-dot"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div
                        class="px-6 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-t border-light-border-default dark:border-dark-border-default">
                        <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                            <input type="text" x-model="messageInput" @input="handleTyping()"
                                @keydown.enter.prevent="sendMessage()"
                                class="flex-1 rounded-full border-light-border-default dark:border-dark-border-default dark:bg-gray-700 dark:text-gray-100 focus:border-blue-500 dark:focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 px-4 py-2 placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Type a message..." autocomplete="off">

                            <!-- Send button -->
                            <button type="submit" :disabled="!messageInput.trim()"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 text-dark-text-primary rounded-full p-2 hover:from-blue-600 hover:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Empty State (No conversation selected) -->
                <div class="flex-1 flex items-center justify-center bg-light-bg-primary dark:bg-dark-bg-primary"
                    x-show="!selectedUserId">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-light-text-muted mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-light-text-primary dark:text-dark-text-primary mb-2">Welcome
                            to Coursezy Messages</h3>
                        <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Select a conversation from
                            the left to start messaging</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification sound (hidden audio element) -->
    <audio id="notification-sound" preload="auto">
        <source
            src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQQGAACHhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGCzvLTgjMGHm7A7OWvTgwOWqzn77VlHAU7k9n1ynkpBSiA0fHOgjUIHGy97OOoThIIUKXh8K5oGAU9mNbuyHEkBjaS1+/LgicFJ3/R8c2DOQoaZrfq56hWDwpUqu3yqmMXBTqW2e7KeCQGNpXW78yCKwUje8vvxH4yChxqxuvmql4NBEGc3mekUBEGPZ/E0JZhPgJAkNPWfR4DSJzO9cN1JwMcdcjux3guBR5/yO7HdikBK4LR8c5/LAUngNDyz38sBS+H0/LOhC0FH3zJ7sd5MAUlgdHx0ncmASmH0PTPgicBHHvL7sh7LAUmf9HwzIIvBSuE0fLOgjAAGm3A7c90KAVAgNPxy3UnAzyP2fLJfigJlH/A7spVKwUBVy8FCjGA3e7Rf18CCTp/yLB/Wi8AqAVBkNvsxX0VCRRlw+7SfzEGDGK/6slidBgANHDE7sh+OAYZb8Xs03gnBQtkvO3Ml0sKC1yv6NijSAYNXrPm0YtN9oeL3tK4ZxYBOYzX7NJ+GwI8hdryx3kpATKM0vHPhzoIF2y/7OKbUBcKT6Pm7rJnEAU+mdXvxIMnBSh80PLNfy0FE27G6+CzSg4HVqno7bJnEAU6j9rsxnUmBzSI0+vHgSkJIXXH78V+KgUdbsXt3KdKDAlZre/lrl0LCViw+OKcUQ0ImM/bsWkcBjiT2vLOeywDKIDS8c6EOQchdMfx2YglBilw0/LOhCsGJX/P9M1/KgYxh9Luy34sBS+D0fDMfS8HHnbH8cl5JAMrh9Pxzn4wBjKE0O3FfjAGLYDR7MOCMwUgeMzuxH8nBhl1wefPgjEDKYDQ8cl+IgUohM7jmTASByVzxejjfywJJn7W7ch5KAUog9HyzIEvBCtdumKjeRkEOIzY7sp3KgUxidPuyHcsBSh/ze3OgiwHH3zI7sd5LwUnddDyx3gkCCuH0vDMgjkBJYXR8sNwKwUxidTwyX4tFS930POxdggGIX/S78ZvIgg2idTvyHItBiaA0vPLfjkJgnzAj05BACOA3NG0ZAE+idn1yXkpBCx+0fHNgicFH3/J6cd5LAUlg9HwyoIjBS2J1eTOhSsFJHzK5cp1KAM1iNLoy34gBjiF0/jLficFJ3zQ9c5/KgYnfdD0yoEsChe8yNacVQ4HUaXg8L1pFAU7kNPsxnkiBjSE1uzDfSUFKYXR8ceCKAUxiNDuyIUsECl+xufJdigGI3/I6+KfQBEKVqfq8KtqEgUyidTuzoUnBSeBx+zLgTQHGXjJ6+e0MgQKW6/o7a9VBRY+j8RVHAg2hNTs0ocqBSh+zvHMfzEGEGzB7d2rWw0JUqzl8LBlHgU/lt/1x3gnATKN1OzJfy8FI33L88t+LAUnPa0nhsEAXKze868HBTaO2evOge8CLYbS7sh4LgYjddPywHgkBDSI0/XJfykElH3P8cZ+OwYlf83wyH0rCCR8x/PLfjkFJX7P9Mt/KgUmgNDyy4IqDBh1x+3OfiwKIXfL7sd1LAUuh9Tvyn0vBCt/zvHOjiwCl2xulHY9BlGl2fKzaRgFNpLZ7cd5JQUzjNzry4IjBSeB0fPMfygFJnjJ8sp9JgQrf8/uyn0yDJaC3JphABJZs+ropRcMSKXk8b1lGAUxj9nyz3wqATOOu7NmEASQwLDTxIMnBaGAzfPZVgBBxYBjSYEBAQQQQQg="
            type="audio/wav">
    </audio>

    <script>
        function messagingApp() {
            return {
                // Data properties
                conversations: [],
                filteredConversations: [],
                messages: [],
                selectedUserId: null,
                selectedUser: null,
                messageInput: '',
                searchQuery: '',
                soundEnabled: true,
                typingUsers: {},
                typingTimeout: null,
                totalUnread: 0,
                echo: null,
                // Server-provided initial selection (if any)
                initialSelectedUserId: {!! isset($selectedUser) ? (int) $selectedUser->id : 'null' !!},
                initialMessages: {!! isset($messages) ? json_encode($messages) : '[]' !!},

                // Initialize the app
                init() {
                    this.setupEcho();
                    this.loadConversations();
                    this.startPolling();

                    // Load selected user from URL if present
                    const urlParams = new URLSearchParams(window.location.search);
                    const userId = urlParams.get('user');
                    if (userId) {
                        this.selectUser(parseInt(userId));
                    } else if (this.initialSelectedUserId) {
                        // Use server-selected user if provided (e.g., visiting /messages/{id})
                        this.selectedUserId = this.initialSelectedUserId;
                        this.messages = this.initialMessages || [];
                        // Mark as read immediately
                        this.markConversationAsRead(this.initialSelectedUserId);
                        // Update URL to include query param for consistency
                        window.history.replaceState({}, '', `?user=${this.initialSelectedUserId}`);
                    }
                },

                // Setup Laravel Echo for real-time updates
                setupEcho() {
                    this.echo = new Echo({
                        broadcaster: 'pusher',
                        key: '14d65f689c4c081b8c19',
                        cluster: 'eu',
                        forceTLS: true,
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }
                    });

                    // Listen for new messages
                    this.echo.private('chat.{{ Auth::id() }}')
                        .listen('MessageSent', (e) => {
                            this.handleNewMessage(e);
                        })
                        .listen('MessageDeleted', (e) => {
                            this.messages = this.messages.filter(m => m.id !== e.id);
                            // If the last message was deleted, update conversation preview
                            const conv = this.conversations.find(c => c.last_message && c.last_message.id === e.id);
                            if (conv) {
                                conv.last_message = null;
                                this.filterConversations();
                            }
                        })
                        .listenForWhisper('typing', (e) => {
                            this.handleTypingEvent(e);
                        });
                },

                // Load conversations list
                async loadConversations() {
                    try {
                        const response = await fetch('{{ route("messages.conversations") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();
                        this.conversations = data;
                        this.filteredConversations = data;
                        this.updateTotalUnread();
                        // Ensure selectedUser object is set if selectedUserId was prefilled
                        if (this.selectedUserId && !this.selectedUser) {
                            this.selectedUser = this.conversations.find(c => c.user.id === this.selectedUserId)?.user || null;
                        }
                    } catch (error) {
                        console.error('Error loading conversations:', error);
                    }
                },

                // Filter conversations based on search
                filterConversations() {
                    if (!this.searchQuery) {
                        this.filteredConversations = this.conversations;
                        return;
                    }

                    const query = this.searchQuery.toLowerCase();
                    this.filteredConversations = this.conversations.filter(conv =>
                        conv.user.name.toLowerCase().includes(query) ||
                        (conv.last_message && conv.last_message.message.toLowerCase().includes(query))
                    );
                },

                // Select a user and load their messages
                async selectUser(userId) {
                    this.selectedUserId = userId;
                    this.selectedUser = this.conversations.find(c => c.user.id === userId)?.user;

                    // Mark conversation as read
                    const conv = this.conversations.find(c => c.user.id === userId);
                    if (conv) {
                        conv.unread_count = 0;
                        this.updateTotalUnread();
                    }
                    // Inform backend that messages are read
                    this.markConversationAsRead(userId);

                    // Load messages
                    await this.loadMessages(userId);

                    // Update URL
                    window.history.replaceState({}, '', `?user=${userId}`);
                },

                // Load messages for a user
                async loadMessages(userId) {
                    try {
                        const response = await fetch(`/messages/get/${userId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();
                        this.messages = data;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    } catch (error) {
                        console.error('Error loading messages:', error);
                    }
                },

                // Send a message
                async sendMessage() {
                    if (!this.messageInput.trim() || !this.selectedUserId) return;

                    const message = this.messageInput.trim();
                    this.messageInput = '';

                    try {
                        const response = await fetch('{{ route("messages.send") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                to_user_id: this.selectedUserId,
                                message: message
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.messages.push(data.message);
                            this.updateConversationPreview(data.message);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        this.messageInput = message; // Restore message on error
                    }
                },

                // Delete a message
                async deleteMessage(message) {
                    if (!confirm('Delete this message?')) return;
                    try {
                        const res = await fetch(`/messages/${message.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.messages = this.messages.filter(m => m.id !== message.id);
                        }
                    } catch (e) {
                        console.error('Failed to delete message', e);
                    }
                },

                // Handle incoming messages
                handleNewMessage(message) {
                    const me = Number({{ Auth::id() }});
                    const fromId = Number(message.from_user_id);
                    const toId = Number(message.to_user_id);
                    const selectedId = this.selectedUserId ? Number(this.selectedUserId) : null;

                    // Add to messages if in conversation (type-safe compare)
                    if (selectedId && (selectedId === fromId || selectedId === toId)) {
                        this.messages.push(message);
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                        // If the incoming message is from the other user and this chat is open, mark as read
                        if (fromId !== me) {
                            this.markConversationAsRead(fromId);
                        }
                    } else if (!selectedId && (fromId === me || toId === me)) {
                        // If no conversation selected yet, open the conversation immediately and render the message
                        const otherId = fromId === me ? toId : fromId;
                        this.selectedUserId = otherId;
                        // Set a lightweight selectedUser from event payload to avoid waiting for conversations fetch
                        const otherUser = fromId === me ? (message.to_user || null) : (message.from_user || null);
                        if (otherUser) {
                            this.selectedUser = { id: otherUser.id, name: otherUser.name };
                        }
                        // Ensure conversation list has this user so UI header shows name
                        if (!this.conversations.find(c => Number(c.user.id) === Number(otherId)) && otherUser) {
                            this.conversations.unshift({ user: otherUser, last_message: message, unread_count: 0, is_online: false });
                            this.filteredConversations = this.conversations;
                        }
                        // Render the incoming/outgoing message instantly
                        this.messages.push(message);
                        this.$nextTick(() => this.scrollToBottom());
                        // Mark as read if it's an incoming message
                        if (fromId !== me) {
                            this.markConversationAsRead(fromId);
                        }
                        // Load full history in background
                        this.loadMessages(otherId);
                    }

                    // Update conversation preview
                    this.updateConversationPreview(message);

                    // Play notification sound if not from current user
                    if (message.from_user_id !== {{ Auth::id() }} && this.soundEnabled) {
                        this.playNotificationSound();
                    }

                    // Update unread count
                    if (message.from_user_id !== {{ Auth::id() }} && this.selectedUserId !== message.from_user_id) {
                        const conv = this.conversations.find(c => c.user.id === message.from_user_id);
                        if (conv) {
                            conv.unread_count = (conv.unread_count || 0) + 1;
                            this.updateTotalUnread();
                        }
                    }
                },
                // Mark all messages from a user as read
                async markConversationAsRead(userId) {
                    try {
                        await fetch(`/messages/${userId}/read`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                    } catch (e) {
                        console.warn('Failed to mark conversation as read', e);
                    }
                },

                // Update conversation preview with latest message
                updateConversationPreview(message) {
                    let conv = this.conversations.find(c =>
                        c.user.id === message.from_user_id || c.user.id === message.to_user_id
                    );

                    if (conv) {
                        conv.last_message = message;
                        // Sort conversations by last message time
                        this.conversations.sort((a, b) => {
                            if (!a.last_message && !b.last_message) return 0;
                            if (!a.last_message) return 1;
                            if (!b.last_message) return -1;
                            return new Date(b.last_message.created_at) - new Date(a.last_message.created_at);
                        });
                        this.filterConversations();
                    }
                },

                // Handle typing events
                handleTyping() {
                    if (!this.selectedUserId) return;

                    // Clear previous timeout
                    if (this.typingTimeout) {
                        clearTimeout(this.typingTimeout);
                    }

                    // Whisper typing event
                    this.echo.private(`chat.${this.selectedUserId}`)
                        .whisper('typing', {
                            user_id: {{ Auth::id() }},
                            typing: true
                        });

                    // Stop typing after 2 seconds
                    this.typingTimeout = setTimeout(() => {
                        this.echo.private(`chat.${this.selectedUserId}`)
                            .whisper('typing', {
                                user_id: {{ Auth::id() }},
                                typing: false
                            });
                    }, 2000);
                },

                // Handle typing events from other users
                handleTypingEvent(e) {
                    if (e.typing) {
                        this.typingUsers[e.user_id] = true;
                        setTimeout(() => {
                            delete this.typingUsers[e.user_id];
                        }, 3000);
                    } else {
                        delete this.typingUsers[e.user_id];
                    }
                },

                // Play notification sound
                playNotificationSound() {
                    const audio = document.getElementById('notification-sound');
                    audio.play().catch(e => console.log('Sound play failed:', e));
                },

                // Toggle sound notifications
                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                    localStorage.setItem('messageSoundEnabled', this.soundEnabled);
                },

                // Format time
                formatTime(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));

                    if (days === 0) {
                        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                    } else if (days === 1) {
                        return 'Yesterday';
                    } else if (days < 7) {
                        return date.toLocaleDateString('en-US', { weekday: 'short' });
                    } else {
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }
                },

                // Check if user is online
                isUserOnline(userId) {
                    const conv = this.conversations.find(c => c.user.id === userId);
                    return conv ? conv.is_online : false;
                },

                // Update total unread count
                updateTotalUnread() {
                    this.totalUnread = this.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
                },

                // Scroll to bottom of messages
                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },

                // Start polling for updates (fallback for websocket)
                startPolling() {
                    setInterval(() => {
                        this.loadConversations();
                    }, 30000); // Poll every 30 seconds
                }
            }
        }
    </script>
</body>

</html>