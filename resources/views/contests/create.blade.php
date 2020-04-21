@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Start Your Contest</div>

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

                    <form method="POST" action="{{ route('create.contest', ['category' => $category->url]) }}" class="needs-validation" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="contests-title">Contest Title:</label>
                            <input class="form-control" type="text" name="title" placeholder="{{ ucwords($category->name) }} for my company" value="{{ old('title') }}" required maxlength="200" minlength="15"/>
                        </div>
                        <div class="form-group">
                            <label for="contest-description">Description:</label>
                            <textarea class="form-control" type="text" name="description" placeholder="Please describe your contest, rules, requirements, ..." maxlength="500">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">

                            <label for="contest-tags">Tags:</label>
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
                        <div class="uploads">
                            <label for="contest-uploads">Add Attachments:</label>
                        </div>
                        <div class="create-contest-btns">
                            <button type="submit" class="btn btn-primary submit-contest-btn">Submit</button>
                            <button type="button" class="btn btn-success add-download-btn" id="add-download">Add a File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
