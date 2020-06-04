@extends('layouts.panel')


@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-12 py-5">
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


    @if(!$ads->isEmpty())
    @php
        $ad = $ads->first();
        $content = unserialize($ad->content);
    @endphp
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">User Ads to approve</div>

                <div class="card-body">
                        <form method="POST" action="{{ route('admin.approve.userad', ['id' => $ad->id]) }}" id="add-userad-form">
                            @csrf
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Title:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="header_title" disabled type="text" value="{{ $content['header_title'] }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="appreciation_msg" class="col-sm-2 col-form-label">Appreciation Message:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="appreciation_msg" disabled type="text" value="{{ $content['appreciation_msg'] }}"/>
                                </div>
                            </div>

                        </form>
                </div>
            </div>

            <x-user-ad :user="$ad->user" :user-ad="$ad" show-as-modal="0"></x-user-ad>


            <div class="block-button">
                <button id="add-userad" type="button" class="btn btn-primary btn-lg btn-block">Approve</button>
                <button id="edit-button" type="button" class="btn btn-success btn-lg btn-block">Edit</button>
                <div class="delete-userad-container">
                    <form method="POST" action="{{ route('admin.disapprove.userad', ['id' => $ad->id]) }}" id="delete-userad">
                        @csrf
                        <button id="delete-userad" type="submit" class="btn btn-danger btn-lg btn-block">Disapprove</button>
                    </form>
                </div>
            </div>
            {{ $ads->links() }}
        </div>
    </div>
    @else
        <div class="text-center">
            <p>Nothing to approve.</p>
        </div>
    @endif
</div>
@endsection
