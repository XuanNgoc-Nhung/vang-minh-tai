<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin CMS')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --cms-green: #16a34a; /* emerald-600 */
      --cms-green-dark: #15803d; /* emerald-700 */
      --cms-green-verydark: #166534; /* emerald-800 */
      --sidebar-width: 240px;
      --sidebar-collapsed-width: 64px;
    }

    body {
      background-color: #f8fafc;
    }

    .cms-sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: var(--sidebar-width);
      background: linear-gradient(180deg, var(--cms-green), var(--cms-green-dark));
      color: #fff;
      padding-top: 56px; /* height of topbar */
      overflow-y: auto;
      transition: width 200ms ease;
    }

    .cms-brand {
      position: fixed;
      top: 0;
      left: 0;
      height: 56px;
      width: var(--sidebar-width);
      background-color: var(--cms-green-verydark);
      display: flex;
      align-items: center;
      gap: .625rem;
      padding: 0 .875rem;
      z-index: 1031;
      box-shadow: 0 1px 0 rgba(0,0,0,.06);
      transition: width 200ms ease, background-color 200ms ease, box-shadow 200ms ease;
    }

    .cms-brand .logo {
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.2);
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: grid;
      place-items: center;
      margin-right: 0;
      color: #fff;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.12), 0 1px 2px rgba(0,0,0,0.08);
    }

    .cms-brand strong {
      color: #fff;
      font-weight: 600;
      font-size: .9375rem; /* 15px */
      line-height: 1;
      letter-spacing: .2px;
      white-space: nowrap;
    }

    .cms-nav .nav-link {
      color: rgba(255,255,255,0.9);
      padding: .625rem 1rem;
      border-left: 3px solid transparent;
      border-radius: 0;
    }
    .cms-nav .nav-link i { width: 1.25rem; }
    .cms-nav .nav-link:hover {
      background-color: rgba(255,255,255,0.12);
      border-left-color: rgba(255,255,255,0.65);
      color: #fff;
    }
    .cms-nav .nav-link.active {
      background-color: rgba(255,255,255,0.18);
      border-left-color: #fff;
      color: #fff;
      font-weight: 600;
    }

    .cms-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: margin-left 200ms ease;
    }

    .cms-topbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background-color: var(--cms-green);
      color: #ffffff;
      border-bottom: 1px solid rgba(0,0,0,.08);
      height: 56px;
      display: flex;
      align-items: center;
    }

    .cms-topbar .text-muted { color: rgba(255,255,255,0.85) !important; }
    .cms-topbar .btn.btn-outline-success { color: #ffffff; border-color: rgba(255,255,255,0.6); }
    .cms-topbar .btn.btn-outline-success:hover { background-color: rgba(255,255,255,0.12); border-color: #ffffff; }
    .cms-topbar .breadcrumb { margin: 0; display: flex; align-items: center; }
    .cms-topbar .breadcrumb, .cms-topbar .breadcrumb * { color: #ffffff !important; }

    /* Dim all modal backdrops a bit */
    .modal-backdrop { --bs-backdrop-opacity: .35; }

    /* Collapsed sidebar (desktop) */
    .cms-collapsed .cms-sidebar { width: var(--sidebar-collapsed-width); }
    .cms-collapsed .cms-brand { width: var(--sidebar-collapsed-width); }
    .cms-collapsed .cms-brand strong { display: none; }
    .cms-collapsed .cms-content { margin-left: var(--sidebar-collapsed-width); }
    .cms-collapsed .cms-nav .nav-link { padding-left: .75rem; padding-right: .75rem; text-align: center; }
    .cms-collapsed .cms-nav .nav-link .label { display: none; }
    .cms-collapsed .cms-nav .logout-btn { justify-content: center; }

    /* Toggle button */
    .cms-toggle-btn {
      border: 0;
      background: transparent;
      color: #fff;
      width: 36px;
      height: 36px;
      display: grid;
      place-items: center;
      border-radius: .375rem;
    }
    .cms-toggle-btn:hover { background: rgba(255,255,255,0.12); }

    /* Toast header colors by type */
    .toast-header.success { background-color: #198754; color: #fff; }
    .toast-header.error { background-color: #dc3545; color: #fff; }
    .toast-header.warning { background-color: #ffc107; color: #212529; }
    .toast-header.info { background-color: #0dcaf0; color: #fff; }
    .toast-header.success .btn-close,
    .toast-header.error .btn-close,
    .toast-header.info .btn-close { filter: invert(1) grayscale(100%); opacity: .9; }
    .toast-header .toast-title { color: inherit; }

    @media (max-width: 991.98px) {
      .cms-sidebar, .cms-brand { width: 100%; position: fixed; }
      .cms-sidebar { display: none; z-index: 1040; width: 80%; max-width: 320px; box-shadow: 0 8px 24px rgba(0,0,0,.25); }
      .cms-mobile-open .cms-sidebar { display: block; }
      /* On mobile, ignore collapsed layout: always use full content width and show labels */
      .cms-collapsed .cms-brand { width: 100% !important; }
      .cms-collapsed .cms-content { margin-left: 0 !important; }
      .cms-collapsed .cms-sidebar { width: 80% !important; }
      .cms-collapsed .cms-nav .nav-link { text-align: left !important; padding-left: 1rem !important; padding-right: 1rem !important; }
      .cms-collapsed .cms-nav .nav-link .label { display: inline !important; }
      .cms-mobile-open .cms-content::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.35);
        z-index: 1039;
      }
      .cms-content { margin-left: 0; padding-top: 56px; }
    }
  </style>
  @stack('head')
  @yield('head')
  <!-- place for per-page head additions -->
  @stack('styles')
  @yield('styles')
</head>
<body>
  <div class="cms-brand">
    <div class="logo"><i class="bi bi-speedometer2"></i></div>
    <strong>Admin CMS</strong>
    <div class="ms-auto d-lg-none">
      <button id="cmsSidebarToggleMobile" type="button" class="cms-toggle-btn" aria-label="Mở/đóng menu">
        <i class="bi bi-list"></i>
      </button>
    </div>
  </div>

  @include('admin.layout.menu')

  <main class="cms-content">
    <div class="cms-topbar px-3 d-none d-lg-flex justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <button id="cmsSidebarToggle" type="button" class="btn btn-sm btn-outline-success" aria-label="Thu gọn/mở rộng sidebar">
          <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <div class="breadcrumb">
          <div class="text-muted small">@yield('breadcrumb')</div>
        </div>
      </div>
      <div>
        <span class="badge text-bg-success">Online</span>
      </div>
    </div>

    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  <!-- Global Confirm Modal -->
  <div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-labelledby="globalConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="globalConfirmModalLabel">Xác nhận</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="globalConfirmModalBody">
          Bạn có chắc chắn muốn thực hiện hành động này?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="globalConfirmModalBtn">
            <i class="bi bi-check-circle me-1"></i> Xác nhận
          </button>
        </div>
      </div>
    </div>
  </div>

  @yield('modals')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js"></script>
  <script src="/js/toast.js"></script>
  <script src="/js/confirm.js"></script>
  <script>
    // Make axios globally available with sensible defaults
    window.axios = axios;
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    const csrf = document.querySelector('meta[name="csrf-token"]');
    if (csrf) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf.getAttribute('content');
    }
  </script>
  <script>
    (function() {
      const bodyEl = document.body;
      const desktopBtn = document.getElementById('cmsSidebarToggle');
      const mobileBtn = document.getElementById('cmsSidebarToggleMobile');

      // Restore desktop collapsed state
      const stored = localStorage.getItem('cms.sidebar');
      if (stored === 'collapsed') {
        bodyEl.classList.add('cms-collapsed');
      }

      function toggleDesktop() {
        bodyEl.classList.toggle('cms-collapsed');
        localStorage.setItem('cms.sidebar', bodyEl.classList.contains('cms-collapsed') ? 'collapsed' : 'expanded');
      }

      function toggleMobile() {
        bodyEl.classList.toggle('cms-mobile-open');
      }

      if (desktopBtn) desktopBtn.addEventListener('click', toggleDesktop);
      if (mobileBtn) mobileBtn.addEventListener('click', toggleMobile);

      // Close mobile sidebar when clicking the overlay
      document.addEventListener('click', function(e) {
        if (!bodyEl.classList.contains('cms-mobile-open')) return;
        const sidebar = document.querySelector('.cms-sidebar');
        const brand = document.querySelector('.cms-brand');
        const isInside = sidebar.contains(e.target) || brand.contains(e.target);
        if (!isInside) bodyEl.classList.remove('cms-mobile-open');
      });

      // Initialize Bootstrap tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // Toast queue: show toast saved before reload
      try {
        const queued = localStorage.getItem('cms.toast');
        if (queued) {
          const { type, message, options } = JSON.parse(queued);
          if (typeof showToast === 'function') {
            showToast(type || 'info', message || '', options || {});
          }
          localStorage.removeItem('cms.toast');
        }
      } catch (e) {
        // ignore
      }

      // Expose helper to queue toast for after reload
      window.queueToast = function(type, message, options) {
        try {
          localStorage.setItem('cms.toast', JSON.stringify({ type, message, options: options || {} }));
        } catch (e) {
          // ignore
        }
      };
    })();
  </script>
  @stack('scripts')
  @yield('scripts')
</body>
</html>


