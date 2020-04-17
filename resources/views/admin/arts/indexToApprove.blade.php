@extends('layouts.panel')


@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Arts to approve</div>

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

                        @if(!empty($art))
                        <form method="POST" action="{{ route('admin.approve.art', ['id' => $art->id]) }}" id="add-art-form">
                            @csrf
                            <div class="form-group row">
                                <label for="art" class="col-sm-2 col-form-label">Art:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="title" disabled type="text" value="{{ $art->title }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control enabled-disabled" name="description" disabled>{{ $art->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-2 col-form-label">URL:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="url" disabled type="text" value="{{ $art->url }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-sm-2 col-form-label">Type:</label>
                                <div class="col-sm-10">
                                    <select class="form-control enabled-disabled" name="type_id" disabled>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" <?php echo $art->type->id == $type->id ? 'Selected' : ''; ?>>{{ $type->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tags" class="col-sm-2 col-form-label">Tags:</label>
                                <div class="col-sm-10">
                                    <div class="selected-tags">
                                        <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                        @foreach($art->tags as $tag)
                                            <li class="list-group-item list-group-item-primary selected-tags-li">{{ $tag->name }}</li>
                                        @endforeach
                                        </ul>
                                    </div>
                                    <div class="tag-container">
                                        <input type="hidden" name="tags" id="hidden-tag-input" value="<?php $i=0; foreach($art->tags as $tag){ $i++; if($i < count($art->tags)){echo $tag->name . ', ';} else { echo $tag->name; }} ?>"/>
                                        <input class="form-control" id="tag-input" type="text" required/>
                                    </div>
                                    <ul id="tags" class="list-group">
                                </div>
                            </div>
                            <p>Options:</p>
                            <div class="row">
                                @foreach($art->downloads as $key => $download)
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <input class="form-control enabled-disabled" name="options[{{ $key }}]" type="text" disabled value="{{ $download->name }}" />
                                        </div>
                                    </div>
                                    
                                @endforeach
                            </div>

                        </form>
                        @if(!empty($featured))
                        <div class="row">
                            <div class="col">
                                <img src="{{ $art->public_url }}">
                            </div>
                        </div>
                        @endif
                </div>
            </div>
            <div class="block-button">
                <button id="add-art" type="button" class="btn btn-primary btn-lg btn-block">Approve</button>
                <button id="edit-button" type="button" class="btn btn-success btn-lg btn-block">Edit</button>
                <div class="delete-art-container">
                    <form method="POST" action="{{ route('admin.delete.art', ['id' => $art->id]) }}" id="delete-art">
                        @csrf
                        {!! method_field('DELETE') !!}
                        <button id="delete-art" type="submit" class="btn btn-danger btn-lg btn-block">Delete</button>
                    </form>
                    @else
                        <p>Nothing to approve.</p>
                    @endif
                </div>
            </div>
            

        </div>
    </div>
</div>
@endsection
