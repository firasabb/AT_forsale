@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col text-center">
            <div class="py-5">
                <h2>{{ __('main.my posts') }}</h2>
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
                                {{ __('main.title') }}
                            </th>
                            <th scope="col">
                                {{ __('main.status') }}
                            </th>
                            <th scope="col">
                                {{ __('main.views') }}
                            </th>
                            <th scope="col">
                                {{ __('main.downloads') }}
                            </th>
                            <th scope="col">
                                {{ __('main.published at') }}
                            </th>
                            <th scope="col">
                                {{ __('main.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <a target="_blank" href="{{ route('show.post', ['url' => $post->url]) }}" class="a-no-decoration"><strong>{{ Str::limit($post->title, 20, '...') }}</strong></a>
                                </td>
                                <td>
                                    {{ $post->statusInText() }}
                                </td>
                                <td>
                                    {{ $post->viewEventsCount() }}
                                </td>
                                <td>
                                    {{ $post->downloadEventsCount() }}
                                </td>
                                <td>
                                    {{ $post->created_at->format('m/d/y h:i') }}
                                </td>
                                <td>
                                    <form class="delete-post" method="POST" action="{{ route('user.delete.post', ['id' => encrypt($post->id)]) }}">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-sm btn-danger">{{ __('main.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($posts->isEmpty())
                    <div class="pt-5">
                        <h4>{{ __('main.no posts user') }}</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row py-3">
        <div class="col pagination-container">
            <div>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
