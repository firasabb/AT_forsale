@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.arts') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="ID" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='title' placeholder="Art Title" class="form-control" value="{{ old('title') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='url' placeholder="Art URL" class="form-control" value="{{ old('url') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value='search' class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Arts</div>

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
                                ID
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                URL
                            </th>
                            <th>
                                Comments
                            </th>
                            <th class="td-actions">
                                Actions
                            </th>   
                        </tr>
                        @foreach ($arts as $art)
                            <tr>
                                <td>
                                    {{$art->id}}
                                </td>
                                <td>
                                    {{ $art->title }}
                                </td>
                                <td>
                                    <a href="{{ route('show.art', ['url' => $art->url]) }}">{{ str_limit($art->url, $limit = 20, $end = '...') }}</a>
                                </td>
                                <td>
                                    {{ $art->comments()->count() }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.art', ['id' => $art->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.art', ['id' => $art->id]) }}" method="POST" id="delete-form-tags" class="delete-form-1">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $arts->links() }}
                </div>
            </div>
            <div class="block-button">
                <a href="{{route('create.art')}}" target="_blank" class="btn btn-primary btn-lg btn-block">Add Art</a>
            </div>

        </div>
    </div>
</div>
@endsection
