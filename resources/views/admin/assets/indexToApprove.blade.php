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

    @if(!empty($asset))
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Assets to approve</div>

                <div class="card-body">
                        <form method="POST" action="{{ route('admin.approve.asset', ['id' => $asset->id]) }}" id="add-asset-form">
                            @csrf
                            <div class="form-group row">
                                <label for="asset" class="col-sm-2 col-form-label">Asset:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="title" disabled type="text" value="{{ $asset->title }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control enabled-disabled" name="description" disabled>{{ $asset->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-sm-2 col-form-label">URL:</label>
                                <div class="col-sm-10">
                                    <input class="form-control enabled-disabled" name="url" disabled type="text" value="{{ $asset->url }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-sm-2 col-form-label">Category:</label>
                                <div class="col-sm-10">
                                    <select class="form-control enabled-disabled" name="category_id" disabled>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" <?php echo $asset->category->id == $category->id ? 'Selected' : ''; ?>>{{ $category->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tags" class="col-sm-2 col-form-label">Tags:</label>
                                <div class="col-sm-10">
                                    <div class="selected-tags">
                                        <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                        @foreach($asset->tags as $tag)
                                            <li class="list-group-item list-group-item-primary selected-tags-li">{{ $tag->name }}</li>
                                        @endforeach
                                        </ul>
                                    </div>
                                    <div class="tag-container">
                                        <input type="hidden" name="tags" id="hidden-tag-input" value="<?php $i=0; foreach($asset->tags as $tag){ $i++; if($i < count($asset->tags)){echo $tag->name . ', ';} else { echo $tag->name; }} ?>"/>
                                        <input class="form-control" id="tag-input" type="text" required/>
                                    </div>
                                    <ul id="tags" class="list-group">
                                </div>
                            </div>
                            <p>Options:</p>
                            <div class="row">
                                @foreach($asset->downloads as $key => $download)
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
                                    <img src="{{ $asset->public_url }}">
                                </div>
                            </div>
                            @endif
                </div>
            </div>
            <div class="block-button">
                <button id="add-asset" type="button" class="btn btn-primary btn-lg btn-block">Approve</button>
                <button id="edit-button" type="button" class="btn btn-success btn-lg btn-block">Edit</button>
                <div class="delete-asset-container">
                    <form method="POST" action="{{ route('admin.delete.asset', ['id' => $asset->id]) }}" id="delete-asset">
                        @csrf
                        {!! method_field('DELETE') !!}
                        <button id="delete-asset" type="submit" class="btn btn-danger btn-lg btn-block">Delete</button>
                    </form>
                </div>
            </div>
            

        </div>
    </div>
    @else
        <div class="text-center">
            <p>Nothing to approve...</p>
        </div>
    @endif

</div>
@endsection
