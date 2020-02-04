@extends('layouts.admin')

@section('wrapper')
main-wrapper-2
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />
@endpush

@section('content_header')
<div class="section-header">
    <h1>Blog Post</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Post</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4>Data Post</h4>
            <div class="card-header-action">
                <a href="{{ route('admin.post.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Buat Post</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md" id="posts-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                    <label for="check-all" class="custom-control-label">&nbsp;</label>
                                </div>
                            </th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Dibuat</th>
                            <th>Status</th>
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

        $('#posts-table').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [4, "desc"]
            ],
            ajax: '{{ route('admin.post.datatable') }}',
            columns: [{
                    data: 'checkboxes',
                    name: 'checkboxes'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'author',
                    name: 'author'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
                }
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
                            $('#posts-table').DataTable().ajax.reload()
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
                            url: "{{ route('admin.post.destroy_all') }}",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#posts-table').DataTable().ajax.reload();
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
