@extends('admin.master')
@section('title', 'Slider')
@push('style')
    <style>
        .banner {
            height: 120px;
            object-fit: contain;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .map {
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
@endpush
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent mb-3">
                <h4 class="mb-sm-0">Car Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.car.index') }}">Cars</a></li>
                        <li class="breadcrumb-item active">{{ $car->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left: Image Slider -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body p-2">
                    <div id="carCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($car->images && count($car->images))
                                @foreach($car->images as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset(CAR_PATH . $image) }}" class="d-block w-100 rounded"
                                            style="height:250px; object-fit:cover;">
                                    </div>

                                @endforeach

                            @else
                                <div class="carousel-item active">
                                    <img src="{{ asset('no-image.png') }}" class="d-block w-100 rounded"
                                        style="height:250px; object-fit:cover;">
                                </div>
                            @endif
                        </div>
                        <div class="carousel-indicators">
                            @if($car->images && count($car->images))
                                @foreach($car->images as $key => $image)
                                    <button type="button" data-bs-target="#carCarousel" data-bs-slide-to="{{ $key }}"
                                        class="{{ $key == 0 ? 'active' : '' }}"
                                        aria-current="{{ $key == 0 ? 'true' : 'false' }}"></button>
                                @endforeach
                            @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                </div>
            </div>

            @if($car->banner)
                <div class="card shadow-sm">
                    <div class="card-body  text-center">
                        <h6 class="text-muted mb-2">Banner</h6>
                        <img src="{{ asset(CAR_PATH . $car->banner) }}" class="img-fluid rounded shadow-sm"
                            style="height:120px; object-fit:contain;">
                    </div>


                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-2 text-center">

                        <p class="banner"> No Banner</p>
                    </div>


                </div>
            @endif


        </div>

        <!-- Right: Car Info -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">

                    <h3 class="mb-1">{{ $car->name }}</h3>
                    <div class="mb-3 mt-3">
                        <span class="badge bg-primary me-2 ">{{ $car->category->name ?? 'No Category' }}</span>
                        @if($car->is_recommended)
                            <span class="badge bg-success">Recommended</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        @if($car->location_id && isset($car->map) && $car->map->google_map_link)
                            <div class="ratio ratio-16x9 map">
                                {!! $car->map->google_map_link !!}
                            </div>
                        @elseif(!empty($car->location))
                            <div class="p-3 bg-light rounded border">
                                <h6 class="text-muted mb-1"><i class="ri-map-pin-2-line text-danger me-1"></i> Location</h6>
                                <p class="mb-0 fw-semibold fs-15 text-dark">{{ $car->location }}</p>
                            </div>
                        @endif
                    </div>


                    <!-- Hidden based on client request. -->
                    {{--
                    <div class="mb-3">
                        <h6 class="text-muted">Pricing</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th>1-4 Days</th>
                                        <td>{{ number_format($car->less_four_days_price,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>5-6 Days</th>
                                        <td>{{ number_format($car->five_six_days_price,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Weekly</th>
                                        <td>{{ number_format($car->week_price,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Monthly</th>
                                        <td>{{ number_format($car->month_price,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Maximum</th>
                                        <td>{{ number_format($car->max_price,2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Insurance</th>
                                        <td>{{ number_format($car->insurance_price,2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-muted">Included Accessories</h6>
                        @if($car->free_accessory && count($car->free_accessory))
                        @foreach($car->free_accessory as $acc)
                        <span class="badge bg-light text-dark border me-1 mb-1">
                            {{ $accessories->where('id',$acc)->first()->name ?? '' }}
                        </span><br>
                        @endforeach
                        @else
                        <p class="mb-0">No accessories included.</p>
                        @endif
                    </div>
                    --}}
                </div>
            </div>

        </div>
        <div cass="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Description</h5>
                    <div class="ck-content mb-4">
                        {!! $car->description ?? '<p class="text-muted">No description available.</p>' !!}
                    </div>

                    <h5 class="mb-4">Technical Specifications</h5>

                    <div class="row">

                        <!-- Engine -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-speed-up-line fs-3 text-danger"></i>
                                </div>
                                <div>
                                    <small class="text-muted">ENGINE</small>
                                    <h6 class="mb-0">{{ $car->engine ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Power -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-flashlight-line fs-3 text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted">POWER</small>
                                    <h6 class="mb-0">{{ $car->power ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Seat Height -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-ruler-line fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted">SEAT HEIGHT</small>
                                    <h6 class="mb-0">{{ $car->seat_height ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Weight -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-scales-2-line fs-3 text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted">WEIGHT</small>
                                    <h6 class="mb-0">{{ $car->weight ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Tank Capacity -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-gas-station-line fs-3 text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted">TANK CAPACITY</small>
                                    <h6 class="mb-0">{{ $car->tank_capacity ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Luggage -->
                        <div class="col-md-4 mb-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-briefcase-4-line fs-3 text-dark"></i>
                                </div>
                                <div>
                                    <small class="text-muted">LUGGAGE</small>
                                    <h6 class="mb-0">{{ $car->luggage ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection