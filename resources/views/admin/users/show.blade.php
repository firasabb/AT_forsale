@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$user->username}}</div>

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

                    <form method="POST" action="{{ route('admin.edit.user', ['id' => $user->id]) }}" class="edit-form-confirm">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input class="form-control enabled-disabled" type="text" name="name"  value="{{ $user->username }}" placeholder="Name" disabled/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control enabled-disabled" type="email" name="email"  value="{{ $user->email }}" placeholder="Email" disabled/>
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <select multiple class="form-control enabled-disabled" id="usersSelect" name="roles[]" disabled>
                                        @foreach($roles as $role)
                                            <option <?php $user->hasRole($role->name) ? print('selected') : print(' ')  ?> value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary submit-edit-btn enabled-disabled" disabled>Submit</button>
                                <a href=" {{ route('admin.generate.password.user', ['id' => $user->id]) }} " class="btn btn-danger submit-edit-btn disabled" id="generate-password">Generate Password</a>
                            </div>
                        </div>
                        <div class="row info-row">
                            <div class="col">
                                <h4>Roles:</h4>
                                @foreach($user->roles as $role)
                                    <p> {{ strtoupper($role->name) }} </p>
                                @endforeach
                            </div>
                            <div class="col">
                                <h5>Created at:</h1>
                                <p>{{ $user->created_at }}</p>
                                <h5>Updated at:</h1>
                                <p>{{ $user->updated_at }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="block-button">
                <button type="button" class="btn btn-success btn-lg btn-block" id="edit-button">Edit User</button>
                <form action="{{ route('admin.delete.user', ['id' => $user->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">Delete User</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
