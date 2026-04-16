@extends('landing.master')
@section('title','Profile')

@push('style')
    <style>
        /* ================= DOCUMENT VERIFICATION NEW DESIGN ================= */

        .documents-wrapper {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
        }

        .document-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 18px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .document-title {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
        }

        .document-number {
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-left: 10px;
        }

        .document-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .document-images {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .document-thumb {
            width: 90px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: 0.3s;
        }

        .document-thumb:hover {
            transform: scale(1.05);
        }

        .document-status {
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .status-approved {
            background: #16a34a;
            color: white;
        }

        .status-pending {
            background: #f59e0b;
            color: white;
        }

        .status-rejected {
            background: #dc2626;
            color: white;
        }

        .update-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .update-btn:hover {
            background: #b91c1c;
        }

        /* image modal preview */
        .image-preview-modal img {
            width: 100%;
        }

        /* ===== DRIVER PORTAL WIZARD MODAL ===== */
        #driverPortalModal .modal-dialog {
            max-width: 620px;
        }

        #driverPortalModal .modal-content {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .dp-header {
            background: #fff;
            padding: 18px 24px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .dp-portal-title {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        /* Step progress bar */
        .dp-steps {
            display: flex;
            gap: 0;
            margin-bottom: 0;
        }

        .dp-step {
            flex: 1;
            text-align: center;
            padding: 10px 6px;
            font-size: 12px;
            color: #9ca3af;
            border-bottom: 3px solid #e5e7eb;
            cursor: default;
            transition: all 0.2s;
        }

        .dp-step.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
            font-weight: 600;
        }

        .dp-step.done {
            color: #16a34a;
            border-bottom-color: #16a34a;
        }

        /* Step body */
        .dp-body {
            padding: 24px;
        }

        .dp-step-panel {
            display: none;
        }

        .dp-step-panel.active {
            display: block;
        }

        .dp-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        /* Warning box */
        .dp-warning {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 12.5px;
            color: #92400e;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .dp-warning i {
            color: #f59e0b;
            font-size: 16px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* Upload zone */
        .dp-upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            background: #f9fafb;
            text-align: center;
            padding: 30px 20px;
            cursor: pointer;
            transition: all 0.25s;
            position: relative;
        }

        .dp-upload-zone:hover,
        .dp-upload-zone.dragover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .dp-upload-zone.has-file {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .dp-upload-zone .dp-upload-icon {
            font-size: 28px;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .dp-upload-zone.has-file .dp-upload-icon {
            color: #16a34a;
        }

        .dp-upload-zone .dp-upload-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 3px;
        }

        .dp-upload-zone .dp-upload-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        .dp-upload-zone img.dp-preview {
            max-height: 120px;
            max-width: 100%;
            object-fit: contain;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .dp-upload-zones-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Footer */
        .dp-footer {
            padding: 16px 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
        }

        .dp-next-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.04em;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .dp-next-btn:hover {
            background: #1d4ed8;
        }

        .dp-next-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-list li {
            list-style-type: disc;
            /* bullet point */
            margin-left: 20px;
            /* indent */
            color: #dc3545;
            /* Bootstrap red */
            font-size: 0.9rem;
            display: none;
            /* hidden by default */
        }

        .error-list li.active {
            display: list-item;
            /* show when error present */
        }

        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }

        .profile-container {
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            min-height: 200px;
            position: relative;
        }

        .profile-card {
            margin-top: -100px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px 10px 0 0;
            margin-right: 5px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: #053C7C;
        }

        .profile-avatar {
            position: relative;
            display: inline-block;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #053C7C;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-avatar .camera-icon:hover {
            background: #5a67d8;
            transform: scale(1.1);
        }

        .document-upload {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #f8fafc;
        }

        .document-upload:hover {
            border-color: #053C7C;
            background: #f0f4ff;
        }

        .document-upload.has-image {
            border-style: solid;
            border-color: #10b981;
            background: #f0fdf4;
        }

        .document-preview {
            width: 100%;
            max-width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .document-icon {
            font-size: 2rem;
            color: #cbd5e0;
            margin-bottom: 10px;
        }

        .document-upload.has-image .document-icon {
            display: none;
        }

        .verification-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .verification-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .verification-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .verification-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .form-control:focus {
            border-color: #053C7C;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .document-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: block;
        }

        .upload-text {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .upload-text strong {
            color: #053C7C;
        }

        .hidden-input {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .show-input {
            display: block !important;
            opacity: 1;
            transform: translateY(0);
            margin-top: 10px;
        }

        .data-table {
            table-layout: fixed !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .data-table thead th {
            text-align: center !important;
            vertical-align: middle !important;
            background-color: #f8f9fa !important;
            color: #333 !important;
            font-weight: 600 !important;
            width: 12.5% !important; /* 100% / 8 columns */
            border-bottom: 2px solid #dee2e6 !important;
        }

        .data-table tbody td {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 12px 8px !important;
        }

        .data-table tbody tr:hover {
            background-color: #fcfcfc;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            border: none;
        }
        .swal2-actions{
            gap: 5px;
        }
    </style>
@endpush

@section('main')

    {{--<div class="profile-container"></div>--}}
    <div class="container mt-5 mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab">
                                    <i class="fas fa-user me-2"></i>Profile Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">
                                    <i class="fas fa-file-alt me-2"></i>Documents & Verification
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#security" role="tab">
                                    <i class="fas fa-shield-alt me-2"></i>Security Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#bookings" role="tab">
                                    <i class="fas fa-shield-alt me-2"></i>Bookings
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <form id="profileForm" action="javascript:void(0);" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3 text-center mb-4">
                                            <div class="profile-avatar">
                                                <img src="{{$user->profile_img}}"
                                                     alt="Profile Image"
                                                     id="profileImagePreview"
                                                     class="rounded-circle" loading="lazy">
                                                <input type="file" id="profileImageInput" name="profile"
                                                       accept="image/*" class="d-none">
                                                <div class="camera-icon"
                                                     onclick="document.getElementById('profileImageInput').click()">
                                                    <i class="ri-camera-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 mb-1">{{$user->first_name}} {{$user->last_name}}</h5>
                                            <p class="text-muted">Member since {{dateToHuman($user->created_at)}}</p>
                                        </div>

                                        <div class="col-lg-9">
                                            @if(session('warning'))
                                                <div
                                                    class="alert alert-warning alert-border-left alert-dismissible fade show"
                                                    role="alert">
                                                    <i class="ri-alert-line me-3 align-middle"></i>
                                                    {{ session('warning') }}

                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                            aria-label="Close"></button>

                                                </div>
                                            @endif
                                            <h5 class="mb-4">Personal Information</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="firstName" class="form-label">First Name <span
                                                            class="text-danger">*required</span></label>
                                                    <input type="text" id="firstName" name="first_name"
                                                           class="form-control" placeholder="Enter your first name"
                                                           value="{{$user->first_name}}">
                                                    <span class="text-danger small" id="first_name-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="lastName" class="form-label">Last Name <span
                                                            class="text-danger">*required</span></label>
                                                    <input type="text" id="lastName" name="last_name"
                                                           class="form-control" placeholder="Enter your last name"
                                                           value="{{$user->last_name}}">
                                                    <span class="text-danger small" id="last_name-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" id="email" name="email"
                                                           class="form-control" placeholder="Enter your email"
                                                           value="{{$user->email}}">
                                                    <span class="text-danger small" id="email-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="dateOfBirth" class="form-label">Date Of Birth <span
                                                            class="text-danger">*required</span></label>

                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="ri-calendar-2-fill"></i></span>
                                                        <input type="text" class="form-control datepicker"
                                                               id="dateOfBirth" name="date_of_birth"
                                                                value="{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d - M- Y') : '' }}" placeholder="Select Date of Birth">
                                                    </div>
                                                    <span class="text-danger small" id="date_of_birth-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="phone" class="form-label">Phone Number <span
                                                            class="text-danger">*required</span></label>
                                                    <input type="text" id="phone" name="phone"
                                                           class="form-control" placeholder="+1 (555) 123-4567"
                                                           value="{{$user->mobile}}">
                                                    <span class="text-danger small" id="phone-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="address" class="form-label">Address <span
                                                            class="text-danger">*required</span></label>
                                                    <input type="text" id="address" name="address"
                                                           class="form-control" placeholder="Enter your address"
                                                           value="{{$user->address}}">
                                                    <span class="text-danger small" id="address-error"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="country" class="form-label">Country <span
                                                            class="text-danger">*required</span></label>
                                                    <input type="text" id="country" name="country"
                                                           class="form-control" placeholder="Enter your country"
                                                           value="{{$user->country}}">

                                                    <span class="text-danger small" id="country-error"></span>
                                                </div>

                                            </div>

                                            <div class="mt-4 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Documents Tab -->
                            <div class="tab-pane fade" id="documents" role="tabpanel">

                                {{-- READ-ONLY STATUS CARDS --}}

                                <div class="documents-wrapper">

                                    @php
                                        $passport_status = strtoupper($user->userDetail?->passport_status ?? 'PENDING');
                                        $regular_status  = strtoupper($user->userDetail?->regular_lic_status ?? 'PENDING');
                                        $idp_status      = strtoupper($user->userDetail?->international_lic_status ?? 'PENDING');
                                    @endphp

                                    {{-- PASSPORT CARD --}}
                                    <div class="document-card">
                                        <div class="document-header">
                                            <div>
                                                <span class="document-title">1. PASSPORT</span>
                                                <span class="document-number">Number: {{ $user->userDetail?->passport_number ?? '-' }}</span>
                                            </div>
                                            <span class="document-status @if($passport_status=='VERIFIED') status-approved @elseif($passport_status=='REJECTED') status-rejected @else status-pending @endif">
                                                {{ $passport_status }}
                                            </span>
                                        </div>
                                        <div class="document-body">
                                            <div class="document-images">
                                                @if($user->userDetail?->getRawOriginal('passport'))
                                                    <img src="{{ $user->userDetail->passport }}" class="document-thumb" onclick="showImagePreview(this.src)" alt="Passport Document" loading="lazy">
                                                @else
                                                    <span class="text-muted small"><i class="fas fa-image me-1"></i>No document uploaded</span>
                                                @endif
                                            </div>
                                            <button type="button" class="update-btn" onclick="openDriverPortal(1)">
                                                <i class="fas fa-upload me-1"></i>Update Documents
                                            </button>
                                        </div>
                                    </div>

                                    {{-- DRIVER'S LICENSE CARD --}}
                                    <div class="document-card">
                                        <div class="document-header">
                                            <div>
                                                <span class="document-title">2. DRIVER'S LICENSE</span>
                                                <span class="document-number">Number: {{ $user->userDetail?->regular_lic_number ?? '-' }}</span>
                                            </div>
                                            <span class="document-status @if($regular_status=='VERIFIED') status-approved @elseif($regular_status=='REJECTED') status-rejected @else status-pending @endif">
                                                {{ $regular_status }}
                                            </span>
                                        </div>
                                        <div class="document-body">
                                            <div class="document-images">
                                                @if($user->userDetail?->getRawOriginal('regular_lic'))
                                                    <img src="{{ $user->userDetail->regular_lic }}" class="document-thumb" onclick="showImagePreview(this.src)" alt="License Front" loading="lazy">
                                                @endif
                                                @if($user->userDetail?->getRawOriginal('regular_lic_back'))
                                                    <img src="{{ $user->userDetail->regular_lic_back }}" class="document-thumb" onclick="showImagePreview(this.src)" alt="License Back" loading="lazy">
                                                @endif
                                                @if(!$user->userDetail?->getRawOriginal('regular_lic'))
                                                    <span class="text-muted small"><i class="fas fa-image me-1"></i>No document uploaded</span>
                                                @endif
                                            </div>
                                            <button type="button" class="update-btn" onclick="openDriverPortal(2)">
                                                <i class="fas fa-upload me-1"></i>Update Documents
                                            </button>
                                        </div>
                                    </div>

                                    {{-- IDP CARD --}}
                                    <div class="document-card">
                                        <div class="document-header">
                                            <div>
                                                <span class="document-title">3. INT'L PERMIT (IDP)</span>
                                                <span class="document-number">Number: {{ $user->userDetail?->idp_number ?? '-' }}</span>
                                            </div>
                                            <span class="document-status @if($idp_status=='VERIFIED') status-approved @elseif($idp_status=='REJECTED') status-rejected @else status-pending @endif">
                                                {{ $idp_status }}
                                            </span>
                                        </div>
                                        <div class="document-body">
                                            <div class="document-images">
                                                @if($user->userDetail?->getRawOriginal('international_lic'))
                                                    <img src="{{ $user->userDetail->international_lic }}" class="document-thumb" onclick="showImagePreview(this.src)" alt="IDP Front" loading="lazy">
                                                @endif
                                                @if($user->userDetail?->getRawOriginal('international_lic_back'))
                                                    <img src="{{ $user->userDetail->international_lic_back }}" class="document-thumb" onclick="showImagePreview(this.src)" alt="IDP Back" loading="lazy">
                                                @endif
                                                @if(!$user->userDetail?->getRawOriginal('international_lic'))
                                                    <span class="text-muted small"><i class="fas fa-image me-1"></i>No document uploaded</span>
                                                @endif
                                            </div>
                                            <button type="button" class="update-btn" onclick="openDriverPortal(3)">
                                                <i class="fas fa-upload me-1"></i>Update Documents
                                            </button>
                                        </div>
                                    </div>

                                </div>{{-- /.documents-wrapper --}}

                            </div>{{-- /.tab-pane documents --}}


                            {{-- ============ DRIVER PORTAL MODAL ============ --}}
                            <div class="modal fade" id="driverPortalModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        {{-- MODAL HEADER --}}
                                        <div class="dp-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="dp-portal-title">DRIVER PORTAL</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="dp-steps">
                                                <div class="dp-step active" id="dpStep1Tab">Step 1 of 3: Passport</div>
                                                <div class="dp-step" id="dpStep2Tab">Step 2 of 3: Licence</div>
                                                <div class="dp-step" id="dpStep3Tab">Step 3 of 3: IDP</div>
                                            </div>
                                        </div>

                                        {{-- MODAL BODY --}}
                                        <form id="documentsForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="dp-body">

                                                {{-- STEP 1: PASSPORT --}}
                                                <div class="dp-step-panel active" id="dpPanel1">
                                                    <div class="dp-section-title">1. Passport Details</div>
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold" style="font-size:13px;">Passport Number</label>
                                                            <input type="text" id="dp_passport_number" name="passport_number"
                                                                   class="form-control form-control-sm"
                                                                   placeholder="e.g., M12345678"
                                                                   value="{{ $user->userDetail?->passport_number ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="dp-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <span><strong>CRITICAL:</strong> The passport number in the photo must be 100% readable and match the number you type above.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dp-upload-zone" id="passportZone" onclick="document.getElementById('passportInput').click()">
                                                        <div class="dp-upload-icon"><i class="fas fa-camera"></i></div>
                                                        <div class="dp-upload-label">Click to Upload Passport Photo</div>
                                                        <div class="dp-upload-sub">or drag and drop</div>
                                                        <img class="dp-preview d-none" id="passportThumb" alt="Passport Preview" loading="lazy">
                                                    </div>
                                                    <input type="file" id="passportInput" name="passport" class="d-none" accept="image/*">
                                                </div>

                                                {{-- STEP 2: DRIVER'S LICENSE --}}
                                                <div class="dp-step-panel" id="dpPanel2">
                                                    <div class="dp-section-title">2. Licence Details</div>
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold" style="font-size:13px;">Licence Number</label>
                                                            <input type="text" id="dp_regular_license_number" name="regular_license_number"
                                                                   class="form-control form-control-sm"
                                                                   placeholder="e.g., M12345678"
                                                                   value="{{ $user->userDetail?->regular_lic_number ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="dp-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <span><strong>CRITICAL:</strong> The licence number in the photo must be 100% readable and match the number you type above.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dp-upload-zones-row">
                                                        <div class="dp-upload-zone" id="regularLicZone" onclick="document.getElementById('regularLicInput').click()">
                                                            <div class="dp-upload-icon"><i class="fas fa-camera"></i></div>
                                                            <div class="dp-upload-label">Click to upload Licence front side</div>
                                                            <div class="dp-upload-sub">or drag and drop</div>
                                                            <img class="dp-preview d-none" id="regularLicThumb" alt="License Front Preview" loading="lazy">
                                                        </div>
                                                        <div class="dp-upload-zone" id="regularBackZone" onclick="document.getElementById('regularBackInput').click()">
                                                            <div class="dp-upload-icon"><i class="fas fa-camera"></i></div>
                                                            <div class="dp-upload-label">Click to upload Licence back side</div>
                                                            <div class="dp-upload-sub">or drag and drop</div>
                                                            <img class="dp-preview d-none" id="regularBackThumb" alt="License Back Preview" loading="lazy">
                                                        </div>
                                                    </div>
                                                    <input type="file" id="regularLicInput" name="regular_lic" class="d-none" accept="image/*">
                                                    <input type="file" id="regularBackInput" name="regular_lic_back" class="d-none" accept="image/*">
                                                </div>

                                                {{-- STEP 3: IDP --}}
                                                <div class="dp-step-panel" id="dpPanel3">
                                                    <div class="dp-section-title">3. International Driving Permit (IDP)</div>
                                                    <p class="text-muted" style="font-size:12px; margin-top:-8px; margin-bottom:14px;">
                                                        <em>This page will be duplicated for IDP (International drivers permit 1949 Convention only Booklet type)</em>
                                                    </p>
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold" style="font-size:13px;">IDP Number</label>
                                                            <input type="text" id="dp_idp_number" name="idp_number"
                                                                   class="form-control form-control-sm"
                                                                   placeholder="e.g., M12345678"
                                                                   value="{{ $user->userDetail?->idp_number ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="dp-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <span><strong>CRITICAL:</strong> The IDP number in the photo must be 100% readable and match the number you type above.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dp-upload-zones-row">
                                                        <div class="dp-upload-zone" id="idpFrontZone" onclick="document.getElementById('internationalLicInput').click()">
                                                            <div class="dp-upload-icon"><i class="fas fa-camera"></i></div>
                                                            <div class="dp-upload-label">Click to upload IDP front side</div>
                                                            <div class="dp-upload-sub">or drag and drop</div>
                                                            <img class="dp-preview d-none" id="idpFrontThumb" alt="IDP Front Preview" loading="lazy">
                                                        </div>
                                                        <div class="dp-upload-zone" id="idpBackZone" onclick="document.getElementById('internationalLicBackInput').click()">
                                                            <div class="dp-upload-icon"><i class="fas fa-camera"></i></div>
                                                            <div class="dp-upload-label">Click to upload IDP back side</div>
                                                            <div class="dp-upload-sub">or drag and drop</div>
                                                            <img class="dp-preview d-none" id="idpBackThumb" alt="IDP Back Preview" loading="lazy">
                                                        </div>
                                                    </div>
                                                    <input type="file" id="internationalLicInput" name="international_lic" class="d-none" accept="image/*">
                                                    <input type="file" id="internationalLicBackInput" name="international_lic_back" class="d-none" accept="image/*">
                                                </div>

                                            </div>{{-- /.dp-body --}}

                                            {{-- MODAL FOOTER --}}
                                            <div class="dp-footer">
                                                <button type="button" class="dp-next-btn" id="dpNextBtn" onclick="dpNext()">
                                                    NEXT STEP <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>

                                        </form>

                                    </div>{{-- /.modal-content --}}
                                </div>{{-- /.modal-dialog --}}
                            </div>{{-- /#driverPortalModal --}}

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="security" role="tabpanel">
                                <form id="passwordForm" action="javascript:void(0);">
                                    @csrf
                                    <h5 class="mb-4">Change Password</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="oldPassword" class="form-label">Current Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" id="oldPassword" name="old_password"
                                                   class="form-control" placeholder="Enter your current password"
                                                   required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="newPassword" class="form-label">New Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" id="newPassword" name="new_password"
                                                   class="form-control" placeholder="Enter new password" required>
                                            <small class="text-muted">Password must be at least 8 characters
                                                long</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="confirmPassword" class="form-label">Confirm New Password
                                                <span class="text-danger">*</span></label>
                                            <input type="password" id="confirmPassword" name="confirm_password"
                                                   class="form-control" placeholder="Confirm new password" required>
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-key me-2"></i>Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Bookings Tab -->
                            <div class="tab-pane fade" id="bookings" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered data-table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Price</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('profile.booking') }}",
                autoWidth: false,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false}, // Row number column
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'price', name: 'price'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
            // Profile Image Preview
            var profileImageChanged = false;
            $('#profileImageInput').change(function (event) {
                previewImage(event, 'profileImagePreview');
                profileImageChanged = true;
            });

            // Track if profile form has unsaved changes
            var profileFormInitialData = $("#profileForm").serialize();
            function isProfileFormDirty() {
                return profileImageChanged || ($("#profileForm").serialize() !== profileFormInitialData);
            }

            // Handle tab switching specifically for the Profile tab
            $('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
                if ($(e.relatedTarget).attr('href') === '#profile' && isProfileFormDirty()) {
                    e.preventDefault(); // Stop the tab switch

                    var targetElement = e.target;
                    showConfirmNavigation(function() {
                        var tab = new bootstrap.Tab(targetElement);
                        tab.show();
                    });
                }
            });

            // Customized "Confirm Navigation" Prompt for Internal Links
            $(document).on('click', 'a', function(e) {
                var href = $(this).attr('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                
                // Allow tab switching to be handled by the show.bs.tab event listener above
                if ($(this).data('bs-toggle') === 'tab') return;

                if (isProfileFormDirty()) {
                    e.preventDefault();
                    showConfirmNavigation(function() {
                        window.location.href = href;
                    });
                }
            });

            function showConfirmNavigation(onConfirm) {
                Swal.fire({
                    title: 'Unsaved Changes',
                    html: '<p style="font-size: 15px; color: #444;">You have made changes to your profile. Do you want to save them now, or leave without saving?</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Exit Without Saving',
                    cancelButtonText: 'Update Profile & Leave',
                    confirmButtonColor: '#053C7C',
                    cancelButtonColor: '#053C7C',
                    showCloseButton: true,
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-3',
                        confirmButton: 'btn btn-danger btn-sm px-4 py-2',
                        cancelButton: 'btn btn-danger btn-sm px-4 py-2 me-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // EXIT WITHOUT SAVING
                        document.getElementById('profileForm').reset();
                        profileImageChanged = false;
                        profileFormInitialData = $("#profileForm").serialize();
                        
                        var profilePicThumbnail = document.getElementById('profileImagePreview');
                        if (profilePicThumbnail) {
                            profilePicThumbnail.src = "{{$user->profile_img}}";
                        }
                        
                        $(window).off('beforeunload'); // Turn off browser dialog for this transition
                        if (typeof onConfirm === 'function') onConfirm();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // UPDATE PROFILE & LEAVE
                        var form = $("#profileForm")[0];
                        if (!$(form).valid()) {
                            // Validation failed, do not leave
                            return;
                        }
                        var formData = new FormData(form);
                        
                        Swal.fire({
                            title: 'Saving...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('profile.update') }}",
                            type: "POST",
                            data: formData,
                            dataType: "json",
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function (result) {
                                sendSuccess(result.message || "Profile updated.");
                                profileFormInitialData = $("#profileForm").serialize();
                                profileImageChanged = false;
                                $(window).off('beforeunload');
                                if (typeof onConfirm === 'function') onConfirm();
                            },
                            error: function (xhr) {
                                Swal.close();
                                let data = xhr.responseJSON;
                                if (data && data.hasOwnProperty('error')) {
                                    $.each(data.error, function (key, value) {
                                        $("#" + key + "-error").html(value).show();
                                    });
                                } else if (data && data.hasOwnProperty('message')) {
                                    actionError(xhr, data.message);
                                } else {
                                    actionError(xhr);
                                }
                            }
                        });
                    }
                });
            }

            // Wizard upload zone file inputs
            $('#passportInput').change(function (event) {
                dpPreviewZone(event, 'passportThumb', 'passportZone');
            });
            $('#regularLicInput').change(function (event) {
                dpPreviewZone(event, 'regularLicThumb', 'regularLicZone');
            });
            $('#regularBackInput').change(function (event) {
                dpPreviewZone(event, 'regularBackThumb', 'regularBackZone');
            });
            $('#internationalLicInput').change(function (event) {
                dpPreviewZone(event, 'idpFrontThumb', 'idpFrontZone');
            });
            $('#internationalLicBackInput').change(function (event) {
                dpPreviewZone(event, 'idpBackThumb', 'idpBackZone');
            });

            // Drag and drop for wizard upload zones
            $('.dp-upload-zone').on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            }).on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            }).on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                var inputId = $(this).next('input[type="file"]').attr('id');
                if (!inputId) inputId = $(this).siblings('input[type="file"]:first').attr('id');
                var files = e.originalEvent.dataTransfer.files;
                if (files.length && inputId) {
                    document.getElementById(inputId).files = files;
                    $(document.getElementById(inputId)).trigger('change');
                }
            });

            // Generic image preview function
            function previewImage(event, previewId) {
                if (event.target.files && event.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#' + previewId).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            // Preview inside wizard upload zone
            function dpPreviewZone(event, thumbId, zoneId) {
                if (event.target.files && event.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var $thumb = $('#' + thumbId);
                        var $zone  = $('#' + zoneId);
                        $thumb.attr('src', e.target.result).removeClass('d-none');
                        $zone.addClass('has-file');
                        $zone.find('.dp-upload-label').text('File selected ✓');
                        $zone.find('.dp-upload-sub').text(event.target.files[0].name);
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            // Drag and drop functionality for document uploads
            $('.document-upload').on('dragover', function (e) {
                e.preventDefault();
                $(this).addClass('border-primary');
            });

            $('.document-upload').on('dragleave', function (e) {
                e.preventDefault();
                $(this).removeClass('border-primary');
            });

            $('.document-upload').on('drop', function (e) {
                e.preventDefault();
                $(this).removeClass('border-primary');

                var files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    var inputId = $(this).find('input[type="file"]').attr('id');
                    document.getElementById(inputId).files = files;
                    $(document.getElementById(inputId)).trigger('change');
                }
            });

            $("#profileForm").validate({
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true
                    }
                },
                messages: {
                    first_name: {
                        required: "First name is required."
                    },
                    last_name: {
                        required: "Last name is required."
                    },
                    email: {
                        required: "Email is required.",
                        email: "Invalid email."
                    },
                    phone: {
                        required: "Phone number is required."
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    var btn = $(form).find('button[type="submit"]');
                    var spinner = $('<i class="bx bx-loader spinner me-2" style="display:inline-block"></i>');
                    btn.attr('disabled', true).prepend(spinner);

                    $.ajax({
                        url: "{{ route('profile.update') }}", // update this route
                        type: "POST",
                        data: new FormData(form),
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function (result) {
                             sendSuccess(result.message || "Profile updated.");
                             // Reset dirty state tracking
                             profileFormInitialData = $("#profileForm").serialize();
                             profileImageChanged = false;
                             $(window).off('beforeunload'); // Turn off browser dialog before reload
                             setTimeout(function() { location.reload(); }, 2000);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            btn.attr('disabled', false);
                            spinner.remove();
                        },
                    });
                }
            });

            // Driver Portal wizard — step navigation & submit
            var dpCurrentStep = 1;
            var dpTotalSteps  = 3;

            window.openDriverPortal = function(startStep) {
                if (isProfileFormDirty()) {
                    showConfirmNavigation(function() {
                        // If they choose to leave unsaved changes, we can either save or just continue.
                        // Given the prompt "Leave this page", it implies discarding.
                        // However, for Driver Portal, they transition steps.
                        // Let's open the portal as requested even if they chose "Leave" (meaning discard changes).
                        dpCurrentStep = startStep || 1;
                        dpGoToStep(dpCurrentStep);
                        var modal = new bootstrap.Modal(document.getElementById('driverPortalModal'));
                        modal.show();
                    });
                    return;
                }
                dpCurrentStep = startStep || 1;
                dpGoToStep(dpCurrentStep);
                var modal = new bootstrap.Modal(document.getElementById('driverPortalModal'));
                modal.show();
            };

            window.dpNext = function() {
                if (dpCurrentStep < dpTotalSteps) {
                    dpCurrentStep++;
                    dpGoToStep(dpCurrentStep);
                } else {
                    // Final step — submit
                    dpSubmit();
                }
            };

            function dpGoToStep(step) {
                // Update panels
                $('.dp-step-panel').removeClass('active');
                $('#dpPanel' + step).addClass('active');

                // Update tab indicators
                $('.dp-step').removeClass('active done');
                for (var i = 1; i < step; i++) {
                    $('#dpStep' + i + 'Tab').addClass('done');
                }
                $('#dpStep' + step + 'Tab').addClass('active');

                // Update button label
                var $btn = $('#dpNextBtn');
                if (step === dpTotalSteps) {
                    $btn.html('SUBMIT <i class="fas fa-check ms-1"></i>');
                } else {
                    $btn.html('NEXT STEP <i class="fas fa-chevron-right ms-1"></i>');
                }
            }

            function dpSubmit() {
                var form = document.getElementById('documentsForm');
                var $btn = $('#dpNextBtn');
                $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

                $.ajax({
                    url: "{{ route('profile.documents.update') }}",
                    type: "POST",
                    data: new FormData(form),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (result) {
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('driverPortalModal')).hide();
                        sendSuccess(result.message || "Documents updated. Await verification.");
                        // Reload page after short delay to refresh status cards
                        $(window).off('beforeunload'); // Turn off browser dialog before reload
                        setTimeout(function() { location.reload(); }, 1800);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.hasOwnProperty('message')) {
                            actionError(xhr, data.message);
                        } else {
                            actionError(xhr);
                        }
                        $btn.attr('disabled', false).html('SUBMIT <i class="fas fa-check ms-1"></i>');
                    },
                });
            }

            // Reset wizard state when modal closes
            $('#driverPortalModal').on('hidden.bs.modal', function() {
                dpCurrentStep = 1;
                dpGoToStep(1);
            });

            $("#passwordForm").validate({
                rules: {
                    old_password: {
                        required: true,
                        minlength: 6
                    },
                    new_password: {
                        required: true,
                        minlength: 8
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#newPassword"
                    }
                },
                messages: {
                    old_password: {
                        required: "Current password is required.",
                        minlength: "At least 6 characters."
                    },
                    new_password: {
                        required: "New password is required.",
                        minlength: "At least 8 characters."
                    },
                    confirm_password: {
                        required: "Confirm password is required.",
                        equalTo: "Passwords do not match."
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    var btn = $(form).find('button[type="submit"]');
                    var spinner = $('<i class="bx bx-loader spinner me-2" style="display:inline-block"></i>');
                    btn.attr('disabled', true).prepend(spinner);

                    $.ajax({
                        url: "{{ route('profile.change.password') }}", // update this route
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: "json",
                        success: function (result) {
                            sendSuccess(result.message || "Password updated!");
                            // Optionally, reset form
                            form.reset();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            btn.attr('disabled', false);
                            spinner.remove();
                        },
                    });
                }
            });

        });
        function showImagePreview(src)
        {
            let modal = `
    <div class="modal fade image-preview-modal" id="imgModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="${src}" alt="Image Preview" loading="lazy">
                </div>
            </div>
        </div>
    </div>`;

            $('body').append(modal);

            let m = new bootstrap.Modal(document.getElementById('imgModal'));
            m.show();

            $('#imgModal').on('hidden.bs.modal', function(){
                $('#imgModal').remove();
            });
        }
    </script>
@endsection

