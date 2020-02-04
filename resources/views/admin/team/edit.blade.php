@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.team.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Ubah Data</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.team.index') }}">Manajemen Team</a></div>
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
                    <h4>Data Team</h4>
                </div>
                <div class="card-body">
                    <form id="form" action="{{ route('admin.team.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="name" value="{{ $team->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Divisi</label>
                            <div class="col-sm-12 col-md-7">
                                <select id="division-select" name="division_id" class="form-control">
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $division->id == $team->division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="add-division" class="btn btn-sm btn-primary mt-2"><i class="fa fa-plus mr-2"></i>Tambah Divisi</button>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email" value="{{ $team->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Sosial Media</label>
                            <div class="col-sm-12 col-md-3">
                                <select name="social_provider" class="form-control">
                                    <option value="">-- Pilih Sosial Media --</option>
                                    <option value="facebook" {{ $team->social_provider == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="twitter" {{ $team->social_provider == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                    <option value="instagram" {{ $team->social_provider == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="youtube" {{ $team->social_provider == 'youtube' ? 'selected' : '' }}>Youtube</option>
                                    <option value="linkedin" {{ $team->social_provider == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="google+" {{ $team->social_provider == 'google+' ? 'selected' : '' }}>Google+</option>
                                    <option value="dribbble" {{ $team->social_provider == 'dribbble' ? 'selected' : '' }}>Dribbble</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <input type="text" name="social_link" placeholder="Sosial media link (ex: moherik)" value="{{ $team->social_link }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Bio</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea name="bio" class="form-control">{{ $team->bio }}</textarea>
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