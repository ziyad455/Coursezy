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

                            <div class="flex items-center justify-between">
                                <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-300">
                                    {{ __('Already registered?') }}
                                </a>

                                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
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


