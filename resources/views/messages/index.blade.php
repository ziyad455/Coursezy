<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    {{-- Use the appropriate navigation based on user role --}}
    @if (Auth::user()->role == "coach")
        <x-coachNav />
    @else
        <x-studentNav />
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-lg shadow-lg dark:shadow-xl overflow-hidden"
            style="height: 80vh;">
            <div class="flex h-full">
                <!-- Users List -->
                <div class="w-1/3 border-r border-light-border-default dark:border-dark-border-default overflow-y-auto">
                    <div
                        class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 text-dark-text-primary">
                        <h3 class="text-lg font-semibold">Messages</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $user)
                            <a href="{{ route('messages.chat', $user->id) }}"
                                class="block px-4 py-3 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors duration-200 {{ $selectedUser && $selectedUser->id == $user->id ? 'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 dark:from-blue-500 dark:to-purple-600 flex items-center justify-center text-dark-text-primary font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary truncate">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-sm text-light-text-muted dark:text-dark-text-muted truncate">
                                            {{ $user->role ?? 'User' }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col">
                    @if($selectedUser)
                        <!-- Chat Header -->
                        <div
                            class="px-6 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-b border-light-border-default dark:border-dark-border-default">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 dark:from-blue-500 dark:to-purple-600 flex items-center justify-center text-dark-text-primary font-semibold">
                                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary">
                                        {{ $selectedUser->name }}</h3>
                                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted">
                                        {{ $selectedUser->role ?? 'User' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="messages-container"
                            class="flex-1 overflow-y-auto p-6 space-y-4 bg-light-bg-primary dark:bg-dark-bg-primary">
                            @foreach($messages as $message)
                                <div class="flex {{ $message->from_user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md">
                                        <div
                                            class="{{ $message->from_user_id == Auth::id() ? 'bg-blue-500 dark:bg-blue-600 text-dark-text-primary' : 'bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-primary dark:text-dark-text-primary' }} rounded-lg px-4 py-2 shadow dark:shadow-md">
                                            <p class="text-sm">{{ $message->message }}</p>
                                        </div>
                                        <p
                                            class="text-xs text-light-text-muted dark:text-dark-text-muted mt-1 {{ $message->from_user_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                            {{ $message->created_at->format('g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        <div
                            class="px-6 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-t border-light-border-default dark:border-dark-border-default">
                            <form id="message-form" class="flex space-x-2">
                                @csrf
                                <input type="hidden" id="to_user_id" value="{{ $selectedUser->id }}">
                                <input type="text" id="message-input"
                                    class="flex-1 rounded-full border-light-border-default dark:border-dark-border-default dark:bg-gray-700 dark:text-gray-100 focus:border-blue-500 dark:focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 px-4 py-2 placeholder-gray-500 dark:placeholder-gray-400"
                                    placeholder="Type a message..." autocomplete="off">
                                <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 text-dark-text-primary rounded-full px-6 py-2 hover:from-blue-600 hover:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800 transition-all duration-200 font-medium">
                                    Send
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- No User Selected -->
                        <div class="flex-1 flex items-center justify-center bg-light-bg-primary dark:bg-dark-bg-primary">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-light-text-muted" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-light-text-primary dark:text-dark-text-primary">No
                                    conversation selected</h3>
                                <p class="mt-1 text-sm text-light-text-muted dark:text-dark-text-muted">Choose a user from
                                    the list to start messaging</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($selectedUser)
        <script>
                // Initialize dark mode on page load
                (function () {
                    const isDark = {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'true' : 'false' }};
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }

                    // Listen for dark mode changes from localStorage
                    window.addEventListener('storage', function (e) {
                        if (e.key === 'darkMode') {
                            const isDark = e.newValue === 'true';
                            if (isDark) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        }
                    });
                })();

            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Echo for real-time messaging
                if (typeof Echo !== 'undefined') {
                    Echo.private('chat.{{ Auth::id() }}')
                        .listen('MessageSent', (e) => {
                            if (e.from_user_id == {{ $selectedUser->id }} || e.to_user_id == {{ $selectedUser->id }}) {
                                addMessage(e);
                            }
                        });
                }

                // Handle form submission
                document.getElementById('message-form').addEventListener('submit', function (e) {
                    e.preventDefault();

                    const messageInput = document.getElementById('message-input');
                    const message = messageInput.value.trim();

                    if (!message) return;

                    fetch('{{ route("messages.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            to_user_id: document.getElementById('to_user_id').value,
                            message: message
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                addMessage(data.message);
                                messageInput.value = '';
                                scrollToBottom();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                function addMessage(message) {
                    const container = document.getElementById('messages-container');
                    const isOwn = message.from_user_id == {{ Auth::id() }};

                    const isDark = document.documentElement.classList.contains('dark');
                    const messageHtml = `
                <div class="flex ${isOwn ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-xs lg:max-w-md">
                        <div class="${isOwn ? 'bg-blue-500 dark:bg-blue-600 text-dark-text-primary' : 'bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-primary dark:text-dark-text-primary'} rounded-lg px-4 py-2 shadow dark:shadow-md">
                            <p class="text-sm">${escapeHtml(message.message)}</p>
                        </div>
                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted mt-1 ${isOwn ? 'text-right' : 'text-left'}">
                            ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}
                        </p>
                    </div>
                </div>
            `;

                    container.insertAdjacentHTML('beforeend', messageHtml);
                    scrollToBottom();
                }

                function scrollToBottom() {
                    const container = document.getElementById('messages-container');
                    container.scrollTop = container.scrollHeight;
                }

                function escapeHtml(text) {
                    const map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text.replace(/[&<>"']/g, m => map[m]);
                }

                // Scroll to bottom on load
                scrollToBottom();
            });
        </script>
    @else
        <script>
            // Initialize dark mode on page load (for no user selected state)
            (function () {
                const isDark = {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'true' : 'false' }};
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }

                // Listen for dark mode changes from localStorage
                window.addEventListener('storage', function (e) {
                    if (e.key === 'darkMode') {
                        const isDark = e.newValue === 'true';
                        if (isDark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                });
            })();
        </script>
    @endif
</body>

</html>