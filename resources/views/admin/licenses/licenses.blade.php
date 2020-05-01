@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col search-col">
            <form method="post" action="{{ route('admin.search.licenses') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="ID" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='name' placeholder="License Name" class="form-control" value="{{ old('name') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value='search' class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">Licenses</div>

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
                                Name
                            </th>
                            <th>
                                URL
                            </th>
                            <th>
                                Link
                            </th>
                            <th class="td-actions">
                                Actions
                            </th>   
                        </tr>
                        @foreach ($licenses as $license)
                            <tr>
                                <td>
                                    {{$license->id}}
                                </td>
                                <td>
                                    {{ strtoupper($license->name) }}
                                </td>
                                <td>
                                    {{ $license->url }}
                                </td>
                                <td>
                                    <a href="{{ $license->link }}">{{ Str::limit($license->link, $limit = 20, $end = '...') }}</a>
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.license', ['id' => $license->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.license', ['id' => $license->id]) }}" method="POST" id="delete-form-licenses" class="delete-form-1">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $licenses->links() }}
                </div>
            </div>
            <div class="block-button">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">Add License</button>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.license') }}">
                            {!! csrf_field() !!}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add License</h5>
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
                                        <input class="form-control" type="text" name="link"  value="{{ old('link') }}" placeholder="Link" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button name="action" type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
