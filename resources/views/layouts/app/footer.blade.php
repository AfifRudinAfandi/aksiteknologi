<footer class="footer">
    <div class="container-fluid footer-container">

        <div class="row footer-top">
            <div class="col-md-5 logo-footer">
                @if(empty(SettingHelper::siteLogo()))
                    <img src="{{ Config::get('app.logo_url') }}" style="max-width: 226px;" alt="Aksi Logo">
                @else
                    <img src="{{ SettingHelper::siteLogo() }}" style="max-width: 226px;" alt="Aksi Logo">
                @endif
            </div>
            <div class="col card-contact">
                <a class="link-card-ft" href="tel:{{ SettingHelper::phone() }}">
                    <div class="wrapper-contact">
                        <div class="icon-contact">
                            <img class="icon-footer" style="max-width: 56px;" src="{{ asset('/images/ic-telephone.png') }}" alt="">
                        </div>
                        <div class="title-contact">
                            <p>{{ SettingHelper::phone() }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col card-contact">
                <a class="link-card-ft" href="mailto:{{ SettingHelper::email() }}">
                    <div class="wrapper-contact">
                        <div class="icon-contact">
                            <img class="icon-footer" style="max-width: 56px;" src="{{ asset('/images/ic-mail.png') }}" alt="">
                        </div>
                        <div class="title-contact">
                            <p>{{ SettingHelper::email() }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row footer-bottom">
            <div class="col-md-5 footer-left">
                <h6>{{ SettingHelper::companyName() }}</h6>
                <h6>{{ __('app.head_office') }}</h6>
                <p style="max-width: 70%">{{ SettingHelper::address() }}</p>
                <a class="btn-location" href="{{ SettingHelper::map() }}" target="_blank">{{ __('app.menu.map') }}<i class="ml-2 fa fa-external-link"></i></a>
            </div>
            <div class="col footer-link">
                <a href="{{ route('app.about') }}">{{ __('app.menu.about_us') }}</a>
                <a href="{{ route('app.team') }}">{{ __('app.menu.our_team') }}</a>
                <a href="{{ route('app.partner') }}">{{ __('app.menu.our_partner') }}</a>
            </div>
            <div class="col footer-link">
                <a href="{{ route('app.blog') }}">Media</a>
                <a href="{{ route('app.career') }}">{{ __('app.menu.career') }}</a>
            </div>
            <div class="col-md-3 child-sosmed">
                <h6>{{ __('app.find_us') }}</h6>
                <ul>
                    @php
                        $social = SettingHelper::socialLinks()
                    @endphp
                    @if(!empty($social->twitter))
                        <li><a href="https://www.twitter.com/{{ $social->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    @endif
                    @if(!empty($social->facebook))
                        <li><a href="https://www.facebook.com/{{ $social->facebook }}" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
                    @endif
                    @if(!empty($social->linkedin))
                        <li><a href="https://www.linkedin.com/{{ $social->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    @endif
                    @if(!empty($social->instagram))
                        <li><a href="https://www.instagram.com/{{ $social->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    @endif
                    @if(!empty($social->youtube))
                        <li><a href="https://www.youtube.com/{{ $social->youtube }}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                    @endif
                    @if(!empty($social->google))
                        <li><a href="https://plus.google.com/{{ $social->google }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    @endif
                    @if(!empty($social->dribbble))
                        <li><a href="https://www.dribbble.com/{{ $social->dribbble }}" target="_blank"><i class="fa fa-dribbble"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 copy-right">
                <p>2019 © Copyright {{ SettingHelper::name() }}</p>
            </div>
        </div>
    </div>
</footer>
