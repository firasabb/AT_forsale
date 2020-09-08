@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col search-col">
            <form method="post" action="{{ route('admin.search.contactmessages') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name='id' placeholder="{{ __('main.ID') }}" class="form-control" value="{{ old('id') }}"/>
                    </div>
                    <div class="col">
                        <input type='text' name='title' placeholder="{{ __('main.title') }}" class="form-control" value="{{ old('title') }}"/>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="{{ __('main.search') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('main.contact messages') }}</div>

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
                                {{ __('main.title') }}
                            </th>
                            <th>
                                {{ __('main.Sender Name') }}
                            </th>
                            <th>
                                {{ __('main.Sender Email') }}
                            </th>
                            <th>
                                {{ __('main.user') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($messages as $message)
                            <tr>
                                <td>
                                    {{$message->id}}
                                </td>
                                <td>
                                    {{Str::limit($message->title, $limit = 20, $end = '...')}}
                                </td>
                                <td>
                                    {{ $message->sender_email }}
                                </td>
                                <td>
                                    {{ $message->sender_name }}
                                </td>
                                <td>
                                    {{ $message->user_id ?? 'No' }}
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.contactmessage', ['id' => $message->id]) }}" class="btn btn-success">Show/Edit</a>
                                        <form action="{{ route('admin.delete.contactmessage', ['id' => $message->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
