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

        .gallery-thumbs-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            margin-bottom: 40px;
        }

        .thumb-box {
            cursor: pointer;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            aspect-ratio: 4/3;
        }

        .thumb-box.active {
            border-color: var(--primary-blue);
            transform: scale(0.95);
            box-shadow: 0 0 0 2px rgba(27, 68, 122, 0.2);
        }

        .thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {

            line-height: 35px;
            font-size: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 9px;

        }

        .select2-container--default .select2-selection--single {

            height: 39px;
        }

        @media (max-width: 1200px) {
            .gallery-thumbs-grid {
                grid-template-columns: repeat(8, 1fr);
            }
        }

        @media (max-width: 991px) {
            .gallery-thumbs-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 767px) {
            .gallery-thumbs-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        @media (max-width: 480px) {
            .gallery-thumbs-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 6px;
            }
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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

            .main-slider-btn.prev {
                left: 10px;
            }

            .main-slider-btn.next {
                right: 10px;
            }
        }

        /* Right Sidebar */
        .sidebar-inner {
            background: #fff;
            border-radius: 16px;
            padding: 12px 12px 10px;
            /* controls top & bottom space */
        }

        .sidebar-inner h1 {
            margin-top: 0;
        }

        /* TITLE */
        .vehicle-title-main {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            line-height: 1.1;
        }

        .vehicle-desc-small {
            font-size: 13px;
            color: #6B7280;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }



        /* PRICE */
        .price-info-card {
            background: #E11D48;
            color: #fff;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
            margin: 8px 0;
        }

        .price-info-card strong {
            display: block;
            font-size: 22px;
            margin-top: 5px;
        }

        /* FORM BOX */
        .sidebar-form-box {
            background: #E5E7EB;
            padding: 14px;
            margin-top: 8px;
            border-radius: 12px;
        }

        /* HEAD */
        .sidebar-form-box h4 {
            margin-bottom: 12px;
            font-weight: 700;
        }

        /* LABEL */
        .sidebar-form-box label {
            font-size: 9px;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        /* INPUT */
        .sidebar-form-box input,
        .sidebar-form-box select,
        .sidebar-form-box textarea {
            width: 100%;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #D1D5DB;
            padding: 0 10px;
            margin-bottom: 8px;
            font-size: 13px;
            background: #fff;
        }

        /* TEXTAREA */
        .sidebar-form-box textarea {
            height: 80px;
            padding: 8px;
        }

        /* 2 COLUMN */
        .row-2 {
            display: flex;
            gap: 10px;
        }

        .row-2>div {
            width: 50%;
        }

        /* VEHICLE ID */
        .vehicle-id-label {
            margin-top: 5px;
        }

        .vehicle-id-box {
            background: #fff;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        /* BUTTON */
        .btn-submit {
            width: 100%;
            background: #1B447A;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        /* STICKY */
        .sticky-sidebar-wrapper {
            position: sticky;
            top: 90px;
        }

        /* Details */
        .car-details-section-title {
            justify-content: space-between;
            font-size: 24px;
            font-weight: 800;
            margin: 30px 0 15px;
            display: flex;
            align-items: center;
            color: #111;
        }

        .car-details-section-title i {
            margin-left: 15px;
            color: #4B5563;
            font-size: 22px;
        }

        .details-heading-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .details-heading-row h3 {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }

        .grade-badge-custom {
            background: #F3F4F6;
            padding: 5px 8px 5px 15px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #E5E7EB;
        }

        .grade-badge-custom span {
            font-size: 14px;
            font-weight: 700;
        }

        .grade-val-circle {
            width: 30px;
            height: 30px;
            background: #1B447A;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 800;
        }

        .details-grid-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px 20px;
            border: 1px solid #EDEDED;
        }

        .spec-grid-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .spec-grid-item:last-child {
            border-bottom: none;
        }

        .spec-icon-box {
            width: 35px;
            font-size: 20px;
            color: #111;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spec-label-box {
            flex: 1;
            padding-right: 15px;
        }

        .spec-label-main {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            display: block;
        }

        .spec-label-sub {
            font-size: 11px;
            color: #6B7280;
            font-weight: 500;
        }

        .spec-value-box {
            font-size: 14px;
            font-weight: 600;
            color: #111;
            text-align: right;
        }

        /* Accordion */
        .bike-faq-container {
            margin-top: 25px;
        }

        .accordion-item {
            border: none;
            background: #F3F4F6;
            margin-bottom: 3px;
            border-radius: 12px !important;
            overflow: hidden;
            padding: 0px;
        }

        .bx-key:before {
            font-size: 42px;
        }

        .accordion-button {
            background: #1B447A !important;
            color: #ffffffff !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700;
            padding: 0px 18px;
            box-shadow: none !important;
            font-size: 14px;
            background-image: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.3s;
        }

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            color: white;
        }

        .accordion-button::after {


            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");

        }

        .accordion-button:not(.collapsed) {
            color: white !important;
        }



        .accordion-body {
            padding: 0 20px 20px;
            color: #4B5563;
            font-size: 13px;
            line-height: 1.6;
            font-family: 'Inter', sans-serif !important;
        }

        @media (max-width: 991px) {
            .vehicle-title-main {
                font-size: 32px;
            }

            .details-grid-card {
                padding: 20px;
            }
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        .related-bikes-slider .slick-prev {
            left: -20px;
        }

        .related-bikes-slider .slick-next {
            right: -20px;
        }

        .related-bikes-slider .slick-prev:before {
            content: "\ea41";
        }

        /* bx-chevron-left */
        .related-bikes-slider .slick-next:before {
            content: "\ea42";
        }

        /* bx-chevron-right */
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
                        <button class="main-slider-btn prev" onclick="moveMainImage('prev')"><i
                                class="bx bx-chevron-left"></i></button>
                        <button class="main-slider-btn next" onclick="moveMainImage('next')"><i
                                class="bx bx-chevron-right"></i></button>
                    </div>

                    <!-- Thumbnails grid wrapper -->
                    <div class="gallery-thumbs-grid">
                        @foreach($bike->images as $index => $image)
                            <div class="thumb-box {{ $index === 0 ? 'active' : '' }}"
                                onclick="changeMainImage('{{ asset(BIKE_PATH . $image) }}', this)">
                                <img src="{{ asset(BIKE_PATH . $image) }}" alt="Bike thumbnail">
                            </div>
                        @endforeach
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
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $conf->title }}
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#bikeFaqMain">
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
                    <div class="sidebar-inner sticky-sidebar-wrapper">

                        <h1 class="vehicle-title-main">
                            Honda Africa Twin CRF1000L #1
                        </h1>

                        <p class="vehicle-desc-small">
                            Premium Rental 1000cc
                        </p>

                        <div class="price-info-card">
                            <div>FOB = Full on Board</div>
                            <strong>¥1,655,000 FOB</strong>
                        </div>

                        <div class="sidebar-form-box">

                            <h4>Contact Us</h4>

                            <label>Full Name</label>
                            <input type="text">

                            <label>Email</label>
                            <input type="email">

                            <div class="row-2">
                                <div>
                                    <label>Phone Number</label>
                                    <input type="text">
                                </div>
                                <div>
                                    <label>Preferred Contact Method</label>
                                    <select>
                                        <option>Select...</option>
                                        <option>Email</option>
                                        <option>Phone</option>
                                        <option>WhatsApp</option>
                                    </select>
                                </div>
                            </div>

                            <label class="vehicle-id-label">* Vehicle ID :</label>
                            <div class="vehicle-id-box">
                                Honda Africa Twin CRF1000L
                            </div>

                            <div class="row-2">
                                <div>
                                    <label>Destination Country</label>
                                    <select>
                                        <option>🇺🇸 USA</option>
                                        <option>🇬🇧 UK</option>
                                        <option>🇦🇺 Australia</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Nearest Major Port / Postal Code</label>
                                    <input type="text">
                                </div>
                            </div>

                            <label>Message</label>
                            <textarea></textarea>

                            <button class="btn-submit">
                                REQUEST VEHICLE DETAILS & QUOTE
                            </button>

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
                                <img src="{{ asset(BIKE_PATH . $relatedBike->images[0]) }}" class="card-img-top"
                                    style="height: 240px; object-fit: cover;">
                            </a>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3" style="font-size: 20px;">{{ $relatedBike->name }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-danger fs-5">From
                                        ¥{{ number_format($relatedBike->less_four_days_price) }} <span
                                            class="text-muted fw-normal fs-6">/ day</span></div>
                                    <a href="{{ route('motorcycle.single', ['slug' => $relatedBike->slug]) }}"
                                        class="text-secondary fw-bold text-decoration-none">VIEW <i
                                            class="bx bx-right-arrow-alt"></i></a>
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
        $(document).ready(function () {
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

            // Ensure the active thumbnail is somewhat visible if there are many rows
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function moveMainImage(direction) {
            const $active = $('.thumb-box.active');
            let $target;

            if (direction === 'next') {
                $target = $active.next('.thumb-box');
                if ($target.length === 0) $target = $('.thumb-box').first();
            } else {
                $target = $active.prev('.thumb-box');
                if ($target.length === 0) $target = $('.thumb-box').last();
            }

            if ($target.length) {
                const newSrc = $target.find('img').attr('src');
                changeMainImage(newSrc, $target[0]);
            }
        }

    </script>
@endsection