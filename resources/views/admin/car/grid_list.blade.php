@php
    $groupedCars = $cars->groupBy('category_id');
@endphp

@foreach($ccRanges as $rangeName => $rangeData)
    @php
        $carsInRange = collect();
        foreach ($rangeData['category_ids'] as $catId) {
            if (isset($groupedCars[$catId])) {
                $carsInRange = $carsInRange->merge($groupedCars[$catId]);
            }
        }
        $carsInRange = $carsInRange->sortBy('sort_order')->values();
    @endphp

    @if($carsInRange->count() > 0)
        <div class="category-section-header mt-4 mb-3">
            <h5 class="cc-range-title border-bottom pb-2">{{ $rangeName }}</h5>
        </div>
        <div class="row nested-sortable g-3 car-sortable-group" id="car-sortable-{{ Str::slug($rangeName) }}">
            @foreach($carsInRange as $car)
                <div class="col-xxl-3 col-lg-6 col-md-6 sortable-item" data-id="{{ $car->id }}" id="slider-card-{{$car->id}}">
                    <div class="card overflow-hidden blog-grid-card list-group-item nested-1">
                        <div class="position-relative overflow-hidden">
                            <a href="{{ route('admin.car.view', [$car->id])}}">
                                @php
                                    $firstImg = (!empty($car->images) && is_array($car->images) && isset($car->images[0]) && !empty($car->images[0])) ? $car->images[0] : null;
                                @endphp
                                <img src="{{ $firstImg ? asset(CAR_PATH . $firstImg) : asset('uploads/user_documents/default.jpg') }}" alt="{{ $car->name }}"
                                    class="blog-img object-fit-cover w-100" style="height: 200px;">
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $car->name }}</h5>
                            <span class="text-muted">{{ dateToHuman($car->created_at) }}</span>
                            <br>
                            <div class="action-btn text-end">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.car.specs', $car->id) }}"
                                    data-bs-toggle="tooltip" title="Manage Specs">
                                    <i class="ri-settings-4-line"></i>
                                </a>
                                <a class="btn btn-success btn-sm" href="{{ route('admin.car.edit', $car->id) }}"
                                    data-bs-toggle="tooltip" title="Edit Car">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteCar('{{$car->id}}',this)"
                                    data-bs-toggle="tooltip" title="Delete Car"><i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach

@if($cars->count() == 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <h4 class="text-muted">No car found</h4>
                    <p class="text-muted">Create your first car by clicking the "Add New" button</p>
                </div>
            </div>
        </div>
    </div>
@endif