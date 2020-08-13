@extends('layouts.main')

@php
    $title = !empty($reqCategory) ? ucwords($reqCategory) : 'Posts';
    $title = 'Free Royalty-Free ' . $title;
@endphp

@section('title', $title)

@section('content')

<div class="container-fluid pt-3">
    
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-5">
            <div class="search-col">
                <form method="post" action="{{ route('main.post.search') }}">
                    @csrf
                    <div class="row justify-content-center text-center">
                        <div class="col-12 col-md-5 form-group">
                            <input name="keyword" type="text" value="{{ $inputKeyword }}" placeholder="All" class="form-control" id="search-keyword">
                        </div>
                        <div class="col-12 col-md-5 form-group">
                            <select name="category" id="category" class="form-control">
                                <option value="all">ALL</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->url }}" {{ $reqCategory == $category->name ? 'selected' : '' }}>{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2 form-group">
                            <button type="submit" class="btn btn-light-green">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card-deck">
                @foreach($posts as $post)
                    <div class="pb-4">
                        <x-post-card :post="$post"/>
                    </div>
                @endforeach 
            </div>
            <div class="py-5 text-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }} - Discover The Latest Free Posts!" />
    <meta property="og:description" content="Discover and Download Top Free Royalty-Free Posts!"/>
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta name="description" content="Discover and Download Top Free Royalty-Free Posts!"/>
@endpush
