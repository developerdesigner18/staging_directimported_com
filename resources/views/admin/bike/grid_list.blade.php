@php
    $groupedBikes = $bikes->groupBy('category_id');
@endphp

@foreach($ccRanges as $rangeName => $rangeData)
    @php
        $bikesInRange = collect();
        foreach($rangeData['category_ids'] as $catId) {
            if(isset($groupedBikes[$catId])) {
                $bikesInRange = $bikesInRange->merge($groupedBikes[$catId]);
            }
        }
        $bikesInRange = $bikesInRange->sortBy('sort_order')->values();
    @endphp

    @if($bikesInRange->count() > 0)
        <div class="category-section-header mt-4 mb-3">
            <h5 class="cc-range-title border-bottom pb-2">{{ $rangeName }}</h5>
        </div>
        <div class="row nested-sortable g-3 bike-sortable-group" id="bike-sortable-{{ Str::slug($rangeName) }}">
            @foreach($bikesInRange as $bike)
                <div class="col-xxl-3 col-lg-6 col-md-6 sortable-item" data-id="{{ $bike->id }}" id="slider-card-{{$bike->id}}">
                    <div class="card overflow-hidden blog-grid-card list-group-item nested-1">
                        <div class="position-relative overflow-hidden">
                            <a href="{{ route('admin.bike.view',[$bike->id])}}">
                                <img src="{{ asset(BIKE_PATH.$bike->images[0]) }}"
                                     alt="{{ $bike->name }}"
                                     class="blog-img object-fit-cover w-100"
                                     style="height: 200px;">
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $bike->name }}</h5>
                            <span class="text-muted">{{ dateToHuman($bike->created_at) }}</span>
                            <br>
                            <div class="action-btn text-end">
                                <a class="btn btn-success btn-sm" href="{{ route('admin.bike.edit', $bike->id) }}" data-bs-toggle="tooltip" title="Edit Bike">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteBike('{{$bike->id}}',this)" data-bs-toggle="tooltip" title="Delete Bike"><i
                                            class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach

@if($bikes->count() == 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <h4 class="text-muted">No bike found</h4>
                    <p class="text-muted">Create your first bike by clicking the "Add New" button</p>
                </div>
            </div>
        </div>
    </div>
@endif
