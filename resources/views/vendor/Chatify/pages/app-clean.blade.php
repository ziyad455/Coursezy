@include('Chatify::layouts.headLinks')

@php
    $current = auth()->user();
@endphp

@if ($current->role == "student")
    <x-studentNav/>
@else
    <x-coachNav/>
@endif

<div class="messenger" data-current-user-id="{{ $current->id }}">
    {{-- Users/Groups lists side --}}
    <div class="messenger-listView">
        <div class="m-header">
            <nav>
                <input type="text" class="messenger-search" placeholder="Search">
            </nav>
        </div>
        
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
            {{-- Lists [Users/Group] --}}
            <div class="show messenger-tab users-tab app-scroll" data-view="users">
                {{-- Favorites --}}
                <div class="messenger-favorites app-scroll-hidden"></div>
                
                {{-- Saved Messages --}}
                {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
                
                {{-- Contact --}}
                <p class="messenger-title">
                    <span>All Messages</span>
                </p>
                <div class="listOfContacts" style="width: 100%;position: relative;"></div>
            </div>
            
            {{-- Search Tab --}}
            <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
            </div>
        </div>
    </div>

    {{-- Messaging side --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] and buttons --}}
        <div class="m-header m-header-messaging">
            <nav>
                {{-- header back button, avatar and user name --}}
                <div style="display: inline-flex;">
                    <a href="#" class="show-listView">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="messenger-header-content"></div>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite">
                        <i class="fas fa-star"></i>
                    </a>
                </nav>
            </nav>
        </div>
        
        {{-- Internet connection --}}
        <div class="internet-connection">
            <span class="ic-connected">Connected</span>
            <span class="ic-connecting">Connecting...</span>
            <span class="ic-noInternet">No internet access</span>
        </div>
        
        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el">
                    <span>Please select a chat to start messaging</span>
                </p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
