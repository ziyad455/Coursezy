<div class="messenger-sendCard sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-3 sm:p-4 backdrop-blur-lg bg-opacity-95 dark:bg-opacity-95 z-10">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data" class="flex items-end gap-2 sm:gap-3">
        @csrf
        <input type="hidden" name="to_id" id="to_id" value="">
        {{-- Attachment button --}}
        <label class="flex-shrink-0 cursor-pointer text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 active:scale-95" aria-label="Attach file">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            <input disabled='disabled' type="file" class="upload-attachment hidden" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" />
        </label>
        
        {{-- Emoji button --}}
        <button class="emoji-button flex-shrink-0 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200 p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 border-none bg-transparent cursor-pointer active:scale-95" type="button" aria-label="Add emoji">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>
        
        {{-- Message input --}}
        <div class="flex-1 relative">
<textarea 
                readonly='readonly' 
                name="message" 
                class="m-send app-scroll w-full px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-100 dark:bg-gray-700 border border-white/15 dark:border-gray-600 rounded-xl sm:rounded-2xl text-sm sm:text-base text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/70 focus:border-transparent transition-all duration-200 resize-none scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600" 
                placeholder="Type a message..." 
                style="min-height: 40px; max-height: 120px;"
                aria-label="Message input"></textarea>
        </div>
        
        {{-- Send button --}}
        <button 
            disabled='disabled' 
            class="send-button flex-shrink-0 bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed text-white p-2.5 sm:p-3 rounded-xl sm:rounded-2xl transition-all duration-200 border-none cursor-pointer shadow-sm hover:shadow-lg transform hover:scale-105 active:scale-95 disabled:transform-none disabled:shadow-none"
            aria-label="Send message">
            <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
            </svg>
        </button>
    </form>
</div>
