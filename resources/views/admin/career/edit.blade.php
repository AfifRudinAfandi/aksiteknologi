@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@push('styles')
<style>
    #job-desc {
        height: 120px;
    }
</style>
@endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.career.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Ubah Karir</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.career.index') }}">Data Karir</a></div>
        <div class="breadcrumb-item active">Ubah Data</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Karir</h4>
                </div>
                <div class="card-body">
                    <form id="form" action="{{ route('admin.career.update', $career->id) }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Judul</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="hidden" name="uuid" value="{{ $career->requirement_uuid }}">
                                <input type="text" name="job_title" value="{{ $career->job_title }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Kategori</label>
                            <div class="col-sm-12 col-md-7">
                                <select id="category-select" name="category_id" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($category as $_category)
                                    <option value="{{ $_category->id }}" {{ $career->category_id == $_category->id ? 'selected' : '' }}>{{ $_category->category }}</option>
                                    @endforeach
                                </select>
                                <button id="add-category" class="btn btn-sm btn-primary mt-2"><i class="fa fa-plus mr-2"></i>Tambah Kategori</button>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Requirements</label>
                            <div class="col-sm-12 col-md-7">
                                <div class="accordion" id="accordionExample">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link px-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Basic Requirements
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="collapse basic-wrapper" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <button type="button" class="btn btn-sm btn-primary add-basic mb-2">Tambah Kolom</button>
                                        @if(count($basics) > 0)
                                        @foreach($basics as $basic)
                                        <div>
                                            <input type="text" name="basic[]" value="{{ $basic->content }}" class="form-control mb-2" />
                                            <button class="remove_field btn btn-md float-right mr-2" style="margin-top: -48px;"><i class="text-danger fa fa-times"></i></button>
                                        </div>
                                        @endforeach
                                        @else
                                        <input type="text" name="basic[]" class="form-control mb-2">
                                        @endif
                                    </div>

                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed px-0" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Specific Requirements
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="collapse specific-wrapper" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <button type="button" class="btn btn-sm btn-primary add-specific mb-2">Tambah Kolom</button>
                                        @if(count($specifics) > 0)
                                        @foreach($specifics as $specific)
                                        <div>
                                            <input type="text" name="specific[]" value="{{ $specific->content }}" class="form-control mb-2" />
                                            <button class="remove_field btn btn-md float-right mr-2" style="margin-top: -48px;"><i class="text-danger fa fa-times"></i></button>
                                        </div>
                                        @endforeach
                                        @else
                                        <input type="text" name="specific[]" class="form-control mb-2">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi Job</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea id="job-desc" name="job_desc" class="form-control" placeholder="Deskripsi Tambahan">{{ $career->job_desc }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-6">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

{!! $validator->selector('#form') !!}

<script src="{{ asset('/stisla/modules/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>

<script>
    CKEDITOR.replace('job-desc');

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

        $(document).on('click', '#add-category', function() {
            Swal.fire({
                title: 'Masukan nama Kategori',
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
                        url: "{{ route('admin.career.add_category') }}",
                        data: {
                            category: category
                        },
                        success: function(result) {
                            Swal.fire({
                                icon: result.status,
                                title: result.message,
                            });
                            updateCategory();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        function updateCategory() {
            $.ajax({
                type: "GET",
                url: "{{ route('admin.career.get_category') }}",
                success: function(result) {
                    if (result.length > 0) {
                        var html = [];
                        html += '<option value="">-- Pilih Kategori --</opton>'
                        $.each(result, function(index, row) {
                            html += '<option value="' + row.id + '">' + row.category + '</option>';
                        });
                        $('#category-select').html(html);
                    }
                }
            })
        }

        var max_fields = 10;
        $(".add-basic").click(function(e) { //on add input button click
            var x = 1;
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(".basic-wrapper").append('<div><input type="text" name="basic[]" class="form-control mb-2"/><button class="remove_field btn btn-md float-right mr-2" style="margin-top: -48px;"><i class="text-danger fa fa-times"></i></button></div>');
            }
        });

        $(".basic-wrapper").on("click", ".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });


        $(".add-specific").click(function(e) { //on add input button click
            var y = 1;
            e.preventDefault();
            if (y < max_fields) {
                y++;
                $(".specific-wrapper").append('<div><input type="text" name="specific[]" class="form-control mb-2"/><button class="remove_field btn btn-md float-right mr-2" style="margin-top: -48px;"><i class="text-danger fa fa-times"></i></button></div>');
            }
        });

        $(".specific-wrapper").on("click", ".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            y--;
        });

    });
</script>

@endpush