

@extends($dashboard ? 'layouts.user' : 'layouts.main')

@section('title', $user->username)


@section('content')
<div class="container">
    <div class="profile-container">
        <div class="row justify-content-center mt-5">
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="profile-container-header">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 profile-text-col">
                            <div class="profile-img">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}"/>
                            </div>
                            <div class="profile-text">
                                <div class="profile-name">
                                    <h3>{{ $user->username }}</h3>
                                </div>
                                <div class="profile-bio">
                                    <p>{{ $user->bio }}</p>
                                </div>
                                <div class="profile-numbers">
                                    <div class="text-center">
                                        <p>{{ $approvedPosts->count() }}</p>
                                        <p>{{ __('main.posts') }}</p>
                                    </div>
                                    <!--<div class="ml-4 text-center">
                                        <p>0</p>
                                        <p>FOLLOWERS</p>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="posts-container">
                    @if(!empty($categories))
                        <div class="text-center py-5">
                            <div class="posts-container-title">
                                <h4 class="m-0">{{ __('main.published posts') }}</h4>
                            </div>
                        </div>
                        @foreach($categories as $category)
                            @php
                                $category = App\Category::find($category->id);
                                $posts = $category->approvedPosts()->where('user_id', $user->id)->get();
                            @endphp
                            <div class="pt-5 pb-5 text-center">
                                <h5>{{ strtoupper($category->name) }}</h5>
                            </div>
                            <div class="card-columns">
                                @foreach($posts as $post)
                                    <x-post-card :post="$post"></x-post-card>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
