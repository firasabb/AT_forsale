@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{strtoupper($role->name)}}</div>

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

                    <form method="POST" action="{{ route('admin.edit.role', ['id' => $role->id]) }}" class="edit-form-confirm">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input class="form-control enabled-disabled" type="text" name="name"  value="{{ strtoupper($role->name) }}" placeholder="{{ __('main.name') }}" disabled/>
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <select multiple class="form-control enabled-disabled" id="rolesSelect" name="permissions[]" disabled>
                                        @foreach($permissions as $permission)
                                            <option <?php $role->hasPermissionTo($permission->name) ? print('selected') : print(' ') ?> value="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
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
                                <h4>{{ __('main.permissions') }}:</h4>
                                @foreach($role->permissions as $permission)
                                    <p>{{ $permission->name }}</p>
                                @endforeach
                            </div>
                            <div class="col">
                                <h5>{{ __('main.Created at') }}:</h1>
                                <p>{{ $role->created_at }}</p>
                                <h5>{{ __('main.Updated at') }}:</h1>
                                <p>{{ $role->updated_at }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">{{ __('main.edit') }}</button>
                <form action="{{ route('admin.delete.role', ['id' => $role->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">{{ __('main.delete') }}</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
