// Auto-attach show/hide toggle to all password inputs
(function () {
	function enhancePasswordInputs(root) {
		const inputs = Array.from((root || document).querySelectorAll('input[type="password"]'));
		inputs.forEach((input) => {
			if (input.dataset.toggleInitialized === 'true') return;
			if (input.hasAttribute('data-no-toggle')) return;

			// Create wrapper with positioning
			const wrapper = document.createElement('span');
			wrapper.className = 'position-relative d-block';

			// Insert wrapper before input and move input inside
			input.parentNode.insertBefore(wrapper, input);
			wrapper.appendChild(input);

			// Add toggle button
			const btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'btn btn-outline-secondary btn-sm password-toggle-btn';
			btn.setAttribute('aria-label', 'Hiển thị/Ẩn mật khẩu');
			btn.innerHTML = '<i class="bi bi-eye"></i>';
			btn.style.position = 'absolute';
			btn.style.right = '.5rem';
			btn.style.top = '50%';
			btn.style.transform = 'translateY(-50%)';
			btn.style.padding = '.125rem .35rem';

			wrapper.appendChild(btn);

			// Add padding so text doesn't go under the button
			const originalPaddingRight = getComputedStyle(input).paddingRight;
			input.style.paddingRight = '2.25rem';

			btn.addEventListener('click', function () {
				const isPassword = input.type === 'password';
				input.type = isPassword ? 'text' : 'password';
				btn.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
			});

			// Mark as initialized
			input.dataset.toggleInitialized = 'true';

			// Restore padding if the element is removed
			const observer = new MutationObserver(() => {
				if (!document.body.contains(input)) {
					observer.disconnect();
					try { input.style.paddingRight = originalPaddingRight; } catch (_) {}
				}
			});
			observer.observe(document.body, { childList: true, subtree: true });
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			enhancePasswordInputs(document);
		});
	} else {
		enhancePasswordInputs(document);
	}

	// If your pages inject content dynamically, expose a helper
	window.initPasswordToggle = enhancePasswordInputs;
})();


