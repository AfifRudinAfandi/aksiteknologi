@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/page/career-apply.css') }}">
@endpush

@section('content')
<div class="container ctp-career">
    <h1><span>{{ __('page.career.apply_job') }}</span></h1>
    <h1>{{ $career->job_title }}</h1>
</div>

<section class="input-career">
    <div class="container ct-input-career">
        <div class="wrapper-input-career">
            <form id="form" action="{{ route('app.career.post') }}" method="POST">
                @csrf
                <input type="hidden" name="career_id" value="{{ $career->id }}">
                <div class="row">
                    <div class="col-md-6 col-form">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">{{ __('page.career.job_position') }}</label>
                            <select class="form-control" name="position">
                                <option>{{ $career->job_title }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">{{ __('page.career.name') }}</label>
                            <input type="text" class="form-control" name="name" placeholder="{{ __('page.career.your_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">Email</label>
                            <input type="email" class="form-control" name="email" id="exampleFormControlInput2" placeholder="YourEmail@mail.com">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput3">{{ __('page.career.phone') }}</label>
                            <input type="tel" class="form-control" name="phone" id="exampleFormControlInput3" placeholder="+62" value="+62">
                        </div>
                    </div>
                    <div class="col-md-6 col-form col-form-left">
                        <h6>{{ __('page.career.note') }}</h6>
                        <p>{{ __('page.career.subnote') }}</p>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">{{ __('page.career.link_cv') }}</label>
                            <input type="text" class="form-control" name="link" id="exampleFormControlInput1" placeholder="https://dropbox.com/cv">
                        </div>
                    </div>
                    <div class="submit-group ml-5">
                        <button type="submit" class="btn btn-warning btn-submit">{{ __('page.career.apply_this') }}</button>
                    </div>
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