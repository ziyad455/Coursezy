/**
 * Chatify Responsive JavaScript
 * Handles mobile menu toggles, touch gestures, and responsive behaviors
 * Enhanced with smooth animations and accessibility features
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu elements
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const closeSidebar = document.getElementById('close-sidebar');
    const messengerSidebar = document.getElementById('messenger-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // Touch gesture variables
    let touchStartX = 0;
    let touchEndX = 0;
    let touchStartY = 0;
    let touchEndY = 0;

    /**
     * Toggle mobile sidebar
     */
    function toggleSidebar() {
        if (messengerSidebar) {
            const isOpen = messengerSidebar.classList.contains('translate-x-0');
            
            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
    }

    /**
     * Open mobile sidebar
     */
    function openSidebar() {
        if (messengerSidebar && window.innerWidth < 768) {
            messengerSidebar.classList.remove('-translate-x-full');
            messengerSidebar.classList.add('translate-x-0');
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => {
                    sidebarOverlay.classList.add('opacity-100');
                }, 10);
            }
            
            // Prevent body scroll when sidebar is open
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Close mobile sidebar
     */
    function closeSidebarFunc() {
        if (messengerSidebar) {
            messengerSidebar.classList.add('-translate-x-full');
            messengerSidebar.classList.remove('translate-x-0');
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('opacity-100');
                setTimeout(() => {
                    sidebarOverlay.classList.add('hidden');
                }, 300);
            }
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    /**
     * Handle mobile back button navigation
     */
    function initMobileBackButton() {
        const mobileBackBtn = document.getElementById('mobile-back-btn');
        
        if (mobileBackBtn) {
            mobileBackBtn.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    openSidebar();
                }
            });
        }
    }

    /**
     * Handle touch gestures for swipe navigation
     */
    function handleGesture() {
        const swipeThreshold = 50;
        const verticalThreshold = 100;
        
        const horizontalSwipe = touchEndX - touchStartX;
        const verticalSwipe = Math.abs(touchEndY - touchStartY);
        
        // Only process horizontal swipes (ignore vertical scrolling)
        if (verticalSwipe < verticalThreshold) {
            if (horizontalSwipe > swipeThreshold) {
                // Swipe right - open sidebar
                if (window.innerWidth < 768) {
                    openSidebar();
                }
            } else if (horizontalSwipe < -swipeThreshold) {
                // Swipe left - close sidebar
                if (window.innerWidth < 768) {
                    closeSidebarFunc();
                }
            }
        }
    }

    /**
     * Responsive viewport height fix for mobile browsers
     */
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    /**
     * Handle responsive image loading
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    /**
     * Optimize scrolling performance
     */
    function optimizeScrolling() {
        let ticking = false;
        
        function updateScrolling() {
            // Add/remove classes based on scroll position
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const header = document.querySelector('.messenger-header');
            
            if (header) {
                if (scrollTop > 100) {
                    header.classList.add('shadow-lg');
                } else {
                    header.classList.remove('shadow-lg');
                }
            }
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateScrolling);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick, { passive: true });
    }

    /**
     * Handle responsive modal sizing
     */
    function adjustModalSize() {
        const modals = document.querySelectorAll('.app-modal-card');
        const viewportHeight = window.innerHeight;
        
        modals.forEach(modal => {
            if (viewportHeight < 600) {
                modal.style.maxHeight = '90vh';
                modal.style.overflowY = 'auto';
            } else {
                modal.style.maxHeight = '';
                modal.style.overflowY = '';
            }
        });
    }

    /**
     * Handle responsive textarea auto-resize
     */
    function initTextareaResize() {
        const textarea = document.querySelector('.m-send');
        
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
        }
    }

    /**
     * Add responsive keyboard shortcuts
     */
    function initKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Escape key closes sidebar on mobile
            if (e.key === 'Escape' && window.innerWidth < 768) {
                closeSidebarFunc();
            }
            
            // Ctrl/Cmd + K opens search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('.messenger-search');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    }

    /**
     * Handle responsive font sizing
     */
    function adjustFontSize() {
        const baseFontSize = window.innerWidth < 640 ? 14 : 
                           window.innerWidth < 1024 ? 15 : 16;
        
        document.documentElement.style.fontSize = baseFontSize + 'px';
    }

    /**
     * Initialize touch events for mobile
     */
    function initTouchEvents() {
        // Touch start
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });
        
        // Touch end
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleGesture();
        }, { passive: true });
    }

    /**
     * Handle responsive animations
     */
    function initAnimations() {
        // Reduce animations on low-end devices
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.classList.add('reduce-motion');
        }
        
        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    }

    /**
     * Handle responsive connection status
     */
    function initConnectionStatus() {
        function updateConnectionStatus() {
            if (!navigator.onLine) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in';
                toast.textContent = 'You are offline';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }
        
        window.addEventListener('offline', updateConnectionStatus);
        window.addEventListener('online', () => {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in';
            toast.textContent = 'Back online';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        });
    }

    /**
     * Initialize all responsive features
     */
    function init() {
        // Event listeners for mobile menu
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', toggleSidebar);
        }
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebarFunc);
        }
        
        // Initialize mobile back button
        initMobileBackButton();
        
        // Close sidebar when clicking on a chat item (mobile only)
        if (window.innerWidth < 768) {
            document.querySelectorAll('.messenger-list-item').forEach(item => {
                item.addEventListener('click', () => {
                    setTimeout(closeSidebarFunc, 300);
                });
            });
        }
        
        // Initialize responsive features
        setViewportHeight();
        lazyLoadImages();
        optimizeScrolling();
        adjustModalSize();
        initTextareaResize();
        initKeyboardShortcuts();
        adjustFontSize();
        initTouchEvents();
        initAnimations();
        initConnectionStatus();
        
        // Handle resize events
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                setViewportHeight();
                adjustModalSize();
                adjustFontSize();
                
                // Auto-close sidebar on desktop resize
                if (window.innerWidth >= 768 && messengerSidebar) {
                    messengerSidebar.classList.remove('translate-x-0');
                    messengerSidebar.classList.add('-translate-x-full');
                    document.body.style.overflow = '';
                }
            }, 250);
        });
        
        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                setViewportHeight();
                adjustModalSize();
            }, 100);
        });
    }

    // Initialize when DOM is ready
    init();
    
    // Re-initialize when new content is loaded (for AJAX)
    window.addEventListener('chatify:loaded', init);
    });

    // Export functions for external use
    window.ChatifyResponsive = {
    openSidebar: function() {
        const sidebar = document.getElementById('messenger-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && window.innerWidth < 768) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            
            if (overlay) {
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                }, 10);
            }
            
            document.body.style.overflow = 'hidden';
        }
    },
    
    closeSidebar: function() {
        const sidebar = document.getElementById('messenger-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
        }
        
        if (overlay) {
            overlay.classList.remove('opacity-100');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }
        
        document.body.style.overflow = '';
    }
    };
})();
