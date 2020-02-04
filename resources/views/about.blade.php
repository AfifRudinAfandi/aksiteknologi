@extends('layouts.app')

@push('styles')
<link href="{{ asset('/css/page/about-us.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="hero hero-about-cp">
    <div class="container wrapper-content">
        <h1><span>{{ __('page.about.about') }}</span></h1>
        <h1>{{ SettingHelper::name() }}</h1>
    </div>
</section>

<section class="about-contain">
    <div class="container">
        <div class="row">
            <div class="col-md-5 abc-left">
                <img style="max-width: 100%;" src="{{ asset('/images/img-about-content.png') }}" alt="about us" />
            </div>
            <div class="col-md-7 abc-right">
                <p>{{ SettingHelper::about() }}</p>
            </div>
        </div>
    </div>
</section>
<section class="visi">
    <div class="container visi-container">
        <div class="card-visi">
            <div class="decor-visi"></div>
            <h5>{!! __('page.about.vision') !!}</h5>
            <h6>" {{ SettingHelper::vision() }} "</h6>
        </div>
    </div>
</section>

<section class="misi">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-right-misi">
                {!! __('page.about.mision') !!}
            </div>
            <div class="col-md-7 col-left-misi">
                @if(count($missions) > 0)
                    @foreach($missions as $mission)
                        <div class="card-misi">
                            <div class="wrapper-misi">
                                <div class="icon-misi">
                                    <img style="width: 42px;" src="{{ asset('/images/ic-arrow.png') }}" alt="misi" />
                                </div>
                                <div class="title-misi">
                                    <p>{{ $mission->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endsection