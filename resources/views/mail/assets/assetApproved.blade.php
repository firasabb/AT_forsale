@component('mail::message')
# Introduction

Congratulations! Your asset is approved!<br>
Thank you for being a part of us.

@component('mail::button', ['url' => $url, 'color' => 'blue'])
View My Asset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
