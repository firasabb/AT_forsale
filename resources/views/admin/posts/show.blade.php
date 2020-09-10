@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><a href="{{ route('show.post', ['url' => $post->url]) }}">{{$post->title}}</a></div>

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
                        <h5>Added by: <a href="{{ url('admin/dashboard/user/' . $post->user->id) }}">{{ $post->user->username }}</a></h5>
                    </div>

                    <form method="POST" action="{{ route('admin.edit.post', ['id' => $post->id]) }}" class="edit-form-confirm" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="title">{{ __('main.post') }}:</label>
                                    <input class="form-control enabled-disabled" type="text" name="title"  value="{{ $post->title }}" placeholder="{{ __('main.title') }}" disabled/>
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <label for="url">{{ __('main.url') }}:</label>
                                    <input class="form-control enabled-disabled" type="text" name="url"  value="{{ $post->url }}" placeholder="{{ __('main.URL') }}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="description">{{ __('main.description') }}:</label>
                                    <textarea class="form-control enabled-disabled" name="description" disabled placeholder="{{ __('main.description') }}">{{ $post->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="license">{{ __('main.license') }}:</label>
                                <select class="form-control enabled-disabled" name="license_id" disabled>
                                    @foreach($licenses as $license)
                                        <option value="{{ $license->id }}" <?php echo $license->id == $post->license_id ? 'Selected' : ''; ?>>{{ $license->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="category">{{ __('main.category') }}:</label>
                                <select class="form-control enabled-disabled" name="category_id" disabled>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" <?php echo $category->id == $post->category_id ? 'Selected' : ''; ?>>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row edit-tags">
                            <div class="col">
                                <label for="tags">{{ __('main.tags') }}:</label>
                                <div class="selected-tags">
                                    <ul id="selected-tags-ul" class="selected-tags-ul list-group list-group-horizontal">
                                    @foreach($post->tags as $tag)
                                        <li class="list-group-item list-group-item-primary selected-tags-li">{{ $tag->name }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                                <div class="tag-container">
                                    <input type="hidden" name="tags" id="hidden-tag-input" value="<?php $i=0; foreach($post->tags as $tag){ $i++; if($i < count($post->tags)){echo $tag->name . ', ';} else { echo $tag->name; }} ?>"/>
                                    <input class="form-control enabled-disabled" id="tag-input" type="text" disabled/>
                                </div>
                                <ul id="tags" class="list-group">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>{{ __('main.featured') }}:</label>
                                <input type="file" name="featured" class="enabled-disabled" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>{{ __('main.cover') }}:</label>
                                <input type="file" name="cover" class="enabled-disabled" disabled>  
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
                        <div class="row">
                            <div class="col submit-btn-roles">
                                <button type="submit" class="btn btn-primary submit-edit-btn enabled-disabled" disabled>Submit</button>
                            </div>
                        </div>
                        <div class="row info-row">
                            <div class="col">
                                <h5>{{ __('main.Created at') }}:</h1>
                                <p>{{ $post->created_at }}</p>
                                <h5>{{ __('main.Updated at') }}:</h1>
                                <p>{{ $post->updated_at }}</p>
                            </div>
                            <div class="col">
                                <h5>{{ __('main.ID') }}:</h1>
                                <p>{{ $post->id }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">{{ __('main.edit') }}</button>
                <form action="{{ route('admin.delete.post', ['id' => $post->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">{{ __('main.delete') }}</button>
                </form>
            </div>

        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('main.downloads') }}:</a></div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>
                            {{ __('main.URL') }}
                        </th>
                        <th>
                            {{ __('main.size') }}
                        </th>
                        <th>
                            {{ __('main.mime') }}
                        </th>
                        <th>
                            {{ __('main.actions') }}
                        </th>
                    </tr>
                    @foreach($downloads as $download)
                        <tr>
                            <td>
                                <a href="{{ route('admin.download.download', ['id' => encrypt($download->id)]) }}" target="_blank">{{ $download->name }}</a>
                            </td>
                            <td>
                                <p>{{$download->getSize()}}B</p>
                            </td>
                            <td>
                                <p>{{$download->getMime()}}</p>
                            </td>
                            <td>
                                <form action="{{ route('admin.download.delete', ['id' => $download->id]) }}" method="POST" class="delete-form-confirm">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="block-button">
            <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#addModal">{{ __('main.Add Download') }}</button>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form method="POST" action="{{ route('admin.download.add', ['postId' => $post->id]) }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('main.Add Download') }}</h5>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('main.close') }}</button>
                        <button name="action" type="submit" class="btn btn-primary">{{ __('main.add') }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
