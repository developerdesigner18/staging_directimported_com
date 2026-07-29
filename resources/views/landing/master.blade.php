<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="thecodude">
    <meta name="description" content="">

    <!-- responsive tag -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | {{env('APP_NAME')}}</title>

    @include('landing.layouts.header-links')
    @stack('style-src')
    @yield('style')
    @stack('style')
    <style>
        :root {
            /* Color */
            --body-color: #F5F5F5;
            /* Light BG */
            --title-color: #1A1A1A;
            /* Tarmac Black */
            --primary-color: #050B20;
            /* Consistent Dark Blue/Black */
            --primary-hover-color: #de2b43;
            /* Red Hover as requested */
            --primary-light-color: #E0E0E0;
            /* Border Light */
            --secondary-color: #4A4A4A;
            /* Brake Dust Grey */
            --white-color: #FFFFFF;
            /* Pure White */
            --dark-color: #121212;
            /* Dark BG */

            /* Theme Gradients */
            --header-footer-gradient: linear-gradient(180deg, #0E488C 0%, #000000 100%);
            --primary-gradient: linear-gradient(180deg, #053C7C 0%, #042B59 100%);

            /* Typography */
            --body-font: 'Poppins', sans-serif;
            --title-font: 'Poppins', sans-serif;
            --font-weight-normal: 400;
            --font-weight-medium: 500;
            --font-weight-semi-bold: 600;
            --font-weight-bold: 700;

            /* Others */
            --transition: 0.3s;
            --footer-text-color: #8c8d8f;
        }

        /*#loader-overlay {*/
        /*    display: none; !* hidden by default *!*/
        /*    position: fixed;*/
        /*    top: 0;*/
        /*    left: 0;*/
        /*    width: 100vw;*/
        /*    height: 100vh;*/
        /*    background: rgba(0,0,0,0.4); !* semi-transparent overlay *!*/
        /*    z-index: 9999;*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*    align-items: center;*/
        /*}*/
        .rid-social-links .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 24px;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            padding: 0px 0px 5px 0px;
        }

        .rid-social-links .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Facebook */
        .rid-social-links .social-icon.facebook {
            background-color: #3b5998;
        }

        /* Instagram */
        .rid-social-links .social-icon.instagram {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        /* YouTube */
        .rid-social-links .social-icon.youtube {
            background-color: #ff0000;
        }

        /* Optional: Add margin between icons */
        .rid-social-links .list-inline-item:not(:last-child) {
            margin-right: 15px;
        }

        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 35px !important;
            white-space: nowrap !important;
        }

        .rid-menubar ul li:last-child a {
            margin-right: 0 !important;
        }

        .logo-container,
        .col-lg-3.col-md-8.col-sm-8.col-6.logo-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .logo {
            padding: 0 !important;
        }

        .rid-menubar .dropdown-menu li a {
            font-size: 16px !important;
            color: #050B20 !important;
            font-weight: 500 !important;
        }

        .loader-container {
            display: flex;
            /* visible by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /*     .progress-container { */
        /*         display: none; */
        /*     } */
        .sticky_header {
            position: fixed;
            width: 100%;
            z-index: 9999;
            top: 0;
            background-color: white;
            box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.2);
        }

        /* Header Responsiveness Fixes for Small to Medium Screens */
        @media (max-width: 991px) {
            .rid-header-top {
                display: none !important;
            }

            .rid-header-style-1 .rid-header-bottom [class*="col-"] {
                width: auto !important;
                flex: 1 !important;
            }

            .rid-header-bottom .col-lg-2 {
                max-width: 60% !important;
                flex: 0 0 60% !important;
                text-align: left !important;
            }

            .rid-header-bottom .col-lg-10 {
                max-width: 40% !important;
                flex: 0 0 40% !important;
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
            }

            .rid-header-bottom [class*="col-8"] {
                margin-top: 0 !important;
            }

            .rid-header-style-1 .rid-header-bottom {
                padding: 10px 15px !important;
            }

            .logo {
                margin-left: 0 !important;
                margin-right: auto !important;
                max-width: 156px !important;
                padding: 0 !important;
            }

            .rid-header-bottom .rid-offcanvas-btn {
                display: block !important;
                margin: 0 !important;
            }

            .rid-header-bottom .rid-offcanvas-btn span {
                background-color: #050b20 !important;
            }
        }

        /* Mobile Offcanvas Menu Design Fixes */
        .rid-offcanvas-sidebar {
            background-color: #121418 !important;
        }

        .rid-offcanvas-sidebar .rid-menu ul li a {
            color: rgba(255, 255, 255, 0.75) !important;
            font-size: 20px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .rid-offcanvas-sidebar .rid-menu ul li a:hover,
        .rid-offcanvas-sidebar .rid-menu ul li a.active {
            color: #ffffff !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }

        .rid-offcanvas-sidebar .rid-menu ul li {
            padding: 12px 10px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .rid-offcanvas-sidebar .rid-social-links .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
            font-size: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            padding: 0px 0px 5px 0px;
        }

        .rid-offcanvas-sidebar .rid-social-links .social-icon.facebook {
            background-color: #3b5998;
        }

        .rid-offcanvas-sidebar .rid-social-links .social-icon.instagram {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .rid-offcanvas-sidebar .rid-social-links .social-icon.youtube {
            background-color: #ff0000;
        }

        .rid-offcanvas-sidebar .rid-social-links .list-inline-item:not(:last-child) {
            margin-right: 15px;
        }


        /* Full Page Loader */
        .loader-container {
            display: flex;
            /* visible by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Sticky header */
        .sticky_header {
            position: fixed;
            width: 100%;
            z-index: 9999;
            top: 0;
            background-color: white;
            box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.2);
        }

        /* AJAX Loader */
        .progress-container {
            display: flex;
            /* keep flex always */
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: 10000;
            /* higher than header */
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .progress-container.active {
            visibility: visible;
            opacity: 1;
        }

        .font-10 {
            font-size: 10px !important;
        }

        /* Loader animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #E0E0E0;
            border-top: 5px solid #053C7C;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Progress dots */
        .progress {
            width: 60px;
            aspect-ratio: 4;
            --_g: no-repeat radial-gradient(circle closest-side, var(--primary-color) 90%, #0000);
            background:
                var(--_g) 0% 50%,
                var(--_g) 50% 50%,
                var(--_g) 100% 50%;
            background-size: calc(100%/3) 100%;
            animation: l7 1s infinite linear;
        }

        @keyframes l7 {
            33% {
                background-size: calc(100%/3) 0%, calc(100%/3) 100%, calc(100%/3) 100%
            }

            50% {
                background-size: calc(100%/3) 100%, calc(100%/3) 0%, calc(100%/3) 100%
            }

            66% {
                background-size: calc(100%/3) 100%, calc(100%/3) 100%, calc(100%/3) 0%
            }
        }

        .quick-links-style {
            list-style: none;
            padding-left: 0;
        }

        .quick-links-style li {
            margin-bottom: 1px;

        }

        .quick-links-style li a {
            color: #ffffff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s ease;
        }

        .quick-links-style li a i {
            font-size: 16px;
            color: #9aa5b1;
            transition: 0.3s ease;
        }

        .quick-links-style li a:hover i {
            transform: translateX(4px);
        }

        .quick-links-style li a:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>
    <!-- Full Page Loader -->
    <div class="loader-container">
        <div class="loader"></div>
    </div>

    <!-- AJAX Loader -->
    <div class="progress-container">
        <div class="progress"></div>
    </div>
    <!-- Header Start: rid is prefix -->
    @include('landing.layouts.header')
    <!--  header End  -->

    @stack('modals')

    @yield('main')

    <!-- Footer Section Start -->
    @include('landing.layouts.footer')
    <!-- Footer Section End -->

    <div class="rid-offcanvas-sidebar">

        <button class="rid-offcanvas-btn" aria-label="Main Menu Icon">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <!--        -->
        <a href="{{route('landing')}}">
            <img src="{{asset('assets/logo/' . getSetting()->logo)}}" alt="ridexo footer logo" loading="lazy">
        </a>

        <!--   Rid Menu     -->
        <div class="rid-menu d-flex justify-content-center text-center">
            <ul>
                <li><a class="active home" style="color:white !important;" href="{{route('landing')}}">Home</a></li>
                <li><a href="{{route('contact')}}">Contact</a></li>
                <li><a href="{{ route('car') }}">Auction Access</a></li>
                <li><a href="{{ route('available.vehicles') }}">Available Vehicles</a></li>
                <li><a href="{{ route('services.view') }}">Services</a></li>
                {{-- <li class="dropdown">
                    <a href="javascript:void (0);" class="dropdown-toggle" data-bs-toggle="dropdown">Bookings</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('my.bookings') }}">Request a Quote</a></li>
                    </ul>
                </li> --}}

                @if(!Auth::guard('web')->check())
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('login') }}">Log in</a></li>
                    <li><a href="{{route('faqs')}}">FAQs</a></li>
                @endif

                {{-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">More</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('rental.policies') }}">Rental Policies</a></li>
                        <li><a href="{{ route('licence.requirement') }}">Licence Requirement</a></li>
                        <li><a href="{{ route('about.our.cars') }}">About Our Cars</a></li>
                        <li><a href="{{ route('useful.links') }}">Useful Links</a></li>
                        <li><a href="{{ route('japan.law') }}">Car Laws in Japan</a></li>
                        <li><a href="{{ route('ride.japan.law') }}">How to Ride Safe in Japan</a></li>
                    </ul>
                </li> --}}
                @if (Auth::check())

                    <li><a href="{{route('logout')}}">Logout</a></li>
                @endif
            </ul>
        </div>
        <!-- Social Icons inside offcanvas menu for mobile/small screen -->
        <div class="rid-social-links d-flex justify-content-center mt-4">
            <ul class="list-inline d-flex mb-0">
                <li class="list-inline-item mx-1">
                    <a href="{{ getSetting()->facebook_url }}" target="_blank" class="social-icon facebook">
                        <i class="bxl bx-facebook"></i>
                    </a>
                </li>
                <li class="list-inline-item mx-1">
                    <a href="{{ getSetting()->instagram_url }}" target="_blank" class="social-icon instagram">
                        <i class="bxl bx-instagram"></i>
                    </a>
                </li>
                <li class="list-inline-item mx-1">
                    <a href="{{ getSetting()->youtube_url }}" target="_blank" class="social-icon youtube">
                        <i class="bxl bx-youtube"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    {{--<div id="loader-overlay">--}}
        {{-- <lottie-player--}} {{-- src="{{ asset('js/loaded.json') }}" --}} {{-- background="transparent" --}} {{--
            speed="1" --}} {{-- style="width:150px; height:150px;" --}} {{-- loop--}} {{-- autoplay>--}}
            {{-- </lottie-player>--}}
            {{--</div>--}}
    <div id="toTop">
        <i class="bx bx-chevron-up"></i>
    </div>

    <!-- Js files -->
    @include('landing.layouts.footer-links')
    @stack('script-src')
    @include('admin.layouts.common-js')
    @yield('script')
    @stack('script')
    <script !src="">
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 50) {
                $(".rid-header-bottom").addClass("sticky_header");
            } else {
                $(".rid-header-bottom").removeClass("sticky_header");
            }
        });
        $(window).on("load", function () {
            setTimeout(function () {
                $(".loader-container").fadeOut("slow");
            }, 1000); // Optional delay
        });
        document.addEventListener('DOMContentLoaded', function () {

            function updateTokyoTime() {
                // Options for date display
                const dateOptions = {
                    timeZone: 'Asia/Tokyo',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                // Options for time display (12-hour format with AM/PM)
                const timeOptions = {
                    timeZone: 'Asia/Tokyo',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };

                const now = new Date();

                // Get formatted date and time
                const tokyoDate = now.toLocaleDateString('en-US', dateOptions);
                const tokyoTime = now.toLocaleTimeString('en-US', timeOptions);

                // Combine date and time for display
                const fullDateTime = `${tokyoDate}, ${tokyoTime}`;

                // Update both mobile and desktop elements
                document.getElementById('tokyo-time-mobile').textContent = fullDateTime;
                document.getElementById('tokyo-time-desktop').textContent = fullDateTime;

                // Update tooltip with just the time
                const clockIcons = document.querySelectorAll('.bx-time');
                clockIcons.forEach(icon => {
                    icon.setAttribute('title', `Tokyo Time: ${tokyoTime}`);
                });
            }

            // Update immediately and then every second
            updateTokyoTime();
            setInterval(updateTokyoTime, 1000);

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>