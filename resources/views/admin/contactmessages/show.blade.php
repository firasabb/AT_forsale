@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$message->name}}</div>

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

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input class="form-control enabled-disabled" type="text" name="name"  value="{{ $message->title }}" placeholder="Name" disabled/>
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <input class="form-control enabled-disabled" type="text" name="url"  value="{{ $message->sender_email }}" placeholder="Url" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div>
                                <input class="form-control enabled-disabled" type="text" name="link"  value="{{ $message->sender_name }}" placeholder="Url" disabled/>
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <textarea class="form-control enabled-disabled" name="description" disabled>{{$message->body}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row info-row">
                        <div class="col">
                            <h5>Created at:</h1>
                            <p>{{ $message->created_at }}</p>
                            <h5>Updated at:</h1>
                            <p>{{ $message->updated_at }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="block-button">
                <form action="{{ route('admin.delete.contactmessage', ['id' => $message->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">Delete Message</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
