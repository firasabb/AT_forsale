@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col text-center">
            <div class="py-5">
                <h2>My Assets</h2>
            <div>
        </div>
    </div>
    @if (session('status') || $errors->any())
    <div class="row justify-content-center">
        <div class="col text-center">
            <div class="py-5">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            <div>
        </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col p-0">
            <div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">
                                Title
                            </th>
                            <th scope="col">
                                Status
                            </th>
                            <th scope="col">
                                Views
                            </th>
                            <th scope="col">
                                Downloads
                            </th>
                            <th scope="col">
                                Published at
                            </th>
                            <th scope="col">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>
                                    <a target="_blank" href="{{ route('show.asset', ['url' => $asset->url]) }}" class="a-no-decoration"><strong>{{ Str::limit($asset->title, 20, '...') }}</strong></a>
                                </td>
                                <td>
                                    {{ $asset->statusInText() }}
                                </td>
                                <td>
                                    {{ $asset->viewEventsCount() }}
                                </td>
                                <td>
                                    {{ $asset->downloadEventsCount() }}
                                </td>
                                <td>
                                    {{ $asset->created_at->format('m/d/y h:i') }}
                                </td>
                                <td>
                                    <form class="delete-asset" method="POST" action="{{ route('user.delete.asset', ['id' => encrypt($asset->id)]) }}">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($assets))
                            <p>No assets have been published yet</p>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row py-3">
        <div class="col pagination-container">
            <div>
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
