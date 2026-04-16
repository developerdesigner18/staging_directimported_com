@extends('landing.master')
@section('title', $bike->name)

@push('style')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1B447A;
            --secondary-blue: #111827;
            --text-main: #111827;
            --text-secondary: #4B5563;
            --bg-page: #F3F4F6;
            --accent-green: #D1FAE5;
            --accent-green-text: #10B981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: var(--text-main);
        }

        /* Responsive Header Adjustments */
        @media (max-width: 767px) {
            header .logo {
                display: none !important;
            }
        }

        .bike-detail-wrapper {
            padding: 50px 0;
        }

        /* Gallery */
        .main-image-container {
            position: relative;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid #E5E7EB;
        }

        .main-image-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent-green);
            color: #065F46;
            padding: 6px 15px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            z-index: 10;
        }

        .thumbs-slider-wrapper {
            position: relative;
            margin-bottom: 40px;
            padding: 0;
            overflow: visible;
        }

        .gallery-thumbs-slider .slick-slide {
            padding: 0 6px;
        }

        .thumb-box {
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            transition: 0.2s;
        }

        .thumb-box.active {
            border-color: var(--primary-blue);
        }

        .thumb-box img {
            width: 100%;
            height: 90px;
            object-fit: cover;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 20;
            border: 1px solid #E5E7EB;
            color: #111;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .slider-btn:hover {
            background: #f8f9fa;
            transform: translateY(-50%) scale(1.1);
        }

        .slider-btn-left { 
            left: -16px; 
        }
        
        .slider-btn-right { 
            right: -16px; 
        }

        /* Main Image Arrows */
        .main-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
            font-size: 24px;
            cursor: pointer;
            z-index: 30;
            transition: all 0.3s;
        }

        .main-slider-btn:hover {
            background: #fff;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .main-slider-btn.prev {
            left: 20px;
        }

        .main-slider-btn.next {
            right: 20px;
        }

        @media (max-width: 575px) {
            .main-slider-btn {
                width: 36px;
                height: 36px;
                font-size: 20px;
            }
            .main-slider-btn.prev { left: 10px; }
            .main-slider-btn.next { right: 10px; }
        }

        /* Right Sidebar */
        .sidebar-inner {
            background: #F3F4F6;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #E5E7EB;
        }

        .vehicle-title-main {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 10px;
            color: #111;
        }

        .vehicle-desc-small {
            font-size: 14px;
            color: #4B5563;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .price-info-card {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 40px;
            text-align: center;
            border: 1px solid #E5E7EB;
        }

        .price-card-label {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .price-card-value {
            font-size: 36px;
            font-weight: 800;
            color: #111;
            margin: 0;
            letter-spacing: -1px;
        }

        .sidebar-form-card {
            background: transparent;
            padding: 0;
            box-shadow: none;
            border: none;
        }

        .sidebar-form-card h4 {
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 25px;
            color: #111;
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 10px;
            display: block;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }

        .form-input-custom {
            background: #fff;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 0 15px !important;
            height: 48px !important;
            line-height: 46px !important; /* height - borders */
            font-size: 14px;
            width: 100%;
            margin-bottom: 15px;
            color: #111;
            transition: all 0.2s;
            box-sizing: border-box !important;
        }

        select.form-input-custom {
            appearance: none;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23333'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 35px;
            cursor: pointer;
            display: block; /* Selects don't support flex properly */
        }

        .form-input-custom:focus {
            outline: none;
            border-color: #111;
            box-shadow: none;
        }

        .form-input-custom::placeholder {
            color: #94A3B8;
        }

        .btn-quote-main {
            background: #1B447A;
            color: #fff;
            border: none;
            width: 100%;
            padding: 18px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .btn-quote-main:hover {
            background: #143560;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(27, 68, 122, 0.15);
        }

        .btn-quote-main:active {
            transform: translateY(0);
            box-shadow: 0 5px 10px rgba(27, 68, 122, 0.1);
        }

        .form-footnote {
            font-size: 11px;
            color: #9CA3AF;
            text-align: center;
            margin-top: 15px;
            line-height: 1.4;
        }

        /* Details */
        .car-details-section-title {
            font-size: 34px;
            font-weight: 800;
            margin: 60px 0 30px;
            display: flex;
            align-items: center;
            color: #111;
        }

        .car-details-section-title i {
            margin-left: 20px;
            color: #4B5563;
            font-size: 30px;
        }

        .details-heading-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .details-heading-row h3 {
            font-size: 30px;
            font-weight: 800;
            margin: 0;
        }

        .grade-badge-custom {
            background: #F3F4F6;
            padding: 8px 10px 8px 30px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #E5E7EB;
        }

        .grade-badge-custom span {
            font-size: 22px;
            font-weight: 700;
        }

        .grade-val-circle {
            width: 44px;
            height: 44px;
            background: #1B447A;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
        }

        .details-grid-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px 40px;
            border: 1px solid #EDEDED;
        }

        .spec-grid-item {
            display: flex;
            align-items: center;
            padding: 18px 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .spec-grid-item:last-child {
            border-bottom: none;
        }

        .spec-icon-box {
            width: 45px;
            font-size: 30px;
            color: #111;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spec-label-box {
            flex: 1;
            padding-right: 20px;
        }

        .spec-label-main {
            font-size: 17px;
            font-weight: 500;
            color: #333;
            display: block;
        }

        .spec-label-sub {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
        }

        .spec-value-box {
            font-size: 17px;
            font-weight: 600;
            color: #111;
            text-align: right;
        }

        /* Accordion */
        .bike-faq-container {
            margin-top: 50px;
        }

        .accordion-item {
            border: none;
            background: #F3F4F6;
            margin-bottom: 5px;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .accordion-button {
            background: #F3F4F6 !important;
            color: #111 !important;
            font-weight: 700;
            padding: 22px 25px;
            box-shadow: none !important;
            font-size: 18px;
        }

        .accordion-button::after {
            content: "+";
            background-image: none;
            font-size: 26px;
            font-weight: 300;
            color: #333;
            transform: none !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .accordion-button:not(.collapsed)::after {
            content: "-";
        }

        .accordion-body {
            padding: 0 25px 30px;
            color: #4B5563;
            font-size: 16px;
            line-height: 1.6;
        }

        @media (max-width: 991px) {
            .vehicle-title-main { font-size: 32px; }
            .details-grid-card { padding: 20px; }
        }

        /* Slick Slider Dots - Fixing the numbers issue */
        .slick-dots {
            display: flex !important;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 30px 0 0;
            gap: 8px;
        }

        .slick-dots li {
            margin: 0;
            display: flex;
            align-items: center;
        }

        .slick-dots li button {
            font-size: 0;
            line-height: 0;
            display: block;
            width: 10px;
            height: 10px;
            padding: 0;
            cursor: pointer;
            border: 0;
            outline: none;
            background: #CBD5E1;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .slick-dots li button:before {
            display: none !important;
        }

        .slick-dots li.slick-active button {
            background: var(--primary-blue);
            width: 25px;
            border-radius: 10px;
        }

        /* Related Bikes Slider Arrows */
        .related-bikes-slider .slick-prev, 
        .related-bikes-slider .slick-next {
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .related-bikes-slider .slick-prev:hover, 
        .related-bikes-slider .slick-next:hover {
            background: var(--primary-blue);
        }

        .related-bikes-slider .slick-prev:before, 
        .related-bikes-slider .slick-next:before {
            color: #111;
            font-family: 'boxicons';
            font-size: 24px;
            opacity: 1;
        }

        .related-bikes-slider .slick-prev:hover:before, 
        .related-bikes-slider .slick-next:hover:before {
            color: #fff;
        }

        .related-bikes-slider .slick-prev { left: -20px; }
        .related-bikes-slider .slick-next { right: -20px; }

        .related-bikes-slider .slick-prev:before { content: "\ea41"; } /* bx-chevron-left */
        .related-bikes-slider .slick-next:before { content: "\ea42"; } /* bx-chevron-right */

    </style>
@endpush

@section('main')
    <div class="bike-detail-wrapper">
        <div class="container">
            <div class="row">
                <!-- LEFT CONTENT -->
                <div class="col-lg-7 col-xl-8">
                    <!-- Main Image container -->
                    <div class="main-image-container">
                        <span class="status-badge">Status: Available</span>
                        <img id="bike-display-img" src="{{ asset(BIKE_PATH . $bike->images[0]) }}" alt="{{ $bike->name }}">
                        <button class="main-slider-btn prev" onclick="moveMainImage('prev')"><i class="bx bx-chevron-left"></i></button>
                        <button class="main-slider-btn next" onclick="moveMainImage('next')"><i class="bx bx-chevron-right"></i></button>
                    </div>

                    <!-- Thumbnails slider wrapper -->
                    <div class="thumbs-slider-wrapper">
                        <div class="gallery-thumbs-slider">
                            @foreach($bike->images as $index => $image)
                                <div class="thumb-box {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ asset(BIKE_PATH . $image) }}', this)">
                                    <img src="{{ asset(BIKE_PATH . $image) }}" alt="Bike thumbnail">
                                </div>
                            @endforeach
                        </div>
                        <button class="slider-btn slider-btn-left"><i class="bx bx-chevron-left"></i></button>
                        <button class="slider-btn slider-btn-right"><i class="bx bx-chevron-right"></i></button>
                    </div>

                    <!-- Car Details Title -->
                    <h2 class="car-details-section-title">Car Details <i class="bx bx-key"></i></h2>

                    <!-- Vehicle Details Grid -->
                    <div class="details-heading-row">
                        <h3>Vehicle Details</h3>
                        <div class="grade-badge-custom">
                            <span>Auction Grade</span>
                            <div class="grade-val-circle">2</div>
                        </div>
                    </div>

                    <div class="details-grid-card">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-file"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Vehicle description</span>
                                    </div>
                                    <div class="spec-value-box"></div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-car"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Make</span>
                                        <span class="spec-label-sub">Rugged adventure motorcycle</span>
                                    </div>
                                    <div class="spec-value-box">Honda</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-palette"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Exterior colour</span>
                                    </div>
                                    <div class="spec-value-box">Blue</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-layer"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Body type</span>
                                    </div>
                                    <div class="spec-value-box">Adventure Touring</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-gas-pump"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Fuel type</span>
                                    </div>
                                    <div class="spec-value-box">Gasoline</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-tachometer"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Odometer</span>
                                    </div>
                                    <div class="spec-value-box">120,000 km</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-calendar"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Model Year</span>
                                    </div>
                                    <div class="spec-value-box">2018</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-select-multiple"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Interior colour</span>
                                    </div>
                                    <div class="spec-value-box">White</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-cog"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Engine</span>
                                    </div>
                                    <div class="spec-value-box">{{ $bike->engine }}</div>
                                </div>
                                <div class="spec-grid-item">
                                    <div class="spec-icon-box"><i class="bx bx-reset"></i></div>
                                    <div class="spec-label-box">
                                        <span class="spec-label-main">Transmission</span>
                                    </div>
                                    <div class="spec-value-box">6-speed</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="bike-faq-container accordion" id="bikeFaqMain">
                        @foreach($bikeConf as $index => $conf)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $conf->title }}
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#bikeFaqMain">
                                    <div class="accordion-body">
                                        {!! $conf->description !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="col-lg-5 col-xl-4 mt-5 mt-lg-0">
                    <div class="sidebar-inner sticky-top" style="top: 100px; z-index: 5;">
                        <h1 class="vehicle-title-main">{{ $bike->name }}</h1>
                        <p class="vehicle-desc-small">{{ $bike->card_subtitle ?? 'Premium Rental 1000cc (or Expert Export Model)' }}</p>

                        <div class="price-info-card">
                            <span class="price-card-label">FOB Purchase Price:</span>
                            <h2 class="price-card-value">¥{{ number_format(1655000) }}</h2>
                        </div>

                        <div class="sidebar-form-card">
                            <h4 class="mb-4 fw-bold">Contact Form</h4>
                            <form action="#" method="POST">
                                <div class="mb-3">
                                    <label class="form-label-custom">Full Name</label>
                                    <input type="text" class="form-input-custom" placeholder="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-custom">Email</label>
                                    <input type="email" class="form-input-custom" placeholder="">
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Phone Number</label>
                                            <input type="text" class="form-input-custom" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Preferred Contact Method</label>
                                            <select class="form-input-custom">
                                                <option>Email</option>
                                                <option>Phone</option>
                                                <option>WhatsApp</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label-custom">Vehicle ID</label>
                                    <input type="text" class="form-input-custom" value="{{ $bike->name }}" readonly style="background-color: #fff;">
                                </div>

                                <div class="mt-4">
                                    <h4 class="mb-4 fw-bold">Destination Details</h4>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label-custom">Destination Country</label>
                                                <select class="form-input-custom">
                                                    <option>🇺🇸 USA</option>
                                                    <option>🇬🇧 UK</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label-custom">Nearest Major Port /</label>
                                                <input type="text" class="form-input-custom" placeholder="Postal Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn-quote-main mt-2">REQUEST EXPORT QUOTE</button>
                                <p class="form-footnote mt-3" style="font-size: 11px; text-align: left; opacity: 0.7;">Our team will confirm availability and send your detailed quote</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RELATED SECTION -->
    <section class="py-5 bg-white border-top mt-5">
        <div class="container">
            <h2 class="fw-bold mb-4" style="color: #111; font-size: 32px;">You Might Also Like</h2>
            <div class="related-bikes-slider">
                @foreach($relatedBikes as $relatedBike)
                    <div class="px-2">
                        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                            <a href="{{ route('motorcycle.single', ['slug' => $relatedBike->slug]) }}">
                                <img src="{{ asset(BIKE_PATH . $relatedBike->images[0]) }}" class="card-img-top" style="height: 240px; object-fit: cover;">
                            </a>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3" style="font-size: 20px;">{{ $relatedBike->name }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-danger fs-5">From ¥{{ number_format($relatedBike->less_four_days_price) }} <span class="text-muted fw-normal fs-6">/ day</span></div>
                                    <a href="{{ route('motorcycle.single', ['slug' => $relatedBike->slug]) }}" class="text-secondary fw-bold text-decoration-none">VIEW <i class="bx bx-right-arrow-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Gallery Thumbs Slider
            const $thumbsSlider = $('.gallery-thumbs-slider');
            
            $thumbsSlider.slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                prevArrow: $('.slider-btn-left'),
                nextArrow: $('.slider-btn-right'),
                infinite: true,
                dots: false,
                autoplay: true,
                autoplaySpeed: 3000,
                pauseOnHover: true,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });

            // Synchronize main image with slider movement
            $thumbsSlider.on('afterChange', function(event, slick, currentSlide) {
                const activeThumb = $(this).find(`.slick-slide[data-slick-index="${currentSlide}"] .thumb-box`);
                const newSrc = activeThumb.find('img').attr('src');
                
                if (newSrc) {
                    $('#bike-display-img').attr('src', newSrc);
                    $('.thumb-box').removeClass('active');
                    activeThumb.addClass('active');
                }
            });

            // Initialize Related Bikes Slider
            $('.related-bikes-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: true,
                dots: true,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });

        function changeMainImage(src, el) {
            $('#bike-display-img').attr('src', src);
            $('.thumb-box').removeClass('active');
            $(el).addClass('active');
            
            // Sync slider to this index if it's from a manual click
            const slickIndex = $(el).closest('.slick-slide').data('slick-index');
            if (slickIndex !== undefined) {
                $('.gallery-thumbs-slider').slick('slickGoTo', slickIndex);
            }
        }

        function moveMainImage(direction) {
            const $slider = $('.gallery-thumbs-slider');
            if (direction === 'next') {
                $slider.slick('slickNext');
            } else {
                $slider.slick('slickPrev');
            }
        }
    </script>
@endsection