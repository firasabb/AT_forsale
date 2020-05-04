<div>
    <div class="card card-shadow" style="max-width: 20rem">
        <a href="{{ route('show.asset', ['url' => $asset->url]) }}">
            <img class="card-img-top" src="{{ Storage::cloud()->url($asset->featured()) }}" alt="{{ $asset->title }}">
        </a>
        <div class="card-body card-body-asset">
            <div class="card-meta-info">
                <div class="card-user-img">
                    <a href="#"><img class="avatar-pic" src="{{ $asset->user->avatar_url }}"/></a>
                </div>
                <div class="card-user-text">
                    <a class="a-no-decoration" href="#">{{ $asset->user->name }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $asset->category }}" background-color="{{ $asset->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <div class="card-info">
                <a href="{{ route('show.asset', ['url' => $asset->url]) }}" class="a-no-decoration"><h5 class="card-title">{{ Str::limit($asset->title, $limit = 50, $end = '...') }}</h5></a>
            </div>
        </div>
        <div class="card-footer bg-light card-f">
            <!--<div class="card-footer-icons">
                @svg('heart', 'heart-icon')
            </div>-->
            <div class="card-footer-more">
                <div class="float-left card-footer-date">
                    <span>{{$asset->createdAt()}}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle-comment btn-no-padding float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @svg('th-menu', 'menu-icon-comment')
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if(Auth::id() != $asset->user->id)
                            <button type="button" v-on:click="open_report_modal('{{ encrypt($asset->id) }}', '{{ route('add.report', ['type' => 'asset']) }}')" class="dropdown-item">Report</button>
                        @elseif(!Auth::check())
                            <a target="_blank" class="a-no-decoration dropdown-item" href="{{ route('login') }}">Report</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>