@extends('layouts.main')

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
                            <option value="all" selected>ALL</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ strtoupper($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2 mt-3">
                    <button class="btn btn-primary search-btn" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="card-deck">
        @foreach($arts as $art)
            <x-art-card :art="$art"/>
        @endforeach
    </div>
</div>

<x-report>
</x-report>




@endsection