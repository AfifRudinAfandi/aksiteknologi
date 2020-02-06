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
    <h1>Manajemen Product</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Manajemen Product</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4>Data Product</h4>
            <div class="card-header-action">
                <button class="btn btn-primary" data-toggle="modal" data-target="#add-product-modal"><i class="fas fa-plus mr-2"></i>Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md" id="products-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                    <label for="check-all" class="custom-control-label">&nbsp;</label>
                                </div>
                            </th>
                            <th>Logo</th>
                            <th>Nama</th>
                            <th>Tipe Produk</th>
                            <th>Ditampilan</th>
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
<div class="modal fade" role="dialog" id="add-product-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="add-form" action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Product</h5>
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
                        <label>Deskripsi</label>
                        <ul class="nav nav-pills" id="productTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="id-tab" data-toggle="tab" href="#cid" role="tab" aria-controls="id" aria-selected="true">ID</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="true">EN</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="productTabContent">
                            <div class="tab-pane fade show active" id="cid" role="id" aria-labelledby="id-tab">
                                <textarea name="description" class="form-control" style="height: 80px;"></textarea>
                            </div>
                            <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                <textarea name="en_description" class="form-control" style="height: 80px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Tipe Product</label>
                                <select name="type" class="form-control">
                                    <option value="">-- Pilih Tipe Produk --</option>
                                    <option value="aksi">AKSI Product</option>
                                    <option value="ayo">AYO Product</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Ditampilkan?</label>
                                <select name="is_displayed" class="form-control">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="playstore_link">Playstore Link</label>
                        <input type="text" name="playstore_link" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="appstore_link">Appstore Link</label>
                        <input type="text" name="appstore_link" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="web_link">Web Link</label>
                        <input type="text" name="web_link" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <div id="image-preview" class="image-preview" style="width: 160px;">
                            <label for="image-upload" id="image-label">Pilih File</label>
                            <input type="file" name="image" id="image-upload" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Gambar Thumbnail (Popover Image)</label>
                        <div id="thumb-preview" class="image-preview" style="width: 320px;">
                            <label for="thumb-upload" id="thumb-label">Pilih File</label>
                            <input type="file" name="thumb" id="thumb-upload" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="edit-product-modal">
    <div class="modal-dialog" role="document">
        <form id="edit-form" action="{{ route('admin.product.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Product</h5>
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
                            <label>Deskripsi</label>
                            <ul class="nav nav-pills" id="productEditTab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="edit-id-tab" data-toggle="tab" href="#edit-id" role="tab" aria-controls="edit-id" aria-selected="true">ID</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="edit-en-tab" data-toggle="tab" href="#edit-en" role="tab" aria-controls="edit-en" aria-selected="true">EN</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="productEditTabContent">
                                <div class="tab-pane fade show active" id="edit-id" role="edit-id" aria-labelledby="edit-id-tab">
                                    <textarea id="description" name="description" class="form-control" style="height: 80px;"></textarea>
                                </div>
                                <div class="tab-pane fade" id="edit-en" role="tabpanel" aria-labelledby="edit-en-tab">
                                    <textarea id="en-description" name="en_description" class="form-control" style="height: 80px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Tipe Product</label>
                                    <select id="type" name="type" class="form-control">
                                        <option value="">-- Pilih Tipe Produk --</option>
                                        <option value="aksi">AKSI Product</option>
                                        <option value="ayo">AYO Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Ditampilkan?</label>
                                    <select id="is_displayed" name="is_displayed" class="form-control">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="playstore_link">Playstore Link</label>
                            <input type="text" id="playstore-link" name="playstore_link" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="appstore_link">Appstore Link</label>
                            <input type="text" id="appstore-link" name="appstore_link" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="web_link">Web Link</label>
                            <input type="text" id="web-link" name="web_link" class="form-control">
                        </div>    
                        <div class="form-group">
                            <label>Logo (biarkan kosong jika tidak ingin mengganti logo.)</label>
                            <div id="edit-image-preview" class="image-preview" style="width: 180px;">
                                <label for="edit-image-upload" id="edit-image-label">Pilih File</label>
                                <input type="file" name="image" id="edit-image-upload" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar Thumbnail (Popover Image)</label>
                            <div id="edit-thumb-preview" class="image-preview" style="width: 220px;">
                                <label for="edit-thumb-upload" id="edit-thumb-label">Pilih File</label>
                                <input type="file" name="thumb" id="edit-thumb-upload" required />
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
            input_field: "#thumb-upload",
            preview_box: "#thumb-preview",
            label_field: "#thumb-label",
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

        $.uploadPreview({
            input_field: "#edit-thumb-upload",
            preview_box: "#edit-thumb-preview",
            label_field: "#edit-thumb-label",
            label_default: "Pilih File",
            label_selected: "Ubah File",
            no_label: false,
            success_callback: null
        });

        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [5, "desc"]
            ],
            ajax: '{{ route('admin.product.datatable') }}',
            columns: [{
                    data: 'checkboxes',
                    name: 'checkboxes'
                },
                {
                    data: 'logo',
                    name: 'logo'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'is_displayed',
                    name: 'is_displayed'
                },
                {
                    name: 'created_at.timestamp',
                    data: {
                        _: 'created_at.display',
                        sort: 'created_at'
                    }
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],
            columnDefs: [{
                    'orderable': false,
                    'targets': [0, 1, 6]
                },
                {
                    'searchable': false,
                    'targets': [0, 1, 6]
                }
            ]
        });

        $(document).on('click', '.edit-button', function() {
            $('#edit-input').hide();
            $('#edit-spinner').show();
            var id = $(this).data('id');
            $.ajax({
                method: "POST",
                url: "{{ route('admin.product.get_edit') }}",
                data: {
                    id: id
                },
                success: function(result) {
                    if (result != null) {
                        $('#id').val(result.id);
                        $('#name').val(result.name);
                        $('#description').val(result.description);
                        $('#en-description').val(result.en_description);
                        $('#playstore-link').val(result.playstore_link);
                        $('#appstore-link').val(result.appstore_link);
                        $('#web-link').val(result.web_link);
                        $('#type').val(result.type);
                        $('#is_displayed').val(result.is_displayed);
                        $('#edit-spinner').hide();
                        $('#edit-input').fadeIn();
                    } else {
                        $('#edit-product-modal').modal('hide');
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
                        url: "{{ route('admin.product.destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            $('#products-table').DataTable().ajax.reload()
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
                            url: "{{ route('admin.product.destroy_all') }}",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#products-table').DataTable().ajax.reload();
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