@extends('layouts.main')

@section('title', 'Upload an Asset')

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
                <div class="card-header">Rules and Requirements:</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item no-border">- Your work should be completed. Unfinished work will not get approved.</li>
                        <li class="list-group-item no-border">- Your work has never been licensed under a different license than Creative Commons.</li>
                        <li class="list-group-item no-border">- Your work should not contain any pornography or NSFW media.</li>
                        <li class="list-group-item no-border">- Your work has never been published under a different person's name.</li>
                        <li class="list-group-item no-border">- The maximum size of each file should not exceed 100 Migabytes.</li>
                    </ul>
                </div>
            </div>

            <div class="card card-shadow">
                <div class="card-header">Add Your {{ucwords($category->name)}}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('create.asset', ['category' => $category->url]) }}" class="needs-validation" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="assets-title">Title: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Type a beautiful and eye-catching text, which will be the title of your asset">?</span></label>
                            <input class="form-control" type="text" name="title" placeholder="What is The Largest Galaxy in Our Universe?" value="{{ old('title') }}" required maxlength="200" minlength="15"/>
                            <div class="invalid-feedback">
                                    Please provide a valid asset: maximum allowed number of characters is 300 and minimum number is 15.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset-description">Description: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Describe your asset story, features and uses.">?</span></label>
                            <textarea class="form-control" type="text" name="description" placeholder="Add a description to your asset..." required maxlength="500">{{ old('description') }}</textarea>
                            <div class="invalid-feedback">
                                    Please provide a valid description: maximum allowed number of characters is 1000.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="license">License:</label>
                            <p style="font-size:0.7rem;">You can read more about the licenses here: <a href="https://creativecommons.org/licenses/" target="_blank">Creative Commons</a></p>
                            <select name="license" class="form-control" id="license">
                                @foreach($licenses as $license)
                                    <option value="{{ $license->name }}">{{ $license->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="asset-tags">Tags: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Select top tags which matches your asset's concept, uses, features and subject. These tags can increase your asset's visitors and views.">?</span></label>
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
                        <div class="form-row pt-5">
                            <div class="col">
                                <p style="font-size: 0.7rem">*Upload a Featured Media File of Your Asset Which Will Work as A Preview... For example, Upload an MP3 or WAV for a Music or a Sound Effect Asset, an MP4 File for a Stock Video Asset, Or an JPG or PNG for a Stock Photo Asset...</p>
                            </div>
                        </div>
                        <div class="form-row featured-media">
                            <div class="custom-file form-group col-6">
                                <input type="file" name="featured" class="custom-file-input">
                                <label class="custom-file-label" for="asset-featured">Featured Media</label>
                            </div>
                            <div class="custom-file form-group col-6">
                                <input type="file" name="cover" class="custom-file-input">
                                <label class="custom-file-label" for="asset-featured">Cover Image</label>
                            </div>
                        </div>
                        <div class="uploads">
                            <label for="asset-uploads">Upload Your Files: <span class="info-questionmark" data-toggle="tooltip" data-placement="top" title="Here you can upload your files which are downloadable by the user, you can add from 1 to 5 files for your asset">?</span></label>
                            <div class="form-group upload-form-group">
                                <input type="file" name="uploads[0]" maxlength="200" placeholder="Option 1..."  />
                                <span class="delete-upload-btn">x</span>
                            </div>
                        </div>
                        <button type="button" class="btn add-download-btn" id="add-download">+ File</button>
                        <div class="create-asset-btns">
                            <input type="hidden" name="type" id="type_field">
                            <button type="submit" class="btn btn-primary submit-asset-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
