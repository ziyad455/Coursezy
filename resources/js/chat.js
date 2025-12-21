/**
 * AI Chat Functionalitity
 */

document.addEventListener('DOMContentLoaded', () => {
    const chatContainer = document.getElementById('chatContainer');
    if (!chatContainer) return;

    // DOM Elements
    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Configuration from data attributes
    const userId = chatContainer.dataset.userId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // State
    let isLoading = false;

    // Initialize
    if (messageInput) {
        messageInput.focus();
        scrollToBottom();
    }

    // Event Listeners
    if (sendButton) {
        sendButton.addEventListener('click', handleSendMessage);
    }

    if (messageInput) {
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage();
            }
        });
    }

    // Handle sending messages
    async function handleSendMessage() {
        const message = messageInput.value.trim();
        
        if (!message || isLoading) return;
        
        // Add user message to chat
        addMessage(message, 'user');
        messageInput.value = '';
        
        setLoading(true);
        
        try {
            const response = await sendMessageToAPI(message);
            addMessage(response, 'bot');
        } catch (error) {
            console.error('Error sending message:', error);
            addMessage('Sorry, I encountered an error. Please try again.', 'bot', true);
        } finally {
            setLoading(false);
            messageInput.focus();
        }
    }

    // Send message to API
    async function sendMessageToAPI(message) {
        const response = await fetch('/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                user_id: userId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data.response || data.reply || 'No response received';
    }

    // Add message to chat UI
    function addMessage(text, sender, isError = false) {
        if (!messagesContainer) return;

        const messageDiv = document.createElement('div');
        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.className = 'animate-fade-in-up';
        
        if (sender === 'user') {
            messageDiv.classList.add('flex', 'items-start', 'space-x-3', 'justify-end');
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl p-4 shadow-md max-w-md">
                    <p>${escapeHtml(text)}</p>
                    <span class="text-xs text-indigo-200 mt-2 block">${timestamp}</span>
                </div>
                <div class="flex-shrink-0 user-avatar-placeholder">
                    <!-- Avatar will be rendered via template or CSS -->
                </div>
            `;
            
            // Try to clone user avatar if available in the DOM
            const existingAvatar = document.querySelector('.user-avatar-source');
            if (existingAvatar) {
                const avatarClone = existingAvatar.cloneNode(true);
                avatarClone.classList.remove('hidden', 'user-avatar-source');
                messageDiv.querySelector('.user-avatar-placeholder').appendChild(avatarClone);
            }
        } else {
            messageDiv.classList.add('flex', 'items-start', 'space-x-3');
            const bgColor = isError ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-light-bg-secondary dark:bg-dark-bg-secondary border-light-border-subtle dark:border-dark-border-subtle';
            const textColor = isError ? 'text-red-800 dark:text-red-200' : 'text-light-text-primary dark:text-dark-text-primary';
            const timestampColor = isError ? 'text-red-500 dark:text-red-400' : 'text-light-text-muted dark:text-dark-text-muted';
            
            messageDiv.innerHTML = `
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="${bgColor} rounded-xl p-4 shadow-md max-w-md border">
                    <p class="${textColor}" style="white-space: pre-line;">${escapeHtml(text)}</p>
                    <span class="text-xs ${timestampColor} mt-2 block">${timestamp}</span>
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Set loading state UI
    function setLoading(loading) {
        isLoading = loading;
        if (sendButton) sendButton.disabled = loading;
        if (messageInput) messageInput.disabled = loading;
        
        if (loading) {
            loadingIndicator?.classList.remove('hidden');
            if (sendButton) {
                sendButton.dataset.originalHtml = sendButton.innerHTML;
                sendButton.innerHTML = `
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                    <span>Sending...</span>
                `;
            }
        } else {
            loadingIndicator?.classList.add('hidden');
            if (sendButton && sendButton.dataset.originalHtml) {
                sendButton.innerHTML = sendButton.dataset.originalHtml;
            }
        }
    }

    // Scroll to bottom helper
    function scrollToBottom() {
        if (!messagesContainer) return;
        setTimeout(() => {
            messagesContainer.scrollTo({
                top: messagesContainer.scrollHeight,
                behavior: 'smooth'
            });
        }, 100);
    }

    // HTML Escaping
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
