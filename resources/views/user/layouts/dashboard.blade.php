@extends('user.layouts.app')

@section('title', 'Dashboard - Web Đầu Tư')

@section('content')
<!-- Mobile Sidebar Toggle Button -->
<button class="mobile-sidebar-toggle d-md-none">
    <i class="bi bi-list"></i>
</button>

<!-- Content Sidebar Overlay for Mobile -->
<div class="content-sidebar-overlay d-md-none" id="contentSidebarOverlay" onclick="closeContentSidebar()"></div>

<!-- Dashboard Container with Bootstrap Grid -->
<div class="container-fluid py-4 pt-3">
    <div class="row">
        <!-- Sidebar Menu - col-3 -->
        <div class="col-12 col-md-3 content-sidebar" id="contentSidebar">
            @include('user.layouts.menu-dashboard')
        </div>

        <!-- Main Content - col-9 -->
        <div class="col-12 col-md-9 main-content-area">
            @yield('content-dashboard')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Dashboard specific styles */
    .user-avatar-large {
        text-align: center;
    }
    .main-content-area{
        padding-right: 0;
        padding-left: 0;
    }
    .avatar-circle {
        width: 150px;
        height: 150px;
        border-radius: 15px;
        background: linear-gradient(135deg, var(--bs-primary), #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 3.5rem;
        color: white;
        font-weight: bold;
        box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #e9ecef;
        border-radius: 12px 12px 0 0 !important;
        padding: 0.5rem 0.75rem;
    }

    .card-body {
        padding: 6px;
    }

    /* Stats Cards */
    .stat-card {
        background: linear-gradient(135deg, #fff, #f8f9fa);
        border-left: 4px solid var(--bs-primary);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--bs-primary);
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Compact Cards */
    .compact-stat .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--bs-primary);
    }

    .compact-stat .stat-label {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .compact-card .card-header {
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .compact-card .card-body {
        padding: 0.75rem 1rem;
    }

    /* Responsive adjustments for compact cards */
    @media (max-width: 575.98px) {
        .compact-stat .stat-number {
            font-size: 1.25rem;
        }
        
        .compact-stat .stat-label {
            font-size: 0.75rem;
        }
    }

    /* Welcome Section Styles */
    .user-avatar-large .avatar-circle {
        width: 120px;
        height: 160px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--bs-primary), #6f42c1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    @media (max-width: 767.98px) {
        .user-avatar-large .avatar-circle {
            width: 120px;
            height: 160px;
            max-width: 100%;
            font-size: 2rem;
        }
    }

    /* Mobile Sidebar Toggle Button */
    .mobile-sidebar-toggle {
        position: fixed;
        top: calc(56px + 12px); /* navbar height + 4px */
        left: 1rem;
        z-index: 1004;
        background: var(--bs-primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        cursor: grab;
        user-select: none;
        touch-action: none;
    }

    .mobile-sidebar-toggle:hover {
        background: #15803d;
        transform: scale(1.05);
    }

    .mobile-sidebar-toggle:active {
        cursor: grabbing;
        transform: scale(1.1);
    }

    .mobile-sidebar-toggle.dragging {
        cursor: grabbing;
        opacity: 0.8;
        transform: scale(1.1);
        z-index: 1005;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    /* Drag indicator */
    .mobile-sidebar-toggle::after {
        content: '⋮⋮';
        position: absolute;
        top: 50%;
        right: 4px;
        transform: translateY(-50%);
        font-size: 8px;
        opacity: 0.7;
        line-height: 1;
    }

    /* Content Sidebar Styles */
    .content-sidebar {
        position: relative;
        transition: all 0.3s ease;
    }

    /* Subtle card-like container feel on new background */
    .main-content-area > *:first-child .card,
    .main-content-area > .card {
        backdrop-filter: saturate(120%) blur(2px);
        background-color: rgba(255,255,255,.9);
    }

    /* Content Sidebar Overlay */
    .content-sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1001;
        display: none;
    }

    .content-sidebar-overlay.show {
        display: block;
    }

    /* Mobile Responsive for Content Sidebar */
    @media (max-width: 767.98px) {
        .content-sidebar {
            position: fixed;
            top: calc(56px + 12px); /* navbar height + 4px */
            left: -100%;
            width: 280px;
            height: calc(100vh - 56px - 4px); /* full height minus navbar and gap */
            background: white;
            z-index: 1002;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
        }

        .content-sidebar.show {
            left: 0;
        }

        .main-content-area {
            width: 100%;
        }

        /* Adjust mobile toggle button position */
        .mobile-sidebar-toggle {
            top: calc(56px + 12px); /* navbar height + 4px */
            left: 1rem;
        }
    }

    @media (min-width: 768px) {
        .mobile-sidebar-toggle {
            display: none !important;
        }

        .content-sidebar-overlay {
            display: none !important;
        }
    }

</style>
@endpush

@push('scripts')
<script>
    // Content Sidebar Toggle Functions
    function toggleContentSidebar() {
        const sidebar = document.getElementById('contentSidebar');
        const overlay = document.getElementById('contentSidebarOverlay');

        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    function closeContentSidebar() {
        const sidebar = document.getElementById('contentSidebar');
        const overlay = document.getElementById('contentSidebarOverlay');

        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }

    // Close content sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('contentSidebar');
        const toggleBtn = document.querySelector('.mobile-sidebar-toggle');

        if (window.innerWidth <= 767.98) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                closeContentSidebar();
            }
        }
    });

    // Handle window resize for content sidebar
    window.addEventListener('resize', function () {
        if (window.innerWidth > 767.98) {
            closeContentSidebar();
        }
    });

    // Close content sidebar when clicking on menu items on mobile
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function () {
                if (window.innerWidth <= 767.98) {
                    closeContentSidebar();
                }
            });
        });
    });

    // Drag and Drop functionality for mobile sidebar toggle button
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.mobile-sidebar-toggle');
        let isDragging = false;
        let hasMoved = false;
        let startX, startY, startLeft, startTop;

        // Load saved position from localStorage
        function loadButtonPosition() {
            const savedPosition = localStorage.getItem('mobileSidebarTogglePosition');
            if (savedPosition) {
                const position = JSON.parse(savedPosition);
                toggleButton.style.left = position.left + 'px';
                toggleButton.style.top = position.top + 'px';
            }
        }

        // Save position to localStorage
        function saveButtonPosition() {
            const position = {
                left: parseInt(toggleButton.style.left) || 16, // default 1rem = 16px
                top: parseInt(toggleButton.style.top) || 80 // default position
            };
            localStorage.setItem('mobileSidebarTogglePosition', JSON.stringify(position));
        }

        // Initialize position
        loadButtonPosition();

        // Mouse events for dragging
        toggleButton.addEventListener('mousedown', function(e) {
            if (window.innerWidth > 767.98) return; // Only on mobile
            
            isDragging = true;
            hasMoved = false;
            toggleButton.classList.add('dragging');
            
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt(toggleButton.style.left) || 16;
            startTop = parseInt(toggleButton.style.top) || 80;
            
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            // Check if mouse has moved significantly (more than 5px)
            if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) {
                hasMoved = true;
            }
            
            let newLeft = startLeft + deltaX;
            let newTop = startTop + deltaY;
            
            // Constrain to viewport bounds
            const buttonRect = toggleButton.getBoundingClientRect();
            const maxLeft = window.innerWidth - buttonRect.width;
            const maxTop = window.innerHeight - buttonRect.height;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(56, Math.min(newTop, maxTop)); // Min top = navbar height
            
            toggleButton.style.left = newLeft + 'px';
            toggleButton.style.top = newTop + 'px';
        });

        document.addEventListener('mouseup', function(e) {
            if (!isDragging) return;
            
            isDragging = false;
            toggleButton.classList.remove('dragging');
            
            // Only save position if button was actually moved
            if (hasMoved) {
                saveButtonPosition();
            }
        });

        // Touch events for dragging
        toggleButton.addEventListener('touchstart', function(e) {
            if (window.innerWidth > 767.98) return;
            
            isDragging = true;
            hasMoved = false;
            toggleButton.classList.add('dragging');
            
            const touch = e.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
            startLeft = parseInt(toggleButton.style.left) || 16;
            startTop = parseInt(toggleButton.style.top) || 80;
            
            // Don't prevent default on touchstart to allow click events
        }, { passive: true });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            
            const touch = e.touches[0];
            const deltaX = touch.clientX - startX;
            const deltaY = touch.clientY - startY;
            
            // Check if touch has moved significantly (more than 10px for touch)
            if (Math.abs(deltaX) > 10 || Math.abs(deltaY) > 10) {
                hasMoved = true;
            }
            
            let newLeft = startLeft + deltaX;
            let newTop = startTop + deltaY;
            
            // Constrain to viewport bounds
            const buttonRect = toggleButton.getBoundingClientRect();
            const maxLeft = window.innerWidth - buttonRect.width;
            const maxTop = window.innerHeight - buttonRect.height;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(56, Math.min(newTop, maxTop));
            
            toggleButton.style.left = newLeft + 'px';
            toggleButton.style.top = newTop + 'px';
            
            e.preventDefault();
        }, { passive: false });

        // Reset position on double tap
        let lastTap = 0;
        
        document.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            
            isDragging = false;
            toggleButton.classList.remove('dragging');
            
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            
            // Check for double tap first
            if (tapLength < 500 && tapLength > 0 && !hasMoved) {
                // Double tap - reset to default position
                toggleButton.style.left = '1rem';
                toggleButton.style.top = 'calc(56px + 12px)';
                saveButtonPosition();
                e.preventDefault(); // Prevent click event
            } else if (hasMoved) {
                // Button was moved - save position
                saveButtonPosition();
                e.preventDefault(); // Prevent click event
            }
            // For single tap without movement, let click event handle it
            
            lastTap = currentTime;
        });

        // Fallback click event for desktop and mobile
        toggleButton.addEventListener('click', function(e) {
            // Only toggle sidebar if button wasn't dragged
            if (!hasMoved) {
                toggleContentSidebar();
            }
        });
    });

</script>
@endpush
