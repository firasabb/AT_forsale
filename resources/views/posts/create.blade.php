@extends('layouts.main')

@section('title', __('main.upload') )

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

            <div class="card mb-5">
                <div class="card-header">{{ __('main.Rules and Requirements') }}:</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item no-border">- {{ __('main.Rule 1') }}</li>
                        <li class="list-group-item no-border">- {{ __('main.Rule 2') }}</li>
                        <li class="list-group-item no-border">- {{ __('main.Rule 3') }}</li>
                        <li class="list-group-item no-border">- {{ __('main.Rule 4') }}</li>
                        <li class="list-group-item no-border">- {{ __('main.Rule 5') }}</li>
                    </ul>
                </div>
            </div>

            <div class="card card-shadow">
                <div class="card-header">{{ __('main.upload') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('create.post', ['category' => $category->url]) }}" class="needs-validation" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="posts-title">Title: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Type a beautiful and eye-catching text, which will be the title of your post">?</span></label>
                            <input class="form-control" type="text" name="title" placeholder="What is The Largest Galaxy in Our Universe?" value="{{ old('title') }}" required maxlength="200" minlength="15"/>
                            <div class="invalid-feedback">
                                {{ __('main.invalid post title') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post-description">Description: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Describe your post story, features and uses.">?</span></label>
                            <textarea class="form-control" type="text" name="description" placeholder="Add a description to your post..." maxlength="500">{{ old('description') }}</textarea>
                            <div class="invalid-feedback">
                                {{ __('main.invalid post description') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="license">License:</label>
                            <select name="license" class="form-control" id="license">
                                @foreach($licenses as $license)
                                    <option value="{{ $license->name }}">{{ $license->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="post-tags">Tags:</label>
                            <div class="selected-tags">
                                <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                </ul>
                            </div>
                            <div class="tag-container">
                                <input type="hidden" name="tags" id="hidden-tag-input" value="{{ old('tags') }}"/>
                                <input class="form-control" id="tag-input" type="text" data-category="{{ $category->id }}"/>
                            </div>
                            <ul id="tags" class="list-group">
                        </div>
                        <div class="form-row featured-media">
                            @if($category->name != 'stock photos')
                                <div class="custom-file form-group col-6">
                                    <input type="file" name="featured" class="custom-file-input">
                                    <label class="custom-file-label" for="post-featured">Featured Media</label>
                                </div>
                            @endif
                            <div class="custom-file form-group col-6">
                                <input type="file" name="cover" class="custom-file-input">
                                <label class="custom-file-label" for="post-featured">Cover Image</label>
                            </div>
                        </div>
                        <div class="uploads">
                            <label for="post-uploads">{{ __('main.upload files here') }}:</label>
                            <div class="form-group upload-form-group">
                                <input type="file" name="uploads[0]" maxlength="200" />
                                <span class="delete-upload-btn">x</span>
                            </div>
                        </div>
                        <button type="button" class="btn add-download-btn" id="add-download">{{ __('main.+ file') }}</button>
                        <div class="create-post-btns">
                            <input type="hidden" name="type" id="type_field">
                            <button type="submit" class="btn btn-primary submit-post-btn">{{ __('main.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
