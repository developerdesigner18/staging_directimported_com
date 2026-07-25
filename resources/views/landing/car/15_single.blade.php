@extends('landing.master')
@section('title', $car->name)

@push('style')
    <style>
        :root {
            --primary-color: #E42E46;
        }

        /* BREADCRUMB */
        .page-breadcrumb-wrapper {
            background: #f3f4f6;
            padding: 15px 0;
        }

        .breadcrumb-box {
            background: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 13px;
        }

        .breadcrumb-box a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb-box span {
            margin: 0 6px;
            color: #9ca3af;
        }

        .breadcrumb-box .active {
            font-weight: 600;
            color: #111827;
        }

        /* IMAGE */
        .car-details-main-img {
            width: 100%;
            border-radius: 10px;
        }

        .car-image {
            cursor: pointer;
            border-radius: 6px;
            transition: 0.3s;
        }

        .car-image:hover {
            opacity: 0.8;
        }

        /* SIDEBAR */
        .booking-sidebar {
            position: relative;
            align-self: flex-start;
        }

        .booking-sidebar.is-fixed {
            position: fixed;
            top: 100px;
            width: inherit;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            z-index: 100;
        }

        .client-booking-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* Ensure parent row allows positioning */
        .container .row {
            overflow: visible !important;
        }

        .client-price-box {
            background: #de2b43;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        .client-price-box h4 {
            margin: 5px 0;
            font-size: 26px;
            font-weight: 700;
            color: white;
        }

        .client-price-box small {
            display: block;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .client-price-box span {
            font-size: 12px;
            opacity: 0.8;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 6px;
            height: 42px;
        }

        .btn-check-availability {
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
        }

        .btn-check-availability:hover {
            opacity: 0.9;
        }

        .rates-section {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 14px;
        }

        .rates-section h6 {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #6b7280;
        }

        .rates-section li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .highlight-rate {
            color: var(--primary-color);
            font-weight: 700;
        }

        .whatsapp-line {
            font-size: 13px;
            margin-top: 15px;
            text-align: center;
            color: #6b7280;
        }

        .car-tabs .nav-link {
            border: none;
            color: #9ca3af;
            font-weight: 600;
            margin-right: 25px;
        }

        .car-tabs .nav-link.active {
            color: #E42E46;
            border-bottom: 3px solid #E42E46;
        }

        /* Related Cars Section */
        .related-cars-section {
            margin-top: 60px;
            padding: 50px 0;
            background: #f8f9fa;
        }

        .related-cars-title {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #1f2937;
        }



        .car-tag {
            display: inline-block;
            margin-top: 6px;
            color: black;
            font-size: 11px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }



        .car-info-row {
            display: block;
        }

        .car-info-row h5 {
            font-size: 24px;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 2px;
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

        .car-price-block {
            display: flex;
            align-items: baseline;
            justify-content: center;
            margin-bottom: 16px;
            gap: 4px;
        }

        .car-price-block .price {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 30px;
            font-weight: 900;
            line-height: 1;
        }

        .car-price-block .period {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }



        .btn-view-details {
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
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-view-details:hover {
            background: linear-gradient(135deg, #042B59 0%, #053C7C 100%);
            /* Racing Red Hover */
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(188, 33, 46, .45);
            color: #fff;
        }

        .related-cars-section {
            margin-top: 60px;
            padding: 50px 0;
            background: #f8f9fa;
        }

        .related-car-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .related-car-card:hover {
            border-color: #E42E46;
            box-shadow: 0 8px 20px rgba(228, 46, 70, 0.15);
            transform: translateY(-4px);
        }

        .related-car-img {
            height: 220px;
            background: #f3f4f6;
            overflow: hidden;
            position: relative;
        }

        .related-car-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .related-car-card:hover .related-car-img img {
            transform: scale(1.05);
        }

        .related-car-body {
            padding: 20px;
        }

        .related-car-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .related-car-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #f1f1f1;
        }

        .related-car-price {
            font-size: 16px;
            font-weight: 900;
            color: #E42E46;
        }

        .related-car-price span {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
        }

        .related-car-view {
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .related-car-view:hover {
            color: #E42E46;
            gap: 8px;
        }

        .related-car-view i {
            font-size: 14px;
        }

        .tech-spec-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .spec-icon {
            width: 55px;
            height: 55px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #374151;
        }

        .spec-item small {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            letter-spacing: 1px;
        }

        .spec-item h6 {
            font-size: 18px;
            font-weight: 700;
            margin-top: 4px;
        }

        .spec-icon i {
            font-size: 22px;
        }

        /* Ensure datepicker is always on top */
        .ui-datepicker {
            z-index: 2000 !important;
        }
    </style>
@endpush


@section('main')

    <!-- HERO -->
    <section class="rid-titlebar-2"
        style="background: url('{{ isset($car->banner) ? asset(CAR_PATH . $car->banner) : asset('uploads/car_images/default-banner.jpg') }}');
                                background-size:cover; background-position:center; padding:80px 0; text-align:center; color:#fff;">
        <div class="container">
            <h2>RENTAL DETAILS</h2>
        </div>
    </section>


    <!-- BREADCRUMB -->
    <div class="page-breadcrumb-wrapper">
        <div class="container">
            <div class="breadcrumb-box">
                <a href="{{ route('landing') }}">Home</a>
                <span>/</span>
                <a href="{{ route('car') }}">Cars</a>
                <span>/</span>
                <span class="active">{{ $car->name }}</span>
            </div>
        </div>
    </div>


    <div class="container my-5">
        <div class="row" style="overflow: visible;">

            <!-- LEFT CONTENT -->
            <div class="col-lg-8">

                <img class="car-details-main-img mb-3" src="{{ asset(CAR_PATH . $car->images[0]) }}"
                    alt="{{ $car->name }}" loading="lazy">

                @if(count($car->images) > 0)
                    <div class="row g-2">
                        @foreach($car->images as $image)
                            <div class="col">
                                <img src="{{ asset(CAR_PATH . $image) }}" class="car-image" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- TABS -->
                <div class="row mt-5">
                    <div class="col-12">

                        <ul class="nav nav-tabs car-tabs border-0">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#overviewTab">OVERVIEW</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#specsTab">SPECS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#requirementsTab">REQUIREMENTS</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">

                            <div class="tab-pane fade" id="overviewTab">
                                {!! $car->description !!}
                            </div>

                            <div class="tab-pane fade show active" id="specsTab">
                                <div class="tech-spec-box">
                                    <h4 class="tech-title">
                                        {{-- <i class="bx bx-cog text-danger me-2"></i>--}}
                                        Technical Specifications
                                    </h4>

                                    <div class="row mt-4">

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bx-tachometer"></i>
                                                </div>
                                                <div>
                                                    <small>ENGINE</small>
                                                    <h6>{{ $car->engine }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bxs-bolt"></i>
                                                </div>
                                                <div>
                                                    <small>POWER</small>
                                                    <h6>{{ $car->power }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bx-ruler"></i>
                                                </div>
                                                <div>
                                                    <small>SEAT HEIGHT</small>
                                                    <h6>{{ $car->seat_height }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bx-cube"></i>
                                                </div>
                                                <div>
                                                    <small>WEIGHT</small>
                                                    <h6>{{ $car->weight }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bxs-gas-pump"></i>
                                                </div>
                                                <div>
                                                    <small>TANK CAPACITY</small>
                                                    <h6>{{ $car->tank_capacity }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="spec-item">
                                                <div class="spec-icon">
                                                    <i class="bx bx-briefcase"></i>
                                                </div>
                                                <div>
                                                    <small>LUGGAGE</small>
                                                    <h6>{{ $car->luggage }}</h6>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="requirementsTab">
                                <p>Please check detailed rental requirements below.</p>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- FAQ SECTION MOVED INSIDE col-lg-8 -->
                <div class="row mt-5 mb-5">
                    <div class="col-12">
                        <div class="rid-accordion" id="faqWrapDynamic">
                            @foreach($carConf as $index => $conf)
                                @php
                                    $headingId = 'heading' . $index;
                                    $collapseId = 'collapse' . $index;
                                    $isFirst = $index === 0;
                                @endphp
                                <div class="accordion-item position-relative">
                                    <h6 class="accordion-header" id="{{ $headingId }}">
                                        <button class="rid-accordion-btn {{ $isFirst ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                            aria-expanded="{{ $isFirst ? 'true' : 'false' }}">
                                            {{ $conf->title }}
                                        </button>
                                    </h6>
                                    <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                        data-bs-parent="#faqWrapDynamic">
                                        <div class="accordion-body">
                                            {!! $conf->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-lg-4">
                <div class="booking-sidebar">
                    <div class="client-booking-card">

                        <h5 class="fw-bold mb-1">{{ $car->name }}</h5>

                        <p class="small text-muted mb-3">
                            {{$car->card_subtitle ?? ""}}
                        </p>

                        <!-- Hidden based on client request. -->
                        {{--
                        <div class="client-price-box">
                            <small>STARTING FROM</small>
                            <h4>{{ number_format($car->month_price) }}</h4>
                            <span>/ Per Day</span>
                        </div>
                        --}}

                        <div class="mb-3">
                            <label class="form-label">PICK-UP DATE</label>
                            <input type="text" class="form-control" id="single_pickup_date" autocomplete="off"
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RETURN DATE</label>
                            <input type="text" class="form-control" id="single_return_date" value="{{ date('Y-m-d') }}"
                                autocomplete="off">
                        </div>
                        <input type="text" value="{{$car->insurance_price}}" id="insurance_price" hidden>

                        <button class="btn-check-availability mb-3" id="checkAvailabilityBtn" data-id="{{ $car->id }}"
                            data-name="{{ $car->name }}" data-image="{{ asset(CAR_PATH . $car->images[0]) }}">
                            CHECK AVAILABILITY
                        </button>

                        <!-- Hidden based on client request. -->
                        {{--
                        <div class="rates-section">
                            <h6>Rental Rates Per Day</h6>
                            <ul class="list-unstyled">
                                <li><span>1 - 4 Days</span> <span>{{ number_format($car->less_four_days_price) }} Per
                                        Day</span></li>
                                <li><span>5 - 7 Days</span> <span>{{ number_format($car->five_six_days_price) }} Per
                                        Day</span></li>
                                <li><span>8 - 29 Days</span> <span>{{ number_format($car->week_price) }} Per Day</span>
                                </li>
                                <li><span>30+ Days</span> <span
                                        class="highlight-rate">{{ number_format($car->month_price) }} Per Day</span></li>
                                <li><span>Optional Insurance </span> <span>{{ number_format($car->insurance_price) }} Per
                                        Day</span></li>
                            </ul>
                        </div>
                        --}}

                        <div class="whatsapp-line">
                            Questions ask us on WhatsApp or call
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- You Might Also Like Section -->
    @if($relatedCars->count() > 0)
        <div class="related-cars-section">
            <div class="container">
                <h5 style="font-weight:600; margin-bottom:20px;">You Might Also Like</h5>

                <div class="row">
                    @foreach($relatedCars as $relatedCar)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="related-car-card">

                                <div class="related-car-img">
                                    <a href="{{ route('car.single', ['slug' => $relatedCar->slug]) }}">
                                        <img src="{{ asset(CAR_PATH . $relatedCar->images[0]) }}" alt="{{ $relatedCar->name }}"
                                            loading="lazy">
                                    </a>
                                </div>

                                <div class="related-car-body">
                                    <div class="related-car-title">
                                        {{ $relatedCar->name }}
                                    </div>

                                    <div class="related-car-bottom">
                                        <!-- Hidden based on client request. -->
                                        {{--
                                        <div class="related-car-price">
                                            From {{ number_format($relatedCar->less_four_days_price) }}
                                            <span>/per day</span>
                                        </div>
                                        --}}

                                        <a href="{{ route('car.single', ['slug' => $relatedCar->slug]) }}"
                                            class="related-car-view">
                                            VIEW <i class="bx bx-right-arrow-alt"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


@endsection
@section('script')
    <script>
        $(document).ready(function () {

            // Pick-up Date datepicker (1 month, past dates disabled)
            $("#single_pickup_date").datepicker({
                numberOfMonths: 3,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                onSelect: function (selected) {
                    var startDate = $(this).datepicker('getDate');
                    $("#single_return_date").datepicker("option", "minDate", startDate);

                    // Clear return date if it is before the newly selected pick-up date
                    var endDate = $("#single_return_date").datepicker('getDate');
                    if (endDate && endDate < startDate) {
                        $("#single_return_date").val('');
                    }
                }
            });

            // Return Date datepicker (1 month, past dates disabled)
            $("#single_return_date").datepicker({
                numberOfMonths: 3,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true
            });

            // Fixed Sidebar on Scroll
            var $sidebar = $('.booking-sidebar');
            var $sidebarParent = $sidebar.parent();
            var sidebarOffset = $sidebar.offset().top;
            var sidebarWidth = $sidebar.width();

            $(window).on('scroll resize', function () {
                var scrollTop = $(window).scrollTop();
                var parentOffset = $sidebarParent.offset().top;
                var parentHeight = $sidebarParent.height();
                var sidebarHeight = $sidebar.outerHeight();

                // Update width on resize
                if (!$sidebar.hasClass('is-fixed')) {
                    sidebarWidth = $sidebar.width();
                }

                if (scrollTop + 100 > parentOffset && scrollTop + sidebarHeight + 120 < parentOffset + parentHeight) {
                    $sidebar.addClass('is-fixed').css('width', sidebarWidth + 'px');
                } else {
                    $sidebar.removeClass('is-fixed').css('width', '');
                }
            });

            $('a[href="#requirementsTab"]').on('shown.bs.tab', function () {

                let faqSection = $('#faqWrapDynamic');

                if (faqSection.length) {

                    // Smooth scroll (better than jQuery animate)
                    window.scrollTo({
                        top: faqSection.offset().top - 120,
                        behavior: 'smooth'
                    });

                    // Wait a bit longer so scroll fully completes
                    setTimeout(function () {

                        let requirementBtn = null;

                        $('#faqWrapDynamic .rid-accordion-btn').each(function () {
                            let text = $(this).text().trim().toLowerCase();
                            if (text === 'requirements') {
                                requirementBtn = $(this);
                            }
                        });

                        if (requirementBtn) {

                            let target = $(requirementBtn.attr('data-bs-target'));

                            // Close any open item smoothly
                            $('#faqWrapDynamic .accordion-collapse.show')
                                .not(target)
                                .collapse('hide');

                            // Small delay for smoother transition
                            setTimeout(function () {
                                target.collapse('show');
                            }, 200);
                        }

                    }, 700); // longer delay = smoother feeling
                }
            });

            // Thumbnail click smooth swap
            $('.car-image').on('click', function () {
                let img = $(this).attr('src');

                $('.car-details-main-img').fadeOut(150, function () {
                    $(this).attr('src', img).fadeIn(200);
                });
            });

        });
    </script>
@endsection