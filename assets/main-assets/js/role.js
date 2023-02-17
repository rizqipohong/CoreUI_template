function _modal_filter() {
    $("#fil_role_name").val(role_name);
    $('#fil_access_status').val(access_status);
    $('#fil_access_status').trigger('change');

    $('#modal-filter').modal('show');
}

function _modal_input() {
    $('#modal-input').modal('show');
}

function _modal_update(role_id) {
    $.ajax({
        url: getUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            'role_id': role_id,
        },
        success: function (response) {
            if (response.success === true) {
                $("#formUpdate")[0].reset();
                $("#up_role_id").val(response.data.id);
                $("#up_role_name").val(response.data.name);
                $("#up_role_display_name").val(response.data.display_name);
                $("#up_role_description").html(response.data.description);

                if (response.data.status == '1') {
                    $("#up_radio_1").attr('checked', true);
                    $("#up_radio_2").attr('checked', false);
                } else {
                    $("#up_radio_1").attr('checked', false);
                    $("#up_radio_2").attr('checked', true);
                }

                $('#modal-update').modal('show');
            } else {
                toastr.error(response.message)
            }
        }
    });
}

function _modal_export() {
    $('#modal-export').modal('show');
}

$("#formInput").submit(function (e) {
    e.preventDefault();
    var role_name = $("#role_name").val();

    if (role_name.length >= 3 && role_name.length <= 30) {
        $.ajax({
            url: createUrl,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success === true) {
                    $("#formInput")[0].reset();
                    $("#ID_divtable").load(window.location.href + " #ID_table");
                    $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                    toastr.success(response.message)
                } else {
                    toastr.error(response.message)
                }
            }
        });
    } else {
        toastr.error("Panjang karakter minimal 3 & maksimal 30")
    }
});

$("#formUpdate").submit(function (e) {
    e.preventDefault();
    var role_name = $("#up_role_name").val();

    if (role_name.length >= 3 && role_name.length <= 30) {
        $.ajax({
            url: updateUrl,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success === true) {
                    $("#ID_divtable").load(window.location.href + " #ID_table");
                    $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                    $('#modal-update').modal('hide');
                    toastr.success(response.message)
                } else {
                    toastr.error(response.message)
                }
            }
        });
    } else {
        toastr.error("Panjang karakter minimal 3 & maksimal 30")
    }
});

$("#formExport").submit(function (e) {
    e.preventDefault();
    var user_export_pin = $("#ex_user_export_pin").val();

    if (user_export_pin) {
        $.ajax({
            url: exportUrl,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success === true) {
                    $('#modal-export').modal('hide');
                    toastr.success(response.message)
                    window.open(response.exportURL, '_blank');
                } else {
                    toastr.error(response.message)
                }
            }
        });
    } else {
        toastr.error("Anda belum memasukan PIN!")
    }
});

function _delete(role_id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger btn-confirmation',
            cancelButton: 'btn btn-default btn-confirmation'
        },
        buttonsStyling: false,
    })

    swalWithBootstrapButtons.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak akan dapat mengembalikan ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus'
    }).then((result) => {
        if (result.isConfirmed) {
        $.ajax({
            url: deleteUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                'role_id': role_id,
            },
            success: function(response) {
                if (response.success === true) {
                    $("#ID_divtable").load(window.location.href + " #ID_table");
                    $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                    toastr.success(response.message)
                } else {
                    toastr.error(response.message)
                }
            }
        });
    }
})
}