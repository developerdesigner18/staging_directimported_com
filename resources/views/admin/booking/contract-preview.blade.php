@extends('admin.master')
@section('title', 'Rental Contract')

@push('style')
<style>
    @page {
        size: A4;
        margin: 0;
    }

    .contract-page {
        font-family: Arial, sans-serif;
        line-height: 1.15;
        font-size: 11px;
        background: #fff;
        padding: 10mm 15mm;
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 30px auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
        box-sizing: border-box;
        border: 1px solid #ddd;
        color: #000;
    }

    @media print {
        body { 
            background: none !important; 
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        #contractPreview {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .contract-page {
            margin: 0 !important;
            padding: 10mm 12mm !important;
            box-shadow: none !important;
            border: none !important;
            page-break-after: always !important;
            page-break-inside: avoid !important;
            height: 296mm !important;
            width: 210mm !important;
            overflow: hidden !important;
            display: block !important;
        }
        #contractPage2, #contractPage3 {
            page-break-before: always !important;
        }
        #downloadBtn, #printBtn, .breadcrumb, .page-title-box, .camera-btn {
            display: none !important;
        }
    }

    .contract-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 5px;
    }

    .contract-header h3 {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .contract-header .date {
        font-weight: bold;
        font-size: 13px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #000;
        margin-bottom: 10px;
    }

    .info-table td, .info-table th {
        border: 1px solid #000;
        padding: 3px 5px;
        vertical-align: middle;
        font-size: 12px;
    }

    .info-table input[type="text"] {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 12px;
        outline: none;
    }

    .text-center-bold {
        text-align: center;
        font-weight: bold;
    }

    .vehicle-section {
        margin-top: 5px;
    }

    .vehicle-title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .vehicle-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .vehicle-info-table td {
        padding: 3px;
        font-size: 12px;
        vertical-align: bottom;
    }
    
    .input-line {
        border: none;
        border-bottom: 1px solid #000;
        background: transparent;
        outline: none;
        padding: 0 5px;
        font-size: 12px;
    }

    .mileage-box {
        border: 1px solid #000;
        display: inline-block;
        height: 20px;
        width: 100px;
    }

    .mileage-container {
        display: flex; 
        align-items: center; 
        gap: 5px;
    }

    .condition-title {
        text-align: center;
        font-weight: bold;
        font-size: 13px;
        margin-top: 10px;
        margin-bottom: -5px;
    }

    .images-container {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .image-box {
        width: 48%;
        position: relative;
    }

    .image-upload-wrapper {
        position: relative;
        display: block;
        width: 100%;
        height: 180px;
    }

    .image-upload-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .camera-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(255,255,255,0.9);
        border: 1px solid #000;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 10;
    }

    .btn-loading {
        cursor: not-allowed;
        opacity: 0.7;
    }

</style>
@endpush

@section('main')

@php
    $totalDays = (int) $rowData->totalDays();
    $totalDays = max(1, $totalDays);

    // Car base price calculation
    $car = $rowData->car;
    $dailyRate = $car ? $car->getTieredPrice($totalDays) : 0;

    // Total car price for the duration
    $totalCarPrice = $dailyRate * $totalDays;

    // Insurance
    $insurancePrice = $rowData->insurance_price ?? 0;
    
    // Accessories manual calculation (mirroring quoteDetails.blade.php logic)
    $accessoryIds = $rowData->selected_accessories ?? [];
    $helmetPrice = 0;
    $helmetCount = 0;
    $secondHelmetPrice = 0;
    $pannierPrice = 0;
    $dryBagPrice = 0;
    $otherExtraPrice = 0;
    
    if (!is_array($accessoryIds) && is_string($accessoryIds)) {
        $accessoryIds = json_decode($accessoryIds, true) ?: [];
    }

    foreach($accessoryIds as $acc_id) {
        $acc = \App\Models\Accessories::find($acc_id);
        if (!$acc) continue;

        // Pricing logic from quoteDetails
        $accPrice = 0;
        if($totalDays > 1 && $acc->additional_day_price){
            $accPrice = $acc->price + ($acc->additional_day_price * ($totalDays - 1));
        } else {
            $accPrice = $acc->price * $totalDays;
        }

        // Helmet cap
        if(\Illuminate\Support\Str::contains(strtolower($acc->name), 'helmet') && $accPrice >= 6500){
            $accPrice = 6500;
        }

        $name = strtolower($acc->name);
        if (str_contains($name, 'helmet')) {
            if ($helmetCount == 0) {
                $helmetPrice = $accPrice;
                $helmetCount++;
            } else {
                $secondHelmetPrice = $accPrice;
            }
        } elseif (str_contains($name, 'pannier')) {
            $pannierPrice += $accPrice;
        } elseif (str_contains($name, 'dry bag')) {
            $dryBagPrice += $accPrice;
        } else {
            $otherExtraPrice += $accPrice;
        }
    }
    
    // Aggregate for breakdown
    $breakdownSubTotal = $totalCarPrice + $insurancePrice + $helmetPrice + $secondHelmetPrice + $pannierPrice + $dryBagPrice + $otherExtraPrice;
    $breakdownTax = round($breakdownSubTotal * 0.10);
    
    // Card fee 3.65% calculated on (Subtotal + Tax)
    $breakdownCardFee = round(($breakdownSubTotal + $breakdownTax) * 0.0365); 
    
    $breakdownTotal = $breakdownSubTotal + $breakdownTax + $breakdownCardFee;

    // Age calculation
    $age = null;
    if ($rowData->user?->date_of_birth) {
        $ageDiff = date_diff(date_create($rowData->user->date_of_birth), date_create(now()));
        $age     = $ageDiff->y;
    }
@endphp

<div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
    <h4 class="mb-sm-0">Booking Management</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Booking</a></li>
            <li class="breadcrumb-item active">Bokking Contract</li>
        </ol>
    </div>
</div>

<div class="container-fluid py-4 bg-light">
    <div class="row justify-content-center">
        <div class="col-12">

            <div id="contractPreview">
                <div id="contractPage1" class="contract-page">
                    
                    <div class="contract-header">
                        <h3>CAR RENTAL AGREEMENT & CONDITION REPORT</h3>
                        <div class="date">{{ now()->format('d F Y') }}</div>
                    </div>

                    <table class="info-table">
                        <tr>
                            <td colspan="6" class="text-center-bold" style="border-bottom: none;">Renter</td>
                        </tr>
                        <tr>
                            <td style="width: 15%;">Full Name</td>
                            <td style="width: 35%;">{{ strtoupper($rowData->first_name . ' ' . $rowData->last_name) }}</td>
                            <td style="width: 10%;">D.O.B.</td>
                            <td style="width: 20%;">{{ $rowData->user?->date_of_birth ? \Carbon\Carbon::parse($rowData->user->date_of_birth)->format('d-M-y') : '' }}</td>
                            <td style="width: 10%;">Age</td>
                            <td style="width: 10%;">{{ $age }}</td>
                        </tr>
                        <tr>
                            <td>Address (while in Japan):</td>
                            <td colspan="5"><input type="text" placeholder=""></td>
                        </tr>
                        <tr>
                            <td>Address (Home country):</td>
                            <td colspan="5"><input type="text" value="{{ ($rowData->user?->address ?? '') . ($rowData->user?->country ? ', ' . $rowData->user->country : '') }}"></td>
                        </tr>
                        <tr>
                            <td>TEL No. (Home country)</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user->mobile ?? '' }}"></td>
                            <td colspan="1">Contact No#. (in Japan)</td>
                            <td colspan="2"><input type="text" placeholder=""></td>
                        </tr>
                        <tr>
                            <td colspan="2">Contact email (Messenger/What'sApp )</td>
                            <td colspan="4"><input type="text" value="{{ $rowData->user?->email ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>License No.</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user->userDetail->regular_lic_number ?? '' }}"></td>
                            <td>Issuing country</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user?->country ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>IDP No.</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user->userDetail->idp_number ?? '' }}"></td>
                            <td>issuing country</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user?->country ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>Passport No.</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user->userDetail->passport_number ?? '' }}"></td>
                            <td>Issuing country</td>
                            <td colspan="2"><input type="text" value="{{ $rowData->user?->country ?? '' }}"></td>
                        </tr>
                        <tr>
                            <td>Credit Card No.</td>
                            <td colspan="2"><input type="text"></td>
                            <td>comment</td>
                            <td colspan="2"><input type="text"></td>
                        </tr>
                        <tr>
                            <td class="text-center-bold border-0" style="padding: 6px;">Please confirm</td>
                            <td colspan="5" style="border-left: none;">
                                <label style="margin-right: 40px; margin-bottom:0; font-weight: normal; font-size: 11px;">
                                    <input type="checkbox" style="vertical-align: middle;"> Customer has read and understood the Terms and Conditions
                                </label>
                                <label style="margin-bottom:0; font-weight: normal; font-size: 11px;">
                                    <input type="checkbox" style="vertical-align: middle;"> Customer has watched information video
                                </label>
                            </td>
                        </tr>
                    </table>

                    <div class="vehicle-section" style="margin-top: 15px; margin-bottom: 20px;">
                        <div class="vehicle-title" style="margin-bottom: 5px;">Rented Vehicle</div>
                        
                        <div style="display: flex; align-items: flex-end; margin-bottom: 10px; font-size: 12px;">
                            <div style="width: 18%;"><span style="text-decoration: underline;">Vehicle Name</span></div>
                            <div style="flex-grow: 1; border-bottom: 1px solid #000; text-align: center; padding-bottom: 2px;">
                                {{ $rowData->car->name ?? '' }} 
                                @if(isset($rowData->car->number_plate))
                                {{ $rowData->car->number_plate }}
                                @endif
                                @if(isset($rowData->car->id))
                                ID#{{ $rowData->car->id }}
                                @endif
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 15px; font-size: 12px;">
                            <!-- Left: Mileage out -->
                            <div style="display: flex; flex-direction: column; width: 40%;">
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 80px;"><span style="text-decoration: underline;">Mileage out</span></div>
                                    <div style="display: flex; align-items: center;">
                                        <input type="text" style="border: 1px solid #000; width: 130px; height: 22px; outline: none; padding: 0 4px; box-sizing: border-box;"> 
                                        <span style="margin-left: 5px; font-size: 11px;">km</span>
                                    </div>
                                </div>
                                <div style="font-size: 11px; margin-top: 4px;">1,250klm per 7 days alowance 15¥ per klm after</div>
                            </div>
                            
                            <!-- Middle: Mileage in -->
                            <div style="display: flex; align-items: flex-start; width: 33%;">
                                <div style="width: 70px; margin-top: 2px;">Mileage in</div>
                                <div style="display: flex; align-items: center;">
                                    <div style="border: 1px solid #000; width: 130px; display: flex; flex-direction: column;">
                                        <div style="border-bottom: 1px solid #000; height: 22px;">
                                            <input type="text" style="width: 100%; height: 100%; border: none; background: transparent; outline: none; padding: 0 4px; box-sizing: border-box;">
                                        </div>
                                        <div style="height: 22px;">
                                            <input type="text" style="width: 100%; height: 100%; border: none; background: transparent; outline: none; padding: 0 4px; box-sizing: border-box;">
                                        </div>
                                    </div>
                                    <div style="display: flex; flex-direction: column; justify-content: space-around; height: 45px; margin-left: 5px; font-size: 11px;">
                                        <div style="height: 22px; display: flex; align-items: center;">km</div>
                                        <div style="height: 22px; display: flex; align-items: center;">km</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Total KM -->
                            <div style="width: 25%; display: flex; justify-content: flex-end;">
                                <div style="border: 1px solid #000; width: 100%; display: flex; flex-direction: column;">
                                    <div style="border-bottom: 1px solid #000; padding: 0 5px; height: 22px; box-sizing: border-box; font-size: 11px; display: flex; align-items: center;">Total KM **</div>
                                    <div style="height: 22px;">
                                        <input type="text" style="width: 100%; height: 100%; border: none; background: transparent; outline: none; padding: 0 5px; box-sizing: border-box;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-end; margin-bottom: 5px; font-size: 12px;">
                            <div style="width: 18%;"><span style="text-decoration: underline;">Rental period</span></div>
                            <div style="flex-grow: 1; text-align: center;">
                                Total Days &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="text" style="border: none; border-bottom: 1px solid #000; width: 80px; text-align: center; outline: none;" value="{{ $totalDays }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        
                        <div style="font-size: 11px; text-align: center; padding-left: 10%;">
                            <div style="margin-bottom: 8px;">
                                Start Date : &nbsp;
                                <input type="text" style="border: none; border-bottom: 1px solid #000; width: 130px; text-align: center; outline: none;" value="{{ \Carbon\Carbon::parse($rowData->start_date)->format('d-F-Y') }}">
                                &nbsp;&nbsp;&nbsp;&nbsp; ~ &nbsp;&nbsp;&nbsp;&nbsp;
                                Return Date: &nbsp;
                                <input type="text" style="border: none; border-bottom: 1px solid #000; width: 130px; text-align: center; outline: none;" value="{{ \Carbon\Carbon::parse($rowData->end_date)->format('d-F-Y') }}">
                            </div>
                            <div>
                                Pickup Time : &nbsp;
                                <input type="text" style="border: none; border-bottom: 1px solid #000; width: 100px; text-align: center; outline: none;" value="{{ \Carbon\Carbon::parse($rowData->start_time)->format('h:i A') }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Drop-off Time: &nbsp;
                                <input type="text" style="border: none; border-bottom: 1px solid #000; width: 100px; text-align: center; outline: none;" value="{{ \Carbon\Carbon::parse($rowData->end_time)->format('h:i A') }}">
                            </div>
                        </div>
                    </div>

                    <div class="condition-title">Current condition of vehicle before rental</div>

                    <div class="images-container">
                        <div class="image-box">
                            <div class="image-upload-wrapper">
                                <img id="leftImagePreview" src="{{ asset('assets/landing/images/car-details/right.jpg')}}" alt="Left">
                                <button class="camera-btn" id="leftImageButton" data-html2canvas-ignore="true">
                                    <i class="ri-camera-fill"></i>
                                </button>
                                <input type="file" accept="image/*" id="leftImageInput" style="display:none;">
                            </div>
                        </div>
                        <div class="image-box">
                            <div class="image-upload-wrapper">
                                <img id="rightImagePreview" src="{{ asset('assets/landing/images/car-details/left.png')}}" alt="Right">
                                <button class="camera-btn" id="rightImageButton" data-html2canvas-ignore="true">
                                    <i class="ri-camera-fill"></i>
                                </button>
                                <input type="file" accept="image/*" id="rightImageInput" style="display:none;">
                            </div>
                        </div>
                    </div>

                    <!-- SIG AND PRE-CHECK SECTION -->
                    <div style="display: flex; font-size: 10px; margin-top: 5px; margin-bottom: 3px; border-bottom: 1px solid #000; padding-bottom: 2px;">
                        <div style="flex: 1;">Signed out: Person in Charge : <input type="text" style="border: none; width: 170px; outline: none; background: transparent;"></div>
                        <div style="flex: 1;">Signed in: Person in Charge : <input type="text" style="border: none; width: 170px; outline: none; background: transparent;"></div>
                    </div>
                    
                    <div style="font-weight: bold; font-size: 12px; margin-top: 3px;">Vehicle Condition Pre-Check</div>
                    <div style="font-style: italic; font-size: 10px; text-align: center; margin-bottom: 8px; line-height: 1.1;">
                        By signing below, I acknowledge that I have inspected the vehicle and agree it is in good working order with no damage other than what is noted on the separate "Damage Inspection Diagram."
                    </div>
                    
                    <div style="display: flex; font-size: 10px; margin-bottom: 10px;">
                        <div style="width: 50%; display: flex; align-items: flex-end;">
                            Renter signature 
                            <input type="text" style="border: none; border-bottom: 1px solid #000; flex-grow: 1; margin-left: 20px; outline: none; background: transparent;">
                        </div>
                        <div style="width: 50%; display: flex; align-items: flex-end; padding-left: 20px;">
                            DATE: 
                            <input type="text" style="border: none; border-bottom: 1px solid #000; flex-grow: 1; margin-left: 10px; outline: none; background: transparent;">
                        </div>
                    </div>

                    <!-- TABLES SECTION -->
                    <div style="display: flex; align-items: flex-start; gap: 8px; font-size: 10px;">
                        <!-- LEFT TABLE (Rental Costs) -->
                        <div style="flex: 0 0 52%;">
                            <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; text-align: center;">
                                <tr>
                                    <th colspan="2" style="border: 1px solid #000; border-right: 2px solid #000; font-size: 11px; font-weight: bold; padding: 1px;">Rental Costs</th>
                                    <th style="border: 2px solid #000; font-size: 11px; font-weight: bold; padding: 1px; width: 30%;">Totals</th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid #000; padding: 1px; font-weight: bold; font-size: 11px;">Car Rate</th>
                                    <th style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px; font-weight: bold; font-size: 9px; width: 15%;">Per/Day</th>
                                    <td style="border: 1px solid #000; padding: 1px; background: linear-gradient(to top right, transparent 49%, black 49%, black 51%, transparent 51%);"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Daily Rate</span>
                                            <span>¥{{ number_format($dailyRate, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $totalDays }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($totalCarPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px; text-align: center; font-size: 10px;">Discount</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥0</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Optional Insurance</span>
                                            <span>¥{{ number_format($insurancePrice > 0 ? $insurancePrice / $totalDays : 34, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $insurancePrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($insurancePrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid #000; padding: 1px; font-weight: bold; font-size: 11px;">Rental extras</th>
                                    <th style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px; font-weight: bold; font-size: 9px;">Per/day</th>
                                    <td style="border: 1px solid #000; padding: 1px; background: linear-gradient(to top right, transparent 49%, black 49%, black 51%, transparent 51%);"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Helmet</span>
                                            <span>¥{{ number_format($helmetPrice > 0 ? $helmetPrice / $totalDays : 1000, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $helmetPrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($helmetPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>2nd Helmet</span>
                                            <span>¥{{ number_format($secondHelmetPrice > 0 ? $secondHelmetPrice / $totalDays : 1000, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $secondHelmetPrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($secondHelmetPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Side pannier</span>
                                            <span>¥{{ number_format($pannierPrice > 0 ? $pannierPrice / $totalDays : 0, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $pannierPrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($pannierPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Dry Bag</span>
                                            <span>¥{{ number_format($dryBagPrice > 0 ? $dryBagPrice / $totalDays : 0, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $dryBagPrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($dryBagPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">
                                        <div style="display: flex; justify-content: space-between; padding: 0 5px;">
                                            <span>Other</span>
                                            <span>¥{{ number_format($otherExtraPrice > 0 ? $otherExtraPrice / $totalDays : 0, 0) }}</span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">{{ $otherExtraPrice > 0 ? $totalDays : 0 }}</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($otherExtraPrice, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">Tank Bag</td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px; font-size: 10px;">Not Allowed</td>
                                    <td style="border: 1px solid #000; padding: 1px; font-size: 10px;">N/A</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px; text-align: right; padding-right: 5px; font-size: 10px;">Sub Total</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($breakdownSubTotal, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; text-align: left; padding-left: 5px; font-size: 10px;">Sales Tax</td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">10%</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($breakdownTax, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; text-align: left; padding-left: 5px; font-size: 10px;">Card fee %</td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;">3.65%</td>
                                    <td style="border: 1px solid #000; padding: 1px;">¥{{ number_format($breakdownCardFee, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #000; padding: 1px; text-align: left; padding-left: 5px; font-size: 10px; height: 16px;">Tour Name</td>
                                    <td style="border: 1px solid #000; border-right: 2px solid #000; padding: 1px;"></td>
                                    <td style="border: 1px solid #000; padding: 1px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 1px solid #000; border-right: 2px solid #000; padding: 2px; text-align: left; padding-left: 10px; font-weight: bold; font-size: 11px;">Total (including tax)</td>
                                    <td style="border: 1px solid #000; padding: 2px; font-weight: bold; font-size: 12px;">¥{{ number_format($breakdownTotal, 0) }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- MIDDLE & RIGHT AREA -->
                        <div style="flex: 0 0 46%; display: flex; flex-direction: column; gap: 10px;">
                            
                            <div style="display: flex; gap: 10px;">
                                <!-- Deposit/Payment Table -->
                                <div style="flex: 1;">
                                    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; text-align: center;">
                                        <tr>
                                            <th colspan="2" style="border: 1px solid #000; font-size: 12px; font-weight: normal; padding: 2px;">Deposit/Payment</th>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px; text-align: left; padding-left: 4px; width: 40%;">Deposit</td>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 9px;">¥0</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px; text-align: left; padding-left: 4px;">Balance</td>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 9px;">¥{{ number_format($breakdownTotal, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 9px; text-align: left; padding-left: 4px; color: #555;">Paid on day</td>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 9px;">¥0</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 3px; font-size: 12px;">Total</td>
                                            <td style="border: 1px solid #000; padding: 3px; font-weight: bold; font-size: 11px;">¥{{ number_format($breakdownTotal, 0) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Check Table -->
                                <div style="flex: 0 0 45%;">
                                    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000;">
                                        <tr>
                                            <th style="border: 1px solid #000; font-size: 12px; font-weight: normal; padding: 2px; text-align: left; padding-left: 4px;">Check</th>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px;"><input type="checkbox" style="margin-right: 4px; margin-left: 2px;"> IDP</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px;"><input type="checkbox" style="margin-right: 4px; margin-left: 2px;"> Home License</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px;"><input type="checkbox" style="margin-right: 4px; margin-left: 2px;"> Credit Card</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 1px; font-size: 10px;"><input type="checkbox" style="margin-right: 4px; margin-left: 2px;"> Passport</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Signatures and Address -->
                            <div style="margin-top: 5px; margin-left: 20px;">
                                <div style="display: flex; align-items: flex-end; margin-bottom: 20px;">
                                    <span style="font-size: 10px; width: 130px;">Signed (person in charge)</span>
                                    <input type="text" style="border: none; border-bottom: 1px solid #000; flex-grow: 1; outline: none; background: transparent;">
                                </div>
                                <div style="display: flex; align-items: flex-end; margin-bottom: 15px;">
                                    <span style="font-size: 10px; width: 130px;">Signed on return</span>
                                    <input type="text" style="border: none; border-bottom: 1px solid #000; flex-grow: 1; outline: none; background: transparent;">
                                </div>
                                
                                <div style="text-align: center; font-size: 10px; line-height: 1.5; margin-top: 15px;">
                                    <div style="font-weight: bold; font-size: 11px;">Car Rental Japan <span style="font-weight: normal;">by EZ Moto Kansai</span></div>
                                    <div>4-10 -B1 Senrioka Shimo, Suita Shi, Osaka</div>
                                    <div><span style="text-decoration: underline;">TEL:06 4864 2081</span></div>
                                    <div><span style="text-decoration: underline;">E-Mail : rental@carrentaljapan.com</span></div>
                                </div>
                            </div>
                        </div> <!-- Closes MIDDLE & RIGHT AREA -->

                    </div> <!-- Closes TABLES SECTION -->

                </div> <!-- Closes contractPage1 -->

                <!-- PAGE 2 -->
                <div id="contractPage2" class="contract-page">
                    <div class="text-center-bold" style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin-bottom: 15px;">
                        EZMOTO KANSAI LLC – VEHICLE RENTAL<br>AGREEMENT & ACCEPTANCE FORM
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px; font-size: 13px; font-weight: bold;">
                        <div style="display: flex; align-items: flex-end; width: 60%;">
                            <span style="margin-right: 15px;">Name</span>
                            <span style="font-weight: normal; text-transform: uppercase;">{{ $rowData->first_name . ' ' . $rowData->last_name }}</span>
                        </div>
                        <div style="display: flex; align-items: flex-end;">
                            <span style="margin-right: 15px;">Date</span>
                            <span style="font-weight: normal;">{{ now()->format('d-M-Y') }}</span>
                        </div>
                    </div>
                    
                    <div style="border-top: 2px solid #000; border-bottom: 2px solid #000; margin-top: 5px; padding: 5px 0; font-size: 13px; font-weight: bold; margin-bottom: 5px; display: flex;">
                        <div style="width: 150px;">Car model</div>
                        <div style="font-weight: normal; flex-grow: 1; text-align: center;">
                            {{ $rowData->car->name ?? '' }} 
                            @if(isset($rowData->car->number_plate))
                            {{ $rowData->car->number_plate }}
                            @endif
                            @if(isset($rowData->car->id))
                            ID#{{ $rowData->car->id }}
                            @endif
                        </div>
                    </div>

                    <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 15px;">
                        This form is to confirm the important points the customer read in our terms and conditions
                    </div>

                    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; font-size: 12px; margin-bottom: 20px;">
                        <tr>
                            <th style="border: 1px solid #000; padding: 10px; text-align: left; width: 25%; font-weight: normal;">Section</th>
                            <th style="border: 1px solid #000; padding: 10px; text-align: left; font-weight: normal;">Essential Term Summary</th>
                            <th style="border: 1px solid #000; padding: 10px; width: 8%; text-align: center; font-weight: normal;">Initials</th>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">1. NON-RETURN</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I understand that if the car is not returned within 24 hours of the scheduled time and I have made no contact with EZMOTO, the vehicle will be reported as STOLEN to the Japanese police.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">2. COMMERCIAL USE</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I confirm this vehicle is for personal use only. It will NOT be used for operated tours, deliveries, or any commercial activity unless a separate contract has been issued.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">3. DAMAGE & ERI</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I understand my liability for damage is capped at ¥300,000 – ¥500,000 (if ERI/CDW is selected). This includes coverage for "dropping" the car. Displayed on the car listing page.<br><div style="text-align: right; margin-top: 10px;">ERI/CDW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Selected [ &nbsp;&nbsp;&nbsp;&nbsp; ] Declined [ &nbsp;&nbsp;&nbsp;&nbsp; ]</div></td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">4. AUTHORIZED RIDER</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I confirm that only the rider(s) named on this agreement are permitted to operate the vehicle. Allowing unauthorized riders voids all insurance.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">5. FINES & TICKETS</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I am responsible for all parking and traffic violations. Unpaid fines returned to EZMOTO will incur the fine cost plus an administrative processing fee.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">6. LATE RETURNS</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I agree to notify EZMOTO of any delays. Unarranged late returns are billed at ¥3,750 per hour.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">7. SAFETY AND SIGNS</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">I Have watched the Safety and signs Videos displayed on Car rental Japans website and have studied the signs page.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">8. INDEMNIFICATION</td>
                            <td style="border: 1px solid #000; padding: 12px 10px; text-align: center;">The Customer agrees to indemnify EZMOTO against any personal injury or third-party claims arising from the operation of the vehicle.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; padding: 12px 10px; vertical-align: top;">9. PROHIBITED USE</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;">Use of the car on roads that do not appear on any map for roaduse, beaches, or racing tracks is strictly prohibited.</td>
                            <td style="border: 1px solid #000; padding: 12px 10px;"></td>
                        </tr>
                    </table>

                <div style="margin-top:20px; font-size:13px; font-family: sans-serif;">
                    <div style="margin-bottom:20px; text-align: center; line-height: 1.5;">
                        <strong>★Acknowledgment: The Customer named above has read, fully understands, and agrees to abide by the<br>
                        terms and conditions set forth in this agreement.</strong>
                    </div>

                    <div style="margin-top:30px; display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; align-items: flex-end; justify-content: space-between;">
                            <div style="display: flex; align-items: flex-end; width: 65%;">
                                <span style="font-weight: bold; white-space: nowrap;">Customer Signature:</span>
                                <div style="border-bottom: 1px solid #000; flex-grow: 1; margin-left: 10px; height: 18px;"></div>
                            </div>
                            <div style="display: flex; align-items: flex-end; width: 30%;">
                                <span style="font-weight: bold; white-space: nowrap;">Date:</span>
                                <div style="border-bottom: 1px solid #000; flex-grow: 1; margin-left: 10px; height: 18px;"></div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-end; justify-content: space-between;">
                            <div style="display: flex; align-items: flex-end; width: 65%;">
                                <span style="font-weight: bold; white-space: nowrap;">Staff Signature:</span>
                                <div style="border-bottom: 1px solid #000; flex-grow: 1; margin-left: 10px; height: 18px;"></div>
                            </div>
                            <div style="display: flex; align-items: flex-end; width: 30%;">
                                <span style="font-weight: bold; white-space: nowrap;">Time:</span>
                                <div style="border-bottom: 1px solid #000; flex-grow: 1; margin-left: 10px; height: 18px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE 3 -->
            <div id="contractPage3" class="contract-page" style="margin-top: 50px; border-top: 1px dashed #ccc; padding-top: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; font-family: 'Times New Roman', serif;">
                    <div style="width: 250px; border: 1px solid #ccc; padding: 10px; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('assets/logo/main.png') }}" style="width: 100%; display: block;" alt="Car Rental Japan">
                    </div>
                    <div style="text-align: right; font-size: 13px; line-height: 1.4;">
                        <strong>Car Rental Japan Ezmoto Kansai</strong> &nbsp; 4-10 B1<br>
                        Senrioka Shimo, Suita Shi, Osaka, 565-0813<br>
                        <span style="font-family: Arial;">☎</span> 080-3770-0740<br>
                        <span style="font-family: Arial;">☎</span> 06-4864-2081<br>
                        ✉ Rental@carrentaljapan.com<br>
                        <a href="http://www.carrentaljapan.com" style="color: blue; text-decoration: underline;">www.carrentaljapan.com</a>
                    </div>
                </div>

                <div style="font-size: 13px; line-height: 1.5; font-family: 'Times New Roman', serif;">
                    <p style="margin-bottom: 15px;"><strong>Welcome!</strong> Thank you for renting with us. We hope you have a fantastic time! To help you enjoy your trip, please review the important information below.</p>

                    <p style="margin-bottom: 15px;"><strong>EMERGENCY CONTACTS</strong> Shop: 06 4864 2081 | <strong>Luke (Out of Hours):</strong> 080 3770 0740<br>
                    <strong>Police:</strong> 110 &nbsp; <strong>Fire/Ambulance:</strong> 119 <em>(Please call us for breakdowns, police stops, or issues requiring immediate assistance.)</em></p>

                    <p style="margin-bottom: 5px;"><strong>PROCEDURES Breakdowns:</strong> Call us immediately and send a pin on Google Maps. We will arrange recovery ASAP.</p>
                    <p style="margin-bottom: 5px;"><strong>Accidents:</strong></p>
                    <ol style="margin-top: 5px; margin-bottom: 15px; padding-left: 20px;">
                        <li>Administer first aid to anyone injured.</li>
                        <li>Move vehicles to a safe place if possible.</li>
                        <li>Call <strong>Police (110)</strong> and <strong>Ambulance (119)</strong> immediately.</li>
                        <li>Call us and send a map pin. <strong>"Never admit liability"</strong>. Take photos of the scene and gather evidence to ensure a fair determination of fault.</li>
                    </ol>

                    <p style="margin-bottom: 15px;"><strong>Police Stops:</strong> Show your IDP, Home License, Passport, Rental Agreement, and Vehicle Documents. Call us if you need help.</p>

                    <p style="margin-bottom: 15px;"><strong>Traffic Fines:</strong> If you get a ticket, call us for instructions on where to go. <strong>All parking/speeding fines MUST be paid locally by the renter.</strong></p>

                    <hr style="border: 0; border-top: 1px solid #ccc; margin: 15px 0;">

                    <div style="display: flex; justify-content: space-between; gap: 20px;">
                        <div style="width: 48%;">
                            <p style="margin-bottom: 15px; font-weight: bold;">USEFUL TRANSLATIONS (Point to these if you need help)</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"I am not the owner, I am renting the car from Car Rental Japan."</strong> 私 は持ち主ではなくてバイクレンタルジャパンから借りています。</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"Their contact details are: 4/10 Senrioka Shimo, Suita Shi, Osaka. ☎ 0648642081."</strong> 本社の連絡先は：565 0813 大阪府吹田市千里丘下 4/10 ☎ 0648642081</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"Please call the staff at the shop and they will help translate for me."</strong> レンタルの店に通訳がいるので電話してください。</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"I've come to pay a speeding/parking fine, where do I go to do that?"</strong> スピード/パーキング違反罰</p>
                        </div>
                        <div style="width: 48%;">
                            <p style="margin-bottom: 15px; padding-top: 25px;">金を支払いに来てましたが。どこに行ったらいいですか？</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"I am injured, please can you call an ambulance."</strong> 怪我をしていると思いますので救急車を呼んでください。</p>
                            
                            <p style="margin-bottom: 15px;"><strong>"I'm sorry, I didn't know it was illegal."</strong> 大変申し訳ございませんが、違反だと知りませんでした。</p>
                            
                            <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
                            
                            <p style="font-style: italic; font-weight: bold; line-height: 1.6;">We understand this sounds a bit scary, but we have to prepare for all rare situations. Anyway, it's time to ride. Bon Voyage!<br>Ride Safely and Stay Aware.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="text-center mt-3">
                <button id="downloadBtn" class="btn btn-primary btn-lg px-5">
                    <i class="ri-file-pdf-line me-1"></i> Download PDF
                </button>
                <button id="printBtn" class="btn btn-secondary btn-lg px-5 ms-3">
                    <i class="ri-printer-line me-1"></i> Print
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    const leftInput = document.getElementById('leftImageInput');
    const rightInput = document.getElementById('rightImageInput');
    const leftPreview = document.getElementById('leftImagePreview');
    const rightPreview = document.getElementById('rightImagePreview');
    const downloadBtn = document.getElementById('downloadBtn');
    const printBtn = document.getElementById('printBtn');

    function compressImage(file, maxWidth = 800, quality = 0.8) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth) {
                        height = (height * maxWidth) / width;
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(
                        (blob) => {
                            const compressedUrl = URL.createObjectURL(blob);
                            resolve(compressedUrl);
                        },
                        'image/jpeg',
                        quality
                    );
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    async function previewImage(input, previewEl) {
        const file = input.files[0];
        const defaultSrc = previewEl.getAttribute('data-default') || previewEl.src;

        if (!file) {
            previewEl.src = defaultSrc;
            checkImages();
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            alert("Invalid image type! Please upload JPG, JPEG, PNG, or WEBP only.");
            input.value = '';
            previewEl.src = defaultSrc;
            checkImages();
            return;
        }

        try {
            const compressedUrl = await compressImage(file, 800, 0.8);
            previewEl.src = compressedUrl;
        } catch (error) {
            console.error('Image compression failed:', error);
            const reader = new FileReader();
            reader.onload = e => {
                previewEl.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        checkImages();
    }

    function checkImages() {
        if (
            leftInput.files.length &&
            rightInput.files.length &&
            allowedTypes.includes(leftInput.files[0].type) &&
            allowedTypes.includes(rightInput.files[0].type)
        ) {
            downloadBtn.disabled = false;
            downloadBtn.style.opacity = "1";
            downloadBtn.style.cursor = "pointer";
        } else {
            downloadBtn.disabled = true;
            downloadBtn.style.opacity = "0.6";
            downloadBtn.style.cursor = "not-allowed";
        }
    }

    document.getElementById('leftImageButton').addEventListener('click', () => leftInput.click());
    document.getElementById('rightImageButton').addEventListener('click', () => rightInput.click());
    leftInput.addEventListener('change', () => previewImage(leftInput, leftPreview));
    rightInput.addEventListener('change', () => previewImage(rightInput, rightPreview));

    printBtn.addEventListener('click', () => {
        window.print();
    });

    checkImages();

    downloadBtn.addEventListener('click', async () => {
        if (downloadBtn.disabled) {
            alert("Please upload both left and right images before downloading PDF.");
            return;
        }

        const originalContent = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i> Generating...';
        downloadBtn.classList.add('btn-loading');
        downloadBtn.disabled = true;

        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4',
                compress: true
            });

            const pages = ['contractPage1', 'contractPage2', 'contractPage3'];

            for (let i = 0; i < pages.length; i++) {
                const element = document.getElementById(pages[i]);
                
                // Ensure all images are loaded for this page
                const imgs = element.getElementsByTagName('img');
                await Promise.all(Array.from(imgs).map(img => {
                    if (img.complete) return Promise.resolve();
                    return new Promise(resolve => { img.onload = resolve; img.onerror = resolve; });
                }));

                // Fix for html2canvas duplication glitch when elements are out of the viewport
                window.scrollTo(0, 0);
                
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    scrollY: 0,
                    windowWidth: document.documentElement.scrollWidth,
                    windowHeight: document.documentElement.scrollHeight
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);
                const pageWidth = 210;
                
                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, (canvas.height * pageWidth) / canvas.width, undefined, 'FAST');
            }

            pdf.save('Rental_Contract_{{ $rowData->booking_id }}.pdf');
        } catch (error) {
            console.error('PDF generation failed:', error);
            alert('Failed to generate PDF. Please try again.');
        } finally {
            downloadBtn.innerHTML = originalContent;
            downloadBtn.classList.remove('btn-loading');
            downloadBtn.disabled = false;
        }
    });

</script>
@endsection
