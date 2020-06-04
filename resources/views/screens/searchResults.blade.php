@extends('layouts.main')

@php
    $title = !empty($reqCategory) ? ucwords($reqCategory) : 'Assets';
    $title = 'Free Royalty-Free ' . $title;
@endphp

@section('title', $title)

@section('content')

<div class="container pt-5">
    
    <div class="row justify-content-center">
        <div class="col-4">
            <div class="search-col">
                <form method="post" action="{{ route('main.search') }}">
                    @csrf
                    <div class="form-row text-left">
                        <div class="col form-group">
                            <label for="search-keyword">Search:</label>
                            <input name="search" type="text" value="{{ Request::input('search') }}" placeholder="All" class="form-control" id="search-keyword">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group pb-3">
                            <label for="category">Category:</label>
                            <select name="category" id="category" class="form-control">
                                <option value="all">ALL</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ $reqCategory == $category->name ? 'selected' : '' }}>{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-8 search-card-columns">
            <div class="card-columns">
                @foreach($assets as $asset)
                    <div class="pb-4">
                        <x-asset-card :asset="$asset"/>
                    </div>
                @endforeach 
            </div>
            <div class="py-5 text-center">
                {{ $assets->links() }}
            <div>
        </div>
    </div>
</div>

@endsection

@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }} - Discover The Latest Free Assets!" />
    <meta property="og:description" content="Discover and Download Top Free Royalty-Free Assets!"/>
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta name="description" content="Discover and Download Top Free Royalty-Free Assets!"/>
@endpush
