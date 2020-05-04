@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.comments') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name="id" placeholder="ID" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type='number' name="asset_id" placeholder="Asset ID" class="form-control" value="{{ old('asset_id') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name="title" placeholder="Title" class="form-control" value="{{ old('title') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name="description" placeholder="Description" class="form-control" value="{{ old('description') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="Search" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Comments</div>

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

                    <table class="table">
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                Asset
                            </th>
                            <th class="td-actions">
                                Actions
                            </th>   
                        </tr>
                        @foreach ($comments as $comment)
                            <tr>
                                <td>
                                    {{$comment->id}}
                                </td>
                                <td>
                                    {{ $comment->title }}
                                </td>
                                <td>
                                    <a href="{{ route('show.asset', ['url' => $comment->asset->url]) }}">{{ $comment->asset->url }}</a>
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.comment', ['id' => $comment->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.comment', ['id' => $comment->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $comments->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
