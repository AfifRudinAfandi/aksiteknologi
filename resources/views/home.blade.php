@extends('layouts.app')

@push('styles')
<link href="{{ asset('/css/page/home.css') }}" rel="stylesheet" />
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
<div class="float">
  <form class="search">
    <div class="search__wrapper">
      <input type="text" name="" placeholder="Search for..." class="search__field">
      <button type="submit" class="fa fa-search search__icon"></button>
    </div>
  </form>
</div>
<section class="hero hero-home2">
    <div class="container wrapper-content">
        <h1>Action</h1>
        <h1 class="line-two" style="color: #FF9000;"><span>for</span> the Future</h1>
        <p>Let you have a dream,</br>Let us actualize your vision to become your future value</p>
        <a href="{{ route('app.contact') }}" class="btn btn-warning">{{ __('page.home.contact_us') }}</a>
    </div>
    <div class="line"></div>
</section>

@if($aksiProducts->isNotEmpty() && $ayoProducts->isNotEmpty())
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
@endif

<section class="our-servis">
    <div class="container">
        <div class="title-servis">
            <h5>{!! __('page.home.our_service') !!}</h5>
            <p>{{ SettingHelper::serviceDesc() }}</p>
        </div>
        <div class="logo-servis">
            @foreach($services as $service)
                <img src="{{ $service->getFirstMediaUrl('images') }}" alt="{{ $service->name }}">
            @endforeach
        </div>
    </div>
</section>

<section class="our-partner">
    <div class="container">
        <div class="title">
            <h5>{!! __('page.home.our_partner') !!}</h5>
            <p>{{ SettingHelper::partnerDesc() }}</p>
        </div>
        <div class="logo-partner">
            @foreach($partners as $partner)
                <img src="{{ $partner->getFirstMediaUrl('images') }}" alt="{{ $partner->name }}">
            @endforeach
        </div>
    </div>
</section>

<section class="recent-post">
    <div class="parent-post">
        <div class="left-post">
            <div class="wraper-content">
                <div class="content">
                    <h5>{!! __('page.home.media_creation') !!}</h5>
                    <div class="card-parent">
                        @foreach($mediaPosts as $media)
                            <div class="card">
                                <a href="{{ route('app.single', $media->slug) }}">
                                    @if(method_exists($media, 'getFirstMediaUrl') && $media->getFirstMediaUrl('images', 'thumbnail') != null)
                                        <img src="{{ $media->getFirstMediaUrl('images', 'thumbnail') }}" class="card-img-top" alt="{{ $media->title }}">
                                    @else
                                        <img src="{{ asset('/images/no-thumbnail.png') }}" alt="no thumbnail">
                                    @endif
                                </a>
                                <div class="card-body">
                                    <a href="{{ route('app.single', $media->slug) }}">
                                        <h6 class="card-title">{{ $media->title }}</h6>
                                    </a>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($media->content), 150, $end='...') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <img class="decor" src="{{ asset('/images/Union.png') }}" alt="dots">
                </div>
            </div>
        </div>
        <div class="right-post">
            <div class="content">
                <h5>{!! __('page.home.recent_post') !!}</h5>
                @if(count($recentPosts) > 0)
                    @foreach($recentPosts as $post)
                        <a href="{{ route('app.single', $post->slug) }}">
                            <h6>{{ $post->title }}</h6>
                        </a>
                        <p>{{ $post->created_at->diffForHumans() }}</p>
                        @if(!$loop->last)
                            <hr class="mx-0 mt-2 mb-4">
                        @endif
                    @endforeach
                @else
                    <p>Belum ada post sama sekali.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="testimoni">
    <div class="card-testimoni">
        <div class="testi-decor"></div>
        <h5>{{ __('page.home.testimony') }}</h5>
        <div class="slider-testimoni">
            @foreach($testimonies as $testimony)
                <div class="card item testimoni-item">
                    <div class="card-body">
                        <p>{{ \Illuminate\Support\Str::limit(SettingHelper::locale($testimony->content, $testimony->en_content), 150, $end='...') }}</p>
                        <div class="container">
                            <div class="row">
                                <div class="wrap-avatar">
                                    <img src="{{ $testimony->getFirstMediaUrl('images', 'square') }}" alt="{{ $testimony->name }}">
                                </div>
                                <div class="col" id="title-avatar">
                                    <h6>{{ $testimony->name }}</h6>
                                    <p>{{ $testimony->bio }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $(".slider-testimoni").bxSlider({
            responsive: true,
            slideMargin: 50,
            controls: false,
            touchEnabled: true
        });
    });

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
