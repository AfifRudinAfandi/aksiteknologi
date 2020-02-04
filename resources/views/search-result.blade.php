@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/page/search.css') }}">
@endpush

@section('content')
<div class="container ctp-career ctp-search">
    <div class="result-search">
        <div class="item-result">
            <h1>{{ $query }}</h1>
            <i class="fa fa-times-circle"></i>
        </div>
        <p>{{ $count }} {{ __('page.search.result') }}</p>
    </div>
    <h1 class="mb-5"><span>{{ __('page.search.job_vacancy') }}</span></h1>

    @if(count($careers) > 0)
        @foreach($careers as $career)
            <div class="accordion" id="accordionExample">
                <div class="wrapper-job-menu mb-3">
                    <button class="btn job-menu collapsed " type="button" data-toggle="collapse" data-target="#collapse{{ $career->id }}" aria-expanded="true" aria-controls="collapse{{ $career->id }}">
                        {{ $career->job_title }}
                        <i class="fa fa-sort-down"></i>
                    </button>
                    <div id="collapse{{ $career->id }}" class="collapse collapse-item" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <label>{{ __('page.search.basic') }}:</label>
                        <ul class="mb-0 ul-collapse">
                            @foreach($career->basicRequirement as $basic)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $basic->content }}
                                </li>
                            @endforeach
                        </ul>
                        <label>{{ __('page.search.specific') }}:</label>
                        <ul class="mb-0 ul-collapse">
                            @foreach($career->specificRequirement as $specific)
                                <li class="basic-item">
                                    <i class="fa fa-check-circle"></i>
                                    {{ $specific->content }}
                                </li>
                            @endforeach
                        </ul>
                        <div class="btm-collapse">
                            <p>{{ $career->job_desc }}</p>
                            <a href="{{ route('app.career.apply', $career->id) }}"><button type="submit" class="btn btn-warning btn-submit">{{ __('page.search.apply') }}</button></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>{{ __('page.search.empty') }}</p>
    @endif

    <div class="container blk-btm-ct rspn-ctn pl-0">
        <h5><span>{{ __('page.search.blog') }}</span></h5>
        @if(count($posts) > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-4 item-post itm-post-result">
                        <img src="{{ $post->getFirstMediaUrl('images', 'thumbnail') }}" class="card-img-top" alt="{{ $post->title }}">
                        <div class="content-related">
                            <a href="{{ route('app.single', $post->slug) }}"><h6 class="card-title">{{ $post->title }}</h6></a>
                            <p class="card-text">{!! \Illuminate\Support\Str::limit($post->content, 150, $end='...') !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('page.search.empty') }}</p>                
        @endif
    </div>

</div>
@endsection