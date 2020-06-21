@php
    
    $visualArr = ['stock photos', 'logos', 'icons', 'illustrations'];
    $audioArr = ['sound effects', 'music'];
    $videoArr = ['stock videos', 'intro'];
    $categoryName = $asset->category->name;

@endphp

@if(in_array($categoryName, $visualArr))
    <div class="card card-shadow" style="max-width: 20rem">
        <a href="{{ route('show.asset', ['url' => $asset->url]) }}">
            <img class="card-img card-img-top" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
        </a>
        <div class="card-img-overlay card-user-img-transition">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $asset->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}">{{ $asset->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $asset->category }}" background-color="{{ $asset->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.asset', ['url' => $asset->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($asset->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
@elseif(in_array($categoryName, $audioArr))

    <div class="card card-shadow" style="max-width: 20rem">
        <a href="{{ route('show.asset', ['url' => $asset->url]) }}">
            <img class="card-img card-img-top" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
        </a>
        <div class="card-img-overlay card-user-img-transition">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $asset->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}">{{ $asset->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $asset->category }}" background-color="{{ $asset->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.asset', ['url' => $asset->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($asset->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="card-audio-overlay">
            <audio controls class="card-audio-overlay-audio">
                <source src="{{ Storage::cloud()->url($asset->featured()) }}">
                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.asset', ['url' => $asset->url]) }}"></a></p>
            </audio>
        </div>
    </div>

@elseif(in_array($categoryName, $videoArr))

    <div class="card card-shadow card-video" style="max-width: 20rem">
        <a href="{{ route('show.asset', ['url' => $asset->url]) }}">
            <img class="card-img card-img-top" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
        </a>
        <div class="card-video-overlay">
            <video muted width="100%" height="230" poster="{{ Storage::cloud()->url($asset->cover()) }}" preload="none">
                <source src="{{ Storage::cloud()->url($asset->featured()) }}">
                <p>Your browser doesn't support HTML5 audio. Download It <a class="a-no-decoration-white" href="{{ route('show.asset', ['url' => $asset->url]) }}"></a></p>
            </video>
        </div>
        <div class="card-user-img-transition card-img-video-overlay">
            <div class="card-overlay-upper">
                <div class="card-user-img">
                    <a href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}" target="_blank"><img class="avatar-pic" src="{{ $asset->user->avatarUrl() }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration-white" target="_blank" href="{{ route('user.profile.show', ['username' => $asset->user->username]) }}">{{ $asset->user->username }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $asset->category }}" background-color="{{ $asset->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <a href="{{ route('show.asset', ['url' => $asset->url]) }}" target="_blank" class="a-no-decoration">
                <div class="card-overlay-lower">
                    <div class="card-info">
                        <h5 class="card-title">{{ Str::limit($asset->title, $limit = 50, $end = '...') }}</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

@else
    <div>
        <div class="card card-shadow" style="max-width: 20rem">
            <a href="{{ route('show.asset', ['url' => $asset->url]) }}">
                <img class="card-img-top" src="{{ Storage::cloud()->url($asset->cover()) }}" alt="{{ $asset->title }}">
            </a>
            <div class="card-body card-body-asset">
                <div class="card-meta-info">
                    <div class="card-user-img">
                        <a href="#"><img class="avatar-pic" src="{{ $asset->user->avatarUrl() }}"/></a>
                    </div>
                    <div class="card-user-text">
                        <a class="a-no-decoration" href="#">{{ $asset->user->username }}</a>
                    </div>
                    <div class="card-category">
                        <category-button category="{{ $asset->category }}" background-color="{{ $asset->category->backgroundColor() }}"></category-button>
                    </div>
                </div>
                <div class="card-info">
                    <a href="{{ route('show.asset', ['url' => $asset->url]) }}" class="a-no-decoration"><h5 class="card-title">{{ Str::limit($asset->title, $limit = 50, $end = '...') }}</h5></a>
                </div>
            </div>
            <div class="card-footer bg-light card-f"> -->
                <!--<div class="card-footer-icons">
                    @svg('heart', 'heart-icon')
                </div>-->
                <div class="card-footer-more">
                    <div class="float-left card-footer-date">
                        <span>{{$asset->createdAt()}}</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle-comment btn-no-padding float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
@endif