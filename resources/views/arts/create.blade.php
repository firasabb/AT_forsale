@extends('layouts.app')

@section('content')
<div class="container">
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
            <div class="card card-shadow">
                <div class="card-header">Add Your {{ucwords($category->name)}}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('create.art', ['category' => $category->url]) }}" class="needs-validation" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="arts-title">Title: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Type a beautiful and eye-catching text, which will be the title of your art">?</span></label>
                            <input class="form-control" type="text" name="title" placeholder="What is The Largest Galaxy in Our Universe?" value="{{ old('title') }}" required maxlength="200" minlength="15"/>
                            <div class="invalid-feedback">
                                    Please provide a valid art: maximum allowed number of characters is 300 and minimum number is 15.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="art-description">Description: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Describe your art story, features and uses.">?</span></label>
                            <textarea class="form-control" type="text" name="description" placeholder="Add a description to your art..." required maxlength="500">{{ old('description') }}</textarea>
                            <div class="invalid-feedback">
                                    Please provide a valid description: maximum allowed number of characters is 1000.
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="art-tags">Tags: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Select top tags which matches your art's concept, uses, features and subject. These tags can increase your art's visitors and views.">?</span></label>
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
                            <div class="form-group col-6">
                                <label for="art-featured">Featured Media: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Upload a corresponding media file, for example, a photo if you are art is an image or a logo, an audio file if your art is a song... That will help the viewer to get the idea of your art">?</span></label>
                                <input type="file" name="featured" class="form-control-file">
                            </div>
                            <div class="form-group col-6">
                                <label for="art-featured">Cover: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Upload an image which is going to be the cover of your art">?</span></label>
                                <input type="file" name="cover" class="form-control-file">
                            </div>
                        </div>
                        <div class="uploads">
                            <label for="art-uploads">Upload Your Files: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="These are the files which going to be downloadable by the user, you can add from 1 to 5 files for your art">?</span></label>
                            <div class="form-group upload-form-group">
                                <input type="file" name="uploads[0]" maxlength="200" placeholder="Option 1..."  />
                                <span class="delete-upload-btn">x</span>
                            </div>
                        </div>
                        <button type="button" class="btn add-download-btn" id="add-download">+ File</button>
                        <div class="create-art-btns">
                            <input type="hidden" name="type" id="type_field">
                            <button type="submit" class="btn btn-primary submit-art-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
