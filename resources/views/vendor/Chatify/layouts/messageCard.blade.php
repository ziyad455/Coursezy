<?php 
$seenIcon = (!!$seen ? 'check-double' : 'check'); 
$timeAndSeen = "<span data-time='$created_at' class='text-white text-xs mt-2 flex items-center gap-1'>
        ".($isSender ? "<span class='fas fa-$seenIcon seen text-indigo-300'></span>" : '' )."
        <span class='time text-xs'>$timeAgo</span>
    </span>"; 
?>

<div class="message-card mb-4 flex group @if($isSender) mc-sender justify-end @else justify-start @endif" data-id="{{ $id }}">
    {{-- Delete Message Button --}}
    @if ($isSender)
        <div class="actions flex flex-col items-center gap-2 mt-2 mr-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
            <!-- Delete Button -->
            <div class="relative group/delete">
                <button class="delete-btn w-8 h-8 flex items-center justify-center rounded-full bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white cursor-pointer transition-all duration-300 hover:scale-110 active:scale-95 hover:shadow-lg hover:shadow-red-500/25" data-id="{{ $id }}">
                    <i class="fas fa-trash text-xs"></i>
                </button>
                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/delete:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                    Delete
                </div>
            </div>
            

        </div>
    @endif

    {{-- Card --}}
    <div class="message-card-content max-w-xs sm:max-w-md lg:max-w-lg transform transition-all duration-300 ease-out hover:scale-[1.02] hover:shadow-lg">
        @if (@$attachment->type != 'image' || $message)
            <div class="message @if($isSender) bg-gradient-to-br from-indigo-600 to-indigo-700 dark:from-indigo-500 dark:to-indigo-600 text-white border-indigo-600 dark:border-indigo-500 shadow-indigo-200 dark:shadow-indigo-800/30 @else bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-gray-700 shadow-gray-200 dark:shadow-gray-900/30 @endif rounded-2xl px-4 py-3 shadow-lg border backdrop-blur-sm relative overflow-hidden">
                {{-- Subtle shine effect --}}
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-out pointer-events-none"></div>
                
                <div class="relative z-10">
                    {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
                    {!! $timeAndSeen !!}
                    
                    {{-- If attachment is a file --}}
                    @if(@$attachment->type == 'file')
                    <div class="file-attachment-wrapper mt-3 p-3 rounded-xl @if($isSender) bg-white/10 border border-white/20 @else bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 @endif">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <!-- File Icon with Animation -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg @if($isSender) bg-white/20 @else bg-indigo-100 dark:bg-indigo-900/30 @endif flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    @php
                                        $fileExt = pathinfo($attachment->file, PATHINFO_EXTENSION);
                                        $iconClass = match(strtolower($fileExt)) {
                                            'pdf' => 'fas fa-file-pdf text-red-500',
                                            'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                            'xls', 'xlsx' => 'fas fa-file-excel text-green-600',
                                            'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                            'zip', 'rar', '7z' => 'fas fa-file-archive text-yellow-600',
                                            'mp3', 'wav', 'ogg' => 'fas fa-file-audio text-purple-500',
                                            'mp4', 'avi', 'mkv' => 'fas fa-file-video text-pink-500',
                                            'txt' => 'fas fa-file-alt text-gray-500',
                                            default => 'fas fa-file text-gray-500'
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }} text-lg"></i>
                                </div>
                                
                                <!-- File Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm @if($isSender) text-white @else text-gray-900 dark:text-gray-100 @endif truncate">
                                        {{ $attachment->title }}
                                    </h4>
                                    <p class="text-xs @if($isSender) text-indigo-200 @else text-gray-500 dark:text-gray-400 @endif">
                                        {{ strtoupper($fileExt ?? 'FILE') }} • {{ number_format(filesize(storage_path('app/public/'.config('chatify.attachments.folder').'/'.$attachment->file)) / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Download Button -->
                            <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" 
                               class="file-download flex-shrink-0 w-10 h-10 rounded-lg @if($isSender) bg-white/20 hover:bg-white/30 text-white @else bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 @endif flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 hover:shadow-lg">
                                <i class="fas fa-download text-sm"></i>
                            </a>
                        </div>
                        
                        <!-- Progress Bar Animation (Optional) -->
                        <div class="mt-3 h-1 bg-black/10 rounded-full overflow-hidden">
                            <div class="h-full @if($isSender) bg-white/30 @else bg-indigo-300 dark:bg-indigo-600 @endif rounded-full w-full"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif

        @if(@$attachment->type == 'image')
        <div class="image-wrapper mb-2" style="text-align: {{$isSender ? 'end' : 'start'}}">
            <div class="image-file chat-image rounded-2xl overflow-hidden shadow-lg cursor-pointer hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 relative group/image" 
                 style="background-image: url('{{ Chatify::getAttachmentUrl($attachment->file) }}'); width: 200px; height: 200px; background-size: cover; background-position: center;">
                
                {{-- Image overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                    <div class="absolute bottom-0 left-0 right-0 p-3 text-white text-sm font-medium">
                        {{ $attachment->title }}
                    </div>
                </div>
                
                {{-- Hover expand icon --}}
                <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover/image:bg-black/20 transition-all duration-300">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover/image:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </div>
            </div>
            
            <div class="mt-2" style="margin-bottom:5px">
                {!! $timeAndSeen !!}
            </div>
        </div>
        @endif
    </div>
</div>