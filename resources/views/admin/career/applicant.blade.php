@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />
@endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.career.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Data Pelamar</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.career.index') }}">Karir</a></div>
        <div class="breadcrumb-item active">Data Pelamar</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4>Data Pelamar</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md" id="careers-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                    <label for="check-all" class="custom-control-label">&nbsp;</label>
                                </div>
                            </th>
                            <th>Pelamar</th>
                            <th>Link Dokumen</th>
                            <th>Pekerjaan</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                </table>
                <button id="bulk-delete" class="btn btn-danger">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus data dipilih
                </button>
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
            order: [
                [4, "asc"]
            ],
            ajax: '{{ route('admin.career.applicant.datatable') }}',
            columns: [{
                    data: 'checkboxes',
                    name: 'checkboxes'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'document_link',
                    name: 'document_link'
                },
                {
                    data: 'job',
                    name: 'job'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },

            ],
            columnDefs: [{
                    'orderable': false,
                    'targets': [0]
                },
                {
                    'searchable': false,
                    'targets': [0]
                }
            ]
        });

        $(document).on('click', '#bulk-delete', function() {
            var id = [];
            $('.check-datatable:checked').each(function() {
                id.push($(this).val());
            });
            if (id.length > 0) {
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
                            url: "{{ route('admin.career.applicant.destroy') }}",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                console.log(data);
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#careers-table').DataTable().ajax.reload();
                                $('#check-all').prop('indeterminate', false);
                                $('#check-all').prop('checked', false);
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: 'Pilih minimal satu data.',
                });
            }
        });
    });
</script>
@endpush
