@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>

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
                    <div class="row">
                        <div class="col">
                            <div class="text-center p-3">
                                <h5>Reported by: <a href="{{ url('admin/dashboard/user/' . $report->user->id) }}">{{ $report->user->username }}</a></h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center p-3">
                                <h5>Report ID: {{ $report->id }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="text-center p-3">
                                <h5>Type: {{ $report->reportable_type }}</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center p-3">
                            @if($report->reportable_type == 'App\Asset')
                                <h5>Reported ID: <a href="{{ route('show.asset', ['url' => $report->reportable->url]) }}">{{ $report->reportable->id }}</a></h5>
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="text-center p-3">
                                <textarea class="form-control" disabled>{{ $report->body }}</textarea>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>

            <div class="block-button">
                <form action="{{ route('admin.delete.report', ['id' => $report->id]) }}" method="POST" class="delete-form-2 delete-form-confirm">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit" class="btn btn-danger btn-lg btn-block">Delete Report</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
