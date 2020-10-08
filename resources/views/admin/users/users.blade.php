@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.users') }}" id="filter-form">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type="number" name="id" placeholder="{{ __('main.ID') }}" class="form-control filter-input" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type="eamil" name="email" placeholder="{{ __('main.email') }}" class="form-control filter-input" value="{{ old('email') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="first_name" placeholder="{{ __('main.first name') }}" class="form-control filter-input" value="{{ old('first_name') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="last_name" placeholder="{{ __('main.last name') }}" class="form-control filter-input" value="{{ old('last_name') }}"/>
                    </div>
                    <div class="col">
                        <input type="text" name="username" placeholder="{{ __('main.username') }}" class="form-control filter-input" value="{{ old('username') }}"/>
                    </div>
                    <div class="col">
                        <select name="status" class="form-control">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ strtoupper($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <input type="submit" id='filter-btn' value="{{ __('main.filter') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('main.users') }}</div>

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
                                <a href="{{ route('admin.index.users', ['order' => 'id', 'desc' => !$desc]) }}">{!! $order == 'id' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.ID') }}</a>
                            </th>
                            <th>
                                <a>{{ __('main.name') }}</a>
                            </th>
                            <th>
                                <a>{{ __('main.email') }}</a>
                            </th>
                            <th>
                                <a href="{{ route('admin.index.users', ['order' => 'status', 'desc' => !$desc]) }}">{!! $order == 'status' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.status') }}</a>
                            </th>
                            <th>
                                <a href="{{ route('admin.index.users', ['order' => 'username', 'desc' => !$desc]) }}">{!! $order == 'username' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.username') }}</a>
                            </th>
                            <th>
                                <a href="{{ route('admin.index.users', ['order' => 'created_at', 'desc' => !$desc]) }}">{!! $order == 'created_at' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.created') }}</a>
                            </th> 
                            <th>
                                {{ __('main.roles') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
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
                                    {{ strtoupper($user->status) }}
                                </td>
                                <td>
                                    {{$user->username}}
                                </td>
                                <td>
                                    {{ $user->created_at->format('Y-m-d') }}
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
                                        <a href="{{ url('admin/dashboard/user/' . $user->id) }}" class="btn btn-success">{{ __('main.edit') }}</a>
                                        <form action="{{ route('admin.delete.user', ['id' => $user->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
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
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addModal">{{ __('main.add') }}</button>
            </div>

            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('admin.add.user') }}">
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
                                        <input class="form-control" type="text" name="first_name"  value="{{ old('first_name') }}" placeholder="{{ __('main.first name') }}" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="last_name"  value="{{ old('last_name') }}" placeholder="{{ __('main.last name') }}" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email"  value="{{ old('email') }}" placeholder="{{ __('main.email') }}" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" id="username" type="text" name="username"  value="{{ old('username') }}" placeholder="{{ __('main.username') }}" />
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password"  value="{{ old('password') }}" placeholder="{{ __('main.password') }}" />
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
