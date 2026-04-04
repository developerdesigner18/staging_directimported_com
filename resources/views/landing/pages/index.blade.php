@extends('landing.master')
@section('title', 'Home')
@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
            color: #777777;
        }

        .rid-banner-style-1 {
            position: relative !important;
            width: 100% !important;
            /*aspect-ratio: 12 / 5 !important;*/
            height: 400px;
            overflow: hidden !important;
            background:
                {{$color->slider_backcolor ?? '#C2C2C2'}}
                !important;
            /* Fallback background */
        }

        .slider-container {
            width: 100% !important;
            height: 100% !important;
        }

        .slider-item {
            width: 100% !important;
            height: 100% !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
        }

        .slider-bg-image {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            z-index: 0 !important;
        }

        /* Dark overlay for better text readability */
        .slider-item::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 1 !important;
        }

        .banner-info {
            position: relative !important;
            z-index: 2 !important;
            color: white !important;
            padding: 40px 0 !important;
        }

        .banner-info h3 {
            font-size: 3.5rem !important;
            font-weight: 700 !important;
            margin-bottom: 20px !important;
            line-height: 1.2 !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5) !important;
        }

        .banner-info h1 {
            color: white !important;
            font-size: 1.2rem !important;
            font-weight: 400 !important;
            margin-bottom: 30px !important;
            line-height: 1.6 !important;
            opacity: 0.9 !important;
            max-width: 500px !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5) !important;
        }

        .banner-info .btn {
            display: inline-block !important;
            padding: 15px 35px !important;
            background: linear-gradient(45deg, #053C7C, #0056b3) !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3) !important;
        }

        .banner-info .btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
            background: linear-gradient(45deg, #0056b3, #004085) !important;
        }

        /* Custom Slick Arrows */
        .slick-prev,
        .slick-next {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 60px !important;
            height: 60px !important;
            background: rgba(255, 255, 255, 0.2) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
            opacity: 1 !important;
        }

        .slick-prev:hover,
        .slick-next:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-50%) scale(1.1) !important;
        }

        .slick-prev {
            left: 30px !important;
        }

        .slick-next {
            right: 30px !important;
        }

        .slick-prev:before,
        .slick-next:before {
            font-family: 'Font Awesome 5 Free' !important;
            font-weight: 900 !important;
            font-size: 20px !important;
            color: white !important;
            line-height: 1 !important;
        }

        .slick-prev:before {
            content: '\f104' !important;
            /* Left arrow */
        }

        .slick-next:before {
            content: '\f105' !important;
            /* Right arrow */
        }

        /* If Font Awesome is not available, use Unicode arrows */
        .no-fontawesome .slick-prev:before {
            content: '‹' !important;
            font-size: 30px !important;
            font-weight: bold !important;
        }

        .no-fontawesome .slick-next:before {
            content: '›' !important;
            font-size: 30px !important;
            font-weight: bold !important;
        }

        /* Custom Slick Dots */
        .slick-dots {
            position: absolute !important;
            bottom: 30px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            display: flex !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            z-index: 10 !important;
        }

        .slick-dots li {
            margin: 0 8px !important;
        }

        .slick-dots li button {
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.4) !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            font-size: 0 !important;
            padding: 0 !important;
        }

        .slick-dots li.slick-active button {
            background: white !important;
            border-color: white !important;
            transform: scale(1.2) !important;
        }

        .slick-dots li button:hover {
            background: rgba(255, 255, 255, 0.7) !important;
            transform: scale(1.1) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .slider-item {
                height: 100% !important;
            }

            .banner-info h3 {
                font-size: 2.5rem !important;
            }

            .banner-info h1 {
                font-size: 1rem !important;
            }

            .slick-prev,
            .slick-next {
                width: 50px !important;
                height: 50px !important;
            }

            .slick-prev {
                left: 15px !important;
            }

            .slick-next {
                right: 15px !important;
            }
        }

        @media (max-width: 576px) {
            .banner-info {
                text-align: center !important;
            }

            .banner-info h3 {
                font-size: 2rem !important;
            }

            .banner-info h1 {
                font-size: 0.9rem !important;
            }
        }
    </style>
    <style>
        .slider-set {
            position: absolute;
            top: 15px;
            left: 15px;
            width: calc(100% - 30px);
            height: calc(100% - 30px);
            display: flex;
            gap: 2mm;
            z-index: 2;
        }

        .slide {
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            width: 100%;
            /* default for mobile */
        }

        /* ✅ Tablet: 2 slides */
        @media (min-width: 768px) and (max-width: 1023px) {
            .slide {
                width: calc(50% - (2mm / 2));
            }
        }

        /* ✅ Desktop: 3 slides */
        @media (min-width: 1024px) {
            .slide {
                width: calc(100% / 3 - (2mm * 2 / 3));
            }
        }

        .slide-img-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            z-index: 1;
        }

        .slide-img-container.visible {
            opacity: 1;
            z-index: 2;
        }

        .slide img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .prev-button,
        .next-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            font-size: 24px;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 0.3s, transform 0.2s;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 0.8;
        }

        .prev-button:hover,
        .next-button:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: translateY(-50%) scale(1.05);
            opacity: 1;
        }

        .prev-button {
            left: 15px;
        }

        .next-button {
            right: 15px;
        }

        .nav-dots {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
            padding: 5px 0;
        }

        .dot {
            width: 14px;
            height: 14px;
            background: rgba(100, 100, 100, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dot.active {
            background: #4a90e2;
            transform: scale(1.3);
            border-color: #4a90e2;
        }

        /* Bikes Grid */
        /* ===== Card ===== */
        .bike-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
            transition: all .3s ease;
            height: 500px;
        }

        /*.bike-card:hover {*/
        /*    box-shadow: 0 24px 40px rgba(0,0,0,.15);*/
        /*    transform: translateY(-6px);*/
        /*}*/

        /* ===== Image ===== */
        .bike-card-img-wrapper {
            position: relative;
            height: 260px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .bike-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .bike-card:hover img {
            transform: scale(1.06);
        }

        /* ===== Tag ===== */
        .bike-tag {
            display: inline-block;
            margin-top: 6px;

            color: black;
            font-size: 11px;
            font-weight: 800;

            /*background: #f1f3f5;*/
            padding: 6px 14px;
            border-radius: 999px;

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ===== Body ===== */
        .bike-card-body {
            padding: 26px 22px 28px;
            text-align: center;
        }

        /* REMOVE side-by-side layout */
        .bike-info-row {
            display: block;
        }

        /* ===== Title ===== */
        .bike-info-row h5 {
            font-size: 24px;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .bike-info-row h5 a {
            text-decoration: none;
            color: inherit;
        }


        /* ===== Subtitle ===== */
        .bike-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 22px;
            font-weight: 500;
        }

        /* ===== Price (centered like image 1) ===== */
        .bike-price-block {
            display: flex;
            align-items: baseline;
            /* matches flex items-baseline */
            justify-content: center;
            /* matches justify-center */
            margin-bottom: 16px;
            /* mb-4 */
            gap: 4px;
        }

        /*.bike-price-block::before {*/
        /*    !*content: "From";*!*/
        /*    display: block;*/
        /*    font-size: 13px;*/
        /*    font-weight: 700;*/
        /*    color: #053C7C; /* ASJ Racing Red */
        */
        /*    margin-bottom: 2px;*/
        /*}*/

        .bike-price-block .price {
            color: #053C7C;
            /* ASJ Racing Red */
            /* text-red-600 */
            font-size: 30px;
            /* text-3xl */
            font-weight: 900;
            /* font-black */
            line-height: 1;
        }

        .bike-price-block .period {
            color: #053C7C;
            /* ASJ Racing Red */
            /* text-red-600 */
            font-size: 14px;
            /* text-sm */
            font-weight: 700;
            /* font-bold */
            white-space: nowrap;
        }

        .btn-quote {
            width: 100%;
            background: linear-gradient(135deg, #053C7C 0%, #042B59 100%);
            /* ASJ Racing Red */
            color: #fff;
            border: none;
            padding: 15px 18px;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            box-shadow: 0 6px 14px rgba(188, 33, 46, .35);
            cursor: pointer;
            transition: all .3s ease;
        }

        .btn-quote:hover {
            background: linear-gradient(135deg, #053C7C 0%, #042B59 100%) !important;
            color: #de2b43 !important;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(188, 33, 46, .45);
        }

        .top-20vh {
            top: 20vh;
        }

        /* Reclaimed CTA Box Fixed Visibility */
        #reclaimed-cta-box {
            padding: 30px 20px;
            text-align: center;
            max-width: 560px;
            margin: -112px auto 0;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            position: relative;
            z-index: 999;
        }

        .cta-title {
            font-weight: 800;
            font-size: 24px;
            color: #000;
            margin-bottom: 20px;
            letter-spacing: -0.3px;
            text-transform: uppercase;
        }

        .cta-btn {
            display: inline-block;
            background: linear-gradient(180deg, #1e4c8f 0%, #163a6b 100%);
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 35px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s ease;
            /* text-transform: uppercase; */
        }

        ..cta-btn {
            display: inline-block;
            background: linear-gradient(180deg, #1e4c8f 0%, #163a6b 100%);
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 35px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .top-20vh {
            top: 20vh;
        }
    </style>
@endpush
@section('main')

    <!-- Banner Section Start -->
    {{-- @dd($sliderImages)--}}
    @if(!$sliders->isEmpty())
        {{-- <section class="rid-banner-style-1">--}}
            {{-- <div class="slider-container">--}}
                {{-- @foreach($sliders as $slider)--}}
                {{-- <div class="slider-item">--}}
                    {{-- <!-- Background Image -->--}}
                    {{-- <img src="{{ $slider->image }}" alt="{{ @$slider->title }}" class="slider-bg-image">--}}
                    {{-- <div class="container">--}}
                        {{-- <div class="row">--}}
                            {{-- <div class="col-sm-8 col-md-7 offset-sm-2 offset-md-1">--}}
                                {{-- <div class="banner-info">--}}
                                    {{-- @if($slider->title)--}}
                                    {{-- <h3 class="primary-color">{{ \Str::upper($slider->title) }}</h3>--}}
                                    {{-- @endif--}}
                                    {{-- @if($slider->description)--}}
                                    {{-- <h1>{{ $slider->description }}</h1>--}}
                                    {{-- @endif--}}
                                    {{-- @if($slider->button_text && $slider->href)--}}
                                    {{-- <a href="{{ $slider->href }}" class="btn">{{ $slider->button_text }}</a>--}}
                                    {{-- @endif--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- @endforeach--}}

                {{-- <!-- Add first two slides again at the end for seamless looping -->--}}
                {{-- @if(count($sliders) >= 3)--}}
                {{-- @foreach($sliders->take(2) as $slider)--}}
                {{-- <div class="slider-item">--}}
                    {{-- <!-- Background Image -->--}}
                    {{-- <img src="{{ $slider->image }}" alt="{{ @$slider->title }}" class="slider-bg-image">--}}
                    {{-- <div class="container">--}}
                        {{-- <div class="row">--}}
                            {{-- <div class="col-sm-8 col-md-7 offset-sm-2 offset-md-1">--}}
                                {{-- <div class="banner-info">--}}
                                    {{-- @if($slider->title)--}}
                                    {{-- <h3 class="primary-color">{{ \Str::upper($slider->title) }}</h3>--}}
                                    {{-- @endif--}}
                                    {{-- @if($slider->description)--}}
                                    {{-- <h1>{{ $slider->description }}</h1>--}}
                                    {{-- @endif--}}
                                    {{-- @if($slider->button_text && $slider->href)--}}
                                    {{-- <a href="{{ $slider->href }}" class="btn">{{ $slider->button_text }}</a>--}}
                                    {{-- @endif--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- @endforeach--}}
                {{-- @endif--}}
                {{-- </div>--}}
            {{-- </section>--}}

        <section class="rid-banner-style-1">
            <div class="slider-container">
                <div class="slider-set">
                    <div class="slide"></div>
                    <div class="slide"></div>
                    <div class="slide"></div>
                </div>

                <button class="prev-button">&lt;</button>
                <button class="next-button">&gt;</button>
                <div class="nav-dots">
                </div>
            </div>
        </section>
    @endif
    <!-- Banner Section End -->

    <!-- Filter/CTA Section Start -->
    <section class="rid-filter-1"
        style="display: block !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 9998 !important;">
        <div class="container">
            <div class="cta-box" id="reclaimed-cta-box">
                <h2 class="cta-title">Buy cars from Japan</h2>
                <a href="http://127.0.0.1:8000/motorcycle" class="cta-btn">
                    Search auctions live here
                </a>
            </div>
        </div>
    </section>
    <!-- Filter/CTA Section End -->

    <!-- Our Services Section Start -->
    <section id="ourservices">
        <div class="container">
            <div class="inner-container-bg">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="section-title">
                        <h2>Our Services</h2>
                    </div>
                    {{-- <a href="#" class="show-all-link">Show All</a> --}}
                </div>

                <div class="row g-4">
                    <!-- Auction Inspection Services -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="icon-wrapper">
                                <img src="{{asset('assets/landing/images/services/Group-5-300x300.png')}}"
                                    alt="Auction Inspection">
                            </div>
                            <h4>Auction Inspection Services</h4>
                            <p>
                                Do we sit behind a computer NO!!! We provide detailed information, high-resolution photos,
                                and
                                condition reports for each vehicle at the auctions we attend.
                            </p>
                        </div>
                    </div>

                    <!-- Inspection and ODO Certification -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="icon-wrapper">
                                <img src="{{asset('assets/landing/images/services/Frame-300x300 (1).png')}}"
                                    alt="ODO Certification">
                            </div>
                            <h4>Inspection and ODO Certification</h4>
                            <p>
                                Thorough inspection reports translated checked based on auction location and grading. Not
                                all
                                auctions are equal. ODO certification services from JEVIC to ensure genuine KLM.
                            </p>
                        </div>
                    </div>

                    <!-- Documentation for export -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="icon-wrapper">
                                <img src="{{asset('assets/landing/images/services/Group.png')}}" alt="Documentation">
                            </div>
                            <h4>Documentation for export</h4>
                            <p>
                                Complete support with export and import documentation, customs export clearance, to
                                successfully
                                export your vehicles without delay.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-5">
                    <a href="#" class="btn-read-more">
                        READ MORE <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- About IAS Japan Section Start -->
    <section id="about-ias" class="py-5">
        <div class="container">
            <div class="about-card p-4 p-md-5">
                <div class="row">
                    <div class="col-lg-10">
                        <h2 class="about-title">About IAS Japan</h2>

                        <p class="about-desc">
                            Welcome to International Auto Select Japan LLC, where our mission is to connect car dealers and
                            buyers around the world with reliable services in Japan. With years of experience in the
                            automotive industry, we pride ourselves on delivering exceptional support, transparency, and
                            efficiency to meet your business needs. Whether you are looking to source quality vehicles or
                            expand your dealership's inventory, we are here to provide trusted solutions and build lasting
                            partnerships.
                        </p>

                        <div class="about-points mb-5">
                            <div class="about-point">
                                <span class="about-point-icon">
                                    <i class='bx bx-check'></i>
                                </span>
                                <p class="about-point-text">
                                    International Auto Select Japan Export is a leading car export company dedicated to
                                    making the process of buying and importing vehicles as seamless as possible.
                                </p>
                            </div>

                            <div class="about-point">
                                <span class="about-point-icon">
                                    <i class='bx bx-check'></i>
                                </span>
                                <p class="about-point-text">
                                    We offer a wide range of high-quality services to help you navigate the vehicle market
                                    in Japan and expand your business reliably.
                                </p>
                            </div>

                            <div class="about-point">
                                <span class="about-point-icon">
                                    <i class='bx bx-check'></i>
                                </span>
                                <p class="about-point-text">
                                    We have built a reputation over the past 20 years for establishing long-term business
                                    relationships based on trust. If you need this type of service, please contact us today.
                                </p>
                            </div>
                        </div>

                        <a href="#" class="ias-btn uppercase">
                            READ MORE <i class='bx bx-right-arrow-alt align-middle ms-1'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    <!--  How-it-work Section Start-->
    <div class="rid-how-it-work">

        <div class="container">
            <h2 class="text-center">How it Works?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <i class="flaticon-magnifying-glass-search"></i>
                        <h4>Find the Right Car</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <i class="flaticon-booking"></i>
                        <h4>Buy it Online</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="rid-info-box text-center">
                        <img src="{{asset('assets/landing/images/car.png')}}" alt="Car">
                        <h4>Enjoy your Ride</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  How-it-work Section End-->

    <!--  Skill Section Start -->
    {{-- <div class="rid-skill sec-space">--}}
        {{-- <div class="row">--}}
            {{-- <div class="col-lg-6 col-md-12 bg-bike">--}}
                {{-- </div>--}}
            {{-- <div class="col-lg-6 col-md-12">--}}
                {{-- <div class="skill-info">--}}
                    {{-- <div class="col-12">--}}
                        {{-- <div class="skill-info-text">--}}
                            {{-- <h2 class="text-white">World Top 100 Bike For You</h2>--}}
                            {{-- <p class="text-white">Fermentum assumenda, nostrud semper tellus reprehenderit, auctor--}}
                                {{-- aliquip--}}
                                {{-- officia, adipiscing! Sapien consequuntur consectetuer facere potenti?
                                Incididuntmontes--}}
                                {{-- praesent, qui. Venenatis, consequuntur nobis pede.</p>--}}
                            {{-- <p class="text-white"> Harum incidunt mollis natus dui quas, massa irure cursus odit--}}
                                {{-- molestias--}}
                                {{-- nemo a cursus. Metus. Mollit irure posuere eget, sociis, aliquip, ipsum tempus
                                turpis.--}}
                                {{-- Mollitia, sunt, egestas montes! Sollicitudin! Hendrerit rhoncu.--}}
                                {{-- </p>--}}
                            {{-- </div>--}}
                        {{-- <!-- Progressbar -->--}}
                        {{-- <div class="rid-progressbar d-flex">--}}
                            {{-- <div class="circlechart" data-percentage="90">--}}
                                {{-- <h2>Motorbike</h2>--}}
                                {{-- </div>--}}

                            {{-- <div class="circlechart" data-percentage="80">--}}
                                {{-- <h2>Scooter</h2>--}}
                                {{-- </div>--}}

                            {{-- <div class="circlechart" data-percentage="60">--}}
                                {{-- <h2>Bicycle</h2>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </div>--}}
    <!--  Skill Section End -->

    <!-- Rentals Section Start -->
    @if(!$bikes->isEmpty())
        <div class="rid-rentals-1">

            <div class="container">

                <h2 class="text-center">Most Popular Bikes</h2>
                <div class="text-end mb-3">
                    <a href="{{ route('motorcycle') }}" class="btn">See All</a>
                </div>
                <div class="row">
                    @foreach($bikes as $bike)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bike-card">

                                <!-- Image -->
                                <div class="bike-card-img-wrapper">
                                    <a href="{{ route('motorcycle.single', ['slug' => $bike->slug]) }}">
                                        @if(isset($bike->images[0]))
                                            <img src="{{ asset(BIKE_PATH . $bike->images[0]) }}" alt="{{ $bike->name }}">
                                        @else
                                            <img src="{{ asset('assets/landing/img/no-image.jpg') }}" alt="{{ $bike->name }}">
                                        @endif
                                    </a>
                                </div>

                                <!-- Body -->
                                <div class="bike-card-body">

                                    <!-- Title -->
                                    <h4 class="bike-title">
                                        <a href="{{ route('motorcycle.single', ['slug' => $bike->slug]) }}">
                                            {{ $bike->card_header ?? $bike->name }}
                                        </a>
                                    </h4>

                                    <!-- Subtitle -->
                                    <div class="bike-subtitle">
                                        {{ $bike->card_subtitle ?? ($bike->category->name ?? 'Premium Adventure Touring') }}
                                    </div>

                                    <!-- Emblems / Icons -->
                                    <div class="bike-emblem-row">
                                        <div class="emblem-item">
                                            <i class='bx bx-helmet'></i>
                                            <span>Geared Up</span>
                                        </div>
                                        <div class="emblem-item">
                                            <i class='bx bx-calendar'></i>
                                            <span>Ready to Book</span>
                                        </div>
                                        <div class="emblem-item">
                                            <i class='bx bx-gas-pump'></i>
                                            <span>Adventure-Ready</span>
                                        </div>
                                    </div>

                                    <div>
                                        <!-- Price -->
                                        <div class="bike-price-block">
                                            <span class="price-from">From</span>
                                            <span class="price-amount">¥{{ number_format($bike->month_price) }}</span>
                                            <span class="price-per">/ Per Day</span>
                                        </div>

                                        <!-- Button -->
                                        {{-- <button class="btn-adventure bikeRequestQuote" data-id="{{ $bike->id }}"
                                            data-name="{{ $bike->name }}"
                                            data-image="{{ isset($bike->images[0]) ? asset(BIKE_PATH.$bike->images[0]) : '' }}">
                                            CHECK IT OUT
                                        </button> --}}
                                        <button class="btn-adventure btncheckout" data-slug="{{ $bike->slug }}"
                                            data-id="{{ $bike->id }}" data-name="{{ $bike->name }}"
                                            data-image="{{ isset($bike->images[0]) ? asset(BIKE_PATH . $bike->images[0]) : '' }}">
                                            CHECK IT OUT
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @endif
    <!-- Rentals Section End -->

    <!-- Matters To You Section Start -->
    <section id="matters-to-you">
        <div class="container">
            <div class="inner-container-bg">
                <div class="row ">
                    <!-- Left Column -->
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <h2 class="matters-title">We’re Big On What Matters To You</h2>
                        <p class="matters-desc">
                            At IAS, we pride ourselves on trust, reliability, and integrity in every vehicle export. We're
                            more
                            than a service provider—we're your partner, dedicated to simplifying the process, meeting your
                            unique needs, and building relationships that last. It's a two-way commitment, ensuring
                            transparency and personalized support as we help your business grow with confidence.
                        </p>
                        <a href="#" class="btn-read-more">
                            READ MORE <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>

                    <!-- Right Column (Features Grid) -->
                    <div class="col-lg-7">
                        <div class="row">
                            <!-- English speakers -->
                            <div class="col-sm-6 mb-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <img src="{{asset('assets/landing/images/matter-section/Service_2.png')}}"
                                            alt="English speakers">
                                    </div>
                                    <h4 class="feature-title">English speakers</h4>
                                    <p class="feature-text">
                                        English is important to communicate most of our people english is our 1st language.
                                    </p>
                                </div>
                            </div>

                            <!-- Reliable, honesty, trust -->
                            <div class="col-sm-6 mb-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <img src="{{asset('assets/landing/images/matter-section/diamonds.svg')}}"
                                            alt="Reliable">
                                    </div>
                                    <h4 class="feature-title">Reliable, honesty, trust</h4>
                                    <p class="feature-text">
                                        We prioritize honesty, trust and integrity making us reliable
                                    </p>
                                </div>
                            </div>

                            <!-- Clear and Honest Pricing -->
                            <div class="col-sm-6 mb-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <img src="{{asset('assets/landing/images/matter-section/dollar-square.svg')}}"
                                            alt="Pricing">
                                    </div>
                                    <h4 class="feature-title">Clear and Honest Pricing</h4>
                                    <p class="feature-text">
                                        Our commitment to honesty that you understand what you are make informed decisions.
                                    </p>
                                </div>
                            </div>

                            <!-- Professional Inspection -->
                            <div class="col-sm-6 mb-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <img src="{{asset('assets/landing/images/matter-section/car.svg')}}"
                                            alt="Inspection">
                                    </div>
                                    <h4 class="feature-title">Professional Inspection</h4>
                                    <p class="feature-text">
                                        Experianced staff some who are mechanics from previous lifes are dedicated who know
                                        cars
                                        inside and out.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section Start -->
    <section id="who-we-are">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Column (Content) -->
                <div class="col-lg-6">
                    <h2 class="who-title">Who We Are</h2>
                    <p class="who-desc">
                        Welcome to International Auto Select Japan (IAS). Since the early 1990s, IAS has grown through close
                        collaborations with partners, consistently upholding principles of honesty, integrity, and
                        commitment. Over the years, this approach has allowed us to gain valuable experience and specialized
                        insight into the JDM market. Through hard work and a focus on building strong relationships, we have
                        established ourselves as a reliable resource in the industry. Our expertise enables us to navigate
                        the complexities of the JDM market effectively, providing dependable solutions for our clients. At
                        IAS, we continue to adapt to the changing needs of the industry while staying true to the values
                        that have shaped our journey.
                    </p>

                    <div class="who-points">
                        <div class="who-point">
                            <div class="who-point-icon">
                                <i class='bx bx-check'></i>
                            </div>
                            <p class="who-point-text">
                                Our dedicated team provides personalized support, handling all aspects of the import and
                                export process, including shipping logistics and documentation.
                            </p>
                        </div>
                        <div class="who-point">
                            <div class="who-point-icon">
                                <i class='bx bx-check'></i>
                            </div>
                            <p class="who-point-text">
                                At IAS Japan, we strive to deliver a seamless and trustworthy car buying experience, making
                                us your trusted partner in the global automotive market.
                            </p>
                        </div>
                    </div>

                    <a href="#" class="btn-read-more">
                        READ MORE <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>

                <!-- Right Column (Images) -->
                <div class="col-lg-6">
                    <div class="who-img-area">
                        <img src="{{asset('assets/landing/images/who/left-img-1.png')}}" alt="Who We Are" class="img-fluid">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Testimonial Section Start -->
    {{-- <section class="testimonial-section sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <h2 class="text-center">Happy Customer</h2>--}}
            {{-- </div>--}}
        {{-- <div class="rid-testimonial">--}}
            {{-- <div class="container">--}}
                {{-- <div class="rid-slider-one-col">--}}
                    {{-- <div class="rid-single-testimonial">--}}
                        {{-- <div class="row">--}}
                            {{-- <div class="col-lg-5 col-md-5">--}}
                                {{-- <div class="testimonial-photo">--}}
                                    {{-- <img src="images/testimonial/testimonial-01.jpg" alt="Alaina Gillespy">--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}

                            {{-- <div class="col-lg-7 col-md-7 align-items-center">--}}
                                {{-- <div class="testimonial-info">--}}
                                    {{-- <h4 class="testimonial-name">Backey Tompson</h4>--}}
                                    {{-- <p class="testimonial-designation">Ceo of Atardam</p>--}}

                                    {{-- <p class="rid-test-desc">Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio--}}
                                        {{-- in--}}
                                        {{-- the find it per odiy.Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio--}}
                                        {{-- in--}}
                                        {{-- the find it per odiy.Maboriosam in a tloctio in the find it per odiy.</p>--}}
                                    {{-- <div class="rid-rating">--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- </div>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- <div class="rid-single-testimonial">--}}
                        {{-- <div class="row">--}}
                            {{-- <div class="col-lg-5 col-md-5">--}}
                                {{-- <div class="testimonial-photo">--}}
                                    {{-- <img src="images/testimonial/testimonial-02.jpg" alt="Lucas Stuart">--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}

                            {{-- <div class="col-lg-7 col-md-7">--}}
                                {{-- <div class="testimonial-info">--}}
                                    {{-- <h4 class="testimonial-name">Lucas Stuart</h4>--}}
                                    {{-- <p class="testimonial-designation">Director</p>--}}

                                    {{-- <p class="rid-test-desc">Lorem ipsum dolor sit amet, ducimus eveniet explicabo--}}
                                        {{-- consectetur adipisicing elit. Dolorem ducimus eveniet explicabo in ipsum--}}
                                        {{-- necessitatibus, nisi numquam provident quas quos reiciendis saepe sequi
                                        sunt.--}}
                                        {{-- Adipisci doloribus.</p>--}}
                                    {{-- <div class="rid-rating">--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- </div>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- <div class="rid-single-testimonial">--}}
                        {{-- <div class="row">--}}
                            {{-- <div class="col-lg-5 col-md-5">--}}
                                {{-- <div class="testimonial-photo">--}}
                                    {{-- <img src="images/testimonial/testimonial-03.jpg" alt="Himika Rex">--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}

                            {{-- <div class="col-lg-7 col-md-7">--}}
                                {{-- <div class="testimonial-info">--}}
                                    {{-- <h4 class="testimonial-name">Himika Rex</h4>--}}
                                    {{-- <p class="testimonial-designation">Manager</p>--}}

                                    {{-- <p class="rid-test-desc">Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio--}}
                                        {{-- in--}}
                                        {{-- the find it per odiy.Maboriosam in a nesciung eget magna dapibus disting
                                        tloctio--}}
                                        {{-- in--}}
                                        {{-- the find it per odiy.Maboriosam in a tloctio in the find it per odiy.</p>--}}
                                    {{-- <div class="rid-rating">--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- <i class="icofont-star"></i>--}}
                                        {{-- </div>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    <!-- Testimonial Section End -->


    {{-- Temp Our Gallery Section comment because client want--}}
    <!-- Our Gallery Section Start -->
    {{-- @if(!$gallery->isEmpty())--}}
    {{-- <section class="rid-gallery sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <h2 class="text-center">Our Gallery</h2>--}}

            {{-- <!-- Gallery Start -->--}}
            {{-- <div class="row">--}}
                {{-- @php--}}
                {{-- // Split gallery items into 3 columns--}}
                {{-- $column1 = [];--}}
                {{-- $column2 = [];--}}
                {{-- $column3 = [];--}}
                {{-- $count = 0;--}}

                {{-- foreach($gallery as $galleryDetail) {--}}
                {{-- if ($count % 3 == 0) {--}}
                {{-- $column1[] = $galleryDetail;--}}
                {{-- } elseif ($count % 3 == 1) {--}}
                {{-- $column2[] = $galleryDetail;--}}
                {{-- } else {--}}
                {{-- $column3[] = $galleryDetail;--}}
                {{-- }--}}
                {{-- $count++;--}}
                {{-- }--}}
                {{-- @endphp--}}

                {{-- <!-- Column 1 -->--}}
                {{-- <div class="col-lg-4 col-md-6" id="image-popups">--}}
                    {{-- @foreach($column1 as $item)--}}
                    {{-- <div class="rid-gallery-single popup-gallery">--}}
                        {{-- <a href="{{ $item->image }}" class="gallery-img rid-popup-link" --}} {{--
                            data-effect="mfp-zoom-in" title="{{ $item->title }}">--}}
                            {{-- <img src="{{ $item->image }}" alt="{{ $item->title }}" class="width-img">--}}
                            {{-- </a>--}}
                        {{-- </div>--}}
                    {{-- @endforeach--}}
                    {{-- </div>--}}

                {{-- <!-- Column 2 -->--}}
                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- @foreach($column2 as $item)--}}
                    {{-- <div class="rid-gallery-single popup-gallery">--}}
                        {{-- <a href="{{ $item->image }}" class="gallery-img rid-popup-link" --}} {{--
                            data-effect="mfp-zoom-in" title="{{ $item->title }}">--}}
                            {{-- <img src="{{ $item->image }}" alt="{{ $item->title }}" class="width-img">--}}
                            {{-- </a>--}}
                        {{-- </div>--}}
                    {{-- @endforeach--}}
                    {{-- </div>--}}

                {{-- <!-- Column 3 -->--}}
                {{-- <div class="col-lg-4">--}}
                    {{-- @foreach($column3 as $item)--}}
                    {{-- <div class="rid-gallery-single popup-gallery">--}}
                        {{-- <a href="{{ $item->image }}" class="gallery-img rid-popup-link" --}} {{--
                            data-effect="mfp-zoom-in" title="{{ $item->title }}">--}}
                            {{-- <img src="{{ $item->image }}" alt="{{ $item->title }}" class="width-img">--}}
                            {{-- </a>--}}
                        {{-- </div>--}}
                    {{-- @endforeach--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    {{-- @endif--}}
    <!-- Our Gallery Section End -->

    <!-- Video Section Start -->
    {{-- <section class="rid-video-1">--}}
        {{-- <div class="video-banner">--}}
            {{-- <div class="container">--}}
                {{-- <h2 class="visually-hidden">Video</h2>--}}
                {{-- <div class="rid-video-play text-center">--}}
                    {{-- <a class="popup-youtube" href="https://www.youtube.com/watch?v=FdrNFEbcsRs">--}}
                        {{-- <i class="flaticon-play"></i>--}}
                        {{-- </a>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    <!-- Video Section End -->

    <!-- Our Bike Section Start -->
    <section class="rid-our-bike sec-space">
        <div class="container">
            <h2 class="text-center">Why Us?</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content pe-4">
                        <h3>Discover Japan from the Best Starting Point</h3>
                        <p>We are a motorcycle rental company situated in the second city in Japan - Osaka. We have very
                            quick and easy access to much more than Tokyo. Osaka city itself has a wide variety of
                            foods, is famous for its friendly and down-to-earth people, is less crowded but at the same
                            time has all the shopping and entertainment facilities you'd expect. A little further away
                            is stylish Kobe city, then historic Kyoto and Nara - 4 great cities all within an hour.</p>

                        <h3 class="mt-4">The Perfect Base for Your Motorcycle Adventure</h3>
                        <p>But wait, you're here to ride bikes right? Well what better position in Japan to start
                            touring than here in Osaka? Our shop location is minutes away from Suita interchange, with
                            access to 3 major highways heading wherever you want. You can be on a country road in about
                            20 minutes from pick up!</p>
                        <p>Head North to Kyoto/Hyogo Prefectures for rugged coastline and secluded beaches, East toward
                            Lake Biwa, then Suzuka a little further away, with fantastic country roads everywhere. Then
                            perhaps South to Wakayama prefecture for the great fishing heritage and slightly more
                            popular beaches. Then West, the best way, stay on the mainland toward Okayama, Tottori,
                            Yamaguchi - all much less crowded with fantastic touring roads, or perhaps to the Island of
                            Shikoku - awesome countryside, small cities, fantastic food, castles, and temples.</p>
                        <p>Then even further afield you have Kyushu, another fantastically different area famed for hot
                            springs, volcanoes and even more food!</p>

                        <h3 class="mt-4">Our Commitment to You</h3>
                        <p>We have a wide range of high quality, professionally maintained motorcycles available for
                            rent at reasonable prices, with a good range of accessories if you are travelling light.
                            Please do have a look through our website - any questions please don't hesitate to ask. Why
                            not be different and start your tour from Osaka!</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="map-container mb-4">
                        <div class="text-center">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d819.1187075672343!2d135.54499482924905!3d34.793993998775676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDQ3JzM4LjQiTiAxMzXCsDMyJzQ0LjAiRQ!5e0!3m2!1sen!2sjp!4v1538629279601"
                                width="400" height="450" frameborder="0" style="border:0; width: 100%;"
                                allowfullscreen></iframe>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">
                                {{-- <div class="rid-info-box-icon position-relative">--}}
                                    {{-- <i class="flaticon-history"></i>--}}
                                    {{-- </div>--}}
                                <div class="rid-info-box-text">
                                    <h4>Immediate Booking</h4>
                                    <p>Real-time availability with instant online booking.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">
                                {{-- <div class="rid-info-box-icon position-relative">--}}
                                    {{-- <i class="flaticon-globe"></i>--}}
                                    {{-- </div>--}}
                                <div class="rid-info-box-text">
                                    <h4>Flexible Changes</h4>
                                    <p>Change dates or cancel without extra fees.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">
                                {{-- <div class="rid-info-box-icon position-relative">--}}
                                    {{-- <i class="flaticon-refund"></i>--}}
                                    {{-- </div>--}}
                                <div class="rid-info-box-text">
                                    <h4>Best Price Guarantee</h4>
                                    <p>You won't find better prices for the same quality.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">
                                {{-- <div class="rid-info-box-icon position-relative">--}}
                                    {{-- <i class="flaticon-call-center"></i>--}}
                                    {{-- </div>--}}
                                <div class="rid-info-box-text">
                                    <h4>24/7 Support</h4>
                                    <p>Multilingual assistance throughout your journey.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Bike Section End -->
    {{-- temp comment because client want--}}
    {{-- <section class="rid-faq sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <!-- FAQ Title -->--}}
            {{-- <h2 class="text-center">Frequently Asked Questions</h2>--}}
            {{-- <!-- FAQ  -->--}}
            {{-- <div class="row">--}}
                {{-- <div class="col-lg-6">--}}
                    {{-- <div class="rid-accordion" id="faqWrap">--}}

                        {{-- @foreach($faqs as $i => $faq)--}}
                        {{-- <div class="accordion-item position-relative">--}}

                            {{-- <h6 class="accordion-header" id="heading{{ $i }}">--}}
                                {{-- <button class="rid-accordion-btn {{ $i == 0 ? '' : 'collapsed' }}" --}} {{--
                                    type="button" --}} {{-- data-bs-toggle="collapse" --}} {{--
                                    data-bs-target="#collapse{{ $i }}" --}} {{--
                                    aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" --}} {{--
                                    aria-controls="collapse{{ $i }}">--}}
                                    {{-- {{ $faq->key }}--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}

                            {{-- <div id="collapse{{ $i }}" --}} {{--
                                class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" --}} {{--
                                aria-labelledby="heading{{ $i }}" --}} {{-- data-bs-parent="#faqWrap">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- {!! $faq->value !!}--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}

                            {{-- </div>--}}
                        {{-- @endforeach--}}

                        {{-- </div>--}}

                    {{-- </div>--}}

                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}

    <!-- FAQ Section Start -->
    {{-- <section class="rid-faq sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <!-- FAQ Title -->--}}
            {{-- <h2 class="text-center">Frequently Asked Questions</h2>--}}
            {{-- <!-- FAQ  -->--}}
            {{-- <div class="row">--}}
                {{-- <div class="col-lg-6">--}}
                    {{-- <div class="rid-accordion" id="faqWrap1">--}}
                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingOne">--}}
                                {{-- <button class="rid-accordion-btn" type="button" data-bs-toggle="collapse" --}} {{--
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
                                    {{-- Can I rent a motorcycle in Japan?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                --}} {{-- data-bs-parent="#faqWrap1">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>You need to be a minimum of 20 years of age with a valid Japanese license or
                                        a--}}
                                        {{-- valid international drivers license with the motorcycle endorsement
                                        applicable--}}
                                        {{-- for the bike you want to rent issued by a country listed on our license--}}
                                        {{-- requirement page (<a href="https://bikerentaljapan.com/license">please see--}}
                                            {{-- here.</a>) You must have a credit card, a residents card or passport if
                                        not--}}
                                        {{-- a resident.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingTwo">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">--}}
                                    {{-- How much am I insured for?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" --}}
                                {{-- data-bs-parent="#faqWrap1">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>All our motorcycles are provided with insurance as stipulated for rental
                                        vehicles--}}
                                        {{-- by law.<br>--}}
                                        {{-- Third part injury: Unlimited<br>--}}
                                        {{-- Third Party Property: Unlimited<br>--}}
                                        {{-- Personal injury: 80,000,000 yen<br>--}}
                                        {{-- Personal Property: 2,000,000yen<br>--}}
                                        {{-- Passenger: 5,000,000 yen<br>--}}
                                        {{-- An excess of 300,000yen is payable on all claims. For mopeds or vehicles
                                        from--}}
                                        {{-- 51cc-250cc, only personal unrestricted damages are covered. Damages that
                                        exceed--}}
                                        {{-- the amount covered by the insurance policy is assumed by the renter. The
                                        vehicle--}}
                                        {{-- insurance doesn't cover this and is unrestricted.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingThree">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseThree" aria-expanded="false" --}} {{--
                                    aria-controls="collapseThree">--}}
                                    {{-- How much is the excess?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                --}} {{-- data-bs-parent="#faqWrap1">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>Any damage whatsoever regardless of blame is assumed by the renter and is up
                                        to--}}
                                        {{-- the value of the vehicle rented. If our excess reduction insurance (ERI) is--}}
                                        {{-- purchased as part of the contract then the maximum excess for any damage is--}}
                                        {{-- 300,000yen.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingFour">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseFour" aria-expanded="false" --}} {{--
                                    aria-controls="collapseFour">--}}
                                    {{-- How's the weather in Japan?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                --}} {{-- data-bs-parent="#faqWrap1">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>Japan has an extremely varying climate depending on where you are, but
                                        basically--}}
                                        {{-- cold in winter 0c and up to 40c in summer. Mid June to July is the rainy
                                        season,--}}
                                        {{-- not too bad for riding as it is not permanent rain however you will need
                                        wet--}}
                                        {{-- weather gear. July and august are hot and humid, but feels good on a bike,
                                        just--}}
                                        {{-- don't stop too long! By far the best seasons for riding are March to June,
                                        the--}}
                                        {{-- September to December.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- <div class="col-lg-6">--}}
                    {{-- <div class="rid-accordion" id="faqWrap2">--}}
                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingFive">--}}
                                {{-- <button class="rid-accordion-btn" type="button" data-bs-toggle="collapse" --}} {{--
                                    data-bs-target="#collapseFive" aria-expanded="true" --}} {{--
                                    aria-controls="collapseFive">--}}
                                    {{-- Is it difficult to ride in Japan?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                --}} {{-- data-bs-parent="#faqWrap2">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>Yes and no! If you come from the UK, Australia or New Zealand etc, then it
                                        is--}}
                                        {{-- very similar as you drive on the left here, however there are very few--}}
                                        {{-- roundabouts and more traffic lights like in the US. There are also some
                                        stop--}}
                                        {{-- intersections which are also like in the US but not in the UK. There are no--}}
                                        {{-- strange rules luckily and is reasonably straightforward with common sense.
                                        <a--}} {{-- href="https://bikerentaljapan.com/safely">Please see our video</a>
                                            on--}}
                                            {{-- how to ride safely in Japan.
                                    </p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingSix">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseSix" aria-expanded="false"
                                    aria-controls="collapseSix">--}}
                                    {{-- How do I navigate in Japan?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" --}}
                                {{-- data-bs-parent="#faqWrap2">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>--}}
                                        {{-- <a
                                            href="https://play.google.com/store/apps/details?id=com.google.android.apps.maps"
                                            --}} {{-- target="_blank">Google maps(ANDROID)</a> and <a--}} {{--
                                            href="https://itunes.apple.com/app/id585027354" target="_blank">Google--}}
                                            {{-- maps(IOS)</a> (requires internet connection) or <a href="https://maps.me/"
                                                --}} {{-- target="_blank">Maps.me</a>--}}
                                            {{-- (doesn't require internet connection, specific maps for your route are--}}
                                            {{-- downloadable). All locations can be searched in English. For our self
                                            guided--}}
                                            {{-- tours we ask that the customer downloads <a href="https://maps.me/" --}}
                                                {{-- target="_blank">Maps.me</a> with--}}
                                            {{-- complete maps for Japan or the area you will be visiting onto their phones.
                                            We--}}
                                            {{-- also give advice on places to stay and camp grounds etc.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingSeven">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseSeven" aria-expanded="false" --}} {{--
                                    aria-controls="collapseSeven">--}}
                                    {{-- Do I need to bring my own helmet and gear?--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                                --}} {{-- data-bs-parent="#faqWrap2">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p>Although we do have helmets and gear to rent we highly recommend you bring
                                        your--}}
                                        {{-- own gear, especially a helmet. Helmets are a very personal thing, often--}}
                                        {{-- different brands fit differently, sizing can be difficult however we will do
                                        our--}}
                                        {{-- best to supply the best possible helmet if you can't bring your own.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}

                        {{-- <div class="accordion-item position-relative">--}}
                            {{-- <h6 class="accordion-header" id="headingEight">--}}
                                {{-- <button class="rid-accordion-btn collapsed" type="button" data-bs-toggle="collapse"
                                    --}} {{-- data-bs-target="#collapseEight" aria-expanded="false" --}} {{--
                                    aria-controls="collapseEight">--}}
                                    {{-- Useful Links--}}
                                    {{-- </button>--}}
                                {{-- </h6>--}}
                            {{-- <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                                --}} {{-- data-bs-parent="#faqWrap2">--}}
                                {{-- <div class="accordion-body">--}}
                                    {{-- <p><strong><u><a href="https://origami-book.com/column/course-en/7384" --}} {{--
                                                    target="_blank">Road Use in Japan</a></u></strong><br>--}}
                                        {{-- This site has an amazing amount of information about all aspects of driving
                                        in--}}
                                        {{-- Japan, a must read if this is your first time motoring here.</p>--}}

                                    {{-- <p><strong><u><a href="https://www.xe.com/" target="_blank">Currency
                                                    Conversion</a></u></strong><br>--}}
                                        {{-- All rates found on our website are in Japanese Yen (JPY). XE currency
                                        conversion--}}
                                        {{-- is an easy to use website that can be used to convert our rates to your
                                        local--}}
                                        {{-- currency.</p>--}}

                                    {{-- <p><a href="https://drive.google.com/open?id=1MnuZll_ke0IUo_gQwtCHWwIlifB9vaBT"
                                            --}} {{-- target="_blank"><u><strong>Motorcycle Trip Packing
                                                    List</strong></u></a><br>--}}
                                        {{-- We expect you've been planning this for a long time and have it covered,
                                        but--}}
                                        {{-- please do take a look at this list just to make sure nothing has slipped
                                        your--}}
                                        {{-- mind.</p>--}}

                                    {{-- <p><strong><a href="https://www.japan.travel/en/destinations/kansai/" --}} {{--
                                                target="_blank">JNTO-Destination Kansai</a></strong><br>--}}
                                        {{-- Kansai and the surrounding areas are an amazing, beautiful and interesting--}}
                                        {{-- place. We have made up some great tours however there is always much more to
                                        see--}}
                                        {{-- that have nothing to do with bikes so please have a look and ride there
                                        anyway!--}}
                                        {{-- </p>--}}

                                    {{-- <p><strong><a href="https://www.japan-guide.com/e/e2157.html"
                                                target="_blank">Japan--}}
                                                {{-- Guide-Osaka</a></strong><br>--}}
                                        {{-- Osaka is a unique, lively and interesting city with plenty to offer, more
                                        than--}}
                                        {{-- we can tell anyone so please check here for yourself.</p>--}}

                                    {{-- <p><a href="https://www.holiday-weather.com/osaka/"
                                            target="_blank"><strong>Osaka--}}
                                                {{-- Weather</strong></a><br>--}}
                                        {{-- Japan has one of the most variable climates in the world with a possible
                                        50°c--}}
                                        {{-- difference between summer and winter, with a rainy and typhoon season. This
                                        site--}}
                                        {{-- can help you plan your trip for your preferred weather.</p>--}}
                                    {{-- </div>--}}
                                {{-- </div>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    <!-- FAQ Section End -->

    <!-- Post Section Start -->
    {{-- <section class="rid-posts-1 sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <!-- Rid Post Title -->--}}
            {{-- <h2 class="text-center">Latest Posts</h2>--}}
            {{-- <!-- Rid Post Section -->--}}
            {{-- <div class="row">--}}
                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-01.jpg" alt="Bike lovers deserve better">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}
                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>Bike lovers deserve better</h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Tristique donec sociosqu molestie eleifend donec! Luctus! Eros maxime molestiae.
                                Vero,--}}
                                {{-- officiapl corpent.</p>--}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-02.jpg" alt="Enjoy with Roads">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}

                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>Enjoy with Roads</h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Sollicitudin suscipit penatibus leo vero venenatis ipsam occaecati? Irure facilis.</p>
                            --}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-03.jpg" alt="Choose your Favorite Bike">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}

                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}
                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>Choose your Favorite Bike </h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Cumque magnam, distinctio class facilisi deleniti! Eos ea sociosqu sit assumenda
                                elit--}}
                                {{-- deleniti.</p>--}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-04.jpg" alt="KTM 2021 RC 125">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}
                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>KTM 2021 RC 125</h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Sollicitudin suscipit laboriosam felis penatibus leo vero venenatis ipsam occaecati?--}}
                                {{-- Irure--}}
                                {{-- facilis.</p>--}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-05.jpg" alt="The Bike is a Half Car">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}

                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}
                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>The Bike is a Half Car</h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Conubia libero, proin recusandae cursus dicta labore ratione unde adipisci proin
                                aperiam--}}
                                {{-- mollitia.</p>--}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-4 col-md-6">--}}
                    {{-- <div class="rid-posts-single">--}}
                        {{-- <div class="rid-post-img">--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <img src="images/post/post-06.jpg" alt="Wise Rent with no Dents">--}}
                                {{-- </a>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-post-info">--}}
                            {{-- <div class="post-meta">--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-user"></i>--}}
                                    {{-- <span>By James Smith</span>--}}
                                    {{-- </a>--}}
                                {{-- <a href="blog-details.html">--}}
                                    {{-- <i class="flaticon-folder"></i>--}}
                                    {{-- <span>Motorcycle</span>--}}
                                    {{-- </a>--}}
                                {{-- </div>--}}
                            {{-- <a href="blog-details.html">--}}
                                {{-- <h4>Wise Rent with no Dents</h4>--}}
                                {{-- </a>--}}
                            {{-- <p>Perferendis magnam minus viverra! Dictum ex culpa officiis deleniti, assumenda.</p>--}}
                            {{-- <a class="btn rid-post-btn" href="blog-details.html">Read More</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    <!-- Post Section End -->

    <!-- Statistics Section Start -->
    {{-- temp comment becuase client want--}}
    {{-- <section class="rid-statistics sec-space">--}}
        {{-- <div class="container">--}}
            {{-- <div class="row">--}}
                {{-- <div class="col-lg-3 col-md-6 col-sm-6">--}}
                    {{-- <div class="rid-counter d-sm-flex align-items-center">--}}
                        {{-- <div class="rid-counter-icon">--}}
                            {{-- <i class="ri-whatsapp-fill"></i>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-counter-text">--}}

                            {{-- <a href="https://www.whatsapp.com/" class="h4 text-black fw-bold"
                                target="_blank">Whatsapp</a>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-3 col-md-6 col-sm-6">--}}
                    {{-- <div class="rid-counter d-sm-flex align-items-center">--}}
                        {{-- <div class="rid-counter-icon">--}}
                            {{-- <i class="ri-wallet-2-fill"></i>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-counter-text">--}}
                            {{-- <h4 class="">Insurance deductables</h4>--}}
                            {{-- <span>Set Limits on accidents</span>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-3 col-md-6 col-sm-6">--}}
                    {{-- <div class="rid-counter d-sm-flex align-items-center">--}}
                        {{-- <div class="rid-counter-icon">--}}
                            {{-- <i class="ri-phone-fill"></i>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-counter-text">--}}
                            {{-- <h4 class="">CALL US</h4>--}}
                            {{-- <span>Intl Phone: +81 6 4864 2081</span>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}

                {{-- <div class="col-lg-3 col-md-6 col-sm-6">--}}
                    {{-- <div class="rid-counter d-sm-flex align-items-center">--}}
                        {{-- <div class="rid-counter-icon">--}}
                            {{-- <i class="ri-chat-1-fill"></i>--}}
                            {{-- </div>--}}
                        {{-- <div class="rid-counter-text">--}}
                            {{-- <h4 class="">ONLINE SUPPORT</h4>--}}
                            {{-- <span class="text-decoration-underline"><a href="https://m.me/bikerentaljapan" --}} {{--
                                    target="_blank">Via facebook messenger</a></span>--}}
                            {{-- </div>--}}
                        {{-- </div>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- </div>--}}
        {{-- </section>--}}
    <!-- Statistics Section End -->


@endsection
@section('script')
    <script>
        $(document).on('click', '.btncheckout', function () {
            let slug = $(this).data('slug');
            window.location.href = "/motorcycle/" + slug;
        });
        document.addEventListener('DOMContentLoaded', () => {
            const sliderContainer = document.querySelector('.slider-container');
            const slides = document.querySelectorAll('.slide');
            const prevButton = document.querySelector('.prev-button');
            const nextButton = document.querySelector('.next-button');
            const navDotsContainer = document.querySelector('.nav-dots');

            const imageArray = "{{ $sliderImages }}";
            const decoded = imageArray.replace(/&quot;/g, '"');
            const allImages = JSON.parse(decoded);

            const totalImages = allImages.length;
            let currentImageIndex = 0;
            let autoScrollInterval;
            let slideIndexToUpdate = 0;
            const transitionDuration = 1200;

            // ✅ Responsive slide count
            const getVisibleSlides = () => {
                if (window.innerWidth < 768) return 1;     // Mobile
                if (window.innerWidth < 1024) return 2;    // Tablet
                return 3;                                  // Desktop
            };

            let visibleSlides = getVisibleSlides();

            window.addEventListener("resize", () => {
                visibleSlides = getVisibleSlides();
                updateFullSet(currentImageIndex);
            });

            const updateSlideContent = (slideElement, imageUrl, isInitial = false) => {
                const oldImgContainer = slideElement.querySelector('.slide-img-container');
                const newImgContainer = document.createElement('div');
                newImgContainer.classList.add('slide-img-container');
                const newImg = document.createElement('img');
                newImg.src = imageUrl;
                newImg.alt = "Carousel Image";

                newImgContainer.appendChild(newImg);
                slideElement.appendChild(newImgContainer);

                if (isInitial) {
                    newImgContainer.classList.add('visible');
                    return;
                }

                if (oldImgContainer) {
                    oldImgContainer.classList.remove('visible');
                    setTimeout(() => oldImgContainer.remove(), transitionDuration);
                }
                setTimeout(() => newImgContainer.classList.add('visible'), 50);
            };

            const updateFullSet = (startIndex) => {
                slides.forEach((slide, index) => {
                    if (index < visibleSlides) {
                        slide.style.display = "block"; // show only required slides
                        const imageUrl = allImages[(startIndex + index) % totalImages];
                        updateSlideContent(slide, imageUrl, true);
                    } else {
                        slide.style.display = "none"; // hide extra slides
                    }
                });
                updateDots();
            };

            const updateDots = () => {
                const dots = document.querySelectorAll('.nav-dots .dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentImageIndex);
                });
            };

            const createDots = () => {
                navDotsContainer.innerHTML = '';
                for (let i = 0; i < totalImages; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('dot');
                    dot.addEventListener('click', () => {
                        currentImageIndex = i;
                        updateFullSet(currentImageIndex);
                        resetAutoScroll();
                    });
                    navDotsContainer.appendChild(dot);
                }
                updateDots();
            };

            const nextImage = () => {
                currentImageIndex = (currentImageIndex + visibleSlides) % totalImages;
                updateFullSet(currentImageIndex);
            };

            const prevImage = () => {
                currentImageIndex = (currentImageIndex - visibleSlides + totalImages) % totalImages;
                updateFullSet(currentImageIndex);
            };

            const autoScrollNext = () => {
                const slideToUpdate = slides[slideIndexToUpdate];
                const newImageIndex = (currentImageIndex + slideIndexToUpdate + visibleSlides) % totalImages;
                const imageUrl = allImages[newImageIndex];

                updateSlideContent(slideToUpdate, imageUrl);

                slideIndexToUpdate = (slideIndexToUpdate + 1) % visibleSlides;
                if (slideIndexToUpdate === 0) {
                    currentImageIndex = (currentImageIndex + visibleSlides) % totalImages;
                    updateDots();
                }
            };

            const startAutoScroll = () => {
                stopAutoScroll();
                autoScrollInterval = setInterval(autoScrollNext, 4200);
            };

            const stopAutoScroll = () => {
                clearInterval(autoScrollInterval);
            };

            const resetAutoScroll = () => {
                stopAutoScroll();
                startAutoScroll();
            };

            prevButton.addEventListener('click', () => {
                prevImage();
                resetAutoScroll();
            });

            nextButton.addEventListener('click', () => {
                nextImage();
                resetAutoScroll();
            });

            sliderContainer.addEventListener('mouseenter', stopAutoScroll);
            sliderContainer.addEventListener('mouseleave', startAutoScroll);

            createDots();
            updateFullSet(currentImageIndex);
            startAutoScroll();
        });

    </script>
@endsection