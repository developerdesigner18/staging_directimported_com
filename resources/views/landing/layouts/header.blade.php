<header class="site-header">
    <!-- Top Black Bar -->
    <div class="topbar">
        <div class="container topbar__inner">
            <div class="topbar__date">
                <span id="tokyo-date"></span><span class="topbar__time" id="tokyo-time"></span>
            </div>

            <div class="topbar__right">
                <div class="social-icons social-icons--top">
                    <a href="https://wa.me/818033441177" target="_blank" aria-label="WhatsApp" class="social-icon social-icon--sm"><i class="fab fa-whatsapp"></i></a>
                    <a href="{{ getSetting()->facebook_url }}" target="_blank" aria-label="Facebook" class="social-icon social-icon--sm"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ getSetting()->instagram_url }}" target="_blank" aria-label="Instagram" class="social-icon social-icon--sm"><i class="fab fa-instagram"></i></a>
                    <a href="{{ getSetting()->youtube_url }}" target="_blank" aria-label="YouTube" class="social-icon social-icon--sm"><i class="fab fa-youtube"></i></a>
                </div>

                @if(Auth::guard('web')->check())
                    <a href="{{ route('profile.settings') }}" class="brand-nav-titlecase topbar__login"><i class="fas fa-user-circle me-1"></i> My Account</a>
                @else
                    <a href="{{ route('login') }}" class="brand-nav-titlecase topbar__login">Account Login</a>
                @endif

                <button aria-label="Menu" class="hamburger" id="menuToggle" aria-expanded="false" aria-controls="mobileMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Lower White Navigation Bar -->
    <div class="navbar">
        <div class="container navbar__inner">
            <div class="navbar__logo">
                <a href="{{ route('landing') }}">
                    <img src="{{ getSetting()->logo ? asset('assets/logo/' . getSetting()->logo) : asset('assets/logo/direct-imported-logo-red.png') }}" alt="Direct Imported Japan">
                </a>
            </div>

            <nav class="navbar__menu">
                <a href="{{ route('landing') }}" class="navbar__link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about.us') }}" class="navbar__link {{ request()->is('about-us') ? 'active' : '' }}">About Us</a>
                <a href="https://auc.directimported.com/" target="_blank" class="navbar__link">Auction Access</a>
                <a href="{{ route('available.vehicles') }}" class="navbar__link {{ request()->is('available-vehicles*') ? 'active' : '' }}">Available Vehicles</a>
                <a href="{{ route('services.view') }}" class="navbar__link {{ request()->is('services*') ? 'active' : '' }}">Services</a>
                <a href="{{ route('blog') }}" class="navbar__link {{ request()->is('blog*') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('contact') }}" class="navbar__link {{ request()->is('contact*') ? 'active' : '' }}">Contact</a>
            </nav>

            <div class="navbar__spacer"></div>
        </div>
    </div>

    <!-- Mobile / Tablet Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="container">
            <ul class="mobile-menu__list">
                <li><a href="{{ route('landing') }}" class="mobile-menu__link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about.us') }}" class="mobile-menu__link {{ request()->is('about-us') ? 'active' : '' }}">About Us</a></li>
                <li><a href="https://auc.directimported.com/" target="_blank" class="mobile-menu__link">Auction Access</a></li>
                <li><a href="{{ route('available.vehicles') }}" class="mobile-menu__link {{ request()->is('available-vehicles*') ? 'active' : '' }}">Available Vehicles</a></li>
                <li><a href="{{ route('services.view') }}" class="mobile-menu__link {{ request()->is('services*') ? 'active' : '' }}">Services</a></li>
                <li><a href="{{ route('blog') }}" class="mobile-menu__link {{ request()->is('blog*') ? 'active' : '' }}">Blog</a></li>
                <li><a href="{{ route('contact') }}" class="mobile-menu__link {{ request()->is('contact*') ? 'active' : '' }}">Contact</a></li>
                @if(Auth::guard('web')->check())
                    <li><a href="{{ route('profile.settings') }}" class="mobile-menu__link {{ request()->is('profile*') ? 'active' : '' }}">My Account</a></li>
                    <li><a href="{{ route('logout') }}" class="mobile-menu__link">Logout</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="mobile-menu__link {{ request()->is('login') ? 'active' : '' }}">Account Login</a></li>
                    <li><a href="{{ route('register') }}" class="mobile-menu__link {{ request()->is('register') ? 'active' : '' }}">Register</a></li>
                @endif
            </ul>
            <div class="mobile-menu__socials">
                <a href="https://wa.me/818033441177" target="_blank" aria-label="WhatsApp" class="social-icon social-icon--md"><i class="fab fa-whatsapp"></i></a>
                <a href="{{ getSetting()->facebook_url }}" target="_blank" aria-label="Facebook" class="social-icon social-icon--md"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ getSetting()->instagram_url }}" target="_blank" aria-label="Instagram" class="social-icon social-icon--md"><i class="fab fa-instagram"></i></a>
                <a href="{{ getSetting()->youtube_url }}" target="_blank" aria-label="YouTube" class="social-icon social-icon--md"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
</header>
@if(session()->has('admin_id'))
    <div style="background:#053C7C;color:white;text-align:center;padding:5px 0;font-size:13px;">
        Admin Impersonation Mode —
        <a href="{{ route('back.to.admin') }}" style="color:white;font-weight:600;text-decoration:underline;">
            Return to Admin Panel
        </a>
    </div>
@endif
