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
                                    <th class="col-tab120">ID Permission</th>
                                    <th class="col-tab300">Nama Permission</th>
                                    <th class="col-tab120">Status</th>
                                    <th class="col-tab100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($permission_data as $key => $val) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $val->id; ?></td>
                                        <td class="text-left">
                                            <?= $val->display_name ?>
                                            <?php
                                            if ($val->description)
                                                echo "<br/>" . $val->description;

                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $val->status == '1' ? 'Aktif' : 'Tidak Aktif'; ?>
                                        </td>
                                        <td class="text-center">
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

                                if (empty($permission_data)) {
                                ?>
                                    <tr>
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
                                <br>
                                <i style="margin-right: 10px;">Total Data : <?= $permission_data_count; ?></i>
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
                                <label>Nama Permission</label>
                                <input type="text" name="permission_name" class="form-control" placeholder="Nama Permission" minlength="3" maxlength="30" id="fil_permission_name">
                            </div>
                            <div class="form-group">
                                <label>Akses Status</label>
                                <select class="form-control select2bs4" name="access_status" style="width: 100%;" id="fil_access_status">
                                    <option value="">- Pilih Semua -</option>
                                    <option value="Activated">Aktif</option>
                                    <option value="Deactivated">Tidak Aktif</option>
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
                <h4 class="modal-title">Tambah Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formInput">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Permission</label>
                                <input type="text" name="name" class="form-control" placeholder="format: method_name-controller_name" id="permission_name" required>
                            </div>
                            <div class="form-group">
                                <label>Nama yang ditampilkan</label>
                                <input type="text" name="display_name" class="form-control" placeholder="Deskripsi Permission" id="permission_display_name" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" placeholder="Deskripsi Permission" id="permission_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Modul</label>
                                <select class="form-control select2bs4" name="module_id" style="width: 100%;" id="module_id" required>
                                    <?php
                                    foreach ($module_data as $key => $val) {
                                        echo '<option value="' . $val->id . '">' . $val->name . '</option>';
                                    }
                                    ?>
                                </select>
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
                <h4 class="modal-title">Ubah Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formUpdate">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Permission</label>
                                <input type="hidden" name="permission_id" id="up_permission_id" required readonly>
                                <input type="text" name="name" class="form-control" placeholder="Nama Permission" id="up_permission_name" required>
                            </div>
                            <div class="form-group">
                                <label>Nama yang ditampilkan</label>
                                <input type="text" name="display_name" class="form-control" placeholder="Deskripsi Permission" id="up_permission_display_name" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" placeholder="Deskripsi Permission" id="up_permission_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Modul</label>
                                <select class="form-control select2bs4" name="module_id" style="width: 100%;" id="up_module_id" required>
                                    <?php
                                    foreach ($module_data as $key => $val) {
                                        echo '<option value="' . $val->id . '">' . $val->name . '</option>';
                                    }
                                    ?>
                                </select>
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

                                <input type="hidden" name="permission_name" value="<?= $_fil_permission_name; ?>">
                                <input type="hidden" name="access_status" value="<?= $_fil_permission_access_status; ?>">
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
<script>
    let getUrl = '<?= $_getupdateURL; ?>';
    let createUrl = '<?= $_createURL; ?>';
    let updateUrl = '<?= $_updateURL; ?>';
    let deleteUrl = '<?= $_deleteURL; ?>';
    let exportUrl = '<?= $_exportURL; ?>';
</script>