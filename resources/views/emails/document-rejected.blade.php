@component('mail::message')
# Document Rejected

Hi {{ $user->first_name.' '.($user->last_name??'') }},

We reviewed your documents, and unfortunately, it has been **rejected**.

{{--@component('mail::panel')--}}
{{--    **Reason:**--}}
{{--    {{ $reason }}--}}
{{--@endcomponent--}}

If you want to update and resubmit the document, please make the necessary changes and upload it again.
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px;">
    <tr>
        <td align="center">
            <a href="{{ route('login') }}"
               style="background:#D7304D;
                      color:#ffffff;
                      padding:12px 0;
                      text-decoration:none;
                      border-radius:6px;
                      display:inline-block;
                      width:50%;
                      text-align:center;
                      font-weight:600;">
                Login
            </a>
        </td>
    </tr>
</table>

Need help? Reply to this email or contact our support team.

Thanks,
{{ config('app.name') }}
@endcomponent
