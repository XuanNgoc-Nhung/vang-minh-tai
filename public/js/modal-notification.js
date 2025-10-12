/**
 * Modal Notification System
 * Hệ thống thông báo modal có thể tái sử dụng
 */

// Modal Notification System
function showModalNotification(type, message, options = {}) {
    const modal = $('#notificationModal');
    const header = $('#notificationModalHeader');
    const icon = $('#notificationModalIcon');
    const title = $('#notificationModalTitle');
    const body = $('#notificationModalBody');
    const confirmBtn = $('#notificationModalConfirmBtn');
    
    // Reset classes
    header.removeClass('success error warning info');
    icon.removeClass('mdi-check-circle mdi-alert-circle mdi-alert mdi-information');
    
    // Set type-specific styling
    let iconClass, titleText, headerClass;
    switch(type) {
        case 'success':
            iconClass = 'mdi-check-circle';
            titleText = 'Thành công';
            headerClass = 'success';
            break;
        case 'error':
            iconClass = 'mdi-alert-circle';
            titleText = 'Lỗi';
            headerClass = 'error';
            break;
        case 'warning':
            iconClass = 'mdi-alert';
            titleText = 'Cảnh báo';
            headerClass = 'warning';
            break;
        case 'info':
        default:
            iconClass = 'mdi-information';
            titleText = 'Thông tin';
            headerClass = 'info';
            break;
    }
    
    // Apply styling
    header.addClass(headerClass);
    icon.addClass(iconClass);
    title.text(titleText);
    body.html(message);
    
    // Handle confirm button
    if (options.showConfirm && options.onConfirm) {
        confirmBtn.removeClass('d-none');
        confirmBtn.off('click').on('click', function() {
            options.onConfirm();
            modal.modal('hide');
        });
    } else {
        confirmBtn.addClass('d-none');
    }
    
    // Auto close after delay if specified
    if (options.autoClose !== false && options.delay) {
        setTimeout(() => {
            modal.modal('hide');
        }, options.delay || 5000);
    }
    
    // Show modal with animation
    modal.addClass('notification-animation');
    modal.modal('show');
    
    // Remove animation class after animation completes
    setTimeout(() => {
        modal.removeClass('notification-animation');
    }, 300);
}

// Convenience functions
function showSuccessModal(message, options = {}) {
    return showModalNotification('success', message, options);
}

function showErrorModal(message, options = {}) {
    return showModalNotification('error', message, options);
}

function showWarningModal(message, options = {}) {
    return showModalNotification('warning', message, options);
}

function showInfoModal(message, options = {}) {
    return showModalNotification('info', message, options);
}

// Export functions for global usage
if (typeof window !== 'undefined') {
    window.showModalNotification = showModalNotification;
    window.showSuccessModal = showSuccessModal;
    window.showErrorModal = showErrorModal;
    window.showWarningModal = showWarningModal;
    window.showInfoModal = showInfoModal;
}

// Export for module usage (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showModalNotification,
        showSuccessModal,
        showErrorModal,
        showWarningModal,
        showInfoModal
    };
}
