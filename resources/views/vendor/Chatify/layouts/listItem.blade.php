{{-- -------------------- Contact list -------------------- --}}
@if($get == 'users' && !!$lastMessage)
    <?php 
        $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8'); 
        $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody; 
    ?>
    @php
        $names = explode(' ', $user->name);
        $firstInitial = strtoupper(substr($names[0], 0, 1));
        $lastInitial = isset($names[1]) ? strtoupper(substr($names[1], 0, 1)) : '';
    @endphp

    <table class="messenger-list-item w-full" data-contact="{{ $user->id }}">
        <tr data-action="0" class="group cursor-pointer transition-all duration-300 hover:bg-white/10 hover:shadow-lg hover:-translate-y-0.5 active:scale-95">
            {{-- Avatar side --}}
            <td class="relative p-3">
                @if($user->active_status)
                    <span class="activeStatus absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 border-2 border-white rounded-full animate-pulse"></span>
                @endif
                @if ($user->profile_photo == null)
                    <div class="avatar av-m w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center rounded-full font-semibold text-lg shadow-md">
                        {{ $firstInitial }}{{ $lastInitial }}
                    </div>
                @else
                    <img class="avatar av-m w-12 h-12 object-cover rounded-full ring-2 ring-white/20 shadow-md transition-transform duration-300 group-hover:scale-110" 
                         src="{{ asset('storage/' . $user->profile_photo) }}" 
                         alt="Avatar">
                @endif
            </td>
            
            {{-- center side --}}
            <td class="p-3 w-full">
                <div class="flex items-center justify-between mb-1">
                    <p data-id="{{ $user->id }}" class="text-white font-semibold text-sm truncate pr-2" data-type="user">
                        {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
                    </p>
                    <span class="contact-item-time text-xs text-gray-400 flex-shrink-0" data-time="{{$lastMessage->created_at}}">
                        {{ $lastMessage->timeAgo }}
                    </span>
                </div>
                
                <div class="flex items-center text-sm text-gray-300">
                    {{-- Last Message user indicator --}}
                    {!!
                        $lastMessage->from_id == Auth::user()->id
                        ? '<span class="lastMessageIndicator text-blue-400 mr-1">You :</span>'
                        : ''
                    !!}
                    
                    {{-- Last message body --}}
                    @if($lastMessage->attachment == null)
                        <span class="truncate">{!! $lastMessageBody !!}</span>
                    @else
                        <div class="flex items-center text-gray-400">
                            <span class="fas fa-file mr-1"></span> 
                            <span>Attachment</span>
                        </div>
                    @endif
                </div>
                
                {{-- New messages counter --}}
                @if($unseenCounter > 0)
                    <div class="inline-block mt-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5 animate-pulse">
                        {!! "<b>".$unseenCounter."</b>" !!}
                    </div>
                @endif
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
    <table class="messenger-list-item w-full" data-contact="{{ $user->id }}">
        <tr data-action="0" class="group cursor-pointer transition-all duration-300 hover:bg-white/10 hover:shadow-lg hover:-translate-y-0.5 active:scale-95">
            {{-- Avatar side --}}
            <td class="p-3">
                <div class="avatar av-m w-10 h-10 rounded-full overflow-hidden ring-2 ring-white/20 shadow-md"
                     style="background-image: url('{{ $user->avatar }}'); background-size: cover; background-position: center;">
                </div>
            </td>
            
            {{-- center side --}}
            <td class="p-3 w-full">
                <p data-id="{{ $user->id }}" data-type="user" class="text-white font-semibold text-sm truncate">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
                </p>
                <span class="text-xs text-gray-400 mt-0.5 block">Available</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
    <div class="shared-photo chat-image group cursor-pointer">
        <div class="relative w-20 h-20 rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl hover:scale-105 active:scale-95"
             style="background-image: url('{{ $image }}'); background-size: cover; background-position: center;">
            
            {{-- Hover overlay --}}
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
            </div>
        </div>
    </div>
@endif