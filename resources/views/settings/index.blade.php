@extends('layouts.app')

@section('custom_nav')
    @php($role = auth()->user()->role ?? null)
    @if($role === 'student')
        <x-studentNav />
    @elseif($role === 'coach')
        <x-coachNav />
    @else
        @include('layouts.navigation')
    @endif
@endsection

@section('content')
<div class="py-12 bg-light-bg-primary dark:bg-dark-bg-primary min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-light-text-primary dark:text-dark-text-primary">Settings</h1>
            <p class="text-light-text-secondary dark:text-dark-text-secondary mt-2">Manage your account settings and preferences</p>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Password Management Section -->
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-light-text-secondary dark:text-dark-text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary">Password Management</h2>
                </div>

                @if ($hasGoogleAccount && !$hasPassword)
                    <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    <strong>Google Account User:</strong> You signed in with Google and don't have a password set. You can create a password below to enable traditional login.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Current Password (only if user has a password) -->
                    @if ($hasPassword)
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                                Current Password
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="block w-full px-3 py-2 border border-light-border-default dark:border-dark-border-default rounded-lg shadow-sm focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary"
                                   required>
                            @error('current_password')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            {{ $hasPassword ? 'New Password' : 'Create Password' }}
                        </label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="block w-full px-3 py-2 border border-light-border-default dark:border-dark-border-default rounded-lg shadow-sm focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary"
                               required>
                        @error('new_password')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary mb-2">
                            Confirm {{ $hasPassword ? 'New ' : '' }}Password
                        </label>
                        <input type="password" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               class="block w-full px-3 py-2 border border-light-border-default dark:border-dark-border-default rounded-lg shadow-sm focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:bg-gray-700 dark:text-dark-text-primary"
                               required>
                        @error('new_password_confirmation')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-light-accent-secondary hover:bg-light-accent-secondary/90 text-dark-text-primary font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:ring-offset-2">
                        {{ $hasPassword ? 'Update Password' : 'Create Password' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Appearance Section -->
        <div class="bg-light-bg-secondary dark:bg-dark-bg-secondary shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-light-text-secondary dark:text-dark-text-secondary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5a2 2 0 00-2 2v12a4 4 0 004 4h2m-6-4h2m2-5h2m-2-3h2"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary">Appearance</h2>
                </div>

                <form method="POST" action="{{ route('settings.appearance.update') }}" id="appearance-form">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center justify-between py-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-light-text-primary dark:text-dark-text-primary">Dark Mode</h3>
                            <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Toggle between light and dark theme</p>
                        </div>

                        <div class="flex items-center">
                            <!-- Dark Mode Toggle -->
                            <button type="button" 
                                    onclick="toggleDarkModeSetting()" 
                                    class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary focus:ring-offset-2"
                                    id="dark-mode-toggle"
                                    data-current="{{ (session('dark_mode', $user->dark_mode)) ? 'true' : 'false' }}">
                                <span class="sr-only">Toggle dark mode</span>
                                <span class="h-6 w-6 transform rounded-full bg-light-bg-secondary shadow-lg transition-transform duration-300 flex items-center justify-center"
                                      id="toggle-indicator">
                                    <svg class="h-3 w-3 text-yellow-500" id="sun-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg class="h-3 w-3 text-blue-400 hidden" id="moon-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                    </svg>
                                </span>
                            </button>

                            <input type="hidden" name="dark_mode" id="dark-mode-input" value="{{ (session('dark_mode', $user->dark_mode)) ? '1' : '0' }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDarkModeSetting() {
    const toggle = document.getElementById('dark-mode-toggle');
    const input = document.getElementById('dark-mode-input');
    const indicator = document.getElementById('toggle-indicator');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    const form = document.getElementById('appearance-form');
    
    const currentMode = toggle.dataset.current === 'true';
    const newMode = !currentMode;
    
    // Update the toggle appearance
    if (newMode) {
        // Dark mode ON
        toggle.classList.remove('bg-gray-200');
        toggle.classList.add('bg-light-accent-secondary');
        indicator.classList.remove('translate-x-1');
        indicator.classList.add('translate-x-7');
        indicator.classList.remove('bg-light-bg-secondary');
        indicator.classList.add('bg-dark-bg-primary');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
    } else {
        // Dark mode OFF  
        toggle.classList.remove('bg-light-accent-secondary');
        toggle.classList.add('bg-gray-200');
        indicator.classList.remove('translate-x-7');
        indicator.classList.add('translate-x-1');
        indicator.classList.remove('bg-dark-bg-primary');
        indicator.classList.add('bg-light-bg-secondary');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
    }
    
    // Update data attribute and input
    toggle.dataset.current = newMode;
    input.value = newMode ? '1' : '0';
    
    // Apply dark mode to document immediately for preview
    if (newMode) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    }
    
    // Submit form to save preference
    form.submit();
}

// Initialize toggle appearance based on current user preference
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('dark-mode-toggle');
    const indicator = document.getElementById('toggle-indicator');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    const currentMode = toggle.dataset.current === 'true';
    
    if (currentMode) {
        // Set dark mode appearance
        toggle.classList.add('bg-light-accent-secondary');
        indicator.classList.add('translate-x-7', 'bg-dark-bg-primary');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
        document.documentElement.classList.add('dark');
    } else {
        // Set light mode appearance
        toggle.classList.add('bg-gray-200');
        indicator.classList.add('translate-x-1', 'bg-light-bg-secondary');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
        document.documentElement.classList.remove('dark');
    }
});
</script>
@endsection