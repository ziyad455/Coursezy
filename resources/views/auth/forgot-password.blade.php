<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Coursezy</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300 min-h-screen flex items-center">
    <!-- Background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-20 h-20 bg-purple-200 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-32 h-32 bg-indigo-200 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-2000"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-pink-200 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-4000"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Coursezy
                    </h1>
                    <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-dark-text-primary">Reset Password</h2>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Info Text -->
                <div class="mb-6 text-sm text-light-text-secondary dark:text-dark-text-secondary bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                    {{ __('Forgot your password? No problem. Just enter your email address and we\'ll email you a password reset link.') }}
                </div>

                <!-- Reset Card -->
                <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg overflow-hidden border border-light-border-default dark:border-dark-border-default transition-all duration-300">
                    <div class="p-6 sm:p-8">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">
                                    {{ __('Email') }}
                                </label>
                                <input id="email" name="email" type="email" 
                                       class="w-full px-4 py-2 rounded-lg border border-light-border-default dark:border-dark-border-default bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent transition-colors duration-300"
                                       :value="old('email')" required autofocus>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full px-6 py-2 text-sm font-medium text-dark-text-primary bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center text-sm text-light-text-muted dark:text-dark-text-muted">
                    Remember your password? <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Log in</a>
                </div>
            </div>
        </div>
    </div>

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
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>