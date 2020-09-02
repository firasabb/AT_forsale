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

    @if(!empty($post))
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">{{ __('main.Posts to approve') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.approve.post', ['id' => $post->id]) }}" id="add-post-form">
                        @csrf
                        <div class="form-group row">
                            <label for="post" class="col-sm-2 col-form-label">{{ __('main.post') }}:</label>
                            <div class="col-sm-10">
                                <input class="form-control enabled-disabled" name="title" disabled type="text" value="{{ $post->title }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-2 col-form-label">{{ __('main.description') }}:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control enabled-disabled" name="description" disabled>{{ $post->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-2 col-form-label">{{ __('main.URL') }}:</label>
                            <div class="col-sm-10">
                                <input class="form-control enabled-disabled" name="url" disabled type="text" value="{{ $post->url }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-sm-2 col-form-label">{{ __('main.category') }}:</label>
                            <div class="col-sm-10">
                                <select class="form-control enabled-disabled" name="category_id" disabled>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" <?php echo $post->category->id == $category->id ? 'Selected' : ''; ?>>{{ $category->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tags" class="col-sm-2 col-form-label">{{ __('main.tags') }}:</label>
                            <div class="col-sm-10">
                                <div class="selected-tags">
                                    <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                    @foreach($post->tags as $tag)
                                        <li class="list-group-item list-group-item-primary selected-tags-li">{{ $tag->name }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                                <div class="tag-container">
                                    <input type="hidden" name="tags" id="hidden-tag-input" value="<?php $i=0; foreach($post->tags as $tag){ $i++; if($i < count($post->tags)){echo $tag->name . ', ';} else { echo $tag->name; }} ?>"/>
                                    <input class="form-control" id="tag-input" type="text" required/>
                                </div>
                                <ul id="tags" class="list-group">
                            </div>
                        </div>
                        <p>Options:</p>
                        <div class="row">
                            @foreach($post->downloads as $key => $download)
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
                    @if(!empty($cover))
                        <img class="img-thumbnail" src="{{ $cover->public_url }}" style="max-width: 200px"/>
                    @endif
                    <a target="_blank" href="{{ route('show.post', ['url' => $post->url]) }}">{{__('main.View The Post')}}</a>
                </div>
            </div>
            <div class="block-button">
                <button id="add-post" type="button" class="btn btn-primary btn-lg btn-block">{{ __('main.approve') }}</button>
                <button id="edit-button" type="button" class="btn btn-success btn-lg btn-block">{{ __('main.edit') }}</button>
                <div class="delete-post-container">
                    <form method="POST" action="{{ route('admin.disapprove.post', ['id' => $post->id]) }}" id="delete-post">
                        @csrf
                        <button id="delete-post" type="submit" class="btn btn-danger btn-lg btn-block">{{ __('main.disapprove') }}</button>
                    </form>
                </div>
            </div>
            

        </div>
    </div>
    @else
        <div class="text-center">
            <p>{{ __('main.Nothing to approve')}}...</p>
        </div>
    @endif

</div>
@endsection
