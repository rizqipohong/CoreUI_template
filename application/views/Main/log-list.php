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
                                <tr class=" text-center">
                                    <th class="col-tab150">ID Log</th>
                                    <th class="col-tab200">Tanggal</th>
                                    <th class="col-tab300">Nama Log</th>
                                    <th>Detail Log</th>
                                    <th class="col-tab150">Status</th>
                                    <th class="col-tab200">Dibuat Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($log_data as $key => $val) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $val->log_id; ?></td>
                                        <td class="text-center"><?= date('d/m/Y - H:i:s', strtotime($val->created_at)); ?></td>
                                        <td class="text-left"><?= $val->log_name; ?></td>
                                        <td class="text-center">
                                            <a href="#" id="<?= $val->log_id; ?>" data-logname='<?= $val->log_name; ?>' data-logstatus='<?= $val->log_status; ?>' data-log='<?= $val->log_data; ?>' data-crat='<?= $val->created_at; ?>' data-crby='<?= $val->created_by; ?>' onclick="_modal_detail('<?= $val->log_id; ?>')"> Detail</a>
                                        </td>
                                        <td class="text-center"><?= $val->log_status; ?></td>
                                        <td class="text-center"><?= $val->created_by; ?></td>
                                    </tr>
                                <?php
                                }

                                if (empty($log_data)) {
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
                                <?php if ($_fil_log_name) { ?>
                                    <span class="badge badge-primary"><?= $_fil_log_name; ?>
                                        <a href="<?= $_clearfilterURL . '/log_name'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>

                                <?php if ($_fil_log_status) { ?>
                                    <span class="badge badge-primary"><?= $_fil_log_status ?>
                                        <a href="<?= $_clearfilterURL . '/log_status'; ?>" class="badge-primary"><i class="fas fa-times"></i></a>
                                    </span>
                                <?php } ?>
                                <br>
                                <i style="margin-right: 10px;">Total Data : <?= $log_data_count; ?></i>
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
                                <label>Nama Log</label>
                                <input type="text" name="log_name" class="form-control" placeholder="Nama Log" id="fil_log_name">
                            </div>
                            <div class="form-group">
                                <label>Log Status</label>
                                <select class="form-control select2bs4" name="log_status" style="width: 100%;" id="fil_log_status">
                                    <option value="">- Pilih Semua -</option>
                                    <?php
                                    foreach ($select_menu as $key => $val) {
                                    ?>
                                        <option value="<?= $val->log_status; ?>"><?= $val->log_status; ?></option>
                                    <?php } ?>
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

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Log</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Nama Log</label>
                            <input type="text" class="form-control" disabled id="de_log_name">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" class="form-control" disabled id="de_log_status">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" class="form-control" disabled id="de_created_at">
                        </div>
                        <div class="form-group">
                            <label>Dibuat Oleh</label>
                            <input type="text" class="form-control" disabled id="de_created_by">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <pre id="data_log" style="border: 1px; background-color:#f3f3f3;"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="far fa-times-circle"></i> Tutup
                </button>
            </div>
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
    function _modal_filter() {
        var log_name = '<?= $_fil_log_name; ?>';
        var log_status = '<?= $_fil_log_status; ?>';

        $("#fil_role_name").val(log_name);
        $('#fil_log_status').val(log_status);
        $('#fil_log_status').trigger('change');

        $('#modal-filter').modal('show');
    }

    function _modal_detail(e) {
        var log_data = $('#' + e).data('log');
        var log_name = $('#' + e).data('logname');
        var log_status = $('#' + e).data('logstatus');
        var log_created_at = $('#' + e).data('crat');
        var log_created_by = $('#' + e).data('crby');

        var jsonPretty = JSON.stringify(log_data, null, '\t')

        $("#data_log").html(jsonPretty);
        $("#de_log_name").val(log_name);
        $('#de_log_status').val(log_status);
        $("#de_created_at").val(log_created_at);
        $('#de_created_by').val(log_created_by);
        $('#modal-detail').modal('show');
    }

    function _modal_export() {
        $('#modal-export').modal('show');
    }

    $("#formExport").submit(function(e) {
        e.preventDefault();
        var user_export_pin = $("#ex_user_export_pin").val();

        if (user_export_pin) {
            $.ajax({
                url: '<?= $_exportURL; ?>',
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
        } else {
            toastr.error("Anda belum memasukan PIN!")
        }
    });
</script>