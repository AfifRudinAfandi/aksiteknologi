@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/page/media.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container ct-media-prt">
    <div class="row rw-media-prt">
        <div class="col-md-8 cl-media-left">
            <h4>{{ __('page.blog.blog_all') }}</h4>
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    <div class="wrapper-media-rw">
                        <div class="img-wrapper">
                            <img src="{{ $post->getFirstMediaUrl('images', 'thumbnail') }}" alt="{{ $post->title }}">
                        </div>
                        <div class="ctn-media-wrapper">
                            <a href="{{ route('app.single', $post->slug) }}"><h6>{{ $post->title }}</h6></a>
                            <p class="timedate"><i class="fa fa-clock-o"></i>{{ $post->created_at->diffForHumans() }}</p>
                            <p>{!! \Illuminate\Support\Str::limit($post->content, 150, $end='...') !!}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p>{{ __('page.blog.empty_post') }}</p>
            @endif
        </div>
        <div class="col-md-4 cl-media-right">
            <div class="ct-kategori shadow">
                <h4>{{ __('page.blog.category') }}</h4>
                <div class="container">
                    
                <div class="row">
                    <p><a href="{{ route('app.blog') }}">{{ __('page.blog.all_category') }}</a></p>
                    @foreach($categories as $category)
                        <p><a href="{{ route('app.blog.category', $category->id) }}">{{ $category->category }}</a></p>
                    @endforeach
                </div>
                
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

