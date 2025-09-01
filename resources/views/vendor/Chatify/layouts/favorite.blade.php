<div class="favorite-list-item flex flex-col items-center gap-2 min-w-0 cursor-pointer p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
    @if($user)
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m w-12 h-12 rounded-full bg-cover bg-center" style="background-image: url('{{ Chatify::getUserWithAvatar($user)->avatar }}');">
        </div>
        <p class="text-xs text-gray-600 dark:text-gray-300 text-center">{{ strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name }}</p>
    @endif
</div>