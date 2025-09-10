/**
 * Chatify Fixes - Delete Confirmation & Image Display
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', initFixes);

    function initFixes() {
        fixImageDisplay();
        setupDeleteConfirmation();
    }

    /**
     * Fix Image Display - Remove white overlays
     */
    function fixImageDisplay() {
        // Remove any loading overlays on existing images
        const images = document.querySelectorAll('.image-file, .chat-image');
        images.forEach(img => {
            // Remove any child divs that might be blocking the image
            const overlays = img.querySelectorAll('.image-loading, .lazy-image-placeholder, .absolute');
            overlays.forEach(overlay => {
                if (overlay.classList.contains('image-loading') || 
                    overlay.classList.contains('lazy-image-placeholder') ||
                    overlay.style.backgroundColor === 'white' ||
                    overlay.style.background.includes('white')) {
                    overlay.remove();
                }
            });
            
            // Ensure image is visible
            img.style.opacity = '1';
            img.style.visibility = 'visible';
            
            // If it has a data-image-url, apply it as background
            if (img.dataset.imageUrl) {
                img.style.backgroundImage = `url('${img.dataset.imageUrl}')`;
            }
        });

        // Monitor for new images and fix them
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        const newImages = node.querySelectorAll ? 
                            node.querySelectorAll('.image-file, .chat-image') : [];
                        newImages.forEach(img => {
                            // Remove loading overlays
                            setTimeout(() => {
                                const overlays = img.querySelectorAll('.image-loading, .lazy-image-placeholder');
                                overlays.forEach(overlay => overlay.remove());
                                img.style.opacity = '1';
                                img.style.visibility = 'visible';
                            }, 100);
                        });
                    }
                });
            });
        });

        // Start observing
        const messagesContainer = document.querySelector('.messages');
        if (messagesContainer) {
            observer.observe(messagesContainer, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Setup Delete Confirmation Modal
     */
    function setupDeleteConfirmation() {
        // Create modal HTML
        const modalHTML = `
            <div id="delete-confirmation-modal" class="chatify-modal" style="display: none;">
                <div class="chatify-modal-overlay"></div>
                <div class="chatify-modal-content">
                    <div class="chatify-modal-header">
                        <h3>Delete Message</h3>
                    </div>
                    <div class="chatify-modal-body">
                        <p>Are you sure you want to delete this message?</p>
                        <p class="warning-text">This action cannot be undone.</p>
                    </div>
                    <div class="chatify-modal-footer">
                        <button class="btn-cancel">Cancel</button>
                        <button class="btn-delete">Delete</button>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Get modal elements
        const modal = document.getElementById('delete-confirmation-modal');
        const cancelBtn = modal.querySelector('.btn-cancel');
        const deleteBtn = modal.querySelector('.btn-delete');
        const overlay = modal.querySelector('.chatify-modal-overlay');

        let messageToDelete = null;

        // Handle delete button clicks
        document.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-btn');
            if (deleteButton) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the message card
                messageToDelete = deleteButton.closest('.message-card');
                
                // Show modal
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        });

        // Cancel button
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        // Delete button
        deleteBtn.addEventListener('click', function() {
            if (messageToDelete) {
                // Add fade out animation
                messageToDelete.style.transition = 'all 0.3s ease';
                messageToDelete.style.opacity = '0';
                messageToDelete.style.transform = 'translateX(100px)';
                
                // Remove after animation
                setTimeout(() => {
                    messageToDelete.remove();
                }, 300);
                
                // If this was an actual delete, you would make an AJAX call here
                const messageId = messageToDelete.dataset.id;
                if (messageId) {
                    // You can add your delete API call here
                    console.log('Deleting message with ID:', messageId);
                }
            }
            closeModal();
        });

        // Close modal function
        function closeModal() {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                messageToDelete = null;
            }, 300);
        }

        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });
    }

    // Add modal styles
    const styles = `
        <style>
        /* Delete Confirmation Modal Styles */
        .chatify-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .chatify-modal.show {
            opacity: 1;
        }

        .chatify-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }

        .chatify-modal-content {
            position: relative;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .chatify-modal.show .chatify-modal-content {
            transform: scale(1);
        }

        .chatify-modal-header {
            padding: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .chatify-modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .chatify-modal-body {
            padding: 20px;
        }

        .chatify-modal-body p {
            margin: 0 0 10px 0;
            color: #555;
            font-size: 14px;
        }

        .chatify-modal-body .warning-text {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 10px;
        }

        .chatify-modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e5e5e5;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .chatify-modal-footer button {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chatify-modal-footer .btn-cancel {
            background: #e5e5e5;
            color: #333;
        }

        .chatify-modal-footer .btn-cancel:hover {
            background: #d5d5d5;
        }

        .chatify-modal-footer .btn-delete {
            background: #e74c3c;
            color: #fff;
        }

        .chatify-modal-footer .btn-delete:hover {
            background: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }

        /* Dark mode support */
        .dark .chatify-modal-content {
            background: #1f2937;
        }

        .dark .chatify-modal-header {
            border-bottom-color: #374151;
        }

        .dark .chatify-modal-header h3 {
            color: #e5e7eb;
        }

        .dark .chatify-modal-body p {
            color: #9ca3af;
        }

        .dark .chatify-modal-footer {
            border-top-color: #374151;
        }

        .dark .chatify-modal-footer .btn-cancel {
            background: #374151;
            color: #e5e7eb;
        }

        .dark .chatify-modal-footer .btn-cancel:hover {
            background: #4b5563;
        }

        /* Fix image display */
        .image-file {
            opacity: 1 !important;
            visibility: visible !important;
        }

        .image-file .image-loading,
        .image-file .lazy-image-placeholder,
        .image-file .absolute {
            display: none !important;
        }

        /* Ensure images are visible */
        .chat-image,
        .image-wrapper .image-file {
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }
        </style>
    `;

    // Add styles to head
    document.head.insertAdjacentHTML('beforeend', styles);

})();
