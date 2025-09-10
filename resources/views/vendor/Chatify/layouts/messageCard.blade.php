<?php 
$seenIcon = (!!$seen ? 'check-double' : 'check'); 
$timeAndSeen = "<span data-time='$created_at' class='time message-time'>
        $timeAgo
        ".($isSender ? "<span class='fas fa-$seenIcon seen'></span>" : '' )."
    </span>"; 
?>

<div class="message-card @if($isSender) mc-sender @endif" data-id="{{ $id }}">
    {{-- Delete Button --}}
    @if ($isSender)
        <div class="actions">
            <span class="delete-btn" data-id="{{ $id }}">
                <i class="fas fa-trash"></i>
            </span>
        </div>
    @endif

    {{-- Message Content --}}
    <div class="message-card-content">
        @if (@$attachment->type != 'image' || $message)
            <div class="message">
                {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
                {!! $timeAndSeen !!}
                
                {{-- File Attachment --}}
                @if(@$attachment->type == 'file')
                <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" class="file-download">
                    <i class="fas fa-file"></i> {{ $attachment->title }}
                </a>
                @endif
            </div>
        @endif

        
        {{-- Image Attachment --}}
        @if(@$attachment->type == 'image')
        <div class="image-wrapper" style="text-align: {{$isSender ? 'end' : 'start'}}">
            <div class="image-file chat-image" style="background-image: url('{{ Chatify::getAttachmentUrl($attachment->file) }}')">
                <div>{{ $attachment->title }}</div>
            </div>
            <div style="margin-bottom:5px">
                {!! $timeAndSeen !!}
            </div>
        </div>
        @endif
    </div>
</div>
