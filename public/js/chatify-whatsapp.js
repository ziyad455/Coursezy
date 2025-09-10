/**
 * Chatify WhatsApp-Style Pure JavaScript
 * Smooth, seamless interactions without frameworks
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', initChatify);

    function initChatify() {
        // Initialize all features
        initMobileMenu();
        initSearchFunctionality();
        initMessageAnimations();
        initTypingIndicator();
        initSmoothScrolling();
        initMessageActions();
        initAutoResize();
        initKeyboardShortcuts();
        initConnectionStatus();
        initImagePreview();
    }

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const listView = document.querySelector('.messenger-listView');
        const showBtn = document.querySelector('.show-listView');
        const messagingView = document.querySelector('.messenger-messagingView');
        
        if (showBtn) {
            showBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (listView) {
                    listView.classList.add('show');
                }
            });
        }

        // Close on selecting a conversation
        const listItems = document.querySelectorAll('.messenger-list-item');
        listItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768 && listView) {
                    setTimeout(() => {
                        listView.classList.remove('show');
                    }, 300);
                }
            });
        });

        // Add swipe gestures for mobile
        if (window.innerWidth <= 768) {
            addSwipeGestures(listView, messagingView);
        }
    }

    /**
     * Swipe Gestures for Mobile
     */
    function addSwipeGestures(listView, messagingView) {
        let touchStartX = 0;
        let touchEndX = 0;
        
        if (messagingView) {
            messagingView.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            messagingView.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
        
        function handleSwipe() {
            if (touchEndX - touchStartX > 50 && listView) {
                // Swipe right - show sidebar
                listView.classList.add('show');
            }
        }
        
        if (listView) {
            listView.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            listView.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) {
                    // Swipe left - hide sidebar
                    listView.classList.remove('show');
                }
            });
        }
    }

    /**
     * Enhanced Search Functionality
     */
    function initSearchFunctionality() {
        const searchInput = document.querySelector('.messenger-search');
        const contactsList = document.querySelector('.listOfContacts');
        
        if (!searchInput || !contactsList) return;
        
        // Add search icon
        const searchIcon = document.createElement('i');
        searchIcon.className = 'fas fa-search search-icon';
        searchInput.parentElement.insertBefore(searchIcon, searchInput);
        
        // Debounced search
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.toLowerCase();
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        // Clear search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                performSearch('');
            }
        });
    }
    
    function performSearch(query) {
        const items = document.querySelectorAll('.messenger-list-item');
        let hasResults = false;
        
        items.forEach(item => {
            const name = item.textContent.toLowerCase();
            if (name.includes(query) || query === '') {
                item.style.display = '';
                hasResults = true;
                // Add subtle animation
                item.style.animation = 'fadeInUp 0.3s ease';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show no results message
        const noResults = document.querySelector('.no-search-results');
        if (!hasResults && query !== '') {
            if (!noResults) {
                const msg = document.createElement('div');
                msg.className = 'no-search-results';
                msg.textContent = 'No conversations found';
                msg.style.cssText = 'text-align: center; padding: 20px; color: #667781;';
                document.querySelector('.listOfContacts').appendChild(msg);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }

    /**
     * Message Animations
     */
    function initMessageAnimations() {
        const messages = document.querySelectorAll('.message-card');
        
        // Stagger animation for existing messages
        messages.forEach((msg, index) => {
            msg.style.animationDelay = `${index * 50}ms`;
        });
        
        // Observer for new messages
        const messagesContainer = document.querySelector('.messages');
        if (messagesContainer) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.classList && node.classList.contains('message-card')) {
                            // Animate new message
                            node.style.animation = 'messageSlideIn 0.3s ease forwards';
                            
                            // Scroll to bottom
                            smoothScrollToBottom();
                            
                            // Play notification sound
                            playMessageSound();
                        }
                    });
                });
            });
            
            observer.observe(messagesContainer, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Typing Indicator
     */
    function initTypingIndicator() {
        const input = document.querySelector('.m-send');
        const typingIndicator = document.querySelector('.typing-indicator');
        let typingTimer;
        let isTyping = false;
        
        if (!input || !typingIndicator) return;
        
        input.addEventListener('input', function() {
            if (!isTyping) {
                showTypingIndicator();
                isTyping = true;
            }
            
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                hideTypingIndicator();
                isTyping = false;
            }, 1000);
        });
        
        function showTypingIndicator() {
            // This would normally send to server
            // For demo, just show locally
            if (typingIndicator) {
                typingIndicator.classList.add('show');
            }
        }
        
        function hideTypingIndicator() {
            if (typingIndicator) {
                typingIndicator.classList.remove('show');
            }
        }
    }

    /**
     * Smooth Scrolling
     */
    function initSmoothScrolling() {
        const scrollContainer = document.querySelector('.m-body.messages-container');
        if (!scrollContainer) return;
        
        // Smooth scroll to bottom button
        const scrollBtn = createScrollToBottomButton();
        scrollContainer.appendChild(scrollBtn);
        
        let isScrolledToBottom = true;
        
        scrollContainer.addEventListener('scroll', function() {
            const threshold = 100;
            const position = scrollContainer.scrollTop + scrollContainer.clientHeight;
            const height = scrollContainer.scrollHeight;
            
            isScrolledToBottom = height - position < threshold;
            
            if (isScrolledToBottom) {
                scrollBtn.style.opacity = '0';
                scrollBtn.style.transform = 'scale(0)';
            } else {
                scrollBtn.style.opacity = '1';
                scrollBtn.style.transform = 'scale(1)';
            }
        });
        
        scrollBtn.addEventListener('click', smoothScrollToBottom);
    }
    
    function createScrollToBottomButton() {
        const btn = document.createElement('button');
        btn.className = 'scroll-to-bottom';
        btn.innerHTML = '<i class="fas fa-chevron-down"></i>';
        btn.style.cssText = `
            position: fixed;
            bottom: 80px;
            right: 30px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
            z-index: 100;
        `;
        return btn;
    }
    
    function smoothScrollToBottom() {
        const scrollContainer = document.querySelector('.m-body.messages-container');
        if (scrollContainer) {
            scrollContainer.scrollTo({
                top: scrollContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    }

    /**
     * Message Actions (Delete, Edit, etc.)
     */
    function initMessageActions() {
        // Delete message
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                const messageCard = e.target.closest('.message-card');
                if (messageCard) {
                    // Add fade out animation
                    messageCard.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => {
                        messageCard.remove();
                    }, 300);
                }
            }
        });
        
        // Double click to reply
        const messages = document.querySelectorAll('.message');
        messages.forEach(msg => {
            msg.addEventListener('dblclick', function() {
                const input = document.querySelector('.m-send');
                if (input) {
                    // Get message text
                    const text = this.textContent.trim().substring(0, 50);
                    input.value = `Replying to: "${text}..."\n`;
                    input.focus();
                }
            });
        });
    }

    /**
     * Auto-resize textarea
     */
    function initAutoResize() {
        const textarea = document.querySelector('.m-send');
        if (!textarea) return;
        
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            const newHeight = Math.min(this.scrollHeight, 100);
            this.style.height = newHeight + 'px';
        });
    }

    /**
     * Keyboard Shortcuts
     */
    function initKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K - Focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const search = document.querySelector('.messenger-search');
                if (search) search.focus();
            }
            
            // Escape - Close modals/menus
            if (e.key === 'Escape') {
                const listView = document.querySelector('.messenger-listView');
                if (listView && listView.classList.contains('show')) {
                    listView.classList.remove('show');
                }
            }
        });
        
        // Enter to send (Shift+Enter for new line)
        const input = document.querySelector('.m-send');
        if (input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (form) {
                        // Trigger form submit
                        const submitEvent = new Event('submit', { bubbles: true });
                        form.dispatchEvent(submitEvent);
                    }
                }
            });
        }
    }

    /**
     * Connection Status Indicator
     */
    function initConnectionStatus() {
        const indicator = document.querySelector('.internet-connection');
        if (!indicator) return;
        
        // Monitor online/offline status
        window.addEventListener('online', function() {
            updateConnectionStatus('connected');
        });
        
        window.addEventListener('offline', function() {
            updateConnectionStatus('offline');
        });
        
        function updateConnectionStatus(status) {
            const spans = indicator.querySelectorAll('span');
            spans.forEach(span => span.style.display = 'none');
            
            if (status === 'connected') {
                indicator.classList.remove('show');
                const connected = indicator.querySelector('.ic-connected');
                if (connected) connected.style.display = 'inline';
            } else {
                indicator.classList.add('show');
                const noInternet = indicator.querySelector('.ic-noInternet');
                if (noInternet) noInternet.style.display = 'inline';
            }
        }
    }

    /**
     * Image Preview
     */
    function initImagePreview() {
        const images = document.querySelectorAll('.image-file');
        
        images.forEach(img => {
            img.addEventListener('click', function() {
                createImageModal(this);
            });
        });
    }
    
    function createImageModal(imageElement) {
        const modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        `;
        
        const img = document.createElement('img');
        img.src = imageElement.style.backgroundImage.slice(5, -2);
        img.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            border-radius: 4px;
            animation: scaleIn 0.3s ease;
        `;
        
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = `
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 40px;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
        `;
        
        modal.appendChild(img);
        modal.appendChild(closeBtn);
        document.body.appendChild(modal);
        
        // Close on click
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target === closeBtn) {
                modal.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => modal.remove(), 300);
            }
        });
        
        // Close on Escape
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => modal.remove(), 300);
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    /**
     * Play notification sound
     */
    function playMessageSound() {
        // Create audio element
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjiS2Oy9diMFl2+z9N17');
        audio.volume = 0.3;
        audio.play().catch(() => {}); // Ignore autoplay errors
    }

    /**
     * Add CSS animations
     */
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

})();
