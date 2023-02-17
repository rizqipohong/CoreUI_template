function _update(menu_id) {
    $.ajax({
        url: getUpdateUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            'menu_id': menu_id,
        },
        success: function (response) {
            if (response.success === true) {
                $("#formInput")[0].reset();
                $("#menu_id").val(response.menu_id);
                $("#menu_name").val(response.menu_name);
                $("#menu_type").val(response.menu_type);
                // $("#modules_name").val(response.modules_name);
                // $("#controller_name").val(response.controller_name);
                // $("#function_name").val(response.function_name);
                $("#nav_icon").val(response.nav_icon);
                // $("#permission_label").val(response.permission_label);
                $("#url").val(response.url);

                $('#menu_type').trigger('change');
                $('#btn_save').hide();
                $('#btn_cancel').show();
                $('#btn_update').show();

                $(".check_role_id").attr('checked', false);
                const role_id = response.role_id;
                if (role_id) {
                    let i = 0;
                    for (; role_id[i];) {
                        $("#check_" + role_id[i]).attr('checked', true);
                        i++;
                    }
                }

                if (response.access_status == 'Activated') {
                    $("#in_radio_1").attr('checked', true);
                    $("#in_radio_2").attr('checked', false);
                } else {
                    $("#in_radio_1").attr('checked', false);
                    $("#in_radio_2").attr('checked', true);
                }

                $('#modal-update').modal('show');
            } else {
                toastr.error(response.message)
            }
        }
    });
}

function _cancel_update() {
    $("#formInput")[0].reset();

    $("#menu_id").val('');
    $('#btn_save').show();
    $('#btn_cancel').hide();
    $('#btn_update').hide();
    $(".check_role_id").attr('checked', false);
    $("#check_RL2022-00000000").attr('checked', true);
}

$("#formInput").submit(function (e) {
    if ($("#menu_id").val()) {
        // update data
        e.preventDefault();
        $("#menu_id").attr('required', true);

        var menu_id = $("#menu_id").val();
        var menu_name = $("#menu_name").val();
        var menu_type = $("#menu_type").val();

        const in_list = ['Header', 'Main Menu', 'Sub Menu'];

        if (menu_name.length >= 3 && menu_name.length <= 25 && menu_id) {
            if (in_list.includes(menu_type)) {
                $.ajax({
                    url: updateUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success === true) {
                            $("#formInput")[0].reset();

                            $('#btn_save').show();
                            $('#btn_cancel').hide();
                            $('#btn_update').hide();

                            toastr.success(response.message)
                            setTimeout('window.location.reload(true)', 1000)
                        } else {
                            toastr.error(response.message)
                        }
                    }
                });
            } else {
                toastr.error("Pilih Tipe Menu Terlebih Dahulu")
            }
        } else {
            toastr.error("Panjang karakter minimal 3 & maksimal 30 s")
        }
    } else {
        // create data
        e.preventDefault();
        $("#menu_id").attr('required', false);

        var menu_name = $("#menu_name").val();
        var menu_type = $("#menu_type").val();

        const in_list = ['Header', 'Main Menu', 'Sub Menu'];

        if (menu_name.length >= 3 && menu_name.length <= 25) {
            if (in_list.includes(menu_type)) {
                $.ajax({
                    url: createUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success === true) {
                            $("#formInput")[0].reset();
                            toastr.success(response.message)
                            setTimeout('window.location.reload(true)', 1000)
                        } else {
                            toastr.error(response.message)
                        }
                    }
                });
            } else {
                toastr.error("Pilih Tipe Menu Terlebih Dahulu")
            }
        } else {
            toastr.error("Panjang karakter minimal 3 & maksimal 30")
        }
    }
});

function _delete(menu_id) {
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
                    'menu_id': menu_id,
                },
                success: function (response) {
                    if (response.success === true) {
                        toastr.success(response.message)
                        setTimeout('window.location.reload(true)', 1000)
                    } else {
                        toastr.error(response.message)
                    }
                }
            });
        }
    })
}

function _switch(menu_id) {
    $.ajax({
        url: switchUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            'menu_id': menu_id,
        },
        success: function (response) {
            if (response.success === true) {
                $("#formInput")[0].reset();
                toastr.success(response.message)
                setTimeout('window.location.reload(true)', 1000)
            } else {
                toastr.error(response.message)
            }
        }
    });
}


$(function () {
    var ns = $('ol.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        maxLevels: 3,
        isTree: true,
        expandOnHover: 700,
        startCollapsed: false,
        stop: function () {
            serialized = $('ol.sortable').nestedSortable('serialize');
            update_order(serialized);
            console.log(serialized);
        }
    });

});

function update_order(serialized) {
    $.ajax({
        url: changeOrderUrl,
        type: 'POST',
        dataType: 'json',
        data: serialized,
        success: function (response) {
            if (response.success === true) {
                toastr.success(response.message)
                setTimeout('window.location.reload(true)', 1000)
            } else {
                toastr.error(response.message)
                setTimeout('window.location.reload(true)', 1000)
            }
        }
    })
}

// create custom collapsible list
document.addEventListener('readystatechange', event => {
    if (event.target.readyState === 'complete') {
        const collapseBtn = document.querySelectorAll('li.mjs-nestedSortable-branch.mjs-nestedSortable-expanded')
        collapseBtn.forEach(btn => {
            const div = document.createElement('div')
            div.classList.add('collapse-button')
            div.innerHTML = '<i class="fas fa-caret-down"></i>'
            btn.prepend(div)
            const collapser = btn.querySelector('.collapse-button')
            const childList = btn.querySelector('ol')
            // childList.classList.add('collapsed') // enable this to make collapsed as default
            collapser.addEventListener('click', () => {
                if (childList.classList.contains('collapsed')) {
                    childList.classList.remove('collapsed')
                    collapser.innerHTML = '<i class="fas fa-caret-down"></i>'
                } else {
                    childList.classList.add('collapsed')
                    collapser.innerHTML = '<i class="fas fa-caret-right"></i>'
                }
            })
        })
    }
});