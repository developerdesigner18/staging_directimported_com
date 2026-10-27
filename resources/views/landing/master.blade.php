<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="thecodude">
    <meta name="description" content="">

    <!-- responsive tag -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>@yield('title') | {{ config('app.name') }}</title>
    @yield('meta')
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

            /* Header & Footer Variables - from Master */
            --navy-900: #020813;
            --navy-800: #0c213d;
            --theme-red: var(--primary-hover-color, #de2b43);
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --font-body: var(--body-font, 'Poppins', sans-serif);
            --font-heading: var(--title-font, 'Poppins', sans-serif);
        }

        /* Full Page Loader */
        .loader-container {
            display: flex;
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

        /* AJAX Loader */
        .progress-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: 10000;
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
                $(".navbar").addClass("sticky_header");
            } else {
                $(".navbar").removeClass("sticky_header");
            }
        });

        $(window).on("load", function () {
            setTimeout(function () {
                $(".loader-container").fadeOut("slow");
            }, 1000); // Optional delay
        });

        // Mobile Menu Toggle
        (function () {
            var btn = document.getElementById('menuToggle');
            var menu = document.getElementById('mobileMenu');
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    var open = menu.classList.toggle('is-open');
                    btn.classList.toggle('is-active', open);
                    btn.setAttribute('aria-expanded', open);
                });
                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 1024) {
                        menu.classList.remove('is-open');
                        btn.classList.remove('is-active');
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        })();

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
                const fullDateTime = `${tokyoDate}, ${tokyoTime}`;

                // Update reference header elements
                const dateEl = document.getElementById('tokyo-date');
                const timeEl = document.getElementById('tokyo-time');
                if (dateEl) dateEl.textContent = tokyoDate;
                if (timeEl) timeEl.textContent = ', ' + tokyoTime;

                // Update legacy elements if any
                const mobileEl = document.getElementById('tokyo-time-mobile');
                const desktopEl = document.getElementById('tokyo-time-desktop');
                if (mobileEl) mobileEl.textContent = fullDateTime;
                if (desktopEl) desktopEl.textContent = fullDateTime;

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