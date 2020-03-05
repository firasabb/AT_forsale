@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><a href="{{ route('show.art', ['url' => $art->url]) }}">{{$art->title}}</a></div>

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
                        <h5>Added by: <a href="{{ url('admin/dashboard/user/' . $art->user->id) }}">{{ $art->user->name }}</a></h5>
                    </div>

                    <form method="POST" action="{{ route('admin.edit.art', ['id' => $art->id]) }}" id="edit-form-arts">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="title">Art:</label>
                                    <input class="form-control enabled-disabled" type="text" name="title"  value="{{ $art->title }}" placeholder="Title" disabled/>
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <label for="url">URL:</label>
                                    <input class="form-control enabled-disabled" type="text" name="url"  value="{{ $art->url }}" placeholder="Url" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control enabled-disabled" name="description" disabled>{{ $art->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="type">Type:</label>
                                <select class="form-control enabled-disabled" name="type_id" disabled>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" <?php echo $type->id == $art->type_id ? 'Selected' : ''; ?>>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row edit-tags">
                            <div class="col">
                                <label for="tags">Tags:</label>
                                <div class="selected-tags">
                                    <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                    @foreach($art->tags as $tag)
                                        <li class="list-group-item list-group-item-primary selected-tags-li">{{ $tag->name }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                                <div class="tag-container">
                                    <input type="hidden" name="tags" id="hidden-tag-input" value="<?php $i=0; foreach($art->tags as $tag){ $i++; if($i < count($art->tags)){echo $tag->name . ', ';} else { echo $tag->name; }} ?>"/>
                                    <input class="form-control enabled-disabled" id="tag-input" type="text" disabled/>
                                </div>
                                <ul id="tags" class="list-group">
                            </div>
                        </div>
                        <div class="row">
                            @if(!empty($featured))
                                <div class="col">
                                    <img src="{{ $featured->public_url }}">
                                </div>
                            @endif
                            @if(!empty($cover))
                                <div class="col">
                                    <img src="{{ $cover->public_url }}">
                                </div>
                            @endif
                        </div>
                        @if(!empty($downloads))
                        <div class="mt-5 mb-2">
                            <h5>Downloads:</h5>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col submit-btn-roles">
                                <button type="submit" class="btn btn-primary submit-edit-btn enabled-disabled" disabled>Submit</button>
                            </div>
                        </div>
                        <div class="row info-row">
                            <div class="col">
                                <h5>Created at:</h1>
                                <p>{{ $art->created_at }}</p>
                                <h5>Updated at:</h1>
                                <p>{{ $art->updated_at }}</p>
                            </div>
                            <div class="col">
                                <h5>ID:</h1>
                                <p>{{ $art->id }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">Edit Art</button>
                <form action="{{ route('admin.delete.art', ['id' => $art->id]) }}" method="POST" id="delete-form-arts" class="delete-form-2">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">Delete Art</button>
                </form>
            </div>

        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
            <div class="card-header">Downloads:</a></div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>
                            URL
                        </th>
                        <th>
                            Size
                        </th>
                        <th>
                            Mime
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                    @foreach($downloads as $download)
                        <tr>
                            <td>
                                <a href="{{ route('download.download', ['id' => $download->id]) }}" target="_blank">{{ $download->name }}</a>
                            </td>
                            <td>
                                <p>{{$download->getSize()}}B</p>
                            </td>
                            <td>
                                <p>{{$download->getMime()}}</p>
                            </td>
                            <td>
                                <form action="{{ route('admin.download.delete', ['id' => $download->id]) }}" method="POST" id="delete-form-downloads">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="block-button">
            <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#addModal">Add Download</button>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form method="POST" action="{{ route('admin.download.add', ['artId' => $art->id]) }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Download</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="file" name="upload" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button name="action" type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
