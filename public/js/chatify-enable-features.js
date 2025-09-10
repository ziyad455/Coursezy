/**
 * Enable Chatify Features - Fix disabled elements
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', enableFeatures);

    function enableFeatures() {
        enableAttachmentButton();
        enableMessageInput();
        enableSendButton();
        setupAttachmentPreview();
        fixRecipientId();
    }

    /**
     * Enable the attachment button (file upload)
     */
    function enableAttachmentButton() {
        // Find the file input
        const fileInput = document.querySelector('.upload-attachment');
        if (fileInput) {
            // Remove disabled attribute
            fileInput.removeAttribute('disabled');
            fileInput.disabled = false;
            
            console.log('Attachment button enabled');
            
            // Add change event listener
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('File selected:', file.name);
                    handleFileSelection(file);
                }
            });
        }
        
        // Make sure the label is clickable
        const label = document.querySelector('label[aria-label="Attach file"]');
        if (label) {
            label.style.cursor = 'pointer';
            label.style.pointerEvents = 'auto';
        }
    }

    /**
     * Enable the message input textarea
     */
    function enableMessageInput() {
        const messageInput = document.querySelector('.m-send');
        if (messageInput) {
            // Remove readonly attribute
            messageInput.removeAttribute('readonly');
            messageInput.readOnly = false;
            
            console.log('Message input enabled');
        }
    }

    /**
     * Enable the send button
     */
    function enableSendButton() {
        const sendButton = document.querySelector('.send-button');
        if (sendButton) {
            // Remove disabled attribute
            sendButton.removeAttribute('disabled');
            sendButton.disabled = false;
            
            // Update classes to remove disabled styling
            sendButton.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed', 'disabled:transform-none', 'disabled:shadow-none');
            
            console.log('Send button enabled');
        }
    }

    /**
     * Handle file selection and show preview
     */
    function handleFileSelection(file) {
        // Check file size (default 10MB limit)
        const maxSize = window.chatify?.maxUploadSize || 10485760;
        if (file.size > maxSize) {
            alert(`File size exceeds the maximum allowed size of ${Math.round(maxSize / 1048576)}MB`);
            return;
        }

        // Check file type
        const allowedImages = window.chatify?.allowedImages || ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        const allowedFiles = window.chatify?.allowedFiles || ['pdf', 'doc', 'docx', 'txt', 'zip'];
        const allAllowed = [...allowedImages, ...allowedFiles];
        
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allAllowed.includes(fileExtension)) {
            alert(`File type .${fileExtension} is not allowed`);
            return;
        }

        // Show preview if it's an image
        if (allowedImages.includes(fileExtension)) {
            showImagePreview(file);
        } else {
            showFilePreview(file);
        }
    }

    /**
     * Show image preview
     */
    function showImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview element
            const preview = createPreviewElement(e.target.result, file.name, true);
            insertPreview(preview);
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show file preview
     */
    function showFilePreview(file) {
        const preview = createPreviewElement(null, file.name, false);
        insertPreview(preview);
    }

    /**
     * Create preview element
     */
    function createPreviewElement(imageSrc, fileName, isImage) {
        const preview = document.createElement('div');
        preview.className = 'attachment-preview-container';
        preview.style.cssText = `
            position: relative;
            padding: 10px;
            margin: 10px 0;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        `;

        if (isImage && imageSrc) {
            preview.innerHTML = `
                <img src="${imageSrc}" alt="${fileName}" style="
                    width: 60px;
                    height: 60px;
                    object-fit: cover;
                    border-radius: 6px;
                ">
                <div style="flex: 1;">
                    <div style="font-size: 14px; color: #333;">${fileName}</div>
                    <div style="font-size: 12px; color: #666;">Image ready to send</div>
                </div>
                <button onclick="this.parentElement.remove(); document.querySelector('.upload-attachment').value='';" style="
                    background: #e74c3c;
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 24px;
                    height: 24px;
                    cursor: pointer;
                    font-size: 16px;
                    line-height: 1;
                ">×</button>
            `;
        } else {
            preview.innerHTML = `
                <svg style="width: 40px; height: 40px; color: #666;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div style="flex: 1;">
                    <div style="font-size: 14px; color: #333;">${fileName}</div>
                    <div style="font-size: 12px; color: #666;">File ready to send</div>
                </div>
                <button onclick="this.parentElement.remove(); document.querySelector('.upload-attachment').value='';" style="
                    background: #e74c3c;
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 24px;
                    height: 24px;
                    cursor: pointer;
                    font-size: 16px;
                    line-height: 1;
                ">×</button>
            `;
        }

        return preview;
    }

    /**
     * Insert preview into the form
     */
    function insertPreview(preview) {
        // Remove any existing preview
        const existingPreview = document.querySelector('.attachment-preview-container');
        if (existingPreview) {
            existingPreview.remove();
        }

        // Insert new preview above the message input
        const messageInputContainer = document.querySelector('.m-send').parentElement;
        messageInputContainer.parentElement.insertBefore(preview, messageInputContainer);
    }

    /**
     * Setup attachment preview functionality
     */
    function setupAttachmentPreview() {
        // Monitor for form submission to clear preview
        const form = document.querySelector('#message-form');
        if (form) {
            form.addEventListener('submit', function() {
                const preview = document.querySelector('.attachment-preview-container');
                if (preview) {
                    preview.remove();
                }
            });
        }
    }

    /**
     * Fix missing recipient ID in form
     */
    function fixRecipientId() {
        const form = document.querySelector('#message-form');
        if (!form) return;

        // Check if to_id input already exists
        let toIdInput = form.querySelector('input[name="to_id"]');
        
        if (!toIdInput) {
            // Create hidden input for recipient ID
            toIdInput = document.createElement('input');
            toIdInput.type = 'hidden';
            toIdInput.name = 'to_id';
            form.appendChild(toIdInput);
        }

        // Get recipient ID from the current conversation
        // Method 1: From URL if it contains user ID
        const urlMatch = window.location.pathname.match(/\/user\/(\d+)/);
        if (urlMatch) {
            toIdInput.value = urlMatch[1];
            console.log('Recipient ID set from URL:', urlMatch[1]);
            return;
        }

        // Method 2: From the active conversation in the sidebar
        const activeConversation = document.querySelector('.listOfContacts .active-user');
        if (activeConversation) {
            const userId = activeConversation.getAttribute('data-id');
            if (userId) {
                toIdInput.value = userId;
                console.log('Recipient ID set from active conversation:', userId);
                return;
            }
        }

        // Method 3: From messenger-user attribute
        const messengerUser = document.querySelector('.messenger-user');
        if (messengerUser) {
            const userId = messengerUser.getAttribute('data-id');
            if (userId) {
                toIdInput.value = userId;
                console.log('Recipient ID set from messenger user:', userId);
                return;
            }
        }

        // Method 4: From any element with user info in the header
        const headerUser = document.querySelector('.m-header-left [data-id]');
        if (headerUser) {
            const userId = headerUser.getAttribute('data-id');
            if (userId) {
                toIdInput.value = userId;
                console.log('Recipient ID set from header:', userId);
                return;
            }
        }

        console.warn('Could not determine recipient ID - will check again');
    }

    // Add some helpful CSS
    const styles = `
        <style>
        /* Make sure file input is properly hidden but functional */
        .upload-attachment {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
        }

        /* Ensure label is clickable */
        label[aria-label="Attach file"] {
            cursor: pointer !important;
            user-select: none;
        }

        label[aria-label="Attach file"]:hover {
            transform: scale(1.1);
        }

        /* Dark mode support for preview */
        .dark .attachment-preview-container {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        .dark .attachment-preview-container div {
            color: #e5e7eb !important;
        }

        /* Ensure form elements are enabled */
        .m-send:not([readonly]) {
            cursor: text !important;
        }

        .send-button:not([disabled]) {
            cursor: pointer !important;
            opacity: 1 !important;
        }
        </style>
    `;

    // Add styles to head
    document.head.insertAdjacentHTML('beforeend', styles);

    // Re-run enable features every second to catch any dynamic changes
    setInterval(function() {
        const fileInput = document.querySelector('.upload-attachment');
        const messageInput = document.querySelector('.m-send');
        const sendButton = document.querySelector('.send-button');
        
        if (fileInput && fileInput.disabled) {
            fileInput.disabled = false;
        }
        if (messageInput && messageInput.readOnly) {
            messageInput.readOnly = false;
        }
        if (sendButton && sendButton.disabled) {
            sendButton.disabled = false;
        }

        // Also check recipient ID
        fixRecipientId();
    }, 1000);

    // Listen for conversation changes
    document.addEventListener('click', function(e) {
        // Check if a conversation was clicked
        if (e.target.closest('.listOfContacts .messenger-list-item')) {
            setTimeout(fixRecipientId, 100);
        }
    });

})();
