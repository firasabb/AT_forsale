@extends('layouts.main')

@section('title', 'Free Royalty-Free Assets')

@section('content')

<div class="search-home-container">
    <div class="search-home">
        <form action="{{ route('main.search') }}" method="post" class="search-form">
        @csrf
            <div class="row justify-content-center">
                <div class="col search-form-title mb-3">
                    <h1>Search and Explore All The Free Assets</h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-6 mt-3">
                    <div>
                        <input type="text" name="search" placeholder="All" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-3 mt-3">
                    <div>
                        <select name="type" class="form-control">
                            <option value="all" selected>All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ ucwords($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2 mt-3">
                    <button class="btn btn-dark search-btn" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">
    <div class="card-columns">
        @foreach($assets as $asset)
            <div class="pb-4">
                <x-asset-card :asset="$asset"/>
            </div>
        @endforeach
    </div>
    <div class="py-3">
        {{ $assets->links() }}
    </div>
</div>

<x-report>
</x-report>

@endsection

@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }} Discover The Latest Assets!" />
    <meta property="og:description" content="Discover and Download Top Free Royalty-Free Assets!"/>
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta name="description" content="Discover and Download Top Free Royalty-Free Assets!"/>
@endpush
