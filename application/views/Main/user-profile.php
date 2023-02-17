<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-bold"><?= $_title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard'; ?>">Beranda</a></li>
                    <li class="breadcrumb-item active"><?= $_title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline <?= $_card_style; ?>">
            <div class="card-body table-height" style="overflow-x:auto;" id="ID_divtable">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                        <div class="form-group mb-0">
                            <label for="up_user_photo">Foto Akun</label>
                            <div id="up_user_photo-custom-msg" class="error"></div>
                        </div>
                        <div class="profile_image d-flex">
                            <?php
                            $fullUrl = 'uploads/profile_picture/' . $_user->photo;
                            if (file_exists($fullUrl)) {
                                echo '<img class="img-selfie" src="' . base_url() . $fullUrl . '" style="border: 1px solid;" height="220px" width="220px" id="up_prv_user_photo">';
                            } else {
                                echo '<image class="img-selfie" src="' . base_url() . 'uploads/profile_picture/No_Image_Available.jpg" style="border: 1px solid;" height="220" width="220px" id="up_prv_user_photo">';
                            }
                            ?>
                            <div class="up_user_photo_preview_crop d-none">
                                <img src="" style="border: 1px solid;" height="220px" width="220px" id="up_user_photo_prv_cropped">
                            </div>
                            <div class="w-100 ml-4">
                                <div class="form-group">
                                    <input type="file" class="fileCropping" name="up_user_photo_ori" id="up_user_photo" onclick="$(this).val(null)" hidden>
                                    <button class="btn btn-primary" onclick="$('#up_user_photo').click()">Unggah Foto</button>
                                </div>
                                <span>Gambar Profile Anda sebaiknya memiliki rasio 1:1<br />dan berukuran tidak lebih dari 4MB.</span>
                                <div class="mt-4 pt-1 img-btn-option">
                                    <button class="btn btn-primary up_user_photo-btn-option-recrop mr-2 d-none"><i class="fas fa-redo mr-1"></i> Re-Crop</button>
                                    <button class="btn btn-danger up_user_photo-btn-option-cancel d-none"><i class="far fa-times-circle mr-1"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama Pengguna</label>
                            <input type="hidden" name="id" id="up_id" value="<?= $_user->id ?>" required readonly>
                            <input type="text" name="name" class="form-control" placeholder="Nama Pengguna" minlength="3" maxlength="100" id="up_name" value="<?= $_user->name ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username" minlength="8" maxlength="20" id="up_username" value="<?= $_user->username ?>" required>
                        </div>
                        <!-- <div class="form-group">
                            <label>Peran Pengguna</label>
                            <input type="text" name="role_id" class="form-control" id="up_role_id" value="" disabled>
                        </div> -->
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="E-mail" maxlength="100" id="up_email" value="<?= $_user->email ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No Handphone</label>
                            <input type="text" name="phone" class="form-control" minlength="10" maxlength="15" id="up_phone" data-inputmask="'mask': ['9999-9999-99999']" value="<?= $_user->phone ?>" data-mask required>
                        </div>
                        <div class="form-group">
                            <label>Kata Sandi</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Kata Sandi" minlength="8" maxlength="20" id="up_password">

                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <a href="#" class="text-secondary" onclick="pass_generate()"><i class="fas fa-cog"></i></a>
                                    </span>
                                </div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <a href="#" class="text-secondary" onclick="pass_preview()"><i class="fas fa-eye"></i></a>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success" id="btn-submit">
                    <i class="far fa-save mr-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</section>

<div class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" id="crop_images_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Foto</h5>
                <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-sub">
                    <div class="up_user_photo-canvas">Memuat...</div>
                </div>
                <div class="mt-3">
                    <b>Tips:</b><br />
                    <ul>
                        <li>Klik dan Seret pada gambar untuk memilih area yang akan dicrop.</li>
                        <li>Scroll Up untuk melakukan Zoom In.</li>
                        <li>Scroll Down untuk melakukan Zoom Out.</li>
                        <li>Klik Rotate untuk memutar gambar.</li>
                        <li>Klik Reset untuk mengembalikan gambar ke ukuran aslinya.</li>
                        <li>Klik Crop untuk menyimpan gambar.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger up_user_photo-btn-option-reset"><i class="fas fa-sync"></i> Reset</button>
                <button class="btn btn-warning up_user_photo-btn-option-rotate"><i class="fas fa-redo"></i> Rotate</button>
                <button class="btn btn-primary up_user_photo-btn-option-crop"><i class="fas fa-crop-alt"></i> Crop</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let _updateURL = '<?= $_updateURL; ?>';
</script>