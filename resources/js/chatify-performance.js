/**
 * Chatify Performance Optimizations
 * Advanced performance enhancements for smooth chat experience
 */

class ChatifyPerformance {
    constructor() {
        this.messageCache = new Map();
        this.imageCache = new Map();
        this.virtualScrollEnabled = false;
        this.intersectionObserver = null;
        this.resizeObserver = null;
        this.debounceTimers = new Map();
        this.throttleTimers = new Map();
        this.performanceMetrics = {
            messagesRendered: 0,
            imagesLoaded: 0,
            scrollEvents: 0
        };
        
        this.init();
    }

    init() {
        this.setupVirtualScrolling();
        this.setupLazyLoading();
        this.setupImageOptimization();
        this.setupMemoryManagement();
        this.setupPerformanceMonitoring();
        this.optimizeScrolling();
        this.setupPreloading();
    }

    // Virtual Scrolling for large message lists
    setupVirtualScrolling() {
        const messagesContainer = document.querySelector('.messages-container');
        if (!messagesContainer) return;

        const ITEM_HEIGHT = 80; // Average message height
        const BUFFER_SIZE = 5; // Number of items to render outside viewport
        let visibleItems = [];
        let totalItems = 0;

        const updateVirtualScroll = this.throttle(() => {
            const containerHeight = messagesContainer.clientHeight;
            const scrollTop = messagesContainer.scrollTop;
            
            const startIndex = Math.max(0, Math.floor(scrollTop / ITEM_HEIGHT) - BUFFER_SIZE);
            const endIndex = Math.min(totalItems, startIndex + Math.ceil(containerHeight / ITEM_HEIGHT) + BUFFER_SIZE * 2);

            this.renderVisibleMessages(startIndex, endIndex);
        }, 16); // 60fps

        messagesContainer.addEventListener('scroll', updateVirtualScroll, { passive: true });
        
        this.virtualScrollEnabled = true;
    }

    renderVisibleMessages(startIndex, endIndex) {
        // Implementation would depend on your message data structure
        // This is a framework for virtual scrolling
        const messagesContainer = document.querySelector('.messages');
        const fragment = document.createDocumentFragment();
        
        for (let i = startIndex; i < endIndex; i++) {
            const messageElement = this.getOrCreateMessageElement(i);
            if (messageElement) {
                fragment.appendChild(messageElement);
            }
        }
        
        // Update container with visible messages
        this.performanceMetrics.messagesRendered = endIndex - startIndex;
    }

    // Lazy Loading for images and attachments
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            this.intersectionObserver = new IntersectionObserver(
                this.handleIntersection.bind(this),
                {
                    root: null,
                    rootMargin: '50px',
                    threshold: 0.1
                }
            );

            // Observe all images
            this.observeImages();
        }
    }

    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                this.loadImage(img);
                this.intersectionObserver.unobserve(img);
            }
        });
    }

    observeImages() {
        const images = document.querySelectorAll('img[data-src], .chat-image[data-image-url]');
        images.forEach(img => {
            this.intersectionObserver.observe(img);
        });
    }

    loadImage(element) {
        return new Promise((resolve, reject) => {
            const imageUrl = element.dataset.src || element.dataset.imageUrl;
            if (!imageUrl) return reject('No image URL found');

            // Check cache first
            if (this.imageCache.has(imageUrl)) {
                const cachedImage = this.imageCache.get(imageUrl);
                this.applyImage(element, cachedImage);
                resolve(cachedImage);
                return;
            }

            // Create loading placeholder
            const placeholder = this.createImagePlaceholder(element);
            
            const img = new Image();
            img.onload = () => {
                // Cache the loaded image
                this.imageCache.set(imageUrl, img);
                this.applyImage(element, img);
                this.removeImagePlaceholder(element, placeholder);
                this.performanceMetrics.imagesLoaded++;
                resolve(img);
            };
            
            img.onerror = () => {
                this.handleImageError(element, placeholder);
                reject('Image failed to load');
            };
            
            img.src = imageUrl;
        });
    }

    createImagePlaceholder(element) {
        const placeholder = document.createElement('div');
        placeholder.className = 'lazy-image-placeholder';
        placeholder.innerHTML = `
            <div class="animate-spin w-6 h-6 border-2 border-gray-300 border-t-primary-500 rounded-full"></div>
        `;
        element.parentNode.insertBefore(placeholder, element);
        element.style.display = 'none';
        return placeholder;
    }

    removeImagePlaceholder(element, placeholder) {
        element.style.display = '';
        element.classList.add('lazy-image', 'loaded');
        if (placeholder && placeholder.parentNode) {
            placeholder.parentNode.removeChild(placeholder);
        }
    }

    applyImage(element, img) {
        if (element.tagName === 'IMG') {
            element.src = img.src;
        } else {
            element.style.backgroundImage = `url(${img.src})`;
        }
    }

    handleImageError(element, placeholder) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'lazy-image-placeholder';
        errorDiv.innerHTML = `
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        `;
        
        if (placeholder && placeholder.parentNode) {
            placeholder.parentNode.replaceChild(errorDiv, placeholder);
        }
    }

    // Image optimization and compression
    setupImageOptimization() {
        // Optimize images for different screen sizes
        this.setupResponsiveImages();
        this.setupImageCompression();
    }

    setupResponsiveImages() {
        const images = document.querySelectorAll('.chat-image');
        images.forEach(img => {
            const imageUrl = img.dataset.imageUrl;
            if (imageUrl) {
                // Create different sizes based on device
                const sizes = this.getOptimalImageSizes();
                img.dataset.srcset = this.generateSrcSet(imageUrl, sizes);
            }
        });
    }

    getOptimalImageSizes() {
        const devicePixelRatio = window.devicePixelRatio || 1;
        const screenWidth = window.screen.width * devicePixelRatio;
        
        if (screenWidth <= 640) return [240, 480]; // Mobile
        if (screenWidth <= 1024) return [320, 640]; // Tablet
        return [480, 960]; // Desktop
    }

    generateSrcSet(baseUrl, sizes) {
        return sizes.map(size => `${baseUrl}?w=${size} ${size}w`).join(', ');
    }

    setupImageCompression() {
        // Implement client-side image compression for uploads
        this.setupImageUploadOptimization();
    }

    setupImageUploadOptimization() {
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', this.handleImageUpload.bind(this));
        });
    }

    async handleImageUpload(event) {
        const files = Array.from(event.target.files);
        const optimizedFiles = await Promise.all(
            files.map(file => this.compressImage(file))
        );
        
        // Replace original files with optimized ones
        const dataTransfer = new DataTransfer();
        optimizedFiles.forEach(file => dataTransfer.items.add(file));
        event.target.files = dataTransfer.files;
    }

    compressImage(file, quality = 0.8, maxWidth = 1920, maxHeight = 1080) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = () => {
                // Calculate optimal dimensions
                const { width, height } = this.calculateOptimalDimensions(
                    img.width, img.height, maxWidth, maxHeight
                );
                
                canvas.width = width;
                canvas.height = height;

                // Draw and compress
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob(resolve, file.type, quality);
            };

            img.src = URL.createObjectURL(file);
        });
    }

    calculateOptimalDimensions(originalWidth, originalHeight, maxWidth, maxHeight) {
        const aspectRatio = originalWidth / originalHeight;
        
        let width = originalWidth;
        let height = originalHeight;
        
        if (width > maxWidth) {
            width = maxWidth;
            height = width / aspectRatio;
        }
        
        if (height > maxHeight) {
            height = maxHeight;
            width = height * aspectRatio;
        }
        
        return { width: Math.round(width), height: Math.round(height) };
    }

    // Memory management and cleanup
    setupMemoryManagement() {
        // Clear caches periodically
        setInterval(() => {
            this.cleanupCaches();
        }, 5 * 60 * 1000); // Every 5 minutes

        // Listen for memory pressure
        if ('memory' in performance) {
            this.monitorMemoryUsage();
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            this.cleanup();
        });
    }

    cleanupCaches() {
        const now = Date.now();
        const maxAge = 10 * 60 * 1000; // 10 minutes

        // Clean image cache
        for (const [url, data] of this.imageCache.entries()) {
            if (data.timestamp && now - data.timestamp > maxAge) {
                this.imageCache.delete(url);
            }
        }

        // Clean message cache
        if (this.messageCache.size > 1000) {
            const entries = Array.from(this.messageCache.entries());
            const toKeep = entries.slice(-500); // Keep last 500
            this.messageCache.clear();
            toKeep.forEach(([key, value]) => {
                this.messageCache.set(key, value);
            });
        }
    }

    monitorMemoryUsage() {
        const checkMemory = () => {
            const memoryInfo = performance.memory;
            const memoryUsagePercent = memoryInfo.usedJSHeapSize / memoryInfo.jsHeapSizeLimit;
            
            if (memoryUsagePercent > 0.9) {
                console.warn('High memory usage detected, cleaning up caches');
                this.cleanupCaches();
                // Reduce cache sizes
                this.imageCache = new Map(Array.from(this.imageCache.entries()).slice(-50));
                this.messageCache = new Map(Array.from(this.messageCache.entries()).slice(-100));
            }
        };

        setInterval(checkMemory, 30000); // Check every 30 seconds
    }

    // Optimized scrolling
    optimizeScrolling() {
        const scrollableElements = document.querySelectorAll('.app-scroll, .messages-container');
        
        scrollableElements.forEach(element => {
            // Use passive listeners for better performance
            element.addEventListener('scroll', this.handleScroll.bind(this), { passive: true });
            
            // Add momentum scrolling for iOS
            element.style.webkitOverflowScrolling = 'touch';
        });
    }

    handleScroll(event) {
        this.performanceMetrics.scrollEvents++;
        
        const element = event.target;
        const scrollPercentage = element.scrollTop / (element.scrollHeight - element.clientHeight);
        
        // Show/hide scroll to bottom button
        this.updateScrollToBottom(scrollPercentage);
        
        // Load more messages if needed
        if (scrollPercentage < 0.1) {
            this.loadMoreMessages();
        }
    }

    updateScrollToBottom(scrollPercentage) {
        const scrollButton = document.getElementById('scroll-to-bottom');
        if (scrollButton) {
            const shouldShow = scrollPercentage < 0.9;
            scrollButton.style.transform = shouldShow ? 'scale(1)' : 'scale(0)';
            scrollButton.style.opacity = shouldShow ? '1' : '0';
        }
    }

    loadMoreMessages() {
        // Throttle message loading
        if (!this.throttleTimers.has('loadMessages')) {
            this.throttleTimers.set('loadMessages', setTimeout(() => {
                // Implement message loading logic here
                this.throttleTimers.delete('loadMessages');
            }, 1000));
        }
    }

    // Preloading strategies
    setupPreloading() {
        // Preload next batch of messages
        this.preloadNextMessages();
        
        // Preload user avatars
        this.preloadUserAvatars();
        
        // Prefetch frequently used assets
        this.prefetchAssets();
    }

    preloadNextMessages() {
        // Implementation depends on your message loading strategy
        const currentMessageCount = document.querySelectorAll('.message-card').length;
        if (currentMessageCount > 0) {
            // Preload next 20 messages in background
            this.requestIdleCallback(() => {
                // Load next batch of messages
            });
        }
    }

    preloadUserAvatars() {
        const avatars = document.querySelectorAll('img[data-user-avatar]');
        avatars.forEach(avatar => {
            if (this.intersectionObserver) {
                this.intersectionObserver.observe(avatar);
            }
        });
    }

    prefetchAssets() {
        const criticalAssets = [
            '/css/chatify-enhanced.css',
            '/js/chatify-smooth.js'
        ];

        criticalAssets.forEach(asset => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = asset;
            document.head.appendChild(link);
        });
    }

    // Performance monitoring
    setupPerformanceMonitoring() {
        if (typeof window !== 'undefined' && window.performance) {
            this.monitorFPS();
            this.trackLoadTimes();
        }
    }

    monitorFPS() {
        let lastTime = performance.now();
        let frames = 0;
        
        const updateFPS = (currentTime) => {
            frames++;
            if (currentTime >= lastTime + 1000) {
                const fps = Math.round((frames * 1000) / (currentTime - lastTime));
                
                const fpsCounter = document.getElementById('fps-counter');
                if (fpsCounter) {
                    fpsCounter.textContent = fps;
                    
                    // Change color based on FPS
                    if (fps < 30) {
                        fpsCounter.className = 'text-red-400';
                    } else if (fps < 50) {
                        fpsCounter.className = 'text-yellow-400';
                    } else {
                        fpsCounter.className = 'text-green-400';
                    }
                }
                
                frames = 0;
                lastTime = currentTime;
            }
            
            requestAnimationFrame(updateFPS);
        };
        
        requestAnimationFrame(updateFPS);
    }

    trackLoadTimes() {
        // Track message render times
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach(entry => {
                if (entry.name.includes('message-render')) {
                    console.debug(`Message render time: ${entry.duration}ms`);
                }
            });
        });
        
        observer.observe({ entryTypes: ['measure'] });
    }

    // Utility functions
    debounce(func, delay) {
        return (...args) => {
            const key = func.name || 'anonymous';
            clearTimeout(this.debounceTimers.get(key));
            this.debounceTimers.set(key, setTimeout(() => func.apply(this, args), delay));
        };
    }

    throttle(func, delay) {
        return (...args) => {
            const key = func.name || 'anonymous';
            if (!this.throttleTimers.has(key)) {
                func.apply(this, args);
                this.throttleTimers.set(key, setTimeout(() => {
                    this.throttleTimers.delete(key);
                }, delay));
            }
        };
    }

    requestIdleCallback(callback, options = {}) {
        if ('requestIdleCallback' in window) {
            return window.requestIdleCallback(callback, options);
        } else {
            // Fallback for browsers without requestIdleCallback
            return setTimeout(callback, 1);
        }
    }

    getOrCreateMessageElement(index) {
        // This would be implemented based on your message data structure
        // Return cached element or create new one
        const cacheKey = `message-${index}`;
        if (this.messageCache.has(cacheKey)) {
            return this.messageCache.get(cacheKey);
        }
        
        // Create and cache new message element
        const element = this.createMessageElement(index);
        this.messageCache.set(cacheKey, element);
        return element;
    }

    createMessageElement(index) {
        // Implement based on your message structure
        const div = document.createElement('div');
        div.className = 'message-card';
        div.style.transform = `translateY(${index * 80}px)`;
        return div;
    }

    cleanup() {
        // Clean up observers
        if (this.intersectionObserver) {
            this.intersectionObserver.disconnect();
        }
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
        }
        
        // Clear timers
        this.debounceTimers.clear();
        this.throttleTimers.clear();
        
        // Clear caches
        this.messageCache.clear();
        this.imageCache.clear();
    }

    // Public API methods
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            cacheSize: {
                messages: this.messageCache.size,
                images: this.imageCache.size
            },
            memoryUsage: performance.memory ? {
                used: Math.round(performance.memory.usedJSHeapSize / 1024 / 1024),
                total: Math.round(performance.memory.totalJSHeapSize / 1024 / 1024),
                limit: Math.round(performance.memory.jsHeapSizeLimit / 1024 / 1024)
            } : null
        };
    }

    clearCaches() {
        this.messageCache.clear();
        this.imageCache.clear();
    }

    preloadMessages(messageIds) {
        // Preload specific messages
        messageIds.forEach(id => {
            this.requestIdleCallback(() => {
                // Load message with given ID
            });
        });
    }
}

// Initialize performance optimizations
let chatifyPerformance;

function initPerformanceOptimizations() {
    if (typeof ChatifyPerformance !== 'undefined') {
        chatifyPerformance = new ChatifyPerformance();
        
        // Expose to global scope for debugging
        if (window.APP_ENV === 'local') {
            window.chatifyPerformance = chatifyPerformance;
        }
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPerformanceOptimizations);
} else {
    initPerformanceOptimizations();
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ChatifyPerformance;
}
