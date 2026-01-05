<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ (session('dark_mode', auth()->user()->dark_mode ?? false)) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Account - Coursezy</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .skill-tag {
            transition: all 0.3s ease;
        }

        .skill-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .profile-image-container {
            position: relative;
            border-radius: 50%;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .profile-image-container:hover {
            transform: scale(1.03);
        }

        .profile-image-container:active {
            transform: scale(0.98);
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.95), rgba(168, 85, 247, 0.95));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            backdrop-filter: blur(4px);
        }

        .profile-image-container:hover .upload-overlay {
            opacity: 1;
        }

        .profile-image-container img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        /* Smooth gradient animation */
        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .profile-image-container .group:hover .bg-gradient-to-tr {
            animation: gradientShift 3s ease infinite;
            background-size: 200% 200%;
        }

        /* Input field enhancements */
        input:not([readonly]):focus,
        textarea:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        input:not([readonly]),
        textarea {
            position: relative;
        }

        /* Pulse animation for editable fields on page load */
        @keyframes subtlePulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            }
        }

        .editable-field-hint {
            animation: subtlePulse 2s ease-in-out 1;
            animation-delay: 0.8s;
        }
    </style>
</head>

<body
    class="bg-light-bg-primary dark:bg-dark-bg-primary text-light-text-primary dark:text-dark-text-primary transition-colors duration-300">
    <!-- Navigation -->
    <x-studentNav />

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8 opacity-0 animate-fade-in">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Profile Settings
                    </h1>
                    <p class="text-lg text-light-text-secondary dark:text-dark-text-secondary">
                        Manage your professional profile and information
                    </p>
                </div>
                <div
                    class="flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-green-700 dark:text-green-300">Current Profile Loaded</span>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <form action="/student/accont" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')

            <!-- Info Banner -->
            <div
                class="bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 p-4 rounded-r-lg opacity-0 animate-fade-in">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-indigo-800 dark:text-indigo-300 mb-1">
                            Viewing Your Current Profile
                        </h3>
                        <p class="text-sm text-indigo-700 dark:text-indigo-400">
                            All fields below show your current saved information. Make any changes you'd like and click
                            "Save Profile" when you're done.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Picture Section -->
            <div
                class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle overflow-hidden opacity-0 animate-fade-in stagger-1">
                <div class="p-6 sm:p-8">
                    <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">Profile
                        Picture</h2>
                    <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary mb-6">Update your profile
                        photo to personalize your account</p>

                    <div class="flex flex-col items-center">
                        @php
                            $user = Auth::user();
                        @endphp

                        <!-- Profile Image with Ring -->
                        <div class="relative mb-6">
                            <div class="profile-image-container w-40 h-40 cursor-pointer relative group"
                                onclick="document.getElementById('profile-picture').click()" role="button" tabindex="0"
                                aria-label="Change profile picture">
                                <!-- Decorative Ring -->
                                <div
                                    class="absolute inset-0 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 opacity-20 group-hover:opacity-30 transition-opacity duration-300 pointer-events-none">
                                </div>
                                <div
                                    class="absolute inset-1 rounded-full bg-light-bg-secondary dark:bg-dark-bg-secondary pointer-events-none">
                                </div>

                                <!-- Image Container - Current Photo Displayed Here -->
                                <div class="absolute inset-2 rounded-full overflow-hidden">
                                    <div id="profile-photo-display"
                                        class="w-full h-full flex items-center justify-center">
                                        @if($user->profile_photo)
                                            @if(Str::startsWith($user->profile_photo, 'https://'))
                                                <img src="{{ $user->profile_photo }}" alt="{{ $user->name }}"
                                                    class="w-full h-full object-cover rounded-full">
                                            @else
                                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                    alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                                            @endif
                                        @else
                                            @php
                                                $names = explode(' ', $user->name ?? 'User');
                                                $firstInitial = strtoupper(substr($names[0] ?? 'U', 0, 1));
                                                $lastInitial = isset($names[1]) ? strtoupper(substr($names[1], 0, 1)) : '';
                                            @endphp
                                            <div
                                                class="w-full h-full rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-3xl">
                                                {{ $firstInitial }}{{ $lastInitial }}
                                            </div>
                                        @endif
                                    </div>
                                    <img id="profile-preview" src="" alt="Profile Picture" loading="lazy"
                                        class="w-full h-full object-cover rounded-full hidden">
                                </div>

                                <!-- Hover Overlay -->
                                <div class="upload-overlay rounded-full">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-white mb-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-white text-xs font-medium">Change Photo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Controls -->
                        <div class="w-full max-w-md space-y-4">
                            <input type="file" id="profile-picture" name="profile_picture" accept="image/*"
                                class="hidden">

                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <button type="button" onclick="document.getElementById('profile-picture').click()"
                                    class="flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Upload New Photo
                                </button>

                                <button type="button" id="remove-picture-btn" onclick="removeProfilePicture()"
                                    class="flex items-center justify-center px-6 py-2.5 bg-white dark:bg-gray-700 border-2 border-red-300 dark:border-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 font-medium rounded-lg transition-all duration-200 {{ Auth::user()->profile_picture ? '' : 'hidden' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove Photo
                                </button>
                            </div>

                            <input type="hidden" id="remove-picture" name="remove_picture" value="0">

                            <!-- Info Box -->
                            <div
                                class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-3 mt-0.5 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-sm text-indigo-700 dark:text-indigo-300">
                                        <p class="font-medium mb-1">Photo Guidelines</p>
                                        <ul class="space-y-1 text-xs">
                                            <li>• Accepted formats: JPG, PNG, or GIF</li>
                                            <li>• Maximum file size: 2MB</li>
                                            <li>• Recommended: 400x400px square image</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div
                class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle p-6 opacity-0 animate-fade-in stagger-2">
                <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-6">Personal
                    Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            Full Name *
                        </label>
                        <input type="text" id="name" name="name" required
                            value="{{ old('name', Auth::user()->name ?? '') }}"
                            class="editable-field-hint w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary transition-all duration-200"
                            placeholder="Enter your full name">
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" readonly value="{{ Auth::user()->email ?? '' }}"
                            class="w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg bg-gray-50 dark:bg-gray-600 text-light-text-muted dark:text-dark-text-muted cursor-not-allowed">
                        <p class="text-xs text-light-text-muted dark:text-dark-text-muted mt-1">Email cannot be changed
                            from this page</p>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div
                class="bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-xl shadow-md border border-light-border-subtle dark:border-dark-border-subtle p-6 opacity-0 animate-fade-in stagger-3">
                <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-6">About</h2>

                <!-- About -->
                <div class="mb-6">
                    <label for="about"
                        class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                        Tell us about yourself
                    </label>
                    <div class="relative">
                        <textarea id="about_you" name="about_you" rows="4" maxlength="300"
                            placeholder="Share your background, experience, teaching philosophy, or what makes you passionate about your field..."
                            class="editable-field-hint w-full px-4 py-3 border border-light-border-default dark:border-dark-border-default rounded-lg focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-light-text-primary dark:text-dark-text-primary transition-all duration-200 resize-vertical"
                            oninput="updateCharCount('about_you', 'about-count', 300)">{{ old('about_you', Auth::user()->about_you ?? '') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-dark-text-secondary">
                            <span id="about-count">{{ strlen(Auth::user()->about_you ?? '') }}</span>/300
                        </div>
                    </div>
                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted mt-2">
                        This will be displayed on your public profile to help students learn more about you.
                    </p>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <button type="button" onclick="resetForm()"
                    class="px-6 py-3 border border-light-border-default dark:border-dark-border-default text-light-text-secondary dark:text-dark-text-secondary font-medium rounded-lg hover:bg-light-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                    Reset Changes
                </button>
                <button type="submit" id="save-btn"
                    class="px-8 py-3 bg-light-accent-secondary hover:bg-light-accent-secondary/90 dark:bg-dark-accent-secondary dark:hover:bg-dark-accent-secondary/90 text-dark-text-primary font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Profile
                </button>
            </div>
        </form>
    </main>
    <x-ai_chat />

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Skills management - simplified for database-only approach

        // Optimized profile picture handling
        const profilePictureInput = document.getElementById('profile-picture');
        const profilePreview = document.getElementById('profile-preview');
        const profileDisplay = document.getElementById('profile-photo-display');
        const removeBtn = document.getElementById('remove-picture-btn');
        const removePictureInput = document.getElementById('remove-picture');

        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validate file type and size
                if (!file.type.match('image.*')) {
                    showMessage('Please select an image file', 'error');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showMessage('File size must be less than 2MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (profilePreview) {
                        profilePreview.src = e.target.result;
                        profilePreview.classList.remove('hidden');
                    }
                    if (profileDisplay) {
                        profileDisplay.classList.add('hidden');
                    }
                    if (removeBtn) {
                        removeBtn.classList.remove('hidden');
                    }
                    if (removePictureInput) {
                        removePictureInput.value = '0';
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        function removeProfilePicture() {
            if (profilePreview) profilePreview.classList.add('hidden');
            if (profileDisplay) profileDisplay.classList.remove('hidden');
            if (removeBtn) removeBtn.classList.add('hidden');
            if (profilePictureInput) profilePictureInput.value = '';
            if (removePictureInput) removePictureInput.value = '1';

            // Update initials if present
            const initials = document.getElementById('profile-initials');
            const nameInput = document.getElementById('name');

            if (initials && nameInput) {
                const name = nameInput.value || 'User';
                const nameParts = name.trim().split(' ');
                let displayInitials = nameParts[0].charAt(0).toUpperCase();
                if (nameParts.length > 1) {
                    displayInitials += nameParts[1].charAt(0).toUpperCase();
                }
                initials.textContent = displayInitials;
            }
        }







        function deleteSkillFromDatabase(skill) {
            fetch('/api/skills/delete-by-name', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ skill: skill }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Skill deleted:', data);
                    showMessage('Skill deleted successfully', 'success');
                    // No need to reload page anymore since we removed it from DOM
                })
                .catch(error => {
                    console.error('Error deleting skill:', error);
                    showMessage('Error deleting skill', 'error');
                    // Re-add the skill to DOM if database deletion failed
                    location.reload();
                });
        }


        // Allow Enter key to add skill
        const skillInput = document.getElementById('skill-input');
        if (skillInput) {
            skillInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSkill();
                }
            });
        }

        // Reset form
        function resetForm() {
            if (confirm('Are you sure you want to reset all changes? This will revert all fields to their saved values.')) {
                location.reload();
            }
        }

        // Message system
        function showMessage(message, type = 'success') {
            const container = document.getElementById('message-container');
            const messageEl = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-green-500' :
                type === 'warning' ? 'bg-yellow-500' : 'bg-red-500';

            messageEl.className = `${bgColor} text-dark-text-primary px-6 py-3 rounded-lg shadow-lg mb-2 transform transition-all duration-300 translate-x-full`;
            messageEl.textContent = message;

            container.appendChild(messageEl);

            // Animate in
            setTimeout(() => {
                messageEl.classList.remove('translate-x-full');
            }, 10);

            // Remove after 3 seconds
            setTimeout(() => {
                messageEl.classList.add('translate-x-full');
                setTimeout(() => {
                    if (messageEl.parentNode) {
                        messageEl.parentNode.removeChild(messageEl);
                    }
                }, 300);
            }, 3000);
        }

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function (e) {
            const saveBtn = document.getElementById('save-btn');
            saveBtn.innerHTML = '<svg class="w-5 h-5 mr-2 inline animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
            saveBtn.disabled = true;
        });

        // Character counter function
        function updateCharCount(inputId, countId, maxLength) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            const currentLength = input.value.length;

            counter.textContent = currentLength;

            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-dark-text-secondary');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-dark-text-secondary');
            }
        }

        // Track if form has been modified
        let formModified = false;

        function trackFormChanges() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input:not([type="hidden"]):not([readonly]), textarea');

            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    if (!formModified) {
                        formModified = true;
                        // Update save button to show changes detected
                        const saveBtn = document.getElementById('save-btn');
                        if (saveBtn && !saveBtn.classList.contains('changes-detected')) {
                            saveBtn.classList.add('changes-detected');
                            saveBtn.innerHTML = '<svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save Changes';
                        }
                    }
                });
            });
        }

        // Initialize character counter on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize about character counter
            const aboutInput = document.getElementById('about_you');
            if (aboutInput) {
                updateCharCount('about_you', 'about-count', 300);
            }

            // Update initials when name changes
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.addEventListener('input', function () {
                    const name = this.value || 'User';
                    const nameParts = name.trim().split(' ');
                    let displayInitials = nameParts[0].charAt(0).toUpperCase();
                    if (nameParts.length > 1) {
                        displayInitials += nameParts[1].charAt(0).toUpperCase();
                    }
                    const initialsElement = document.getElementById('profile-initials');
                    if (initialsElement) {
                        initialsElement.textContent = displayInitials;
                    }
                });
            }

            // Track form changes
            trackFormChanges();

            // Show subtle confirmation that profile data is loaded
            setTimeout(() => {
                const nameField = document.getElementById('name');
                if (nameField && nameField.value) {
                    // Data is present, profile loaded successfully
                    console.log('Profile data loaded successfully');
                }
            }, 500);
        });
    </script>
</body>

</html>