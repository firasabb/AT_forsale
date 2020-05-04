@extends('layouts.main')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light card-shadow mb-5">
                <div class="card-header bg-light">
                    <div class="card-header-flex">
                        <div class="card-header-img">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}"><img class="avatar-pic" src="{{ $asset->user->avatar_url }}"/></a>
                        </div>
                        <div class="card-header-text asset-card-user-text">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}">{{ $asset->user->name }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h3 class="card-title my-2">{{$asset->title}}</h3>
                    @if(!empty($featured))
                        <img class="card-body-img" src="{{ Storage::cloud()->url($featured) }}" alt="{{ $asset->title }}">
                    @endif
                    <div class="downloads-views">
                        <p class="mr-3">@svg('arrow-down', 'arrow-down-icon') {{ $asset->downloadEventsCount() }} downloads</p>
                        <p>@svg('eye', 'eye-icon'){{ $asset->viewEventsCount() }} views</p>
                    </div>
                    @if($asset->description)
                        <div class="py-3">
                            <h3>Description</h3>
                        </div>
                        <div class="mb-5">
                            <p class="card-text">{{$asset->description}}</p>
                        </div>
                        <div class="pb-1">
                            @foreach($asset->tags as $tag)
                                <a class="a-no-decoration" href="#"><span class="tag-span">{{strtoupper($tag->name)}}</span></a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light card-f"><!--@svg('heart', 'heart-icon')-->
                    <div class="card-footer-more">
                        <div class="float-left card-footer-date">
                            <span>{{$asset->createdAt()}}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle-comment float-right btn-no-padding" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @svg('th-menu', 'menu-icon-comment')
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if(Auth::id() != $asset->user->id)
                                    <button type="button" v-on:click="open_report_modal('{{ encrypt($asset->id) }}', '{{ route('add.report', ['type' => 'asset']) }}')" class="dropdown-item">Report</button>
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
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card border-light card-shadow">
                        <div class="card-header bg-light">
                            Free Download
                        </div>
                        <div class="card-body">
                            @foreach($asset->downloads as $download)
                                <div class="row justify-content-center">
                                    <div class="col text-center">
                                        <form action="{{ route('download.download') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ encrypt($download->id) }}">
                                            <input type="hidden" name="recaptcha" id="recaptcha">
                                            <button v-on:click="open_user_ad_modal()" class="btn btn-lg btn-success">Download</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-5">
                <div class="col">
                    <div class="card border-light card-shadow">
                        <div class="card-header bg-light">
                            License
                        </div>
                        <div class="card-body text-center">
                            <div class="py-2">
                                <h4>{{ $license->name }}<h4>
                            </div>
                            @if(!is_null($license->link))
                                <div>
                                    <a target="_blank" class="a-no-decoration" href="{{ $license->link }}">Click here for more information.</a>
                                </div>
                            @endif
                        </div>
                    </div>
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
                        <form method="POST" action="{{ route('add.comment', ['encryptedId' => encrypt($asset->id)]) }}">
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
        
    @foreach($asset->comments as $comment)
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
                                        @elseif(Auth::id() == $asset->user->id)
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

    @if(!empty($relatedAssets))
        <div class="row py-2">
            <div class="col-md-8">
                <h2>Don't Miss:</h2>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-md-8">
                <div class="card-deck">
                @foreach($relatedAssets as $relatedAsset)
                    <div class="py-2">
                        <x-asset-card :asset="$relatedAsset"></x-asset-card>
                    </div>
                @endforeach    
                </div>
            </div>
        </div>
    @endif

    @hasanyrole('moderator|admin')
        <div class="row">
            <div class="col-md-8">
                <div class="block-button">
                    <a target="_blank" href="{{route('admin.show.asset', ['id' => $asset->id])}}" target="_blank" class="btn btn-secondary btn-lg btn-block">Edit This asset</a>
                </div>
            </div>
        </div>
    @endrole
    </div>


<x-report>
</x-report>

<x-user-ad>
</x-user-ad>

@push('footer_scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script type="text/javascript">
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
                    if (token) {
                    document.getElementById('recaptcha').value = token;
                    }
                });
            });
    </script>
@endpush

@endsection
