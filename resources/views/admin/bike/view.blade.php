@extends('admin.master')
@section('title','Slider')
@push('style')
<style>
.banner{
    height: 120px;
    object-fit: contain;
    text-align: center;
    display: flex
;
    justify-content: center;
    align-items: center;
}
.map{max-width: 400px; height: auto; border-radius: 8px; overflow: hidden;}
</style>
@endpush
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent mb-3">
                <h4 class="mb-sm-0">Bike Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.bike.index') }}">Bikes</a></li>
                        <li class="breadcrumb-item active">{{ $bike->name }}</li>
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
                    <div id="bikeCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($bike->images && count($bike->images))
                                @foreach($bike->images as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset(BIKE_PATH.$image) }}"
                                             class="d-block w-100 rounded"
                                             style="height:250px; object-fit:cover;">
                                    </div>

                                @endforeach

                            @else
                                <div class="carousel-item active">
                                    <img src="{{ asset('no-image.png') }}" class="d-block w-100 rounded" style="height:250px; object-fit:cover;">
                                </div>
                            @endif
                        </div>
                        <div class="carousel-indicators">
                            @if($bike->images && count($bike->images))
                                @foreach($bike->images as $key => $image)
                                    <button type="button" data-bs-target="#bikeCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key==0 ? 'active' : '' }}" aria-current="{{ $key==0 ? 'true':'false' }}"></button>
                                @endforeach
                            @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bikeCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bikeCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                </div>
            </div>

            @if($bike->banner)
                <div class="card shadow-sm">
                    <div class="card-body  text-center">
                        <h6 class="text-muted mb-2">Banner</h6>
                        <img src="{{ asset(BIKE_PATH.$bike->banner) }}"
                             class="img-fluid rounded shadow-sm"
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

        <!-- Right: Bike Info -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">

                    <h3 class="mb-1">{{ $bike->name }}</h3>
           <div class="mb-3 mt-3">
                                            <span class="badge bg-primary me-2 ">{{ $bike->category->name ?? 'No Category' }}</span>
                                            @if($bike->is_recommended)
                                                <span class="badge bg-success">Recommended</span>
                                            @endif
                                        </div>

                    <div class="mb-3">
                        @if($bike->location_id && isset($bike->map) && $bike->map->google_map_link)
                            <div class="ratio ratio-16x9 map">
                                {!! $bike->map->google_map_link !!}
                            </div>
                        @endif
                    </div>


                    <div class="mb-3">
                        <h6 class="text-muted">Pricing</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <tbody>
                                    <tr><th>1-4 Days</th><td>{{ number_format($bike->less_four_days_price,2) }}</td></tr>
                                    <tr><th>5-6 Days</th><td>{{ number_format($bike->five_six_days_price,2) }}</td></tr>
                                    <tr><th>Weekly</th><td>{{ number_format($bike->week_price,2) }}</td></tr>
                                    <tr><th>Monthly</th><td>{{ number_format($bike->month_price,2) }}</td></tr>
                                    <tr><th>Maximum</th><td>{{ number_format($bike->max_price,2) }}</td></tr>
                                    <tr><th>Insurance</th><td>{{ number_format($bike->insurance_price,2) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-muted">Included Accessories</h6>
                        @if($bike->free_accessory && count($bike->free_accessory))
                            @foreach($bike->free_accessory as $acc)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ $accessories->where('id',$acc)->first()->name ?? '' }}
                                </span><br>
                            @endforeach
                        @else
                            <p class="mb-0">No accessories included.</p>
                        @endif
                    </div>
                </div>
            </div>

    </div>
<div cass="col-12">
<div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Description</h5>
                                <div class="ck-content mb-4">
                                    {!! $bike->description ?? '<p class="text-muted">No description available.</p>' !!}
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
                                                <h6 class="mb-0">{{ $bike->engine ?? '-' }}</h6>
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
                                                <h6 class="mb-0">{{ $bike->power ?? '-' }}</h6>
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
                                                <h6 class="mb-0">{{ $bike->seat_height ?? '-' }}</h6>
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
                                                <h6 class="mb-0">{{ $bike->weight ?? '-' }}</h6>
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
                                                <h6 class="mb-0">{{ $bike->tank_capacity ?? '-' }}</h6>
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
                                                <h6 class="mb-0">{{ $bike->luggage ?? '-' }}</h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
        </div>
</div>

@endsection



