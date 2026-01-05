<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>What is your role - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeOutUp {
            0% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-30px);
            }
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-enter {
            animation: fadeInScale 0.8s ease-out forwards;
        }

        .welcome-exit {
            animation: fadeOutUp 0.6s ease-in forwards;
        }

        .role-section-enter {
            animation: slideInUp 0.8s ease-out forwards;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .card-hover:hover:not(.card-selected) {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .dark .card-hover:hover:not(.card-selected) {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        .card-selected {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.3), 0 10px 10px -5px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        .dark .card-selected {
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4), 0 10px 10px -5px rgba(99, 102, 241, 0.3);
        }

        .card-selected::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 1rem;
            z-index: -1;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 min-h-screen text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Welcome Message -->
    <div id="welcomeMessage"
        class="fixed inset-0 flex items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 z-50 transition-colors duration-300">
        <div class="text-center welcome-enter">
            <h1
                class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4">
                Welcome to Coursezy
            </h1>
            <p class="text-xl md:text-2xl text-light-text-secondary dark:text-dark-text-secondary font-medium">
                Thanks for signing in!
            </p>
        </div>
    </div>

    <!-- Role Selection Section -->
    <div id="roleSection" class="min-h-screen flex items-center justify-center px-4 py-8" style="display: none;">
        <div class="max-w-4xl w-full">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-light-text-primary dark:text-dark-text-primary mb-4">
                    What is your role?
                </h1>
                <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                    Choose how you'd like to use Coursezy
                </p>
            </div>

            <form action="{{ route('roll.store') }}" method="POST" id="roleForm">
                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <!-- Student Card -->
                    <div class="role-card card-hover relative bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-2xl p-8 border-2 border-light-border-default dark:border-dark-border-default cursor-pointer group transition-colors duration-300"
                        data-role="student">
                        <input type="radio" name="roll" id="student" value="student" class="sr-only">

                        <div class="text-center">
                            <!-- Student Icon -->
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center group-hover:from-blue-200 group-hover:to-indigo-200 dark:group-hover:from-blue-800/40 dark:group-hover:to-indigo-800/40 transition-colors duration-300">
                                <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-3">
                                Student</h3>
                            <p class="text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                                Learn new skills and advance your career with expert-led courses
                            </p>
                        </div>

                        <!-- Selection Indicator -->
                        <div
                            class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 border-light-border-default dark:border-dark-border-default flex items-center justify-center transition-all duration-300">
                            <div
                                class="w-3 h-3 bg-light-accent-secondary dark:bg-indigo-400 rounded-full opacity-0 scale-0 transition-all duration-300">
                            </div>
                        </div>
                    </div>

                    <!-- Coach Card -->
                    <div class="role-card card-hover relative bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-2xl p-8 border-2 border-light-border-default dark:border-dark-border-default cursor-pointer group transition-colors duration-300"
                        data-role="coach">
                        <input type="radio" name="roll" id="coach" value="coach" class="sr-only">

                        <div class="text-center">
                            <!-- Coach Icon -->
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center group-hover:from-purple-200 group-hover:to-pink-200 dark:group-hover:from-purple-800/40 dark:group-hover:to-pink-800/40 transition-colors duration-300">
                                <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary mb-3">
                                Coach</h3>
                            <p class="text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                                Share your expertise and earn by creating and selling courses
                            </p>
                        </div>

                        <!-- Selection Indicator -->
                        <div
                            class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 border-light-border-default dark:border-dark-border-default flex items-center justify-center transition-all duration-300">
                            <div
                                class="w-3 h-3 bg-purple-600 dark:bg-purple-400 rounded-full opacity-0 scale-0 transition-all duration-300">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" id="submitBtn"
                        class="px-8 py-4 text-lg font-semibold text-dark-text-primary bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 dark:from-indigo-500 dark:to-purple-500 dark:hover:from-indigo-600 dark:hover:to-purple-600 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 opacity-50 cursor-not-allowed"
                        disabled>
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Welcome message animation and timing
        setTimeout(() => {
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.classList.add('welcome-exit');

            setTimeout(() => {
                welcomeMessage.remove();

                const roleSection = document.getElementById('roleSection');
                roleSection.style.display = 'flex';
                roleSection.classList.add('role-section-enter');
            }, 600);
        }, 3000);

        // Role selection functionality
        let selectedRole = null;
        const roleCards = document.querySelectorAll('.role-card');
        const submitBtn = document.getElementById('submitBtn');

        roleCards.forEach(card => {
            card.addEventListener('click', () => {
                const role = card.dataset.role;

                // Remove selection from all cards
                roleCards.forEach(c => {
                    c.classList.remove('card-selected');
                    const indicator = c.querySelector('.absolute.top-4.right-4 div');
                    const border = c.querySelector('.absolute.top-4.right-4');
                    indicator.classList.remove('opacity-100', 'scale-100');
                    indicator.classList.add('opacity-0', 'scale-0');
                    border.classList.remove('border-light-border-default');
                    border.classList.add('border-light-border-default', 'dark:border-dark-border-default');
                });

                // Select current card
                card.classList.add('card-selected');
                const indicator = card.querySelector('.absolute.top-4.right-4 div');
                const border = card.querySelector('.absolute.top-4.right-4');
                indicator.classList.remove('opacity-0', 'scale-0');
                indicator.classList.add('opacity-100', 'scale-100');
                border.classList.remove('border-light-border-default', 'dark:border-dark-border-default');

                if (role === 'student') {
                    border.classList.add('border-indigo-600');
                } else {
                    border.classList.add('border-purple-600');
                }

                // Update radio input
                const radioInput = document.getElementById(role);
                radioInput.checked = true;
                selectedRole = role;

                // Enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.add('opacity-100', 'cursor-pointer');
            });
        });

        // Form submission
        document.getElementById('roleForm').addEventListener('submit', (e) => {
            if (!selectedRole) {
                e.preventDefault();
                alert('Please select a role to continue.');
            }
        });
    </script>
</body>

</html>