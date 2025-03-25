@component('mail::message')
# Welcome to the Team, {{ $user->first_name }}!
We’re delighted to officially welcome you to {{ $user->restaurent_name}}! It’s great to have you as part of our team, and we’re excited about the journey ahead. We truly believe that your skills and talents will be a valuable addition to our company.

We hope you’ve been settling in well and getting to know your colleagues. If there’s anything you need or any questions you have, please don’t hesitate to reach out. We’re all here to support you and ensure you have a smooth transition.

Looking forward to achieving great things together! Once again, welcome aboard!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
