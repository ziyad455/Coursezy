/**
 * Chatify Recipient ID Fixer
 * This script ensures the recipient ID is always set when sending messages
 */

(function() {
    'use strict';

    // Function to get recipient ID from various sources
    function getCurrentRecipientId() {
        // Method 1: Check if we're in a conversation URL
        const urlMatch = window.location.href.match(/\/chatify\/(\d+)/);
        if (urlMatch) {
            return urlMatch[1];
        }

        // Method 2: Check for active conversation in sidebar
        const activeItem = document.querySelector('.messenger-list-item.m-list-active');
        if (activeItem) {
            const dataId = activeItem.getAttribute('data-contact');
            if (dataId) return dataId;
        }

        // Method 3: Check messenger header for recipient info
        const messengerHeader = document.querySelector('.messenger-header .m-header-left');
        if (messengerHeader) {
            const avatar = messengerHeader.querySelector('.avatar');
            if (avatar) {
                const onclickAttr = avatar.getAttribute('onclick');
                if (onclickAttr) {
                    const idMatch = onclickAttr.match(/\((\d+)\)/);
                    if (idMatch) return idMatch[1];
                }
            }
        }

        // Method 4: Check for data-id in active elements
        const activeUser = document.querySelector('[data-id].active-user, .active-user[data-id]');
        if (activeUser) {
            return activeUser.getAttribute('data-id');
        }

        // Method 5: Global Chatify object
        if (window.messengerTheme && window.messengerTheme.info && window.messengerTheme.info.id) {
            return window.messengerTheme.info.id;
        }

        return null;
    }

    // Function to update the to_id field
    function updateRecipientId() {
        const toIdInput = document.getElementById('to_id') || document.querySelector('input[name="to_id"]');
        if (!toIdInput) {
            console.warn('to_id input field not found');
            return false;
        }

        const recipientId = getCurrentRecipientId();
        if (recipientId) {
            toIdInput.value = recipientId;
            console.log('Recipient ID set to:', recipientId);
            return true;
        } else {
            console.warn('Could not determine recipient ID');
            return false;
        }
    }

    // Update on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateRecipientId();

        // Also enable form elements
        const fileInput = document.querySelector('.upload-attachment');
        const messageInput = document.querySelector('.m-send');
        const sendButton = document.querySelector('.send-button');
        
        if (fileInput) fileInput.disabled = false;
        if (messageInput) messageInput.readOnly = false;
        if (sendButton) sendButton.disabled = false;
    });

    // Update when clicking on conversations
    document.addEventListener('click', function(e) {
        if (e.target.closest('.messenger-list-item')) {
            setTimeout(updateRecipientId, 500);
        }
    });

    // Update before form submission
    const form = document.getElementById('message-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!updateRecipientId()) {
                e.preventDefault();
                alert('Please select a conversation first');
                return false;
            }
        });
    }

    // Monitor for AJAX conversation loads
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args).then(response => {
            if (args[0] && args[0].includes && args[0].includes('chatify')) {
                setTimeout(updateRecipientId, 500);
            }
            return response;
        });
    };

    // Periodic check to ensure ID is set
    setInterval(updateRecipientId, 2000);

})();
