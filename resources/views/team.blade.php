@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/page/our-team.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="commissioners">
    <!-- <h6>{{ SettingHelper::name() }}</h6> -->
    <h4>{!! __('page.team.director') !!}</h4>
    <p></p>
    <div class="container">
        <div class="row row-team">
            @foreach($directors as $director)
                <div class="col-md-4 cm-our-team directors">
                    <div class="cm-profile">
                        <img src="{{ $director->getFirstMediaUrl('images', 'square') }}" alt="{{ $director->name }}">
                    </div>
                    <div class="cm-title">
                        <h5>{{ $director->name }}</h5>
                        <h6>{{ isset($director->division) ? $director->division->name : '' }}</h6>
                        <p>{{ SettingHelper::locale($director->bio, $director->en_bio) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="our-team">
    <!-- <h6>{{ SettingHelper::name() }}</h6> -->
    <h4>{!! __('page.team.team') !!}</h4>
    <p></p>
    <div class="container crs-wrapper">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($teams as $team)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img class="d-block w-100" src="{{ $team->getFirstMediaUrl('images', 'full') }}" alt="{{ $team->name }}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="crs-decor"></div>
    </div>
</section>

<section class="collaboration">
    <div class="container ct-banner">
        <div class="wrapper-collab">
            {!! __('page.team.product_development') !!}
            <p>{{ __('page.team.product_caption') }}</p>
            <a href="{{ route('app.career') }}" class="btn btn-warning mt-2">{{ __('page.team.browse_product') }}</a>
        </div>
    </div>
</section>
@endsection