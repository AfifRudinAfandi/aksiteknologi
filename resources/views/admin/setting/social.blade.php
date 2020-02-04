@extends('admin.setting.index')

@section('content')
    <div class="card" id="settings-card">
        <div class="card-header">
            <h4>Sosial Media</h4>
        </div>
        <form id="form" action="{{ route('admin.setting.social.save') }}" method="POST">
            @csrf
            <div class="card-body px-5">
                <div class="form-group row align-items-center">
                    <label for="facebook" class="form-control-label col-sm-3">Facebook</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="hidden" name="id" value="{{ !empty($social->id) ? $social->id : '' }}">
                        <input type="text" name="facebook" value="{{ !empty($social->facebook) ? $social->facebook : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="instagram" class="form-control-label col-sm-3">Instagram</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="instagram" value="{{ !empty($social->instagram) ? $social->instagram : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="youtube" class="form-control-label col-sm-3">Youtube</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="youtube" value="{{ !empty($social->youtube) ? $social->youtube : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="twitter" class="form-control-label col-sm-3">Twitter</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="twitter" value="{{ !empty($social->twitter) ? $social->twitter : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="linkedin" class="form-control-label col-sm-3">LinkedIn</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="linkedin" value="{{ !empty($social->linkedin) ? $social->linkedin : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="google" class="form-control-label col-sm-3">Google+</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="google" value="{{ !empty($social->google) ? $social->google : '' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="dribbble" class="form-control-label col-sm-3">Dribbble</label>
                    <div class="col-sm-6 col-md-9">
                        <input type="text" name="dribbble" value="{{ !empty($social->dribbble) ? $social->dribbble : '' }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-footer bg-whitesmoke text-md-right pr-5">
                <button class="btn btn-primary" id="save-btn">Simpan</button>
            </div>
        </form>
    </div>
@endsection