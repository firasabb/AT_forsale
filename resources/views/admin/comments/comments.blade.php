@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.comments') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name="id" placeholder="ID" class="form-control" value="{{ Request::get('id') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='number' name="post_id" placeholder="Post ID" class="form-control" value="{{ Request::get('post_id') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name="title" placeholder="Title" class="form-control" value="{{ Request::get('title') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name="description" placeholder="Description" class="form-control" value="{{ Request::get('description') ?? '' }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="Search" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('main.comments') }}</div>

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
                                <a class="a-no-decoration" href="{{ route('admin.index.comments', ['order' => 'id', 'desc' => !$desc]) }}">{!! $order == 'id' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.ID') }}</a>
                            </th>
                            <th>
                                <a class="a-no-decoration" href="{{ route('admin.index.comments', ['order' => 'body', 'desc' => !$desc]) }}">{!! $order == 'body' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.body') }}</a>
                            </th>
                            <th>
                                <a class="a-no-decoration" href="{{ route('admin.index.comments', ['order' => 'post', 'desc' => !$desc]) }}">{!! $order == 'post' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.post') }}</a>
                            </th>
                            <th>
                                {{ __('main.user') }}
                            </th>
                            <th>
                                {{ __('main.created') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($comments as $comment)
                            <tr>
                                <td>
                                    {{ $comment->id }}
                                </td>
                                <td>
                                    {{ Str::limit($comment->body, 20, '...') }}
                                </td>
                                <td>
                                    <a href="{{ route('show.post', ['url' => $comment->post->url]) }}">{{ $comment->post->id }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('user.profile.show', ['username' => $comment->user->username]) }}">{{ $comment->user->username }}</a>
                                </td>
                                <td>
                                    {{ $comment->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.comment', ['id' => $comment->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.comment', ['id' => $comment->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $comments->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
