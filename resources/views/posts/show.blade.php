@extends('layouts.main')

@section('title', ucwords($post->category->name . ' - ' . $post->title))

@section('content')


@php
    
    $visualArr = ['stock photos', 'logos', 'icons', 'vectors'];
    $audioArr = ['sound effects', 'music'];
    $videoArr = ['stock videos', 'intro'];
    $categoryName = $post->category->name;


    $viewsCount = $post->viewEventsCount();
    $downloadsCount = $post->downloadEventsCount();

    $viewsNumber = '';
    $downloadsNumber = '';

    if($viewsCount > 200){
        $viewsNumber = $viewsCount;
    } else {
        $viewsNumber = '< 200';
    }

    if($downloadsCount > 100){
        $downloadsNumber = $downloadsCount;
    } else {
        $downloadsNumber = '< 100';
    }

@endphp


<div class="container pt-5">
    <div class="row justify-content-center pb-4">
        <div class="col-12">
            <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-5166868654451969"
            data-ad-slot="9724686559"
            data-ad-format="auto"
            data-full-width-responsive="true">
            </ins>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light card-shadow mb-5">
                <div class="card-header bg-light">
                    <div>
                        <div class="card-header-img">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $post->user->username]) }}"><img class="avatar-pic" src="{{ $post->user->avatarUrl() }}"/></a>
                        </div>
                        <div class="card-header-text post-card-user-text">
                            <a target="_blank" href="{{ route('user.profile.show', ['username' => $post->user->username]) }}">{{ $post->user->username }}</a>
                        </div>
                        <div class="float-right">
                            <a target="_blank" href="{{ route('main.search.categories', ['category' => $post->category->url]) }}" class="a-no-decoration">{{ strtoupper($post->category->name) }}</a>
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
                    <h3 class="card-title my-2">{{$post->title}}</h3>
                        <div class="py-3">
                            @if(!empty($featured))
                                @if(in_array($categoryName, $visualArr))
                                    <img class="card-body-img" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
                                @elseif(in_array($categoryName, $videoArr))
                                    <div>
                                        <video muted width="100%" height="230" poster="{{ Storage::cloud()->url($post->cover()) }}" preload="none">
                                            <source src="{{ Storage::cloud()->url($post->featured()) }}">
                                            <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.post', ['url' => $post->url]) }}"></a></p>
                                        </video>
                                    </div>
                                @elseif(in_array($categoryName, $audioArr))
                                    <div class="card card-shadow" style="width: 100%;">
                                        <img class="card-img card-img-top" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
                                        <div >
                                            <audio controls style="width:100%;">
                                                <source src="{{ Storage::cloud()->url($post->featured()) }}">
                                                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.post', ['url' => $post->url]) }}"></a></p>
                                            </audio>
                                        </div>
                                    </div>
                                @else
                                    <img class="card-body-img" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
                                @endif
                            @endif
                        </div>
                    <div class="downloads-views">
                        <p class="mr-3">@svg('arrow-down', 'arrow-down-icon') {{ $downloadsNumber }} downloads</p>
                        <p>@svg('eye', 'eye-icon') {{ $viewsNumber }} views</p>
                    </div>
                    @if($post->description)
                        <div class="my-5">
                            <p class="card-text">{{$post->description}}</p>
                        </div>
                    @endif
                    <div class="pb-1 pt-3" style="line-height: 2rem">
                        @foreach($post->tags as $tag)
                            <a class="a-no-decoration" target="_blank" href="{{ route('main.search.tags', ['tag' => $tag->url]) }}"><span class="tag-span">{{strtoupper($tag->name)}}</span></a>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-light card-f">
                    <div class="card-footer-more">
                        <div class="float-left card-footer-date">
                            <span>{{$post->createdAt()}}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle-comment float-right btn-no-padding" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @svg('th-menu', 'menu-icon-comment')
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if(Auth::id() != $post->user->id)
                                    <button type="button" v-on:click="open_report_modal('{{ encrypt($post->id) }}', '{{ route('add.report', ['type' => 'post']) }}')" class="dropdown-item">{{ __('main.report') }}</button>
                                @elseif(!Auth::check())
                                    <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">{{ __('main.report') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @auth
            <div>
                <div class="card mb-5">
                    <div class="card-header bg-light">
                        <p class="mb-0">{{ __('main.add comment') }}</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('add.comment', ['encryptedId' => encrypt($post->id)]) }}">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="body">{{ old('body') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('main.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        @guest
            <div class="card mb-5">
                <div class="card-header bg-light">
                    <p class="mb-0">{{ __('main.add comment') }}</p>
                </div>
                <div class="card-body text-center">
                    <a target="_blank" class="btn btn-light" href="{{ route('login') }}">{{ __('Login') }}</a>
                    <a target="_blank" class="btn btn-primary" href="{{ route('register') }}">{{ __('Register') }}</a>
                </div>
            </div>

        @endguest
            
        @foreach($post->comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-md-1">
                            <div class="text-center">
                                <a target="_blank" href="{{ route('user.profile.show', ['username' => $comment->user->username]) }}">
                                    <img class="comment-user-img" src="{{ $post->user->avatarUrl() }}" alt="{{ $comment->user->username }}"/>
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
                                    @elseif(Auth::id() == $post->user->id)
                                        <form action="{{ route('delete.comment', ['id' => encrypt($comment->id)]) }}" method="POST" class="delete-comment">
                                            @csrf
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger dropdown-item" type="submit">Delete</button>
                                        </form>
                                        <button type="button" v-on:click="open_report_modal('{{ encrypt($comment->id) }}', '{{ route('add.report', ['type' => 'comment']) }}')" class="dropdown-item">{{ __('main.report) }}</button>
                                    @elseif(Auth::check())
                                        <button type="button" v-on:click="open_report_modal('{{ encrypt($comment->id) }}', '{{ route('add.report', ['type' => 'comment']) }}')" class="dropdown-item">{{ __('main.report') }}</button>
                                    @else
                                        <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">{{ __('main.report') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if(!$relatedPosts->isEmpty())
            <div class="py-2">
                <h2>{{ __('main.you may also like') }}:</h2>
            </div>
            <div class="pb-5">
                <div class="card-deck">
                @foreach($relatedPosts as $relatedPost)
                    <div class="py-2">
                        <x-post-card :post="$relatedPost"></x-post-card>
                    </div>
                @endforeach    
                </div>
            </div>
        @endif

        @hasanyrole('moderator|admin')
            <div>
                <div class="block-button">
                    <a target="_blank" href="{{route('admin.show.post', ['id' => $post->id])}}" target="_blank" class="btn btn-secondary btn-lg btn-block">{{ __('main.edit') }}</a>
                </div>
            </div>
        @endrole
        </div>
        <div class="col-md-4">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card border-light card-shadow">
                        <div class="card-header bg-light">
                            {{ __('main.free download') }}
                        </div>
                        <div class="card-body">
                            @php
                                $n = 1;
                            @endphp
                            @foreach($post->downloads as $download)
                                <form action="{{ route('download.download') }}" method="post">
                                    <div class="row justify-content-center pb-3">
                                        <div class="col text-center">
                                            <p class="mb-1"><strong>File {{ $n }}:</strong> {{ Download::sizeFormat(Storage::cloud()->size($download->url)) }}</p>
                                            <input type="hidden" name="id" value="{{ encrypt($download->id) }}">
                                            @if($post->category->url == 'stock-photos')
                                                <button class="btn btn-dark download-btn">{{ Download::getImageSize(Storage::cloud()->temporaryUrl($download->url, now()->addSeconds(4))) }}</button>
                                            @else
                                                <button class="btn btn-dark download-btn">Download</button>
                                            @endif
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
                            {{ __('main.license) }}
                        </div>
                        <div class="card-body text-center">
                            <div class="py-2">
                                <h4>{{ strtoupper($license->name) }}<h4>
                            </div>
                            @if(!is_null($license->description))
                                <div class="pb-3">
                                    <p>{{ $license->description }}</p>
                                </div>
                            @endif
                            @if(!is_null($license->link))
                                <div>
                                    <a target="_blank" class="a-no-decoration" href="{{ $license->link }}">{{ __('main.click here information) }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-5">
                <div class="col">
                    <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-5166868654451969"
                    data-ad-slot="6521962234"
                    data-ad-format="auto"
                    data-full-width-responsive="true">
                    </ins>
                </div>
            </div>
            <div class="row py-5">
                <div class="col">
                    <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-5166868654451969"
                    data-ad-slot="4396446058"
                    data-ad-format="auto"
                    data-full-width-responsive="true">
                    </ins>
                </div>
            </div>
        </div>
    </div>






<x-report>
</x-report>

<x-user-ad :user="$post->user" :user-ad="$post->user->approvedUserAd()">
</x-user-ad>

@endsection


@push('meta_tags')
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $post->title }}" />
    <meta property="og:description" content="Made by {{ $post->user->username }}! Download {{ strtoupper($post->category->name) }} Posts for Free on Genyoon!" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
    <meta property="og:image" content="{{ Storage::cloud()->url($post->cover()) }}">
    <meta name="description" content="Download {{ strtoupper($post->category->name) }} Posts for Free on Genyoon! Made by {{ $post->user->username }}"/>
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


@if(strpos($_SERVER['HTTP_HOST'], 'localhost') === false)
    @push('footer_scripts')
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    @endpush
@endif