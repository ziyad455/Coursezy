// Chatify Modern UI Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality for conversations
    const searchInput = document.getElementById('messenger-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const contactItems = document.querySelectorAll('.listOfContacts > div, .messenger-list-item');
            
            contactItems.forEach(item => {
                const name = item.textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Auto-resize textarea
    const messageTextarea = document.querySelector('textarea[name="message"]');
    if (messageTextarea) {
        messageTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }
    
    // Add smooth scroll to messages
    const messagesContainer = document.querySelector('.m-body.messages-container');
    if (messagesContainer) {
        messagesContainer.scrollBehavior = 'smooth';
    }
    
    // Mobile responsive toggle
    const showListViewBtn = document.querySelector('.show-listView');
    const listView = document.querySelector('.messenger-listView');
    
    if (showListViewBtn && listView) {
        showListViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            listView.classList.toggle('show');
        });
        
        // Close sidebar when clicking on a contact in mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.listOfContacts > div, .messenger-list-item').forEach(item => {
                item.addEventListener('click', function() {
                    setTimeout(() => {
                        listView.classList.remove('show');
                    }, 300);
                });
            });
        }
    }
    
    // Dark mode persistence
    const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                      document.documentElement.classList.contains('dark');
    
    if (isDarkMode) {
        document.body.classList.add('dark');
    }
    
    // Observe dark mode changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                }
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('button, .messenger-list-item').forEach(elem => {
        elem.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Emoji picker placeholder (you can integrate a real emoji picker library)
    const emojiButton = document.querySelector('.emoji-button');
    if (emojiButton) {
        emojiButton.addEventListener('click', function() {
            // Add your emoji picker logic here
            const messageInput = document.querySelector('textarea[name="message"]');
            if (messageInput) {
                // Example: Insert a sample emoji
                const emojis = ['😊', '👍', '❤️', '😂', '🎉', '🔥', '✨', '💯'];
                const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
                const cursorPos = messageInput.selectionStart;
                const textBefore = messageInput.value.substring(0, cursorPos);
                const textAfter = messageInput.value.substring(cursorPos);
                messageInput.value = textBefore + randomEmoji + textAfter;
                messageInput.focus();
                messageInput.setSelectionRange(cursorPos + 2, cursorPos + 2);
            }
        });
    }
});

// Ripple effect CSS
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    button, .messenger-list-item {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
