<header id="header" class="rid-header-style-1">

    <div class="rid-header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-8 col-8">
                    <div class="rid-contact">
                        <div class="business-contact">
                            <a href="tel:+81648642081">
                                <i class="flaticon-telephone-call me-2"></i>
                                <span class="mr-40">
                                    +81 6 4864 2081
                                    </span>
                            </a>
                        </div>
                        <div class="business-hour">
                            <div class="business-hour-mob">
                                <i class="flaticon-clock me-2" data-bs-toggle="tooltip"
                                   title="Tokyo Time" data-bs-placement="bottom"></i>
                                <span class="d-none" id="tokyo-time-mobile"></span>
                            </div>

                            <div class="business-hour-des">
                                <i class="flaticon-clock me-2 d-none d-md-inline"></i>
                                <span class="d-none d-md-inline" id="tokyo-time-desktop"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3 col-md-2 col-4 d-flex justify-content-end align-items-center">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="rid-social-links">
                            <ul class="list-inline d-flex pe-3">
                                <li class="list-inline-item">
                                    <a href="https://twitter.com" target="_blank"
                                       class="social-icon twitter bg-black">
                                        <i class="bxl bx-twitter-x"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item mx-1">
                                    <a href="https://www.facebook.com/bikerentaljapan/" target="_blank"
                                       class="social-icon facebook">
                                        <i class="bxl bx-facebook"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item mx-1">
                                    <a href="https://www.instagram.com/ezmotokansai/" target="_blank"
                                       class="social-icon instagram">
                                        <i class="bxl bx-instagram"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item mx-1">
                                    <a href="https://www.youtube.com/channel/UCFqU9FtrLMK7_8F1HBEzPQg" target="_blank"
                                       class="social-icon youtube">
                                        <i class="bxl bx-youtube"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if(Auth::guard('web')->check())
                        <a href="{{route('profile.settings')}}" class="mr-40">
                            <i class="flaticon-avatar"></i>
                        </a>
                    @else


                                                    {{-- <li class="list-inline-item "><a href="{{ route('register') }}" class="list-inline-item-bold">Register</a></li> --}}
                                                    <li class="list-inline-item list-inline-item-bold"><a href="{{ route('login') }}" class="list-inline-item-bold">Account Login</a></li>

                    @endif
                    <button class="rid-offcanvas-btn" aria-label="Main Menu Icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="rid-header-bottom">--}}
{{--        <div class="container">--}}
{{--            <div class="row align-items-center">--}}
{{--                <div class="col-sm-4 col-md-4 col-4">--}}
{{--                    <div class="logo">--}}
{{--                        <a href="{{route('landing')}}">--}}
{{--                            <img src="{{asset('assets/logo/main.png')}}" alt="Bike Rental Logo">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-sm-8 col-md-8 col-8 d-flex justify-content-end align-items-center">--}}
{{--                    <div class="rid-social-links">--}}
{{--                        <ul class="list-inline">--}}
{{--                            <li class="list-inline-item">--}}
{{--                                <a href="https://www.facebook.com/bikerentaljapan/" target="_blank"--}}
{{--                                   class="social-icon facebook">--}}
{{--                                    <i class="icofont-facebook"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="list-inline-item">--}}
{{--                                <a href="https://www.instagram.com/ezmotokansai/" target="_blank"--}}
{{--                                   class="social-icon instagram">--}}
{{--                                    <i class="icofont-instagram"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="list-inline-item">--}}
{{--                                <a href="https://www.youtube.com/channel/UCFqU9FtrLMK7_8F1HBEzPQg" target="_blank"--}}
{{--                                   class="social-icon youtube">--}}
{{--                                    <i class="icofont-youtube-play"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
<div class="rid-header-style-1">

    <div class="rid-header-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-8 col-sm-8 col-6">
                    <a href="{{route('landing')}}">
                        <img class="logo" src="{{asset('assets/logo/main.png')}}" alt="Ridexo Logo Yellow">
                    </a>
                </div>
                <div class="col-lg-10 col-md-4 col-sm-4 col-6">
                    <div class="d-flex justify-content-center align-items-center">
                        <nav class="rid-menubar">
                            <ul class="text-white list-inline">
                                <li class="list-inline-item"><a class="@if(request()->is('/')) active @endif" href="{{route('landing')}}">Home</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0);">Tours</a></li>
                                <li class="list-inline-item"><a href="{{ route('motorcycle') }}" class="@if(request()->is('motorcycle')) active @endif">Motorcycles</a></li>

                                <li class="list-inline-item dropdown">
                                    <a href="javascript:void (0);" class="dropdown-toggle @if(request()->is('my-bookings')) active @endif" data-bs-toggle="dropdown">Bookings</a>
                                    <ul class="dropdown-menu px-2">
                                        <li><a href="{{ route('my.bookings') }}" class="font-10 m-0 dropdown-a @if(request()->is('my-bookings')) active @endif">Request a Quote</a></li>
                                    </ul>
                                </li>
                                <li class="list-inline-item"><a class="@if(request()->is('contact')) active @endif" href="{{route('contact')}}">Contact</a></li>


                                <li class="list-inline-item dropdown">
                                    <a href="javascript:void (0);" class="dropdown-toggle @if(request()->is(['rental-policies','licence-requirement','about-our-bikes','useful-links','japan-law','ride-japan-law'])) active @endif" data-bs-toggle="dropdown">More</a>

                                    <ul class="dropdown-menu px-2">
                                        <li><a href="{{ route('rental.policies') }}" class="m-0 dropdown-a @if(request()->is('rental-policies')) active @endif" >Rental Policies</a></li>
                                        <li><a href="{{ route('licence.requirement') }}" class="m-0 dropdown-a @if(request()->is('licence-requirement')) active @endif">Licence Requirement</a></li>
                                        <li><a href="{{ route('about.our.bikes') }}" class="m-0 dropdown-a @if(request()->is('about-our-bikes')) active @endif">About Our Motorcycles</a></li>
                                        <li><a href="{{ route('useful.links') }}" class="m-0 dropdown-a @if(request()->is('useful-links')) active @endif">Useful Links</a></li>
                                        <li><a href="{{ route('japan.law') }}" class="m-0 dropdown-a @if(request()->is('japan-law')) active @endif">Motorcycle Laws in Japan</a></li>
                                        <li><a href="{{ route('ride.japan.law') }}" class="m-0 dropdown-a @if(request()->is('ride-japan-law')) active @endif">How to Ride Safe in Japan</a></li>
                                    </ul>
                                </li>

                            </ul>

                        </nav>
{{--                        <div class="d-flex justify-content-end align-items-center">--}}
{{--                            <div class="rid-social-links">--}}
{{--                                <ul class="list-inline d-flex">--}}
{{--                                    <li class="list-inline-item">--}}
{{--                                        <a href="https://twitter.com" target="_blank"--}}
{{--                                           class="social-icon twitter bg-black">--}}
{{--                                            <i class="bxl bx-twitter-x"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="list-inline-item mx-1">--}}
{{--                                        <a href="https://www.facebook.com/bikerentaljapan/" target="_blank"--}}
{{--                                           class="social-icon facebook">--}}
{{--                                            <i class="bxl bx-facebook"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="list-inline-item mx-1">--}}
{{--                                        <a href="https://www.instagram.com/ezmotokansai/" target="_blank"--}}
{{--                                           class="social-icon instagram">--}}
{{--                                            <i class="bxl bx-instagram"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="list-inline-item mx-1">--}}
{{--                                        <a href="https://www.youtube.com/channel/UCFqU9FtrLMK7_8F1HBEzPQg" target="_blank"--}}
{{--                                           class="social-icon youtube">--}}
{{--                                            <i class="bxl bx-youtube"></i>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</header>
@if(session()->has('admin_id'))
    <div style="background:#F3364F;color:white;text-align:center;padding:5px 0;font-size:13px;">
        Admin Impersonation Mode —
        <a href="{{ route('back.to.admin') }}"
           style="color:white;font-weight:600;text-decoration:underline;">
            Return to Admin Panel
        </a>
    </div>
@endif
