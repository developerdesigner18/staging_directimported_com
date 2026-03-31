@component('mail::message')
# Booking Quote Details

@foreach($bookings as $key => $booking)
@php
    $bikeData = \App\Models\Bike::find($booking['bike_id']);
    $accessories = $booking['selected_accessories'] ?? [];
    $totalDays = totalBookingDays($booking['start_date'], $booking['end_date'], $booking['end_time']);
    $pricePerDay = $bikeData->getTieredPrice($totalDays);
    $price = $pricePerDay * $totalDays;
    $insurance_price = $booking['insurance'] ? $bikeData->insurance_price * $totalDays : 0;

    $subtotal = $price + $insurance_price;
@endphp

<table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; border: 1px solid #000;margin-bottom: 20px;margin-top: 5px;">
<tr style="border-bottom: 1px solid #000;">
    <td colspan="2"><h4 style="text-align: center; font-weight: bold;">BOOKING QUOTE #{{ $key+1 }}</h4></td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Full Name <span>:</span></td>
    <td>{{ $booking['first_name'] }} {{ $booking['last_name'] ?? '' }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Email <span>:</span></td>
    <td>{{ $booking['email'] }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Mobile <span>:</span></td>
    <td>{{ $booking['mobile'] }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Bike Name <span>:</span></td>
    <td>{{ $bikeData->name }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Total Days <span>:</span></td>
    <td>{{ $totalDays }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Start Date <span>:</span></td>
    <td>{{ date('d - M- Y', strtotime($booking['start_date'])) }} Pickup {{ date('h:i A', strtotime($booking['start_time'])) }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">End Date <span>:</span></td>
    <td>{{ date('d - M- Y', strtotime($booking['end_date'])) }} Drop off {{ date('h:i A', strtotime($booking['end_time'])) }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Total Bike Price <span>:</span></td>
    <td>¥{{ $price }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td colspan="2" style="font-weight: bold;"><h5>Accessories & Insurance</h5></td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Insurance <span>:</span></td>
    <td>{{ ($insurance_price>0) ? $insurance_price : 'NA' }}</td>
</tr>

@if($accessories)
@foreach($accessories as $acc_id)
@php
    $accData = \App\Models\Accessories::find($acc_id);
    $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
    $subtotal += $accPrice;
@endphp
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">{{ $accData->name }} <span>:</span></td>
    <td>¥{{ $accPrice }}</td>
</tr>
@endforeach
@endif

@php
    // Calculate TAX and Card Fee with consistent rounding for Yen
    $tax = round($subtotal * 0.10);
    // Fee is 3.65% of (Subtotal + Tax)
    $cardFee = round(($subtotal + $tax) * 0.0365);
    $totalPrice = $subtotal + $tax + $cardFee;
@endphp

<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">TAX 10% <span>:</span></td>
    <td>¥{{ number_format($tax, 0) }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;">Card Fee 3.65% <span>:</span></td>
    <td>¥{{ number_format($cardFee, 0) }}</td>
</tr>

<tr style="font-weight: bold;border-bottom: 1px solid #000;">
    <td style="display: flex;justify-content: space-between;"><h6>Total Price</h6> <span>:</span></td>
    <td>¥{{ number_format($totalPrice, 0) }}</td>
</tr>
</table>
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
