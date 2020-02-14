<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('app.home') }}">
            @if(empty(SettingHelper::siteLogo()))
                <img src="{{ Config::get('app.logo_url') }}" alt="logo" style="width: 100px;">
            @else
                <img src="{{ SettingHelper::siteLogo() }}" alt="logo" style="width: 100px;">
            @endif
        </a>
        <ul class="navbar-nav nav-mobile nav-right flex-row d-md-flex">
                <li class="nav-item dropdown">
                    @if(Config::get('app.locale') == 'en' || Session::get('locale') == 'en')
                        <a class="btn btn-secondary btn-language dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-eng.png') }}" alt="EN">
                            <span>EN</span>
                        </a>
                    @else
                        <a class="btn btn-secondary btn-language dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-ind.png') }}" alt="ID">
                            <span>ID</span>
                        </a>
                    @endif
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                        <a class="dropdown-item itm-language" href="{{ route('set_locale', 'en') }}">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-eng.png') }}" alt="EN">
                            <span>EN</span>
                        </a>
                        <a class="dropdown-item itm-language" href="{{ route('set_locale', 'id') }}">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-ind.png') }}" alt="IN">
                            <span>ID</span>
                        </a>
                    </div>
                </li>
            </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Route::is('app.home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('app.home') }}">{{ __('app.menu.home') }}<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown {{ Route::is('app.about') || Route::is('app.team') || Route::is('app.partner') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('app.menu.about_us') }}
                    </a>
                    <div class="dropdown-menu first" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item {{ Route::is('app.about') ? 'active' : '' }}" href="{{ route('app.about') }}">{{ __('app.menu.about_company') }}</a>
                        <a class="dropdown-item {{ Route::is('app.team') ? 'active' : '' }}" href="{{ route('app.team') }}">{{ __('app.menu.our_team') }}</a>
                        <a class="dropdown-item {{ Route::is('app.partner') ? 'active' : '' }}" href="{{ route('app.partner') }}">{{ __('app.menu.our_partner') }}</a>
                    </div>
                </li>
                <li class="nav-item {{ Route::is('app.product') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('app.product') }}">{{ __('app.menu.our_product') }}</a>
                </li>
                <li class="nav-item {{ Route::is('app.blog') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('app.blog') }}">{{ __('app.menu.media') }}</a>
                </li>
                <li class="nav-item {{ Route::is('app.career') || Route::is('app.career.apply') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('app.career') }}">{{ __('app.menu.career') }}</a>
                </li>
                <li class="nav-item {{ Route::is('app.contact') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('app.contact') }}">{{ __('app.menu.contact') }}</a>
                </li>
            </ul>

            <ul class="navbar-nav nav-right flex-row ml-md-auto d-none d-md-flex">
                <div class="input-group">
                    <form action="{{ route('app.search') }}" method="get" class="form-inline">
                        <input type="text" name="q" class="form-control" placeholder="{{ __('app.search') }}" aria-label="Search" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <li class="nav-item dropdown">
                    @if(Config::get('app.locale') == 'en' || Session::get('locale') == 'en')
                        <a class="btn btn-secondary btn-language dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-eng.png') }}" alt="EN">
                            <span>EN</span>
                        </a>
                    @else
                        <a class="btn btn-secondary btn-language dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-ind.png') }}" alt="ID">
                            <span>ID</span>
                        </a>
                    @endif
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                        <a class="dropdown-item itm-language" href="{{ route('set_locale', 'en') }}">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-eng.png') }}" alt="EN">
                            <span>EN</span>
                        </a>
                        <a class="dropdown-item itm-language" href="{{ route('set_locale', 'id') }}">
                            <img style="margin-right: 8px; width: 22px;" src="{{ asset('/images/flag-ind.png') }}" alt="IN">
                            <span>ID</span>
                        </a>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</nav>