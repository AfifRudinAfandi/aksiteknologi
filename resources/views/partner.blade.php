@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/page/our-partner.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="our-partner-pg">
    <!-- <h6>{{ SettingHelper::name() }}</h6> -->
    <h4>{!! __('page.partner.partner') !!}</h4>
    <p></p>
</section>
<section class="ic-our-partner">
    <div class="container">
        <div class="row grid-ic">
            @if(count($partners) > 0)
                @foreach($partners as $partner)
                    <div class="col-md-2 custom-grid">
                        <img src="{{ $partner->getFirstMediaUrl('images') }}" alt="{{ $partner->name }}">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
<section class="collaboration">
    <div class="container ct-banner">
        <div class="wrapper-collab">
            {!! __('page.partner.interested') !!}
            <p>{{ __('page.partner.challenge') }}</p>
            <a href="{{ route('app.contact') }}" class="btn btn-warning mt-2">{{ __('page.partner.contact_us') }}</a>
        </div>
    </div>
</section>
@endsection