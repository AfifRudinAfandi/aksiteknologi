@extends('admin.setting.index')

@section('content')
<div class="card" id="settings-card">
    <div class="card-header">
        <h4>Profil Perusahaan</h4>
    </div>
    <form id="form" class="mb-0" action="{{ route('admin.setting.profile.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="form-profile" class="card-body px-5">
            <div class="form-group row align-items-center">
                <label for="name" class="form-control-label col-sm-3">Nama <br><span class="text-mute">*ditampilkan di beberapa halaman website</span></label>
                <div class="col-sm-6 col-md-9">
                    <input type="hidden" name="id" value="{{ !empty($profile->id) ? $profile->id : '' }}">
                    <input type="text" name="name" value="{{ !empty($profile->name) ? $profile->name : '' }}" placeholder="Nama organisasi atau perusahaan" class="form-control">
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="name" class="form-control-label col-sm-3">Nama Perseroan (PT/CV)</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="company_name" value="{{ !empty($profile->company_name) ? $profile->company_name : '' }}" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="form-control-label col-sm-3 mt-2">Logo</label>
                <div class="col-sm-6 col-md-9">
                    <div id="image-preview" class="image-preview" style="width: 240px; background-image: url('{{ !empty($profile->name) ? $profile->getFirstMediaUrl('images') : '' }}'); background-size: cover; background-color: #dee2e6;">
                        <label for="image-upload" id="image-label">Pilih File</label>
                        <input type="file" name="image" id="image-upload" />
                    </div>
                    @if(method_exists($profile, 'getFirstMediaUrl') && $profile->getFirstMediaUrl('images') != null)
                        <button id="delete-logo" type="button" class="btn btn-sm btn-danger mt-2">
                            Hapus Logo
                        </button>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="form-control-label col-sm-3 mt-2">Alamat</label>
                <div class="col-sm-6 col-md-9">
                    <textarea class="form-control" name="address" placeholder="Alamat lengkap">{{ !empty($profile->address) ? $profile->address : '' }}</textarea>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="map" class="form-control-label col-sm-3">Google Maps Link</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="map" value="{{ !empty($profile->map) ? $profile->map : '' }}" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="about_us" class="form-control-label col-sm-3 mt-2">Tentang Kami</label>
                <div class="col-sm-6 col-md-9">
                    <textarea name="about_us" class="form-control">{{ !empty($profile->about_us) ? $profile->about_us : '' }}</textarea>
                </div>
            </div>
            <div class="mt-5 mb-4">
                <h6>Kontak</h6>
                <hr class="mt-0">
            </div>
            <div class="form-group row align-items-center">
                <label for="email" class="form-control-label col-sm-3">Email</label>
                <div class="col-sm-6 col-md-9">
                    <input type="email" name="email" value="{{ !empty($profile->email) ? $profile->email : '' }}" class="form-control">
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="phone" class="form-control-label col-sm-3">Nomor Telepon</label>
                <div class="col-sm-6 col-md-9">
                    <input type="tel" name="phone" value="{{ !empty($profile->phone) ? $profile->phone : '' }}" class="form-control">
                </div>
            </div>
            <div class="mt-5 mb-4">
                <h6>Visi dan Misi</h6>
                <hr class="mt-0">
            </div>
            <div class="form-group row">
                <label for="vision" class="form-control-label col-sm-3 mt-2">Visi</label>
                <div class="col-sm-6 col-md-9">
                    <textarea name="vision" class="form-control">{{ !empty($profile->vision) ? $profile->vision : '' }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="form-control-label col-sm-3 mt-2">Misi</label>
                <div class="col-sm-6 col-md-9">
                    <input type="hidden" name="mission">
                    <button type="button" id="add-mission" class="btn btn-sm btn-success mt-2"><i class="fa fa-plus mr-2"></i>Tambah</button>
                    <div class="mt-4">
                        <div id="mission-spinner">
                            <div class="spinner-grow" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <ul id="list-mission" class="list-group list-group-flush">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-whitesmoke text-md-right pr-5">
            <button class="btn btn-primary" id="save-btn">Simpan</button>
        </div>
    </form>
</div>

<form id="form-delete" action="{{ route('admin.setting.profile.destroyLogo', !empty($profile->id) ? $profile->id : '') }}" method="POST" class="form-inline">
    @csrf
    @method('delete')
</form>

@endsection

@push('scripts')

<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
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

        $(document).on('click', '#delete-logo', function(e) {
            $('#form-delete').submit();
            e.preventDevault();
        })

        getMission();

        function getMission() {
            $('#mission-spinner').fadeIn();
            $.ajax({
                method: "GET",
                url: "{{ route('admin.setting.mission.index') }}",
                success: function(result) {
                    if (result != '') {
                        var list = [];
                        $.each(result, function(index, row) {
                            list += '<li class="list-group-item px-0 py-2"><div class="label-mission">' + row.content + '</div><button type="button" class="btn btn-sm px-0 edit-mission mt-2 mr-2" data-id="' + row.id + '"><i class="fa fa-edit mr-2"></i>Ubah</button><button type="button" class="btn btn-sm px-0 delete-mission mt-2" data-id="' + row.id + '"><i class="fa fa-trash-alt text-danger mr-2"></i>Hapus</button></li>';
                        })

                        $('#list-mission').html(list)
                    } else {
                        $('#list-mission').html('<li class="list-group-item px-0 py-2">Tidak ada data</li>');
                    }
                    $('#mission-spinner').fadeOut();
                }
            })
        }

        $(document).on('click', '#add-mission', function() {
            Swal.fire({
                input: 'textarea',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: (mission) => {
                    if (mission != '') {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('admin.setting.mission.store') }}",
                            data: {content: mission},
                            success: function(result) {
                                Swal.fire({
                                    icon: result.status,
                                    title: result.message,
                                });
                                getMission();
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        $(document).on('click', '.edit-mission', function() {
            var label = $(this).siblings('.label-mission');
            var id = $(this).data('id');

            Swal.fire({
                input: 'textarea',
                inputValue: label.text(),
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Ubah',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: (mission) => {
                    if (mission != '') {
                        $.ajax({
                            method: "PATCH",
                            url: "{{ route('admin.setting.mission.update') }}",
                            data: {
                                id: id,
                                content: mission
                            },
                            success: function(result) {
                                Swal.fire({
                                    icon: result.status,
                                    title: result.message,
                                });
                                getMission();
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });

        $(document).on('click', '.delete-mission', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Hapus misi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "DELETE",
                        url: "{{ route('admin.setting.mission.destroy') }}",
                        data: {id: id},
                        success: function(result) {
                            Swal.fire({
                                icon: result.status,
                                title: result.message,
                            });
                            getMission();
                        }
                    })
                }
            });
        });
    });
</script>

@endpush
