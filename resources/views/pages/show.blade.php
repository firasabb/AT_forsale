@extends('layouts.main')

@section('title', ucwords($page->title))

@section('content')

<div class="container">
    <div class="row py-5">
        <div class="col">
            {{ $page->title }}
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-9">
            {!! $page->body !!}
        </div>
        <div class="col-3">
        </div>
    </div>
</div>
@endsection


@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $page->title }} - AssetTorch" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
@endpush
