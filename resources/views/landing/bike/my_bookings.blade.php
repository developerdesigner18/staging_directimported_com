@extends('landing.master')
@section('title','my-bookings')
@push('style')
    <style>

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px;
            right: 10px;
        }

        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }

        .section-header {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: var(--font-weight-bold);
            color: #333;
        }

        .section-header:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .form-container {
            background: var(--white-color);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-control, .form-select {
            border-left: none;
            padding-left: 5px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            font-weight: var(--font-weight-medium);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
            transform: translateY(-2px);
        }

        .info-text {
            font-size: 14px;
            color: var(--secondary-color);
            margin-top: 5px;
            font-weight: var(--font-weight-medium);
        }

        .selected-bike-placeholder {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }

        .form-label {
            font-weight: var(--font-weight-medium);
            margin-bottom: 8px;
            color: #495057;
        }

        .text-danger.error {
            font-size: 14px;
            margin-top: 5px;
            font-weight: var(--font-weight-medium);
        }

        .w-80 {
            width: 80%;
        }

        .bg-custom-light {
            background-color: #E7EBEE !important;
            border: unset;
        }

        .center-padding-x {
            padding-left: 10%;
            padding-right: 10%;
        }

        .w-33 {
            width: 33%;
        }

        .bg-none {
            border: unset;
        }
        .booking-heading-input{
            font-weight: 600;
        }
        .terms-condition{
            display: flex;
            justify-content: center;
        }
        .w-80{
            width: 80%;
        }
        .p-8{
            padding: 8px;
        }

        /* Accessory Card styles */
        .accessory-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }

        .accessory-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            width: calc(33.33% - 10px);
            min-width: 180px;
            position: relative;
            transition: all 0.2s;
            cursor: pointer;
        }

        .accessory-card.selected {
            border-color: #053C7C;
            box-shadow: 0 0 0 1px #053C7C;
        }

        .accessory-icon-box {
            width: 45px;
            height: 45px;
            background: #f0f4f8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .accessory-icon-box svg {
            width: 28px;
            height: 28px;
            color: #333;
        }

        .accessory-info {
            flex-grow: 1;
            line-height: 1.2;
        }

        .accessory-name {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 2px;
        }

        .accessory-price {
            font-size: 13px;
            color: #666;
            display: block;
        }

        .b-round-10{
            border-radius: 10px;
        }

        .accessory-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #053C7C;
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .accessory-card:hover {
            transform: scale(1.1);
        }

        .accessory-card.selected .accessory-check {
            display: flex;
        }

        .accessory-card input {
            display: none;
        }
.no-extra-space-popup .swal2-actions {
    margin-top: 10px !important;
}

.no-extra-space-popup .swal2-footer {
    border-top: none !important;
    margin-top: 10px !important;
    padding-top: 0 !important;
    text-align: center;
}
        @media (max-width: 768px) {
            .accessory-card {
                width: calc(50% - 8px);
            }
        }

        @media (max-width: 480px) {
            .accessory-card {
                width: 100%;
            }
        }
    </style>
@endpush

@push('modals')

    <div class="modal fade" id="bikeDetailsModal" tabindex="-1" aria-labelledby="bikeDetailsModalLabel"
         data-bs-backdrop="static" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-danger">This quote was created by Quotation. To proceed, please click
                        the "Request to Book" button below.</h6>
                    {{--                    <button type="button" class="btn-close clear_request" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form method="POST" enctype="multipart/form-data" id="bookingProcessingForm" class="p-2">
                    @csrf
                    <!-- Modal Body -->
                    <div class="modal-body p-0 bikeDetailsData">

                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary clear_request" data-bs-dismiss="modal">Quote
                            Clear
                        </button>
                        {{--                        <button type="{{ (!$user)?'button':'submit' }}" class="btn btn-danger {{ (!$user)?'checkLogin':'bookingProcessing' }}"><i class="bx bx-loader spinner me-2" style="display: none" id="processingBtnSpinner"></i> Request To Book</button>--}}
                        <button type="submit" class="btn btn-danger bookingProcessing"><i
                                class="bx bx-loader spinner me-2" style="display: none" id="processingBtnSpinner"></i>
                            Request To Book
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@section('main')

    <section class="">
        <div class="container">
            <div class="d-flex justify-content-between mt-3">
                <h4 class="mb-0">Request a quote</h4>
                {{--                <a href="{{ route('motorcycle') }}" class="btn btn-primary h-100">--}}
                {{--                    <i class="ri-add-circle-line fw-bold"></i> --}}
                {{--                    Add New Bike--}}
                {{--                </a>--}}
            </div>

            <div class="form-container mt-4 bg-custom-light">
                <form method="POST" enctype="multipart/form-data" id="booking-form">
                    @csrf

                    <!-- Bikes Section -->
                    <div class="mb-4">
                        {{--                        <h3 class="section-header">Selected Motorcycles</h3>--}}
                        {{--                        <div class="selected-bike-placeholder">--}}
                        {{--                            <p class="mb-0">No motorcycles selected yet. <a href="{{ route('motorcycle') }}">Add a bike</a> to get started.</p>--}}
                        {{--                        </div>--}}
                        <div class="row flex-column align-items-center justify-content-center selectedMotorcycles mt-3">
                        </div>
                        <a href="{{ route('motorcycle') }}" class="btn btn-primary h-100" style="margin-left: 12%;">
                            <i class="ri-add-circle-line fw-bold"></i>
                            Add More Bikes
                        </a>
                    </div>
                    <!-- Booking Date & Time -->
                    <div class="mb-4 center-padding-x">
                        <h3 class="section-header">Trip Details</h3>
                        <div class="row">
                            <div class="d-flex gap-2">
                                <div class="booking-heading-input col-md-6 w-33" >Start</div>
                                <div class="booking-heading-input col-md-6 w-33" ></div>
                                <div class="booking-heading-input col-md-6 w-33" >Pickup</div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="col-md-6 mb-3 w-33">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-calendar-2-fill"></i></span>
                                        <input type="text" class="form-control datepicker b-round-10" id="from" name="start_date"
                                               value="{{ date('Y-m-d') }}" placeholder="Start Date">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 w-33">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-time-fill"></i></span>
                                        <input type="text" class="form-control pickuptime b-round-10" id="pickupTime"
                                               name="start_time"
                                               placeholder="Pickup Time" autocomplete="off">
                                    </div>
                                    <label id="start_time-error" class="text-danger error" for="start_time"
                                           style="display: none"></label>
                                </div>
                                <div class="col-md-6 mb-3 w-33">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-map-pin-2-fill"></i></span>
                                    <select class="form-select no-select2 b-round-10" id="location" name="location">
                                        <option>Osaka</option>
                                    </select>
                                    </div>
                                    {{--                                <div class="info-text">Pick up and drop off must be at the same location unless--}}
                                    {{--                                    otherwise arranged--}}
                                    {{--                                </div>--}}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="booking-heading-input col-md-6 w-33" >Stop</div>
                                <div class="booking-heading-input col-md-6 w-33" ></div>
                                <div class="booking-heading-input col-md-6 w-33" >Dropoff</div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="col-md-6 mb-3 w-33">
{{--                                    <label for="to" class="form-label">End Date</label>--}}
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-calendar-2-fill"></i></span>
                                        <input type="text" class="form-control datepicker b-round-10" id="to" name="end_date"
                                               value="{{ date('Y-m-d') }}" placeholder="End Date">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 w-33">
{{--                                    <label for="dropoffTime" class="form-label">Drop Off Time</label>--}}
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-time-fill"></i></span>
                                        <input type="text" class="form-control dropoffTime b-round-10" id="dropoffTime"
                                               name="end_time"
                                               placeholder="Drop Off Time" autocomplete="off">
                                    </div>
                                    {{--                                <div class="info-text">AM pickup and PM drop-off counts as a day's riding</div>--}}
                                    <label id="end_time-error" class="text-danger error" for="end_time"
                                           style="display: none"></label>
                                </div>
                                <div class="col-md-6 mb-3 w-33">
{{--                                    <label for="location" class="form-label">Location</label>--}}
                                    <div class="input-group">
                                    <span class="input-group-text"><i class="ri-map-pin-2-fill"></i></span>
                                    <select class="form-select no-select2 b-round-10" name="location">
                                        <option>Osaka</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Contact Details -->
                    <div class="mb-4 center-padding-x">
                        <h3 class="section-header">Rider Information</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
{{--                                <label for="first_name" class="form-label">First Name</label>--}}
                                <div class="input-group">
{{--                                    <span class="input-group-text"><i class="ri-user-3-fill"></i></span>--}}
                                    <input type="text" class="form-control b-round-10" id="first_name" name="first_name"
                                           placeholder="Enter your first name" value="{{ $user->first_name??'' }}"
                                           required="">
                                </div>
                                <label id="first_name-error" class="text-danger error" for="first_name"
                                       style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
{{--                                <label for="last_name" class="form-label">Last Name</label>--}}
                                <div class="input-group">
{{--                                    <span class="input-group-text"><i class="ri-user-3-fill"></i></span>--}}
                                    <input type="text" class="form-control b-round-10" id="last_name" name="last_name"
                                           placeholder="Enter your last name" value="{{ $user->last_name??'' }}"
                                           required="">
                                </div>
                                <label id="last_name-error" class="text-danger error" for="last_name"
                                       style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
{{--                                <label for="email" class="form-label">Email Address</label>--}}
                                <div class="input-group">
{{--                                    <span class="input-group-text"><i class="ri-mail-fill"></i></span>--}}
                                    <input type="email" class="form-control b-round-10" id="email" name="email"
                                           placeholder="Enter a valid email"
                                           value="{{ $user->email??'' }}" required="">
                                </div>
{{--                                <div class="info-text">Format: first.last@email.com</div>--}}
                                <label id="email-error" class="text-danger error" for="email"
                                       style="display: none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
{{--                                <label for="mobile" class="form-label">Contact Number</label>--}}
                                <div class="input-group">
{{--                                    <span class="input-group-text rounded"><i class="ri-phone-fill"></i></span>--}}
                                    <input type="text" class="form-control b-round-10" id="mobile" name="mobile"
                                           value="{{ $user->mobile??'' }}"
                                           placeholder="Phone Number">
                                </div>
{{--                                <div class="info-text">Include area and country code if outside Japan</div>--}}
                                <label id="mobile-error" class="text-danger error" for="mobile"
                                       style="display: none"></label>
                            </div>
                            <div class="col-12">
{{--                                <label for="comment" class="form-label">Comments / Other Details</label>--}}
                                <textarea class="form-control b-round-10" rows="5" id="comment" name="comment" form="booking-form"
                                          placeholder="Any special requests or additional information..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">

                        </div>
                    </div>


                    <!-- Rental Locations -->
{{--                    <div class="mb-4 center-padding-x">--}}
{{--                        <h3 class="section-header">Rental Locations</h3>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6 mb-3">--}}
{{--                                <label for="location" class="form-label">Location</label>--}}
{{--                                <select class="form-select" id="location" name="location">--}}
{{--                                    <option>Osaka</option>--}}
{{--                                </select>--}}
{{--                                <div class="info-text">Pick up and drop off must be at the same location unless--}}
{{--                                    otherwise arranged--}}
{{--                                </div>--}}
{{--                            </div>                           --}}

{{--                        </div>--}}
{{--                    </div>--}}

                    <!-- Submit Button -->
                    <div class="form-check mt-4 pt-3 terms-condition" style="margin-left: 30px;
">
                        <input type="checkbox" class="form-check-input" name="policy_status"
                               id="policy_status" value="1" required="" style="margin-right: 4px">
                        <label class="form-check-labe" for="policy_status">
                            <strong>
                                "By Clicking you agree to our
                                <a target="_blank" href="{{ route('rental.policies') }}" class="text-primary">Terms and conditions</a> and I have read them ."</strong>
                        </label>
                    </div>
                    <label id="policy_status-error" class="text-danger error" for="policy_status"
                           style="display: none"></label>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary w-80" name="submit" id="submitReasonBtn"><i
                                class="bx bx-loader spinner me-2" style="display: none" id="generateBtnSpinner"></i>Generate
                            Quote
                        </button>
                    </div>
                    {{--                    </div>--}}
                </form>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script !src="">


        let old_items_details = JSON.parse(localStorage.getItem('lastRequestedQuoteDetail')) || {};
        if (Object.keys(old_items_details).length > 0) {
            var myModal = new bootstrap.Modal(document.getElementById('bikeDetailsModal'));
            myModal.show();

            $(".bikeDetailsData").html(old_items_details.html);
            $('.bookingProcessing').attr('data-booking_ids', old_items_details.booking_ids);
        }

        let bike_items = JSON.parse(localStorage.getItem('requestQuoteBikes')) || [];

        function renderBikes() {
            let items = JSON.parse(localStorage.getItem('requestQuoteBikes')) || [];
            $('.selectedMotorcycles').html(''); // Clear old content

            // SVGs
            // const mobileSvg=`<i class="ri-smartphone-line"></i>`;
            const bagSVG = `<i class="ri-briefcase-line"></i>`;
            // const helmetSVG = `<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#000000" d="M294.396 52.127c-17.944.066-35.777 1.834-52.886 4.746-86.727 14.76-135.612 53.467-161.99 107.824 31.215-2.434 62.002-5.024 91.966-4.838 24.114.15 47.696 2.097 70.54 7.37 15.15 3.5 24.652 16.647 27.607 31.735 2.954 15.088.858 32.92-5.055 51.553l-.287.904-.468.826c-7.762 13.64-24.263 24.498-45.295 35.994-21.032 11.497-46.695 22.693-72.27 32.428-25.574 9.735-51.012 17.98-71.575 23.437-7.254 1.925-13.85 3.48-19.735 4.657 2.275 31.13 6.562 63.38 12.008 95.98 140.118-38.25 273.5-79.888 403.51-123.254 25.935-44.457 29.927-86.448 16.967-126.734-22.393-69.605-60.9-107.048-105.215-126.168-27.696-11.95-57.913-16.57-87.82-16.46zM130.184 179.205c-9.06.51-18.265 1.156-27.532 1.836L59.31 329.386c3.384-.79 6.936-1.663 10.754-2.676 4.004-1.063 8.27-2.27 12.66-3.554 10.022-31.07 43.3-131.415 47.46-143.95zm-46.7 3.262c-10.868.826-21.824 1.654-32.908 2.37-.32.445-.714.947-1.318 2.267-1.58 3.45-3.375 9.418-4.912 16.724-3.075 14.612-5.37 34.727-6.705 54.877-1.333 20.15-1.73 40.438-1.193 55.582.268 7.572.79 13.905 1.442 17.96.048.306.078.312.13.59.46-.01 1.033-.044 1.546-.064l43.918-150.306zM224 183c-15.596 0-28.66 12.582-28.66 28.152s13.064 28.155 28.66 28.155 28.66-12.584 28.66-28.155c0-15.57-13.064-28.152-28.66-28.152zm0 18c6.12 0 10.66 4.567 10.66 10.152 0 5.586-4.54 10.155-10.66 10.155s-10.66-4.57-10.66-10.155c0-5.585 4.54-10.152 10.66-10.152zm230.19 144.865C330.383 386.852 203.285 426.23 70.054 462.56c.413 2.317.81 4.63 1.232 6.948 147.607-26.65 255.974-68.965 371.36-109.164 4.118-4.857 7.947-9.68 11.546-14.48z"></path></g></svg>`;
            const helmetSVG=`<i class='bx bx-helmet'></i>`;
            const policySVG=`<i class="ri-file-list-line"></i>`;
            if (items.length > 0) {
                items.forEach((item, index) => {

                    // Render Free Accessories
                    getAccessoryData(item.id, function (freeAccessories) {
                        let freeHTML = '';
                        if (freeAccessories.length === 0) {
                            freeHTML = `
                                <div class="mb-2">
                                    <label class="form-check-label ms-2 text-muted">No Included Accessories</label>
                                </div>
                            `;
                        } else {
                            freeHTML = '<div class="accessory-container">';
                            freeAccessories.forEach(acc => {
                                let icon;
                                if(acc.icon){
                                    icon = `<i class="${acc.icon}"></i>`;
                                } else {
                                    let nameLower = acc.name.toLowerCase();
                                    if (nameLower.includes('insurance') || nameLower.includes('policy')) {
                                        icon = policySVG;
                                    } else if (nameLower.includes('helmet')) {
                                        icon = helmetSVG;
                                    } else {
                                        icon = bagSVG;
                                    }
                                }

                                freeHTML += `
                                    <label class="accessory-card selected">
                                        <input type="checkbox" value="${acc.id}"
                                               name="included_acc_id[${item.id}][]"
                                               class="form-check-input" checked disabled>
                                        <div class="accessory-icon-box">
                                            ${icon}
                                        </div>
                                        <div class="accessory-info">
                                            <span class="accessory-name">${acc.name}</span>
                                            <span class="accessory-price">FREE</span>
                                        </div>
                                        <div class="accessory-check"><i class="ri-check-line"></i></div>
                                    </label>
                                `;
                            });
                            freeHTML += '</div>';
                        }
                        $(`#free_acc_${item.id}`).html(freeHTML);
                    });



                    // Render Extra Accessories specific to this bike
                    getBikeExtraAccessories(item.id, function (extraAccessories) {
                        let accHTML = '';

                        if (extraAccessories.length === 0) {
                            accHTML = `
                        <div class="mb-2">
                            <label class="form-check-label ms-2 text-muted">No Extra Accessories</label>
                        </div>
                    `;
                        } else {
                            accHTML = '<div class="accessory-container">';
                            extraAccessories.forEach(acc => {
                                let icon;
                                let isChecked='';
                                let nameLower = acc.name.toLowerCase();
                                if(acc.icon){
                                    icon = `<i class="${acc.icon}"></i>`;
                                } else {
                                    if (nameLower.includes('helmet')) {
                                        icon = helmetSVG;
                                    } else if (nameLower.includes('insurance') || nameLower.includes('policy')) {
                                        icon = policySVG;
                                    } else {
                                        icon = bagSVG;
                                    }
                                }

                                if (nameLower.trim().startsWith('helmet')) {
                                    // ✅ Auto-check ONLY the 2nd helmet
                                    // if (nameLower.includes('2nd')) {
                                        isChecked = 'checked';
                                    // }
                                }

                                accHTML += `
                            <label for="acc_bike_${acc.id}_${item.id}" class="accessory-card" onclick="toggleAccessory(this)">
                                <input type="checkbox" value="${acc.id}"
                                       id="acc_bike_${acc.id}_${item.id}"
                                       name="acc_bike_id[${item.id}][]"
                                       class="form-check-input" ${isChecked} >
                                <div class="accessory-icon-box">
                                    ${icon}
                                </div>
                                <div class="accessory-info">
                                    <span class="accessory-name">${acc.name}</span>
                                    <span class="accessory-price">${acc.price > 0 ? '¥' + acc.price + '/day' : 'FREE'}</span>
                                    <span class="accessory-price">
                                        ${acc.additional_day_price && acc.additional_day_price > 0
                                            ? `+ (¥${acc.additional_day_price} per day after)`
                                            : ''
                                        }
                                    </span>
                                </div>
                                <div class="accessory-check"><i class="ri-check-line"></i></div>
                            </label>
                        `;
                            });
                            accHTML += '</div>';
                        }

                        // Inject into the bike card
                        $(`#extra_acc_list_${item.id}`).html(accHTML);

                        $(`#extra_acc_list_${item.id} input:checked`).each(function () {
                            $(this).closest('.accessory-card').addClass('selected');
                        });
                    });

                    // Render bike card
                    let html = `
                <div class="col-md-6 mb-3 w-80 bg-custom-light">
                    <div class="card p-2 bg-custom-light">
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-bike" data-index="${index}">
                                            Remove Bike
                                        </button>
                                        <h5 class="mb-0">${item.name}</h5>
                                    </div>
                                    <img src="${item.image}" class="img-fluid mb-2" style="max-width: 100%; height:auto;border-radius: 20px;" alt="Bike Image" loading="lazy">
                                    <input type="hidden" value="${item.id}" name="bike_ids[]">
                                </div>
                                <div class="col-12">
                                    <h6 class="mb-2 mt-3 fw-bold">Included Accessories</h6>
                                    <div id="free_acc_${item.id}"></div>

                                    <h6 class="mb-2 mt-3 fw-bold">Extra Accessories / Click to Select</h6>
                                    <div id="extra_acc_list_${item.id}"></div>

                                     <div class="mt-3">
                                        <label for="acc_insurance_${item.id}" class="accessory-card" onclick="toggleAccessory(this)">
                                            <input type="checkbox" value="1" id="acc_insurance_${item.id}" name="acc_insurance[${item.id}]" class="form-check-input">
                                            <div class="accessory-icon-box">
                                                <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70 70" enable-background="new 0 0 70 70" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M56.946,7.583h-0.042h-7.321V5.208c0-1.104-0.896-2-2-2s-2,0.896-2,2v2.375h-4V5.208c0-1.104-0.896-2-2-2s-2,0.896-2,2 v2.375h-5V5.208c0-1.104-0.896-2-2-2s-2,0.896-2,2v2.375h-5V5.208c0-1.104-0.896-2-2-2s-2,0.896-2,2v2.375h-5.678 c-2.209,0-4.322,2.052-4.322,4.261v51c0,2.209,1.113,3.738,3.322,3.738h44c2.209,0,3.679-1.529,3.679-3.738v-51 C60.583,9.635,59.155,7.583,56.946,7.583z M19.583,11.583v2.625c0,1.104,0.896,2,2,2s2-0.896,2-2v-2.625h5v2.625 c0,1.104,0.896,2,2,2s2-0.896,2-2v-2.625h5v2.625c0,1.104,0.896,2,2,2s2-0.896,2-2v-2.625h4v2.625c0,1.104,0.896,2,2,2s2-0.896,2-2 v-2.625h7v10h-43v-10H19.583z M13.583,62.583v-39h43v39H13.583z"></path> <path d="M53.583,50.208c-0.552,0-1,0.447-1,1v7.375h-5.666c-0.552,0-1,0.447-1,1s0.448,1,1,1h6.667c0.552,0,0.999-0.447,0.999-1 v-8.375C54.583,50.655,54.135,50.208,53.583,50.208z"></path> <path d="M42.917,58.583h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S43.469,58.583,42.917,58.583z"></path> <path d="M21.917,31.583h8c0.552,0,1-0.447,1-1s-0.448-1-1-1h-8c-0.552,0-1,0.447-1,1S21.365,31.583,21.917,31.583z"></path> <path d="M33.917,31.583h15c0.552,0,1-0.447,1-1s-0.448-1-1-1h-15c-0.552,0-1,0.447-1,1S33.365,31.583,33.917,31.583z"></path> <path d="M41.917,50.583c0-0.553-0.448-1-1-1h-7c-0.552,0-1,0.447-1,1s0.448,1,1,1h7C41.469,51.583,41.917,51.136,41.917,50.583z"></path> <path d="M48.917,44.583h-8c-0.552,0-1,0.447-1,1s0.448,1,1,1h8c0.552,0,1-0.447,1-1S49.469,44.583,48.917,44.583z"></path> <path d="M21.917,46.583h15c0.552,0,1-0.447,1-1s-0.448-1-1-1h-15c-0.552,0-1,0.447-1,1S21.365,46.583,21.917,46.583z"></path> <path d="M28.917,49.583h-7c-0.552,0-1,0.447-1,1s0.448,1,1,1h7c0.552,0,1-0.447,1-1S29.469,49.583,28.917,49.583z"></path> <path d="M21.917,36.583h6c0.552,0,1-0.447,1-1s-0.448-1-1-1h-6c-0.552,0-1,0.447-1,1S21.365,36.583,21.917,36.583z"></path> <path d="M31.917,36.583h17c0.552,0,1-0.447,1-1s-0.448-1-1-1h-17c-0.552,0-1,0.447-1,1S31.365,36.583,31.917,36.583z"></path> <path d="M21.917,41.583h13c0.552,0,1-0.447,1-1s-0.448-1-1-1h-13c-0.552,0-1,0.447-1,1S21.365,41.583,21.917,41.583z"></path> <path d="M48.917,39.583h-10c-0.552,0-1,0.447-1,1s0.448,1,1,1h10c0.552,0,1-0.447,1-1S49.469,39.583,48.917,39.583z"></path> </g> </g></svg>
                                            </div>
                                            <div class="accessory-info">
                                                <span class="accessory-name">Optional Insurance</span>
                                                <span class="accessory-price">Add Extra Optional Insurance ${item.insurance_price ? '¥' + Number(item.insurance_price).toLocaleString() + ' /day' : ''}</span>
                                            </div>
                                            <div class="accessory-check"><i class="ri-check-line"></i></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    $('.selectedMotorcycles').append(html);
                });
            } else {
                $('.selectedMotorcycles').append(`
            <input type="hidden" value="" name="bike_ids">
            <label id="bike_ids-error" class="text-danger error" for="bike_ids" style="display:none"></label>
        `);
            }
        }


        // AJAX to get Free Accessories
        function getAccessoryData(bike_id, callback) {
            $.ajax({
                url: "{{ route('my.bookings.quote.bike.accessories') }}",
                dataType: "JSON",
                method: "POST",
                data: {_token: "{{ csrf_token() }}", bike_id: bike_id},
                success: function (response) {
                    callback(response.message || []);
                },
                error: function () {
                    callback([]);
                }
            });
        }

        // AJAX to get Extra Accessories specific to a bike
        function getBikeExtraAccessories(bike_id, callback) {
            $.ajax({
                url: "{{ route('motorcycle.extra.accessories') }}",
                method: "POST",
                data: {_token: "{{ csrf_token() }}", bike_id: bike_id},
                success: function (response) {
                    callback(response.accessories || []);
                },
                error: function () {
                    callback([]);
                }
            });
        }

        // Remove bike
        $(document).on('click', '.remove-bike', function () {
            let index = $(this).data('index');
            bike_items.splice(index, 1);
            localStorage.setItem('requestQuoteBikes', JSON.stringify(bike_items));
            renderBikes();
        });

        // Create Quote
        $(document).ready(function () {

            renderBikes();

            // Check for dates in localStorage
            let bookingDates = JSON.parse(localStorage.getItem('bookingDates'));
            if (bookingDates) {
                if (bookingDates.start_date) $('#from').val(bookingDates.start_date);
                if (bookingDates.end_date) $('#to').val(bookingDates.end_date);

                // Clear after using so it doesn't persist on subsequent unrelated visits
                localStorage.removeItem('bookingDates');
            }
        });

        $(document).on('click', '.clear_request', function () {
            localStorage.removeItem('lastRequestedQuoteDetail');
        });

        $(document).ready(function () {
            $("#booking-form").validate({
                rules: {
                    bike_ids: {required: true},
                    first_name: {required: true},
                    last_name: {required: true},
                    email: {required: true},
                    mobile: {required: true},
                    policy_status: {required: true},
                },
                messages: {
                    bike_ids: {required: "The bike Selection is required."},
                    first_name: {required: "The first Name field is required."},
                    last_name: {required: "The last Name field is required."},
                    email: {required: "The email field is required, format: first.last@email.com"},
                    mobile: {required: "Enter your phone number, include area and country code if outside Japan"},
                    policy_status: {required: "The policy checkbox field is required."},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('my.bookings.action') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
//                         beforeSend: function () {
//                             $('#submitReasonBtn').attr('disabled', true);
// $("#generateBtnSpinner").show();
//                         },
                        beforeSend: function () {
                            $('#submitReasonBtn').attr('disabled', true);
                            $("#generateBtnSpinner").show();
                            $(".progress-container").addClass("active");
                        },
                        success: function (result) {
                            $("#bikeDetailsModal").modal('show');
                            $(".bikeDetailsData").html(result.message.html);
                            $('.bookingProcessing').attr('data-booking_ids', result.message.booking_ids);
                            $("label.error").hide();

                            let last_items = {
                                html: result.message.html,
                                booking_ids: result.message.booking_ids,
                                data: result.message.data
                            };
                            localStorage.setItem('lastRequestedQuoteDetail', JSON.stringify(last_items));

                            // localStorage.removeItem('requestQuoteBikes');
                            renderBikes();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });

                                if (data.error.hasOwnProperty('bike_ids')) {
                                    sendError(data.error.bike_ids);
                                }
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
//                         complete: function () {
//                             $('#submitReasonBtn').attr('disabled', false);
// $("#generateBtnSpinnerQ").show();
// },
                        complete: function () {
                            $('#submitReasonBtn').attr('disabled', false);
                            $("#generateBtnSpinnerQ").show();
                            $(".progress-container").removeClass("active");
                        }
                    });
                }
            });

            $(document).on('submit', '#bookingProcessingForm', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('motorcycle.booking.processing') }}",
                    method: "post",
                    dataType: "json",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
//                     beforeSend: function () {
//                         $('.bookingProcessing').attr('disabled', true);
//
//                         $("#processingBtnSpinner").show();
//                     },
                    beforeSend: function () {
                        $('#submitReasonBtn').attr('disabled', true);
                        $("#generateBtnSpinner").show();

                        $(".progress-container").addClass("active");
                    },
                    success: function (result) {
                        //*
        
   Swal.fire({
        title: "Success!",
        html: `
            <p style="margin-bottom: 10px;">
                Your Booking Request has been sent to our Team and you will receive a email confirmation in 24hrs
            </p>
        `,
        icon: "success",

        // ✅ Footer (below button)
        footer: `<span style="color:#6c757d;">Thanks for your Booking Request</span>`,

        confirmButtonText: "OK",
        buttonsStyling: false,

        customClass: {
            confirmButton: 'btn btn-danger w-md',
            popup: 'no-extra-space-popup'
        }
    });            //*
                        $("#bikeDetailsModal").modal('hide');
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
//                     complete: function () {
//                         $('.bookingProcessing').attr('disabled', false);
//                         $("#processingBtnSpinner").hide();
//                         localStorage.removeItem('lastRequestedQuoteDetail');
//                     }
                    complete: function () {
                        $('#submitReasonBtn').attr('disabled', false);
                        $("#generateBtnSpinnerQ").show();

                        // ✅ Hide AJAX loader
                        $(".progress-container").removeClass("active");
                        localStorage.removeItem('lastRequestedQuoteDetail');
                        localStorage.removeItem('requestQuoteBikes');
                        renderBikes();
                    }
                });
            });
        });

        function bookingsQuoteDetails() {
            $("#bikeDetailsModal").modal('show');
            $.ajax({
                url: "{{ route('my.bookings.quote.details') }}",
                dataType: "JSON",
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                },
                success: function (data) {
                    $(".bikeDetailsData").html(data.message);
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
                }
            });
        }

        function toggleAccessory(label) {
            const checkbox = label.querySelector('input[type="checkbox"]');
            // Timeout to allow the default click event to finish updating the checkbox
            setTimeout(() => {
                if (checkbox.checked) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            }, 10);
        }
    </script>
@endsection

