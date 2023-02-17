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
        <div class="row">
            <div class="col-12">
                <div class="card card-outline <?= $_card_style; ?>">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                <button type="button" class="btn btn-sm btn-120 btn-secondary"
                                        onclick="_modal_filter()">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button type="button" class="btn btn-sm btn-120 btn-success" onclick="_modal_input()">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </button>
                                <button type="button" class="btn btn-sm btn-120 btn-danger" onclick="_modal_export()">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <button type="button" class="btn btn-sm btn-120 btn-info">
                                    <i class="fas fa-info-circle"></i> Informasi
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-0 table-height" style="overflow-x:auto;" id="ID_divtable">
                        <table class="table table-bordered table-head-fixed table-hover text-nowrap table-sm"
                               id="ID_table">
                            <thead class="<?= $_th_style; ?>">
                            <tr class="text-center">
                                <th class="col-tab300">Nama Pengguna</th>
                                <th>Username</th>
                                <th>Peran</th>
                                <th class="col-tab120">Status</th>
                                <th class="col-tab100">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($user_data as $key => $val) {
                                ?>
                                <tr>
                                    <td class="text-left"><?= $val->name; ?></td>
                                    <td class="text-left"><?= $val->username; ?></td>
                                    <td class="text-left">
                                        <?php
                                        foreach ($val->roleArr as $role_user) {
                                            ?>

                                            <span class="badge badge-pill badge-dark"><?= $role_user->display_name ?></span>
                                            <?php
                                        }
                                        ?></td>
                                    <td class="text-left">
                                        <?= $val->status == '1' ? 'Aktif' : 'Tidak Aktif'; ?>
                                    </td>
                                    <td class="text-left">
                                        <button type="button" class="btn btn-xs btn-action-icon btn-warning"
                                                onclick="_modal_update('<?= $val->id; ?>')">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-action-icon btn-danger"
                                                onclick="_delete('<?= $val->id; ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }

                            if (empty($user_data)) {
                                ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix" id="ID_divpagination">
                        <div class="row" id="ID_pagination">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-left">
                                <i style="margin-right: 10px;">Filter : </i>
                                <?php if ($_fil_user_fullname) { ?>
                                    <span class="badge badge-primary"><?= $_fil_user_fullname; ?>
                                        <a href="<?= $_clearfilterURL . '/user_fullname'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>

                                <?php if (!is_null($_fil_user_access_status)) { ?>
                                    <span class="badge badge-primary">
                                        <?= $_fil_user_access_status == '1' ? 'Aktif' : 'Tidak Aktif'; ?>
                                        <a href="<?= $_clearfilterURL . '/access_status'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>

                                <br>
                                <i style="margin-right: 10px;">Total Data : <?= $user_data_count; ?></i>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-right">
                                <?= $pagination; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<div class="modal fade" id="modal-filter">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pencarian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= $_filterURL; ?>" method="POST" autocomplete="on">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Pengguna</label>
                                <input type="text" name="user_fullname" class="form-control" placeholder="Nama Pengguna"
                                       minlength="3" maxlength="30" id="fil_user_fullname">
                            </div>
                            <div class="form-group">
                                <label>Akses Status</label>
                                <select class="form-control select2bs4" name="access_status" style="width: 100%;"
                                        id="fil_access_status">
                                    <option value="">- Pilih Semua -</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="<?= $_clearfilterURL; ?>" type="button" class="btn btn-default">
                        <i class="far fa-times-circle"></i> Bersihkan Filter
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-input">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formInput">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Pengguna</label>
                                <input type="text" name="user_fullname" class="form-control" placeholder="Nama Pengguna"
                                       minlength="3" maxlength="100" id="user_fullname" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="user_name" class="form-control" placeholder="username"
                                       minlength="8" maxlength="20" id="user_name" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <!-- <input type="password" name="user_password" class="form-control" placeholder="Kata Sandi" minlength="8" maxlength="20" id="user_password" required> -->
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="user_password"
                                           placeholder="Kata Sandi" minlength="8" maxlength="20" id="user_password"
                                           required>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a href="#" class="text-secondary" onclick="pass_generate()"><i
                                                        class="fas fa-cog"></i></a>
                                        </span>
                                    </div>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a href="#" class="text-secondary" onclick="pass_preview()"><i
                                                        class="fas fa-eye"></i></a>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label>Peran Pengguna</label>
                                <div class="row">
                                    <?php
                                    foreach ($select_role as $key => $val) {
                                        ?>
                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <div class="icheck-warning">
                                                    <input type="checkbox" class="check_role_id"
                                                           id="check_<?= $val->id; ?>"
                                                           value="<?= $val->id; ?>"
                                                           name="role_id[]">
                                                    <label for="check_<?= $val->id; ?>">
                                                        <?= $val->name; ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group clearfix">
                                <label>Akses Status</label>
                                <div class="icheck-success">
                                    <input type="radio" id="in_radio_1" value="1" name="access_status">
                                    <label for="in_radio_1">
                                        Aktif
                                    </label>
                                </div>
                                <div class="icheck-danger">
                                    <input type="radio" id="in_radio_2" value="0" name="access_status" checked="true">
                                    <label for="in_radio_2">
                                        Tidak Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 pt-1 img-btn-option" style="display: grid;">
                                <button class="btn btn-primary user_photo-btn-option-crop mb-2 d-none"><i
                                            class="fas fa-crop-alt"></i> Crop
                                </button>
                                <button class="btn btn-primary user_photo-btn-option-recrop mb-2 d-none"><i
                                            class="fas fa-redo"></i> Re-Crop
                                </button>
                                <button class="btn btn-warning user_photo-btn-option-rotate mb-2 d-none"><i
                                            class="fas fa-redo"></i> Rotate
                                </button>
                                <button class="btn btn-danger user_photo-btn-option-reset mb-2 d-none"><i
                                            class="far fa-trash-alt"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="far fa-times-circle"></i> Batalkan
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formUpdate">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Pengguna</label>
                                <input type="hidden" name="user_id" id="up_user_id" required readonly>
                                <input type="text" name="user_fullname" class="form-control" placeholder="Nama Pengguna"
                                       minlength="3" maxlength="100" id="up_user_fullname" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="user_name" class="form-control" placeholder="username"
                                       minlength="8" maxlength="20" id="up_user_name" required>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <!-- <input type="password" name="user_password" class="form-control" placeholder="Kata Sandi" minlength="8" maxlength="20" id="user_password" required> -->
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="user_password"
                                           placeholder="Kata Sandi" minlength="8" maxlength="20" id="up_user_password">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a href="#" class="text-secondary" onclick="pass_generate_up()"><i
                                                        class="fas fa-cog"></i></a>
                                        </span>
                                    </div>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a href="#" class="text-secondary" onclick="pass_preview_up()"><i
                                                        class="fas fa-eye"></i></a>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label>Peran Pengguna</label>
                                <div class="row">
                                    <?php
                                    foreach ($select_role as $key => $val) {
                                        ?>
                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <div class="icheck-warning">
                                                    <input type="checkbox" class="up_role_id"
                                                           id="up_check_<?= $val->id; ?>"
                                                           value="<?= $val->id; ?>"
                                                           name="role_id[]">
                                                    <label for="up_check_<?= $val->id; ?>">
                                                        <?= $val->name; ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group clearfix">
                                <label>Akses Status</label>
                                <div class="icheck-success">
                                    <input type="radio" id="up_radio_1" value="1" name="up_access_status">
                                    <label for="up_radio_1">
                                        Aktif
                                    </label>
                                </div>
                                <div class="icheck-danger">
                                    <input type="radio" id="up_radio_2" value="0" name="up_access_status"
                                           checked="true">
                                    <label for="up_radio_2">
                                        Tidak Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 pt-1 img-btn-option" style="display: grid;">
                                <button class="btn btn-primary up_user_photo-btn-option-crop mb-2 d-none"><i
                                            class="fas fa-crop-alt"></i> Crop
                                </button>
                                <button class="btn btn-primary up_user_photo-btn-option-recrop mb-2 d-none"><i
                                            class="fas fa-redo"></i> Re-Crop
                                </button>
                                <button class="btn btn-warning up_user_photo-btn-option-rotate mb-2 d-none"><i
                                            class="fas fa-redo"></i> Rotate
                                </button>
                                <button class="btn btn-danger up_user_photo-btn-option-reset mb-2 d-none"><i
                                            class="far fa-trash-alt"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="far fa-times-circle"></i> Batalkan
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formExport">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Masukan PIN Export Anda!</label>
                                <input type="text" name="user_export_pin" class="form-control"
                                       placeholder="PIN Export Anda" minlength="8" maxlength="8" id="ex_user_export_pin"
                                       required>

                                <input type="hidden" name="user_fullname" value="<?= $_fil_user_fullname; ?>">
                                <input type="hidden" name="access_status" value="<?= $_fil_user_access_status; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="far fa-times-circle"></i> Batalkan
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    let getUrl = '<?= $_getupdateURL; ?>';
    let updateUrl = '<?= $_updateURL; ?>';
    let createUrl = '<?= $_createURL; ?>';
    let deleteUrl = '<?= $_deleteURL; ?>';

    let user_fullname = '<?= $_fil_user_fullname; ?>';
    let access_status = '<?= $_fil_user_access_status; ?>';

</script>
