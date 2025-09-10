/**
 * Chatify Smooth Experience JavaScript
 * WhatsApp-like real-time chat functionality
 * Optimized for performance and user experience
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        pollingInterval: 1500, // 1.5 seconds for smoother updates
        typingTimeout: 1000,
        scrollThreshold: 100,
        messageLoadBatch: 50,
        imageLoadDelay: 100,
        animationDuration: 300,
        touchSwipeThreshold: 50,
        messageRenderDelay: 50,
        smoothScrollDuration: 300,
        readReceiptDelay: 500
    };

    // State management
    let state = {
        currentUserId: null,
        receiverId: null,
        lastMessageId: 0,
        isTyping: false,
        typingTimer: null,
        isPolling: false,
        pollingInterval: null,
        messageQueue: [],
        renderTimer: null,
        scrollPosition: 0,
        isNearBottom: true,
        connectionStatus: 'connected',
        unreadMessages: new Set(),
        loadedImages: new Set(),
        messageCache: new Map(),
        touchStartX: 0,
        touchStartY: 0
    };

    // DOM elements cache
    let elements = {};

    /**
     * Initialize the chat application
     */
    function init() {
        cacheDOMElements();
        setupEventListeners();
        initializeState();
        startPolling();
        observeMessages();
        initializeTouchGestures();
        preloadImages();
        
        // Set viewport height for mobile
        setViewportHeight();
        
        // Initialize smooth scrolling
        enableSmoothScrolling();
        
        // Load initial messages with animation
        animateInitialMessages();
    }

    /**
     * Cache DOM elements for performance
     */
    function cacheDOMElements() {
        elements = {
            messagesContainer: document.querySelector('.m-body.messages-container'),
            messageInput: document.querySelector('.m-send'),
            sendButton: document.querySelector('.send-button'),
            typingIndicator: document.querySelector('.typing-indicator'),
            connectionStatus: document.querySelector('.connection-status'),
            sidebar: document.querySelector('.messenger-listView'),
            emojiButton: document.querySelector('.emoji-button'),
            attachmentButton: document.querySelector('.attachment-button'),
            messagesList: document.querySelector('.messages'),
            searchInput: document.querySelector('.messenger-search'),
            contactsList: document.querySelector('.listOfContacts')
        };
    }

    /**
     * Setup all event listeners
     */
    function setupEventListeners() {
        // Message input events
        if (elements.messageInput) {
            elements.messageInput.addEventListener('input', handleTyping);
            elements.messageInput.addEventListener('keypress', handleEnterKey);
            elements.messageInput.addEventListener('focus', handleInputFocus);
            elements.messageInput.addEventListener('blur', handleInputBlur);
            
            // Auto-resize textarea
            elements.messageInput.addEventListener('input', autoResizeTextarea);
        }

        // Send button
        if (elements.sendButton) {
            elements.sendButton.addEventListener('click', sendMessage);
        }

        // Scroll events
        if (elements.messagesContainer) {
            elements.messagesContainer.addEventListener('scroll', throttle(handleScroll, 100));
        }

        // Window events
        window.addEventListener('resize', debounce(setViewportHeight, 200));
        window.addEventListener('online', handleOnline);
        window.addEventListener('offline', handleOffline);
        window.addEventListener('beforeunload', cleanup);
        
        // Visibility change
        document.addEventListener('visibilitychange', handleVisibilityChange);
        
        // Touch events for mobile
        if ('ontouchstart' in window) {
            document.addEventListener('touchstart', handleTouchStart, { passive: true });
            document.addEventListener('touchmove', handleTouchMove, { passive: true });
            document.addEventListener('touchend', handleTouchEnd, { passive: true });
        }
    }

    /**
     * Initialize application state
     */
    function initializeState() {
        const currentUserEl = document.querySelector('[data-current-user-id]');
        const receiverEl = document.querySelector('[data-receiver-id]');
        
        if (currentUserEl) state.currentUserId = currentUserEl.dataset.currentUserId;
        if (receiverEl) state.receiverId = receiverEl.dataset.receiverId;
        
        // Get last message ID
        const messages = document.querySelectorAll('.message-card');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            state.lastMessageId = parseInt(lastMessage.dataset.id) || 0;
        }
    }

    /**
     * Start polling for new messages
     */
    function startPolling() {
        if (state.isPolling) return;
        
        state.isPolling = true;
        updateConnectionStatus('connected');
        
        // Initial poll
        pollMessages();
        
        // Set up interval
        state.pollingInterval = setInterval(pollMessages, CONFIG.pollingInterval);
    }

    /**
     * Stop polling
     */
    function stopPolling() {
        if (state.pollingInterval) {
            clearInterval(state.pollingInterval);
            state.pollingInterval = null;
        }
        state.isPolling = false;
        updateConnectionStatus('disconnected');
    }

    /**
     * Poll for new messages
     */
    async function pollMessages() {
        if (!state.receiverId) return;
        
        try {
            const response = await fetch(`/messages/check-new/${state.receiverId}?after=${state.lastMessageId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            if (data.messages && data.messages.length > 0) {
                queueMessages(data.messages);
            }
            
            if (data.typing) {
                showTypingIndicator();
            } else {
                hideTypingIndicator();
            }
            
            updateConnectionStatus('connected');
            
        } catch (error) {
            console.error('Error polling messages:', error);
            updateConnectionStatus('error');
            
            // Retry after delay
            setTimeout(() => {
                if (state.isPolling) pollMessages();
            }, 5000);
        }
    }

    /**
     * Queue messages for smooth rendering
     */
    function queueMessages(messages) {
        messages.forEach(message => {
            if (!state.messageCache.has(message.id)) {
                state.messageQueue.push(message);
                state.messageCache.set(message.id, message);
                state.lastMessageId = Math.max(state.lastMessageId, message.id);
            }
        });
        
        renderQueuedMessages();
    }

    /**
     * Render queued messages with smooth animation
     */
    function renderQueuedMessages() {
        if (state.renderTimer) return;
        
        state.renderTimer = setTimeout(() => {
            const fragment = document.createDocumentFragment();
            
            while (state.messageQueue.length > 0) {
                const message = state.messageQueue.shift();
                const messageEl = createMessageElement(message);
                fragment.appendChild(messageEl);
                
                // Mark as unread if from other user
                if (message.sender_id !== state.currentUserId) {
                    state.unreadMessages.add(message.id);
                }
            }
            
            if (fragment.children.length > 0) {
                elements.messagesContainer.appendChild(fragment);
                
                // Smooth scroll to bottom if near bottom
                if (state.isNearBottom) {
                    smoothScrollToBottom();
                }
                
                // Animate new messages
                animateNewMessages();
                
                // Play notification sound
                playNotificationSound();
                
                // Send read receipts after delay
                setTimeout(sendReadReceipts, CONFIG.readReceiptDelay);
            }
            
            state.renderTimer = null;
        }, CONFIG.messageRenderDelay);
    }

    /**
     * Create message element
     */
    function createMessageElement(message) {
        const div = document.createElement('div');
        const isCurrentUser = message.sender_id === state.currentUserId;
        
        div.className = `message-card ${isCurrentUser ? 'mc-sender' : ''} opacity-0`;
        div.dataset.id = message.id;
        div.dataset.timestamp = message.created_at;
        
        const time = formatTime(new Date(message.created_at));
        const messageContent = escapeHtml(message.content);
        
        if (isCurrentUser) {
            div.innerHTML = `
                <div class="message">
                    <div class="message-text">${messageContent}</div>
                    <div class="time">
                        <span>${time}</span>
                        <span class="seen fas fa-${message.seen ? 'check-double' : 'check'}"></span>
                    </div>
                </div>
            `;
        } else {
            div.innerHTML = `
                <div class="message">
                    <div class="message-text">${messageContent}</div>
                    <div class="time">
                        <span>${time}</span>
                    </div>
                </div>
            `;
        }
        
        return div;
    }

    /**
     * Send a message
     */
    async function sendMessage() {
        const message = elements.messageInput.value.trim();
        if (!message) return;
        
        // Disable send button
        elements.sendButton.disabled = true;
        
        // Create optimistic message
        const optimisticMessage = {
            id: `temp-${Date.now()}`,
            content: message,
            sender_id: state.currentUserId,
            created_at: new Date().toISOString(),
            seen: false
        };
        
        // Add to UI immediately
        const messageEl = createMessageElement(optimisticMessage);
        elements.messagesContainer.appendChild(messageEl);
        
        // Clear input and animate
        elements.messageInput.value = '';
        autoResizeTextarea();
        animateNewMessages();
        smoothScrollToBottom();
        
        try {
            const response = await fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: message,
                    receiver_id: state.receiverId
                })
            });
            
            const data = await response.json();
            
            // Update message ID
            if (data.message) {
                messageEl.dataset.id = data.message.id;
                state.lastMessageId = Math.max(state.lastMessageId, data.message.id);
            }
            
        } catch (error) {
            console.error('Error sending message:', error);
            // Show error notification
            showNotification('Failed to send message', 'error');
        } finally {
            elements.sendButton.disabled = false;
        }
    }

    /**
     * Handle typing indicator
     */
    function handleTyping() {
        if (!state.isTyping) {
            state.isTyping = true;
            sendTypingStatus(true);
        }
        
        clearTimeout(state.typingTimer);
        state.typingTimer = setTimeout(() => {
            state.isTyping = false;
            sendTypingStatus(false);
        }, CONFIG.typingTimeout);
    }

    /**
     * Send typing status
     */
    async function sendTypingStatus(isTyping) {
        try {
            await fetch('/messages/typing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    receiver_id: state.receiverId,
                    is_typing: isTyping
                })
            });
        } catch (error) {
            console.error('Error sending typing status:', error);
        }
    }

    /**
     * Show typing indicator
     */
    function showTypingIndicator() {
        if (elements.typingIndicator) {
            elements.typingIndicator.classList.remove('hidden');
            elements.typingIndicator.classList.add('animate-fade-in');
        }
    }

    /**
     * Hide typing indicator
     */
    function hideTypingIndicator() {
        if (elements.typingIndicator) {
            elements.typingIndicator.classList.add('hidden');
            elements.typingIndicator.classList.remove('animate-fade-in');
        }
    }

    /**
     * Handle scroll events
     */
    function handleScroll() {
        const container = elements.messagesContainer;
        const scrollTop = container.scrollTop;
        const scrollHeight = container.scrollHeight;
        const clientHeight = container.clientHeight;
        
        state.scrollPosition = scrollTop;
        state.isNearBottom = (scrollHeight - scrollTop - clientHeight) < CONFIG.scrollThreshold;
        
        // Load more messages when scrolling to top
        if (scrollTop < CONFIG.scrollThreshold) {
            loadMoreMessages();
        }
        
        // Update read receipts for visible messages
        updateReadReceipts();
    }

    /**
     * Smooth scroll to bottom
     */
    function smoothScrollToBottom() {
        if (!elements.messagesContainer) return;
        
        const start = elements.messagesContainer.scrollTop;
        const end = elements.messagesContainer.scrollHeight;
        const duration = CONFIG.smoothScrollDuration;
        const startTime = performance.now();
        
        function scroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = easeInOutCubic(progress);
            
            elements.messagesContainer.scrollTop = start + (end - start) * easeProgress;
            
            if (progress < 1) {
                requestAnimationFrame(scroll);
            }
        }
        
        requestAnimationFrame(scroll);
    }

    /**
     * Animate new messages
     */
    function animateNewMessages() {
        const newMessages = document.querySelectorAll('.message-card.opacity-0');
        
        newMessages.forEach((message, index) => {
            setTimeout(() => {
                message.classList.remove('opacity-0');
                message.classList.add('animate-message-in');
            }, index * 50);
        });
    }

    /**
     * Animate initial messages
     */
    function animateInitialMessages() {
        const messages = document.querySelectorAll('.message-card');
        
        messages.forEach((message, index) => {
            setTimeout(() => {
                message.classList.add('animate-fade-in');
            }, Math.min(index * 30, 500));
        });
    }

    /**
     * Auto-resize textarea
     */
    function autoResizeTextarea() {
        const textarea = elements.messageInput;
        if (!textarea) return;
        
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    /**
     * Handle Enter key
     */
    function handleEnterKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    /**
     * Handle input focus
     */
    function handleInputFocus() {
        // Scroll to bottom when focusing input
        setTimeout(smoothScrollToBottom, 300);
    }

    /**
     * Handle input blur
     */
    function handleInputBlur() {
        // Stop typing indicator
        if (state.isTyping) {
            state.isTyping = false;
            sendTypingStatus(false);
        }
    }

    /**
     * Update connection status
     */
    function updateConnectionStatus(status) {
        state.connectionStatus = status;
        
        if (elements.connectionStatus) {
            elements.connectionStatus.className = `connection-status ${status}`;
            
            const statusText = {
                'connected': 'Connected',
                'disconnected': 'Disconnected',
                'error': 'Connection error',
                'reconnecting': 'Reconnecting...'
            };
            
            elements.connectionStatus.innerHTML = `
                <span class="status-dot"></span>
                <span>${statusText[status] || 'Unknown'}</span>
            `;
        }
    }

    /**
     * Handle online event
     */
    function handleOnline() {
        updateConnectionStatus('reconnecting');
        setTimeout(() => {
            startPolling();
        }, 1000);
    }

    /**
     * Handle offline event
     */
    function handleOffline() {
        stopPolling();
        updateConnectionStatus('disconnected');
    }

    /**
     * Handle visibility change
     */
    function handleVisibilityChange() {
        if (document.hidden) {
            // Reduce polling frequency when hidden
            stopPolling();
        } else {
            // Resume polling when visible
            startPolling();
            // Check for new messages immediately
            pollMessages();
        }
    }

    /**
     * Initialize touch gestures
     */
    function initializeTouchGestures() {
        // Touch gesture handlers implemented in event listeners
    }

    /**
     * Handle touch start
     */
    function handleTouchStart(e) {
        state.touchStartX = e.touches[0].clientX;
        state.touchStartY = e.touches[0].clientY;
    }

    /**
     * Handle touch move
     */
    function handleTouchMove(e) {
        if (!state.touchStartX || !state.touchStartY) return;
        
        const touchEndX = e.touches[0].clientX;
        const touchEndY = e.touches[0].clientY;
        
        const diffX = state.touchStartX - touchEndX;
        const diffY = state.touchStartY - touchEndY;
        
        // Prevent scrolling when swiping horizontally
        if (Math.abs(diffX) > Math.abs(diffY)) {
            e.preventDefault();
        }
    }

    /**
     * Handle touch end
     */
    function handleTouchEnd(e) {
        if (!state.touchStartX || !state.touchStartY) return;
        
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        
        const diffX = state.touchStartX - touchEndX;
        const diffY = state.touchStartY - touchEndY;
        
        // Swipe detection
        if (Math.abs(diffX) > CONFIG.touchSwipeThreshold && Math.abs(diffX) > Math.abs(diffY)) {
            if (diffX > 0) {
                // Swipe left - close sidebar
                closeSidebar();
            } else {
                // Swipe right - open sidebar
                openSidebar();
            }
        }
        
        state.touchStartX = 0;
        state.touchStartY = 0;
    }

    /**
     * Open sidebar
     */
    function openSidebar() {
        if (elements.sidebar && window.innerWidth < 768) {
            elements.sidebar.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Close sidebar
     */
    function closeSidebar() {
        if (elements.sidebar) {
            elements.sidebar.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    /**
     * Observe messages for intersection
     */
    function observeMessages() {
        if (!('IntersectionObserver' in window)) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const messageEl = entry.target;
                    const messageId = messageEl.dataset.id;
                    
                    // Mark as read
                    if (state.unreadMessages.has(messageId)) {
                        state.unreadMessages.delete(messageId);
                        markMessageAsRead(messageId);
                    }
                    
                    // Lazy load images
                    const images = messageEl.querySelectorAll('img[data-src]');
                    images.forEach(img => {
                        if (!state.loadedImages.has(img)) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            state.loadedImages.add(img);
                        }
                    });
                }
            });
        }, {
            root: elements.messagesContainer,
            rootMargin: '50px',
            threshold: 0.1
        });
        
        // Observe existing messages
        const messages = document.querySelectorAll('.message-card');
        messages.forEach(message => observer.observe(message));
        
        // Observe new messages
        const mutationObserver = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.classList && node.classList.contains('message-card')) {
                        observer.observe(node);
                    }
                });
            });
        });
        
        if (elements.messagesContainer) {
            mutationObserver.observe(elements.messagesContainer, {
                childList: true
            });
        }
    }

    /**
     * Preload images for better performance
     */
    function preloadImages() {
        const imagesToPreload = [
            '/sounds/chatify/new-message-sound.mp3',
            // Add other assets to preload
        ];
        
        imagesToPreload.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = src;
            document.head.appendChild(link);
        });
    }

    /**
     * Play notification sound
     */
    function playNotificationSound() {
        if (document.hidden) return;
        
        const audio = new Audio('/sounds/chatify/new-message-sound.mp3');
        audio.volume = 0.3;
        audio.play().catch(() => {});
    }

    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} animate-slide-in`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('animate-slide-out');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Send read receipts
     */
    async function sendReadReceipts() {
        const unreadIds = Array.from(state.unreadMessages);
        if (unreadIds.length === 0) return;
        
        try {
            await fetch('/messages/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message_ids: unreadIds
                })
            });
            
            state.unreadMessages.clear();
        } catch (error) {
            console.error('Error sending read receipts:', error);
        }
    }

    /**
     * Mark message as read
     */
    function markMessageAsRead(messageId) {
        const messageEl = document.querySelector(`.message-card[data-id="${messageId}"]`);
        if (messageEl) {
            const seenIcon = messageEl.querySelector('.seen');
            if (seenIcon) {
                seenIcon.classList.remove('fa-check');
                seenIcon.classList.add('fa-check-double');
            }
        }
    }

    /**
     * Load more messages
     */
    async function loadMoreMessages() {
        // Implementation for loading older messages
        // This would fetch messages before the first message ID
    }

    /**
     * Update read receipts for visible messages
     */
    function updateReadReceipts() {
        // Implementation for updating read receipts
    }

    /**
     * Enable smooth scrolling
     */
    function enableSmoothScrolling() {
        if (elements.messagesContainer) {
            elements.messagesContainer.style.scrollBehavior = 'smooth';
        }
    }

    /**
     * Set viewport height for mobile
     */
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    /**
     * Format time
     */
    function formatTime(date) {
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Throttle function
     */
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Debounce function
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    /**
     * Easing function for smooth animations
     */
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    /**
     * Cleanup on page unload
     */
    function cleanup() {
        stopPolling();
        if (state.typingTimer) {
            clearTimeout(state.typingTimer);
        }
        if (state.renderTimer) {
            clearTimeout(state.renderTimer);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
