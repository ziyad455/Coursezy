<?php 
$seenIcon = (!!$seen ? 'check-double' : 'check'); 
?>

<div class="message-card @if($isSender) mc-sender @endif" data-id="{{ $id }}">
    {{-- Message Content --}}
    <div class="message">
        @if (@$attachment->type != 'image' || $message)
            <p>{!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}</p>
            
            {{-- File Attachment --}}
            @if(@$attachment->type == 'file')
            <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" class="file-download">
                <i class="fas fa-paperclip"></i> {{ $attachment->title }}
            </a>
            @endif
        @endif
        
        {{-- Image Attachment --}}
        @if(@$attachment->type == 'image')
        <div class="image-file chat-image">
            <img src="{{ Chatify::getAttachmentUrl($attachment->file) }}" alt="{{ $attachment->title }}">
        </div>
        @endif
    </div>
    
    {{-- Time and Status --}}
    <div class="message-time-card">
        <span data-time='{{ $created_at }}'>
            {{ $timeAgo }}
            @if($isSender)
                <i class="fas fa-{{ $seenIcon }} seen-icon"></i>
            @endif
        </span>
    </div>
    
    {{-- Delete Button for Sender --}}
    @if ($isSender)
        <div class="message-actions">
            <button class="delete-btn" data-id="{{ $id }}" title="Delete message">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    @endif
</div>
