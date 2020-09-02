@extends('layouts.panel')


@section('content')
<div class="container">
    <div class="row justify-content-center search-row">
        <div class="col-md-12 search-col">
            <form method="post" action="{{ route('admin.search.reports') }}">
                {!! csrf_field() !!}
                <div class="form-row" >
                    <div class="col">
                        <input type='number' name="id" placeholder="{{ __('main.ID') }}" class="form-control"/>
                    </div>
                    <div class="col">
                        <input type='text' name="reportable_id" placeholder="{{__('main.Reportable ID')}}" class="form-control"/>
                    </div>
                    <div class="col">
                        <select name="reportable_type" class="form-control">
                            @foreach($report_types as $report_type)
                                <option value="{{ $report_type->reportable_type }}">{{ $report_type->reportable_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <input type='submit' value="{{ __('search') }}" class="btn btn-primary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('reports') }}</div>

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
                                {{ __('main.type') }}
                            </th>
                            <th>
                                {{ __('main.Reportable ID') }}
                            </th>
                            <th class="td-actions">
                                {{ __('main.actions') }}
                            </th>   
                        </tr>
                        @foreach ($reports as $report)
                            <tr>
                                <td>
                                    {{$report->id}}
                                </td>
                                <td>
                                    {{ strtoupper($report->reportable_type) }}
                                </td>
                                <td>
                                    @if($report->reportable_type == 'App\Post')
                                        <a href="{{ route('show.post', ['url' => $report->reportable->url]) }}" target="_blank">{{ $report->reportable_id }}</a>
                                    @else
                                        {{ $report->reportable_id }}
                                    @endif
                                </td>
                                <td>
                                    <div class="td-actions-btns">
                                        <a href="{{ route('admin.show.report', ['id' => $report->id]) }}" class="btn btn-success">{{ __('main.show/edit') }}</a>
                                        <form action="{{ route('admin.delete.report', ['id' => $report->id]) }}" method="POST" class="delete-form-1 delete-form-confirm">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button class="btn btn-danger" type="submit">{{ __('main.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
