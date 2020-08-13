@extends('layouts.panel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    <div class="card-columns">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h4>{{ $activeUsers }}</h4>
                </div>
                <div class="text-center">
                    <h4>Active Users</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
