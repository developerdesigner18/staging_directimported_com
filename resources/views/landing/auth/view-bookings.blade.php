@extends('landing.master')
@section('title', 'Confirm Your Booking')
@push('style')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .booking-card {
            background: #ffffff;
            border-radius: 8px;
            /* Slightly tighter radius */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            /* Softer shadow */
            padding: 40px;
            margin-top: 30px;
            margin-bottom: 50px;
            max-width: 800px;
            /* Limit width */
            margin-left: auto;
            margin-right: auto;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 2rem;
            margin-top: 1rem;
            text-align: center;
        }

        .section-header-small {
            font-size: 1rem;
            /* 16px */
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
        }

        .car-name {
            color: #b91c1c;
            /* Deep Red */
            font-weight: 700;
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .booking-id {
            color: #9ca3af;
            /* Light gray */
            font-size: 0.85rem;
            font-weight: 400;
        }

        .amount-label {
            font-size: 1rem;
            font-weight: 700;
            color: #374151;
            margin-right: 5px;
        }

        .amount-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: #333;
            line-height: 1;
        }

        .payment-badge {
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .accessory-list {
            margin-top: 10px;
            padding-left: 0;
            list-style: none;
        }

        .accessory-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .accessory-item i {
            color: #22c55e;
            /* Green */
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .contact-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .contact-row:last-child {
            border-bottom: none;
        }

        .contact-label {
            font-weight: 700;
            color: #374151;
            font-size: 0.95rem;
        }

        .contact-value {
            color: #4b5563;
            font-size: 0.95rem;
            text-align: right;
        }

        .rental-duration-badge {
            background-color: #7f1d1d;
            /* Dark Red */
            color: white;
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 10px;
        }

        .date-box {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: #fff;
            height: 100%;
        }

        .date-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-weight: 700;
            color: #374151;
            font-size: 0.95rem;
        }

        .date-icon {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .date-display {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 5px;
        }

        .time-display {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .comment-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px 15px;
            color: #6b7280;
            background-color: #fff;
        }

        .btn-proceed {
            background-color: #991b1b;
            /* Deep Red */
            color: white;
            font-weight: 600;
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            border-radius: 6px;
            border: none;
            transition: background-color 0.2s;
            display: block;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-proceed:hover {
            background-color: #7f1d1d;
            color: white;
        }

        .hr-divider {
            margin: 25px 0;
            border-top: 1px solid #e5e7eb;
        }

        /* Layout overrides */
        .car-img-container {
            border-radius: 8px;
            overflow: hidden;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .contact-section-wrapper {
            /* Custom wrapper for contact details to match spacing */
        }

        .profile-back-section {
            display: flex;
            text-align: center;
            padding-left: 17%;
        }

        .back-btn {
            width: 20%;
            border-radius: 10px;
            display: flex;
            justify-content: center;
        }
    </style>
@endpush

@section('main')
    <div class="container pb-5">
        <h2 class="page-title">Confirm Your Booking</h2>

        <div class="booking-card">
            <!-- Selected Car Section -->
            <a href="{{ route('profile.settings') }}">
                <div class="bg-danger text-white p-2 back-btn">
                    <svg fill="#000000" height="20px" width="20px" viewBox="0 0 24 24" id="left-arrow" data-name="Flat Line"
                        xmlns="http://www.w3.org/2000/svg" class="icon flat-line" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <line id="primary" x1="21" y1="12" x2="3" y2="12"
                                style="fill: none; stroke: #ffffff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                            </line>
                            <polyline id="primary-2" data-name="primary" points="6 9 3 12 6 15"
                                style="fill: none; stroke: #ffffff; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                            </polyline>
                        </g>
                    </svg>
                    <div class="ms-1">Back</div>
                </div>
            </a>
            <div class="mb-4">
                <h5 class="section-header-small">Selected Car</h5>
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="car-img-container">
                            @if($booking->car && $booking->car->images && count($booking->car->images) > 0)
                                <img src="{{ asset(CAR_PATH . $booking->car->images[0]) }}" alt="{{ $booking->car->name }}"
                                    class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            @else
                                <i class="bx bxs-car text-muted" style="font-size: 4rem;"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h3 class="car-name mb-1">{{ $booking->car->name ?? 'Unknown Car' }}</h3>
                        <div class="booking-id mb-3">Booking ID: #{{ $booking->booking_id ?? 'N/A' }}</div>

                        <div class="mb-3">
                            <span class="amount-label">Total Amount:</span>
                            <div class="d-flex align-items-center flex-wrap">
                                <span class="amount-value">¥{{ number_format($booking->price ?? 0) }}</span>

                                @php
                                    $statusText = 'Payment Remaining';
                                    $badgeClass = 'bg-warning text-dark'; // Default: Yellow

                                    if ($booking->status) {
                                        $val = $booking->status->value;

                                        if ($val === 'confirmed') {
                                            $statusText = 'Confirmed & Paid';
                                            $badgeClass = 'bg-success text-white';

                                        } elseif ($val === 'approved') {
                                            $statusText = 'Approved - Payment remaining';
                                            $badgeClass = 'bg-info text-white';

                                        } elseif ($val === 'processing') {
                                            $statusText = 'Processing - Approval pending';
                                            $badgeClass = 'bg-warning text-dark';

                                        } elseif ($val === 'cancelled') {
                                            $statusText = 'Not approved / Cancelled';
                                            $badgeClass = 'bg-danger text-white';

                                        } elseif ($val === 'finished') {
                                            $statusText = 'Finished & Returned';
                                            $badgeClass = 'bg-primary text-white';

                                        } else {
                                            $statusText = ucfirst($val);
                                            $badgeClass = 'bg-light text-dark';
                                        }
                                    }
                                @endphp
                                <span class="payment-badge {{ $badgeClass }}"
                                    style="border-radius: 9999px; padding: 4px 12px; font-weight: 600; font-size: 0.75rem; vertical-align: middle; margin-left: 10px; display: inline-block;">{{ $statusText }}</span>
                            </div>
                        </div>

                        <div class="accessories-section">
                            @php
                                $includedAccessories = $booking->includedAccessoriesList();
                                $selectedAccessories = $booking->selectedAccessoriesList();
                            @endphp

                            @if($includedAccessories && $includedAccessories->isNotEmpty())
                                <div class="mb-3">
                                    <h6 class="text-dark fw-bold" style="font-size: 0.9rem; margin-bottom: 5px;">Included
                                        Accessories:</h6>
                                    <ul class="accessory-list">
                                        @foreach($includedAccessories as $acc)
                                            <li class="accessory-item">
                                                <i class="bx bxs-check-circle"></i>
                                                {{ $acc->name }} (Free)
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($selectedAccessories && $selectedAccessories->isNotEmpty())
                                <div class="mb-3">
                                    <h6 class="text-dark fw-bold" style="font-size: 0.9rem; margin-bottom: 5px;">Extra
                                        Accessories:Selected</h6>
                                    <ul class="accessory-list">
                                        @foreach($selectedAccessories as $acc)
                                            <li class="accessory-item">
                                                <i class="bx bxs-plus-circle"></i>
                                                {{ $acc->name }}
                                                {{-- @if($acc->price > 0) (¥{{ number_format($acc->price) }}) @endif --}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if((!$includedAccessories || $includedAccessories->isEmpty()) && (!$selectedAccessories || $selectedAccessories->isEmpty()))
                                <div class="mb-3">
                                    <h6 class="text-dark fw-bold" style="font-size: 0.9rem; margin-bottom: 5px;">Included
                                        Accessories:</h6>
                                    <ul class="accessory-list">
                                        <li class="accessory-item"><i class="bx bxs-check-circle"></i> Standard Equipment</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="hr-divider"></div>

            <!-- Contact Details -->
            <div class="mb-4 contact-section-wrapper">
                <h5 class="section-header-small">Contact Details</h5>

                <div class="contact-row">
                    <span class="contact-label">First Name</span>
                    <span class="contact-value">{{ $booking->user->first_name ?? '' }}</span>
                </div>
                <div class="contact-row">
                    <span class="contact-label">Last Name</span>
                    <span class="contact-value">{{ $booking->user->last_name ?? '' }}</span>
                </div>
                <div class="contact-row">
                    <span class="contact-label">Email Address</span>
                    <span class="contact-value">{{ $booking->user->email ?? '' }}</span>
                </div>
                <div class="contact-row">
                    <span class="contact-label">Contact Number</span>
                    <span class="contact-value">{{ $booking->user->mobile ?? '[Not Provided]' }}</span>
                </div>
                <div class="contact-row align-items-center">
                    <span class="contact-label">Rental Location</span>
                    <div class="d-flex align-items-center justify-content-end w-100">
                        @php
                            $durationDays = $booking->totalDays();
                        @endphp
                        <span class="rental-duration-badge">{{ $durationDays }} Day Rental</span>
                    </div>
                </div>
            </div>

            <!-- Pickup and Drop-off Details -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="date-box">
                        <div class="date-header">
                            <i class="bx bxs-calendar-event date-icon" style="color: #22c55e;"></i>
                            Pickup Details
                        </div>
                        <div class="date-display">
                            {{ $booking->start_date ? $booking->start_date->format('d - M- Y') : 'N/A' }}
                        </div>
                        <div class="time-display">
                            {{ $booking->start_date ? $booking->start_date->format('l') : '' }},
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '10:00 AM' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="date-box">
                        <div class="date-header">
                            <i class="bx bxs-calendar-event date-icon" style="color: #b91c1c;"></i>
                            Drop-off Details
                        </div>
                        <div class="date-display">
                            {{ $booking->end_date ? $booking->end_date->format('d - M- Y') : 'N/A' }}
                        </div>
                        <div class="time-display">
                            {{ $booking->end_date ? $booking->end_date->format('l') : '' }},
                            {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '10:00 AM' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Locations & Comments -->
            <div class="mb-4">
                <h5 class="section-header-small">Rental Locations</h5>
                <p class="text-secondary mb-3" style="font-size: 0.95rem;">
                    {{ $booking->location ?? 'Osaka' }}
                </p>

                <h5 class="section-header-small mt-4">Comments / Special Requests</h5>
                <input type="text" class="comment-input" value="{{ $booking->comment ?? '' }}" placeholder="check" readonly>
            </div>

            <!-- Action Button -->
            <div class="mt-4">
                <a href="#" class="btn-proceed">Proceed to Payment</a>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src=""></script>
@endsection