@extends('layouts.app')

@section('content')
<div class="container">
    @if($isUser)
        @component('layouts.profileNavigation', ['user' => $user])
        @endcomponent
    @endif
    <div class="profile-container">
        <div class="row justify-content-center mt-5">
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="profile-container-header">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 profile-text-col">
                            <div class="profile-img">
                                <img src="{{ Storage::cloud()->url($user->avatar_url) }}" />
                            </div>
                            <div class="profile-text">
                                <div class="profile-name">
                                    <h3>{{ $user->name }}</h3>
                                </div>
                                <div class="profile-bio">
                                    <p>{{ $user->bio }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 profile-header-numbers">
                            <div class="profile-numbers text-center">
                                <h5>{{ count($user->assets->all()) }}</h5>
                                <h5>ASSETS</h5>
                            </div>
                            <div class="profile-numbers text-center">
                                <h5>0</h5>
                                <h5>POINTS</h5>
                            </div>
                            <div class="profile-numbers text-center">
                                <h5>0</h5>
                                <h5>LIKES</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="assets-container">
                    @if(!empty($user->assets->first()))
                        <div class="assets-container-title text-center">
                            <h3>PUBLISHED ASSETS</h3>
                        </div>
                        <div class="card-columns">
                            @foreach($user->assets->all() as $asset)
                            <a href="{{ route('show.asset', ['url' => $asset->url]) }}" target="_blank" class="card-link">
                                <div class="card" style="width: 18rem;">
                                    <img class="card-img-top" src="{{ Storage::cloud()->url($asset->covers->first()->url) }}" alt="{{ $asset->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $asset->title }}</h5>
                                        <p class="card-text">{{ $asset->description }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <div style="">
                                            <small class="text-muted"><span class="footer-category">{{ ucwords($asset->category->name) }}</span></small>
                                        </div>
                                        <div style="">
                                            <small class="text-muted">{{ $asset->created_at->toDateString() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
