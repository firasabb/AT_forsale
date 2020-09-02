@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Send a Custom Email') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <form action="{{ route('admin.send.email') }}" method="post">
                        @csrf
                            <div class="form-group">
                                <input class="form-control" type="email" name="reciever" placeholder="email@example.com" value="{{ old('reciever') }}">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="subject" placeholder="{{ __('main.subject') }}" value="{{ old('subject') }}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="body" placeholder="{{ __('main.Email Body') }}">{{ old('body') }}</textarea>
                            </div>
                            <button class="btn btn-primary">{{ __('main.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
