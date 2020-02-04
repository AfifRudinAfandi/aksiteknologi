@extends('layouts.admin')

@section('wrapper')
main-wrapper-2
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endpush

@section('content_header')
<div class="section-header">
    <h1>Manajemen Team</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Manajemen Team</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4>Data Team</h4>
            <div class="card-header-action">
                <div class="dropdown show float-left">
                    <button type="button" id="btn-division" class="btn has-dropdown" data-toggle="dropdown" aria-expanded="true">
                        <!-- <i class="fa fa-quote-right mr-2"></i> -->
                        {{ isset($divisionId) ? $divisionName : 'Pilih Divisi' }}<i class="fa fa-chevron-down pl-2"></i>
                    </button>
                    <input type="hidden" name="division_id" id="division-id" value="">
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" id="add-division" class="dropdown-item has-icon">
                            <i class="fas fa-plus"></i> Tambah Divisi
                        </a>
                        <div class="dropdown-divider"></div>
                        <div id="division-spinner" class="dropdown-spinner">
                            <div class="spinner-grow" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div id="division-list"></div>
                    </div>
                </div>
                <a href="{{ route('admin.team.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Tambah</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md" id="teams-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                    <label for="check-all" class="custom-control-label">&nbsp;</label>
                                </div>
                            </th>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Sosial Media</th>
                            <th>Aksi</th>
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

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script type="text/javascript">
    $(function() {

        $('#teams-table').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, "asc"]
            ],
            ajax: {
                type: 'POST',
                url: '{{ route('admin.team.datatable') }}',
                data: { division_id: {{ (isset($divisionId) && !empty($divisionId) ? $divisionId : 'null') }} }
            },
            columns: [{
                    data: 'checkboxes',
                    name: 'checkboxes'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'division',
                    name: 'division'
                },
                {
                    data: 'social',
                    name: 'social'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],
            columnDefs: [{
                    'orderable': false,
                    'targets': [0, 4]
                },
                {
                    'searchable': false,
                    'targets': [0, 4]
                }
            ]
        });

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
                        url: "{{ route('admin.team.destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            $('#teams-table').DataTable().ajax.reload()
                        }
                    });
                }
            })
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
                            url: "{{ route('admin.team.destroy_all') }}",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#teams-table').DataTable().ajax.reload();
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


        function getDivision() {
            $('#division-spinner').fadeIn();
            $.ajax({
                type: "GET",
                url: "{{ route('admin.team.division.index') }}",
                success: function(result) {
                    if(result.length > 0) {
                        var html = [];
                        html += '<a href="{{ route('admin.team.index') }}" class="dropdown-item">Semua Divisi</a>';
                        $.each(result, function(index, row){
                            html+= '<a href="/admin/team/division/'+row.id+'" data-id="'+row.id+'" data-name="'+row.name+'" class="dropdown-item division">'+row.name+'</a><button type="button" class="edit-division btn btn-sm float-right" data-id="'+row.id+'" style="margin-top: -32px; margin-right: 34px;"><i class="fa fa-edit"></i></button><button type="button" class="delete-division btn btn-sm float-right" data-id="'+row.id+'" style="margin-top: -32px; margin-right: 10px;"><i class="fa fa-trash-alt text-danger"></i></button>'
                        });
                        $('#division-list').html(html);
                    } else {
                        $('#division-list').html('');
                    }
                    $('#division-spinner').fadeOut();
                }
            });
        }

        $(document).on('click', '#btn-division', function() {
            getDivision();
        });

        $(document).on('click', '#add-division', function() {
            Swal.fire({
                title: 'Masukan nama divisi',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: (division) => {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.team.division.store') }}",
                        data: {name:division},
                        success: function(result) {
                            Swal.fire({
                                icon: result.status,
                                title: result.message,
                            });
                            getDivision();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        $(document).on('click', '.edit-division', function() {
            var id = $(this).data('id');
            console.log(id);

            Swal.fire({
                title: 'Ubah nama divisi',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Ubah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: (division) => {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.team.division.update') }}',
                        data: {
                            id: id,
                            name: division,
                        },
                        success: function(result) {
                            Swal.fire({
                                icon: result.status,
                                title: result.message,
                            });
                            getDivision();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        $(document).on('click', '.delete-division', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Hapus kategori ini?',
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
                        url: "{{ route('admin.team.division.destroy') }}",
                        data: {id:id},
                        success:function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            getDivision();
                        }
                    });
                }
            });
        });

    });
</script>
@endpush