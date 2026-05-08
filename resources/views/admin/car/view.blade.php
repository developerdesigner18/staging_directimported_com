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
                                        <img src="{{ asset(CAR_PATH.$image) }}"
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
                            @if($car->images && count($car->images))
                                @foreach($car->images as $key => $image)
                                    <button type="button" data-bs-target="#carCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key==0 ? 'active' : '' }}" aria-current="{{ $key==0 ? 'true':'false' }}"></button>
                                @endforeach
                            @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                </div>
            </div>

            @if($car->banner)
                <div class="card shadow-sm">
                    <div class="card-body  text-center">
                        <h6 class="text-muted mb-2">Banner</h6>
                        <img src="{{ asset(CAR_PATH.$car->banner) }}"
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

        <!-- Right: Car Info -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h3 class="mb-1">{{ $car->name }}</h3>
                    <div class="mb-3 mt-3">
                        <span class="badge bg-primary me-2">{{ $car->category->name ?? 'No Category' }}</span>
                        @if($car->is_recommended)
                            <span class="badge bg-success me-2">Recommended</span>
                        @endif
                        <span class="badge bg-info me-2">ID: {{ $car->vehicle_id }}</span>
                        <span class="badge bg-dark">{{ $car->status->label() }}</span>
                    </div>

                    <div class="mb-3">
                        @if($car->location_id && isset($car->map) && $car->map->google_map_link)
                            <div class="ratio ratio-16x9 map">
                                {!! $car->map->google_map_link !!}
                            </div>
                        @endif
                    </div>


                    <div class="mb-3">
                        <h6 class="text-muted">Pricing</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <tbody>
                                    <tr><th>1-4 Days</th><td>{{ number_format($car->less_four_days_price,2) }}</td></tr>
                                    <tr><th>5-6 Days</th><td>{{ number_format($car->five_six_days_price,2) }}</td></tr>
                                    <tr><th>Weekly</th><td>{{ number_format($car->week_price,2) }}</td></tr>
                                    <tr><th>Monthly</th><td>{{ number_format($car->month_price,2) }}</td></tr>
                                    <tr><th>Maximum</th><td>{{ number_format($car->max_price,2) }}</td></tr>
                                    <tr><th>Insurance</th><td>{{ number_format($car->insurance_price,2) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Included Accessories</h6>
                        @if($car->free_accessory && count($car->free_accessory))
                            @foreach($car->free_accessory as $acc)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ $accessories->where('id',$acc)->first()->name ?? '' }}
                                </span>
                            @endforeach
                        @else
                            <p class="mb-0">No accessories included.</p>
                        @endif
                    </div>

                    @if($car->auctionGrade)
                    <div class="mb-3">
                        <h6 class="text-muted">Auction Grade</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold; font-size: 1.2rem;">
                                    {{ $car->auctionGrade->grade }}
                                </div>
                                <h6 class="mb-0">{{ $car->auctionGrade->name }}</h6>
                            </div>
                            @if($car->auctionGrade->remarks)
                                <p class="mb-0 text-muted small"><strong>Remarks:</strong> {{ $car->auctionGrade->remarks }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
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
                                    @php
                                        $specs = [
                                            ['label' => 'MAKE', 'value' => $car->spec->make ?? '-', 'icon' => 'bx bxs-car', 'color' => 'text-primary'],
                                            ['label' => 'MODEL YEAR', 'value' => $car->spec->model_year ?? '-', 'icon' => 'bx bx-calendar', 'color' => 'text-success'],
                                            ['label' => 'ENGINE', 'value' => $car->spec->engine ?? '-', 'icon' => 'bx bx-tachometer', 'color' => 'text-danger'],
                                            ['label' => 'ODOMETER', 'value' => ($car->spec->odometer ?? '-') . ' km', 'icon' => 'bx bxs-dashboard', 'color' => 'text-warning'],
                                            ['label' => 'FUEL TYPE', 'value' => $car->spec->fuel_type ?? '-', 'icon' => 'bx bxs-gas-pump', 'color' => 'text-info'],
                                            ['label' => 'TRANSMISSION', 'value' => $car->spec->transmission ?? '-', 'icon' => 'bx bx-cog', 'color' => 'text-secondary'],
                                            ['label' => 'BODY TYPE', 'value' => $car->spec->body_type ?? '-', 'icon' => 'bx bx-grid-alt', 'color' => 'text-dark'],
                                            ['label' => 'EXTERIOR COLOR', 'value' => $car->spec->exterior_color ?? '-', 'icon' => 'bx bx-palette', 'color' => 'text-primary'],
                                            ['label' => 'INTERIOR COLOR', 'value' => $car->spec->interior_color ?? '-', 'icon' => 'bx bx-brush', 'color' => 'text-info'],
                                        ];
                                    @endphp

                                    @foreach($specs as $spec)
                                    <div class="col-md-4 mb-4">
                                        <div class="border rounded p-3 h-100 d-flex align-items-center shadow-sm hover-shadow transition">
                                            <div class="me-3">
                                                <i class="{{ $spec['icon'] }} fs-3 {{ $spec['color'] }}"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted fw-bold">{{ $spec['label'] }}</small>
                                                <h6 class="mb-0">{{ $spec['value'] }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
        </div>
</div>

@endsection



