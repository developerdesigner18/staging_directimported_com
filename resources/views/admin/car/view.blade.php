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

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end align-items-center gap-2">
            <a href="{{ route('admin.car.edit', $car->id) }}"
                class="btn btn-sm btn-success d-flex align-items-center gap-1">
                <i class="ri-pencil-line"></i> Edit Vehicle
            </a>
            <a href="{{ route('car.single', ['slug' => $car->stock_id]) }}" target="_blank"
                class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                <i class="ri-external-link-line"></i> View Vehicle Details
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left: Image Slider -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between p-2">
                        <h6 class="text-muted mb-0">Vehicle Images</h6>
                        @if(isset($car) && $car->id)
                            <a href="{{ route('admin.car.download-images', $car->id) }}"
                                class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                <i class="ri-download-cloud-2-line"></i> Download Images
                            </a>
                        @endif
                    </div>
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
                    <div class="mb-3 mt-3 d-flex align-items-center gap-2">
                        @php
                            $vStatus = is_object($car->status) ? $car->status->value : (string) $car->status;
                            $vLabel = is_object($car->status) ? $car->status->label() : ucfirst($vStatus ?: 'Available');
                        @endphp
                        @if($vStatus === 'draft')
                            <span class="badge bg-warning text-dark"><i class="ri-draft-line me-1"></i> Draft</span>
                        @elseif($vStatus === 'sold')
                            <span class="badge bg-danger"><i class="ri-close-circle-line me-1"></i> SOLD</span>
                        @elseif($vStatus === 'under_offer')
                            <span class="badge text-white" style="background-color: #fd7e14;"><i class="ri-time-line me-1"></i> Under Offer</span>
                        @else
                            <span class="badge bg-success"><i class="ri-checkbox-circle-line me-1"></i> {{ $vLabel }}</span>
                        @endif

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
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Description</h5>
                    <div class="ck-content mb-4">
                        {!! $car->description ?? '<p class="text-muted">No description available.</p>' !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">🔒 Private Notes (Internal Use Only)</h5>
                </div>
                <div class="card-body">
                    <label for="private_notes" class="form-label">Source URL & Location Details</label>
                    <textarea class="form-control" id="private_notes" rows="4" readonly
                        placeholder="Add web addresses, specific physical locations, or internal notes here. These will not be visible on the frontend website.">{{ $car->private_notes ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

@endsection