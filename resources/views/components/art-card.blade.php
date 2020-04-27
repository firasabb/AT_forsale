<div>
    <div class="card card-shadow" style="max-width: 20rem">
        <a href="{{ route('show.art', ['url' => $art->url]) }}">
            <img class="card-img-top" src="{{ Storage::cloud()->url($art->featured()) }}" alt="{{ $art->title }}">
        </a>
        <div class="card-body card-body-art">
            <div class="card-meta-info">
                <div class="card-user-img">
                    <a href="#"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration" href="#">{{ $art->user->name }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $art->category }}" background-color="{{ $art->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <div class="card-info">
                <a href="{{ route('show.art', ['url' => $art->url]) }}" class="a-no-decoration"><h5 class="card-title">{{$art->title}}</h5></a>
                @if($art->description)
                    <p class="card-text">{{ Str::limit($art->description, $limit = 20, $end = '...') }}</p>
                @endif
            </div>
        </div>
        <div class="card-footer bg-light card-f">
            <!--<div class="card-footer-icons">
                @svg('heart', 'heart-icon')
            </div>-->
            <div class="card-footer-more">
                <div class="float-left card-footer-date">
                    <span>{{$art->createdAt()}}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle-comment btn-no-padding float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @svg('th-menu', 'menu-icon-comment')
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(Auth::id() != $art->user->id)
                            <button type="button" v-on:click="open_report_modal('{{ encrypt($art->id) }}', '{{ route('add.report', ['type' => 'art']) }}')" class="report-btn float-right">Report</button>
                        @elseif(!Auth::check())
                            <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">Report</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>