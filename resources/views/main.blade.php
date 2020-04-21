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

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form method="POST" action="{{ route('add.report', ['type' => 'art']) }}">
                {!! csrf_field() !!}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report This Art</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea class="form-control" name="body" placeholder="Please describe why the art should not be on the website..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button name="action" type="submit" class="btn btn-primary">Submit</button>
            </div>
            <input type="hidden" name="_q" v-bind:value="id">
        </form>
        </div>
    </div>
</div>




@endsection