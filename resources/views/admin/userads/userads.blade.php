@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col search-col">
            <form method="post" action="{{ route('admin.search.userads') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="ID" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='name' placeholder="User Ad Name" class="form-control" value="{{ old('name') }}"/>
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
                <div class="card-header">User Ads</div>

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
                                User
                            </th>
                            <th>
                                User ID
                            </th>
                            <th class="td-actions">
                                Actions
                            </th>   
                        </tr>
                        @foreach ($userAds as $userAd)
                            <tr>
                                <td>
                                    {{$userAd->id}}
                                </td>
                                <td>
                                    {{ strtoupper($userAd->user->username) }}
                                </td>
                                <td>
                                    {{ $userAd->user->id }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.userad', ['id' => $userAd->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.userad', ['id' => $userAd->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $userAds->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
