@include('Chatify::layouts.headLinks')

@php
    $current = auth()->user();
@endphp

    @if ($current->role == "student")

        <x-studentNav/>
    @else
    
    <x-coachNav/>
    @endif

{{-- Mobile menu toggle button --}}
<button id="mobile-menu-toggle" class="fixed bottom-4 right-4 z-50 lg:hidden bg-primary-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
    </svg>
</button>

<div class="messenger grid grid-cols-1 lg:grid-cols-[320px_1fr] xl:grid-cols-[360px_1fr] 2xl:grid-cols-[400px_1fr] h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 overflow-hidden">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div id="messenger-sidebar" class="messenger-listView fixed inset-y-0 left-0 z-40 w-full max-w-[90vw] sm:max-w-sm lg:relative lg:inset-auto lg:z-auto lg:max-w-none bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 transform -translate-x-full lg:translate-x-0">
        {{-- Header with search bar and mobile close button --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-4 lg:hidden">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Messages</h2>
                <button id="close-sidebar" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="relative">
                <input type="text" class="messenger-search w-full px-4 py-2.5 pl-10 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-xl text-sm placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" placeholder="Search messages...">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        {{-- tabs and lists --}}
        <div class="m-body contacts-container flex-1 overflow-hidden">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 hover:scrollbar-thumb-gray-400 dark:hover:scrollbar-thumb-gray-500" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section px-4 py-3">
                <p class="messenger-title px-2 py-2 text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <span>Favorites</span>
                </p>
                <div class="messenger-favorites app-scroll-hidden flex gap-2 sm:gap-3 overflow-x-auto pb-2 scrollbar-none"></div>
               </div>
               {{-- Saved Messages --}}
               <div class="px-2">
                   {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
               </div>
               
               {{-- Contact --}}
            <p class="px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50">
                <span>All Messages</span>
            </p>

               <div class="listOfContacts px-2 pb-20 lg:pb-4" style="width: 100%;position: relative;"></div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}
             <div class="messenger-tab search-tab app-scroll hidden" data-view="search">
                <div class="px-4 py-3">
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Search results will appear here</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden transition-opacity duration-300"></div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView col-span-1 flex flex-col bg-white dark:bg-gray-800 transition-colors duration-300 relative">
        {{-- header title [conversation name] and buttons --}}
        <div class="m-header sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 sm:py-4 backdrop-blur-lg bg-opacity-95 dark:bg-opacity-95">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <button id="mobile-back-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="messenger-header-content flex-1"></div>
                </div>
                <div class="messenger-header-actions flex items-center space-x-2"></div>
            </div>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll flex-1 overflow-y-auto p-3 sm:p-4 md:p-6 bg-gray-50 dark:bg-gray-900 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
            <div class="messages min-h-full flex flex-col justify-end">
                <div class="flex flex-col items-center justify-center py-12 px-4">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="message-hint text-center text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                        <span>Select a conversation to start messaging</span>
                    </p>
                </div>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator hidden px-3 sm:px-4 pb-2">
                <div class="message-card typing flex animate-fade-in">
                    <div class="message-card-content max-w-[200px] sm:max-w-xs">
                        <div class="message bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl px-3 py-2 sm:px-4 sm:py-3 shadow-sm border border-gray-200 dark:border-gray-600 relative">
                            <span class="typing-dots flex gap-1 items-center">
                                <span class="dot w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0s;"></span>
                                <span class="dot w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.15s;"></span>
                                <span class="dot w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.3s;"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    {{-- <div class="messenger-infoView app-scroll w-80 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 overflow-y-auto"> --}}
        {{-- nav actions --}}
        {{-- <nav class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <p class="text-gray-900 dark:text-white font-semibold text-lg">User Details</p>
            <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-times"></i>
            </a>
        </nav> --}}
        {{-- {!! view('Chatify::layouts.info')->render() !!} --}}
    {{-- </div> --}}
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')