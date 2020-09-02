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
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="profile-container-header">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 text-center mt-5 mb-5">
                            <h3>{{ __('passwords.change password') }}</h3>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-6 profile-text-col">
                            <form action="{{route('user.password.request', ['username' => $user->username])}}" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <p>{{ __('passwords.change password message') }}</p>
                                    <label for="old_password">{{ __('passwords.old password') }}:</label>
                                    <input name="old_password" class="form-control" type="password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">{{ __('passwords.new password') }}:</label>
                                    <input name="new_password" class="form-control" type="password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password_confirmation">{{ __('passwords.confirm new password') }}:</label>
                                    <input name="new_password_confirmation" class="form-control" type="password">
                                </div>
                                <input type="submit" class="btn btn-primary" value="{{ __('main.submit') }}">
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
