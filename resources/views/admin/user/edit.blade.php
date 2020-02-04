@extends('layouts.admin')

@section('wrapper')
main-wrapper-1
@endsection

@section('content_header')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.user.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Ubah Data</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="{{ route('admin.user.index') }}">Manajemen Admin</a></div>
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
                    <h4>Data Admin</h4>
                </div>
                <div class="card-body">
                    <form id="form" action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">E-Mail</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Pengguna</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="username" value="{{ $user->username }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                            <div class="col-sm-12 col-md-7">
                                <select name="role" class="form-control">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ubah Kata Sandi</label>
                            <div class="col-sm-12 col-md-4">
                                <input type="password" name="password" id="password" class="form-control" data-indicator="pwindicator">
                            </div>
                            <div id="pwindicator" class="col-sm-12 col-md-3 pwindicator mt-3">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ulangi Kata Sandi</label>
                            <div class="col-sm-12 col-md-4">
                                <input type="password" name="password_confirmation" class="form-control">
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

<!-- Password strength indicator modules -->
<script src="{{ asset('/stisla/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>

<script type="text/javascript">
    $(function() {
        $("#password").pwstrength();
    });
</script>
@endpush