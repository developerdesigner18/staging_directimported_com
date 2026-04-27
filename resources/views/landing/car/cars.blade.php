@extends('landing.master')
@section('title', 'Cars')
@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }

        /* Main Container */
        .cars-page-wrapper {
            background: #f8f9fa;
            padding: 0 0;
            min-height: 100vh;
        }

        /* Search & Filter Section */
        .search-filter-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            transition: box-shadow 0.3s ease;
        }

        /* When sticky */
        .search-filter-section.is-sticky {
            position: fixed;
            top: 100px;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            border-radius: 0;
            width: 1156px;
        }

        .search-filter-section {
            margin-top: 25px;
            background: white;
            box-sizing: border-box;
        }

        .search-filter-inner {
            max-width: 1140px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* Placeholder keeps layout from jumping */
        .search-filter-placeholder {
            height: 0;
        }

        .search-input-wrapper {
            position: relative;
            margin-bottom: 25px;
        }

        .search-input-wrapper input {
            width: 100%;
            padding: 14px 50px 14px 25px;
            border: 2px solid #f1f1f1;
            border-radius: 50px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f9f9f9;
        }

        .search-input-wrapper input:focus {
            outline: none;
            border-color: #053C7C;
            background: #fff;
            box-shadow: 0 5px 20px rgba(188, 33, 46, 0.1);
        }

        .search-input-wrapper .search-icon {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 20px;
        }

        /* Engine Size Buttons */
        .engine-buttons-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .engine-btn {
            padding: 9px 18px;
            border: 1px solid transparent;
            background: #f1f1f1;
            color: #555;
            font-size: 14px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .engine-btn:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .engine-btn.active {
            background: #053C7C;
            /* ASJ Racing Red */
            color: white;
            box-shadow: 0 4px 15px rgba(188, 33, 46, 0.3);
        }

        /* Cars Grid */
        /* ===== Card ===== */
        .car-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
            transition: all .3s ease;
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
            padding: 26px 22px 28px;
            text-align: center;
        }

        /* REMOVE side-by-side layout */
        .car-info-row {
            display: block;
        }

        /* ===== Title ===== */
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


        /* ===== Subtitle ===== */
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
            /* matches flex items-baseline */
            justify-content: center;
            /* matches justify-center */
            margin-bottom: 16px;
            /* mb-4 */
            gap: 4px;
        }

        /*.car-price-block::before {*/
        /*    !*content: "From";*!*/
        /*    display: block;*/
        /*    font-size: 13px;*/
        /*    font-weight: 700;*/
        /*    color: #053C7C; /* ASJ Racing Red */
        */
        /*    margin-bottom: 2px;*/
        /*}*/

        .car-price-block .price {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 30px;
            /* text-3xl */
            font-weight: 900;
            /* font-black */
            line-height: 1;
        }

        .car-price-block .period {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 14px;
            /* text-sm */
            font-weight: 700;
            /* font-bold */
            white-space: nowrap;
        }


        /* ===== Button ===== */
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
            background: linear-gradient(135deg, #042B59 0%, #053C7C 100%);
            /* Racing Red Hover */
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(188, 33, 46, .45);
        }

        /* Car Card design ends here */

        /* Loading */
        .loading-wrapper {
            text-align: center;
            padding: 60px 20px;
        }

        /* No Results */
        .no-results {
            background: white;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .engine-buttons-row {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .engine-btn {
                white-space: nowrap;
            }
        }

        /* IMAGE-ONLY HERO */
        .hero-section {
            position: relative;
            width: 100%;
            left: 0px;
            right: 0px;
            height: 420px;
            background: url('{{ isset($banner) && $banner->image ? asset(CAR_PATH . $banner->image) : asset("assets/landing/images/hero-car.png") }}') center center / cover no-repeat;
            overflow: hidden;
        }

        /* Responsive heights */
        @media (max-width: 992px) {
            .hero-section {
                height: 420px;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                height: 320px;
            }
        }

        /* Category Section Headers */
        .category-section-header {
            position: relative;
            margin-top: 40px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .category-section-header::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            /* EXACTLY at bottom */
            height: 1px;
            background: #e5e7eb;
        }

        .category-section-header:first-child {
            margin-top: 20px;
        }

        .cc-range-title {
            position: relative;
            display: inline-block;

            background: #e5e7eb;
            color: #374151;

            padding: 6px 16px;
            font-size: 12px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 1px;

            border-radius: 8px 8px 0 0;

            top: 1px;
            /* overlaps the line */
            margin-left: 12px;

            z-index: 1;
            /* removes unwanted space */
        }

        .category-section-header {
            margin-top: 30px;
            margin-bottom: 0;
        }

        /* One-row filter layout */
        .filter-row {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Buttons stay left */
        .engine-buttons-row {
            display: flex;
            gap: 12px;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        /* Search stays right */
        .filter-search {
            margin-left: auto;
            position: relative;
            width: 280px;
            flex-shrink: 0;
        }

        .filter-search input {
            width: 100%;
            padding: 12px 40px 12px 18px;
            border: 2px solid #f1f1f1;
            border-radius: 50px;
            font-size: 14px;
            background: #f9f9f9;
        }

        .filter-search .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-search {
                width: 100%;
                margin-left: 0;
            }

            .engine-buttons-row {
                overflow-x: auto;
            }
        }

        #cc {
            bottom: 10px;
        }

        /* ===== CARD ===== */
        .car-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: 0.3s ease;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
        }

        /* ===== IMAGE ===== */
        .car-card-img-wrapper {
            height: 260px;
            overflow: hidden;
        }

        .car-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== BODY ===== */
        .car-card-body {
            padding: 26px 22px 28px;
            text-align: center;
        }

        /* ===== TITLE ===== */
        .car-title {
            font-size: 26px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .car-title a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== SUBTITLE ===== */
        .car-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 18px;
            font-weight: 500;
        }

        /* ===== EMBLEM ROW ===== */
        .car-emblem-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .emblem-item {
            flex: 1;
            text-align: center;
            font-size: 12px;
            color: #374151;
        }

        .emblem-item i {
            display: block;
            font-size: 24px;
            margin-bottom: 6px;
            color: #111827;
        }

        /* ===== PRICE ===== */
        .car-price-block {
            display: flex;
            justify-content: center;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 20px;
        }

        .price-from {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 14px;
            font-weight: 700;
        }

        .price-amount {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 30px;
            font-weight: 900;
        }

        .price-per {
            color: #053C7C;
            /* ASJ Racing Red */
            font-size: 14px;
            font-weight: 700;
        }

        /* ===== BUTTON ===== */
        .btn-adventure {
            width: 100%;
            background: linear-gradient(135deg, #053C7C 0%, #042B59 100%);
            /* ASJ Racing Red */
            color: #fff;
            border: none;
            padding: 14px;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-adventure:hover {
            background: linear-gradient(135deg, #042B59 0%, #053C7C 100%);
            /* Racing Red Hover */
        }
    </style>
@endpush

@section('main')
    <div class="cars-page-wrapper">
        <div class="hero-section"></div>
        <div class="container">

            <div class="search-filter-placeholder"></div>

            <div class="search-filter-section">
                <div class="search-filter-inner">
                    <input type="hidden" class="search-range" value="0">

                    <div class="filter-row">
                        <div class="engine-buttons-row">
                            <button class="engine-btn active" data-range="0">ALL</button>
                            @foreach($ccRanges as $rangeName => $rangeData)
                                @if(!empty($rangeData['category_ids']))
                                    <button class="engine-btn" data-range="{{ $rangeName }}">
                                        {{ $rangeName }}
                                    </button>
                                @endif
                            @endforeach
                        </div>

                        <div class="filter-search">
                            <input type="text" class="search-cars" placeholder="Search cars by name...">
                            <i class="icofont-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Cars Grid -->
            <div class="cars-grid">
                <div class="row carsData">
                    @if(!$carsList->isEmpty())
                        @php
                            // Group cars by category for initial display
                            $groupedCars = $carsList->groupBy('category_id');
                        @endphp

                        @foreach($ccRanges as $rangeName => $rangeData)
                            @php
                                // Get cars in this range
                                //                                $carsInRange = collect();
                                //                                foreach($rangeData['category_ids'] as $catId) {
                                //                                    if(isset($groupedCars[$catId])) {
                                //                                        $carsInRange = $carsInRange->merge($groupedCars[$catId]);
                                //                                    }
                                //                                }
                                $carsInRange = collect();
                                foreach ($rangeData['category_ids'] as $catId) {
                                    if (isset($groupedCars[$catId])) {
                                        $carsInRange = $carsInRange->merge($groupedCars[$catId]);
                                    }
                                }
                                $carsInRange = $carsInRange->sortBy('sort_order')->values();
                            @endphp

                            @if($carsInRange->count() > 0)
                                <!-- Category Section Header -->
                                <div class="col-12 category-section-header" id="cc" data-range="{{ $rangeName }}">
                                    <span class="cc-range-title">{{ $rangeName }}</span>
                                </div>

                                <!-- Cars in this range -->
                                @foreach($carsInRange as $car)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="car-card">

                                            <!-- Image -->
                                            <div class="car-card-img-wrapper">
                                                <a href="{{route('car.single', ['slug' => $car->slug])}}">
                                                    <img src="{{asset(CAR_PATH . $car->images[0])}}" alt="{{$car->name}}" loading="lazy">
                                                </a>
                                            </div>

                                            <!-- Card Body -->
                                            <div class="car-card-body">

                                                <!-- Title -->
                                                <h4 class="car-title">
                                                    <a href="{{route('car.single', ['slug' => $car->slug])}}">
                                                        {{$car->card_header ?? $car->name}}
                                                    </a>
                                                </h4>

                                                <!-- Subtitle -->
                                                <div class="car-subtitle">
                                                    {{$car->card_subtitle ?? 'Premium Adventure Touring'}}
                                                </div>

                                                <!-- Emblems Row -->
                                                <div class="car-emblem-row">
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
                                                {{--@dd($car);--}}
                                                <!-- Price -->
                                                <div class="car-price-block">
                                                    <span class="price-from">From</span>
                                                    <span class="price-amount">¥{{number_format($car->month_price)}}</span>
                                                    <span class="price-per">/ Per Day</span>
                                                </div>

                                                <!-- Button -->
                                                {{-- <button class="btn-adventure carRequestQuote" data-id="{{ $car->id }}"
                                                    data-name="{{ $car->name }}" data-image="{{ asset(CAR_PATH.$car->images[0]) }}">
                                                    CHECK IT OUT
                                                </button> --}}
                                                <button class="btn-adventure btncheckout" data-slug="{{ $car->slug }}"
                                                    data-id="{{ $car->id }}" data-name="{{ $car->name }}"
                                                    data-image="{{ asset(CAR_PATH . $car->images[0]) }}">
                                                    CHECK IT OUT
                                                </button>


                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="no-results">
                                <h4>No cars found</h4>
                                <p>Try adjusting your search criteria</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="carPaginationData">
                {!! carPagination($totalPages) !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script !src="">
        window.addEventListener("scroll", function () {
            const filter = document.querySelector(".search-filter-section");

            if (window.scrollY > 200) {
                filter.classList.add("is-sticky");
            } else {
                filter.classList.remove("is-sticky");
            }
        });
        $(document).on('click', '.btncheckout', function () {
            let slug = $(this).data('slug');
            window.location.href = "/car/" + slug;
        });
        // Search functionality
        $('.search-cars').on('keyup', function (e) {
            loadPageData(1);
        });

        // Engine filter button click handler
        $(document).on("click", ".engine-btn", function () {
            $(".engine-btn").removeClass("active");
            $(this).addClass("active");

            var range = $(this).data('range');
            $('.search-range').val(range);
            loadPageData(1);
        });
        //Search Filter Position fixed
        $(window).on('scroll', function () {
            const filter = $('.search-filter-section');
            const placeholder = $('.search-filter-placeholder');
            const start = $('.hero-section').outerHeight() - 20;

            if ($(window).scrollTop() > start) {
                if (!filter.hasClass('is-sticky')) {
                    placeholder.height(filter.outerHeight());
                    filter.addClass('is-sticky');
                }
            } else {
                filter.removeClass('is-sticky');
                placeholder.height(0);
            }
        });
        // Price range slider - (Kept commented/broken as in original to avoid errors if elements missing)
        const rangeMin = document.getElementById('range-min');
        const rangeMax = document.getElementById('range-max');
        const sliderRange = document.querySelector('.slider-range');
        const minValue = document.querySelector('.min-value');
        const maxValue = document.querySelector('.max-value');

        function updateSlider() {
            if (!rangeMin || !rangeMax) return { min: 0, max: 100000 };

            let min = parseInt(rangeMin.value);
            let max = parseInt(rangeMax.value);

            // Prevent overlap
            if (max - min < 500) {
                if (event.target === rangeMin) {
                    rangeMin.value = max - 500;
                    min = max - 500;
                } else {
                    rangeMax.value = min + 500;
                    max = min + 500;
                }
            }

            // Update display values
            if (minValue) minValue.textContent = min.toLocaleString();
            if (maxValue) maxValue.textContent = max.toLocaleString();

            // Update slider track
            if (sliderRange) {
                const percentMin = (min / rangeMin.max) * 100;
                const percentMax = (max / rangeMax.max) * 100;

                sliderRange.style.left = percentMin + '%';
                sliderRange.style.width = (percentMax - percentMin) + '%';
            }

            return { min, max };
        }

        if (rangeMin) {
            rangeMin.addEventListener('input', function () {
                updateSlider();
            });
            rangeMin.addEventListener('change', function () {
                loadPageData(1);
            });
        }

        if (rangeMax) {
            rangeMax.addEventListener('input', function () {
                updateSlider();
            });
            rangeMax.addEventListener('change', function () {
                loadPageData(1);
            });
        }

        function loadPageData(page = '1') {
            var search = $('.search-cars').val() || '';
            var range = $('.search-range').val() || '0';
            var price = updateSlider();

            $.ajax({
                url: "{{ route('car.pagination') }}",
                dataType: "JSON",
                method: "POST",
                data: {
                    "search": search,
                    "range": range,
                    "min_price": price.min,
                    "max_price": price.max,
                    "page": page,
                    "limit": "{{ $limit }}",
                    "_token": "{{csrf_token()}}",
                },
                beforeSend: function () {
                    $('.carsData').html('<div class="col-12"><div class="loading-wrapper"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" style="width: 60px;"><radialGradient id="a12" cx=".66" fx=".66" cy=".3125" fy=".3125" gradientTransform="scale(1.5)"><stop offset="0" stop-color="#dc3545"></stop><stop offset=".3" stop-color="#dc3545" stop-opacity=".9"></stop><stop offset=".6" stop-color="#dc3545" stop-opacity=".6"></stop><stop offset=".8" stop-color="#dc3545" stop-opacity=".3"></stop><stop offset="1" stop-color="#dc3545" stop-opacity="0"></stop></radialGradient><circle transform-origin="center" fill="none" stroke="url(#a12)" stroke-width="15" stroke-linecap="round" stroke-dasharray="200 1000" stroke-dashoffset="0" cx="100" cy="100" r="70"><animateTransform type="rotate" attributeName="transform" calcMode="spline" dur="2" values="360;0" keyTimes="0;1" keySplines="0 0 1 1" repeatCount="indefinite"></animateTransform></circle><circle transform-origin="center" fill="none" opacity=".2" stroke="#dc3545" stroke-width="15" stroke-linecap="round" cx="100" cy="100" r="70"></circle></svg></div></div>');
                },
                success: function (result) {
                    $('.carsData').html(result.message.data);
                    $('.carPaginationData').html(result.message.pagination);

                    // Hide pagination when an engine/range filter is active
                    var currentRange = $('.search-range').val() || '0';
                    if (currentRange !== '0') {
                        $('.carPaginationData').hide();
                    } else {
                        $('.carPaginationData').show();
                    }
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('error')) {
                        if (data.error.hasOwnProperty('id')) {
                            sendError(data.error.id);
                        }
                    } else if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message)
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                }
            });
        }

        $(document).ready(function () {
            loadBanner();

            // Load current banner image
            function loadBanner() {
                $.get("{{ route('admin.banner.get') }}", function (response) {
                    if (response.status) {
                        let path = response.data.image_url;
                        console.log(path)
                        $('.hero-section').css(
                            'background', 'url(' + path + ') center center / cover no-repeat'
                        );
                    }
                });
            }
            // Initialize slider
            updateSlider();

            // Pagination handlers
            $(document).delegate(".rid-pagination .page-btn a", "click", function (e) {
                e.preventDefault();
                let page = $(this).text();
                loadPageData(page);
                $(".rid-pagination .page-btn").removeClass("active");
                $(this).parent().addClass("active");
            });

            $(document).delegate(".rid-pagination .next", "click", function (e) {
                e.preventDefault();
                let current = $(".rid-pagination .page-btn.active");
                let next = current.nextAll(".page-btn").first();

                if (next.length) {
                    let page = next.text();
                    loadPageData(page);
                    current.removeClass("active");
                    next.addClass("active");
                }
            });

            $(document).delegate(".rid-pagination .previous", "click", function (e) {
                e.preventDefault();
                let current = $(".rid-pagination .page-btn.active");
                let prev = current.prevAll(".page-btn").first();

                if (prev.length) {
                    let page = prev.text();
                    loadPageData(page);
                    current.removeClass("active");
                    prev.addClass("active");
                }
            });
        });
    </script>
@endsection