@extends('layouts.app')

@section('blog-title')
{{ $post->title }} |
@endsection

@push('styles')
<link href="{{ asset('css/page/blog.css') }}" rel="stylesheet" />
<style>
    #recent {
        position: sticky;
        top: 16vh;
        margin-bottom: 32px;
    }
</style>
@endpush

@section('content')
<section class="content-blog">
    <div class="container ct-blog">
        <div class="row r-blog">
            <div class="col-md-8 blk-left-ct">
                <div class="blk-ct-hd-left">
                    @if(method_exists($post, 'getFirstMediaUrl'))
                        <img src="{{ $post->getFirstMediaUrl('images', 'full') }}" alt="{{ $post->title }}">
                    @else
                        <img src="{{ asset('/images/img-blog.png') }}" alt="no thumbnail">
                    @endif
                </div>
                <div class="blk-ct-bdy-left">
                    <div class="label-wrapp">
                        <p>Blog</p>
                        @if(isset($post->category->category))
                            <p>{{ $post->category->category }}</p>
                        @endif
                    </div>
                    <h4>{{ $post->title }}</h4>
                    <div class="release-at">
                        <div class="blk-ct-release">
                            <img src="{{ asset('/images/avatar6.jpg') }}" alt="Author">
                            <p>{{ $post->author->name }}</p>
                        </div>
                        <div class="wrapp">
                            <div class="blk-ct-release">
                                <i class="fa fa-clock-o"></i>
                                <p>{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-justify">{!! $post->content !!}</p>
                </div>
            </div>
            <div class="col-md-4 blk-right-ct">
                <div class="content"  id="recent">
                    @if(count($recentPosts) > 0)
                        @foreach($recentPosts as $_post)
                            <a href="{{ route('app.single', $_post->slug) }}">
                                <h6>{{ $_post->title }}</h6>
                            </a>
                            <p>{{ $_post->created_at->diffForHumans() }}</p>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <p>{{ __('page.blog.empty') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="related-post">
    <div class="container blk-btm-ct">
        <h5>{!! __('page.blog.related') !!}</h5>
        <div class="row">
            @if(count($relatedPosts) > 0)
                @foreach($relatedPosts as $relatedPost)
                    <div class="col-md-3 item-post">
                        <a href="{{ route('app.single', $relatedPost->slug) }}">
                            @if(method_exists($relatedPost, 'getFirstMediaUrl'))
                                <img src="{{ $relatedPost->getFirstMediaUrl('images', 'full') }}" class="card-img-top" alt="{{ $post->title }}">
                            @else
                                <img src="{{ asset('/images/img-blog.png') }}" class="card-img-top" alt="no thumbnail">
                            @endif
                        </a>
                        <div class="content-related">
                            <a href="{{ route('app.single', $relatedPost->slug) }}">
                                <h6 class="card-title">{{ $relatedPost->title }}</h6>
                            </a>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($relatedPost->content), 40, $end='...') }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-3 item-post">
                    <p>{{ __('page.blog.no_related') }}</p>
                </div>
            @endif
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script>
</script>
@endpush
