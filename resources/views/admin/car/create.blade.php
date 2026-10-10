@extends('admin.master')
@section('title', 'Create Car')

@section('style')
    <style>
        /* FilePond Side-by-Side Grid Layout (10 images per row) */
        .filepond--root {
            margin-bottom: 0 !important;
        }

        .filepond--item {
            width: calc(10% - 0.6em) !important;
            height: 100px !important;
        }

        @media (max-width: 768px) {
            .filepond--item {
                width: calc(20% - 0.5em) !important;
            }
        }

        @media (max-width: 480px) {
            .filepond--item {
                width: calc(33.33% - 0.5em) !important;
            }
        }

        .color-picker-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            margin-top: 8px;
            overflow-x: auto;
            padding: 12px 0px;
        }

        .color-picker-container::-webkit-scrollbar {
            height: 6px;
        }

        .color-picker-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .color-picker-container::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .color-picker-container::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        .color-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            width: 55px;
            flex-shrink: 0;
        }

        .color-option input[type="radio"] {
            display: none;
        }

        .color-box {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 1px solid #CBD5E0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .color-name {
            font-size: 0.75rem;
            color: #4A5568;
            text-align: center;
            font-weight: 500;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .color-option:hover .color-box {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .color-option input[type="radio"]:checked+.color-box {
            outline: 3px solid #1E50A2;
            outline-offset: 3px;
            border-color: transparent;
            transform: scale(1.05);
        }

        .color-option input[type="radio"]:checked~.color-name {
            color: #1E50A2;
            font-weight: 700;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear,
        .select2-selection__clear {
            display: none !important;
        }

        /* Banner Dropzone & Drag-and-Drop Styles */
        .banner-dropzone {
            transition: all 0.25s ease-in-out;
            min-height: 120px;
            background-color: #f8f9fa;
            border-color: #cbd5e1;
        }

        .banner-dropzone.is-dragging-pond {
            border-color: #1E50A2;
            background-color: #edf2f7;
            box-shadow: 0 0 12px rgba(30, 80, 162, 0.2);
        }

        .banner-dropzone.is-drag-over {
            border-color: #0ab39c;
            background-color: #e6f7f5;
            transform: scale(1.005);
            box-shadow: 0 0 16px rgba(10, 179, 156, 0.3);
        }

        .filepond--item {
            cursor: grab;
            -webkit-user-drag: element;
        }

        .filepond--item:active {
            cursor: grabbing;
        }

        .image-preview-container {
            cursor: grab;
            -webkit-user-drag: element;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Create Car</h4>

                <div class="page-title-right d-flex align-items-center gap-3">
                    <div id="auto-save-status" class="fs-13 fw-semibold text-muted" style="display:none;"></div>
                    {{-- CarSensor Import Button --}}
                    <button type="button" id="btn-open-carsensor-modal"
                        class="btn btn-warning d-flex align-items-center gap-2"
                        title="Import vehicle data from any car listing website">
                        <i class="ri-import-line"></i>
                        <span>Import from Listing URL</span>
                    </button>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Car</a></li>
                        <li class="breadcrumb-item active">Create Car</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @include('admin.car.partials.carsensor-modal', ['isEdit' => false])

    <div class="row">
        <div class="col-12">
            <form id="addForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="car_id" id="draft_car_id" value="{{ optional($draft)->id }}">

                @if(isset($draft) && $draft)
                    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
                        <i class="ri-history-line fs-18 me-2"></i>
                        <div>
                            <strong>Draft Restored:</strong> Restored saved draft for
                            <strong>{{ $draft->name ?: ($draft->model ?: 'Untitled Draft') }}</strong>
                            (Saved {{ $draft->updated_at ? $draft->updated_at->diffForHumans() : 'recently' }}).
                            <a href="{{ route('admin.car.create', ['new' => 1]) }}" class="btn btn-sm btn-outline-info ms-2 py-0">Start Fresh Listing</a>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- CarSensor Import Summary (populated by JS after import) --}}
                {{-- NOTE: This panel is already rendered above the rows, keeping here as anchor --}}

                <!-- 1. Banner & Car Images Section (Top) -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="banner" class="form-label d-flex align-items-center justify-content-between">
                                <span>{{ admin_label('car_form', 'banner', 'Banner') }}</span>
                                <small class="text-muted"><i class="ri-drag-drop-line me-1"></i>Drag image from Car Images
                                    below to set as Banner</small>
                            </label>
                            <label for="banner" id="banner-dropzone"
                                class="custom-file-label w-100 banner-dropzone p-3 border border-2 border-dashed rounded position-relative transition-all d-block">
                                <input type="file" id="banner" class="form-control file-preview" name="banner"
                                    accept="image/*">
                                <label id="banner-error" class="text-danger error" style="display: none"></label>
                                <div class="uploaded-preview mt-2" style="max-width: 280px;"></div>
                                <div class="banner-drop-overlay d-none position-absolute top-0 start-0 w-100 h-100 flex-column align-items-center justify-content-center bg-white bg-opacity-90 rounded"
                                    style="z-index: 10; pointer-events: none; backdrop-filter: blur(2px);">
                                    <i class="ri-image-add-line fs-1 text-primary mb-1"></i>
                                    <span class="fw-bold text-primary banner-drop-text">Drop here to set as Banner
                                        Image</span>
                                    <small class="text-muted">Replaces banner preview instantly</small>
                                </div>
                            </label>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="images"
                                    class="form-label mb-0">{{ admin_label('car_form', 'car_images', 'Car Images') }}</label>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="filepond" id="images" name="images[]" multiple
                                    data-allow-reorder="true">
                            </div>

                            @if(isset($draft) && $draft->images && count(array_filter($draft->images)))
                                <div class="mt-4 pt-3 border-top">
                                    <label class="form-label text-muted fw-semibold mb-2">Existing Car Images (Drag to reorder):</label>
                                    <div id="sortable-images" class="d-flex flex-wrap gap-2">
                                        @foreach($draft->images as $image)
                                            @if(!empty($image))
                                                <div class="image-preview-container me-2 mb-2 position-relative" data-image="{{ $image }}"
                                                    style="cursor: grab;">
                                                    <img src="{{ asset(CAR_PATH . $image) }}" class="img-thumbnail" style="height:100px;"
                                                        draggable="false">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-image position-absolute top-0 end-0"
                                                        data-image="{{ $image }}">×</button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="image_order" id="image_order">
                                </div>
                            @endif

                            <input type="hidden" id="removed_images" name="removed_images">
                            <label id="images-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Download Images Button -->
                        <div class="text-center mt-3 pt-2">
                            <button type="button" id="btn-download-pond-images"
                                class="btn btn-success px-4 py-2 d-inline-flex align-items-center gap-2">
                                <i class="ri-download-cloud-2-line"></i> Download Images
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Basic Info Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Make + Model + Year -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="manufacturer_id"
                                    class="form-label mb-2">{{ admin_label('car_form', 'make', 'Make') }}</label>
                                <select class="form-select select2" id="manufacturer_id" name="manufacturer_id">
                                    <option value="">Select Make</option>
                                    @foreach($manufacturers ?? [] as $manufacturer)
                                        <option value="{{ optional($manufacturer)->id }}" {{ (isset($draft) && $draft->manufacturer_id == optional($manufacturer)->id) ? 'selected' : '' }}>
                                            {{ optional($manufacturer)->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="manufacturer_id-error" class="text-danger error" for="manufacturer_id"
                                    style="display: none"></label>
                            </div>

                            <div class="col-md-4">
                                <label for="model"
                                    class="form-label mb-2">{{ admin_label('car_form', 'model', 'Model') }}</label>
                                <input type="text" class="form-control" id="model" name="model"
                                    value="{{ old('model', optional($draft)->model) }}"
                                    placeholder="Enter model name">
                                <label id="model-error" class="text-danger error" for="model" style="display: none"></label>
                            </div>

                            <div class="col-md-4">
                                <label for="year"
                                    class="form-label mb-2">{{ admin_label('car_form', 'year', 'Year') }}</label>
                                <select class="form-select select2" id="year" name="year">
                                    <option value="">Select Year</option>
                                    @for($y = date('Y') + 1; $y >= 1970; $y--)
                                        <option value="{{ $y }}" {{ (isset($draft) && $draft->year == $y) ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <label id="year-error" class="text-danger error" for="year" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Category + Status + Auction Grade + Location -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <label for="status"
                                    class="form-label mb-2">{{ admin_label('car_form', 'status', 'Status') }}</label>
                                <select class="form-select select2" id="status" name="status">
                                    <option value="">Select Status</option>
                                    @foreach(\App\Enum\CarStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ (isset($draft) && $draft->status === $status) ? 'selected' : '' }}>{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                                <label id="status-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <div class="col-lg-4">
                                <label for="auction_grade_id"
                                    class="form-label mb-2">{{ admin_label('car_form', 'auction_grade', 'Auction Grade') }}</label>
                                <select class="form-select select2" id="auction_grade_id" name="auction_grade_id">
                                    <option value="">Select Auction Grade</option>
                                    @foreach($auctionGrades ?? [] as $grade)
                                        <option value="{{ optional($grade)->id }}" {{ (isset($draft) && $draft->auction_grade_id == optional($grade)->id) ? 'selected' : '' }}>
                                            {{ optional($grade)->grade }} {{ optional($grade)->remarks }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="auction_grade_id-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <div class="col-lg-4">
                                <label for="location"
                                    class="form-label mb-2">{{ admin_label('car_form', 'location', 'Location') }}</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    value="{{ old('location', optional($draft)->location) }}"
                                    placeholder="e.g. Japan">
                                <label id="location-error" class="text-danger error" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Vehicle Price (¥) -->
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label for="vehicle_price"
                                    class="form-label mb-2">{{ admin_label('car_form', 'vehicle_price', 'Vehicle Price (¥)') }}</label>
                                <input type="text" class="form-control" id="vehicle_price" name="vehicle_price"
                                    value="{{ old('vehicle_price', optional($draft)->vehicle_price) }}"
                                    placeholder="e.g. 15000000">
                                <label id="vehicle_price-error" class="text-danger error" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Vehicle ID Type + Recommended Car -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <label
                                    class="form-label d-block mb-2">{{ admin_label('car_form', 'vehicle_id_type', 'Vehicle ID Type') }}</label>
                                <div class="d-flex align-items-center gap-4 py-1">
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="vehicle_id_type"
                                            id="vehicle_id_type_auto" value="auto" {{ (!isset($draft) || empty($draft->vehicle_id)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="vehicle_id_type_auto">Auto Generate</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vehicle_id_type"
                                            id="vehicle_id_type_manual" value="manual" {{ (isset($draft) && !empty($draft->vehicle_id)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="vehicle_id_type_manual">Manual Entry</label>
                                    </div>
                                </div>

                                <div id="vehicle_id_container" class="mt-3" style="{{ (isset($draft) && !empty($draft->vehicle_id)) ? '' : 'display: none;' }}">
                                    <label for="vehicle_id"
                                        class="form-label mb-2">{{ admin_label('car_form', 'vehicle_id', 'Vehicle ID / Chassis No') }}</label>
                                    <input type="text" class="form-control" id="vehicle_id" name="vehicle_id"
                                        value="{{ old('vehicle_id', optional($draft)->vehicle_id) }}"
                                        placeholder="Enter Vehicle ID">
                                    <label id="vehicle_id-error" class="text-danger error" style="display: none"></label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label
                                    class="form-label d-block mb-2">{{ admin_label('car_form', 'recommended_car', 'Recommended Car') }}</label>
                                <div class="form-check form-switch py-1">
                                    <input type="checkbox" class="form-check-input" id="is_recommended"
                                        name="is_recommended" value="1">
                                    <label class="form-check-label ms-2" for="is_recommended">Mark as recommended</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Technical Specifications -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Technical Specifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="vin" class="form-label">{{ admin_label('car_form', 'vin', 'VIN #') }}</label>
                                <input type="text" id="vin" name="vin" class="form-control"
                                    placeholder="e.g. JTDKN36G000123">
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label for="body_type" class="form-label">{{ admin_label('car_form', 'body_type', 'Body
                                    Type') }}</label>
                                <select id="body_type" name="body_type" class="form-select select2">
                                    <option value="">Select Body Type</option>
                                    <option value="Motorcycle">Motorcycle</option>
                                    <option value="Truck">Truck</option>
                                    <option value="Pickup">Pickup</option>
                                    <option value="Van">Van</option>
                                    <option value="Wagon">Wagon</option>
                                    <option value="Coupe">Coupe</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Hatchback">Hatchback</option>
                                </select>
                            </div> --}}

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">{{ admin_label('car_form', 'type', 'Type') }}</label>
                                <select id="type" name="type" class="form-select select2">
                                    <option value="">Select Type</option>
                                    @foreach($carTypes ?? [] as $carType)
                                        <option value="{{ $carType->name }}">{{ $carType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="steering"
                                    class="form-label">{{ admin_label('car_form', 'steering', 'Steering') }}</label>
                                <select id="steering" name="steering" class="form-select select2">
                                    <option value="">Select Steering</option>
                                    <option value="RHD">RHD (Right-Hand Drive)</option>
                                    <option value="LHD">LHD (Left-Hand Drive)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="interior_grade"
                                    class="form-label">{{ admin_label('car_form', 'interior_grade', 'Interior Grade') }}</label>
                                <select id="interior_grade" name="interior_grade" class="form-select select2">
                                    <option value="">Select Interior Grade</option>
                                    <option value="A">A</option>
                                    <option value="B" selected>B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="exterior_grade"
                                    class="form-label">{{ admin_label('car_form', 'exterior_grade', 'Exterior Grade') }}</label>
                                <select id="exterior_grade" name="exterior_grade" class="form-select select2">
                                    <option value="">Select Exterior Grade</option>
                                    <option value="A">A</option>
                                    <option value="B" selected>B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="drive_type"
                                    class="form-label">{{ admin_label('car_form', 'drive_type', 'Drive Type') }}</label>
                                <select id="drive_type" name="drive_type" class="form-select select2">
                                    <option value="">Select Drive Type</option>
                                    <option value="2WD">2WD</option>
                                    <option value="4WD">4WD</option>
                                    <option value="AWD">AWD</option>
                                    <option value="FWD">FWD</option>
                                    <option value="RWD">RWD</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="engine"
                                    class="form-label">{{ admin_label('car_form', 'engine', 'Engine') }}</label>
                                <input type="text" id="engine" name="engine" class="form-control"
                                    placeholder="e.g. 2.0L Turbo">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fuel_type"
                                    class="form-label d-block">{{ admin_label('car_form', 'fuel_type', 'Fuel Type') }}</label>
                                <select id="fuel_type" name="fuel_type" class="form-select select2">
                                    <option value="">Select Fuel Type</option>
                                    <option value="Petrol">Petrol</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Electric">Electric</option>
                                </select>
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input fuel-custom-toggle" type="radio"
                                            name="fuel_custom_option" id="fuel_custom_off" value="0" checked>
                                        <label class="form-check-label text-muted" for="fuel_custom_off">Standard
                                            Only</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input fuel-custom-toggle" type="radio"
                                            name="fuel_custom_option" id="fuel_custom_on" value="1">
                                        <label class="form-check-label text-muted" for="fuel_custom_on">Add Custom
                                            Wording</label>
                                    </div>
                                </div>
                                <div id="fuel_custom_wrapper" class="mt-2 d-none">
                                    <input type="text" id="fuel_type_custom" name="fuel_type_custom" class="form-control"
                                        placeholder="Enter custom wording (optional)">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="transmission"
                                    class="form-label d-block">{{ admin_label('car_form', 'transmission', 'Transmission') }}</label>
                                <select id="transmission" name="transmission" class="form-select select2">
                                    <option value="">Select Transmission</option>
                                    <option value="Auto">Auto</option>
                                    <option value="MT">MT</option>
                                    <option value="5Spd">5Spd</option>
                                    <option value="6Spd">6Spd</option>
                                    <option value="DCT">DCT</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="d-flex align-items-center gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input transmission-custom-toggle" type="radio"
                                            name="transmission_custom_option" id="trans_custom_off" value="0" checked>
                                        <label class="form-check-label text-muted" for="trans_custom_off">Standard
                                            Only</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input transmission-custom-toggle" type="radio"
                                            name="transmission_custom_option" id="trans_custom_on" value="1">
                                        <label class="form-check-label text-muted" for="trans_custom_on">Add Custom
                                            Wording</label>
                                    </div>
                                </div>
                                <div id="trans_custom_wrapper" class="mt-2 d-none">
                                    <input type="text" id="transmission_custom" name="transmission_custom"
                                        class="form-control" placeholder="Enter custom wording (optional)">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="odometer"
                                    class="form-label">{{ admin_label('car_form', 'odometer', 'Odometer (km)') }}</label>
                                <input type="number" id="odometer" name="odometer" class="form-control"
                                    placeholder="e.g. 50000">
                            </div>

                            <div class="col-12 mb-3">
                                <label
                                    class="form-label">{{ admin_label('car_form', 'interior_color', 'Interior Color Selection') }}</label>
                                <div class="color-picker-container">
                                    @php
                                        $interiorColors = [
                                            ['name' => 'White', 'hex' => '#FFFFFF'],
                                            ['name' => 'Pearl', 'hex' => '#FDFDF0'],
                                            ['name' => 'Silver', 'hex' => '#C0C0C0'],
                                            ['name' => 'Gray', 'hex' => '#696969'],
                                            ['name' => 'Black', 'hex' => '#1A1A1A'],
                                            ['name' => 'Beige', 'hex' => '#E3DAC9'],
                                            ['name' => 'Brown', 'hex' => '#654321'],
                                            ['name' => 'Gold', 'hex' => '#D4AF37'],
                                            ['name' => 'Yellow', 'hex' => '#FFD700'],
                                            ['name' => 'Orange', 'hex' => '#FF8C00'],
                                            ['name' => 'Red', 'hex' => '#CC0000'],
                                            ['name' => 'Burgundy', 'hex' => '#800020'],
                                            ['name' => 'Pink', 'hex' => '#FFB6C1'],
                                            ['name' => 'Purple', 'hex' => '#4B0082'],
                                            ['name' => 'L. Blue', 'hex' => '#87CEFA'],
                                            ['name' => 'Blue', 'hex' => '#0047AB'],
                                            ['name' => 'Green', 'hex' => '#2E8B57'],
                                        ];
                                    @endphp
                                    @foreach($interiorColors as $col)
                                        <label class="color-option">
                                            <input type="radio" name="interior_color" value="{{ $col['hex'] }}">
                                            <div class="color-box" style="background-color: {{ $col['hex'] }};"></div>
                                            <span class="color-name">{{ $col['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label
                                    class="form-label">{{ admin_label('car_form', 'exterior_color', 'Exterior Color Selection') }}</label>
                                <div class="color-picker-container">
                                    @php
                                        $colors = [
                                            ['name' => 'White', 'hex' => '#FFFFFF'],
                                            ['name' => 'Pearl', 'hex' => '#FDFDF0'],
                                            ['name' => 'Silver', 'hex' => '#C0C0C0'],
                                            ['name' => 'Gray', 'hex' => '#696969'],
                                            ['name' => 'Black', 'hex' => '#1A1A1A'],
                                            ['name' => 'Beige', 'hex' => '#E3DAC9'],
                                            ['name' => 'Brown', 'hex' => '#654321'],
                                            ['name' => 'Gold', 'hex' => '#D4AF37'],
                                            ['name' => 'Yellow', 'hex' => '#FFD700'],
                                            ['name' => 'Orange', 'hex' => '#FF8C00'],
                                            ['name' => 'Red', 'hex' => '#CC0000'],
                                            ['name' => 'Burgundy', 'hex' => '#800020'],
                                            ['name' => 'Pink', 'hex' => '#FFB6C1'],
                                            ['name' => 'Purple', 'hex' => '#4B0082'],
                                            ['name' => 'L. Blue', 'hex' => '#87CEFA'],
                                            ['name' => 'Blue', 'hex' => '#0047AB'],
                                            ['name' => 'Green', 'hex' => '#2E8B57'],
                                        ];
                                    @endphp
                                    @foreach($colors as $col)
                                        <label class="color-option">
                                            <input type="radio" name="exterior_color" value="{{ $col['hex'] }}">
                                            <div class="color-box" style="background-color: {{ $col['hex'] }};"></div>
                                            <span class="color-name">{{ $col['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('admin.car.partials.ai-generator')

                <!-- 4. Description Section -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ admin_label('car_form', 'description', 'Description') }}</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="description_editor" rows="5"></textarea>
                        <input type="hidden" id="description" name="description">
                        <label id="description-error" class="text-danger error" style="display: none"></label>
                    </div>
                </div>

                <!-- 5. Card Settings Section -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Card Settings (Frontend Display)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="card_header"
                                class="form-label">{{ admin_label('car_form', 'card_header', 'Card Header') }}</label>
                            <input type="text" class="form-control" id="card_header" name="card_header"
                                value="{{ old('card_header', optional($draft)->card_header) }}"
                                placeholder="Enter card title (Frontend)">
                            <label id="card_header-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <div class="mb-3">
                            <label for="card_subtitle"
                                class="form-label">{{ admin_label('car_form', 'card_subtitle', 'Card Subtitle') }}</label>
                            <input type="text" class="form-control" id="card_subtitle" name="card_subtitle"
                                value="{{ old('card_subtitle', optional($draft)->card_subtitle) }}"
                                placeholder="Enter card subtitle (Frontend)">
                            <label id="card_subtitle-error" class="text-danger error" style="display: none"></label>
                        </div>
                    </div>
                </div>

                <!-- 6. Private Notes Section -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">🔒 Private Notes (Internal Use Only)</h5>
                    </div>
                    <div class="card-body">
                        <label for="private_notes" class="form-label">Source URL & Location Details</label>
                        <textarea class="form-control" id="private_notes" name="private_notes" rows="4"
                            placeholder="Add web addresses, specific physical locations, or internal notes here. These will not be visible on the frontend website.">{{ old('private_notes', optional($draft)->private_notes) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-start mb-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Create Vehicle</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        tinymce.init({
            selector: '#description_editor',
            height: 300,
            menubar: true,
            plugins: 'lists link image help wordcount code media table',
            toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image media tinydrive | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            file_picker_types: 'file image media',
            images_upload_url: "{{route('admin.tinymce.image.upload')}}",
            images_upload_handler: function (blobInfo, success, failure) {
                uploadFilePond(blobInfo.blob(), 'image').then(function (url) {
                    success(url);
                }).catch(function (error) {
                    failure('Image upload failed: ' + error);
                });
            },
            file_picker_callback: function (callback, value, meta) {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');

                if (meta.filetype === 'image') {
                    input.setAttribute('accept', 'image/*');
                } else if (meta.filetype === 'media') {
                    input.setAttribute('accept', 'video/*,audio/*');
                } else {
                    input.setAttribute('accept', '*');
                }

                input.onchange = function () {
                    const file = this.files[0];
                    uploadFilePond(file, meta.filetype).then(function (url) {
                        callback(url);
                    }).catch(function (error) {
                        alert('File upload failed: ' + error);
                    });
                };

                input.click();
            },
            media_live_embeds: true,
            media_url_resolver: function (data, resolve) {
                if (data.url.match(/youtube\.com|youtu\.be/)) {
                    let videoId = '';
                    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                    const match = data.url.match(regExp);

                    if (match && match[2].length === 11) {
                        videoId = match[2];
                    } else if (data.url.includes('youtu.be/')) {
                        videoId = data.url.split('youtu.be/')[1].split(/[?&]/)[0];
                    }

                    if (videoId) {
                        const embedUrl = 'https://www.youtube.com/embed/' + videoId;
                        const embedHtml = '<iframe src="' + embedUrl +
                            '" width="560" height="314" allowfullscreen="allowfullscreen"></iframe>';
                        resolve({ html: embedHtml });
                    } else {
                        resolve({ html: '' });
                    }
                } else {
                    resolve({ html: '' });
                }
            },
            setup: function (editor) {
                editor.on('init', function () {
                    var textareaId = editor.id.replace('_editor', '');
                    editor.setContent(document.getElementById(textareaId).value);
                });
                editor.on('change', function () {
                    var textareaId = editor.id.replace('_editor', '');
                    document.getElementById(textareaId).value = editor.getContent();
                });
            }
        });

        function initFilepond() {
            const plugins = [];
            if (typeof FilePondPluginFileEncode !== 'undefined') plugins.push(FilePondPluginFileEncode);
            if (typeof FilePondPluginFileValidateSize !== 'undefined') plugins.push(FilePondPluginFileValidateSize);
            if (typeof FilePondPluginImageExifOrientation !== 'undefined') plugins.push(FilePondPluginImageExifOrientation);
            if (typeof FilePondPluginImagePreview !== 'undefined') plugins.push(FilePondPluginImagePreview);
            if (typeof FilePondPluginFileValidateType !== 'undefined') plugins.push(FilePondPluginFileValidateType);
            if (typeof FilePondPluginImageResize !== 'undefined') plugins.push(FilePondPluginImageResize);
            if (typeof FilePondPluginImageTransform !== 'undefined') plugins.push(FilePondPluginImageTransform);

            if (plugins.length > 0 && typeof FilePond !== 'undefined') {
                FilePond.registerPlugin(...plugins);
            }

            const inputElement = document.querySelector('input.filepond');
            if (inputElement && typeof FilePond !== 'undefined') {
                FilePond.create(inputElement, {
                    allowMultiple: true,
                    maxFiles: 60,
                    imagePreviewHeight: 100,
                    allowImageResize: true,
                    imageResizeTargetWidth: 1920,
                    imageResizeTargetHeight: 1920,
                    imageResizeMode: 'contain',
                    imageResizeUpscale: false,
                    allowImageTransform: true,
                    imageTransformOutputQuality: 85,
                    imageTransformOutputMimeType: 'image/jpeg'
                });
            }
        }

        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                allowClear: false
            });

            initFilepond();

            // Auto-update Card Header & Card Subtitle
            function updateCardHeader() {
                const makeText = $('#manufacturer_id option:selected').text();
                const make = (makeText && makeText !== 'Select Make') ? makeText.trim() : '';
                const model = $('#model').val();
                $('#card_header').val(`${make} ${model}`.trim());
            }

            function updateCardSubtitle() {
                $('#card_subtitle').val($('#vehicle_price').val());
            }

            $('#manufacturer_id').on('change', updateCardHeader);
            $('#model').on('input', updateCardHeader);
            $('#vehicle_price').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateCardSubtitle();
            });

            $('input[name="vehicle_id_type"]').on('change', function () {
                if ($(this).val() === 'manual') {
                    $('#vehicle_id_container').slideDown();
                } else {
                    $('#vehicle_id_container').slideUp();
                    $('#vehicle_id').val('');
                    $('#vehicle_id-error').hide();
                }
            });

            const sortableContainer = document.getElementById('sortable-images');
            if (sortableContainer && typeof Sortable !== 'undefined') {
                new Sortable(sortableContainer, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: function () {
                        let order = [];
                        document.querySelectorAll('#sortable-images .image-preview-container')
                            .forEach(function (el) {
                                order.push(el.getAttribute('data-image'));
                            });
                        document.getElementById('image_order').value = order.join(',');
                        triggerAutoSave();
                    }
                });
            }

            $(document).on('click', '.remove-image', function () {
                const imagePath = $(this).data('image');
                $(this).closest('.image-preview-container').remove();

                let removedImages = $('#removed_images').val();
                removedImages = removedImages ? removedImages.split(',') : [];
                removedImages.push(imagePath);
                $('#removed_images').val(removedImages.join(','));

                let order = [];
                $('#sortable-images .image-preview-container').each(function () {
                    order.push($(this).data('image'));
                });
                $('#image_order').val(order.join(','));
                triggerAutoSave();
            });

            $("#addForm").validate({
                rules: {
                    manufacturer_id: { required: true },
                    model: { required: true },
                    year: { required: true },
                    // category_id: { required: true },
                    vehicle_price: { digits: true },
                    vehicle_id: {
                        required: function () {
                            return $('input[name="vehicle_id_type"]:checked').val() === 'manual';
                        }
                    },
                    status: { required: true },
                    auction_grade_id: { required: true },
                    'images[]': {
                        required: function () {
                            return $('#sortable-images .image-preview-container').length === 0;
                        }
                    },
                    description: { required: true },
                    card_header: { required: true },
                    card_subtitle: { required: true },
                    banner: { required: false },
                },
                messages: {
                    manufacturer_id: { required: "Please select a make." },
                    model: { required: "The model field is required." },
                    year: { required: "Please select a year." },
                    // category_id: { required: "Please select a category." },
                    vehicle_price: { digits: "The vehicle price must contain only numbers." },
                    vehicle_id: { required: "The Vehicle ID field is required." },
                    status: { required: "Please select a status." },
                    auction_grade_id: { required: "Please select an auction grade." },
                    'images[]': { required: "Please upload at least one image." },
                    description: { required: "Description is required." },
                    card_header: { required: "Card header is required." },
                    card_subtitle: { required: "Card subtitle is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.hasClass('select2') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    isFinalSubmitting = true;

                    $.ajax({
                        url: "{{ route('admin.car.store') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
                        },
                        success: function (result) {
                            sendSuccess(result.message || 'Car published successfully!');
                            form.reset();
                            const pond = FilePond.find(document.querySelector('input.filepond'));
                            if (pond) {
                                pond.removeFiles();
                            }
                            if (tinymce.get('description_editor')) {
                                tinymce.get('description_editor').setContent('');
                            }
                            window.location.href = "{{route('admin.car.index')}}";
                        },
                        error: function (xhr) {
                            isFinalSubmitting = false;
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let errorKey = key.includes('.') ? key.split('.')[0] : key;
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + errorKey + "-error").html(errorMessage).show();
                                });
                            } else if (data && data.hasOwnProperty('errors')) {
                                $.each(data.errors, function (key, value) {
                                    let errorKey = key.includes('.') ? key.split('.')[0] : key;
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + errorKey + "-error").html(errorMessage).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                sendError(data.message);
                            } else {
                                sendError("An error occurred. Please check file sizes or try again.");
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Create Car');
                        }
                    });
                }
            });

            // ============ AUTO-SAVE LOGIC ============
            let autoSaveTimer = null;
            let isAutoSaving = false;
            let isFinalSubmitting = false;

            function triggerAutoSave() {
                if (isFinalSubmitting) return;
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function () {
                    performAutoSave();
                }, 1500);
            }

            function performAutoSave() {
                if (isAutoSaving || isFinalSubmitting) return;
                isAutoSaving = true;

                $('#auto-save-status').removeClass('text-success text-danger').addClass('text-muted')
                    .html('<i class="ri-loader-4-line spinner-border spinner-border-sm me-1"></i> Saving...').show();

                if (typeof tinymce !== 'undefined' && tinymce.get('description_editor')) {
                    tinymce.get('description_editor').save();
                }

                let formEl = document.getElementById('addForm');
                let formData = new FormData(formEl);

                $.ajax({
                    url: "{{ route('admin.car.save-draft') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        isAutoSaving = false;
                        if (response.car_id) {
                            $('#draft_car_id').val(response.car_id);
                        }
                        let now = new Date();
                        let timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        $('#auto-save-status').removeClass('text-muted text-danger').addClass('text-success')
                            .html('<i class="ri-check-line me-1"></i> Saved (' + timeStr + ')').show();
                    },
                    error: function (xhr) {
                        isAutoSaving = false;
                        $('#auto-save-status').removeClass('text-muted text-success').addClass('text-danger')
                            .html('<i class="ri-error-warning-line me-1"></i> Save failed').show();
                    }
                });
            }

            $('#addForm').on('input change keyup', 'input, select, textarea', function () {
                triggerAutoSave();
            });

            $(document).on('change select2:select select2:unselect', '.select2', function () {
                triggerAutoSave();
            });

            // Custom Wording Radio Toggles
            $(document).on('change', 'input[name="fuel_custom_option"]', function () {
                if ($(this).val() == '1') {
                    $('#fuel_custom_wrapper').removeClass('d-none');
                    $('#fuel_type_custom').focus();
                } else {
                    $('#fuel_custom_wrapper').addClass('d-none');
                    $('#fuel_type_custom').val('');
                }
            });

            $(document).on('change', 'input[name="transmission_custom_option"]', function () {
                if ($(this).val() == '1') {
                    $('#trans_custom_wrapper').removeClass('d-none');
                    $('#transmission_custom').focus();
                } else {
                    $('#trans_custom_wrapper').addClass('d-none');
                    $('#transmission_custom').val('');
                }
            });

        });
    </script>
    @include('admin.car.partials.scripts', ['isEdit' => false, 'carId' => optional($draft)->id])
@endsection