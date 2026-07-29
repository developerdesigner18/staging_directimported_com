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

        /* Cars Grid */
        /* ===== Card ===== */
        .car-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .04);
            transition: all .3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
        }

        /*.car-card:hover {*/
        /*    box-shadow: 0 24px 40px rgba(0,0,0,.15);*/
        /*    transform: translateY(-6px);*/
        /*}*/

        /* ===== Image ===== */
        .car-card-img-wrapper {
            position: relative;
            height: 260px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .car-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .car-card:hover img {
            transform: scale(1.06);
        }

        /* ===== Tag ===== */
        .car-tag {
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
        .car-card-body {
            padding: 24px 20px 28px;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* REMOVE side-by-side layout */
        .car-info-row {
            display: block;
        }

        .car-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .car-info-row h5 a {
            text-decoration: none;
            color: inherit;
        }


        .car-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 22px;
            font-weight: 500;
        }

        /* ===== Price (centered like image 1) ===== */
        .car-price-block {
            display: flex;
            align-items: baseline;
            justify-content: center;
            margin-bottom: 16px;
            gap: 9px;
        }


        .car-price-block .price-amount {
            color: #991b1b;
            font-size: 30px;
            font-weight: 900;
            line-height: 1;
        }

        .car-price-block .price-from,
        .car-price-block .price-per {
            color: #991b1b;
            font-size: 28px;
            font-weight: 700;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .section-title h2 {
            display: flex;
            align-items: center;
            font-size: 32px;
            font-weight: 700;
            color: #000B21;
            margin-bottom: 0;
        }

        .section-title h2::before {
            content: "";
            display: inline-block;
            width: 30px;
            height: 2px;
            background-color: #000B21;
            margin-right: 15px;
        }

        /* =========================
   How It Works
========================= */

.rid-how-it-work {
    padding: 80px 0;
    background: #f9fafb;
}

.work-card {
    background: #fff;
    padding: 40px 25px;
    border-radius: 18px;
    height: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.work-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
}

.work-icon {
    width: 90px;
    height: 90px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: linear-gradient(135deg, #053C7C 0%, #042B59 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.work-icon i {
    color: #fff;
    font-size: 40px;
}

.work-card h4 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #111827;
}

.work-card p {
    font-size: 15px;
    line-height: 1.7;
    color: #6b7280;
    margin: 0;
}
        /* ===== EMBLEM ROW ===== */
        .car-emblem-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            align-items: flex-start;
            margin-bottom: 22px;
            padding: 12px 0;
            border: none;
            gap: 4px;
        }

        .emblem-item {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            overflow: hidden;
        }

        .emblem-item i,
        .emblem-item svg {
            display: block;
            width: 20px;
            height: 20px;
            font-size: 20px;
            color: #111827;
            margin-bottom: 2px;
        }

        .emblem-item span {
            font-size: 9px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.1px;
            white-space: normal;
            line-height: 1.2;
            word-break: normal;
        }

        .btn-adventure {
            width: 100%;
            background: linear-gradient(135deg, #053C7C 0%, #042B59 100%);
            color: #fff;
            border: none;
            padding: 14px 18px;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 8px;
            cursor: pointer;
            transition: all .3s ease;
        }

        .btn-adventure:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(5, 60, 124, 0.2);
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



        .top-20vh {
            top: 20vh;
        }
    </style>
@endpush
@section('main')

    <!-- Banner Section Start -->

    @if(!$sliders->isEmpty())


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
                <a href="http://127.0.0.1:8000/car" class="cta-btn">
                    Search auctions live here
                </a>
            </div>
        </div>
    </section>
    <!-- Filter/CTA Section End -->

    <!-- Latest Cars Section Start -->
    @if(!$cars->isEmpty())
        <div class="rid-rentals-1 py-5">

            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="section-title">
                        <h2>Latest Cars</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach($cars as $car)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="car-card">

                                <!-- Image -->
                                <div class="car-card-img-wrapper">
                                    <a href="{{ route('car.single', ['slug' => $car->slug]) }}">
                                        @if(isset($car->images[0]))
                                            <img src="{{ asset(CAR_PATH . $car->images[0]) }}" alt="{{ $car->name }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('assets/landing/img/no-image.jpg') }}" alt="{{ $car->name }}" loading="lazy">
                                        @endif
                                    </a>
                                </div>

                                <!-- Body -->
                                <div class="car-card-body">

                                    <!-- Title -->
                                    <h4 class="car-title">
                                        <a href="{{ route('car.single', ['slug' => $car->slug]) }}">
                                            {{ $car->card_header ?? $car->name }}
                                        </a>
                                    </h4>

                                    <!-- Subtitle -->
                                    <div class="car-subtitle">
                                        {{ $car->card_subtitle ?? ($car->category->name ?? 'Premium Adventure Touring') }}
                                    </div>

                                    <div class="car-emblem-row">
                                        <!-- Year -->
                                        <div class="emblem-item">
                                            <i class='bx bx-calendar'></i>
                                            <span>{{ $car->year ?? '1994' }}</span>
                                        </div>

                                        <!-- Mileage -->
                                        <div class="emblem-item">
                                            <i class='bx bx-tachometer'></i>
                                            <span>{{ !empty($car->spec->odometer) ? number_format($car->spec->odometer) : (!empty($car->odometer) ? number_format($car->odometer) : '65,000') }}</span>
                                        </div>

                                        <!-- Fuel -->
                                        <div class="emblem-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/><path d="M15 11a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2"/><path d="M10 11H5"/><path d="M3 22h12"/></svg>
                                            <span>{{ !empty($car->spec->formatted_fuel_type) ? $car->spec->formatted_fuel_type : ($car->fuel_type ?? 'Hybrid/Petrol') }}</span>
                                        </div>

                                        <!-- Transmission -->
                                        <div class="emblem-item">
                                            <i class='bx bx-git-branch'></i>
                                            <span>{{ !empty($car->spec->formatted_transmission) ? $car->spec->formatted_transmission : ($car->transmission ?? '8 Speed') }}</span>
                                        </div>
                                    </div>

                                    <div style="margin-top: auto;">
                                        <button class="btn-adventure btncheckout" data-slug="{{ $car->slug }}"
                                            data-id="{{ $car->id }}" data-name="{{ $car->name }}"
                                            data-image="{{ isset($car->images[0]) ? asset(CAR_PATH . $car->images[0]) : '' }}">
                                            CHECK IT OUT
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('available.vehicles') }}" class="cta-btn text-white fw-bold d-inline-block px-5 py-3" style="text-decoration: none;">
                        View All Vehicles
                    </a>
                </div>

            </div>
        </div>
    @endif
    <!-- Latest Cars Section End -->

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

                @if($services->isNotEmpty())
                    <div class="row g-4">
                        @foreach ($services as $service)
                            <!-- Auction Inspection Services -->
                            <div class="col-lg-4 col-md-6">
                                <div class="service-card h-100">
                                    <div class="icon-wrapper">
                                        <img src="{{ isset($service->images[0]) ? asset(SERVICE_PATH . $service->images[0]) : asset('uploads/user_documents/default.jpg') }}"
                                            alt="{{ $service->title }}" loading="lazy">
                                    </div>
                                    <h4>{{ $service->title }}</h4>
                                    <p>
                                        {!! $service->description !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="text-end mt-5">
                        <a href="{{ route('services.view') }}" class="btn-read-more">
                            READ MORE <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">No services available right now.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- About IAS Japan Section Start -->
    @if($homeSection)
    <section id="about-ias">
        <div class="container">
            <div class="py-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title mb-5">
                            <h2>{{ $homeSection->title }}</h2>
                        </div>

                        <div class="mb-5" style="font-size: 16px; color: #6B7280; line-height: 1.8;">
                            {!! $homeSection->short_description !!}
                        </div>

                        @if($homeSection->points->isNotEmpty())
                        <div class="who-points mb-5">
                            @foreach($homeSection->points as $point)
                            <div class="who-point">
                                <div class="who-point-icon">
                                    <i class='bx bx-check'></i>
                                </div>
                                <p class="who-point-text">
                                    {{ $point->point_text }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <a href="#" class="btn-read-more">
                            READ MORE <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- About IAS Japan Section End -->


    <!--  How-it-work Section Start-->
    <!-- How It Works Section Start -->
<section class="rid-how-it-work">
    <div class="container">

        <div class="section-title text-center mb-5">
            <h2>How It Works</h2>
        </div>

        <div class="row g-4">

            <!-- Step 1 -->
            <div class="col-md-4">
                <div class="work-card text-center">
                    <div class="work-icon">
                        <i class="bx bx-search"></i>
                    </div>
                    <h4>Find the Right Car</h4>
                    <p>
                        Browse our wide range of quality vehicles and choose the perfect car for your needs.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-md-4">
                <div class="work-card text-center">
                    <div class="work-icon">
                        <i class="bx bx-cart"></i>
                    </div>
                    <h4>Buy It Online</h4>
                    <p>
                        Complete your purchase securely online with a smooth and hassle-free process.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-md-4">
                <div class="work-card text-center">
                    <div class="work-icon">
                        <i class="bx bx-car"></i>
                    </div>
                    <h4>Enjoy Your Ride</h4>
                    <p>
                        Get your vehicle delivered and enjoy a reliable driving experience with confidence.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- How It Works Section End -->
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
                                            alt="English speakers" loading="lazy">
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
                                            alt="Reliable" loading="lazy">
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
                                            alt="Pricing" loading="lazy">
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
                                            alt="Inspection" loading="lazy">
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
                        <img src="{{asset('assets/landing/images/who/left-img-1.png')}}" alt="Who We Are" class="img-fluid" loading="lazy">
                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- Our Car Section Start -->
    <section class="rid-our-car sec-space">
        <div class="container">
            <h2 class="text-center">Why Us?</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content pe-4">
                        <h3>Discover Japan from the Best Starting Point</h3>
                        <p>We are a car rental company situated in the second city in Japan - Osaka. We have very
                            quick and easy access to much more than Tokyo. Osaka city itself has a wide variety of
                            foods, is famous for its friendly and down-to-earth people, is less crowded but at the same
                            time has all the shopping and entertainment facilities you'd expect. A little further away
                            is stylish Kobe city, then historic Kyoto and Nara - 4 great cities all within an hour.</p>

                        <h3 class="mt-4">The Perfect Base for Your Car Adventure</h3>
                        <p>But wait, you're here to ride cars right? Well what better position in Japan to start
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
                        <p>We have a wide range of high quality, professionally maintained cars available for
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
                                    {{-- <i class="bx bx-history"></i>--}}
                                    {{-- </div>--}}
                                <div class="rid-info-box-text">
                                    <h4>Immediate Booking</h4>
                                    <p>Real-time availability with instant online booking.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">

                                <div class="rid-info-box-text">
                                    <h4>Flexible Changes</h4>
                                    <p>Change dates or cancel without extra fees.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">

                                <div class="rid-info-box-text">
                                    <h4>Best Price Guarantee</h4>
                                    <p>You won't find better prices for the same quality.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="rid-info-box-2 d-flex align-items-center mb-3">

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


@endsection
@section('script')
    <script>
        $(document).on('click', '.btncheckout', function () {
            let slug = $(this).data('slug');
            window.location.href = "/car/" + slug;
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
                newImg.loading = "lazy";

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