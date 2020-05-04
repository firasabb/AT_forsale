@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$comment->title}}</div>

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

                    <div class="text-center p-3">
                        <h5>Asked by: <a href="{{ url('admin/dashboard/user/' . $comment->user->id) }}">{{ $comment->user->name }}</a></h5>
                    </div>

                    <form method="POST" action="{{ route('admin.edit.comment', ['id' => $comment->id]) }}" class="edit-form-confirm">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        
                                <div class="text-center p-3">
                                    <h5>Asset: <a href="{{ route('show.asset', ['url' => $comment->asset->url]) }}">{{ $comment->asset->title }}</a></h5>
                                </div>
                            
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input class="form-control enabled-disabled" type="text" name="title"  value="{{ $comment->title }}" placeholder="Title" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <textarea class="form-control enabled-disabled" name="description" disabled>{{ $comment->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col submit-btn-roles">
                                <button type="submit" class="btn btn-primary submit-edit-btn enabled-disabled" disabled>Submit</button>
                            </div>
                        </div>
                        <div class="row info-row">
                            <div class="col">
                                <h5>Created at:</h1>
                                <p>{{ $comment->created_at }}</p>
                                <h5>Updated at:</h1>
                                <p>{{ $comment->updated_at }}</p>
                            </div>
                            <div class="col">
                                <h5>ID:</h1>
                                <p>{{ $comment->id }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">Edit Comment</button>
                <form action="{{ route('admin.delete.comment', ['id' => $comment->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">Delete Comment</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
