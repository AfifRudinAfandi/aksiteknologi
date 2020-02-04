@extends('layouts.admin')

@section('wrapper')
main-wrapper-2
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>
@endpush

@section('content_header')
    <div class="section-header">
        <h1>Manajemen Admin</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item active">Manajemen Admin</div>
        </div>
    </div>
@endsection

@section('content_body')
    <div class="section-body">
        <h2 class="section-title">Administrator</h2>
        <p class="section-lead">Data dari semua administrator yang terdaftar.</p>

        <div class="card">
            <div class="card-header">
                <h4>Data Administrator</h4>
                <div class="card-header-action">
                    <div class="dropdown show float-left">
                        <button type="button" id="btn-role" class="btn has-dropdown" data-toggle="dropdown" aria-expanded="true">
                            <!-- <i class="fa fa-quote-right mr-2"></i> -->
                            {{ isset($roleId) ? $roleName : 'Pilih Role' }}<i class="fa fa-chevron-down pl-2"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" id="add-role" data-toggle="modal" data-target="#insert-role-modal" class="dropdown-item has-icon">
                                <i class="fas fa-plus"></i> Tambah Role
                            </a>
                            <div class="dropdown-divider"></div>
                            <div id="role-spinner" class="dropdown-spinner">
                                <div class="spinner-grow" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div id="role-list"></div>
                        </div>
                    </div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i> Tambah</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-md" id="users-table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="cb-all" class="custom-control-input" id="check-all">
                                        <label for="check-all" class="custom-control-label">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Nama</th>
                                <th>Nama Pengguna</th>
                                <th>E-Mail</th>
                                <th>Role</th>
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
    <div class="modal fade" role="dialog" id="userModal">
        <div class="modal-dialog" role="document">
            <form id="form" action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-at"></i>
                                    </div>
                                </div>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Pengguna (digunakan untuk login)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <input type="text" name="username" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <select name="role" class="form-control">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kata Sandi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <input type="password" name="password" id="password" class="form-control" data-indicator="pwindicator">
                            </div>
                            <div id="pwindicator" class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ulangi Kata Sandi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <input type="password" name="password_confirmation" class="form-control">
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

    <div class="modal fade" role="dialog" id="insert-role-modal">
        <div class="modal-dialog" role="document">
            <form id="edit-form" action="{{ route('admin.user.role.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <hr>
                        <h5>Permission</h5>
                        <ul>
                            @foreach($permissions as $p)
                                <li>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="cb-p" class="custom-control-input" id="icb-{{ $p->id }}" name="permissions[]" value="{{ $p->name }}">
                                        <label for="icb-{{ $p->id }}" class="custom-control-label">{{ $p->name }}</label>
                                    </div>                                    
                                </li>
                            @endforeach                            
                        </ul>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="edit-role-modal">
        <div class="modal-dialog" role="document">
            <form id="edit-form" action="{{ route('admin.user.role.update') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Role</h5>
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
                                <input type="hidden" name="id" id="role-id">
                                <input type="text" name="name" id="role-name" class="form-control">
                            </div>
                            <hr>
                            <h5>Permission</h5>
                            <ul>
                                @foreach($permissions as $p)
                                    <li>
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="cb-p" class="custom-control-input cbp-{{ $p->id }}" id="cb-{{ $p->id }}" name="permissions[]" value="{{ $p->name }}">
                                            <label for="cb-{{ $p->id }}" class="custom-control-label">{{ $p->name }}</label>
                                        </div>                                    
                                    </li>
                                @endforeach                            
                            </ul>
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

{!! $validator->selector('#form') !!}

<!-- DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

<!-- Password strength indicator modules -->
<script src="{{ asset('/stisla/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>

<script type="text/javascript">
    $(function() {

        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 4, "desc" ]],
            ajax: '{{ route('admin.user.datatable') }}',
            columns: [
                { data: 'checkboxes', name: 'checkboxes' },
                { data: 'name', name: 'name' },
                { data: 'username', name: 'username' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: {
                    _: 'created_at.display',
                    sort: 'created_at',
                }, name: 'created_at.timestamp' },
                { data: 'actions', name: 'actions' }
            ],
            columnDefs: [
                {'orderable': false, 'targets': [0,6]},
                {'searchable': false, 'targets': [0,6]}
            ]
        });

        $(document).on('click', '.delete-button', function (e) {
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
                        url: "{{ route('admin.user.destroy') }}",
                        data: {id:id},
                        success: function (data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            $('#users-table').DataTable().ajax.reload()
                        }         
                    });
                }
            })
        });

        $(document).on('click', '#bulk-delete', function(){
            var id = [];
            $('.check-datatable:checked').each(function(){
                id.push($(this).val());
            });
            if(id.length > 0) {
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
                            url: "{{ route('admin.user.destroy_all') }}",
                            data: {id:id},
                            success:function(data) {
                                Swal.fire({
                                    icon: data.status,
                                    text: data.message,
                                });
                                $('#users-table').DataTable().ajax.reload();
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

        function getRole() {
            $('#role-spinner').fadeIn();
            $.ajax({
                type: "GET",
                url: "{{ route('admin.user.role.index') }}",
                success: function(result) {
                    if(result.length > 0) {
                        var html = [];
                        var btn = '';
                        $.each(result, function(index, row){
                            if(row.name != 'Super Admin'){
                                btn = '<button type="button" class="edit-role btn btn-sm float-right" data-id="'+row.id+'" data-target="#edit-role-modal" data-toggle="modal" style="margin-top: -32px; margin-right: 34px;"><i class="fa fa-edit"></i></button><button type="button" class="delete-role btn btn-sm float-right" data-id="'+row.id+'" style="margin-top: -32px; margin-right: 10px;"><i class="fa fa-trash-alt text-danger"></i></button>';
                            }
                            html+= '<a href="#" data-id="'+row.id+'" data-name="'+row.name+'" class="dropdown-item role">'+row.name+'</a>'+btn;
                        });
                        $('#role-list').html(html);
                    } else {
                        $('#role-list').html('');
                    }
                    $('#role-spinner').fadeOut();
                }
            });
        }

        $(document).on('click', '#btn-role', function() {
            getRole();
        });

        $(document).on('click', '.edit-role', function() {
            $('#edit-input').hide();
            $('#edit-spinner').show();

            var id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: "{{ route('admin.user.role.get_edit') }}",
                data: { id: id },
                success: function(result) {
                    console.log(result);
                    if (result != null) {
                        $.each(result.permissions, function(index, row) {
                            $('.cbp-'+row.id).prop('checked', true);
                        });
                        $('#role-id').val(result.id);
                        $('#role-name').val(result.name);
                        $('#edit-spinner').hide();
                        $('#edit-input').fadeIn();
                    } else {
                        $('#edit-role-modal').modal('hide');
                        Swal.fire({
                            icon: 'warning',
                            text: 'Terjadi kesalahan!',
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete-role', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Hapus role ini?',
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
                        url: "{{ route('admin.user.role.destroy') }}",
                        data: {id:id},
                        success:function(data) {
                            Swal.fire({
                                icon: data.status,
                                text: data.message,
                            });
                            getRole();
                        }
                    });
                }
            });
        });

        $("#password").pwstrength();
    });
</script>
@endpush