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
                                <button type="button" class="btn btn-sm btn-120 btn-secondary" onclick="_modal_filter()">
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
                        <table class="table table-bordered table-head-fixed table-hover text-nowrap table-sm" id="ID_table">
                            <thead class="<?= $_th_style; ?>">
                                <tr class="text-center">
                                    <th class="col-tab120">ID Role</th>
                                    <th class="col-tab300">Nama Role</th>
                                    <th class="col-tab300">Deskripsi</th>
                                    <th class="col-tab120">Status</th>
                                    <th class="col-tab150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($role_data as $key => $val) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $val->id; ?></td>
                                        <td class="text-left"><?= $val->display_name ?></td>
                                        <td class="text-left"><?= $val->description ?></td>
                                        <td class="text-center">
                                            <?= $val->status == '1' ? 'Aktif' : 'Tidak Aktif'; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('Main/Main_role/permission/' . $val->id) ?>" class="btn btn-xs btn-primary">Permission</a>
                                            <button type="button" class="btn btn-xs btn-action-icon btn-warning" onclick="_modal_update('<?= $val->id; ?>')">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-action-icon btn-danger" onclick="_delete('<?= $val->id; ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }

                                if (empty($role_data)) {
                                ?>
                                    <tr>
                                        <td>&nbsp;</td>
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
                                <?php if ($_fil_role_name) { ?>
                                    <span class="badge badge-primary"><?= $_fil_role_name; ?>
                                        <a href="<?= $_clearfilterURL . '/role_name'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>

                                <?php if (!is_null($_fil_role_access_status)) { ?>
                                    <span class="badge badge-primary">
                                        <?= $_fil_role_access_status == '1' ? 'Aktif' : 'Tidak Aktif'; ?>
                                        <a href="<?= $_clearfilterURL . '/access_status'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>
                                <br>
                                <i style="margin-right: 10px;">Total Data : <?= $role_data_count; ?></i>
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
                                <label>Nama Peran</label>
                                <input type="text" name="role_name" class="form-control" placeholder="Nama Peran" id="fil_role_name">
                            </div>
                            <div class="form-group">
                                <label>Akses Status</label>
                                <select class="form-control select2bs4" name="access_status" style="width: 100%;" id="fil_access_status">
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
                <h4 class="modal-title">Tambah Peran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formInput">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Peran</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Peran" id="role_name" required>
                            </div>
                            <div class="form-group">
                                <label>Nama yang ditampilkan</label>
                                <input type="text" name="display_name" class="form-control" placeholder="Display name" id="role_display_name" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" placeholder="Deskripsi Peran" id="role_description"></textarea>
                            </div>
                            <div class="form-group clearfix">
                                <label>Akses Status</label>
                                <div class="icheck-success">
                                    <input type="radio" id="in_radio_1" value="1" name="status">
                                    <label for="in_radio_1">
                                        Aktif
                                    </label>
                                </div>
                                <div class="icheck-danger">
                                    <input type="radio" id="in_radio_2" value="0" name="status" checked="true">
                                    <label for="in_radio_2">
                                        Tidak Aktif
                                    </label>
                                </div>
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
                <h4 class="modal-title">Ubah Peran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formUpdate">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Peran</label>
                                <input type="hidden" name="role_id" id="up_role_id" required readonly>
                                <input type="text" name="name" class="form-control" placeholder="Nama Peran" minlength="3" maxlength="30" id="up_role_name" required>
                            </div>
                            <div class="form-group">
                                <label>Nama yang ditampilkan</label>
                                <input type="text" name="display_name" class="form-control" placeholder="Display name" id="up_role_display_name" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" name="description" class="form-control" placeholder="Deskripsi Peran" id="up_role_description"></textarea>
                            </div>
                            <div class="form-group clearfix">
                                <label>Akses Status</label>
                                <div class="icheck-success">
                                    <input type="radio" id="up_radio_1" value="1" name="status">
                                    <label for="up_radio_1">
                                        Aktif
                                    </label>
                                </div>
                                <div class="icheck-danger">
                                    <input type="radio" id="up_radio_2" value="0" name="status">
                                    <label for="up_radio_2">
                                        Tidak Aktif
                                    </label>
                                </div>
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
                                <input type="text" name="user_export_pin" class="form-control" placeholder="PIN Export Anda" minlength="8" maxlength="8" id="ex_user_export_pin" required>

                                <input type="hidden" name="role_name" value="<?= $_fil_role_name; ?>">
                                <input type="hidden" name="access_status" value="<?= $_fil_role_access_status; ?>">
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
    let createUrl = '<?= $_createURL; ?>';
    let updateUrl = '<?= $_updateURL; ?>';
    let deleteUrl = '<?= $_deleteURL; ?>';
    let exportUrl = '<?= $_exportURL; ?>';

    var role_name = '<?= $_fil_role_name; ?>';
    var access_status = '<?= $_fil_role_access_status; ?>';
</script>