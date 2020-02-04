<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">{{ config('app.title') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">{{ config('app.title_slug') }}</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item {{ (Route::is('admin.dashboard') ? 'active' : '') }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ (Route::is('admin.message*') ? 'active' : '') }}">
                <a href="{{ route('admin.message.index') }}" class="nav-link"><i class="fas fa-envelope"></i><span>Pesan</span></a>
            </li>
            <li class="menu-header">Media</li>
            <li class="dropdown {{ (Route::is('admin.post*') ? 'active' : '') }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="far fa-newspaper"></i>
                    <span>Blog</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ (Route::is('admin.post.create') ? 'active' : '') }}">
                        <a href="{{ route('admin.post.create') }}" class="nav-link">Buat Blog</a>
                    </li>
                    <li class="{{ (Route::is('admin.post.index') ? 'active' : '') }}">
                        <a href="{{ route('admin.post.index') }}" class="nav-link">Semua Blog</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ (Route::is('admin.career*') ? 'active' : '') }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="far fa-compass"></i>
                    <span>Karir</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ (Route::is('admin.career.create') ? 'active' : '') }}">
                        <a href="{{ route('admin.career.create') }}" class="nav-link">Buat Karir</a>
                    </li>
                    <li class="{{ (Route::is('admin.career.index') ? 'active' : '') }}">
                        <a href="{{ route('admin.career.index') }}" class="nav-link">Semua Karir</a>
                    </li>
                    <li class="{{ (Route::is('admin.career.applicant.index') ? 'active' : '') }}">
                        <a href="{{ route('admin.career.applicant.index') }}" class="nav-link">Pelamar</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Master Data</li>
            <li class="{{ (Route::is('admin.product*') ? 'active' : '') }}">
                <a href="{{ route('admin.product.index') }}" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Product</span>
                </a>
            </li>
            <li class="{{ (Route::is('admin.team*') ? 'active' : '') }}">
                <a href="{{ route('admin.team.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Team</span>
                </a>
            </li>
            <li class="{{ (Route::is('admin.client*') ? 'active' : '') }}">
                <a href="{{ route('admin.client.index') }}" class="nav-link">
                    <i class="fas fa-user-tie"></i>
                    <span>Client</span>
                </a>
            </li>
            <li class="{{ (Route::is('admin.service*') ? 'active' : '') }}">
                <a href="{{ route('admin.service.index') }}" class="nav-link">
                    <i class="fas fa-poll-h"></i>
                    <span>Service</span>
                </a>
            </li>
            <li class="{{ (Route::is('admin.testimony*') ? 'active' : '') }}">
                <a href="{{ route('admin.testimony.index') }}" class="nav-link">
                    <i class="fas fa-comment-dots"></i>
                    <span>Testimony</span>
                </a>
            </li>
            <li class="menu-header">Pengaturan</li>
            <li class="{{ (Route::is('admin.setting*') ? 'active' : '') }}">
                <a href="{{ route('admin.setting.profile') }}" class="nav-link">
                    <i class="fas fa-sliders-h"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/translations') }}" target="_blank" class="nav-link">
                    <i class="fas fa-language"></i>
                    <span>Translation <i class="fa fa-external-link-alt" style="margin-right: 5px;"></i></span>
                </a>
            </li>
            <li class="nav-item {{ (Route::is('admin.user*') ? 'active' : '') }}">
                <a href="{{ route('admin.user.index') }}" class="nav-link">
                    <i class="fas fa-users-cog"></i>
                    <span>Admin</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
