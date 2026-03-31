<x-mail::message>
{{-- Optional Logo/Header --}}
<div style="text-align: center; margin-bottom: 20px;">
<img src="{{ asset('assets/logo/main.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 160px;">
</div>

{{-- Title --}}
<h1 style="font-size: 22px; color: #2C3E50; margin-bottom: 16px; text-align: center;">
{{ $subjectText ?? 'Hello!' }}
</h1>

{{-- Main Body --}}
<div style="font-size: 15px; line-height: 1.7; color: #444;">
{!! nl2br(e($bodyText ?? 'This is your custom message content.')) !!}
</div>

{{-- Divider Line --}}
<hr style="border: none; border-top: 1px solid #ddd; margin: 25px 0;">

{{-- Signature --}}
<p style="font-size: 14px; color: #555; margin-top: 20px;">
Kind Regards,<br>
<strong>{{ config('app.name') }} Team</strong><br>
</p>

{{-- Optional Footer Note --}}
<p style="font-size: 12px; color: #999; text-align: center; margin-top: 30px;">
You received this email because you’re connected with {{ config('app.name') }}.<br>
Please do not reply to this automated message.
</p>
</x-mail::message>

