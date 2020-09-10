@extends('layouts.user')

@section('content')

<div class="container">
    <div> 
        <div class="mt-5">
            <form method="POST" action="{{ route('user.setup.request') }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
                <div class="profile-container">
                    <div class="row justify-content-center profile-first-row">
                        <div class="col">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="profile-setup-info">
                                <div class="row justify-content-center profile-picture-container">
                                    <div class="col" style="border-bottom: solid 1px #e6e6e6">
                                        <img src="{{ $user->avatarUrl() }}" class="profile-img-setup"/>
                                        <div class="text-center mt-3 mb-5">
                                            <h5>{{ $user->first_name . ' ' . $user->last_name }}</h5>
                                            <h5>{{ $user->username }}</h5>
                                        </div>
                                    </div>
                                        
                                </div>
                                <div class="row justify-content-center profile-info-container">
                                    <div class="col">
                                        <div class="form-row justify-content-center">
                                            <div class="col-md-6">
                                                <label for="profile picture">{{ __('main.profile picture') }}:</label>
                                                <input id="profile-picture-select" name="profile_picture" type="file">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="name">{{ __('main.first name') }}:</label>
                                                <input name="first_name" type="text" value="{{ $user->first_name }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="name">{{ __('main.last name') }}:</label>
                                                <input name="last_name" type="text" value="{{ $user->last_name }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="bio">{{ __('main.bio') }}:</label>
                                                <textarea name="bio" class="form-control">{{ $user->bio }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="email">{{ __('main.email') }}:</label>
                                                <input name="email" class="form-control" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="paypal">{{ __('main.paypal') }}:</label>
                                                <input name="paypal" class="form-control" value="{{ $user->paypal }}" placeholder="If you would like to receive donations, add your Paypal email.">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="links-container">
                        <div class="links-container-title text-center">
                            <h4>My Links</h4>
                        </div>
                        <div class="row links-container-row">
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('instagram') }}; ">{{ __('main.instagram') }}</div>
                                                </div>
                                                <input class="form-control" name="instagram_link" value="{{ $instagram['url'] ?? '' }}" placeholder="@username">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('facebook') }};">{{ __('main.facebook') }}</div>
                                                </div>
                                                <input class="form-control" name="facebook_link" value="{{ $facebook['url'] ?? '' }}" placeholder="@username">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('github') }};">{{ __('main.github') }}</div>
                                                </div>
                                                <input class="form-control" name="github_link" value="{{ $github['url'] ?? '' }}" placeholder="https://www.github.com/username">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('youtube') }};">{{ __('main.youtube') }}</div>
                                                </div>
                                                <input class="form-control" name="youtube_link" value="{{ $youtube['url'] ?? '' }}" placeholder="https://www.youtube.com/channel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('') }};">{{ __('main.website') }}</div>
                                                </div>
                                                <input class="form-control" name="website_link" value="{{ $website['url'] ?? '' }}" placeholder="https://www.example.com">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: {{ UserLink::userLinkColor('portfolio') }};">{{ __('main.behance') }}</div>
                                                </div>
                                                <input class="form-control" name="portfolio_link" value="{{ $portfolio['url'] ?? '' }}" placeholder="https://www.behance.net/example">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="setup-btn-container pb-5">
                    <button type="submit" class="btn btn-blue btn-lg btn-block">{{ __('main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
