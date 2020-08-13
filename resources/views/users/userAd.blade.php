@extends('layouts.user')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col text-center pt-5">
            <div class="pb-3">
                <h2>My Ad</h2>
            </div>
            <div class="pb-5">
                <p>This ad will show up everytime someone clicks the download button of one of your posts.<br>You can customize as you like!</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-5 text-center">
            <div class="py-5" id="status-container">
                @if(empty($ad))
                    <div class="alert alert-secondary" role="alert">
                        Your ad hasn't been set yet... Set it now!
                    </div>
                @else
                    @if($ad->status == 0)
                        <div class="alert alert-danger" role="alert">
                            Unfortunately your ad hasn't been approved... You can try to submit it again after fixing the errors...
                        </div>
                    @elseif($ad->status == 1)
                        <div class="alert alert-warning" role="alert">
                            Your ad request is pending... We will review it as soon as possible!
                        </div>
                    @elseif($ad->status == 2)
                        <div class="alert alert-success" role="alert">
                            Your ad add is approved!
                        </div>
                    @endif
                @endif
            </div>
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

    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div id="ad-modal-container">
                    <x-user-ad :user="$user" :user-ad="$ad" show-as-modal="0"></x-user-ad>
                </div>   
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="pt-5 pb-4">
                    <h3>Customize Your Ad:</h3>
                    <span>* Any Edit Has to be Approved by our Team.</span>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="ad-form" method="POST" action="{{ route('user.userad.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="header_title">Header Title:</label>
                        <input type="text" name="header_title" class="form-control" id="header_title" placeholder="{{'Thank You from ' . $user->username . '!'}}" value="{{ $content ? $content['header_title'] : '' }}">
                    </div>
                    <div class="form-group pb-3">
                        <label for="appreciation_msg">Appreciation Message:</label>
                        <input type="text" name="appreciation_msg" class="form-control" id="appreciation_msg" placeholder="I hope that you will attribute me!" value="{{ $content ? $content['appreciation_msg'] : '' }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_btn">First Button:</label>
                            <select name="first_btn" id="first_btn" class="form-control" {{ !$userLinks->isEmpty() ? '' : 'disabled' }}>
                                <option value="">N/A</option>
                                @foreach($userLinks as $userLink)
                                    <option class="link-{{ $userLink->name }}" value="{{ $userLink->name }}" @if($content) {{ $content['first_btn'] == $userLink->name ? 'selected' : '' }} @endif>{{ strtoupper($userLink->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="second-btn">Second Button:</label>
                            <select name="second_btn" id="second_btn" class="form-control" {{ !$userLinks->isEmpty() ? '' : 'disabled' }}>
                                <option value="">N/A</option>
                                @foreach($userLinks as $userLink)
                                    <option class="link-{{ $userLink->name }}" value="{{ $userLink->name }}" @if($content) {{ $content['second_btn'] == $userLink->name ? 'selected' : '' }} @endif>{{ strtoupper($userLink->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pb-5">
                        <div class="col">
                            @if($userLinks->isEmpty())
                                <span style="color:rgb(255, 104, 104)">Please add your social links in the edit profile page</span>
                            @else
                                <span style="color:rgb(159, 158, 158)">*You can manage your social links in the edit profile page</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-check pb-5">
                        <input class="form-check-input" name="paypal_donation" type="checkbox" value="1" id="donate_btn" {{ empty($user->paypal) ? 'disabled' : '' }} {{ empty($content['paypal_donation']) ? '' : 'checked' }}>
                        <label class="form-check-label" for="donate_btn">
                            Show Donate Button
                        </label>
                        @if(empty($user->paypal))
                            <span style="color:rgb(255, 104, 104); display: block;">Please add your paypal email in the profile setup page</span>
                        @endif
                    </div>
                    <h5>Ad Image:</h5>
                    <div class="form-group">
                        <label for="image_url">Use Image from URL:</label>
                        <input type="text" name="image_url" class="form-control pb-1" id="image_url" value="{{ $content ? $content['image_url'] : '' }}" placeholder="https://image.example.com/img.png">
                    </div>
                        <p>Or</p>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="upload" class="custom-file-input pt-1">
                            <label class="custom-file-label">Choose file...</label>
                        </div>
                        <button class="btn btn-sm btn-danger my-3" id="delete-ad-medias-btn">Delete Image</button>
                    </div>
                    <div class="form-group pb-2">
                        <label for="ad_url">On Ad Image Click URL:</label>
                        <input type="text" name="ad_url" class="form-control" id="ad_url" value="{{ $content ? $content['ad_url'] : '' }}" placeholder="https://www.mywebsite.com/landing">
                    </div>
                    <div class="py-2 text-center">
                        <button class="btn btn-primary" id="update-ad-btn">Update</button>
                    </div>
                </form>
                <form id="delete-medias-form" action="{{ route('user.userad.delete.medias') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="ad_id" value="{{ !empty($ad) ? encrypt($ad->id) : '' }}">
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/myad.js') }}" defer></script>
@endpush