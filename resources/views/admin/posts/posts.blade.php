@extends('layouts.panel')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.posts') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="{{ __('main.ID') }}" class="form-control" value="{{ Request::get('id') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='title' placeholder="{{ __('main.title') }}" class="form-control" value="{{ Request::get('title') ?? '' }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='url' placeholder="{{ __('main.URL') }}" class="form-control" value="{{ Request::get('url') ?? '' }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="{{ __('main.search') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Posts</div>

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
                                <a class="a-no-decoration" href="{{ route('admin.index.posts', ['order' => 'id', 'desc' => !$desc]) }}">{!! $order == 'id' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.ID') }}</a>
                            </th>
                            <th>
                                <a class="a-no-decoration" href="{{ route('admin.index.posts', ['order' => 'title', 'desc' => !$desc]) }}">{!! $order == 'title' && $desc ? '&#8639;' : '&#8642;' !!} {{ __('main.title') }}</a>
                            </th>
                            <th>
                                {{ __('main.URL') }}
                            </th>
                            <th>
                                {{ __('main.category') }}
                            </th>
                            <th>
                                {{ __('main.created') }}
                            </th>
                            <th>
                                {{ __('main.Download Files') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    {{$post->id}}
                                </td>
                                <td>
                                    {{ Str::limit($post->title, $limit = 20, $end = '...') }}
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('show.post', ['url' => $post->url]) }}">{{ Str::limit($post->url, $limit = 20, $end = '...') }}</a>
                                </td>
                                <td>
                                    {{ $post->category->name }}
                                </td>
                                <td>
                                    {{ $post->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    {{ $post->downloads->count() }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.post', ['id' => $post->id]) }}" class="btn btn-success">{{ __('main.show/edit') }}</a>
                                        <form action="{{ route('admin.delete.post', ['id' => $post->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
            <div class="block-button">
                <a href="{{route('create.post')}}" target="_blank" class="btn btn-primary btn-lg btn-block">{{ __('main.add') }}</a>
            </div>

        </div>
    </div>
</div>
@endsection
