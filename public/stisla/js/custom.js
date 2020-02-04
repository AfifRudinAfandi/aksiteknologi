/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.check-datatable', function() {
    var length = $('.check-datatable').length;
    var totalchecked = 0;

    $('.check-datatable').each(function() {
        if ($(this).is(':checked')) {
            totalchecked += 1;
        }
    });

    if (totalchecked == length) {
        $('#check-all').prop('indeterminate', false);
        $("#check-all").prop('checked', true);
    } else if (totalchecked == 0) {
        $('#check-all').prop('indeterminate', false);
        $('#check-all').prop('checked', false);
    } else {
        $('#check-all').prop('indeterminate', true);
    }
});


$(document).on('change', '#check-all', function() {
    var checked = $('#check-all').prop('checked');
    $('.check-datatable').each(function() {
        if (checked == false) {
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }
    });
});

// Profile list
$(document).ready(function() {
    getActiveProfile();
    getPreviewProfile();
})

function getActiveProfile() {
    $.ajax({
        type: "GET",
        url: profile_route.get_active,
        success: function(result) {
            $('#active-profile-name').text(result.name);
        }
    });
}

function getPreviewProfile() {
    $.ajax({
        type: "GET",
        url: profile_route.get_preview,
        success: function(result) {
            $('#preview-profile-name').text(result.name);
        }
    });
}

function getProfile() {
    $('#profile-spinner').fadeIn();
    $.ajax({
        type: "GET",
        url: profile_route.get,
        success: function(result) {
            if (result.length > 0) {
                console.log(result)
                var html = [];
                var btn_delete = '';
                var btn_edit = '';
                var txt_color;
                $.each(result, function(index, row) {
                    if (row.name.toLowerCase() != 'default') {
                        btn_edit = '<button type="button" class="edit-profile btn btn-sm float-right" data-id="' + row.id + '" style="margin-top: -32px; margin-right: 30px;"><i class="fa fa-edit"></i></button>';
                        btn_delete = '<button type="button" class="delete-profile btn btn-sm float-right" data-id="' + row.id + '" style="margin-top: -32px; margin-right: 10px;"><i class="fa fa-trash-alt text-danger"></i></button>';
                    }
                    if (row.is_preview == 1) {
                        txt_color = 'text-primary';
                    } else {
                        txt_color = '';
                    }
                    html += '<a href="#" data-id="' + row.id + '" data-name="' + row.name + '" class="dropdown-item profile ' + txt_color + '">' + row.name + '</a>' + btn_edit + btn_delete;
                });
                $('#profile-list').html(html);
            } else {
                $('#profile-list').html('');
            }
            $('#profile-spinner').fadeOut();
        }
    });
}

$(document).on('click', '#btn-profile', function() {
    getProfile();
});

$(document).on('click', '.profile', function() {
    var id = $(this).data('id');

    $.ajax({
        type: "PATCH",
        url: profile_route.update_preview,
        data: { id: id },
        success: function(data) {
            Swal.fire({
                icon: data.status,
                text: data.message,
            });
            location.reload();
            getActiveProfile();
        }
    });

});

$(document).on('click', '#activate-profile', function() {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Pilih profil ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Aktifkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "PATCH",
                url: profile_route.update_active,
                data: { id: id },
                success: function(data) {
                    Swal.fire({
                        icon: data.status,
                        text: data.message,
                    });
                    location.reload();
                    getActiveProfile();
                }
            });
        }
    });
});

$(document).on('click', '#add-profile', function() {
    Swal.fire({
        title: 'Masukan nama profil',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Tambah',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: (profile) => {
            $.ajax({
                type: "POST",
                url: profile_route.store,
                data: { profile: profile },
                success: function(result) {
                    Swal.fire({
                        icon: result.status,
                        title: result.message,
                    });
                    getProfile();
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
});

$(document).on('click', '.edit-profile', function() {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Ubah nama profil',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Ubah',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: (profile) => {
            $.ajax({
                type: "PATCH",
                url: profile_route.update,
                data: {
                    id: id,
                    name: profile,
                },
                success: function(result) {
                    Swal.fire({
                        icon: result.status,
                        title: result.message,
                    });
                    getProfile();
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
});

$(document).on('click', '.delete-profile', function() {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Hapus profil ini?',
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
                url: profile_route.destroy,
                data: { id: id },
                success: function(data) {
                    Swal.fire({
                        icon: data.status,
                        text: data.message,
                    });
                    getProfile();
                }
            });
        }
    });
});