@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('main.permissions') }}</div>

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
                                {{ __('main.ID') }}
                            </th>
                            <th>
                                {{ __('main.name') }}
                            </th>
                            <th>
                                {{ __('main.roles') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    {{$permission->id}}
                                </td>
                                <td>
                                    {{ strtoupper($permission->name) }}
                                </td>
                                <td>
                                    @foreach($permission->roles as $role)
                                        {{ $role->name }} <br>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('admin.show.permission', ['id' => $permission->id]) }}" class="btn btn-success">{{ __('main.show/edit') }}</a>
                                    <form action="{{ route('admin.delete.permission', ['id' => $permission->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $permissions->links() }}
                </div>
            </div>
            <div class="block-button">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">{{ __('main.add') }}</button>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.permission') }}">
                            {!! csrf_field() !!}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('main.permission') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="name"  value="{{ old('name') }}" placeholder="{{ __('main.name') }}" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <select multiple class="form-control" id="permissionsSelect" name="roles[]">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
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
