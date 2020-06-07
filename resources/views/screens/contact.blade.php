@extends('layouts.main')

@section('title', 'Contact Us')

@section('content')

<div class="container">
    @if(session('status') || $errors->any())
    <div class="row justify-content-center">
        <div class="col py-5 text-center">
            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @else
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif  
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col py-5 text-center">
            <h2>Contact Us</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('store.contactus') }}">
                @csrf
                <div class="form-row">
                    <div class="col form-group">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="col">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="form-row form-group">
                    <div class="col">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-row form-group">
                    <div class="col">
                        <input type="text" name="title" class="form-control" placeholder="Subject" required>
                    </div>
                </div>
                <div class="form-row form-group">
                    <div class="col">
                        <textarea name="body" class="form-control" placeholder="Your Message..." required></textarea>
                    </div>
                </div>
                <input type="hidden" id="recaptcha" name="recaptcha" value="" >
                <button class='btn btn-primary'>Submit</button>
            </form>
        </div>
    </div>
</div>



@push('footer_scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script type="text/javascript">
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function(token) {
                    if (token) {
                    document.getElementById('recaptcha').value = token;
                    }
                });
            });
    </script>
@endpush

@endsection