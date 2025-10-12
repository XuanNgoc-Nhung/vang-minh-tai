/**
 * Toast Notification System
 * Hệ thống thông báo toast có thể tái sử dụng
 */

// Toast configuration
const ToastConfig = {
    autohide: true,
    delay: 5000,
    position: 'top-end'
};

/**
 * Hiển thị toast notification
 * @param {string} type - Loại toast: 'success', 'error', 'warning', 'info'
 * @param {string} message - Nội dung thông báo
 * @param {Object} options - Tùy chọn bổ sung
 */
function showToast(type, message, options = {}) {
    // Merge options with default config
    const config = { ...ToastConfig, ...options };
    
    // Create unique toast ID
    const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    
    // Get title and icon based on type
    let title, iconClass;
    switch(type) {
        case 'success':
            title = 'Thành công';
            iconClass = 'bi-check-circle-fill text-success';
            break;
        case 'error':
            title = 'Lỗi';
            iconClass = 'bi-exclamation-triangle-fill text-white';
            break;
        case 'warning':
            title = 'Cảnh báo';
            iconClass = 'bi-exclamation-triangle-fill text-white';
            break;
        case 'info':
            title = 'Thông tin';
            iconClass = 'bi-info-circle-fill text-white';
            break;
        default:
            title = 'Thông báo';
            iconClass = 'bi-info-circle-fill text-white';
    }
    
    // Create toast HTML
    const toastHtml = `
        <div id="${toastId}" class="toast ${type}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${type}">
                <i class="bi ${iconClass} me-2"></i>
                <strong class="me-auto toast-title ${type}">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    // Get or create toast container
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        // Create toast container if it doesn't exist
        const containerHtml = `
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="toastContainer"></div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', containerHtml);
        toastContainer = document.getElementById('toastContainer');
    }
    
    // Add toast to container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: config.autohide,
        delay: config.delay
    });
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
    
    return toast;
}

/**
 * Hiển thị toast thành công
 * @param {string} message - Nội dung thông báo
 * @param {Object} options - Tùy chọn bổ sung
 */
function showSuccessToast(message, options = {}) {
    return showToast('success', message, options);
}

/**
 * Hiển thị toast lỗi
 * @param {string} message - Nội dung thông báo
 * @param {Object} options - Tùy chọn bổ sung
 */
function showErrorToast(message, options = {}) {
    return showToast('error', message, options);
}

/**
 * Hiển thị toast cảnh báo
 * @param {string} message - Nội dung thông báo
 * @param {Object} options - Tùy chọn bổ sung
 */
function showWarningToast(message, options = {}) {
    return showToast('warning', message, options);
}

/**
 * Hiển thị toast thông tin
 * @param {string} message - Nội dung thông báo
 * @param {Object} options - Tùy chọn bổ sung
 */
function showInfoToast(message, options = {}) {
    return showToast('info', message, options);
}

/**
 * Xóa tất cả toast đang hiển thị
 */
function clearAllToasts() {
    const toastContainer = document.getElementById('toastContainer');
    if (toastContainer) {
        toastContainer.innerHTML = '';
    }
}

/**
 * Hiển thị modal xác nhận dùng chung (dựa trên modal global trong layout)
 * @param {Object} opts
 * @param {string} opts.title - Tiêu đề modal
 * @param {string} opts.message - Nội dung xác nhận
 * @param {string} [opts.confirmText='Xác nhận'] - Text nút xác nhận
 * @param {Function} [opts.onConfirm] - Hàm gọi khi người dùng xác nhận
 */
function showConfirm(opts) {
    const options = opts || {};
    const title = options.title || 'Xác nhận';
    const message = options.message || 'Bạn có chắc chắn muốn thực hiện hành động này?';
    const confirmText = options.confirmText || 'Xác nhận';
    const onConfirm = typeof options.onConfirm === 'function' ? options.onConfirm : null;

    const modalEl = document.getElementById('globalConfirmModal');
    const titleEl = document.getElementById('globalConfirmModalLabel');
    const bodyEl = document.getElementById('globalConfirmModalBody');
    const confirmBtn = document.getElementById('globalConfirmModalBtn');

    if (modalEl && window.bootstrap) {
        // Set content
        if (titleEl) titleEl.textContent = title;
        if (bodyEl) bodyEl.textContent = message;
        if (confirmBtn) confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> ' + confirmText;

        // Reset previous listeners by cloning the node
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        newBtn.addEventListener('click', function() {
            if (onConfirm) onConfirm();
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();
        });

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        // Fallback native confirm
        if (window.confirm(title + '\n' + message)) {
            if (onConfirm) onConfirm();
        }
    }
}

// Lưu lại confirm gốc của trình duyệt (nếu tồn tại) để dùng fallback
const __nativeConfirm = typeof window !== 'undefined' && typeof window.confirm === 'function'
    ? window.confirm.bind(window)
    : null;

/**
 * API confirm mới: có thể gọi với object để mở modal đẹp,
 * hoặc truyền string để dùng confirm gốc.
 * Ví dụ:
 * confirm({ title: 'Xác nhận', message: 'Bạn chắc chắn?', onConfirm: fn })
 * confirm('Bạn chắc chắn?') // fallback confirm gốc
 */
function customConfirm(opts) {
    // Object -> sử dụng modal confirm tuỳ biến
    if (opts && typeof opts === 'object') {
        return showConfirm(opts);
    }
    // String/primitive -> dùng confirm gốc
    if (__nativeConfirm) {
        return __nativeConfirm(String(opts || ''));
    }
    // Fallback cuối cùng
    return true;
}

// Gán ra global để dùng trực tiếp trong view
if (typeof window !== 'undefined') {
    window.showConfirm = showConfirm;
    window.confirm = customConfirm; // ghi đè confirm để hỗ trợ gọi với object
}

// Export functions for module usage (if needed)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showToast,
        showSuccessToast,
        showErrorToast,
        showWarningToast,
        showInfoToast,
        clearAllToasts,
        showConfirm,
        confirm: customConfirm
    };
}
