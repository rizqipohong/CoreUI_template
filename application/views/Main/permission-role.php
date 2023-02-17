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
                    <form method="POST" autocomplete="off" id="formInput">
                        <div class="card-header">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="icheck-warning">
                                        <input type="checkbox" id="checkAll">
                                        <label for="checkAll">
                                            Semua
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-height" style="overflow-x:auto;" id="ID_divtable">
                            <div class="row col-12" id="">
                                <?php
                                $resultArr = array();
                                foreach ($permission_data as $permission) {
                                    if ($permission->is_choose == '1') {
                                        $is_checked = 'checked';
                                    } else {
                                        $is_checked = '';
                                    }
                                    $resultArr[$permission->module_name][] = '<div class="col-12">
                                        <div class="form-group">
                                            <div class="icheck-warning">
                                                <input type="checkbox" class="check_role_id"
                                                       id="check_' . $permission->id . '" ' . $is_checked . '
                                                       value="' . $permission->id . '"
                                                       name="permission_id[]">
                                                <label for="check_' . $permission->id . '">
                                                    ' . $permission->display_name . '
                                                </label>
                                            </div>
                                        </div>
                                    </div>';
                                ?>
                                <?php } ?>


                                <?php
                                $idx_title = 1;
                                foreach ($resultArr as $title => $permissionArr) {
                                ?>
                                    <div class="col-4">
                                        <div id="accordion<?= $idx_title ?>" class="mt-2">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h4 class="card-title"><?= $title ?>
                                                    </h4>
                                                    <div class="card-tools">
                                                        <a class="d-block" data-toggle="collapse" href="#collapse<?= $idx_title ?>">
                                                            <i class="fas fa-angle-down"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapse<?= $idx_title ?>" class="collapse" data-parent="#accordion<?= $idx_title ?>">
                                                    <div class="card-body">
                                                        <?php
                                                        foreach ($permissionArr as $permission) {
                                                            echo $permission;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $idx_title++;
                                } ?>

                            </div>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-default" href="<?= site_url('Main/Main_role') ?>">
                                <i class="far fa-times-circle"></i> Batalkan
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="far fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
</section>
<script>
    let getUrl = '<?= $_getupdateURL; ?>';
</script>