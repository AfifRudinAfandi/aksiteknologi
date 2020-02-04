@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/page/contact.css') }}">
@endpush

@section('content')
<section class="hero hero-about-cp ct-hero-contact">
    <div class="container wrapper-content wp-hero-contact">
        <h1 class="hero-contact"><span>{{ __('page.home.contact_us') }}</span></h1>
        <h1 class="hero-contact">{{ SettingHelper::name() }}</h1>
    </div>
</section>

<section class="input-contact">
    <div class="container ct-input-contact">
        <div class="wrapper-input-contact shadow">
            <form id="form" action="{{ route('app.contact.send') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlInput1">{{ __('page.contact.full_name') }}</label>
                    <input type="text" class="form-control" name="name" placeholder="{{ __('page.contact.your_name') }}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput2">E-Mail</label>
                    <input type="email" class="form-control" name="email" placeholder="YourEmail@mail.com">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput3">{{ __('page.contact.phone') }}</label>
                    <input type="tel" class="form-control" name="phone" placeholder="+62" value="+62">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput4">{{ __('page.contact.messages') }}</label>
                    <textarea class="form-control messages" name="messages" rows="5" id="comment" placeholder="{{ __('page.contact.messages_placeholder') }}"></textarea>
                </div>
                <div class="submit-group">
                    <button type="button" class="btn btn-light" style="visibility: hidden;">
                    </button>
                    <button type="submit" class="btn btn-warning btn-submit">{{ __('page.contact.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/vendor/jsvalidation/js/jsvalidation.min.js') }}"></script>
{!! $validator->selector('#form') !!}
@endpush