@component('mail::message')

Hello {{ $user->first_name }} {{ $user->last_name }},

Please use the payment form to complete your booking.

Thanks,<br>
{{ config('app.name') }}

@endcomponent
