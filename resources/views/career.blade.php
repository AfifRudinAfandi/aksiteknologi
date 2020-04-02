@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/page/career.css') }}">
@endpush

@section('content')
<div class="container ctp-career">
    <h1><span>{{ __('page.career.job_vacancy') }}</span></h1>
    <h1>{{ SettingHelper::name() }}</h1>

    <div class="ctp-pill">
        <ul class="nav nav-tabs border-bottom-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="all-job-tab" data-toggle="tab" href="#all-job" role="tab" aria-controls="all-job" aria-selected="true">{{ __('page.career.all_job') }}</a>
            </li>
            @foreach($careers as $i => $label)
            <li class="nav-item">
                <a class="nav-link" id="{{ $label['id'] }}-tab" data-toggle="tab" href="#{{ $label['id'] }}" role="tab" aria-controls="{{ $label['id'] }}" aria-selected="false">{{ $label['category'] }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all-job" role="tabpanel" aria-labelledby="all-job-tab">
                <div class="accordion" id="accordionExample">
                    @foreach($allCareers as $all)
                    <div class="wrapper-job-menu mb-3">
                        <button class="btn job-menu collapsed " type="button" data-toggle="collapse" data-target="#collapse{{ $all->id }}" aria-expanded="true" aria-controls="collapse{{ $all->id }}">
                            {{ $all->job_title }}
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <div id="collapse{{ $all->id }}" class="collapse collapse-item" aria-labelledby="heading{{ $all->id }}" data-parent="#accordionExample">
                            <label>{{ __('page.career.basic') }}:</label>
                            <ul class="mb-0 ul-collapse">
                                @foreach($all->basicRequirement as $basic)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $basic->content }}
                                </li>
                                @endforeach
                            </ul>
                            <label>{{ __('page.career.specific') }}:</label>
                            <ul class="mb-0 ul-collapse">
                                @foreach($all->specificRequirement as $specific)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $specific->content }}
                                </li>
                                @endforeach
                            </ul>
                            <div class="btm-collapse">
                                <p>{!! $all->job_desc !!}</p>
                                <a href="{{ route('app.career.apply', $all->id) }}" class="btn btn-warning btn-submit">{{ __('page.career.apply') }}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @foreach($careers as $i => $category)
            <div class="tab-pane fade" id="{{$category['id'] }}" role="tabpanel" aria-labelledby="{{ $category['id'] }}-tab">
                <div class="accordion" id="accordionExample">
                    @foreach($category['data'] as $data)
                    <div class="wrapper-job-menu mb-3">
                        <button class="btn job-menu collapsed " type="button" data-toggle="collapse" data-target="#collapse{{ $data->id }}" aria-expanded="true" aria-controls="collapse{{ $data->id }}">
                            {{ $data->job_title }}
                            <i class="fa fa-sort-down"></i>
                        </button>
                        <div id="collapse{{ $data->id }}" class="collapse collapse-item" aria-labelledby="heading{{ $data->id }}" data-parent="#accordionExample">
                            <label>{{ __('page.career.basic') }}:</label>
                            <ul class="mb-0 ul-collapse">
                                @foreach($data->basicRequirement as $_basic)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $_basic->content }}
                                </li>
                                @endforeach
                            </ul>
                            <label>{{ __('page.career.specific') }}:</label>
                            <ul class="mb-0 ul-collapse">
                                @foreach($data->specificRequirement as $_specific)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $_specific->content }}
                                </li>
                                @endforeach
                            </ul>
                            <div class="btm-collapse">
                                <p>{!! $data->job_desc !!}</p>
                                <a href="{{ route('app.career.apply', $data->id) }}" class="btn btn-warning btn-submit">{{ __('page.career.apply') }}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection