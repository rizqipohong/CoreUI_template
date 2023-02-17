function closeModal() {
    $('#crop_images_modal').modal('hide');
}

let cropper = {};
let doneCrop = {
    'up_photo': null,
};

$("#btn-submit").on('click', function (e) {
    e.preventDefault();
    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let id = $("#up_id").val();
    let name = $("#up_name").val();
    let username = $("#up_username").val();
    let email = $("#up_email").val();
    let phone = $("#up_phone").val();

    // if (name.length < 3 || name.length > 30) {
    //     toastr.error("Panjang karakter nama minimal 3 & maksimal 30")
    //     return true
    // }

    // if (username.length < 8 || username.length > 20) {
    //     toastr.error("Panjang karakter minimal 3 & maksimal 20")
    //     return true
    // }

    // if (!email.match(validRegex)) {
    //     toastr.error("Format e-mail tidak tepat")
    //     return true
    // }

    // const phonenumber = phone.replace(/\D/g, '');
    // if (phonenumber.length < 10 || phonenumber.length > 15) {
    //     toastr.error("Panjang karakter minimal 10 & maksimal 15")
    //     return true
    // }

    if (doneCrop.up_photo === null || doneCrop.up_photo) {
        let post_data = new FormData();

        post_data.append('up_id', id);
        post_data.append('up_name', name);
        post_data.append('up_username', username);
        post_data.append('up_email', email);
        post_data.append('up_phone', phone);

        if ($("#up_password").val() != '') {
            post_data.append('up_password', $("#up_password").val());
        }

        let files = $('#up_user_photo_prv_cropped').attr('value');
        if (files) {
            post_data.append('up_user_photo', files);
        }

        $.ajax({
            url: _updateURL,
            contentType: false,
            cache: false,
            processData: false,
            type: 'POST',
            dataType: 'json',
            data: post_data,
            success: function (response) {
                if (response.success === true) {
                    toastr.success(response.message)
                    setTimeout(function () {
                        location.reload(true);
                    }, 2000);
                } else {
                    toastr.error(response.message)
                }
            }
        });
    } else {
        toastr.error("Silahkan Crop terlebih dahulu Foto anda")
    }

});


function pass_preview() {
    let x = document.getElementById("up_password");
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
    document.querySelector('.up_user_photo-canvas').innerHTML = 'Memuat...';
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
                $('#crop_images_modal').modal('show');
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
                            // show recrop and cancel
                            document.querySelector(`.${field_name}-btn-option-cancel`).classList.remove('d-none');
                            document.querySelector(`.up_user_photo-btn-option-recrop`).classList.remove('d-none');
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
$(document).ready(function () {
    document.querySelector(`.up_user_photo-btn-option-rotate`).addEventListener('click', function (e) {
        e.preventDefault();
        cropper['up_user_photo'].rotate(10);
    });

    document.querySelector(`.up_user_photo-btn-option-reset`).addEventListener('click', function (e) {
        e.preventDefault();
        cropper['up_user_photo'].reset();
    });

    document.querySelector(`.up_user_photo-btn-option-crop`).addEventListener('click', function (e) {
        e.preventDefault();

        doneCrop['up_user_photo'] = true;

        let Resize = cropper['up_user_photo'].getCroppedCanvas({
            minWidth: 350,
            minHeight: 350,
            maxWidth: 450,
            maxHeight: 450,
            aspectRatio: 1,
        });

        const croppedImageDataURL = Resize.toDataURL("image/jpg");
        $(`#up_user_photo_prv_cropped`).attr('value', croppedImageDataURL);
        $(`#up_user_photo_prv_cropped`).attr('src', croppedImageDataURL);
        document.querySelector(`.up_user_photo_preview_crop`).classList.remove('d-none');
        document.querySelector(`.img-selfie`).classList.add('d-none');
        setCustomMessage('up_user_photo', '');
        $('#crop_images_modal').modal('hide');
    });

    document.querySelector(`.up_user_photo-btn-option-recrop`).addEventListener('click', function (e) {
        e.preventDefault();

        doneCrop['up_user_photo'] = false;

        $('#crop_images_modal').modal('show');
        setCustomMessage('up_user_photo', 'Anda belum menyesuaikan (crop) foto yang sudah diupload');
    });

    document.querySelector(`.up_user_photo-btn-option-cancel`).addEventListener('click', function (e) {
        e.preventDefault();

        clearCropper('up_user_photo');
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
    document.querySelector(`.${element}-btn-option-recrop`).classList.add('d-none');
    document.querySelector(`.${element}-btn-option-cancel`).classList.add('d-none');

    setCustomMessage(element, '');

    // bring back default
    document.querySelector('.img-selfie').classList.remove('d-none');
}

function pass_generate() {
    let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    let passwordLength = 12;
    let password = "";
    for (let i = 0; i <= passwordLength; i++) {
        let randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber + 1);
    }
    document.getElementById("up_password").value = password;
}