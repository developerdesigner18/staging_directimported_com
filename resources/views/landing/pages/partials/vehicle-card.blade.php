@php
    $getImgSrc = function ($path) {
        if (empty($path)) {
            return asset('uploads/user_documents/default.jpg');
        }
        $relativePath = CAR_PATH . $path;
        return asset($relativePath);
    };


    $mainImg = !empty($car->banner)
        ? $getImgSrc($car->banner)
        : (!empty($car->images) && is_array($car->images) && count(array_filter($car->images)) > 0
            ? $getImgSrc(current(array_filter($car->images)))
            : asset('uploads/user_documents/default.jpg'));

    $thumbs = [];
    if (!empty($car->images) && is_array($car->images)) {
        $imgList = array_slice(array_filter($car->images), 0, 4);
        foreach ($imgList as $img) {
            if (!empty($img)) {
                $thumbs[] = $getImgSrc($img);
            }
        }
    }
    if (empty($thumbs)) {
        $thumbs = [$mainImg];
    }

    $inspection = 'Available';
    if ($car->status) {
        $inspection = is_object($car->status) ? $car->status->label() : (string) $car->status;
    }

    $gradeStr = $car->auctionGrade->grade ?? '';
    $remarksStr = $car->auctionGrade->remarks ?? '';
    $repairHistory = trim(($gradeStr ? 'Grade ' . $gradeStr : '') . ($remarksStr ? ' ' . $remarksStr : '')) ?: 'N/A';
    $ratingCert = $gradeStr ? ('Grade ' . $gradeStr) : ($remarksStr ?: 'N/A');

    $makeName = $car->manufacturer->name ?? ($car->spec->make ?? '');
    $modelName = $car->model ?? '';
    $displayName = !empty($car->name)
        ? $car->name
        : (!empty($car->card_header)
            ? $car->card_header
            : (trim(($makeName ? $makeName . ' ' : '') . $modelName) ?: 'Vehicle'));

    $typeName = $car->spec->type ?? '';
    $yearVal = $car->year ?? ($car->spec->model_year ?? 'N/A');
    $mileageVal = isset($car->spec->odometer) && $car->spec->odometer ? number_format($car->spec->odometer) . ' km' : 'N/A';
    $engineVal = $car->spec->engine ?? 'N/A';
    $transmissionVal = !empty($car->spec->transmission_custom)
        ? $car->spec->transmission_custom
        : ($car->spec->transmission ?? 'N/A');
    $locationVal = $car->location ?? 'N/A';
    $priceVal = number_format((float) ($car->vehicle_price ?? 0));
    $detailUrl = route('car.single', $car->slug ?? $car->id);
@endphp

<div class="listing-card" data-make="{{ $makeName }}" data-model="{{ strtolower($modelName) }}"
    data-type="{{ strtolower($typeName) }}" data-year="{{ is_numeric($yearVal) ? $yearVal : 0 }}"
    data-mileage="{{ (int) ($car->spec->odometer ?? 0) }}" data-location="{{ $locationVal }}"
    data-price="{{ (float) ($car->vehicle_price ?? 0) }}">
    <div class="gallery">
        <img src="{{ $mainImg }}" alt="{{ $displayName }}" class="main-img">
        <div class="thumbnail-grid">
            @foreach(array_slice($thumbs, 0, 4) as $t)
                <img src="{{ $t }}" alt="thumb" class="thumb-img"
                    onclick="this.closest('.gallery').querySelector('.main-img').src='{{ $t }}'">
            @endforeach
        </div>
    </div>

    <div class="details">
        <div class="header-row">
            <h2 class="vehicle-title">
                {{ $displayName }}
            </h2>
            <div class="status-badge">{{ $inspection }}</div>
        </div>

        <div class="specs-grid">
            <div class="spec-item">
                <span class="spec-label">Model<br>Year</span>
                <span class="spec-value">{{ $yearVal }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Mileage</span>
                <span class="spec-value">{{ $mileageVal }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Engine<br>Displacement</span>
                <span class="spec-value">{{ $engineVal }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Transmission</span>
                <span class="spec-value">{{ $transmissionVal }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Vehicle<br>Inspection</span>
                <span class="spec-value">{{ $inspection }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Repair<br>History</span>
                <span class="spec-value">{{ $repairHistory }}</span>
            </div>

            <div class="spec-item">
                <span class="spec-label">Location</span>
                <span class="spec-value">{!! $locationVal ? str_replace(' ', '<br>', e($locationVal)) : 'N/A' !!}</span>
            </div>
        </div>

        <div class="action-area">
            <div class="price-section">
                <span class="price-label">Total Base payment</span>
                <div class="price-value">¥{{ $priceVal }}</div>
            </div>

            <div class="buttons-section">
                <button class="btn-fav" title="Add to Favorites">★</button>

                <a href="{{ $detailUrl }}" class="btn-quote" style="text-decoration:none;">
                    <span class="btn-text-main">MORE INFO CHECK HERE</span>
                    <span class="btn-text-sub">and Contact us</span>
                </a>
            </div>
        </div>

        <div class="fine-print">
            ** Plus FOB or CIF freight charges not inc transport
        </div>

        <div class="evaluation-row">
            <span>Vehicle Quality Evaluation</span>

            <span>
                Rating Certification
                <span class="eval-grade">{{ $ratingCert }}</span>
            </span>

            <span>
                Interior:
                <span class="stars">{{ $car->spec->interior_grade_stars ?? '' }}</span>
            </span>

            <span>
                Exterior:
                <span class="stars">{{ $car->spec->exterior_grade_stars ?? '' }}</span>
            </span>
        </div>
    </div>
</div>