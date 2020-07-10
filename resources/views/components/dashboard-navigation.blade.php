<div>
    <div class="p-3">
        <a class="navbar-brand a-no-decoration" href="{{ url('/') }}">
            <div>
                <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 373.66 75.08" style="width: 7rem">
                    <text transform="translate(0 62.23)" style="font-size:72px;fill:#ff8100;font-family:Lato-Black, Lato;font-weight:800">
                        <tspan style="letter-spacing:0.05000135633680555em">G</tspan>
                        <tspan x="54.86" y="0" style="fill:#b3b3b3">e</tspan>
                        <tspan x="94.03" y="0" style="fill:#b3b3b3;letter-spacing:-0.019992404513888888em">ny</tspan>
                        <tspan x="172.73" y="0" style="fill:#b3b3b3">oon</tspan>
                    </text>
                </svg>
            </div>
        </a>
    </div>
    <div class="list-group list-group-flush navigation-dashboard-list">
        <a href="{{route('user.profile.dashboard.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('user', 'dashboard-icon')</span> My Profile</a>
        <a href="{{route('user.setup.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('edit', 'dashboard-icon')</span> Edit Profile</a>
        <a href="{{route('user.password.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('lock-closed', 'dashboard-icon')</span> Change Password</a>
        <a href="{{route('user.assets.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('th-list', 'dashboard-icon')</span> My Assets</a>
        <a href="{{ route('user.userad.show') }}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('code', 'dashboard-icon')</span> My Ad</a>
    </div>
    <div class="py-3 text-center">
        <a target="_blank" href="{{ route('create.asset') }}" class="btn btn-primary">Upload&nbsp;&nbsp;@svg('upload', 'dashboard-upload-icon')</a>
    </div>
</div>