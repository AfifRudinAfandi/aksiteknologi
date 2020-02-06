@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@push('styles')
    <style>
        textarea.bio {
            height: 100px !important;
        }
    </style>
@endpush

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.team.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Tambah Data</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.team.index') }}">Manajemen Team</a></div>
        <div class="breadcrumb-item active">Tambah Data</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Team</h4>
                </div>
                <div class="card-body">
                    <form id="form" action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Divisi</label>
                            <div class="col-sm-12 col-md-7">
                                <select id="division-select" name="division_id" class="form-control">
                                    <option value="">-- Pilih Divisi --</option>
                                </select>
                                <button id="add-division" class="btn btn-sm btn-primary mt-2"><i class="fa fa-plus mr-2"></i>Tambah Divisi</button>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Sosial Media</label>
                            <div class="col-sm-12 col-md-3">
                                <select name="social_provider" class="form-control">
                                    <option value="">-- Pilih Sosial Media --</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">Youtube</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="google+">Google+</option>
                                    <option value="Dribbble">Dribbble</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <input type="text" name="social_link" placeholder="Sosial media link (ex: moherik)" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Bio</label>
                            <div class="col-sm-12 col-md-7">
                                <ul class="nav nav-pills" id="bioTab" role="tablist">
                                    <li class="nav-item">
                                      <a class="nav-link active" id="id-tab" data-toggle="tab" href="#id" role="tab" aria-controls="id" aria-selected="true">ID</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="true">EN</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="bioTabContent">
                                    <div class="tab-pane fade show active" id="id" role="id" aria-labelledby="id-tab">
                                        <textarea name="bio" class="form-control bio"></textarea>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <textarea name="en_bio" class="form-control bio"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto Profil</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview" style="width: 150px;">
                                    <label for="image-upload" id="image-label">Pilih File</label>
                                    <input type="file" name="image" id="image-upload" />
                                </div>
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

<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>

<script>
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
                            updateDivision();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        updateDivision();

        function updateDivision() {
            $.ajax({
                type: "GET",
                url: "{{ route('admin.team.division.index') }}",
                success: function(result) {
                    if(result.length > 0) {
                        var html = [];
                        html += '<option value="">-- Pilih Divisi --</opton>'
                        $.each(result, function(index, row) {
                            html += '<option value="'+row.id+'">'+row.name+'</option>';
                        });
                        $('#division-select').html(html);
                    }
                }
            })
        }

    });
</script>

@endpush