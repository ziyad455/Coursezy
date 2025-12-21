@props([
    'user' => null,
    'size' => 'md',
    'showName' => false,
    'dropdown' => false
])

@php
    // Define size classes
    $sizeClasses = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        '2xl' => 'w-20 h-20 text-xl'
    ];
    
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    
    // Get user initials
    $names = explode(' ', $user->name ?? 'User');
    $firstInitial = strtoupper(substr($names[0] ?? 'U', 0, 1));
    $lastInitial = isset($names[1]) ? strtoupper(substr($names[1], 0, 1)) : '';
    
    // Determine photo URL
    $photoUrl = null;
    if ($user && $user->profile_photo) {
        if (Str::startsWith($user->profile_photo,'https://')) {
            $photoUrl = $user->profile_photo;
        } else {
            $photoUrl = asset('storage/' . $user->profile_photo);
        }
    }
@endphp

@if($dropdown)
    <!-- Profile with Dropdown -->
    <div class="relative">
        <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" 
                     alt="{{ $user->name }}" 
                     class="{{ $currentSize }} rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
            @else
                <div class="{{ $currentSize }} rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-dark-text-primary flex items-center justify-center font-bold shadow-sm hover:shadow-md transition-all duration-200 border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500">
                    {{ $firstInitial }}{{ $lastInitial }}
                </div>
            @endif
            
            @if($showName)
                <span class="hidden md:block text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">{{ $user->name }}</span>
                <svg class="w-4 h-4 text-light-text-muted dark:text-dark-text-muted transform transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            @endif
        </button>
        
        @if($dropdown)
            <!-- Dropdown Menu -->
            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-light-bg-secondary dark:bg-dark-bg-secondary rounded-lg shadow-lg border border-light-border-default dark:border-dark-border-default py-2 z-50 opacity-0 transform scale-95 transition-all duration-200">
                <div class="px-4 py-2 border-b border-gray-200 dark:border-dark-border-default">
                    <p class="text-sm font-medium text-light-text-primary dark:text-dark-text-primary">{{ $user->name }}</p>
                    <p class="text-xs text-light-text-muted dark:text-dark-text-muted">{{ $user->email }}</p>
                </div>
                
                @if($user->role === 'student')
                    <a href="/student/accont" class="flex items-center px-4 py-2 text-sm text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-light-text-muted dark:text-dark-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Profile
                    </a>
                    
                    <a href="{{ route('my.courses') }}" class="flex items-center px-4 py-2 text-sm text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-light-text-muted dark:text-dark-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        My Courses
                    </a>
                @else
                    <a href="/accont" class="flex items-center px-4 py-2 text-sm text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-light-text-muted dark:text-dark-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Account
                    </a>
                @endif
                
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 text-sm text-light-text-secondary dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-light-text-muted dark:text-dark-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>
                
                <div class="border-t border-gray-200 dark:border-dark-border-default my-2"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        @endif
    </div>
@else
    <!-- Simple Profile Photo -->
    @if($photoUrl)
        <img src="{{ $photoUrl }}" 
             alt="{{ $user->name }}" 
             class="{{ $currentSize }} rounded-full object-cover border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-200 {{ $attributes->get('class') }}">
    @else
        <div class="{{ $currentSize }} rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-dark-text-primary flex items-center justify-center font-bold shadow-sm hover:shadow-md transition-all duration-200 border-2 border-transparent hover:border-indigo-400 dark:hover:border-indigo-500 {{ $attributes->get('class') }}">
            {{ $firstInitial }}{{ $lastInitial }}
        </div>
    @endif
    
    @if($showName && !$dropdown)
        <span class="ml-2 text-sm font-medium text-light-text-secondary dark:text-dark-text-secondary">{{ $user->name }}</span>
    @endif
@endif