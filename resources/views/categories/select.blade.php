@extends('layouts.main')

@section('title', 'Upload a Post')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col">
            @if ($errors->any() || @session('status'))
                <div class="card">
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
                    </div>
                </div>
            @endif
            <category obj="Post" categories="{{ json_encode($categories) }}"></category>
        </div>
    </div>
</div>
@endsection
