@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.pages') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type="number" name="id" placeholder="{{ __('main.ID') }}" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="name" placeholder="{{ __('main.name') }}" class="form-control" value="{{ old('name') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type="submit" value="{{ __('main.search') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('main.pages') }}</div>

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

                    <table class="table">
                        <tr>
                            <th>
                                <a href="{{ route('admin.index.pages', ['order' => 'id', 'desc' => !$desc]) }}">{!! $order == 'id' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.ID') }}</a>
                            </th>
                            <th>
                                <a href="{{ route('admin.index.pages', ['order' => 'title', 'desc' => !$desc]) }}">{!! $order == 'title' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.title') }}</a>
                            </th>
                            <th>
                                {{ __('main.URL') }}
                            </th>
                            <th>
                                <a href="{{ route('admin.index.pages', ['order' => 'status', 'desc' => !$desc]) }}">{!! $order == 'status' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.status') }}</a>
                            </th>
                            <th>
                                <a href="{{ route('admin.index.pages', ['order' => 'created_at', 'desc' => !$desc]) }}">{!! $order == 'created_at' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.created') }}</a>
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($pages as $page)
                            <tr>
                                <td>
                                    {{ $page->id }}
                                </td>
                                <td>
                                    {{ strtoupper($page->title) }}
                                </td>
                                <td>
                                    {{ $page->url }}
                                </td>
                                <td>
                                    {{ strtoupper($page->status) }}
                                </td>
                                <td>
                                    {{ $page->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.page', ['id' => $page->id]) }}" class="btn btn-success">{{ __('main.show/edit') }}</a>
                                        <form action="{{ route('admin.delete.page', ['id' => $page->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $pages->links() }}
                </div>
            </div>
            <div class="block-button">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">{{ __('main.add') }}</button>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.page') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('main.add') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="title"  value="{{ old('title') }}" placeholder="{{ __('main.title') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="url"  value="{{ old('url') }}" placeholder="{{ __('main.URL') }}" />
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
</div>
@endsection
