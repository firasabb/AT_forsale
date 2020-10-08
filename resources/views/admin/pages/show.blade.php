@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$page->title}}</div>

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

                    <form method="POST" action="{{ route('admin.edit.page', ['id' => $page->id]) }}" class="edit-form-confirm" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input class="form-control enabled-disabled" type="text" name="title"  value="{{ $page->title }}" placeholder="{{ __('main.title') }}" disabled/>
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <input class="form-control enabled-disabled" type="text" name="url"  value="{{ $page->url }}" placeholder="{{ __('main.url') }}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <textarea name="body" class="form-control enabled-disabled tinymce-textarea" disabled placeholder="{{ __('main.body') }}">{{ $page->body }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-control enabled-disabled" name="status" disabled>
                                        <option value="draft" {{ $page->status == 'draft' ? 'selected' : '' }}>{{ __('main.draft') }}</option>
                                        <option value="published" {{ $page->status == 'published' ? 'selected' : '' }}>{{ __('main.published') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col submit-btn-roles">
                                <button type="submit" class="btn btn-primary submit-edit-btn enabled-disabled" disabled>{{ __('main.submit') }}</button>
                            </div>
                        </div>
                        <div class="row info-row">
                            <div class="col">
                                <h5>{{ __('main.Created at') }}:</h1>
                                <p>{{ $page->created_at }}</p>
                                <h5>{{ __('main.Updated at') }}:</h1>
                                <p>{{ $page->updated_at }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">{{ __('main.edit') }}</button>
                <form action="{{ route('admin.delete.page', ['id' => $page->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">{{ __('main.delete') }}</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
