<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - Coursezy</title>
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
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
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
            overflow: hidden;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .profile-image-container:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image-container:hover .upload-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
<!-- Navigation -->
<x-coachNav/>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Profile Settings
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Manage your professional profile and information
            </p>
        </div>

        <!-- Profile Form -->
        <form action="/accont" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')


            <!-- Profile Picture Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Profile Picture</h2>
                
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    @php
                        $user = Auth::user();
                    @endphp
                    @if ($user->profile_photo == null)
                        <!-- Current Profile Image - No Photo -->
                        <div class="profile-image-container w-32 h-32 bg-black flex items-center justify-center text-white text-4xl font-bold cursor-pointer">
                            <img id="profile-preview" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : '' }}" 
                                 alt="Profile Picture" class="w-full h-full object-cover {{ Auth::user()->profile_photo ? '' : 'hidden' }}">
                            <span id="profile-initials" class="{{ Auth::user()->profile_photo ? 'hidden' : '' }}">
                                @php
                                    $name = Auth::user()->name ?? 'User';
                                    $nameParts = explode(' ', trim($name));
                                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                                    if (count($nameParts) > 1) {
                                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                    }
                                @endphp
                                {{ $initials }}
                            </span>
                            <div class="upload-overlay">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    @else
                        <!-- Current Profile Image - Has Photo -->
                        <div class="profile-image-container w-32 h-32 cursor-pointer">
                            <img id="profile-preview" src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="Profile Picture" class="w-full h-full object-cover rounded-full">
                            <span id="profile-initials" class="hidden">
                                @php
                                    $name = Auth::user()->name ?? 'User';
                                    $nameParts = explode(' ', trim($name));
                                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                                    if (count($nameParts) > 1) {
                                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                    }
                                @endphp
                                {{ $initials }}
                            </span>
                            <div class="upload-overlay">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    @endif


                    <!-- Upload Controls -->
                    <div class="flex-1 space-y-4">
                        <input type="file" id="profile-picture" name="profile_picture" accept="image/*" class="hidden">
                        
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="document.getElementById('profile-picture').click()" 
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Upload Photo
                            </button>
                            
                            <button type="button" id="remove-picture-btn" onclick="removeProfilePicture()" 
                                    class="px-4 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 font-medium rounded-lg transition-colors {{ Auth::user()->profile_picture ? '' : 'hidden' }}">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                        
                        <input type="hidden" id="remove-picture" name="remove_picture" value="0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            JPG, PNG or GIF. Max file size 2MB. Recommended dimensions: 400x400px.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Personal Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name', Auth::user()->name ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200">
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" readonly
                               value="{{ Auth::user()->email ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Email cannot be changed from this page</p>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">About</h2>
                
                <!-- About -->
                <div class="mb-6">
                    <label for="about" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tell us about yourself
                    </label>
                    <div class="relative">
                        <textarea id="about_you" name="about_you" rows="4" maxlength="300"
                               placeholder="Share your background, experience, teaching philosophy, or what makes you passionate about your field..."
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200 resize-vertical"
                               oninput="updateCharCount('about_you', 'about-count', 300)">{{ old('about_you', Auth::user()->about_you ?? '') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                            <span id="about-count">{{ strlen(Auth::user()->about_you ?? '') }}</span>/300
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        This will be displayed on your public profile to help students learn more about you.
                    </p>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Professional Information</h2>
                
                <!-- Skills Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Skills & Expertise
                    </label>
                    
                    <!-- Skills Input -->
                    <div class="flex mb-4">
                        <input type="text" id="skill-input" placeholder="Add a skill (e.g., JavaScript, Leadership, etc.)"
                               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all duration-200">
                        <button type="button" onclick="addSkill()" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-r-lg transition-colors">
                            Add
                        </button>
                    </div>

                    <!-- Skills Display - loaded from database -->
                    <div id="skills-container" class="flex flex-wrap gap-2 min-h-[50px] p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                        @forelse(Auth::user()->skills ?? [] as $skill)
                            <span class="skill-tag inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 border border-indigo-200 dark:border-indigo-700">
                                {{ $skill->name }}
                                <button type="button" onclick="removeSkill('{{ $skill->name }}')" class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No skills added yet. Add your first skill above.</p>
                        @endforelse
                    </div>

                    {{-- Remove hidden input since we're not using form submission for skills --}}

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <button type="button" onclick="resetForm()" 
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Reset Changes
                </button>
                <button type="submit" id="save-btn"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Profile
                </button>
            </div>
        </form>
    </main>

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Skills management - simplified for database-only approach

        // Profile picture handling (keep original functionality)
        document.getElementById('profile-picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-preview');
                    const initials = document.getElementById('profile-initials');
                    const removeBtn = document.getElementById('remove-picture-btn');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    initials.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                    
                    // Reset remove flag
                    document.getElementById('remove-picture').value = '0';
                };
                reader.readAsDataURL(file);
            }
        });

        function removeProfilePicture() {
            const preview = document.getElementById('profile-preview');
            const initials = document.getElementById('profile-initials');
            const removeBtn = document.getElementById('remove-picture-btn');
            const fileInput = document.getElementById('profile-picture');
            
            preview.classList.add('hidden');
            initials.classList.remove('hidden');
            removeBtn.classList.add('hidden');
            fileInput.value = '';
            
            // Update initials display when removing picture
            const name = document.getElementById('name').value || 'User';
            const nameParts = name.trim().split(' ');
            let displayInitials = nameParts[0].charAt(0).toUpperCase();
            if (nameParts.length > 1) {
                displayInitials += nameParts[1].charAt(0).toUpperCase();
            }
            initials.textContent = displayInitials;
            
            // Set remove flag
            document.getElementById('remove-picture').value = '1';
        }

        // Add skill function - direct database save
        function addSkill() {
            const input = document.getElementById('skill-input');
            const skill = input.value.trim();
            
            if (skill) {
                // Save directly to database
                const skillsContainer = document.getElementById('skills-container');
                skillsContainer.innerHTML += `<span class="skill-tag inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 border border-indigo-200 dark:border-indigo-700">
                                ${skill}
                                <button type="button" onclick="removeSkill('${skill}')" class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>`




                saveSkillToDatabase(skill);
                input.value = '';
            }
        }

        // Remove skill function - direct database delete
        function removeSkill(skill) {
            // First remove from DOM
            const skillTags = document.querySelectorAll('.skill-tag');
            skillTags.forEach(tag => {
                if (tag.textContent.trim().includes(skill)) {
                    tag.remove();
                }
            });
            
            // Check if no skills left, show empty message
            const skillsContainer = document.getElementById('skills-container');
            if (skillsContainer.children.length === 0) {
                skillsContainer.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm">No skills added yet. Add your first skill above.</p>';
            }
            
            // Then delete from database
            deleteSkillFromDatabase(skill);
        }

        // Database integration functions
        function saveSkillToDatabase(skill) {
            fetch('/api/skills', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ skill: skill }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Skill saved:', data);
            })
            .catch(error => {
                console.error('Error saving skill:', error);
                showMessage('Error saving skill', 'error');
            });
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
        document.getElementById('skill-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });

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
            
            messageEl.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 transform transition-all duration-300 translate-x-full`;
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
        document.querySelector('form').addEventListener('submit', function(e) {
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
                counter.classList.remove('text-gray-400');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-gray-400');
            }
        }

        // Initialize character counter on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize about character counter
            updateCharCount('about_you', 'about-count', 300);
            
            // Update initials when name changes
            document.getElementById('name').addEventListener('input', function() {
                const name = this.value || 'User';
                const nameParts = name.trim().split(' ');
                let displayInitials = nameParts[0].charAt(0).toUpperCase();
                if (nameParts.length > 1) {
                    displayInitials += nameParts[1].charAt(0).toUpperCase();
                }
                document.getElementById('profile-initials').textContent = displayInitials;
            });

            // Add event listener for skill input (Enter key)
            document.getElementById('skill-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSkill();
                }
            });
        });
    </script>
</body>
</html>
