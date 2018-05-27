@component('mail::message')
# @lang('mail.hello')

@lang('passwords.mail.line1')

@component('mail::button', ['url' => url($url)])
@lang('passwords.mail.action')
@endcomponent

@lang('passwords.mail.line2')
<br>

@lang('mail.thanks')<br>
{{ config('app.name') }}

@component('mail::subcopy')
@lang('mail.subcopy', ['action' => __('passwords.mail.action'), 'url' => $url])
@endcomponent
@endcomponent
