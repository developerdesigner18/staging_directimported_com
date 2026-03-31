@extends('landing.master')
@section('title',$bike->name)
@push('style')
    <style>
        :root {
            --primary-color: #F3364F;
            --primary-color-light: rgba(243, 54, 79, 0.1);
            --primary-color-hover: rgba(243, 54, 79, 0.8);
        }

        .popular-card {
            border: 2px solid var(--primary-color) !important;
            background: linear-gradient(145deg, #ffffff, var(--primary-color-light));
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
        }
    </style>
@endpush
@section('main')
    <!-- Titlebar Section Start -->
    <section class="rid-titlebar-2 rid-rental-details-banner" style="@if(isset($bike->banner) && $bike->banner) background: url({{ asset(BIKE_PATH.$bike->banner) }}); @endif">
        <div class="container">
            <h2>Rental Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rental Details</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- Titlebar Section End -->

    <section class="rid-rental-details sec-space">
        <div class="container">
            <div class="bike-details-top">
                <div class="row">
                    <div class="col-md-4">
                        <a class="btn d-grid bikeRequestQuote" href="javascript:void(0);" data-id="{{ $bike->id }}" data-name="{{ $bike->name }}" data-image="{{ asset(BIKE_PATH.$bike->images[0]) }}">Build Your Quote Now</a>
                    </div>
                    <div class="col-md-8">
                        <div class="bike-details-info d-flex justify-content-between align-items-center">
                            <h3>{{$bike->name}}</h3>
                            <h4>From ¥{{$bike->less_four_days_price}}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bike-details-gallery sec-space-bottom">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <!-- Pricing Section -->
                        <div class="mb-4">
                            <div class="text-center">
                                <h5 class="mb-4 pb-2" style="border-bottom: 2px solid #E42E46;">Rental Pricing</h5>
                            </div>
                            <div class="card border-0 shadow-sm pricing-card my-2">
                                <div class="card-body text-center">
                                    <div class="pricing-icon">
                                        <i class="fas fa-calendar-day text-primary-custom fs-2"></i>
                                    </div>
                                    <h5 class="card-title text-danger">1-4 Days</h5>
                                    <h3 class="price-amount text-dark mb-0">¥{{ number_format($bike->less_four_days_price) }}</h3>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm pricing-card my-2">
                                <div class="card-body text-center">
                                    <div class="pricing-icon">
                                        <i class="fas fa-calendar-day text-primary-custom fs-2"></i>
                                    </div>
                                    <h5 class="card-title text-danger">5-6 Days</h5>
                                    <h3 class="price-amount text-dark mb-0">¥{{ number_format($bike->five_six_days_price) }}</h3>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm pricing-card my-2 position-relative popular-card">
                                <div class="card-body text-center">
                                    <div class="badge bg-primary-custom text-white position-absolute top-0 start-50 translate-middle rounded-pill">
                                        Popular
                                    </div>
                                    <div class="pricing-icon">
                                        <i class="fas fa-calendar-day text-primary-custom fs-2"></i>
                                    </div>
                                    <h5 class="card-title text-danger">7 Days</h5>
                                    <h3 class="price-amount text-dark mb-0">¥{{ number_format($bike->week_price) }}</h3>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm pricing-card my-2">
                                <div class="card-body text-center">
                                    <div class="pricing-icon">
                                        <i class="fas fa-calendar-day text-primary-custom fs-2"></i>
                                    </div>
                                    <h5 class="card-title text-danger">Monthly</h5>
                                    <h3 class="price-amount text-dark mb-0">¥{{ number_format($bike->month_price) }}</h3>
                                </div>
                            </div>
                        </div>
                        @if($bike->location)
                            <div class="bike-details-sidebar rid-map-2">
                                <iframe src="https://www.google.com/maps?q={{ $bike->location }}&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="bike-details-main-img" src="{{ asset(BIKE_PATH.$bike->images[0]) }}" alt="{{ $bike->name }}">
                            </div>
                        </div>
                        @if(count($bike->images) > 0)
                            <div class="row g-2">
                                @for($i = 0; $i < count($bike->images); $i++)
                                    <div class="col popup-gallery">
                                        <img src="{{ asset(BIKE_PATH.$bike->images[$i]) }}" alt="{{ $bike->name }} - Image {{ $i }}" class="bike-image" role="button">
                                    </div>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bike-details-description">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="bike-details-info-2">
                            <h4>Description</h4>
                            {!! $bike->description !!}
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="detail-spec-single">
                            <h4>Technical Specification</h4>
                            {!! $bike->tec_spec !!}
                        </div>
                    </div>
                </div>

                <div class="row mt-5 mb-5">
                    <div class="col-lg-12">
                        <div class="rid-accordion" id="faqWrapDynamic">
                            @foreach($bikeConf as $index => $conf)
                                @php
                                    $headingId = 'heading' . $index;
                                    $collapseId = 'collapse' . $index;
                                    $isFirst = $index === 0;
                                @endphp
                                <div class="accordion-item position-relative">
                                    <h6 class="accordion-header" id="{{ $headingId }}">
                                        <button class="rid-accordion-btn {{ $isFirst ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                aria-controls="{{ $collapseId }}">
                                            {{ $conf->title }}
                                        </button>
                                    </h6>
                                    <div id="{{ $collapseId }}"
                                         class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                         aria-labelledby="{{ $headingId }}"
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
        </div>
    </section>
@endsection

@section('script')
    <script !src="">
        $(document).ready(function () {
            $('.bike-image').on('click', function (){
                var img = $(this).attr('src');
                $('.bike-details-main-img').attr('src',img);
            });
        })
    </script>
@endsection
