{{-- user info and avatar --}}
<div class="avatar av-l chatify-d-flex w-20 h-20 rounded-full bg-cover bg-center mx-auto mb-4 border-4 border-gray-200 dark:border-gray-600 hover:border-indigo-500 transition-colors duration-200"></div>
<p class="info-name text-center text-gray-900 dark:text-white font-semibold text-xl mt-4 mb-6">{{ config('chatify.name') }}</p>
<div class="messenger-infoView-btns px-4 mb-6">
    <a href="#" class="danger delete-conversation w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 text-center no-underline block shadow-sm hover:shadow-md transform hover:scale-105">Delete Conversation</a>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared px-4">
    <p class="messenger-title text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">
        <span>Shared Photos</span>
    </p>
    <div class="shared-photos-list grid grid-cols-3 gap-2 mt-4"></div>
</div>