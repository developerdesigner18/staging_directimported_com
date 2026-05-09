@extends('admin.master')
@section('title', 'View Booking')
@push('modal')
    <!-- Document Verified Mail Modal -->
    <div class="modal fade" id="documentVerifiedMailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Send Document Verified Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="documentVerifiedMailForm">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$rowData->user ? $rowData->user->id : ''}}">

                    <div class="modal-body">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Write a message "></textarea>
                        <label id="message-error" class="text-danger error" style="display:none;"></label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border rounded px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" id="submitDocumentMailBtn" class="btn btn-danger text-white rounded px-4">
                            <i class="bx bx-loader spinner me-2" id="docMailBtnSpinner" style="display:none;"></i>
                            Send Mail
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endpush

@push('style')

    <style>
        .mr-30 {
            margin: 0px 30px;
        }

        /* Document Card Styles */
        .document-card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e9ecef;
        }

        .document-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(23, 162, 184, 0.2);
            border-color: #053C7C;
        }

        .document-card-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .document-header {
            padding: 1rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
        }

        .document-title {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .document-title i {
            color: #17a2b8;
            font-size: 1.1rem;
        }

        .document-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 63%;
            /* ~1.58:1 Aspect Ratio (Bank card/ID format) */
            overflow: hidden;
            background: #e9ecef;
            border-radius: 4px;
        }

        .document-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
            text-decoration: none;
        }

        .document-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Ensures full document is visible without cropping */
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .document-card:hover .document-image {
            transform: scale(1.05);
        }

        .document-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(243, 54, 79, 0.85) 0%, rgba(243, 54, 79, 0.95) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: white;
            gap: 0.5rem;
        }

        .document-link:hover .document-overlay {
            opacity: 1;
        }

        .document-overlay i {
            font-size: 2rem;
            animation: pulse 2s infinite;
        }

        .document-overlay span {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 20px;
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .status-verified {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn input[type="checkbox"] {
            cursor: pointer;
        }

        /* Animations */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 1399px) {
            .document-title {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 991px) {
            .document-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 767px) {
            .document-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .document-title {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('main')


    <!-- start page title -->
    <div class="row">

        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">View Booking</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.booking.index') }}">Booking</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">View</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Card 1: User & Booking Info -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>User & Booking Information</h5>
        </div>
        <div class="card-body">
            <form method="post" id="viewForm">
                @csrf
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $rowData->id }}</td>
                            </tr>
                            <tr>
                                <th>Booking ID</th>
                                <td>{{ $rowData->booking_id }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" class="form-select">

                                        @foreach(\App\Enum\BookingStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $rowData->status->value == $status->value ? 'selected' : '' }}>

                                                {{ $status->label() }}

                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td><input type="text" name="first_name" class="form-control"
                                        value="{{ $rowData->first_name ?? '' }}"></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><input type="text" name="last_name" class="form-control"
                                        value="{{ $rowData->last_name ?? '' }}"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $rowData->email ?? '' }}</td>
                            </tr>

                            <tr>
                                <th>Start Date</th>
                                <td>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($rowData->start_date)) }}">
                                </td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($rowData->end_date)) }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Pickup Time</th>
                                <td>
                                    <input type="time" name="start_time" class="form-control"
                                        value="{{ date('H:i', strtotime($rowData->start_time)) }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Dropoff Time</th>
                                <td>
                                    <input type="time" name="end_time" class="form-control"
                                        value="{{ date('H:i', strtotime($rowData->end_time)) }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>
                                    <input type="text" name="location" class="form-control" value="{{ $rowData->location }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Policies Accepted</th>
                                <td>{{ $rowData->policy_status == 1 ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Comments</th>
                                <td>
                                    <textarea class="form-control" name="comment">{{ $rowData->comment ?? '' }}</textarea>
                                </td>
                            </tr>


                            <tr>
                                <th>Selected Cars</th>
                                <td>
                                    @if($cars)
                                        <select class="form-select" name="car_id">
                                            @foreach($cars as $car)
                                                <option value="{{ $car->id }}" {{ $rowData->car_id == $car->id ? 'selected' : '' }}>
                                                    {{ $car->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Included Accessories</th>

                                <td>
                                    {{-- Free Accessories --}}
                                    <select class="form-select select2-multiple" name="included_accessories[]" multiple>

                                        @foreach($freeList as $acc)
                                            <option value="{{ $acc->id }}" {{ in_array($acc->id, $freeAccessoryIds) ? 'selected' : '' }}>
                                                {{ $acc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Extra Accessories</th>
                                <td>
                                    <select class="form-select select2-multiple" name="selected_accessories[]" multiple>
                                        @foreach($extraList as $acc)
                                            <option value="{{ $acc->id }}" {{ in_array($acc->id, $extraAccessoryIds) ? 'selected' : '' }}>
                                                {{ $acc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th>Optional Insurance</th>
                                <td>
                                    <input type="hidden" name="insurance" value="{{ $rowData->insurance }}">
                                    <span id="insurance_amount_display"
                                        class="fw-bold text-dark">{{ ($rowData->insurance == 1) ? '¥' . number_format($rowData->car->insurance_price * $rowData->totalDays()) : '' }}</span>
                                </td>
                            </tr>

                            <tr>
                                <th>Private Comments</th>
                                <td>
                                    <textarea rows="3" class="form-control"
                                        name="system_comment">{{ $rowData->system_comment }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Email Comments</th>
                                <td>
                                    <textarea rows="3" class="form-control"
                                        name="email_comment">{{ $rowData->email_comment }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Booking Amount</th>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">¥</span>
                                        <input type="number" step="any"
                                            value="{{ isset($rowData->price) ? round($rowData->price) : '0' }}"
                                            class="form-control" name="price">
                                    </div>
                                </td>
                            </tr>
                            <tr>

                                {{-- <td colspan="2">--}}
                                    {{-- <button type="button" id=""
                                        class="btn btn-soft-danger waves-effect waves-light">Send Payment Link to
                                        Customer</button>--}}
                                    {{-- <button type="button" --}} {{-- class="btnSendBookingDetail btn btn-soft-danger"
                                        --}} {{-- data-booking-id="{{ $rowData->booking_id }}" --}} {{-- --}}{{--
                                        data-status="{{ $rowData->status }}" --}} {{-- data-email="{{ $rowData->email }}"
                                        --}} {{-- data-id="{{ $rowData->id }}" --}} {{--
                                        data-car_id="{{ $rowData->car_id }}">--}}
                                        {{-- <i class="bx bx-loader spinner me-2" style="display: none"
                                            id="btnSendBookingDetailSpinner"></i>--}}
                                        {{-- Send Booking Details to Customer--}}
                                        {{-- </button>--}}
                                    {{-- <button type="button" id="btnSendLoginDetail" --}} {{--
                                        class="btn btn-soft-danger waves-effect waves-light" --}} {{--
                                        data-booking-id="{{ $rowData->booking_id }}" data-status="{{ $rowData->status }}"
                                        --}} {{-- data-email="{{ $rowData->email }}" data-id="{{ $rowData->id }}" --}} {{--
                                        data-fname="{{ $rowData->first_name }}"
                                        data-lname="{{ $rowData->last_name }}"><i--}} {{-- class="bx bx-loader spinner me-2"
                                            style="display: none" id="btnSendLoginDetailSpinner"></i>Send--}}
                                            {{-- Login Details--}}
                                            {{-- </button>--}}

                                    {{-- --}}{{-- <a
                                        href="{{ route('admin.booking.contract-preview',$rowData->booking_id) }}"
                                        class="btn btn-soft-danger waves-effect waves-light" id="contractPreview">Contract
                                        Preview</a>--}}
                                    {{-- <button type="button" --}} {{-- class="documentVerifiedMail btn btn-soft-danger"
                                        --}} {{-- data-booking-id="{{ $rowData->booking_id }}" --}} {{-->--}}
                                        {{-- <i class="bx bx-loader spinner me-2" style="display: none"
                                            id="btnSendDocumentVerifiedMailSpinner"></i>--}}
                                        {{-- Send Document Verified Mail--}}
                                        {{-- </button>--}}

                                    {{-- </td>--}}
                                <td colspan="2">

                                    <div class="d-flex flex-wrap gap-2 align-items-center">

                                        <!-- Payment Link -->
                                        <button type="button"
                                            class="btn btn-soft-danger waves-effect waves-light action-btn">
                                            Send Payment Link to Customer
                                            <input type="checkbox" class="form-check-input ms-2 bulk-action"
                                                value="payment_link" {{ $rowData->send_payment_link == 1 ? 'checked' : '' }}>
                                        </button>

                                        <!-- Booking Details -->
                                        <button type="button"
                                            class="btn btn-soft-danger waves-effect waves-light action-btn">
                                            Send Booking Details to Customer
                                            <input type="checkbox" class="form-check-input ms-2 bulk-action"
                                                value="booking_detail" {{ $rowData->send_booking_detail == 1 ? 'checked' : '' }}>
                                        </button>

                                        <!-- Login Details -->
                                        <button type="button"
                                            class="btn btn-soft-danger waves-effect waves-light action-btn">
                                            Send Login Details
                                            <input type="checkbox" class="form-check-input ms-2 bulk-action"
                                                value="login_detail" {{ $rowData->send_login_detail == 1 ? 'checked' : '' }}>
                                        </button>

                                        <!-- Document Verified -->
                                        <button type="button"
                                            class="btn btn-soft-danger waves-effect waves-light action-btn">
                                            Send Document Verified Mail
                                            <input type="checkbox" class="form-check-input ms-2 bulk-action"
                                                value="document_verified" {{ $rowData->send_document_verified == 1 ? 'checked' : '' }}>
                                        </button>

                                        <!-- Main Send Button -->
                                        <button type="button" class="btn btn-danger" id="btnSendSelected">
                                            <i class="bx bx-loader spinner me-2" style="display:none;"
                                                id="bulkSendSpinner"></i>
                                            Send
                                        </button>

                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">

                                    <a href="{{ route('admin.booking.contract-preview', $rowData->booking_id) }}"
                                        class="btn btn-soft-danger waves-effect waves-light me-2">
                                        Contract Preview
                                    </a>

                                    @if($rowData->user)
                                        <a href="{{ route('admin.loginAsUser', $rowData->user->id) }}"
                                            class="btn btn-soft-danger waves-effect waves-light me-2">
                                            <i class="bx bx-log-in-circle me-1"></i> Login as User
                                        </a>
                                    @endif

                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success"><i class="bx bx-loader spinner me-2"
                        style="display: none" id="processingBtnSpinner"></i>Update</button>
            </form>
        </div>
    </div>

    @if($rowData->user)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-gradient-primary text-white border-0"
                style="background: #053C7C; padding: 1.25rem 1.5rem;">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-semibold text-white">
                        <i class="bx bx-shield-quarter me-2"></i>User Account Information
                    </h5>
                </div>
            </div>
            <div class="card-body p-4" style="background: #f8f9fa;">
                <form id="updatePasswordForm">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $rowData->user->id }}">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">Login Email / Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" class="form-control bg-light" value="{{ $rowData->user->email }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">Manual Password Reset</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                <input type="text" name="password" id="manual_password" class="form-control"
                                    placeholder="Enter new password">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100" id="btnUpdatePassword">
                                <i class="bx bx-loader spinner me-2" style="display: none"
                                    id="btnUpdatePasswordSpinner"></i>Update
                            </button>
                        </div>
                    </div>
                    <div class="row px-1">
                        <div class="col-12">
                            <small class="text-muted"><i class="bx bx-info-circle me-1"></i>Enter a new password above to
                                manually override the customer's current password.</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <!-- Card 2: Documents & Verification -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-primary text-white border-0"
            style="background: #053C7C; padding: 1.25rem 1.5rem;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold text-white">
                    <i class="bx bx-file me-2"></i>Documents & Verification
                </h5>
                <!-- @php
                        $overallStatus = $rowData->user?->userDetail?->status ?? 'NOT VERIFIED';
                    @endphp
                        <span class="badge {{ $overallStatus === 'VERIFIED' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2 fs-6">
                        <i class="bx {{ $overallStatus === 'VERIFIED' ? 'bx-check-circle' : 'bx-time' }} me-1"></i>
                        {{ $overallStatus }}
                    </span> -->
            </div>
        </div>
        <div class="card-body p-4" style="background: #f8f9fa;">

            <!-- Documents Grid -->
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-5">

                {{-- Passport --}}
                <div class="col">
                    <div class="document-card h-100">
                        <div class="document-card-inner">
                            <div class="document-header">
                                <h6 class="document-title">
                                    <i class="bx bx-id-card me-2"></i>Passport
                                </h6>
                                @if(!empty($rowData->user?->userDetail?->passport_status))
                                    <span
                                        class="status-badge status-{{ strtolower($rowData->user->userDetail->passport_status) }}">
                                        {{ $rowData->user->userDetail->passport_status }}
                                    </span>
                                @endif
                            </div>
                            <div class="document-image-wrapper">
                                {{-- <a
                                    href="{{ $rowData->user?->userDetail?->passport ?: asset('uploads/user_documents/default.jpg') }}"
                                    target="_blank" data-lightbox="gallery" class="document-link">--}}
                                    <a href="javascript:void(0);" class="document-link openPreview"
                                        data-img="{{ $rowData->user?->userDetail?->passport ?: asset('uploads/user_documents/default.jpg') }}"
                                        data-docno="{{ $rowData->user?->userDetail?->passport_number }}"
                                        data-id="{{ $rowData->user?->userDetail?->id }}"
                                        data-user_id="{{ $rowData->user?->id }}" data-field="passport"
                                        data-has-image="{{ $rowData->user?->userDetail?->getRawOriginal('passport') ? 1 : 0 }}"
                                        data-status="{{ $rowData->user?->userDetail?->passport_status }}">
                                        <img class="document-image"
                                            src="{{ $rowData->user?->userDetail?->passport ?: asset('uploads/user_documents/default.jpg') }}"
                                            alt="Passport">
                                        <div class="document-overlay">
                                            <i class="bx bx-search-alt"></i>
                                            <span>View Full Size</span>
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2">
                                Passport Number : {{$rowData->user?->userDetail?->passport_number}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- International License Front --}}
                <div class="col">
                    <div class="document-card h-100">
                        <div class="document-card-inner">
                            <div class="document-header">
                                <h6 class="document-title">
                                    <i class="bx bx-card me-2"></i>Intl License (Front)
                                </h6>
                                @if(!empty($rowData->user?->userDetail?->international_lic_status))
                                    <span
                                        class="status-badge status-{{ strtolower($rowData->user->userDetail->international_lic_status) }}">
                                        {{ $rowData->user->userDetail->international_lic_status }}
                                    </span>
                                @endif
                            </div>
                            <div class="document-image-wrapper">
                                {{-- <a
                                    href="{{ $rowData->user?->userDetail?->international_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                    target="_blank" data-lightbox="gallery" class="document-link">--}}
                                    <a href="javascript:void(0);" class="document-link openPreview"
                                        data-img="{{ $rowData->user?->userDetail?->international_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                        data-docno="{{ $rowData->user?->userDetail?->idp_number }}"
                                        data-id="{{ $rowData->user?->userDetail?->id }}"
                                        data-user_id="{{ $rowData->user?->id }}" data-field="international_lic"
                                        data-has-image="{{ $rowData->user?->userDetail?->getRawOriginal('international_lic') ? 1 : 0 }}"
                                        data-status="{{ $rowData->user?->userDetail?->international_lic_status }}">
                                        <img class="document-image"
                                            src="{{ $rowData->user?->userDetail?->international_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                            alt="International License Front">
                                        <div class="document-overlay">
                                            <i class="bx bx-search-alt"></i>
                                            <span>View Full Size</span>
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2">
                                Intl Dri. No : {{$rowData->user?->userDetail?->idp_number}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- International License Back --}}
                <div class="col">
                    <div class="document-card h-100">
                        <div class="document-card-inner">
                            <div class="document-header">
                                <h6 class="document-title">
                                    <i class="bx bx-card me-2"></i>Intl License (Back)
                                </h6>
                                @if(!empty($rowData->user?->userDetail?->international_lic_back_status))
                                    <span
                                        class="status-badge status-{{ strtolower($rowData->user->userDetail->international_lic_back_status) }}">
                                        {{ $rowData->user->userDetail->international_lic_back_status }}
                                    </span>
                                @endif
                            </div>
                            <div class="document-image-wrapper">
                                {{-- <a
                                    href="{{ $rowData->user?->userDetail?->international_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                    target="_blank" data-lightbox="gallery" class="document-link">--}}
                                    <a href="javascript:void(0);" class="document-link openPreview"
                                        data-img="{{ $rowData->user?->userDetail?->international_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                        data-docno="{{ $rowData->user?->userDetail?->idp_number }}"
                                        data-id="{{ $rowData->user?->userDetail?->id }}"
                                        data-user_id="{{ $rowData->user?->id }}" data-field="international_lic_back"
                                        data-has-image="{{ $rowData->user?->userDetail?->getRawOriginal('international_lic_back') ? 1 : 0 }}"
                                        data-status="{{ $rowData->user?->userDetail?->international_lic_back_status }}">
                                        <img class="document-image"
                                            src="{{ $rowData->user?->userDetail?->international_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                            alt="International License Back">
                                        <div class="document-overlay">
                                            <i class="bx bx-search-alt"></i>
                                            <span>View Full Size</span>
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2">
                                Intl Dri. No : {{$rowData->user?->userDetail?->idp_number}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Regular License Front --}}
                <div class="col">
                    <div class="document-card h-100">
                        <div class="document-card-inner">
                            <div class="document-header">
                                <h6 class="document-title">
                                    <i class="bx bx-card me-2"></i>Regular License (Front)
                                </h6>
                                @if(!empty($rowData->user?->userDetail?->regular_lic_status))
                                    <span
                                        class="status-badge status-{{ strtolower($rowData->user->userDetail->regular_lic_status) }}">
                                        {{ $rowData->user->userDetail->regular_lic_status }}
                                    </span>
                                @endif
                            </div>
                            <div class="document-image-wrapper">
                                {{-- <a
                                    href="{{ $rowData->user?->userDetail?->regular_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                    target="_blank" data-lightbox="gallery" class="document-link">--}}
                                    <a href="javascript:void(0);" class="document-link openPreview"
                                        data-img="{{ $rowData->user?->userDetail?->regular_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                        data-docno="{{ $rowData->user?->userDetail?->regular_lic_number }}"
                                        data-id="{{ $rowData->user?->userDetail?->id }}"
                                        data-user_id="{{ $rowData->user?->id }}" data-field="regular_lic"
                                        data-has-image="{{ $rowData->user?->userDetail?->getRawOriginal('regular_lic') ? 1 : 0 }}"
                                        data-status="{{ $rowData->user?->userDetail?->regular_lic_status }}">
                                        <img class="document-image"
                                            src="{{ $rowData->user?->userDetail?->regular_lic ?: asset('uploads/user_documents/default.jpg') }}"
                                            alt="Regular License Front">
                                        <div class="document-overlay">
                                            <i class="bx bx-search-alt"></i>
                                            <span>View Full Size</span>
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2">
                                Licence No : {{$rowData->user?->userDetail?->regular_lic_number}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Regular License Back --}}
                <div class="col">
                    <div class="document-card h-100">
                        <div class="document-card-inner">
                            <div class="document-header">
                                <h6 class="document-title">
                                    <i class="bx bx-card me-2"></i>Regular License (Back)
                                </h6>
                                @if(!empty($rowData->user?->userDetail?->regular_lic_back_status))
                                    <span
                                        class="status-badge status-{{ strtolower($rowData->user->userDetail->regular_lic_back_status) }}">
                                        {{ $rowData->user->userDetail->regular_lic_back_status }}
                                    </span>
                                @endif
                            </div>
                            <div class="document-image-wrapper">
                                {{-- <a
                                    href="{{ $rowData->user?->userDetail?->regular_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                    target="_blank" data-lightbox="gallery" class="document-link">--}}
                                    <a href="javascript:void(0);" class="document-link openPreview"
                                        data-img="{{ $rowData->user?->userDetail?->regular_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                        data-docno="{{ $rowData->user?->userDetail?->regular_lic_number }}"
                                        data-id="{{ $rowData->user?->userDetail?->id }}"
                                        data-user_id="{{ $rowData->user?->id }}" data-field="regular_lic_back"
                                        data-has-image="{{ $rowData->user?->userDetail?->getRawOriginal('regular_lic_back') ? 1 : 0 }}"
                                        data-status="{{ $rowData->user?->userDetail?->regular_lic_back_status }}">
                                        <img class="document-image"
                                            src="{{ $rowData->user?->userDetail?->regular_lic_back ?: asset('uploads/user_documents/default.jpg') }}"
                                            alt="Regular License Back">
                                        <div class="document-overlay">
                                            <i class="bx bx-search-alt"></i>
                                            <span>View Full Size</span>
                                        </div>
                                    </a>
                            </div>
                            <div class="p-2">
                                Licence No : {{$rowData->user?->userDetail?->regular_lic_number}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- Document modal -->
    <div class="modal fade" id="docPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <img id="docPreviewImage" style="max-height:500px;width:100%;object-fit:contain;">

                    <h6 class="mt-3">
                        Document No : <span id="docPreviewNumber"></span>
                    </h6>

                    <div class="mt-4">
                        <button type="button" class="btn btn-success me-2" id="docVerifyBtn">
                            Verify
                        </button>

                        <button type="button" class="btn btn-danger" id="docRejectBtn">
                            Reject
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function verifyDocument(id, field, element) {

            let openModals = document.querySelectorAll('.modal.show');
            let topModal = openModals.length
                ? openModals[openModals.length - 1]
                : document.body;

            Swal.fire({
                title: "Are you sure?",
                text: "Verify this " + field.replace('_', ' ') + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Verify",
                cancelButtonText: "Cancel",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-success mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                target: topModal
            }).then(function (t) {

                if (!t.isConfirmed) return;

                $.ajax({
                    url: "{{ route('admin.user.status.verify') }}",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        field: field,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        $(element).prop('disabled', true);
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                    },
                    error: function (xhr) {
                        actionError(xhr);
                    }
                });

            });
        }

        $(document).ready(function () {
            $('#btnSendSelected').on('click', function () {
                // Get dynamic values from the Blade template and inject them into JavaScript variables
                let id = "{{ $rowData->id }}"; // Booking ID
                let bookingId = "{{ $rowData->booking_id }}"; // Booking ID
                let carId = "{{ $rowData->car_id }}"; // Car ID
                let email = "{{ $rowData->email }}"; // Email
                let status = "{{ $rowData->status->value }}"; // Status of the booking

                // Prepare the actions and their selected statuses (0 or 1)
                let selectedActions = {
                    send_payment_link: $('input[value="payment_link"]:checked').length ? 1 : 0,
                    send_booking_detail: $('input[value="booking_detail"]:checked').length ? 1 : 0,
                    send_login_detail: $('input[value="login_detail"]:checked').length ? 1 : 0,
                    send_document_verified: $('input[value="document_verified"]:checked').length ? 1 : 0
                };

                // Check if at least one checkbox is selected (value is 1)
                if (Object.values(selectedActions).every(status => status === 0)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please select at least one option'
                    });
                    return;
                }

                // Confirmation before sending emails
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to send selected emails?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Send",
                    cancelButtonText: "Cancel",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4"
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    $("#bulkSendSpinner").show();
                    $("#btnSendSelected").prop("disabled", true);

                    let requests = [];

                    // Booking Detail
                    if (selectedActions.send_booking_detail) {
                        requests.push(
                            $.ajax({
                                url: "{{ route('admin.booking.send-booking-detail') }}", // Update this route
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id: id,
                                    booking_id: bookingId,
                                    car_id: carId,
                                    email: email,
                                    status: status,
                                    _token: "{{ csrf_token() }}"
                                }
                            })
                        );
                    }

                    // Login Detail
                    if (selectedActions.send_login_detail) {
                        requests.push(
                            $.ajax({
                                url: "{{ route('admin.booking.send-login-detail') }}", // Update this route
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id: id,
                                    booking_id: bookingId,
                                    email: email,
                                    status: status,
                                    _token: "{{ csrf_token() }}"
                                }
                            })
                        );
                    }

                    // Document Verified Mail
                    if (selectedActions.send_document_verified) {
                        requests.push(
                            $.ajax({
                                url: "{{ route('admin.booking.send-verified-mail') }}", // Update this route
                                type: "POST",
                                dataType: "json",
                                data: {
                                    booking_id: bookingId,
                                    _token: "{{ csrf_token() }}"
                                }
                            })
                        );
                    }

                    // Payment Link
                    if (selectedActions.send_payment_link) {
                        requests.push(
                            $.ajax({
                                url: "{{ route('admin.booking.payment_link') }}", // Update this route
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id: id,
                                    booking_id: bookingId,
                                    _token: "{{ csrf_token() }}"
                                }
                            })
                        );
                    }

                    // After all requests are done, update the database to set the statuses
                    requests.push(
                        $.ajax({
                            url: "{{ route('admin.booking.set-status') }}", // Controller method to update status
                            type: "POST",
                            dataType: "json",
                            data: {
                                booking_id: bookingId,
                                selectedActions: selectedActions, // Send selected actions with their statuses (0 or 1)
                                _token: "{{ csrf_token() }}"
                            }
                        })
                    );

                    // Wait for all requests to complete
                    $.when.apply($, requests)
                        .done(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'All selected emails sent successfully'
                            });
                        })
                        .fail(function (xhr) {
                            let message = "Some emails failed to send.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        })
                        .always(function () {
                            $("#bulkSendSpinner").hide();
                            $("#btnSendSelected").prop("disabled", false);
                            $('.bulk-action').prop('checked', false); // Reset all checkboxes
                        });
                });
            });

            $(document).on('click', '.documentVerifiedMail', function () {
                let booking_id = $(this).data('booking-id');
                $("#documentVerifiedMailForm input[name=booking_id]").val(booking_id);
                $("#documentVerifiedMailModal").modal('show');
            });

            $('#docPreviewModal').on('hidden.bs.modal', function () {
                $('body').addClass('modal-open');
            });

            // Reject Button
            function rejectDocument(id, field, element) {

                let openModals = document.querySelectorAll('.modal.show');
                let topModal = openModals.length
                    ? openModals[openModals.length - 1]
                    : document.body;

                Swal.fire({
                    title: "Reject Document?",
                    text: "Are you sure you want to reject this " + field.replace('_', ' ') + "?",
                    icon: "warning",
                    input: "textarea",
                    inputPlaceholder: "Enter rejection reason...",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Reject",
                    cancelButtonText: "Cancel",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                    target: topModal
                }).then(function (result) {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('admin.user.status.rejected.single') }}",
                        method: "POST",
                        dataType: "JSON",
                        data: {
                            id: id,
                            field: field,
                            message: result.value,
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function () {
                            $(element).prop('disabled', true);
                        },
                        success: function (response) {

                            sendSuccess(response.message);

                            let previewInstance = bootstrap.Modal.getInstance(document.getElementById('docPreviewModal'));
                            previewInstance.hide();

                            location.reload(); // optional
                        },
                        error: function (xhr) {
                            actionError(xhr);
                        }
                    });

                });
            }

            $("#documentVerifiedMailForm").validate({
                rules: {
                    message: { required: false },
                },
                messages: {},
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },

                submitHandler: function (form, e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to send the Document Verified Mail?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Send",
                        cancelButtonText: "No, Cancel",
                        confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                        cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (!result.isConfirmed) return;

                        $.ajax({
                            url: "{{ route('admin.user.status.rejected') }}",
                            method: "POST",
                            dataType: "json",
                            data: new FormData(form),
                            processData: false,
                            contentType: false,
                            cache: false,

                            beforeSend: function () {
                                $('#submitDocumentMailBtn').attr('disabled', true);
                                $("#docMailBtnSpinner").show();
                            },

                            success: function (response) {
                                sendSuccess(response.message);
                                $("#documentVerifiedMailModal").modal('hide');
                                $("#documentVerifiedMailForm").trigger("reset");
                                $("label.error").hide();
                            },

                            error: function (xhr) {
                                let data = xhr.responseJSON;

                                if (data.hasOwnProperty('error')) {
                                    $.each(data.error, function (key, value) {
                                        $("#" + key + "-error").html(value).show();
                                    });
                                } else if (data.hasOwnProperty('message')) {
                                    actionError(xhr, data.message);
                                } else {
                                    actionError(xhr);
                                }
                            },

                            complete: function () {
                                $('#submitDocumentMailBtn').attr('disabled', false);
                                $("#docMailBtnSpinner").hide();
                            }
                        });
                    });
                }
            });

            // Open Preview Modal
            $(document).on('click', '.openPreview', function () {

                let img = $(this).data('img');
                let docno = $(this).data('docno');
                let id = $(this).data('id');
                let user_id = $(this).data('user_id');
                let field = $(this).data('field');
                let status = $(this).data('status');
                let has_image = $(this).data('has-image');

                $('#docPreviewImage').attr('src', img);
                $('#docPreviewNumber').text(docno ?? 'N/A');

                // VERIFY
                $('#docVerifyBtn')
                    .off('click')
                    .on('click', function () {
                        verifyDocument(id, field, this);
                    });

                // REJECT
                $('#docRejectBtn')
                    .off('click')
                    .on('click', function () {
                        rejectDocument(id, field, this);
                    });

                if (has_image == 1) {
                    if (status === 'VERIFIED') {
                        $('#docVerifyBtn').prop('disabled', true).text('Verified').show();
                        $('#docRejectBtn').prop('disabled', false).text('Reject').show();
                    } else if (status === 'REJECTED') {
                        $('#docVerifyBtn').prop('disabled', false).text('Verify').show();
                        $('#docRejectBtn').prop('disabled', true).text('Rejected').show();
                    } else {
                        $('#docVerifyBtn').prop('disabled', false).text('Verify').show();
                        $('#docRejectBtn').prop('disabled', false).text('Reject').show();
                    }
                } else {
                    $('#docVerifyBtn').hide();
                    $('#docRejectBtn').hide();
                }

                let previewModal = new bootstrap.Modal(document.getElementById('docPreviewModal'));
                previewModal.show();
            });

            $('input[name="start_date"], input[name="end_date"], input[name="start_time"], input[name="end_time"], select[name="car_id"], select[name="selected_accessories[]"]').on('change', function () {
                calculateQuote();
            });

            function calculateQuote() {
                let formData = new FormData($('#viewForm')[0]);
                formData.append('id', "{{ $rowData->id }}");
                $.ajax({
                    url: "{{ route('admin.booking.calculate-quote') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status) {
                            $('input[name="price"]').val(Math.round(response.message.price));
                            if (response.message.insurance_price > 0) {
                                $('#insurance_amount_display').text('¥' + Math.round(response.message.insurance_price).toLocaleString());
                            } else {
                                $('#insurance_amount_display').text('');
                            }
                        }
                    },
                    error: function (xhr) {
                        console.error("Error calculating quote");
                    }
                });
            }

            $("#viewForm").validate({
                rules: {
                    first_name: { required: true },
                    last_name: { required: true },

                    start_date: { required: true },
                    end_date: { required: true },
                    start_time: { required: true },
                    end_time: { required: true },
                    price: { required: true },
                },
                messages: {
                    first_name: { required: "The first name is required." },
                    last_name: { required: "The last name is required." },
                    start_date: { required: "The start date is required." },
                    end_date: { required: "The end date is required." },
                    start_time: { required: "The start time is required." },
                    end_time: { required: "The end time is required." },
                    price: { required: "The price is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.booking.view.action', $rowData->id) }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submitBtn').attr('disabled', true);
                            $("#processingBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });

                                if (data.error.hasOwnProperty('car_ids')) {
                                    sendError(data.error.car_ids);
                                }
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#submitBtn').attr('disabled', false);
                            $("#processingBtnSpinner").hide();
                        },
                    });
                }
            });

            $('#btnSendLoginDetail').on('click', function () {
                var bookingId = $(this).data('booking-id');
                var id = $(this).data('id');
                var email = $(this).data('email');
                // var status=$(this).data('status');
                // var fname = $(this).data('fname');
                // var lname = $(this).data('lname');

                $.ajax({
                    url: "{{ route('admin.booking.send-login-detail') }}",
                    method: "post",
                    dataType: "json",

                    data: {
                        id: id,
                        booking_id: bookingId,
                        email: email,
                        status: status,
                        "_token": "{{csrf_token()}}",
                    },

                    beforeSend: function () {
                        $('#btnSendLoginDetail').attr('disabled', true);
                        $("#btnSendLoginDetailSpinner").show();
                    },
                    success: function (result) {
                        sendSuccess(result.message);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            $.each(data.error, function (key, value) {
                                $("#" + key + "-error").html(value).show();
                            });

                            if (data.error.hasOwnProperty('car_ids')) {
                                sendError(data.error.car_ids);
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message);
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#btnSendLoginDetail').attr('disabled', false);
                        $("#btnSendLoginDetailSpinner").hide();
                    },

                });

            });

            $(document).on('click', '.btnSendBookingDetail', function () {
                alert('dd');
                var bookingId = $(this).data('booking-id');
                var carId = $(this).data('car_id'); // underscore, not hyphen
                var id = $(this).data('id');
                var email = $(this).data('email');
                var status = $(this).data('status');

                $.ajax({
                    url: "{{ route('admin.booking.send-booking-detail') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        id: id,
                        booking_id: bookingId,
                        car_id: carId,
                        email: email,
                        status: status,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        $('.btnSendBookingDetail').prop('disabled', true);
                        $("#btnSendBookingDetailSpinner").show();
                    },
                    complete: function () {
                        $('.btnSendBookingDetail').prop('disabled', false);
                        $("#btnSendBookingDetailSpinner").hide();
                    }
                });
            });

            $(document).on('click', '.documentVerifiedMail', function () {

                var bookingId = $(this).data('booking-id');
                var status = $(this).data('status');

                $.ajax({
                    url: "{{ route('admin.booking.send-verified-mail') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        booking_id: bookingId,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        $('.btnSendBookingDetail').prop('disabled', true);
                        $("#btnSendBookingDetailSpinner").show();
                    },
                    complete: function () {
                        $('.btnSendBookingDetail').prop('disabled', false);
                        $("#btnSendBookingDetailSpinner").hide();
                    }
                });
            });

            $("#updatePasswordForm").validate({
                rules: {
                    password: { required: true, minlength: 6 },
                },
                messages: {
                    password: {
                        required: "The password is required.",
                        minlength: "The password must be at least 6 characters."
                    },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.closest('.input-group').after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.booking.update-customer-password') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnUpdatePassword').attr('disabled', true);
                            $("#btnUpdatePasswordSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            $('#manual_password').val('');
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    sendError(value);
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnUpdatePassword').attr('disabled', false);
                            $("#btnUpdatePasswordSpinner").hide();
                        },
                    });
                }
            });
        });
    </script>
@endsection