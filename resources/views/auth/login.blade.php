<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth @auth {{ (session('dark_mode', auth()->user()->dark_mode)) ? 'dark' : '' }} @endauth {{ !auth()->check() && session('dark_mode') ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300 min-h-screen flex items-center">
    <!-- Background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute top-20 left-10 w-20 h-20 bg-purple-200 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse">
        </div>
        <div
            class="absolute top-40 right-10 w-32 h-32 bg-indigo-200 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-2000">
        </div>
        <div
            class="absolute bottom-20 left-1/4 w-24 h-24 bg-pink-200 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-normal filter blur-xl opacity-70 animate-pulse animation-delay-4000">
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <h1
                        class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Coursezy
                    </h1>
                    <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-dark-text-primary">Welcome back</h2>
                    <p class="mt-2 text-light-text-secondary dark:text-dark-text-secondary">
                        Continue your learning journey
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Login Card -->
                <div
                    class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-lg overflow-hidden border border-light-border-default dark:border-dark-border-default transition-all duration-300">
                    <div class="p-6 sm:p-8">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-5">
                                <label for="email"
                                    class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">
                                    {{ __('Email') }}
                                </label>
                                <input id="email" name="email" type="email"
                                    class="w-full px-4 py-2 rounded-lg border border-light-border-default dark:border-dark-border-default bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent transition-colors duration-300"
                                    :value="old('email')" required autofocus autocomplete="username">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <label for="password"
                                    class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-1">
                                    {{ __('Password') }}
                                </label>
                                <input id="password" name="password" type="password"
                                    class="w-full px-4 py-2 rounded-lg border border-light-border-default dark:border-dark-border-default bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-transparent transition-colors duration-300"
                                    required autocomplete="current-password">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between mb-6">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded border-light-border-default dark:border-dark-border-default text-indigo-600 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary dark:bg-gray-700 dark:focus:ring-indigo-600 transition-colors duration-300"
                                        name="remember">
                                    <span
                                        class="ms-2 text-sm text-light-text-secondary dark:text-dark-text-secondary">{{ __('Remember me') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-300">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-2 text-sm font-medium text-dark-text-primary bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Log in') }}
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div
                                    class="w-full border-t border-light-border-default dark:border-dark-border-default">
                                </div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span
                                    class="px-2 bg-light-bg-secondary dark:bg-dark-bg-secondary text-light-text-muted dark:text-dark-text-muted">
                                    Or continue with
                                </span>
                            </div>
                        </div>

                        <!-- Google Login Button -->
                        <a href="{{ route('google.redirect') }}"
                            class="w-full flex items-center justify-center gap-3 px-6 py-2.5 border border-light-border-default dark:border-dark-border-default rounded-lg text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            <span>Sign in with Google</span>
                        </a>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="mt-6 text-center text-sm text-light-text-muted dark:text-dark-text-muted">
                    Don't have an account? <a href="{{ route('register') }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Sign up</a>
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

        // Real-time validation for login form
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const submitButton = document.querySelector('button[type="submit"]');

            let validationState = {
                email: false,
                password: false
            };

            // Email validation
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Show error message
            function showError(input, message) {
                let errorElement = input.parentNode.querySelector('.js-error');
                if (!errorElement) {
                    errorElement = document.createElement('p');
                    errorElement.className = 'js-error mt-2 text-sm text-red-600 dark:text-red-400';
                    input.parentNode.appendChild(errorElement);
                }
                errorElement.textContent = message;
            }

            // Show success state
            function showSuccess(input) {
                const errorElement = input.parentNode.querySelector('.js-error');
                if (errorElement) {
                    errorElement.remove();
                }
            }

            // Update submit button state
            function updateSubmitButton() {
                const isValid = Object.values(validationState).every(state => state === true);
                submitButton.disabled = !isValid;

                if (isValid) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-light-accent-secondary/90', 'dark:hover:bg-light-accent-secondary');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-light-accent-secondary/90', 'dark:hover:bg-light-accent-secondary');
                }
            }

            // Email validation on input
            emailInput.addEventListener('input', function () {
                const email = this.value.trim();

                if (email === '') {
                    showError(this, 'Email is required');
                    validationState.email = false;
                } else if (!validateEmail(email)) {
                    showError(this, 'Please enter a valid email address');
                    validationState.email = false;
                } else {
                    showSuccess(this);
                    validationState.email = true;
                }

                updateSubmitButton();
            });

            // Password validation on input
            passwordInput.addEventListener('input', function () {
                const password = this.value;

                if (password === '') {
                    showError(this, 'Password is required');
                    validationState.password = false;
                } else if (password.length < 6) {
                    showError(this, 'Password must be at least 6 characters long');
                    validationState.password = false;
                } else {
                    showSuccess(this);
                    validationState.password = true;
                }

                updateSubmitButton();
            });

            // Form submission validation
            form.addEventListener('submit', function (e) {
                // Prevent submission if any field is invalid
                const isValid = Object.values(validationState).every(state => state === true);

                if (!isValid) {
                    e.preventDefault();

                    // Show error message
                    let formError = document.querySelector('.js-form-error');
                    if (!formError) {
                        formError = document.createElement('div');
                        formError.className = 'js-form-error mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg';
                        form.insertBefore(formError, form.firstChild);
                    }
                    formError.textContent = 'Please fix all errors before submitting the form.';

                    // Remove error after 5 seconds
                    setTimeout(() => {
                        if (formError) formError.remove();
                    }, 5000);

                    return false;
                }

                // Remove any existing form errors
                const formError = document.querySelector('.js-form-error');
                if (formError) formError.remove();

                // Show loading state
                submitButton.disabled = true;
                submitButton.textContent = 'Signing in...';
            });

            // Initial validation state
            updateSubmitButton();
        });
    </script>
</body>

</html>