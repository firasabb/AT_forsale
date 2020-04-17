@extends('layouts.main')

@section('content')

<div class="container">

    <div class="search-bar">
        <form method="post" action="">
            @csrf
            <div class="row search-bar-row justify-content-center">
                <div class="col-sm-6">
                    <input name="search" type="text" value="" class="form-control">
                </div>
                <div class="col-sm-4">
                    <select name="type" class="form-control">
                        <option value="all">ALL</option>
                        @foreach($types as $type)
                            <option value="{{ $type->name }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row justify-content-center">
        <div class="col-3">
        </div>
        <div class="col-6">
            @foreach($arts as $art)
            <div class="card">
                <div class="card-header">
                    <div class="card-header-img">
                        <a href="#"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                    </div>
                    <div class="card-header-text">
                        <a href="#">{{ $art->user->name }}</a>
                    </div>
                    <div class="card-header-date">
                        <span>{{ $art->created_at->toDateString() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{$art->title}}</h5>
                    <img src="{{ Storage::cloud()->url($art->downloads->where('featured', 1)) }}">
                    @if($art->description)
                        <p class="card-text">{{$art->description}}</p>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="card-footer-icons">
                        <!--@svg('heart', 'heart-icon')-->
                    </div>
                    <div class="card-footer-report">
                        <button type="button" v-on:click="open_report_modal('{{ encrypt($art->id) }}')" class="report-btn">Report</button>
                    </div>
                </div>
            </div>
            @endforeach 
        </div>
        <div class="col-3">
        </div>
    </div>
</div>

@endsection