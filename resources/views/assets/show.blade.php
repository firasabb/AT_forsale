@extends('layouts.main')

@section('title', ucwords($asset->category->name . ' - ' . $asset->title))

@section('content')


@php
    
    $visualArr = ['stock photo', 'logos', 'icons', 'illustrations'];
    $audioArr = ['sound effects', 'music'];
    $videoArr = ['stock video', 'intro'];
    $categoryName = $asset->category->name;

@endphp


<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light card-shadow mb-5">
                <div class="card-header bg-light">
                    <div>
                        <div class="card-header-img">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}"><img class="avatar-pic" src="{{ $asset->user->avatarUrl() }}"/></a>
                        </div>
                        <div class="card-header-text asset-card-user-text">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}">{{ $asset->user->username }}</a>
                        </div>
                        <div class="float-right">
                            <a target="_blank" href="{{ route('main.search.categories', ['category' => $asset->category->url]) }}" class="a-no-decoration">{{ strtoupper($asset->category->name) }}</a>
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
                        <div class="py-3">
                            @if(!empty($featured))
                                @if(in_array($categoryName, $visualArr))
                                    <img class="card-body-img" src="{{ Storage::cloud()->url($asset->featured()) }}" alt="{{ $asset->title }}">
                                @elseif(in_array($categoryName, $videoArr))
                                    <div>
                                        <video muted width="100%" height="230" poster="{{ Storage::cloud()->url($asset->cover()) }}" preload="none">
                                            <source src="{{ Storage::cloud()->url($asset->featured()) }}">
                                            <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.asset', ['url' => $asset->url]) }}"></a></p>
                                        </video>
                                    </div>
                                @elseif(in_array($categoryName, $audioArr))
                                    <div class="card card-shadow" style="width: 100%;">
                                        <img class="card-img card-img-top" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
                                        <div >
                                            <audio controls style="width:100%;">
                                                <source src="{{ Storage::cloud()->url($asset->featured()) }}">
                                                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.asset', ['url' => $asset->url]) }}"></a></p>
                                            </audio>
                                        </div>
                                    </div>
                                @else
                                    <img class="card-body-img" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
                                @endif
                            @endif
                        </div>
                    <div class="downloads-views">
                        <p class="mr-3">@svg('arrow-down', 'arrow-down-icon') {{ $asset->downloadEventsCount() }} downloads</p>
                        <p>@svg('eye', 'eye-icon'){{ $asset->viewEventsCount() }} views</p>
                    </div>
                    @if($asset->description)
                        <div class="my-5">
                            <p class="card-text">{{$asset->description}}</p>
                        </div>
                        <div class="pb-1">
                            @foreach($asset->tags as $tag)
                                <a class="a-no-decoration" target="_blank" href="{{ route('main.search.tags', ['tag' => $tag->url]) }}"><span class="tag-span">{{strtoupper($tag->name)}}</span></a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light card-f">
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
                            @php
                                $n = 1;
                            @endphp
                            @foreach($asset->downloads as $download)
                                <form action="{{ route('download.download') }}" method="post">
                                    <div class="row justify-content-center pb-3">
                                        <div class="col text-center">
                                            <p class="mb-1">File {{ $n }}:</p>
                                            <input type="hidden" name="id" value="{{ encrypt($download->id) }}">
                                            <button class="btn btn-dark download-btn">Download</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="recaptcha" class="recaptcha">
                                    @csrf
                                    @php
                                        $n++;
                                    @endphp
                                </form>
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
                                        <img class="comment-user-img" src="{{ $asset->user->avatarUrl() }}" alt="{{ $comment->user->username }}"/>
                                    </a>
                                </div>
                                <div class="text-center comment-user-name">
                                    <a target="_blank" href="{{ route('user.profile.show', ['username' => $comment->user->username]) }}" class="a-no-decoration">{{ $comment->user->username }}</a>
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

    @if(!$relatedAssets->isEmpty())
        <div class="row py-2">
            <div class="col-md-8">
                <h2>You May Also Like:</h2>
            </div>
        </div>
        <div class="row pt-3 pb-2">
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

<x-user-ad :user="$asset->user" :user-ad="$asset->user->approvedUserAd()">
</x-user-ad>

@endsection


@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $asset->title }}" />
    <meta property="og:description" content="Made by {{ $asset->user->username }}! Download {{ strtoupper($asset->category->name) }} Assets for Free on AssetTorch!" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta property="og:image" content="{{ Storage::cloud()->url($featured) }}">
    <meta name="description" content="Download {{ strtoupper($asset->category->name) }} Assets for Free on AssetTorch! Made by {{ $asset->user->username }}"/>
@endpush


@push('footer_scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script type="text/javascript">
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
                    if (token) {
                        let elms = document.getElementsByClassName('recaptcha');
                        for(let i = 0; i < elms.length; i++){
                            elms[i].value = token;
                        }
                    }
                });
            });
    </script>
    <script defer type="text/javascript">
        window.addEventListener('load', function(){
            var downloadBtn = $('.download-btn');
            downloadBtn.on('click', function(e){
                $('#userAdModal').modal('show');
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
                        if (token) {
                            let elms = document.getElementsByClassName('recaptcha');
                            for(let i = 0; i < elms.length; i++){
                                elms[i].value = token;
                            }
                        }
                    });
                });
            });
        });
    </script>
@endpush