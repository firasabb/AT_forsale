@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light art-card">
                <div class="card-header bg-light">
                    <div class="card-header-flex">
                        <div class="card-header-img">
                            <a href="#"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                        </div>
                        <div class="card-header-text">
                            <a href="#">{{ $art->user->name }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2 class="card-title">{{$art->title}}</h2>
                    @if(!empty($featured))
                        <img src="{{ $featured->public_url }}" alt="{{ $art->title }} Image">
                    @endif
                    @if($art->description)
                        <p class="card-text">{{$art->description}}</p>
                    @endif
                </div>
                <div class="card-footer bg-light">@svg('heart', 'heart-icon')</div>
            </div>
        </div>
    </div>
    
    @auth
        @if(empty($art->comments->where('user_id', Auth::user()->id)->first()))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('add.comment', ['encryptedId' => encrypt($art->id)]) }}">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" name="title" type="text" value="{{ old('title') }}" />
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        @endif
    @endauth
        
    @foreach($art->comments as $comment)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-1">
                        @svg('thumbs-up', 'heart-icon')
                        @svg('thumbs-down', 'heart-icon')
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h5 class="card-title">{{ $comment->title }}</h5>
                            
                            <p class="card-text">{{ $comment->description }}</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
        @hasanyrole('writer|admin')
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="block-button">
                        <a href="{{route('admin.show.art', ['id' => $art->id])}}" target="_blank" class="btn btn-primary btn-lg btn-block">Edit This Art</a>
                    </div>
                <div>
            </div>
        @endrole
        </div>
    </div>
</div>
@endsection
