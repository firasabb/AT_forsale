@extends('layouts.user')

@section('content')
<div class="container">
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
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}"/>
                            </div>
                            <div class="profile-text">
                                <div class="profile-name">
                                    <h3>{{ $user->username }}</h3>
                                </div>
                                <div class="profile-bio">
                                    <p>{{ $user->bio }}</p>
                                </div>
                                <div class="profile-numbers">
                                    <div class="text-center">
                                        <p>{{ $activeAssets->count() }}</p>
                                        <p>ASSETS</p>
                                    </div>
                                    <!--<div class="ml-4 text-center">
                                        <p>0</p>
                                        <p>FOLLOWERS</p>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="assets-container">
                    @if(!empty($activeAssets->first()))
                        <div class="assets-container-title text-center">
                            <h3>PUBLISHED ASSETS</h3>
                        </div>
                        <div class="card-columns">
                            @foreach($user->assets->all() as $asset)
                                <x-asset-card :asset="$asset"></x-asset-card>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
