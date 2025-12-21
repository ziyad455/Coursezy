import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans,'Inter', 'system-ui', 'sans-serif'],
      },
      colors: {
        // Custom Dark Mode Colors - Improved for better harmony
        dark: {
          bg: {
            primary: '#0F0F1E',      // Softer dark blue-black (not pure black)
            secondary: '#1A1A2E',    // Dark blue-purple for cards
            tertiary: '#16213E',     // Medium dark blue for sections
          },
          text: {
            primary: '#F0F0F5',      // Brighter white-ish for main text
            secondary: '#B8B8D0',    // Soft purple-gray for secondary text
            muted: '#7A7A95',        // Muted purple-gray
          },
          button: {
            bg: '#6C63FF',           // Vibrant purple for buttons
            hover: '#5A52E0',        // Darker purple on hover
            active: '#4A42C0',       // Even darker on active
            text: '#FFFFFF',         // White text
          },
          link: {
            default: '#8B7FFF',      // Soft bright purple for links
            hover: '#A89FFF',        // Lighter purple on hover
            visited: '#9F8FFF',      // Medium purple for visited
          },
          accent: {
            primary: '#FF6B9D',      // Pink-red accent (softer than pure red)
            secondary: '#6C63FF',    // Purple accent
            success: '#4ECDC4',      // Teal for success
            warning: '#FFD93D',      // Bright yellow for warnings
            error: '#FF6B6B',        // Coral red for errors
            info: '#6BCF7F',         // Soft green for info
          },
          border: '#2D2D44',         // Subtle purple-gray borders
          input: {
            bg: '#1A1A2E',
            border: '#2D2D44',
            focus: '#6C63FF',
            placeholder: '#7A7A95',
          },
        },
        
        // Custom Light Mode Colors - Improved harmony
        light: {
          bg: {
            primary: '#FAFBFF',      // Soft white with blue tint
            secondary: '#F5F6FA',    // Light gray-blue for cards
            tertiary: '#EBEEF5',     // Slightly darker for sections
          },
          text: {
            primary: '#1A1A2E',      // Dark blue-black for main text
            secondary: '#4A4A6A',    // Medium blue-gray for secondary
            muted: '#7A7A95',        // Muted purple-gray
          },
          button: {
            bg: '#6C63FF',           // Vibrant purple (matches dark mode)
            hover: '#5A52E0',        // Darker purple on hover
            active: '#4A42C0',       // Even darker on active
            text: '#FFFFFF',         // White text
          },
          link: {
            default: '#6C63FF',      // Purple links
            hover: '#5A52E0',        // Darker purple on hover
            visited: '#8B7FFF',      // Lighter purple for visited
          },
          accent: {
            primary: '#FF6B9D',      // Pink-red accent
            secondary: '#6C63FF',    // Purple accent
            success: '#4ECDC4',      // Teal for success
            warning: '#FFB84D',      // Orange for warnings
            error: '#FF6B6B',        // Coral red for errors
            info: '#6BCF7F',         // Soft green for info
          },
          border: '#E0E2EA',         // Soft gray-blue borders
          input: {
            bg: '#FFFFFF',
            border: '#D5D7E0',
            focus: '#6C63FF',
            placeholder: '#7A7A95',
          },
        },
        
        // Keep existing primary colors for compatibility
        primary: {
          50: '#f0f9ff',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
        },
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
        'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'shake': 'shake 0.5s ease-in-out',
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.6s ease-out',
        'pulse-soft': 'pulseSoft 2s infinite',
        'slide-in-left': 'slideInLeft 0.3s ease-out',
        'slide-in-right': 'slideInRight 0.3s ease-out',
        'bounce-in': 'bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'scale-in': 'scaleIn 0.3s ease-out',
        'spin-slow': 'spin 3s linear infinite',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(100%)' },
          '100%': { transform: 'translateY(0)' },
        },
        slideInLeft: {
          '0%': { transform: 'translateX(-100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        slideInRight: {
          '0%': { transform: 'translateX(100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        bounceIn: {
          '0%': { transform: 'scale(0.3)', opacity: '0' },
          '50%': { transform: 'scale(1.05)' },
          '70%': { transform: 'scale(0.9)' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.9)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        pulseSoft: {
          '0%, 100%': { opacity: '1' },
          '50%': { opacity: '0.5' },
        },
      },
      screens: {
        'xs': '475px',
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
        '3xl': '1920px',
      }
    },
  },
  plugins: [forms],
};