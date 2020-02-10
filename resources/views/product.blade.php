@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/page/product.css') }}" rel="stylesheet" />
<style>
    .popover {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0px 3px 14px 7px rgba(166, 184, 195, 0.38);
    }
    .popover-body {
        padding: 0px;
    }
    .popover-body .thumbnail {
        border-radius: 0.5rem 0.5rem 0 0;
        max-height: 150px;
        overflow: hidden;
    }
    .popover-body .thumbnail img {
        max-width: 100%;
    }
    .popover-body h6.has-image {
        margin-bottom: 40px;
        margin-top: -36px;
        padding: 0px 30px;
        font-weight: bold;
        color: #ffff;
    }
    .popover-body h6 {
        padding: 0px 30px;
        font-weight: bold;
    }
    .popover-body p {
        padding: 0px 30px;
        line-height: 1.7rem;
    }
    .wrapper-btn-pop{
        display: flex;
        flex-direction: row;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
    }
    .wrapper-btn-pop a{
        color: #000;
        margin: 0 4px 30px 4px;
    }
</style>
@endpush

@section('content')

<section class="hero hero-about-cp our-product-hero" style="display:none;">
    <div class="container wrapper-content">
        <h1 class="hero-product"><span>{{ __('page.product.product') }}</span></h1>
        <h1 class="hero-product">{{ SettingHelper::name() }}</h1>
        <p class="hero-product"></p>
    </div>
</section>

<section class="our-product">
    <h2>{!! __('page.home.our_product') !!}</h2>
    <div class="container">
        @if($aksiProducts->isNotEmpty())
            <div class="row mb-5">
                <div class="col-md-4 center-col-parent pr-0 rev-title-product">
                    <div class="wrapper-product">
                        <h5 class="mb-0">{!! __('page.product.aksi_product') !!}</h5>
                    </div>
                </div>

                @foreach($aksiProducts as $aksi)
                    <a role="button" tabindex="0" class="col-md-4 center-col-parent btn btn-link"
                        data-toggle="popover"
                        data-img="{{ $aksi->getFirstMediaUrl('thumbs') }}"
                        data-name="{{ $aksi->name }}"
                        data-desc="{{ SettingHelper::locale($aksi->description, $aksi->en_description) }}"
                        data-playstore="{{ $aksi->playstore_link }}"
                        data-appstore="{{ $aksi->appstore_link }}"
                        data-web="{{ $aksi->web_link }}"
                        >
                        <div class="wrapper-product">
                            <div class="icon-product child-left">
                                <img src="{{ $aksi->getFirstMediaUrl('images') }}" alt="{{ $aksi->name }}">
                            </div>
                            <div class="title-product child-right">
                                <h5>{!! str_replace('AKSI', '<span>AKSI</span>', $aksi->name) !!}</h5>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags(SettingHelper::locale($aksi->description, $aksi->en_description)), 40, $end='...') }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($ayoProducts->isNotEmpty())
            <div class="row mt-5 pt-5 pb-5">

                <div class="col-md-4 center-col-parent pr-0 rev-title-product">
                    <div class="wrapper-product">
                        <h5 class="mb-0">{!! __('page.product.ayo_product') !!}</h5>
                    </div>
                </div>

                @foreach($ayoProducts as $ayo)
                <a role="button" tabindex="0" class="col-md-4 center-col-parent btn btn-link"
                        data-toggle="popover"
                        data-img="{{ $ayo->getFirstMediaUrl('thumbs') }}"
                        data-name="{{ $ayo->name }}"
                        data-desc="{{ SettingHelper::locale($ayo->description, $ayo->en_description) }}"
                        data-playstore="{{ $ayo->playstore_link }}"
                        data-appstore="{{ $ayo->appstore_link }}"
                        data-web="{{ $ayo->web_link }}"                        
                        >
                        <div class="wrapper-product">
                            <div class="icon-product child-left">
                                <img src="{{ $ayo->getFirstMediaUrl('images') }}" alt="{{ $ayo->name }}">
                            </div>
                            <div class="title-product child-right">
                                <h5>{!! str_replace(['AYO', 'Sia'], ['<span>AYO</span>','<span>Sia</span>'], $ayo->name) !!}</h5>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags(SettingHelper::locale($ayo->description, $ayo->en_description)), 40, $end='...') }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        @endif

    </div>
</section>

<section class="collaboration">
    <div class="container ct-banner">
        <div class="wrapper-collab">
            {!! __('page.product.interested') !!}
            <p>{{ __('page.product.challenge') }}</p>
            <a href="{{ route('app.contact') }}" class="btn btn-warning mt-2">{{ __('page.product.contact_us') }}</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('.btn-link').popover({
            html: true,
            trigger: 'focus',
            placement: 'top',
            content: function () {
                cls = ($(this).data('img') !== '') ? 'has-image' : '';
                
                ps = ($(this).data('playstore') !== '') ? '<a class="btn btn-outline-primary" href="'+$(this).data('playstore')+'" target="_blank"><img src="{{ asset('/images/ic-play.svg') }}" alt=""> PlayStore</a>' : '';
                
                ap = ($(this).data('appstore') !== '') ? '<a class="btn btn-outline-primary" href="'+$(this).data('appstore')+'" target="_blank"><img src="{{ asset('/images/ic-app.svg') }}" alt=""> AppStore</a>' : '';
                
                web = ($(this).data('web') !== '') ? '<a class="btn btn-outline-primary" href="'+$(this).data('web')+'" target="_blank"><img src="{{ asset('/images/ic-web.svg') }}" alt=""> Website</a></div>' : '';

                return '<div class="thumbnail"><img src="'+$(this).data('img')+'"/></div><h6 class="'+cls+'">'+$(this).data('name')+'</h6><p>'+$(this).data('desc')+'</p><div class="wrapper-btn-pop">'+ps+ap+web;
            },
        }) ;
    });
</script>
@endpush
