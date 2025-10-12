<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Web Đầu Tư - Landing Page')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('styles')
    <style>
        :root{
            --bs-primary:#16a34a; /* green */
            --bs-primary-rgb:22,163,74;
            --bs-link-color:#16a34a;
            --bs-link-hover-color:#15803d;
        }
        .btn-primary{
            --bs-btn-bg:var(--bs-primary);
            --bs-btn-border-color:var(--bs-primary);
            --bs-btn-hover-bg:#15803d;
            --bs-btn-hover-border-color:#15803d;
            --bs-btn-active-bg:#166534;
            --bs-btn-active-border-color:#166534;
        }
        .badge.text-bg-primary{background-color:rgba(var(--bs-primary-rgb),1)!important}
        /* Navbar inherits primary (green) */
        .navbar .nav-link{position:relative;color:#fff!important;opacity:.9;transition:opacity .2s ease,color .2s ease}
        .navbar .nav-link:hover,.navbar .nav-link:focus{opacity:1;color:#ffd54f!important}
        .navbar .nav-link::after{content:"";position:absolute;left:0;right:auto;bottom:-.25rem;height:2px;width:0;background:#fff;transition:width .25s ease}
        .navbar .nav-link:hover::after,.navbar .nav-link:focus::after{width:100%}
        .navbar .nav-link.active{opacity:1;color:#ffd54f!important}
        .navbar .nav-link.active::after{width:100%}
        /* Back to top */
        #backToTop{position:fixed;right:16px;bottom:16px;z-index:1050;opacity:0;visibility:hidden;transition:opacity .2s ease,visibility .2s ease}
        #backToTop.show{opacity:1;visibility:visible}
        
        /* Back to top button styling */
        #backToTop {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        /* Mobile responsive for back to top button */
        @media (max-width: 576px) {
            #backToTop {
                right: 12px;
                bottom: 12px;
                width: 50px;
                height: 50px;
                font-size: 1.1rem;
            }
        }
        /* Full-width banner with background image */
        .banner-cover{position:relative;color:#fff;background-position:center;background-size:cover;background-repeat:no-repeat;min-height:520px;display:flex;align-items:center}
        .banner-cover::before{content:"";position:absolute;inset:0;background:rgba(0,0,0,.35)}
        .banner-cover .content{position:relative}
        /* Common hovers */
        .card.hover-lift, .feature-card{transition:transform .25s ease, box-shadow .25s ease}
        .card.hover-lift:hover, .feature-card:hover{transform:none;box-shadow:0 .75rem 1.5rem rgba(0,0,0,.08)}
        /* Icon circle */
        .icon-circle{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:50%;background:rgba(var(--bs-primary-rgb),.1);color:var(--bs-primary)}
        /* Stepper timeline */
        .stepper{position:relative;margin-left:.5rem}
        .step{position:relative}
        .step .step-index{position:relative;z-index:1}
        /* Accordion polish */
        .accordion .accordion-item{border:none;border-radius:1rem;box-shadow:0 .5rem 1rem rgba(0,0,0,.06);overflow:hidden}
        .accordion-button{gap:.5rem}
        .accordion-button:not(.collapsed){color:#0f5132;background:rgba(var(--bs-primary-rgb),.08)}
        /* Project card lift */
        .card.h-100{transition:transform .25s ease, box-shadow .25s ease}
        .card.h-100:hover{transform:none;box-shadow:0 .75rem 1.5rem rgba(0,0,0,.08)}
        /* Soft section backgrounds */
        .bg-soft-primary{background:linear-gradient(180deg, rgba(var(--bs-primary-rgb),.06), rgba(var(--bs-primary-rgb),.03))}
        .bg-soft-gray{background:linear-gradient(180deg, rgba(0,0,0,.03), rgba(0,0,0,.015))}
        /* Vivid alternating tints for sections */
        .bg-tint-green{background:linear-gradient(180deg, rgba(22,163,74,.38), rgba(22,163,74,.22))}
        .bg-tint-teal{background:linear-gradient(180deg, rgba(13,148,136,.38), rgba(13,148,136,.22))}
        .bg-tint-amber{background:linear-gradient(180deg, rgba(245,158,11,.40), rgba(245,158,11,.22))}
        .bg-tint-sky{background:linear-gradient(180deg, rgba(2,132,199,.40), rgba(2,132,199,.22))}
        .bg-tint-slate{background:linear-gradient(180deg, rgba(71,85,105,.34), rgba(71,85,105,.18))}
        /* Global animated soft gradient background */
        body.global-animated-bg{
            min-height:100vh;
            background:
                radial-gradient(60% 80% at 0% 0%, rgba(22,163,74,.28) 0%, rgba(22,163,74,0) 50%),
                radial-gradient(60% 80% at 100% 0%, rgba(2,132,199,.26) 0%, rgba(2,132,199,0) 50%),
                radial-gradient(60% 80% at 0% 100%, rgba(13,148,136,.24) 0%, rgba(13,148,136,0) 50%),
                radial-gradient(60% 80% at 100% 100%, rgba(245,158,11,.22) 0%, rgba(245,158,11,0) 50%),
                linear-gradient(180deg, #f7fafc 0%, #eef2f7 100%);
            background-attachment: fixed;
            position: relative;
        }
        body.global-animated-bg::before{
            content:"";
            position:fixed;
            inset:-10% -10% -10% -10%;
            background: radial-gradient(1200px 1200px at var(--spot-x,20%) var(--spot-y,30%), rgba(22,163,74,.08), transparent 60%);
            pointer-events:none;
            animation: bgFloat 12s ease-in-out infinite alternate;
            filter: blur(0.5px);
        }
        @keyframes bgFloat{ 
            0%{ --spot-x:20%; --spot-y:30%; }
            50%{ --spot-x:80%; --spot-y:40%; }
            100%{ --spot-x:35%; --spot-y:75%; }
        }
        /* Corner-driven gradient background */
        .bg-corners-brand{
            background:
                radial-gradient(60% 80% at 0% 0%, rgba(22,163,74,.44) 0%, rgba(22,163,74,0) 50%),
                radial-gradient(60% 80% at 100% 0%, rgba(2,132,199,.40) 0%, rgba(2,132,199,0) 50%),
                radial-gradient(60% 80% at 0% 100%, rgba(13,148,136,.40) 0%, rgba(13,148,136,0) 50%),
                radial-gradient(60% 80% at 100% 100%, rgba(245,158,11,.40) 0%, rgba(245,158,11,0) 50%),
                linear-gradient(180deg, #f7f9fb 0%, #eef2f6 100%);
        }
        /* Feature card color variants */
        .feature-card.fc-green{background:linear-gradient(180deg, rgba(22,163,74,.12), rgba(22,163,74,.06))}
        .feature-card.fc-teal{background:linear-gradient(180deg, rgba(13,148,136,.12), rgba(13,148,136,.06))}
        .feature-card.fc-amber{background:linear-gradient(180deg, rgba(245,158,11,.14), rgba(245,158,11,.07))}
        .feature-card.fc-sky{background:linear-gradient(180deg, rgba(2,132,199,.14), rgba(2,132,199,.07))}
        .feature-card.fc-rose{background:linear-gradient(180deg, rgba(244,63,94,.14), rgba(244,63,94,.07))}
        .feature-card.fc-indigo{background:linear-gradient(180deg, rgba(79,70,229,.14), rgba(79,70,229,.07))}
        /* FAQ Q/A distinct colors */
        .faq-question{color:#0f5132}
        .faq-answer{color:#374151}
        .accordion-button.faq-question i{color:#0f5132!important}
        /* Typing effect */
        .typing{border-right:.1em solid #fff;white-space:nowrap;overflow:hidden}
        @keyframes caretBlink{50%{border-color:transparent}}
        .typing{animation:caretBlink .8s step-end infinite}
        
        /* Toast styles */
        .toast-container {
            z-index: 1055;
        }
        
        .toast {
            min-width: 300px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .toast.success {
            box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.25), 0 0.25rem 0.5rem rgba(25, 135, 84, 0.1);
        }
        
        .toast.error {
            box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15), 0 0.25rem 0.5rem rgba(220, 53, 69, 0.05);
        }
        
        .toast.warning {
            box-shadow: 0 0.5rem 1rem rgba(253, 126, 20, 0.25), 0 0.25rem 0.5rem rgba(253, 126, 20, 0.1);
        }
        
        .toast.info {
            box-shadow: 0 0.5rem 1rem rgba(13, 202, 240, 0.25), 0 0.25rem 0.5rem rgba(13, 202, 240, 0.1);
        }
        
        .toast-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
        }
        
        .toast-header.success {
            background-color: #198754;
            border-bottom-color: #198754;
            color: #fff;
        }
        
        .toast-header.error {
            background-color: #dc3545;
            border-bottom-color: #dc3545;
            color: #fff;
        }
        
        .toast-header.warning {
            background-color: #ffc107;
            border-bottom-color: #ffc107;
            color: #212529;
        }
        
        .toast-header.info {
            background-color: #0dcaf0;
            border-bottom-color: #0dcaf0;
            color: #fff;
        }
        
        .toast-header .toast-title { font-weight: 600; color: inherit; }
        .toast-header.success .btn-close,
        .toast-header.error .btn-close,
        .toast-header.info .btn-close { filter: invert(1) grayscale(100%); opacity: .9; }
        
        .toast-body {
            background-color: #fff;
            color: #212529;
        }
        
        /* Mobile responsive for toast */
        @media (max-width: 576px) {
            .toast-container {
                top: 1rem !important;
                right: 1rem !important;
                /* left: 1rem !important; */
            }
            
            .toast {
                min-width: auto;
                width: 100%;
            }
        }

        /* Dashboard Sidebar Menu Styles */
        .sidebar-menu {
            background: white;
            border-radius: 7px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 6px;
            margin-bottom: 2rem;
            border:1px solid #cdc4c4
        }
        .menu-section:last-child {
            margin-bottom: 0;
        }
        .content-sidebar{
            padding-right: 0
        }
        .menu-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-list li {
            margin-bottom: 0.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 6px;
            color: #6c757d;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: var(--bs-primary);
            transform: translateX(5px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.1), rgba(22, 163, 74, 0.05));
            color: var(--bs-primary);
            font-weight: 500;
        }

        .menu-item i {
            width: 16px;
            font-size: 1rem;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
            height: 100%;
        }

        .dashboard-card:hover {
            transform: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #e9ecef;
            border-radius: 12px 12px 0 0 !important;
            padding: 0.5rem 0.75rem;
        }

        .card-body {
            padding: 1.5rem;
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

        /* User Avatar */
        .user-avatar-large {
            text-align: center;
        }
        
        .avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bs-primary), #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 3rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
        }
        /* Navbar dropdown hover (desktop) */
        @media (min-width: 992px) {
            .navbar .dropdown:hover .dropdown-menu { display:block; }
            .navbar .dropdown-toggle::after { vertical-align: .15em; }
        }
    </style>
</head>
<body class="global-animated-bg" data-bs-spy="scroll" data-bs-target="#navbarNav" data-bs-offset="80" tabindex="0">
    <!-- Header -->
    @include('components.navbar')

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="toastContainer"></div>
    </div>

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
                        <i class="fas fa-check me-1"></i> Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="py-4 bg-body-tertiary border-top">
        <div class="container-fluid d-flex flex-column flex-lg-row align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge text-bg-primary rounded-pill">WD</span>
                <span class="fw-semibold">Web Đầu Tư</span>
            </div>
            <div class="text-muted">© <span id="year"></span> Web Đầu Tư. All rights reserved.</div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary rounded-circle p-3" aria-label="Back to top" title="Lên đầu trang">↑</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/password-toggle.js') }}"></script>
    <!-- Confirm helper is defined in public/js/toast.js -->
    @stack('scripts')
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
        
        // Back to top visibility + smooth scroll
        const btt = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 200) { btt.classList.add('show'); } else { btt.classList.remove('show'); }
        });
        btt.addEventListener('click', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        
        // WOW.js init
        if (typeof WOW === 'function') { new WOW().init(); }
        
        // Bootstrap ScrollSpy for active nav on scroll
        if (typeof bootstrap !== 'undefined' && bootstrap.ScrollSpy) {
            const navbar = document.querySelector('.navbar');
            const offset = navbar ? navbar.offsetHeight + 8 : 80;
            new bootstrap.ScrollSpy(document.body, { target: '#navbarNav', offset });
        }
        
        // Typing effect for top banner slogans
        (function(){
            const target = document.getElementById('typedSlogan');
            if (!target) return;
            const slogans = [
                'Đầu tư thông minh',
                'An toàn - Minh bạch',
                'Hiệu quả - Bền vững',
                'An tâm - Song hành',
                'Tương lai vững bền'
            ];
            let sloganIndex = 0;
            let charIndex = 0;
            let deleting = false;
            const type = () => {
                const text = slogans[sloganIndex];
                if (!deleting) {
                    target.textContent = text.substring(0, charIndex + 1);
                    charIndex++;
                    if (charIndex === text.length) {
                        deleting = true;
                        setTimeout(type, 1400);
                        return;
                    }
                } else {
                    target.textContent = text.substring(0, charIndex - 1);
                    charIndex--;
                    if (charIndex === 0) {
                        deleting = false;
                        sloganIndex = (sloganIndex + 1) % slogans.length;
                    }
                }
                const speed = deleting ? 35 : 55;
                setTimeout(type, speed);
            };
            type();
        })();
    </script>
</body>
</html>
