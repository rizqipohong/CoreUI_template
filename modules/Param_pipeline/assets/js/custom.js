function _modal_filter() {
    $('#fil_access_status').trigger('change');

    $('#modal-filter').modal('show');
}

let statusCard = document.querySelector('#ID_table');
// add scroll event listener for change head's position
statusCard.addEventListener('scroll', e => {
    let tableHead = document.querySelector('thead');
    let scrollTop = statusCard.scrollTop;
    tableHead.style.transform = 'translateY(' + scrollTop + 'px)';
})



$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})


$(document).on('click', '.clear-filter', function () {
    var url = baseUrl + '/clear_filter'
    var session = $(this).data('session');
    $.ajax({
        type: "POST",
        url: url,
        data: { session: session },
        dataType: "json",
        success: function (res) {
            location.reload(true);
        }
    });
})

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
            'id_pipeline': id,
        },
        success: function (response) {

            if (typeof response.message !== 'undefined') {
                toastr.error(response.error.message)

            } else {
                $("#formUpdate")[0].reset();
                $("#up_id").val(response.data.id_pipeline)
                $("#up_pipeline_origin").val(response.data.pipeline_origin);
                $("#up_level_of_priorities").val(response.data.level_of_priorities);
                $("#up_level_of_risk").val(response.data.level_of_risk);
                $("#up_tier").val(response.data.tier);

                $('#modal-update').modal('show');
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
    if (msgErrorGet) {
        toastr.error(msgErrorGet)
    }

    $('#ID_divtable').scroll(function () {

        var $el = $(this);
        if ($el.innerHeight() + $el.scrollTop() + 1 >= this.scrollHeight) {
            var row = Number($('#row').val());
            var allcount = Number($('#all_count').val());
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
                        $(".init-table:last").after(response).show().fadeIn("slow");
                    }
                });
            }
        }
    });

    jQuery.validator.methods.matches = function (value, element, params) {
        var re = new RegExp(params);
        return this.optional(element) || re.test(value);
    }
    var rules_input = {
        in_agent_type: {
            required: true,
        },
        in_level_of_priorities: {
            required: true,
        },
        in_level_of_risk: {
            required: true,
        },
        in_tier: {
            required: true,
        },
        in_detail_agent_type: {
            required: true,
        },
    };

    var messages_input = {
        in_agent_type: {
            required: "Asal Pipeline tidak boleh kosong",
        },
        in_level_of_priorities: {
            required: "Level of Priorities tidak boleh kosong",
        },
        in_level_of_risk: {
            required: "Level of Risk tidak boleh kosong",
        },
        in_tier: {
            required: "Tier tidak boleh kosong",
        },
        in_detail_agent_type: {
            required: "Detail tipe agent tidak boleh kosong",
        },
    };

    $("#formInput").validate({
        rules: rules_input,
        messages: messages_input,
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
                        $('#modal-input').modal("hide");
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

    var rules_update = {
        up_pipeline_origin: {
            required: true,
        },
        up_level_of_priorities: {
            required: true,
        },
        up_level_of_risk: {
            required: true,
        },
        up_tier: {
            required: true,
        },

    };

    var messages_update = {
        up_pipeline_origin: {
            required: "Asal pipeline tidak boleh kosong",
        },
        up_level_of_priorities: {
            required: "Level of Priorities tidak boleh kosong",
        },
        up_level_of_risk: {
            required: "Level of Risk tidak boleh kosong",
        },
        up_tier: {
            required: "Tier tidak boleh kosong",
        },
    };

    $("#formUpdate").validate({
        rules: rules_update,
        messages: messages_update,
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

});
