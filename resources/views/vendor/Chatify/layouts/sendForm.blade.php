<div class="messenger-sendCard bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data" class="flex items-end gap-3">
        @csrf
        <label class="cursor-pointer text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <span class="fas fa-plus-circle"></span>
            <input disabled='disabled' type="file" class="upload-attachment hidden" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" />
        </label>
        <button class="emoji-button text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 border-none bg-transparent cursor-pointer" type="button">
            <span class="fas fa-smile"></span>
        </button>
        <textarea readonly='readonly' name="message" class="m-send app-scroll flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="Type a message.." style="min-height: 44px; max-height: 120px;"></textarea>
        <button disabled='disabled' class="send-button bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed text-white p-3 rounded-xl transition-all duration-200 border-none cursor-pointer shadow-sm hover:shadow-md transform hover:scale-105 disabled:transform-none">
            <span class="fas fa-paper-plane"></span>
        </button>
    </form>
</div>