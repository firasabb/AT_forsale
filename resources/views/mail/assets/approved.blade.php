@component('mail::message')
# Introduction

Congratulations! Your asset is approved!

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
