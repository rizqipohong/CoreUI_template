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
                                <?php if (can('Param_pipeline/create_data')) { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-success" onclick="_modal_input()">
                                        <i class="fas fa-plus-circle"></i> Tambah
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-success" disabled>
                                        <i class="fas fa-plus-circle"></i> Tambah
                                    </button>
                                <?php } ?>

                                <?php if (can('Param_pipeline/export_data')) { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-danger" onclick="_modal_export()">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-danger" disabled>
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-sm btn-120 btn-info" onclick="previewGuide()">
                                    <i class="fas fa-info-circle"></i> Informasi
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-0 table-height" style="overflow-x:auto; height:450px;" id="ID_divtable">
                        <table class="table table-bordered table-head-fixed table-hover text-nowrap table-sm" id="ID_table">
                            <thead class="<?= $_th_style; ?>">
                                <tr class="text-center">
                                    <th class="col-tab80">No.</th>
                                    <th class="col-tab150">Asal Pipeline</th>
                                    <th class="col-tab300">Level of Priorities</th>
                                    <th class="col-tab200">Level of Risk</th>
                                    <th class="col-tab150">Tier</th>
                                    <th class="col-tab100">Aksi</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php if (!isset($table->error)) : ?>
                                    <?php $no = 0;
                                    foreach ($table->data as $key => $val) {
                                        $no++;
                                    ?>
                                        <tr class="init-table" id="param_pipeline_<?php echo $val->id_pipeline; ?>">
                                            <td class="text-center"><?= $no; ?></td>
                                            <td class="text-left"><?= $val->pipeline_origin ?></td>
                                            <td class="text-center"><?= $val->level_priorities ?></td>
                                            <td class="text-center"><?= $val->level_risk ?></td>
                                            <td class="text-center"><?= $val->tier_name ?></td>
                                            <td class="text-center">
                                                <?php if (can('Param_pipeline/update_data')) { ?>
                                                    <button type="button" class="btn btn-xs btn-action-icon btn-warning" onclick="_modal_update('<?= $val->id_pipeline; ?>')">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-xs btn-action-icon btn-warning" disabled>
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if (can('Param_pipeline/delete_data')) { ?>
                                                    <?php if ($val->deletable) : ?>
                                                        <button type="button" class="btn btn-xs btn-action-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Tooltip on top" onclick="_delete('<?= $val->id_pipeline; ?>')">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php } else { ?>
                                                    <?php if ($val->deletable) : ?>
                                                        <button type="button" class="btn btn-xs btn-action-icon btn-danger" disabled>
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    if (empty($table)) {
                                    ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-bold">No Data</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="row" value="0">
                    <input type="hidden" id="all_count" value="<?php echo $total; ?>">
                    <div class="row ajax-load" style="display: none;">
                        <div class="col-lg-12" style="text-align: center;"><img width="100" height="100" src="https://www.primebldg.com/wp-content/uploads/2017/09/ajax-loader.gif"></div>
                    </div>


                    <div class="card-footer clearfix" id="ID_divpagination">
                        <div class="row" id="ID_pagination">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-left">
                                <i class="text-grey-bold" style="margin-right: 10px;">
                                    Filter :
                                    <span class="badge p-1 badge bg-list-set-filter bg-primary <?= @$this->session->userdata('Param_pipeline')['pipeline_origin'] ? '' : 'd-none'; ?>">
                                        Asal Pipeline : <span><?= @$this->session->userdata('Param_pipeline')['pipeline_origin']; ?></span>
                                        &nbsp;<span data-session="pipeline_origin" style="cursor:pointer" class="badge-white clear-filter"><i class="fas fa-times-circle"></i></span>
                                    </span>
                                    <?php
                                    $level_of_priorities = '';
                                    foreach ($param_level_priorities as $key => $value) {
                                        if (@$this->session->userdata('Param_pipeline')['level_of_priorities'] == $value->id_level) {
                                            $level_of_priorities = $value->level_name;
                                        }
                                    }
                                    ?>
                                    <span class="badge p-1 badge bg-list-set-filter bg-primary <?= @$this->session->userdata('Param_pipeline')['level_of_priorities'] ? '' : 'd-none'; ?>">
                                        Level of priorities : <span><?= $level_of_priorities; ?></span>
                                        &nbsp;<span data-session="level_of_priorities" style="cursor:pointer" class="badge-white clear-filter"><i class="fas fa-times-circle"></i></span>
                                    </span>

                                    <?php
                                    $level_of_risk = '';
                                    foreach ($param_level_risk as $key => $value) {
                                        if (@$this->session->userdata('Param_pipeline')['level_of_risk'] == $value->id_level) {
                                            $level_of_risk = $value->level_name;
                                        }
                                    }
                                    ?>
                                    <span class="badge p-1 badge bg-list-set-filter bg-primary <?= @$this->session->userdata('Param_pipeline')['level_of_risk'] ? '' : 'd-none'; ?>">
                                        Level of risk : <span><?= $level_of_risk; ?></span>
                                        &nbsp;<span data-session="level_of_risk" style="cursor:pointer" class="badge-white clear-filter"><i class="fas fa-times-circle"></i></span>
                                    </span>

                                    <?php
                                    $tier = '';
                                    foreach ($param_tier as $key => $value) {
                                        if (@$this->session->userdata('Param_pipeline')['tier'] == $value->id_tier) {
                                            $tier = $value->tier_name;
                                        }
                                    }
                                    ?>
                                    <span class="badge p-1 badge bg-list-set-filter bg-primary <?= @$this->session->userdata('Param_pipeline')['tier'] ? '' : 'd-none'; ?>">
                                        Tier : <span><?= $tier; ?></span>
                                        &nbsp;<span data-session="tier" style="cursor:pointer" class="badge-white clear-filter"><i class="fas fa-times-circle"></i></span>
                                    </span>

                                </i>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-right">
                                <i class="text-grey-bold" style="margin-right: 10px;">Total Data : <?= $total; ?></i>
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
                                <label>Asal Pipeline</label>
                                <input type="text" name="pipeline_origin" class="form-control" placeholder="Asal Pipeline" maxlength="30" value="<?= @$this->session->userdata('Param_pipeline')['pipeline_origin'] ?>" id="pipeline_origin">
                            </div>
                            <div class="form-group">
                                <label>Level of Priorities</label>
                                <select class="form-control" name="level_of_priorities" id="fil_level_priorities">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_priorities as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>" <?= @$this->session->userdata('Param_pipeline')['level_of_priorities'] == $value->id_level ? 'selected' : ''; ?>><?= $value->level_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Level of Risk</label>
                                <select class="form-control" name="level_of_risk" id="fil_level_risk">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_risk as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>" <?= @$this->session->userdata('Param_pipeline')['level_of_risk'] == $value->id_level ? 'selected' : ''; ?>><?= $value->level_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tier</label>
                                <select class="form-control" name="tier" id="fil_tier">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_tier as $key => $value) : ?>
                                        <option value="<?= $value->id_tier; ?>" <?= @$this->session->userdata('Param_pipeline')['tier'] == $value->id_tier ? 'selected' : ''; ?>><?= $value->tier_name; ?></option>
                                    <?php endforeach; ?>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formInput" class="form-validation">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nama pipeline</label>
                                <input type="hidden" name="in_id" id="in_id" readonly>
                                <input type="text" name="in_pipeline_origin" class="form-control" id="in_pipeline_origin">
                            </div>
                            <div class="form-group">
                                <label>Level of Priorities</label>
                                <select class="form-control" name="in_level_of_priorities" id="in_level_of_priorities">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_priorities as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>"><?= $value->level_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Level of Risk</label>
                                <select class="form-control" name="in_level_of_risk" id="in_level_of_risk">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_risk as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>"><?= $value->level_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tier</label>
                                <select class="form-control" name="in_tier" id="in_tier">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_tier as $key => $value) : ?>
                                        <option value="<?= $value->id_tier; ?>"><?= $value->tier_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="far fa-times-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formUpdate" class="form-validation">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nama pipeline</label>
                                <input type="hidden" name="up_id" id="up_id" readonly>
                                <input type="text" name="up_pipeline_origin" class="form-control" id="up_pipeline_origin">
                            </div>
                            <div class="form-group">
                                <label>Level of Priorities</label>
                                <select class="form-control" name="up_level_of_priorities" id="up_level_of_priorities">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_priorities as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>"><?= $value->level_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Level of Risk</label>
                                <select class="form-control" name="up_level_of_risk" id="up_level_of_risk">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_level_risk as $key => $value) : ?>
                                        <option value="<?= $value->id_level; ?>"><?= $value->level_name; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tier</label>
                                <select class="form-control" name="up_tier" id="up_tier">
                                    <option value=""> -- Pilih -- </option>
                                    <?php foreach ($param_tier as $key => $value) : ?>
                                        <option value="<?= $value->id_tier; ?>"><?= $value->tier_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="far fa-times-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="far fa-edit"></i> Update
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

                                <input type="hidden" name="nama" value="<?= @$_fil_nama; ?>">
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

<div class="modal fade" id="preview-guide" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div id="title-modal">
                    <h3><b>Panduan Penggunaan</b></h3>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script>
    // config js
    let baseUrl = '<?= site_url('Param_pipeline'); ?>';
    let confLimit = <?= $this->lib_pagination->limit() ?>;
    let informasi_url = '<?= $module->user_guide ?>';

    let msgErrorGet = '<?= $this->session->flashdata('msgErrorGet') ?>';
</script>
