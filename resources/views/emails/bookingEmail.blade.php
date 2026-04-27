@component('mail::message')

Hello {{$user->first_name.' '.$user->last_name}}
<br />
Please use the payment form to complete your booking <a href="javascript:void(0);">Payment Form</a>
<p>Please check the following booking details below. If you would like to make any changes please REPLY to this email.</p>
<p>{{$booking->email_comment}}</p>
<h4>Booking Details</h4>

<table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; border: 1px solid #000;margin-bottom: 20px;margin-top: 5px;">
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">ID <span>:</span></th>
    <td>{{$booking->id}}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Booking ID <span>:</span></th>
    <td>{{$booking->booking_id}}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Booking Amount <span>:</span></th>
    <td>{{'¥'. number_format($booking->price)}}</td>
</tr>

<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Name <span>:</span></th>
    <td>{{ucfirst($user->first_name) ?? ''}} {{ucfirst($user->last_name)}}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Email <span>:</span></th>
    <td>{{$user->email}}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Start Date <span>:</span></th>
    <td>{{ date('d - M- Y', strtotime($booking->start_date)) }} Pickup {{ date('h:i A', strtotime($booking->start_time)) }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">End Date <span>:</span></th>
    <td>{{ date('d - M- Y', strtotime($booking->end_date)) }} Drop off {{ date('h:i A', strtotime($booking->end_time)) }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Policies Accepted <span>:</span></th>
    <td>{{ $booking->policy_status==1?'Yes':'No' }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Comments <span>:</span></th>
    <td>{{ $booking->comment??'' }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Status <span>:</span></th>
    <td>{{ $booking->status?->label() ?? 'Processing - Approval pending' }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Selected Cars <span>:</span></th>
    <td>{{ $car->name }}</td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Included Accessories <span>:</span></th>
    <td>
        {{ implode(', ',$included_accessories) }}
    </td>
</tr>
<tr style="border-bottom: 1px solid #000;">
    <th style="display: flex;justify-content: space-between;">Selected Accessories <span>:</span></th>
    <td>
        {{ implode(', ',$selected_accessories) }}
    </td>
</tr>
</table>

{{ config('app.name') }}
@endcomponent
