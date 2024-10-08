@component('mail::message')
# Welcome, {{ $user->username }}!

Thank you for joining SurveyCat. We're excited to have you on board.

Get started creating your first survey today!

@component('mail::button', ['url' => config('app.frontend_url')])
    Start Now
@endcomponent

Best,

The SurveyCat Team
@endcomponent

