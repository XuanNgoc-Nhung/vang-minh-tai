/**
 * Global Confirm Helper (Bootstrap-based)
 * Reusable confirmation modal logic for all admin screens.
 */

(function(){
    // Preserve native confirm for fallback usage
    const __nativeConfirm = typeof window !== 'undefined' && typeof window.confirm === 'function'
        ? window.confirm.bind(window)
        : null;

    /**
     * Show shared confirm modal.
     * @param {Object} opts
     * @param {string} opts.title - Modal title
     * @param {string} opts.message - Confirmation message
     * @param {string} [opts.confirmText='Xác nhận'] - Confirm button text
     * @param {Function} [opts.onConfirm] - Callback executed when user confirms
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
            if (titleEl) titleEl.textContent = title;
            if (bodyEl) bodyEl.textContent = message;
            if (confirmBtn) confirmBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> ' + confirmText;

            // Reset previous listeners by cloning the node
            const newBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            newBtn.addEventListener('click', function() {
                if (onConfirm) onConfirm();
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();
            });

            // Ensure confirm modal overlays any existing modal
            modalEl.style.zIndex = '1060';
            modalEl.addEventListener('shown.bs.modal', function onShown(){
                modalEl.removeEventListener('shown.bs.modal', onShown);
                var backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length) {
                    var topBackdrop = backdrops[backdrops.length - 1];
                    topBackdrop.style.zIndex = '1055';
                }
            });
            modalEl.addEventListener('hidden.bs.modal', function onHidden(){
                modalEl.removeEventListener('hidden.bs.modal', onHidden);
                modalEl.style.zIndex = '';
            });
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            // Fallback native confirm
            if (__nativeConfirm) {
                if (__nativeConfirm(title + '\n' + message)) {
                    if (onConfirm) onConfirm();
                }
            } else {
                // Last resort: auto-confirm to avoid blocking flows in non-browser environments
                if (onConfirm) onConfirm();
            }
        }
    }

    /**
     * Enhanced confirm API. Accepts either an options object or a string for native confirm.
     * Examples:
     *   confirm({ title: 'Xác nhận', message: 'Bạn chắc chắn?', onConfirm: fn })
     *   confirm('Bạn chắc chắn?') // native confirm fallback
     */
    function enhancedConfirm(opts) {
        if (opts && typeof opts === 'object') {
            return showConfirm(opts);
        }
        if (__nativeConfirm) {
            return __nativeConfirm(String(opts || ''));
        }
        return true;
    }

    // Expose to global
    if (typeof window !== 'undefined') {
        window.showConfirm = showConfirm;
        window.confirm = enhancedConfirm; // override to support object usage
    }

    // CommonJS export (optional)
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { showConfirm, confirm: enhancedConfirm };
    }
})();


