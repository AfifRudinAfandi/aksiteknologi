<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li class="mt-2 text-white"><h6><i class="far fa-save mr-2"></i>Profil Aktif: {{ ProfileHelper::getNameActive() }}</h6></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle">
            <a href="#" id="btn-profile" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user mt-1">
                <span id="preview-profile-name">Profil</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" id="add-profile" class="dropdown-item has-icon">
                    <i class="fas fa-plus"></i> Tambah Profil
                </a>
                <div class="dropdown-divider"></div>
                <div id="profile-spinner" class="dropdown-spinner">
                    <div class="spinner-grow" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="profile-list"></div>
            </div>
        </li>
        @if(Session::has('profile-preview') && Session::get('profile-preview') == ProfileHelper::getId())
            <a href="{{ route('admin.profile.exit_preview') }}" class="btn btn-sm btn-icon icon-right btn-outline-light mr-2">
                <i class="fa fa-times"></i>
                Exit Preview
            </a>
        @else
            <a href="{{ route('admin.profile.preview', ProfileHelper::getId()) }}" target="_blank" class="btn btn-sm btn-icon icon-right btn-outline-light mr-2">
                <i class="fa fa-eye"></i>
                Preview
            </a>
        @endif
        
        @if(Session::has('profile-preview') || ProfileHelper::getIdActive() != ProfileHelper::getId())
            <button type="button" id="activate-profile" data-id="{{ ProfileHelper::getId() }}" class="btn btn-sm btn-icon icon-right btn-outline-warning">
                <i class="far fa-save"></i>
                Aktifkan
            </button>
        @endif
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('stisla/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item has-icon text-danger" href="#"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>