@extends('admin.setting.index')

@section('content')
    <div class="card" id="settings-card">
        <div class="card-header">
            <h4>Web Setting</h4>
        </div>
        <form id="form-web" action="{{ route('admin.setting.web.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body px-5">
                <div class="form-group row align-items-center">
                    <label for="site-title" class="form-control-label col-sm-3">Judul Website</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="hidden" name="id" value="{{ !empty($web->id) ? $web->id : '' }}">
                        <input type="text" name="site_title" value="{{ !empty($web->site_title) ? $web->site_title : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="site_partner_des" class="form-control-label col-sm-3" style="margin-top: 10px;">Deskripsi Service</label>
                    <div class="col-sm-6 col-md-9">
                        <ul class="nav nav-pills" id="serviceTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="id-tab" data-toggle="tab" href="#id" role="tab" aria-controls="id" aria-selected="true">ID</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="true">EN</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="serviceTabContent">
                            <div class="tab-pane fade show active" id="id" role="id" aria-labelledby="id-tab">
                                <textarea name="site_service_desc" class="form-control">{{ !empty($web->site_service_desc) ? $web->site_service_desc : '' }}</textarea>
                            </div>
                            <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                <textarea name="site_en_service_desc" class="form-control">{{ !empty($web->site_en_service_desc) ? $web->site_en_service_desc : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="site_partner_desc" class="form-control-label col-sm-3" style="margin-top: 10px;">Deskripsi Partner</label>
                    <div class="col-sm-6 col-md-9">
                        <ul class="nav nav-pills" id="partnerTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="partner-id-tab" data-toggle="tab" href="#partner-id" role="tab" aria-controls="partner-id" aria-selected="true">ID</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="partner-en-tab" data-toggle="tab" href="#partner-en" role="tab" aria-controls="partner-en" aria-selected="true">EN</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="partnerTabContent">
                            <div class="tab-pane fade show active" id="partner-id" role="partner-id" aria-labelledby="partner-id-tab">
                                <textarea name="site_partner_desc" class="form-control">{{ !empty($web->site_partner_desc) ? $web->site_partner_desc : '' }}</textarea>
                            </div>
                            <div class="tab-pane fade" id="partner-en" role="tabpanel" aria-labelledby="partner-en-tab">
                                <textarea name="site_en_partner_desc" class="form-control">{{ !empty($web->site_en_partner_desc) ? $web->site_en_partner_desc : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-control-label col-sm-3 mt-2">Logo</label>
                    <div class="col-sm-6 col-md-9">
                        @php
                            if(method_exists($web, 'getFirstMediaUrl')) {
                                $logo = !empty($web->getFirstMediaUrl('images')) ? $web->getFirstMediaUrl('images') : null;
                                $favicon = !empty($web->getFirstMediaUrl('favicon', 'favicon')) ? $web->getFirstMediaUrl('favicon', 'favicon') : null;
                            } else {
                                $logo = null;
                                $favicon = null;
                            }
                        @endphp
                        <div id="logo-preview" class="image-preview" style="width: 240px; background-image: url('{{ $logo }}'); background-size: cover; background-color: #dee2e6;">
                            <label for="image-upload" id="logo-label">Pilih File</label>
                            <input type="file" name="logo" id="logo-upload" />
                        </div>
                        @if(!empty($logo))
                            <button id="delete-logo" type="button" class="btn btn-sm btn-danger mt-2">
                                Hapus Logo
                            </button>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-control-label col-sm-3 mt-2">Favicon</label>
                    <div class="col-sm-6 col-md-9 d-flex">
                        @if(!empty($favicon))<img src="{{ $favicon }}" alt="favicon" width="32px" height="32px" class="mr-2"/>@endif
                        <div id="favicon-preview" class="image-preview" style="height: 32px; width: 32px;">
                            <label for="image-upload" id="favicon-label">Pilih File</label>
                            <input type="file" name="favicon" id="favicon-upload" />
                        </div>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="lang" class="form-control-label col-sm-3">Bahasa</label>
                    <div class="col-sm-6 col-md-9">
                        <select name="lang" class="form-control">
                            <option value="id" {{ !empty($web->lang) && $web->lang == 'id' ? 'selected' : '' }}>Indonesia</option>
                            <option value="en" {{ !empty($web->lang) && $web->lang == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="analytic_view_id" class="form-control-label col-sm-3">Google Analytic View ID</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="analytic_view_id" value="{{ !empty($web->analytic_view_id) ? $web->analytic_view_id : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="form-control-label col-sm-3 mt-2">Google Analytic Scripts</label>
                    <div class="col-sm-6 col-md-9">
                        <textarea class="form-control" name="ga_scripts" placeholder="Tempel scripts disini">{{ !empty($web->ga_scripts) ? $web->ga_scripts : '' }}</textarea>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-whitesmoke text-md-right pr-5">
                <button class="btn btn-primary" id="save-btn">Simpan</button>
            </div>
        </form>
    </div>

    <form id="form-delete" action="{{ route('admin.setting.web.destroyLogo', !empty($web->id) ? $web->id : '') }}" method="POST" class="form-inline">
        @csrf
        @method('delete')
    </form>


@endsection

@push('scripts')

<script src="{{ asset('/stisla/modules/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
<script type="text/javascript">
    $(function() {

        $.uploadPreview({
            input_field: "#logo-upload",
            preview_box: "#logo-preview",
            label_field: "#logo-label",
            label_default: "Pilih File",
            label_selected: "Ubah File",
            no_label: false,
            success_callback: null
        });

        $(document).on('click', '#delete-logo', function(e) {
            $('#form-delete').submit();
            e.preventDevault();
        })

        $.uploadPreview({
            input_field: "#favicon-upload",
            preview_box: "#favicon-preview",
            label_field: "#favicon-label",
            label_default: "Pilih File",
            label_selected: "Ubah File",
            no_label: false,
            success_callback: null
        });

    });
</script>
@endpush
