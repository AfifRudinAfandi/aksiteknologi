@extends('layouts.admin')

@section('wrapper')
main-wrapper-2
@endsection

@stack('styles')

@section('content_header')
<div class="section-header">
    <h1>General Settings</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Setting</div>
    </div>
</div>
@endsection

@section('content_body')
<div class="section-body">
    <h2 class="section-title">All About General Settings</h2>
    <p class="section-lead">
        You can adjust all general settings here
    </p>

    <div id="output-status"></div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Pengaturan</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.setting.profile') }}" class="nav-link {{ (Route::is('admin.setting.profile') ? 'active' : '') }}">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.setting.social') }}" class="nav-link {{ (Route::is('admin.setting.social') ? 'active' : '') }}">Sosial Media</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.setting.web') }}" class="nav-link {{ (Route::is('admin.setting.web') ? 'active' : '') }}">Web Setting</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @yield('content')
        </div>
    </div>
</div>
@endsection