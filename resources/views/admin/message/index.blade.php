@extends('layouts.admin') 

@section('wrapper')
main-wrapper-1
@endsection

@push('styles') @endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Semua Pesan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Pesan</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4>Pesan</h4>
        </div>
        <div class="card-body">
            <a href="#" class="btn btn-primary btn-icon icon-left btn-lg btn-block mb-4 d-md-none" data-toggle-slide="#ticket-items">
                <i class="fas fa-list"></i> All Tickets
            </a>
            <div class="tickets">
                <div class="ticket-items" id="ticket-items">
                    @foreach($messageLists as $list)
                    <a href="{{ route('admin.message.show', $list->id) }}">
                        <div class="ticket-item {{ $list->id == $message->id ? 'active' : '' }}">
                            <div class="ticket-title">
                                <h4>{{ $list->name }}</h4>
                            </div>
                            <div class="ticket-desc">
                                {{ \Illuminate\Support\Str::limit($list->messages, 50, $end='...') }}
                            </div>
                            <hr class="my-2">
                            <div class="ticket-desc">
                                <div>{{ $list->email }}</div>
                            </div>
                            <div class="ticket-desc">
                                <div>{{ $list->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                <div class="ticket-content">
                    <form class="form-inline float-right" action="{{ route('admin.message.destroy', $message->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>                                
                    </form>
                    <div class="ticket-header">
                        <div class="ticket-detail">
                            <div class="ticket-title">
                                <h4>{{ $message->name }}</h4>
                            </div>
                            <div class="ticket-info">
                                <div class="font-weight-600">{{ $message->email }}</div>
                                <div class="bullet"></div>
                                <div class="font-weight-600">{{ $message->phone }}</div>
                                <div class="bullet"></div>
                                <div class="text-primary font-weight-600">{{ $message->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="ticket-description">
                        <p>{{ $message->messages }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

</div>
@endsection @push('scripts') @endpush