@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@push('styles')
<style>
    #job-desc{ height: 120px; }
</style>
@endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.career.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>{{ $career->job_title }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.career.index') }}">Data Karir</a></div>
        <div class="breadcrumb-item active">Detail</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Detail</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.career.edit', $career->id) }}" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Ubah</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Kategori: </h6>
                        <span>{{ $career->category->category }}</span>
                    </div>
                    <div class="mb-4">
                        <h6>Deskripsi :</h6>
                        <span>{{ $career->job_desc }}</span>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <h6 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-chevron-right mr-2"></i>Basic Requirements</h6>
                        <div id="collapseOne" class="basic-wrapper collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                            @if(isset($career->basicRequirement) || !empty($career->basicRequirement))
                                <ul class="list-group list-group-flush">
                                    @foreach($career->basicRequirement as $basic)
                                        <li class="px-0 py-2 list-group-item">{{ $basic->content }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span>Tidak ada data</span>
                            @endif
                        </div>

                        <h6 class="mt-4" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><i class="fa fa-chevron-right mr-2"></i>Specific Requirements</h6>
                        <div id="collapseTwo" class="collapse specific-wrapper" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            @if(isset($career->specificRequirement) || !empty($career->specificRequirement))
                                <ul class="list-group list-group-flush">
                                    @foreach($career->specificRequirement as $specific)
                                        <li class="px-0 py-2 list-group-item">{{ $specific->content }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span>Tidak ada data</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Pelamar</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-md" id="careers-table">
                        <thead>
                            <tr>
                                <th>Pelamar</th>
                                <th>Link CV</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<!-- DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

<script type="text/javascript">
    $(function() {

        $('#careers-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            dom: 'ft',
            order: [
                [2, "asc"]
            ],
            ajax: {
                'url': '{{ route('admin.career.datatableApplicant') }}',
                'type': 'POST',
                'data': {career_id: {{ $career->id }}},
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'document_link',
                    name: 'document_link'
                },
                {
                    data: 'created_at',
                    name: 'updated_at',
                }
            ],
        });

    });
</script>
@endpush