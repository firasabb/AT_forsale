@extends('layouts.main')

@section('title', 'Free Royalty-Free Posts')

@section('content')

<div class="search-home-container">
    <div class="search-home">
        <form action="{{ route('main.post.search') }}" method="post" class="search-form">
            @csrf
            <div class="row justify-content-center">
                <div class="col search-form-title mb-3">
                    <h2>{{ __('main.search and explore') }}</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-6 mt-3">
                    <div>
                        <input type="text" name="keyword" placeholder="All" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-3 mt-3">
                    <div>
                        <select name="category" class="form-control">
                            <option value="all" selected>All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->url }}">{{ ucwords($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2 mt-3">
                    <button class="btn btn-dark search-btn" type="submit">{{ __('main.search') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container pt-5">
    @foreach($posts as $post)
        <x-post-card :post="$post"/>
    @endforeach
</div>

<x-report>
</x-report>

@endsection

@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }} Discover The Latest Posts!" />
    <meta property="og:description" content="Discover and Download Top Free Royalty-Free Posts!"/>
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta name="description" content="Discover and Download Top Free Royalty-Free Posts!"/>
@endpush
