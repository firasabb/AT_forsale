<div>
    <div class="card" style="width: 20rem">
        <img class="card-img-top" src="{{ Storage::cloud()->url($art->featured()) }}" alt="{{ $art->title }}">
        <div class="card-body card-body-art">
            <div class="card-meta-info">
                <div class="card-user-img">
                    <a href="#"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                </div>
                <div class="card-user-text">
                    <a href="#">{{ $art->user->name }}</a>
                </div>
                <div class="card-category">
                    <category-button category="{{ $art->category }}" background-color="{{ $art->category->backgroundColor() }}"></category-button>
                </div>
            </div>
            <div class="card-info">
                <h5 class="card-title">{{$art->title}}</h5>
                @if($art->description)
                    <p class="card-text">{{ Str::limit($art->description, $limit = 20, $end = '...') }}</p>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <!--<div class="card-footer-icons">
                @svg('heart', 'heart-icon')
            </div>-->
            <div class="card-footer-report">
                <button type="button" v-on:click="open_report_modal('{{ encrypt($art->id) }}')" class="report-btn">Report</button>
            </div>
        </div>
    </div>
</div>