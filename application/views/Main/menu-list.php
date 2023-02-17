<style type="text/css">

</style>
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
                <div class="card <?= $_card_style; ?> card-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                <button type="button" class="btn btn-sm btn-120 btn-info">
                                    <i class="fas fa-info-circle"></i> Informasi
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="ID_divsortable">
                                <ol class="sortable" style="padding-left: 0px;" id="ID_olsortable">
                                    <?= $menu_data; ?>
                                </ol>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="card <?= $_card_style; ?> card-outline" style="position: sticky;position: -webkit-sticky;top:4rem;">
                                    <div class="card-header">
                                        <h3 class="card-title"><b>Tambah / Edit Menu</b></h3>
                                    </div>
                                    <form method="POST" autocomplete="off" id="formInput">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label>Nama Menu</label>
                                                        <input type="hidden" name="menu_id" id="menu_id" readonly>
                                                        <input type="text" name="menu_name" class="form-control" placeholder="Nama Menu" minlength="3" maxlength="25" id="menu_name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Type Menu</label>
                                                        <select class="form-control select2bs4" name="menu_type" id="menu_type" style="width: 100%;" required>
                                                            <option value="Main Menu">Main Menu</option>
                                                            <option value="Header">Header</option>
                                                            <option value="Sub Menu">Sub Menu</option>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label>Nama Modules / Folder</label>
                                                        <input type="text" name="modules_name" class="form-control" placeholder="Nama Modules / Folder" maxlength="50" id="modules_name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Controller</label>
                                                        <input type="text" name="controller_name" class="form-control" placeholder="Nama Controller" maxlength="50" id="controller_name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Function</label>
                                                        <input type="text" name="function_name" class="form-control" placeholder="Nama Function" maxlength="150" id="function_name">
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label>Nama Nav Icon / <a href='https://fontawesome.com/v5/search?m=free' target="_blank">Link Icon</a></label>
                                                        <input type="text" name="nav_icon" class="form-control" placeholder="Nama Icon" maxlength="100" id="nav_icon">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>URL</label>
                                                        <input type="text" name="url" class="form-control" placeholder="{Module}/{Folder/Controller}/{Controller}/{function}" id="url">
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label>Label Permission</label>
                                                        <input type="text" name="permission_label" class="form-control" placeholder="Label Permission" maxlength="100" id="permission_label">
                                                    </div> -->
                                                    <label>Akses Status</label>
                                                    <div class="row">
                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-group clearfix">
                                                                <div class="icheck-success i-padding">
                                                                    <input type="radio" id="in_radio_1" value="Activated" name="access_status">
                                                                    <label for="in_radio_1">
                                                                        Aktif
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-group clearfix">
                                                                <div class="icheck-danger i-padding">
                                                                    <input type="radio" id="in_radio_2" value="Deactivated" name="access_status" checked="true">
                                                                    <label for="in_radio_2">
                                                                        Tidak Aktif
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer text-center">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <button type="submit" class="btn btn-sm btn-120 btn-success" id='btn_save'>
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                                <button type="button" class="btn btn-sm btn-120 btn-default" id='btn_cancel' style="display: none;" onclick="_cancel_update()">
                                                    <i class="far fa-times-circle"></i> Batalkan
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-120 btn-warning" id='btn_update' style="display: none;">
                                                    <i class="fas fa-save"></i> Perbaharui
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script type="text/javascript">
    let getUpdateUrl = '<?= $_getupdateURL; ?>';
    let updateUrl = '<?= $_updateURL; ?>';
    let createUrl = '<?= $_createURL; ?>';
    let deleteUrl = '<?= $_deleteURL; ?>';
    let switchUrl = '<?= $_switchURL; ?>';
    let changeOrderUrl = '<?php echo base_url(); ?>Main/Main_menu/change_order';
</script>