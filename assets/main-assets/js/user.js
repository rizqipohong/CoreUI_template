let cropper = {};
let doneCrop = {
    'user_photo': null,
    'up_user_photo': null,
};

function _modal_filter() {

    $("#fil_user_fullname").val(user_fullname);
    $('#fil_access_status').val(access_status);
    $('#fil_access_status').trigger('change');

    $('#modal-filter').modal('show');
}

function _modal_input() {
    $('#modal-input').modal('show');
}

function _modal_update(user_id) {
    $.ajax({
        url: getUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            'user_id': user_id,
        },
        success: function(response) {
            if (response.success === true) {
                $("#formUpdate")[0].reset();
                $('.up_role_id').removeAttr('checked');

                $("#up_user_id").val(response.data.id);
                $("#up_user_fullname").val(response.data.name);
                $("#up_user_name").val(response.data.username);
//                    $("#up_user_password").val(response.data.password);
                jQuery.each(response.data.role_id, function(i, val) {
                    $("#up_check_" + val.id).attr('checked','checked');
                });

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

$("#formInput").submit(function(e) {
    e.preventDefault();
    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let user_fullname = $("#user_fullname").val();
    let user_name = $("#user_name").val();
    let user_password = $("#user_password").val();

    let role_id = $('.check_role_id:checkbox:checked').map(function() {
        return this.value;
    }).get();
    let access_status = $("#formInput input[name='access_status']:checked").val();
    let post_data = new FormData();
    post_data.append('user_fullname', user_fullname);
    post_data.append('user_name', user_name);
    post_data.append('user_password', user_password);
    post_data.append('role_id', role_id);
    post_data.append('access_status', access_status);
    $.ajax({
        url: createUrl,
        contentType: false,
        cache: false,
        processData: false,
        type: 'POST',
        dataType: 'json',
        data: post_data,
        success: function(response) {
            if (response.success === true) {
                $("#formInput")[0].reset();
                $("#ID_divtable").load(window.location.href + " #ID_table");
                $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                $('#modal-create').modal('hide');
                toastr.success(response.message)
            } else {
                toastr.error(response.message)
            }
        }
    });

});

$("#formUpdate").submit(function(e) {
    e.preventDefault();
    let user_id = $("#up_user_id").val();
    let user_fullname = $("#up_user_fullname").val();
    let user_name = $("#up_user_name").val();
    let role_id = $('.up_role_id:checkbox:checked').map(function() {
        return this.value;
    }).get();
    let access_status = $("#formUpdate input[name='up_access_status']:checked").val();

    let post_data = new FormData();

    post_data.append('up_user_id', user_id);
    post_data.append('up_user_fullname', user_fullname);
    post_data.append('up_user_name', user_name);
    post_data.append('up_role_id', role_id);
    post_data.append('up_access_status', access_status);
    if ($("#up_user_password").val() != '') {
        post_data.append('up_user_password', $("#up_user_password").val());
    }
    $.ajax({
        url: updateUrl,
        contentType: false,
        cache: false,
        processData: false,
        type: 'POST',
        dataType: 'json',
        data: post_data,
        success: function(response) {
            if (response.success === true) {
                $("#formUpdate")[0].reset();
                $("#ID_divtable").load(window.location.href + " #ID_table");
                $("#ID_divpagination").load(window.location.href + " #ID_pagination");
                $('#modal-update').modal('hide');
                toastr.success(response.message)
            } else {
                toastr.error(response.message)
            }
        }
    });
});

$("#formExport").submit(function(e) {
    e.preventDefault();
    let user_export_pin = $("#ex_user_export_pin").val();

    if (!user_export_pin) {
        toastr.error("Anda belum memasukan PIN!")
        return true
    }

    $.ajax({
        url: exportUrl,
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success === true) {
                $('#modal-export').modal('hide');
                toastr.success(response.message)
                window.open(response.exportURL, '_blank');
            } else {
                toastr.error(response.message)
            }
        }
    });
});

function _delete(user_id) {
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
                'user_id': user_id,
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

function pass_generate() {
    let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    let passwordLength = 12;
    let password = "";
    for (let i = 0; i <= passwordLength; i++) {
        let randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("user_password").value = password;
}

function pass_generate_up() {
    let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    let passwordLength = 12;
    let password = "";
    for (let i = 0; i <= passwordLength; i++) {
        let randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("up_user_password").value = password;
}

function pass_preview() {
    let x = document.getElementById("user_password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function pass_preview_up() {
    let x = document.getElementById("up_user_password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function setCustomMessage(field, message) {
    if (message == '') {
        $("#" + field).removeClass('error');
    } else {
        $("#" + field).addClass('error');
    }
    $("#" + field + "-custom-msg").html(message);
}

$(".fileCropping").on('change', async (e) => {
    const field_name = e.target.attributes.id.value;
const field_selected = $(this).parents('.form-group');
try {
    if (e.target.files.length) {
        const _validFileExtensions = [".jpg", ".jpeg", ".png"];
        const picsize = e.target.files[0].size;
        let is_valid = true;
        if (e.target.type == "file") {
            const sFileName = e.target.value;
            if (sFileName.length > 0) {
                let blnValid = false;
                const arrayName = sFileName.split('.')
                const fileExt = arrayName[arrayName.length - 1].toLowerCase();
                if (_validFileExtensions.includes('.' + fileExt)) {
                    blnValid = true;
                }
                if (picsize > 5242880) {
                    setCustomMessage(field_name, 'Ukuran file upload gambar tidak sesuai, ukuran file gambar harus dibawah 5 MB');
                    is_valid = false;
                } else if (!blnValid) {
                    setCustomMessage(field_name, "Ekstensi file yang diupload tidak sesuai, format file gambar harus berekstensi: " + _validFileExtensions.join(", "));
                    is_valid = false;
                }
            }
        }
        if (is_valid) {
            e.target.classList.remove('error');
            field_selected.find('.error').html('');
            setCustomMessage(field_name, 'Anda belum menyesuaikan (crop) foto yang sudah diupload');

            // start file reader
            const reader = new FileReader();
            reader.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    canvasElem = document.createElement('canvas');
                    isSupported = !!canvasElem.getContext('2d');
                    if (isSupported) {
                        doneCrop[field_name] = false;
                        img.id = `${field_name}-img`;
                        img.src = e.target.result
                        img.classList.add('img-fluid');
                        // // clean result before
                        document.querySelector(`.${field_name}-canvas`).innerHTML = '';
                        // // append new image
                        document.querySelector(`.${field_name}-canvas`).appendChild(img)
                        // show save btn and options
                        document.querySelector(`.${field_name}-btn-option-crop`).classList.remove('d-none');
                        document.querySelector(`.${field_name}-btn-option-rotate`).classList.remove('d-none');
                        document.querySelector(`.${field_name}-btn-option-reset`).classList.remove('d-none');
                        // hide old preview and input
                        document.querySelector(`.${field_name}_preview`).classList.add('d-none');
                        document.querySelector(`.${field_name}_input`).classList.add('d-none');
                        // init cropper
                        cropper[field_name] = new Cropper(img, {
                            aspectRatio: 1 / 1,
                            viewMode: 1,
                        });
                    } else {
                        document.querySelector(`.${field_name}-canvas`).innerHTML = '<div class="alert alert-danger">Your browser does not support the HTML5 canvas element.</div>';
                    }
                }
            };

            reader.readAsDataURL(e.target.files[0]);
        }
    }
    //
} catch (error) {

}
});

// document ready function
$(document).ready(function() {
    const eventListener = ['user_photo', 'up_user_photo']
    eventListener.forEach(element => {
        document.querySelector(`.${element}-btn-option-rotate`).addEventListener('click', function(e) {
        e.preventDefault();
        cropper[element].rotate(10);
    });

    document.querySelector(`.${element}-btn-option-crop`).addEventListener('click', function(e) {
        e.preventDefault();

        doneCrop[element] = true;

        let Resize = cropper[element].getCroppedCanvas({
            minWidth: 350,
            minHeight: 350,
            maxWidth: 450,
            maxHeight: 450,
            aspectRatio: 1,
        });

        const croppedImageDataURL = Resize.toDataURL("image/jpg");
        $(`#${element}_prv_cropped`).attr('value', croppedImageDataURL);
        $(`#${element}_prv_cropped`).attr('src', croppedImageDataURL);
        document.querySelector(`.${element}-canvas`).classList.add('d-none');
        document.querySelector(`.${element}-btn-option-crop`).classList.add('d-none');
        document.querySelector(`.${element}-btn-option-rotate`).classList.add('d-none');
        document.querySelector(`.${element}-btn-option-recrop`).classList.remove('d-none');
        document.querySelector(`.${element}_preview_crop`).classList.remove('d-none');
        setCustomMessage(element, '');
    });

    document.querySelector(`.${element}-btn-option-recrop`).addEventListener('click', function(e) {
        e.preventDefault();

        doneCrop[element] = false;

        document.querySelector(`.${element}-canvas`).classList.remove('d-none');
        document.querySelector(`.${element}-btn-option-crop`).classList.remove('d-none');
        document.querySelector(`.${element}-btn-option-rotate`).classList.remove('d-none');
        document.querySelector(`.${element}-btn-option-recrop`).classList.add('d-none');
        document.querySelector(`.${element}_preview_crop`).classList.add('d-none');
        setCustomMessage(element, 'Anda belum menyesuaikan (crop) foto yang sudah diupload');
    });

    document.querySelector(`.${element}-btn-option-reset`).addEventListener('click', function(e) {
        e.preventDefault();

        clearCropper(element);
    });
});

})

function clearCropper(element) {
    doneCrop[element] = null;

    document.querySelector(`#${element}`).value = '';
    $(`#${element}_prv_cropped`).attr('value', '');
    $(`#${element}_prv_cropped`).attr('src', '');
    cropper[element].destroy();
    document.querySelector(`.${element}-canvas`).innerHTML = '';
    document.querySelector(`.${element}-canvas`).classList.remove('d-none');
    document.querySelector(`.${element}_preview_crop`).classList.add('d-none');
    document.querySelector(`.${element}-btn-option-crop`).classList.add('d-none');
    document.querySelector(`.${element}-btn-option-recrop`).classList.add('d-none');
    document.querySelector(`.${element}-btn-option-rotate`).classList.add('d-none');
    document.querySelector(`.${element}-btn-option-reset`).classList.add('d-none');
    document.querySelector(`.${element}-custom-file-label`).innerHTML = 'Unggah Foto';

    setCustomMessage(element, '');

    // bring back default
    document.querySelector(`.${element}_preview`).classList.remove('d-none');
    document.querySelector(`.${element}_input`).classList.remove('d-none');
}