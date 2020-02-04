@extends('layouts.admin')

@section('wrapper')
main-wrapper-2
@endsection

@section('content_header')
<div class="section-header">
    <h1 class="d-block">Dashboard {{ ucfirst(trans(ProfileHelper::getName())) }}</h1>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('admin.team.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Team</h4>
                        </div>
                        <div class="card-body">
                            {{ Counter::modelAll('App\Models\Team') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('admin.client.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Client</h4>
                        </div>
                        <div class="card-body">
                            {{ Counter::modelAll('App\Models\Client') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('admin.service.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-poll-h"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Service</h4>
                        </div>
                        <div class="card-body">
                            {{ Counter::modelAll('App\Models\Service') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('admin.testimony.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Testimony</h4>
                        </div>
                        <div class="card-body">
                        {{ Counter::modelAll('App\Models\Testimony') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik - Google Analytics</h4>
                </div>
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <!-- <div class="card">
                <div class="card-header">
                    <h4>Browser</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col text-center">
                            <div class="browser browser-chrome"></div>
                            <div class="mt-2 font-weight-bold">Chrome</div>
                            <div class="text-muted">0</div>
                        </div>
                        <div class="col text-center">
                            <div class="browser browser-firefox"></div>
                            <div class="mt-2 font-weight-bold">Firefox</div>
                            <div class="text-muted">0</div>
                        </div>
                        <div class="col text-center">
                            <div class="browser browser-safari"></div>
                            <div class="mt-2 font-weight-bold">Safari</div>
                            <div class="text-muted">0</div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="card">
                <div class="card-header">
                  <h4>Pesan</h4>
                </div>
                <div class="card-body">             
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach($messages as $message)
                            <li class="media">
                                <div class="media-body">
                                    <div class="float-right text-primary">{{ $message->created_at->diffForHumans() }}</div>
                                    <div class="media-title"><a href="{{ route('admin.message.show', $message->id) }}">{{ $message->name }}</a></div>
                                    <span class="text-small text-muted">{{ \Illuminate\Support\Str::limit($message->messages, 50, $end='...') }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="text-center pt-1 pb-1">
                        <a href="{{ route('admin.message.index') }}" class="btn btn-primary btn-sm btn-round">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <a href="{{ route('admin.post.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fa-paper-plane"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Blog Post</h4>
                        </div>
                        <div class="card-body">
                            {{ Counter::postAll() }}
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.career.index') }}">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="far fa-compass"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Karir</h4>
                        </div>
                        <div class="card-body">
                            {{ Counter::careerAll() }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Post Terbaru</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.post.index') }}" class="btn btn-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestPost as $post)
                                <tr>
                                    <td>
                                        {{ $post->title }}
                                        <div class="table-links">
                                            di <a href="#">{{ $post->category->category }}</a>
                                            <div class="bullet"></div>
                                            <a href="{{ route('app.single', $post->slug) }}" target="_blank">Lihat</a>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $post->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.post.edit', $post->id) }}" target="_blank" class="btn btn-sm btn-primary mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger delete-button" data-toggle="tooltip" title="" data-original-title="Delete" data-id="{{ $post->id }}"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart->script() !!}

<script>
    $(function() {
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus data?',
                text: "Data tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.post.destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            location.reload();
                        }
                    });
                }
            })
        });
    });
</script>
@endpush