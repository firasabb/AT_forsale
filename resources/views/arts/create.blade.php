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
            <type obj="Art" types="{{ $types }}"></type>
            <div class="card">
                <div class="card-header">Add an Art</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('create.art') }}" class="needs-validation" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="arts-title">Art:</label>
                            <input class="form-control" type="text" name="title" placeholder="What is The Largest Galaxy in Our Universe?" value="{{ old('title') }}" required maxlength="200" minlength="15"/>
                            <div class="invalid-feedback">
                                    Please provide a valid art: maximum allowed number of characters is 300 and minimum number is 15.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="art-description">Description:</label>
                            <textarea class="form-control" type="text" name="description" placeholder="Add a description to your art (Optional)..." maxlength="500">{{ old('description') }}</textarea>
                            <div class="invalid-feedback">
                                    Please provide a valid description: maximum allowed number of characters is 1000.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="art-categories">Categories:</label>
                            <select name="categories[]" multiple class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">

                            <label for="art-tags">Tags:</label>
                            <div class="selected-tags">
                                <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                </ul>
                            </div>
                            <div class="tag-container">
                                <input type="hidden" name="tags" id="hidden-tag-input" value="{{ old('tags') }}"/>
                                <input class="form-control" id="tag-input" type="text"/>
                            </div>
                            <ul id="tags" class="list-group">
                        </div>
                        <div class="form-row featured-media">
                            <div class="form-group col-6">
                                <label for="art-featured">Featured Media:</label>
                                <input type="file" name="featured" >
                            </div>
                            <div class="form-group col-6">
                                <label for="art-cover">Thumbnail:</label>
                                <input type="file" name="cover" >
                            </div>
                        </div>
                        <div class="uploads">
                            <label for="art-uploads">Upload Your Files:</label>
                        </div>
                        <div class="create-art-btns">
                            <input type="hidden" name="type" id="type_field">
                            <button type="submit" class="btn btn-primary submit-art-btn">Submit</button>
                            <button type="button" class="btn btn-success add-download-btn" id="add-download">Add a File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
