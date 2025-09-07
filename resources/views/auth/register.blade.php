<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coursezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen flex items-center">
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
                    <h2 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">Create your account</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Join thousands of learners worldwide
                    </p>
                </div>

                <!-- Register Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300">
                    <div class="p-6 sm:p-8">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-5">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Name') }}
                                </label>
                                <input id="name" name="name" type="text" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-300"
                                       :value="old('name')" required autofocus autocomplete="name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-5">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Email') }}
                                </label>
                                <input id="email" name="email" type="email" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-300"
                                       :value="old('email')" required autocomplete="username">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Password') }}
                                </label>
                                <input id="password" name="password" type="password" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-300"
                                       required autocomplete="new-password">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Confirm Password') }}
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-300"
                                       required autocomplete="new-password">
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Register') }}
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                    Or sign up with
                                </span>
                            </div>
                        </div>
                        
                        <!-- Google Signup Button -->
                        <a href="{{ route('google.redirect') }}" 
                           class="w-full flex items-center justify-center gap-3 px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span>Sign up with Google</span>
                        </a>
                        
                        <!-- Already registered link -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-300">
                                {{ __('Already registered? Sign in') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    By registering, you agree to our <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Terms</a> and <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>.
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

        // Real-time validation for registration form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const submitButton = document.querySelector('button[type="submit"]');
            
            let validationState = {
                name: false,
                email: false,
                password: false,
                password_confirmation: false
            };

            // Validation functions
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function validatePassword(password) {
                // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/;
                return passwordRegex.test(password);
            }

            function validateName(name) {
                return name.trim().length >= 2 && /^[a-zA-Z\s]+$/.test(name.trim());
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
                    submitButton.classList.add('hover:bg-indigo-700', 'dark:hover:bg-indigo-600');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-indigo-700', 'dark:hover:bg-indigo-600');
                }
            }

            // Name validation
            nameInput.addEventListener('input', function() {
                const name = this.value.trim();
                
                if (name === '') {
                    showError(this, 'Name is required');
                    validationState.name = false;
                } else if (name.length < 2) {
                    showError(this, 'Name must be at least 2 characters long');
                    validationState.name = false;
                } else if (!validateName(name)) {
                    showError(this, 'Name can only contain letters and spaces');
                    validationState.name = false;
                } else {
                    showSuccess(this);
                    validationState.name = true;
                }
                
                updateSubmitButton();
            });

            // Email validation
            emailInput.addEventListener('input', function() {
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

            // Password validation
            passwordInput.addEventListener('input', function() {
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
                
                // Re-validate password confirmation if it has a value
                if (passwordConfirmationInput.value) {
                    passwordConfirmationInput.dispatchEvent(new Event('input'));
                }
                
                updateSubmitButton();
            });

            // Password confirmation validation
            passwordConfirmationInput.addEventListener('input', function() {
                const passwordConfirmation = this.value;
                const password = passwordInput.value;
                
                if (passwordConfirmation === '') {
                    showError(this, 'Password confirmation is required');
                    validationState.password_confirmation = false;
                } else if (passwordConfirmation !== password) {
                    showError(this, 'Passwords do not match');
                    validationState.password_confirmation = false;
                } else {
                    showSuccess(this);
                    validationState.password_confirmation = true;
                }
                
                updateSubmitButton();
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
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
                submitButton.textContent = 'Creating Account...';
            });

            // Initial validation state
            updateSubmitButton();
        });
    </script>
</body>
</html>


