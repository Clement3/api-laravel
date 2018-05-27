@component('mail::message')
# @lang('auth.mail.hello')

@lang('auth.mail.line1')

@component('mail::button', ['url' => url($url)])
@lang('auth.mail.action')
@endcomponent

@lang('mail.thanks')<br>
{{ config('app.name') }}

@component('mail::subcopy')
@lang('mail.subcopy', ['action' => __('auth.mail.action'), 'url' => $url])
@endcomponent
@endcomponent