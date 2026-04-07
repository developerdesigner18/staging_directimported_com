<input type="hidden" name="user_id" value="{{ $user_id??'' }}">
<input type="hidden" name="first_name" value="{{ $request->first_name }}">
<input type="hidden" name="last_name" value="{{ $request->last_name }}">
<input type="hidden" name="email" value="{{ $request->email }}">
<input type="hidden" name="mobile" value="{{ $request->mobile }}">
<input type="hidden" name="start_date" value="{{ $request->start_date }}">
<input type="hidden" name="end_date" value="{{ $request->end_date }}">
<input type="hidden" name="start_time" value="{{ $request->start_time }}">
<input type="hidden" name="end_time" value="{{ $request->end_time }}">
<input type="hidden" name="location" value="{{ $request->location }}">
<input type="hidden" name="comment" value="{{ $request->comment }}">
<input type="hidden" name="acc_bike_id" value="{{ json_encode($request->acc_bike_id)??'' }}">
@foreach($bookings as $key => $booking)
    @php
        $bikeData = \App\Models\Bike::find($booking['bike_id']);
        $accessories = $booking['selected_accessories'] ?? [];
        $totalDays = totalBookingDays($booking['start_date'], $booking['end_date'], $booking['end_time']);

        $pricePerDay = $bikeData->getTieredPrice($totalDays);
        $price = $pricePerDay * $totalDays;
        $insurance_price = $booking['insurance'] ? $bikeData->insurance_price * ($totalDays): 0;

        $subtotal = $price + $insurance_price;
    @endphp
    <input type="hidden" name="booking_id[]" value="{{ $booking['booking_id'] }}">
    <input type="hidden" name="bike_id[]" value="{{ $booking['bike_id'] }}">
    <input type="hidden" name="bike_name[]" value="{{ $bikeData->name }}">
    <input type="hidden" name="included_accessories[]" value="{{ json_encode($bikeData->free_accessory)??'' }}">
    <input type="hidden" name="price[]" value="{{ $price }}">
    <input type="hidden" name="insurance_price[]" value="{{ ($insurance_price>0) ? $insurance_price : 0 }}">
    <input type="hidden" name="insurance[]" value="{{ $booking['insurance']??0 }}">
    <input type="hidden" name="total_days[]" value="{{ $totalDays }}">
    <table border="1" cellpadding="8" cellspacing="0" width="100%"
           style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; border: 1px solid #000;margin-bottom: 20px;margin-top: 5px;">
        <tr style="border-bottom: 2px solid #000;border-top: 2px solid #000;">
            <td colspan="2" style="text-align: center;" class="h4 fw-bold">BOOKING QUOTE #{{ $key+1 }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Full Name <span class="fw-bold">:</span></td>
            <td>{{ $booking['first_name'] }} {{ $booking['last_name'] ?? '' }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Email <span class="fw-bold">:</span></td>
            <td>{{ $booking['email'] }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Mobile <span class="fw-bold">:</span></td>
            <td>{{ $booking['mobile'] }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Bike Name <span class="fw-bold">:</span></td>
            <td>{{ $bikeData->name }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Total Days <span class="fw-bold">:</span></td>
            <td>{{ $totalDays }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Start Date <span class="fw-bold">:</span></td>
            <td>{{ date('d - M- Y', strtotime($booking['start_date'])) }}
                Pickup {{ date('h:i A', strtotime($booking['start_time'])) }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">End Date <span class="fw-bold">:</span></td>
            <td>{{ date('d - M- Y', strtotime($booking['end_date'])) }} Drop
                off {{ date('h:i A', strtotime($booking['end_time'])) }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Total Bike Price <span class="fw-bold">:</span></td>
            <td>¥{{ $price }}</td>
        </tr>
        <tr style="border-top: 2px solid #000;border-bottom: 1px solid #000;">
            <td colspan="2" class="h5 fw-bold">Accessories & Insurance</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Insurance <span class="fw-bold">:</span></td>
            <td>{{ ($booking['insurance']==1) ? '¥'.$insurance_price : 'NA' }}</td>
        </tr>

@if($accessories)
    @foreach($accessories as $acc_id)
        @php
            $accData = \App\Models\Accessories::find($acc_id);
            $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);

            if($totalDays>1 && $accData->additional_day_price){
                $oneDayPrice=$accData->price;
                $oneDayLaterPrice=$accData->additional_day_price;
                $accPrice=$oneDayPrice + ($oneDayLaterPrice * ($totalDays - 1));
            }else{
                $accPrice=$accData->price*$totalDays;
            }

            if(\Illuminate\Support\Str::contains(strtolower($accData->name),'helmet') && $accPrice >= 6500){
                $accPrice = 6500;
            }
            $subtotal += $accPrice;
        @endphp
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">{{ $accData->name }} <span class="fw-bold">:</span></td>
            <td>¥{{ $accPrice }}</td>
        </tr>
    @endforeach
@endif

        @php
            // Calculate TAX (10%) and Card Fee (3.65%) with consistent rounding for Yen
            $tax = round($subtotal * 0.10);
            // Fee is 3.65% of (Subtotal + Tax)
            $cardFee = round(($subtotal + $tax) * 0.0365);
            $totalPrice = $subtotal + $tax + $cardFee;
        @endphp

        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">TAX 10% <span class="fw-bold">:</span></td>
            <td>¥{{ number_format($tax, 0) }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #000;">
            <td class="d-flex justify-content-between">Card Fee 3.65% <span class="fw-bold">:</span></td>
            <td>¥{{ number_format($cardFee, 0) }}</td>
        </tr>
        <tr class="h6 fw-bold" style="border-bottom: 2px solid #000;">
            <td class="d-flex justify-content-between">Total Price <span>:</span></td>
            <td>¥{{ number_format($totalPrice, 0) }}</td>
        </tr>
    </table>
    <input type="hidden" name="totalPrice[]" value="{{ $totalPrice }}">
@endforeach

