<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Enrollment - Coursezy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulseSoft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
        }
        .dark .glass-effect {
            background: rgba(17, 24, 39, 0.95);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .dark .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }
        .input-focus {
            transition: all 0.2s ease-in-out;
        }
        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 text-light-text-primary dark:text-dark-text-primary font-sans min-h-screen">
    <!-- Navigation Bar -->
    <x-studentNav/>

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-32 w-96 h-96 bg-gradient-to-br from-blue-400/20 to-indigo-600/20 rounded-full blur-3xl animate-pulse-soft"></div>
        <div class="absolute -bottom-40 -left-32 w-96 h-96 bg-gradient-to-tr from-purple-400/20 to-pink-600/20 rounded-full blur-3xl animate-pulse-soft" style="animation-delay: 1s;"></div>
    </div>

    <!-- Main Content -->
    <main class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-16">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in">
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/30 rounded-full text-blue-600 dark:text-blue-400 text-sm font-medium mb-4">
                <i class="fas fa-lock mr-2"></i>
                Secure Checkout
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-4">
                Complete Your Enrollment
            </h1>
            <p class="text-xl text-light-text-secondary dark:text-dark-text-secondary max-w-2xl mx-auto">
                You're one step away from accessing <span class="font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">canve cours</span>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Payment Form -->
            <div class="lg:col-span-2 animate-slide-up">
                <div class="glass-effect rounded-2xl card-shadow border border-white/20 dark:border-dark-border-default/50 p-8 lg:p-10">
                    <div class="flex items-center mb-8">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-credit-card text-dark-text-primary"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-light-text-primary dark:text-dark-text-primary">Payment Details</h2>
                    </div>
                    
                <form class="space-y-6" method="POST" action="/payment">
                    @csrf
                    <!-- Card Number -->
                    <div class="space-y-2">
                        <label for="card-number" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Card Number</label>
                        <div class="relative group">
                            <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" 
                                class="input-focus w-full px-4 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-2 border-gray-200 dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 text-lg font-medium transition-all duration-200">
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 flex space-x-2">
                                <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                                <i class="fab fa-cc-amex text-2xl text-blue-500"></i>
                            </div>
                        </div>
                        @error('card_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <!-- Card Details Row -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="expiry-date" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Expiry Date</label>
                            <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" 
                                class="input-focus w-full px-4 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-2 border-gray-200 dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 text-lg font-medium transition-all duration-200">
                            @error('expiry_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="cvc" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Security Code</label>
                            <div class="relative">
                                <input type="text" id="cvc" name="cvc" placeholder="123" 
                                    class="input-focus w-full px-4 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-2 border-gray-200 dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 text-lg font-medium transition-all duration-200">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-question-circle text-dark-text-secondary hover:text-light-text-secondary cursor-help" title="3-digit code on back of card"></i>
                                </div>
                            </div>
                            @error('cvc')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Name on Card -->
                    <div class="space-y-2">
                        <label for="card-name" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Cardholder Name</label>
                        <input type="text" id="card-name" placeholder="John Doe" name="card_name" 
                            class="input-focus w-full px-4 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-2 border-gray-200 dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 text-lg font-medium transition-all duration-200">
                        @error('card_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="space-y-2">
                        <label for="country" class="block text-sm font-semibold text-light-text-secondary dark:text-dark-text-secondary">Country/Region</label>
                        <div class="relative">
                            <select name="country" id="country" class="input-focus w-full px-4 py-4 bg-light-bg-secondary dark:bg-dark-bg-secondary border-2 border-gray-200 dark:border-dark-border-default rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 text-lg font-medium transition-all duration-200 appearance-none">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>United Kingdom</option>
                                <option>Australia</option>
                                <option>Other</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-text-secondary pointer-events-none"></i>
                        </div>
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-dark-bg-secondary/50 rounded-xl">
                        <div class="flex items-center h-6">
                            <input id="terms" name="terms" type="checkbox" class="w-5 h-5 text-blue-600 bg-light-bg-tertiary border-light-border-default rounded-lg focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-dark-border-default">
                        </div>
                        <label for="terms" class="text-sm text-light-text-secondary dark:text-dark-text-secondary leading-relaxed">
                            I agree to the <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Terms of Service</a> and <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Privacy Policy</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" class="group w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-dark-text-primary font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-2xl shadow-lg">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-lock"></i>
                            <span>Complete Payment - ${{ number_format($course->price, 2) }}</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </button>

                    <!-- Security Note -->
                    <div class="flex items-center justify-center space-x-2 text-light-text-muted dark:text-dark-text-muted pt-4">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span class="text-sm">SSL encrypted • 256-bit security</span>
                    </div>
                </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="animate-slide-up" style="animation-delay: 0.2s;">
                <div class="glass-effect rounded-2xl card-shadow border border-white/20 dark:border-dark-border-default/50 p-8 sticky top-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-receipt text-dark-text-primary"></i>
                        </div>
                        <h2 class="text-xl font-bold text-light-text-primary dark:text-dark-text-primary">Order Summary</h2>
                    </div>
                    
                    <!-- Course Preview -->
                    <div class="mb-8">
                        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl border border-gray-200 dark:border-dark-border-default overflow-hidden">
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $course->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-light-text-primary dark:text-dark-text-primary text-lg mb-2 line-clamp-2">{{ $course->title }}</h3>
                                <div class="flex items-center text-light-text-secondary dark:text-dark-text-secondary text-sm">
                                    <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-dark-text-primary text-xs"></i>
                                    </div>
                                    <span>{{ $course->coach->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-light-text-secondary dark:text-dark-text-secondary">Course Price</span>
                            <span class="font-semibold text-light-text-primary dark:text-dark-text-primary">${{ number_format($course->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-light-text-secondary dark:text-dark-text-secondary flex items-center">
                                <i class="fas fa-tags mr-2 text-green-500"></i>
                                Special Discount
                            </span>
                            <span class="font-semibold text-green-600 dark:text-green-400">-${{ number_format($course->price * 0.5, 2) }}</span>
                        </div>
                        <div class="border-t border-light-border-default dark:border-dark-border-default pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-light-text-primary dark:text-dark-text-primary">Total</span>
                                <div class="text-right">
                                    <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                        ${{ number_format($course->price * 0.5, 2) }}
                                    </div>
                                    <div class="text-sm text-light-text-muted line-through">${{ number_format($course->price, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guarantee -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl p-4 mb-6 border border-green-200 dark:border-green-800">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-800 dark:text-green-300 mb-1">Money-Back Guarantee</h4>
                                <p class="text-sm text-green-700 dark:text-green-400">30-day full refund if you're not satisfied</p>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-3">
                        <h4 class="font-semibold text-light-text-primary dark:text-dark-text-primary mb-3">What's included:</h4>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3 text-sm">
                                <i class="fas fa-play-circle text-blue-500"></i>
                                <span class="text-light-text-secondary dark:text-dark-text-secondary">Lifetime access to course content</span>
                            </div>
                            <div class="flex items-center space-x-3 text-sm">
                                <i class="fas fa-certificate text-blue-500"></i>
                                <span class="text-light-text-secondary dark:text-dark-text-secondary">Certificate of completion</span>
                            </div>
                            <div class="flex items-center space-x-3 text-sm">
                                <i class="fas fa-mobile-alt text-blue-500"></i>
                                <span class="text-light-text-secondary dark:text-dark-text-secondary">Mobile and desktop access</span>
                            </div>
                            <div class="flex items-center space-x-3 text-sm">
                                <i class="fas fa-headset text-blue-500"></i>
                                <span class="text-light-text-secondary dark:text-dark-text-secondary">24/7 student support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="mt-16 text-center animate-fade-in" style="animation-delay: 0.4s;">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-lg font-semibold text-light-text-primary dark:text-dark-text-primary mb-8">Trusted by thousands of students worldwide</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center opacity-60">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-shield-alt text-green-500 text-2xl"></i>
                        <span class="font-medium">SSL Secure</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fab fa-cc-visa text-blue-600 text-2xl"></i>
                        <span class="font-medium">Visa Verified</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-lock text-light-text-secondary text-2xl"></i>
                        <span class="font-medium">256-bit Encryption</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-award text-yellow-500 text-2xl"></i>
                        <span class="font-medium">Trusted Platform</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-dark-text-primary mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-6 mb-6">
                    <i class="fab fa-cc-visa text-3xl opacity-70"></i>
                    <i class="fab fa-cc-mastercard text-3xl opacity-70"></i>
                    <i class="fab fa-cc-amex text-3xl opacity-70"></i>
                    <i class="fab fa-cc-paypal text-3xl opacity-70"></i>
                </div>
                <p class="text-dark-text-secondary text-sm mb-2">© 2025 Coursezy. All rights reserved.</p>
                <p class="text-light-text-muted text-xs">Secure payments powered by industry-leading encryption</p>
            </div>
        </div>
    </footer>

    <script>
        // Dark mode toggle functionality
        // let isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        // function toggleDarkMode() {
        //     isDarkMode = !isDarkMode;
        //     localStorage.setItem('darkMode', isDarkMode);
        //     updateDarkMode();
        // }

        // function updateDarkMode() {
        //     if (isDarkMode) {
        //         document.documentElement.classList.add('dark');
        //     } else {
        //         document.documentElement.classList.remove('dark');
        //     }
        // }

        // Initialize dark mode on load
        document.addEventListener('DOMContentLoaded', function() {
            // Check system preference if no saved preference
            // if (localStorage.getItem('darkMode') === null) {
            //     isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            // }
            // updateDarkMode();

            // Card number formatting
            const cardNumberInput = document.getElementById('card-number');
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                e.target.value = value;
            });

            // Expiry date formatting
            const expiryInput = document.getElementById('expiry-date');
            expiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substring(0,2) + '/' + value.substring(2,4);
                }
                e.target.value = value;
            });

            // CVC validation
            const cvcInput = document.getElementById('cvc');
            cvcInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
            });

            // Form validation visual feedback
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('border-green-300');
                        this.classList.remove('border-light-border-default');
                    } else {
                        this.classList.remove('border-green-300');
                        this.classList.add('border-light-border-default');
                    }
                });
            });
        });
    </script>
</body>
</html>