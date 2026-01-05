<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Coursezy</title>
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
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    @if ($user->role == "coach")
        <x-coachNav />

    @else
        <x-studentNav />

    @endif

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                Messages
            </h1>
            <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                Stay connected with your students and colleagues
            </p>
        </div>

        <!-- Pinned Conversation Section -->
        <div class="mb-6">
            <h2
                class="text-sm font-semibold text-light-text-muted dark:text-dark-text-muted uppercase tracking-wide mb-3 px-2">
                Pinned
            </h2>
            <a href="/ai"
                class="block bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-light-border-subtle dark:border-dark-border-subtle p-4 mb-6 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary/50">
                <div class="flex items-center space-x-4">
                    <!-- AI Assistant Icon -->
                    <div class="relative">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-dark-text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <!-- Pin indicator -->
                        <div
                            class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 dark:bg-yellow-400 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-dark-text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                <path fill-rule="evenodd"
                                    d="M3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <!-- Online status -->
                        <div
                            class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full">
                        </div>
                    </div>

                    <!-- Message content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3
                                class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary truncate">
                                AI Assistant
                            </h3>
                            <span class="text-sm text-light-text-muted dark:text-dark-text-muted">
                                Online
                            </span>
                        </div>
                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary truncate">
                            I'm here to help you with course creation, student management, and more...
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Regular Conversations Section -->
        <div>
            <h2
                class="text-sm font-semibold text-light-text-muted dark:text-dark-text-muted uppercase tracking-wide mb-3 px-2">
                Recent Conversations
            </h2>
            <div class="space-y-2">
                @forelse($users as $user)
                    @php
                        // Get the latest message between logged-in user and this user
                        $latestMessage = \App\Models\Message::where(function ($q) use ($user) {
                            $q->where('sender_id', auth()->id())
                                ->where('receiver_id', $user->id);
                        })->orWhere(function ($q) use ($user) {
                            $q->where('sender_id', $user->id)
                                ->where('receiver_id', auth()->id());
                        })->latest('created_at')->first();
                    @endphp
                    <a href="{{ url('/chating/' . $user->id) }}"
                        class="block bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-light-border-subtle dark:border-dark-border-subtle p-4 hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary/50">
                        <div class="flex items-center space-x-4">
                            <!-- Profile Picture -->
                            <div class="relative">
                                <x-profile-photo :user="$user" size="lg" />
                                <div
                                    class="absolute bottom-0 right-0 w-4 h-4 bg-gray-400 border-2 border-white dark:border-gray-800 rounded-full">
                                </div>
                            </div>
                            <!-- Message content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h3
                                        class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary truncate">
                                        {{ $user->name }}
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-light-text-muted dark:text-dark-text-muted">
                                            {{ $latestMessage ? $latestMessage->created_at->diffForHumans() : '' }}
                                        </span>
                                        @if($latestMessage && $latestMessage->is_updated)
                                            <span class="text-xs text-dark-text-secondary">(edited)</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary truncate">
                                    {{ $latestMessage ? $latestMessage->content : 'No messages yet.' }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <!-- Enhanced Empty State -->
                    <div
                        class="flex flex-col items-center justify-center py-16 px-6 text-center bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl border-2 border-dashed border-light-border-default dark:border-dark-border-default">
                        <!-- Icon -->
                        <div
                            class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                            <svg class="w-10 h-10 text-gray-400 dark:text-light-text-muted" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>

                        <!-- Main Message -->
                        <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">
                            No conversations yet
                        </h3>

                        <!-- Description -->
                        <p class="text-light-text-muted dark:text-dark-text-muted mb-6 max-w-sm leading-relaxed">
                            Start a conversation with someone to see your chat history here. Your recent messages will
                            appear in this space.
                        </p>


                    </div>
                @endforelse
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
                link.addEventListener('click', function (e) {
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

        // Check for dark mode changes in localStorage or session
        function checkDarkModeChanges() {
            // Listen for storage events (when dark mode is changed in another tab/page)
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
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            initScrollAnimations();
            addRippleEffect();
            checkDarkModeChanges();
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