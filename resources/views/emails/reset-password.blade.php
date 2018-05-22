@component('mail::message')
# Hello

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => url(config('api.frontend_url') . '/password/reset?token=' . $token)])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.
<br>

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: {{ config('api.frontend_url') . '/password/reset?token=' . $token }}
@endcomponent
@endcomponent
