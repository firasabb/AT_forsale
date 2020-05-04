@extends('layouts.main')

@section('content')

<div class="container">

    <div class="search-bar">
        <form method="post" action="">
            @csrf
            <div class="row search-bar-row justify-content-center">
                <div class="col-sm-6">
                    <input name="search" type="text" value="{{ Request::input('search') }}" placeholder="All" class="form-control">
                </div>
                <div class="col-sm-4">
                    <select name="type" class="form-control">
                        <option value="all">ALL</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row justify-content-center my-5">
        <div class="col-3">
        </div>
        <div class="col-6">
            @foreach($assets as $asset)
                <x-asset-card :asset="$asset"/>
            @endforeach 
        </div>
        <div class="col-3">
        </div>
    </div>
</div>

@endsection