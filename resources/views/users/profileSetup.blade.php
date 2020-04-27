@extends('layouts.app')

@section('content')

<div class="container">
    <div> 
        @component('layouts.profileNavigation', ['user' => $user])
        @endcomponent
        <div class="mt-5">
            <form method="POST" action="{{ route('user.profile.setup.request', ['username' => $user->username]) }}" enctype="multipart/form-data">
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
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <div class="text-center">
                                            <h3>INFO</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center profile-picture-container">
                                    <div class="col" style="border-bottom: solid 1px #e6e6e6">
                                        <img src="{{ $user->avatar_url }}" class="profile-img-setup"/>
                                        <div class="text-center mt-3 mb-5">
                                            <h5>{{ $user->name }}</h5>
                                            <h5>{{ $user->username }}</h5>
                                        </div>
                                    </div>
                                        
                                </div>
                                <div class="row justify-content-center profile-info-container">
                                    <div class="col">
                                        <div class="form-row justify-content-center">
                                            <div class="col-md-4 mb-3">
                                                <label for="profile picture">Profile Picture:</label>
                                                <input id="profile-picture-select" name="profile_picture" type="file">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="name">Name:</label>
                                                <input name="name" type="text" value="{{ $user->name }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center">
                                            <div class="col-md-6">
                                                <label for="bio">Bio:</label>
                                                <textarea name="bio" class="form-control">{{ $user->bio }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="email">Email:</label>
                                                <input name="email" class="form-control" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="form-row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="paypal">Paypal:</label>
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
                            <h4>LINKS</h4>
                        </div>
                        <div class="row links-container-row">
                            <div class="col-6">
                                <div class="link-container">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text white-input-group-text" style="background-color: #b571c8; ">Instagram</div>
                                                </div>
                                                <input class="form-control" name="instagram_link" value="{{ $instagram['url'] }}" placeholder="@username">
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
                                                    <div class="input-group-text white-input-group-text" style="background-color: #5393e6;">Facebook</div>
                                                </div>
                                                <input class="form-control" name="facebook_link" value="{{ $facebook['url'] }}" placeholder="@username">
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
                                                    <div class="input-group-text white-input-group-text" style="background-color: #656768;">Github</div>
                                                </div>
                                                <input class="form-control" name="github_link" value="{{ $github['url'] }}" placeholder="https://www.github.com/username">
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
                                                    <div class="input-group-text white-input-group-text" style="background-color: #db5f5f;">YouTube</div>
                                                </div>
                                                <input class="form-control" name="youtube_link" value="{{ $youtube['url'] }}" placeholder="https://www.youtube.com/channel">
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
                                                    <div class="input-group-text">Website</div>
                                                </div>
                                                <input class="form-control" name="website_link" value="{{ $website['url'] }}" placeholder="https://www.example.com">
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
                                                    <div class="input-group-text white-input-group-text" style="background-color: #6dbd9f;">Portfolio</div>
                                                </div>
                                                <input class="form-control" name="portfolio_link" value="{{ $portfolio['url'] }}" placeholder="https://www.myportfolio.com">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="setup-btn-container">
                    <button type="submit" class="btn btn-blue btn-lg btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
