{{-- ---------------------- Image modal box ---------------------- --}}
<div id="imageModalBox" class="imageModal fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center p-4 animate-fade-in">
    <span class="imageModal-close absolute top-4 right-4 text-white text-4xl font-light cursor-pointer hover:text-gray-300 transition-colors duration-200 z-10">&times;</span>
    <img class="imageModal-content max-w-full max-h-full object-contain animate-scale-in rounded-lg shadow-2xl" id="imageModalBoxSrc" alt="Full size image">
</div>

{{-- ---------------------- Delete Modal ---------------------- --}}
<div class="app-modal fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in" data-name="delete">
    <div class="app-modal-container w-full max-w-md">
        <div class="app-modal-card bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 animate-scale-in" data-name="delete" data-modal='0'>
            <div class="app-modal-header text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Are you sure you want to delete this?
            </div>
            <div class="app-modal-body text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6">
                You cannot undo this action
            </div>
            <div class="app-modal-footer flex flex-col sm:flex-row gap-3 sm:justify-end">
                <button class="app-btn cancel px-4 py-2 sm:px-6 sm:py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button class="app-btn a-btn-danger delete px-4 py-2 sm:px-6 sm:py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ---------------------- Alert Modal ---------------------- --}}
<div class="app-modal fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in" data-name="alert">
    <div class="app-modal-container w-full max-w-md">
        <div class="app-modal-card bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 animate-scale-in" data-name="alert" data-modal='0'>
            <div class="app-modal-header text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4"></div>
            <div class="app-modal-body text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6"></div>
            <div class="app-modal-footer flex justify-center sm:justify-end">
                <button class="app-btn cancel px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
{{-- ---------------------- Settings Modal ---------------------- --}}
<div class="app-modal fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in overflow-y-auto" data-name="settings">
    <div class="app-modal-container w-full max-w-lg my-8">
        <div class="app-modal-card bg-white dark:bg-gray-800 rounded-2xl shadow-2xl animate-scale-in" data-name="settings" data-modal='0'>
            <form id="update-settings" action="{{ route('avatar.update') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="p-6 sm:p-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">Settings</h2>
                    
                    {{-- Update profile avatar --}}
                    <div class="mb-6">
                        <div class="avatar av-l upload-avatar-preview w-24 h-24 sm:w-32 sm:h-32 mx-auto rounded-full bg-cover bg-center shadow-lg ring-4 ring-white dark:ring-gray-700 mb-4"
                            style="background-image: url('{{ Chatify::getUserWithAvatar(Auth::user())->avatar }}');"></div>
                        <p class="upload-avatar-details text-center text-sm text-gray-600 dark:text-gray-400 mb-3"></p>
                        <label class="app-btn a-btn-primary update block w-full sm:w-auto sm:mx-auto px-6 py-2.5 text-center text-white rounded-xl cursor-pointer hover:opacity-90 transition-opacity duration-200 font-medium shadow-sm" style="background-color:{{$messengerColor}}">
                            Upload New Avatar
                            <input class="upload-avatar hidden" accept="image/*" name="avatar" type="file" />
                        </label>
                    </div>
                    
                    {{-- Dark/Light Mode --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-medium text-gray-900 dark:text-white">Dark Mode</span>
                            <button type="button" class="dark-mode-switch relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 {{ Auth::user()->dark_mode > 0 ? 'bg-primary-600' : 'bg-gray-300' }}" data-mode="{{ Auth::user()->dark_mode > 0 ? 1 : 0 }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ Auth::user()->dark_mode > 0 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Change messenger color --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <p class="text-base font-medium text-gray-900 dark:text-white mb-4">Theme Color</p>
                        <div class="update-messengerColor grid grid-cols-5 sm:grid-cols-6 gap-3">
                            @foreach (config('chatify.colors') as $color)
                                <button type="button" style="background-color: {{ $color}}" data-color="{{$color}}" 
                                    class="color-btn w-10 h-10 sm:w-12 sm:h-12 rounded-xl shadow-sm hover:shadow-md transform hover:scale-110 transition-all duration-200 cursor-pointer ring-2 ring-transparent hover:ring-white dark:hover:ring-gray-600 active:scale-95"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="app-modal-footer border-t border-gray-200 dark:border-gray-700 p-6 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="button" class="app-btn cancel px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 font-medium order-2 sm:order-1">
                        Cancel
                    </button>
                    <button type="submit" class="app-btn a-btn-success update px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-all duration-200 font-medium shadow-sm hover:shadow-md order-1 sm:order-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
