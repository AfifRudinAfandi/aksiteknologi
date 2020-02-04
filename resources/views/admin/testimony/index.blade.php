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
    <h1>Manajemen Testimony</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Manajemen Testimony</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Data Testimony</h4>
            <div class="card-header-action">
                <button class="btn btn-primary" data-toggle="modal" data-target="#add-testimony-modal"><i class="fas fa-plus mr-2"></i>Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md" id="testimony-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                    <label for="check-all" class="custom-control-label">&nbsp;</label>
                                </div>
                            </th>
                            <th>Nama</th>
                            <th>Ditampilkan</th>
                            <th>Dibuat</th>
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

@section('bottom')
<div class="modal fade" role="dialog" id="add-testimony-modal">
    <div class="modal-dialog" role="document">
        <form id="add-form" action="{{ route('admin.testimony.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Testimoni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Bio/Jabatan</label>
                        <input type="text" name="bio" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Konten Testimoni</label>
                        <input type="text" name="content" placeholder="konten testimoni" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ditampilkan?</label>
                        <select name="is_displayed" class="form-control">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <div id="image-preview" class="image-preview" style="width: 180px;">
                            <label for="image-upload" id="image-label">Pilih File</label>
                            <input type="file" name="image" id="image-upload" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" role="dialog" id="edit-testimony-modal">
    <div class="modal-dialog" role="document">
        <form id="edit-form" action="{{ route('admin.testimony.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Testimoni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="edit-spinner" class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div id="edit-input">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="hidden" name="id" id="id">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Bio/Jabatan</label>
                            <input type="text" name="bio" id="bio" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Konten Testimoni</label>
                            <input type="text" name="content" id="content" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Ditampilkan?</label>
                            <select name="is_displayed" id="is-displayed" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Foto Profil (biarkan kosong jika tidak ingin mengganti gambar.)</label>
                            <div id="edit-image-preview" class="image-preview" style="width: 180px;">
                                <label for="edit-image-upload" id="edit-image-label">Pilih File</label>
                                <input type="file" name="image" id="edit-image-upload" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')

{!! $validatorInsert->selector('#add-form') !!}
{!! $validatorEdit->selector('#edit-form') !!}

<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

<script type="text/javascript">
    $(function() {

        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "Pilih File",
            label_selected: "Ubah File",
            no_label: false,
            success_callback: null
        });

        $.uploadPreview({
            input_field: "#edit-image-upload",
            preview_box: "#edit-image-preview",
            label_field: "#edit-image-label",
            label_default: "Pilih File",
            label_selected: "Ubah File",
            no_label: false,
            success_callback: null
        });

        $('#testimony-table').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [3, "asc"]
            ],
            ajax: '{{ route('admin.testimony.datatable') }}',
            columns: [{
                    data: 'checkboxes',
                    name: 'checkboxes'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'is_displayed',
                    name: 'is_displayed'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],
            columnDefs: [{
                    'orderable': false,
                    'targets': [0, 1, 4]
                },
                {
                    'searchable': false,
                    'targets': [0, 1, 4]
                }
            ]
        });

        $(document).on('click', '.edit-button', function() {
            $('#edit-input').hide();
            $('#edit-spinner').show();
            var id = $(this).data('id');
            $.ajax({
                method: "POST",
                url: "{{ route('admin.testimony.get_edit') }}",
                data: {
                    id: id
                },
                success: function(result) {
                    if (result != null) {
                        $('#id').val(result.id);
                        $('#name').val(result.name);
                        $('#bio').val(result.bio);
                        $('#content').val(result.content);
                        $('#is-displayed').val(result.is_displayed);
                        $('#edit-spinner').hide();
                        $('#edit-input').fadeIn();
                    } else {
                        $('#edit-testimony-modal').modal('hide');
                        Swal.fire({
                            icon: 'warning',
                            text: 'Terjadi kesalahan!',
                        });
                    }
                }
            });
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
                        url: "{{ route('admin.testimony.destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            $('#testimony-table').DataTable().ajax.reload()
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
                            url: "{{ route('admin.testimony.destroy_all') }}",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#testimony-table').DataTable().ajax.reload();
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