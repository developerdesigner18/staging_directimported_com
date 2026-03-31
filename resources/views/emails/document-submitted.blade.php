@component('mail::message')
# Document Submission Notification

Hello Admin,

<b>{{ $user->first_name }} {{ $user->last_name }}</b> has just submitted new documents for verification.

**User details:**
- **Name:** {{ $user->first_name }} {{ $user->last_name }}
- **Email:** {{ $user->email }}
- **Phone:** {{ $user->mobile }}

@if($user->userDetail)
@if($user->userDetail->passport)
- **Passport:** [View]({{ $user->userDetail->passport }})
@endif
@if($user->userDetail->international_lic)
- **International License:** [View]({{ $user->userDetail->international_lic }})
@endif
@if($user->userDetail->regular_lic)
- **Regular License:** [View]({{ $user->userDetail->regular_lic }})
@endif
@endif

You can review the documents in the admin dashboard.

@component('mail::button', ['url' => url('admin/user/'.$user->id)])
    View User Profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
