@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col search-col">
            <form method="post" action="{{ route('admin.search.tags') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="{{ __('main.ID') }}" class="form-control" value="{{ Request::get('id') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='name' placeholder="{{ __('main.name') }}" class="form-control" value="{{ Request::get('name') ?? '' }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="{{ __('main.search') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('main.tags') }}</div>

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
                                <a class="a-no-decoration"  href="{{ route('admin.index.tags', ['order' => 'id', 'desc' => !$desc]) }}">{!! $order == 'id' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.ID') }}</a>
                            </th>
                            <th>
                                <a class="a-no-decoration"  href="{{ route('admin.index.tags', ['order' => 'name', 'desc' => !$desc]) }}">{!! $order == 'name' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.name') }}</a>
                            </th>
                            <th>
                                {{ __('main.URL') }}
                            </th>
                            <th>
                                {{ __('main.categories') }}
                            </th>
                            <th>
                                {{ __('main.created') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    {{$tag->id}}
                                </td>
                                <td>
                                    {{ strtoupper($tag->name) }}
                                </td>
                                <td>
                                    {{ $tag->url }}
                                </td>
                                <td>
                                    @if(!empty($tag->categories))
                                        @foreach($tag->categories as $category)
                                            {{ $category->name }},
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    {{ $tag->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.tag', ['id' => $tag->id]) }}" class="btn btn-success">{{ __('main.show/edit') }}</a>
                                        <form action="{{ route('admin.delete.tag', ['id' => $tag->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $tags->links() }}
                </div>
            </div>
            <div class="block-button pt-4">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">{{ __('main.Add Tag') }}</button>
            </div>
            <div class="block-button py-4">
                <a href="{{ route('admin.bulk.add.form.tags') }}" class="btn btn-secondary btn-lg btn-block">{{ __('main.Add Tags') }}</a>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.tag') }}">
                            {!! csrf_field() !!}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('main.Add Tag') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="name"  value="{{ old('name') }}" placeholder="Name" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="url"  value="{{ old('url') }}" placeholder="Url" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="categories[]" multiple>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
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
