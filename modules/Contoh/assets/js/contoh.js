function _modal_filter() {
    $('#fil_access_status').trigger('change');

    $('#modal-filter').modal('show');
}

function _modal_input() {
    $('#modal-input').modal('show');
}

function _modal_demo() {
    $('#modal-demo').modal('show');
}

function _modal_update(id) {
    $.ajax({
        url: baseUrl + '/get_data',
        type: 'POST',
        dataType: 'json',
        data: {
            'id': id,
        },
        success: function (response) {
            if (response.success === true) {
                $("#formUpdate")[0].reset();
                $("#up_id").val(response.data.id);
                $("#up_nama").val(response.data.nama);
                $("#up_alamat").html(response.data.alamat);

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

$("#formExport").submit(function (e) {
    e.preventDefault();
    var user_export_pin = $("#ex_user_export_pin").val();

    if (user_export_pin) {
        $.ajax({
            url: baseUrl + '/check_pin_export',
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

function _delete(id) {
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
                url: baseUrl + '/delete_data',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                },
                success: function (response) {
                    if (response.success === true) {
                        $("#ID_divtable").load(window.location.href + " #ID_table");
                        $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                        toastr.success(response.message)
                        $('#row').val(0);
                    } else {
                        toastr.error(response.message)
                    }
                }
            });
        }
    })
}

function previewGuide() {
    $.ajax({
        // type: 'post',
        cache: false,
        url: informasi_url,
        success: function (data) {
            $('#preview-guide .modal-body').html(data);
            $('#preview-guide').modal('show');
        }
    });
}

$(document).ready(function () {
    $('#ID_divtable').scroll(function () {

        var $el = $(this);
        if ($el.innerHeight() + $el.scrollTop() >= this.scrollHeight) {
            var row = Number($('#row').val());
            var allcount = Number($('#all_contoh_count').val());
            var rowperpage = confLimit;
            row = row + rowperpage;
            if (row <= allcount) {
                $('.ajax-load').show();
                var url = baseUrl + "/pagination";
                $('#row').val(row);
                $.ajax({
                    url: url,
                    type: 'post',
                    data: { row: row },
                    success: function (response) {
                        $('.ajax-load').hide();
                        $(".contoh:last").after(response).show().fadeIn("slow");
                    }
                });
            }
        }
    });

    jQuery.validator.methods.matches = function (value, element, params) {
        var re = new RegExp(params);
        return this.optional(element) || re.test(value);
    }
    var rules = {
        nama: "required",
        alamat: "required",
    };

    var messages = {
        nama: "Please enter your nama",
        alamat: "Please enter your alamat",
    };

    $("#formInput").validate({
        rules: rules,
        messages: messages,
        submitHandler: function (form) {
            $.ajax({
                url: baseUrl + '/create_data',
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (response) {
                    if (response.success === true) {
                        $("#formInput")[0].reset();
                        $("#ID_divtable").load(window.location.href + " #ID_table");
                        $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                        toastr.success(response.message)
                        $('#row').val(0);
                    } else {
                        toastr.error(response.message)
                    }
                }
            });
            return false;
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });

    $("#formUpdate").validate({
        rules: rules,
        messages: messages,
        submitHandler: function (form) {

            $.ajax({
                url: baseUrl + '/update_data',
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (response) {
                    if (response.success === true) {
                        $("#ID_divtable").load(window.location.href + " #ID_table");
                        $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                        $('#modal-update').modal('hide');
                        $('#row').val(0);
                        toastr.success(response.message)
                    } else {
                        toastr.error(response.message)
                    }
                }
            });
            return false;
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });



    //for demo
    $("#form-demo").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            username: {
                required: true,
                minlength: 2
            },
            phone: {
                matches: "^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$",
                required: true,
                minlength: 10,
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            agree: "required"
        },
        messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 2 characters"
            },
            phone: {
                matches: "Please enter valid a phone",
                required: "Please enter a phone",
                minlength: "Your phone must consist of at least 10 characters",
                maxlength: "Your phone must consist of at least 10 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address",
            agree: "Please accept our policy"
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });

});