@extends('layouts.panel')


@section('content')
<div class="container">
    @if ($errors->any())
    <div class="row justify-content-center">
        <div class="col">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    @if (session('status'))
    <div class="row justify-content-center">
        <div class="col">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col">
            <form action="{{ route('admin.bulk.add.tags') }}" method="post">
            @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <input class="form-control" type="text" name="names[0]"  value="{{ old('name') }}" placeholder="{{ __('main.name') }}" />
                        </div>
                    </div>
                </div>
                <div class="row" id="categories-row">
                    <div class="col">
                        <div class="form-group">
                            <select class="form-control" name="categories[]" multiple>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary float-left">{{ __('main.submit') }}</button>
                    </div>
                    <div class="col">
                        <button class="btn btn-light float-right" id="add-field-btn">+ {{__('Add Field')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
