/**
 * Main application JavaScript
 * Consolidates common logic for dark mode, Swiper, and animations.
 */

// Dark Mode Functionality
export function initDarkMode() {
    const html = document.documentElement;
    let isDarkMode = localStorage.getItem('darkMode') === 'true';

    // Check system preference if no saved preference
    if (localStorage.getItem('darkMode') === null) {
        isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    const updateDarkMode = () => {
        if (isDarkMode) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    };

    window.toggleDarkMode = () => {
        isDarkMode = !isDarkMode;
        localStorage.setItem('darkMode', isDarkMode);
        updateDarkMode();
    };

    updateDarkMode();
}

// Swiper Initialization
export function initSwiper(selector = '.courseSwiper') {
    if (typeof Swiper === 'undefined' || !document.querySelector(selector)) return;

    return new Swiper(selector, {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: { slidesPerView: 2, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 30 },
            1280: { slidesPerView: 4, spaceBetween: 30 },
        },
    });
}

// Scroll Animations
export function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observe elements that want fade-in-up animation
    document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
}

// Mobile Search Toggle
window.toggleMobileSearch = () => {
    const searchBar = document.getElementById('mobile-search');
    if (searchBar) {
        searchBar.classList.toggle('hidden');
    }
};

// Global Initialization
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    initSwiper();
    initScrollAnimations();
});
