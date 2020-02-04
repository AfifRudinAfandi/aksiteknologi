@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/stisla/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.post.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Buat Post Baru</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.post.index') }}">Semua Post</a></div>
        <div class="breadcrumb-item active">Buat</div>
    </div>
</div>
@endsection

@section('content_body')
<form id="form" action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-12">
                                <input type="text" name="title" placeholder="Judul Post" class="form-control mb-2">
                                <div class="dropdown show float-left">
                                    <button type="button" id="btn-category" class="btn has-dropdown" data-toggle="dropdown" aria-expanded="true">
                                        <i class="fa fa-quote-right mr-2"></i><span id="label-category">Kategori</span><i class="fa fa-chevron-down pl-2"></i>
                                    </button>
                                    <input type="hidden" name="category_id" id="category-id" value="">
                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="#" id="add-category" class="dropdown-item has-icon">
                                            <i class="fas fa-plus"></i> Tambah Kategori
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <div id="category-spinner" class="dropdown-spinner">
                                            <div class="spinner-grow" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div id="category-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea id="editor" name="content"></textarea>
                        <div class="form-group row mt-2">
                            <label class="col-12">Tag (pisahkan dengan tanda koma ","):</label>
                            <div class="col-12">
                                <input type="text" name="tag" placeholder="Tag" class="inputtags">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Gambar</h4>
                    </div>
                    <div class="card-body">
                        <div id="image-preview" class="image-preview">
                            <label for="image-upload" id="image-label">Pilih File</label>
                            <input type="file" name="image" id="image-upload" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Publish</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">Status Post:</label>
                            <select name="status" class="form-control">
                                <option value="draft">Draft</option>
                                <option value="published">Publish</option>
                                <option value="archived">Archive</option>
                            </select>
                        </div>
                        <div class="media mb-4">
                            <figure class="avatar mr-2 bg-primary text-white" data-initial=""></figure>
                            <div class="media-body">
                                <div class="mt-0">Author:</div>
                                <div class="mt-0 mb-1 font-weight-bold">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('/stisla/modules/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('/stisla/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

{!! $validator->selector('#form') !!}

<script>
    CKEDITOR.replace('editor');

    $("#form").submit(function(e) {
        var content_length = CKEDITOR.instances['editor'].getData().replace(/<[^>]*>/gi, '').length;
        if (!content_length) {
            Swal.fire({
                icon: 'warning',
                text: 'Konten tidak boleh kosong.',
            });
        }
    });

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

        $(".inputtags").tagsinput('items');

        function getCategory() {
            $('#category-spinner').fadeIn();
            $.ajax({
                type: "GET",
                url: "{{ route('admin.post.get_category') }}",
                success: function(result) {
                    if (result.length > 0) {
                        var html = [];
                        $.each(result, function(index, row) {
                            html += '<a href="#" data-id="' + row.id + '" data-name="' + row.category + '" class="dropdown-item category">' + row.category + '</a><button type="button" class="delete-category btn btn-sm float-right" data-id="' + row.id + '" style="margin-top: -32px; margin-right: 10px;"><i class="fa fa-times text-danger"></i></button>'
                        });
                        $('#category-list').html(html);
                    } else {
                        $('#category-list').html('');
                    }
                    $('#category-spinner').fadeOut();
                }
            });
        }

        $(document).on('click', '#btn-category', function() {
            getCategory();
        });

        $(document).on('click', '#add-category', function() {
            Swal.fire({
                title: 'Masukan nama kategori',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: (category) => {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.post.add_category') }}",
                        data: {
                            category: category
                        },
                        success: function(result) {
                            Swal.fire({
                                icon: result.status,
                                title: result.message,
                            });
                            getCategory();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        $(document).on('click', '.category', function() {
            $('input#category-id').val($(this).data('id'));
            $('#label-category').text($(this).data('name'));
        });

        $(document).on('click', '.delete-category', function() {
            var id = $(this).data('id');
            var txt_id = $('#category-id').val();

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
                        url: "{{ route('admin.post.destroy_category') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            getCategory();
                            if (id == txt_id) {
                                $('#category-id').val('');
                                $('#label-category').text('Kategori');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush