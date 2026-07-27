<header id="header" class="rid-header-style-1">

    <div class="rid-header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-8 col-8">
                    <div class="rid-contact">
                        {{-- <div class="business-contact">
                            <a href="tel:+81648642081" class="text-white">
                                <i class="bx bxs-phone me-2"></i>
                                <span class="mr-40 d-none d-md-inline">
                                    +81 6 4864 2081
                                </span>
                            </a>
                        </div> --}}
                        <div class="business-hour">
                            <div class="business-hour-mob">
                                <i class="bx bx-time me-2" data-bs-toggle="tooltip" title="Tokyo Time"
                                    data-bs-placement="bottom"></i>
                                <span class="d-none" id="tokyo-time-mobile"></span>
                            </div>

                            <div class="business-hour-des">
                                <i class="bx bx-time me-2 d-none d-md-inline"></i>
                                <span class="d-none d-md-inline" id="tokyo-time-desktop"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-12 d-flex justify-content-end align-items-center">
                    <div class="rid-social-links me-3">
                        <ul class="list-inline d-flex mb-0">

                            <li class="list-inline-item mx-1">
                                <a href="{{ getSetting()->facebook_url }}" target="_blank" class="social-icon facebook">
                                    <i class="bxl bx-facebook"></i>
                                </a>
                            </li>
                            <li class="list-inline-item mx-1">
                                <a href="{{ getSetting()->instagram_url }}" target="_blank"
                                    class="social-icon instagram">
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
                    @if(Auth::guard('web')->check())
                        <a href="{{route('profile.settings')}}" class="text-white ms-3">
                            <i class="bx bx-user"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-white text-decoration-none font-size-14 fw-bold ms-3">Account Login</a>
                    @endif
                    <button class="rid-offcanvas-btn ms-3" aria-label="Main Menu Icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="rid-header-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-8 col-sm-8 col-6">
                    <a href="{{route('landing')}}">
                        <img class="logo" src="{{asset('assets/logo/' . getSetting()->logo)}}" alt="Ridexo Logo Yellow"
                            loading="lazy">
                    </a>
                </div>
                <div class="col-lg-9 col-md-4 col-sm-4 col-6">
                    <div class="d-flex justify-content-center align-items-center">
                        <nav class="rid-menubar">
                            <ul class="list-inline">
                                <li class="list-inline-item"><a class="@if(request()->is('/')) active @endif"
                                        href="{{route('landing')}}">Home</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0);">About Us</a></li>
                                {{-- <li class="list-inline-item"><a href="{{ route('car') }}"
                                        class="@if(request()->is('car')) active @endif">Cars</a></li> --}}
                                <li class="list-inline-item"><a href="{{ route('car') }}"
                                        class="@if(request()->is('car')) active @endif">Auction Access</a></li>
                                <li class="list-inline-item"><a href="{{ route('available.vehicles') }}"
                                        class="@if(request()->is('available-vehicles')) active @endif">Available
                                        Vehicles</a></li>
                                <li class="list-inline-item"><a href="{{ route('services.view') }}"
                                        class="@if(request()->is('services')) active @endif">Services</a></li>

                                {{-- <li class="list-inline-item dropdown">
                                    <a href="javascript:void (0);"
                                        class="dropdown-toggle @if(request()->is('my-bookings')) active @endif"
                                        data-bs-toggle="dropdown">Bookings</a>
                                    <ul class="dropdown-menu px-2">
                                        <li><a href="{{ route('my.bookings') }}"
                                                class="font-10 m-0 dropdown-a @if(request()->is('my-bookings')) active @endif">Request
                                                a Quote</a></li>
                                    </ul>
                                </li> --}}
                                <li class="list-inline-item"><a class="@if(request()->is('contact')) active @endif"
                                        href="{{route('contact')}}">Contact</a></li>


                                {{-- <li class="list-inline-item dropdown">
                                    <a href="javascript:void (0);"
                                        class="dropdown-toggle @if(request()->is(['rental-policies', 'licence-requirement', 'about-our-cars', 'useful-links', 'japan-law', 'ride-japan-law'])) active @endif"
                                        data-bs-toggle="dropdown">More</a>

                                    <ul class="dropdown-menu px-2">
                                        <li><a href="{{ route('rental.policies') }}"
                                                class="m-0 dropdown-a @if(request()->is('rental-policies')) active @endif">Rental
                                                Policies</a></li>
                                        <li><a href="{{ route('licence.requirement') }}"
                                                class="m-0 dropdown-a @if(request()->is('licence-requirement')) active @endif">Licence
                                                Requirement</a></li>
                                        <li><a href="{{ route('about.our.cars') }}"
                                                class="m-0 dropdown-a @if(request()->is('about-our-cars')) active @endif">About
                                                Our Cars</a></li>
                                        <li><a href="{{ route('useful.links') }}"
                                                class="m-0 dropdown-a @if(request()->is('useful-links')) active @endif">Useful
                                                Links</a></li>
                                        <li><a href="{{ route('japan.law') }}"
                                                class="m-0 dropdown-a @if(request()->is('japan-law')) active @endif">Car
                                                Laws in Japan</a></li>
                                        <li><a href="{{ route('ride.japan.law') }}"
                                                class="m-0 dropdown-a @if(request()->is('ride-japan-law')) active @endif">How
                                                to Ride Safe in Japan</a></li>
                                    </ul>
                                </li> --}}
                            </ul>
                        </nav>
                        <button class="rid-offcanvas-btn d-lg-none" aria-label="Main Menu Icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
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