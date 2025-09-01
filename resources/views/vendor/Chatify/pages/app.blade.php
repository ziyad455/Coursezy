@include('Chatify::layouts.headLinks')

@php
    $current = auth()->user();
@endphp

    @if ($current->role == "student")

        <x-studentNav/>
    @else
    
    <x-coachNav/>
    @endif


<div class="messenger flex h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300">
        {{-- Header and search bar --}}

        {{-- tabs and lists --}}
        <div class="m-body contacts-container flex-1 overflow-hidden">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="show messenger-tab users-tab app-scroll h-full overflow-y-auto" data-view="users">
               {{-- Favorites --}}
               <div class="favorites-section px-4 py-2">
                <p class="messenger-title px-4 py-3 text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <span>Favorites</span>
                </p>
                <div class="messenger-favorites app-scroll-hidden flex gap-3 overflow-x-auto pb-2"></div>
               </div>
               {{-- Saved Messages --}}

               {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
               {{-- Contact --}}
            <p class=" px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white bg-transparent">
                <span>All Messages</span>
            </p>

               <div class="listOfContacts" style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
           </div>
             {{-- ---------------- [ Search Tab ] ---------------- --}}

        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView flex-1 flex flex-col bg-white dark:bg-gray-800 transition-colors duration-300">
        {{-- header title [conversation name] amd buttons --}}


        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900">
            <div class="messages">
                <p class="message-hint center-el text-center text-gray-500 dark:text-gray-400 py-8">
                    <span>Please select a chat to start messaging</span>
                </p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator mb-4">
                <div class="message-card typing mb-4 flex">
                    <div class="message-card-content max-w-xs sm:max-w-md lg:max-w-lg">
                        <div class="message bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl px-4 py-3 shadow-sm border border-gray-200 dark:border-gray-600 relative">
                            <span class="typing-dots flex gap-1">
                                <span class="dot dot-1 w-2 h-2 bg-gray-500 rounded-full animate-pulse"></span>
                                <span class="dot dot-2 w-2 h-2 bg-gray-500 rounded-full animate-pulse" style="animation-delay: 0.2s;"></span>
                                <span class="dot dot-3 w-2 h-2 bg-gray-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></span>
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