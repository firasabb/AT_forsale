@php
    
    $visualArr = ['stock photos', 'logos', 'icons', 'vectors'];
    $audioArr = ['sound effects', 'music'];
    $videoArr = ['stock videos', 'intro'];
    $categoryName = $post->category->name;

@endphp

@if(in_array($categoryName, $visualArr))
    <div class="card card-shadow" style="max-width: 20rem">
        <div class="card-image-container">
            <a href="{{ route('show.post', ['url' => $post->url]) }}">
                <img class="card-img card-img-top" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
            </a>
        </div>
        <div class="card-img-overlay card-user-img-transition">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $post->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $post->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $post->user->username]) }}">{{ $post->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $post->category }}" background-color="{{ $post->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.post', ['url' => $post->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($post->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
@elseif(in_array($categoryName, $audioArr))

    <div class="card card-shadow" style="max-width: 20rem">
        <div class="card-image-container">
            <a href="{{ route('show.post', ['url' => $post->url]) }}">
                <img class="card-img card-img-top" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
            </a>
        </div>
        <div class="card-img-overlay card-user-img-transition">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $post->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $post->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $post->user->username]) }}">{{ $post->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $post->category }}" background-color="{{ $post->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.post', ['url' => $post->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($post->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="card-audio-overlay">
            <audio controls class="card-audio-overlay-audio">
                <source src="{{ Storage::cloud()->url($post->featured()) }}">
                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.post', ['url' => $post->url]) }}"></a></p>
            </audio>
        </div>
    </div>

@elseif(in_array($categoryName, $videoArr))

    <div class="card card-shadow card-video" style="max-width: 20rem">
        <div class="card-image-container">
            <a href="{{ route('show.post', ['url' => $post->url]) }}">
                <img class="card-img card-img-top" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
            </a>
        </div>
        <div class="card-video-overlay">
            <video muted width="100%" height="230" poster="{{ Storage::cloud()->url($post->cover()) }}" preload="none">
                <source src="{{ Storage::cloud()->url($post->featured()) }}">
                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.post', ['url' => $post->url]) }}"></a></p>
            </video>
        </div>
        <div class="card-user-img-transition card-img-video-overlay">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $post->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $post->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $post->user->username]) }}">{{ $post->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $post->category }}" background-color="{{ $post->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.post', ['url' => $post->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($post->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

@else
    <div>
        <div class="card card-shadow" style="max-width: 20rem">
            <div class="card-image-container">
                <a href="{{ route('show.post', ['url' => $post->url]) }}">
                    <img class="card-img-top" src="{{ Storage::cloud()->url($post->cover()) }}" alt="{{ $post->title }}">
                </a>
            </div>
            <div class="card-body card-body-post">
                <div class="card-meta-info">
                    <div class="card-user-img">
                        <a href="#"><img class="avatar-pic" src="{{ $post->user->avatarUrl() }}"/></a>
                    </div>
                    <div class="card-user-text">
                        <a class="a-no-decoration" href="#">{{ $post->user->username }}</a>
                    </div>
                    <div class="card-category">
                        <category-button category="{{ $post->category }}" background-color="{{ $post->category->backgroundColor() }}"></category-button>
                    </div>
                </div>
                <div class="card-info">
                    <a href="{{ route('show.post', ['url' => $post->url]) }}" class="a-no-decoration"><h5 class="card-title">{{ Str::limit($post->title, $limit = 50, $end = '...') }}</h5></a>
                </div>
            </div>
            <div class="card-footer bg-light card-f"> -->
                <!--<div class="card-footer-icons">
                    @svg('heart', 'heart-icon')
                </div>-->
                <div class="card-footer-more">
                    <div class="float-left card-footer-date">
                        <span>{{$post->createdAt()}}</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle-comment btn-no-padding float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    </div>
@endif