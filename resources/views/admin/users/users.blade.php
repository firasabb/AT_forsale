@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.users') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type="eamil" name="email" placeholder="Email..." class="form-control" value="{{ old('email') }}"/>
                    </div>
                    <div class="col">
                        <input type="number" name="id" placeholder="ID..." class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" placeholder="First Name..." class="form-control" value="{{ old('first_name') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="last_name" placeholder="Last Name..." class="form-control" value="{{ old('last_name') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="username" placeholder="Username..." class="form-control" value="{{ old('username') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type="submit" value="Search" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Users</div>

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
                                Email
                            </th>
                            <th>
                                Username
                            </th>
                            <th>
                                Roles
                            </th>
                            <th class="td-actions">
                                Actions
                            </th>   
                        </tr>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{$user->id}}
                                </td>
                                <td>
                                    {{$user->first_name . ' ' . $user->last_name}}
                                </td>
                                <td>
                                    {{$user->email}}
                                </td>
                                <td>
                                    {{$user->username}}
                                </td>
                                <td>
                                    <?php
                                        $userRoles = $user->roles;
                                        foreach ($userRoles as $role) {
                                            echo strtoupper($role->name) . ' <br> ';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ url('admin/dashboard/user/' . $user->id) }}" class="btn btn-success">Edit</a>
                                        <form action="{{ route('admin.delete.user', ['id' => $user->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
            <div class="block-button">
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">Add User</button>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.user') }}">
                            {!! csrf_field() !!}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="first_name"  value="{{ old('first_name') }}" placeholder="First Name" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="last_name"  value="{{ old('last_name') }}" placeholder="Last Name" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email"  value="{{ old('email') }}" placeholder="Email" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" id="username" type="text" name="username"  value="{{ old('username') }}" placeholder="Username" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password"  value="{{ old('password') }}" placeholder="Password" />
                                    </div>
                                    <div>
                                        <select multiple class="form-control" id="usersSelect" name="roles[]">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
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
