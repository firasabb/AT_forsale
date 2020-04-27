@extends('layouts.main')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light card-shadow mb-5">
                <div class="card-header bg-light">
                    <div class="card-header-flex">
                        <div class="card-header-img">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $art->user->username]) }}"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                        </div>
                        <div class="card-header-text post-card-user-text">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $art->user->username]) }}">{{ $art->user->name }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h3 class="card-title my-2">{{$art->title}}</h3>
                    @if(!empty($featured))
                        <img class="card-body-img" src="{{ Storage::cloud()->url($featured) }}" alt="{{ $art->title }}">
                    @endif
                    @if($art->description)
                        <div class="py-3">
                            <h3>Description</h3>
                        </div>
                        <div class="mb-5">
                            <p class="card-text">{{$art->description}}</p>
                        </div>
                        <div class="pb-1">
                            @foreach($art->tags as $tag)
                                <a class="a-no-decoration" href="#"><span class="tag-span">{{strtoupper($tag->name)}}</span></a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light card-f"><!--@svg('heart', 'heart-icon')-->
                    <div class="card-footer-more">
                        <div class="float-left card-footer-date">
                            <span>{{$art->createdAt()}}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle-comment float-right btn-no-padding" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @svg('th-menu', 'menu-icon-comment')
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if(Auth::id() != $art->user->id)
                                    <button type="button" v-on:click="open_report_modal('{{ encrypt($art->id) }}', '{{ route('add.report', ['type' => 'art']) }}')" class="report-btn float-right">Report</button>
                                @elseif(!Auth::check())
                                    <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">Report</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light card-shadow">
                <div class="card-header bg-light">
                    Free Download
                </div>
                <div class="card-body">
                    @foreach($art->downloads as $download)
                        <div class="row justify-content-center">
                            <div class="col text-center">
                                <a target="_blank" class="btn btn-lg btn-success" href="{{ route('download.download', ['id' => encrypt($download->id)]) }}">Download</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    @auth
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header bg-light">
                        <p class="mb-0">Add a Comment</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('add.comment', ['encryptedId' => encrypt($art->id)]) }}">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="body">{{ old('body') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @guest

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header bg-light">
                        <p class="mb-0">Add a Comment</p>
                    </div>
                    <div class="card-body text-center">
                        <a target="_blank" class="btn btn-light" href="{{ route('login') }}">Login</a>
                        <a target="_blank" class="btn btn-primary" href="{{ route('register') }}">Register</a>
                    </div>
                </div>
            </div>
        </div>

    @endguest
        
    @foreach($art->comments as $comment)
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <a target="_blank" href="{{ route('user.profile.show', ['username' => $comment->user->username]) }}">
                                        <img class="comment-user-img" src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"/>
                                    </a>
                                </div>
                                <div class="text-center comment-user-name">
                                    <a target="_blank" href="{{ route('user.profile.show', ['username' => $comment->user->username]) }}" class="a-no-decoration">{{ $comment->user->name }}</a>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">                               
                                    <p class="card-text">{{ $comment->body }}</p>
                                    <p class="card-text"><small class="text-muted">{{ $comment->created_at->format('jS \\of F Y h:m') }}</small></p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle-comment float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @svg('th-menu', 'menu-icon-comment')
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if(Auth::id() == $comment->user->id)
                                            <form action="{{ route('delete.comment', ['id' => encrypt($comment->id)]) }}" method="POST" class="delete-comment">
                                                @csrf
                                                {!! method_field('DELETE') !!}
                                                <button class="btn btn-danger dropdown-item" type="submit">Delete</button>
                                            </form>
                                        @elseif(Auth::id() == $art->user->id)
                                            <form action="{{ route('delete.comment', ['id' => encrypt($comment->id)]) }}" method="POST" class="delete-comment">
                                                @csrf
                                                {!! method_field('DELETE') !!}
                                                <button class="btn btn-danger dropdown-item" type="submit">Delete</button>
                                            </form>
                                            <button type="button" v-on:click="open_report_modal('{{ encrypt($comment->id) }}', '{{ route('add.report', ['type' => 'comment']) }}')" class="dropdown-item">Report</button>
                                        @elseif(Auth::check())
                                            <button type="button" v-on:click="open_report_modal('{{ encrypt($comment->id) }}', '{{ route('add.report', ['type' => 'comment']) }}')" class="dropdown-item">Report</button>
                                        @else
                                            <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">Report</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
        @hasanyrole('moderator|admin')
            <div class="row">
                <div class="col-md-8">
                    <div class="block-button">
                        <a target="_blank" href="{{route('admin.show.art', ['id' => $art->id])}}" target="_blank" class="btn btn-secondary btn-lg btn-block">Edit This Art</a>
                    </div>
                </div>
            </div>
        @endrole
        </div>


<x-report>
</x-report>
@endsection
