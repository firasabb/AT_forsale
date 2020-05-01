<div>
    <div class="p-3">
        <a class="navbar-brand a-no-decoration" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>
    <div class="list-group list-group-flush navigation-dashboard-list">
        <a href="{{route('user.profile.dashboard.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('user', 'dashboard-icon')</span> My Profile</a>
        <a href="{{route('user.setup.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('edit', 'dashboard-icon')</span> Edit Profile</a>
        <a href="{{route('user.password.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('lock-closed', 'dashboard-icon')</span> Change Password</a>
        <a href="{{route('user.assets.show')}}" class="list-group-item list-group-item-action no-border"><span class="dashboard-icon-span">@svg('th-list', 'dashboard-icon')</span> My Assets</a>
    </div>
    <div class="py-3 text-center">
        <a target="_blank" href="{{ route('create.asset') }}" class="btn btn-primary">Upload&nbsp;&nbsp;@svg('upload', 'dashboard-upload-icon')</a>
    </div>
</div>