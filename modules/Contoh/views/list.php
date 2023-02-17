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
                                <?php if (can('Contoh/create_data')) { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-success" onclick="_modal_input()">
                                        <i class="fas fa-plus-circle"></i> Tambah
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-120 btn-success" disabled>
                                        <i class="fas fa-plus-circle"></i> Tambah
                                    </button>
                                <?php } ?>

                                <?php if (can('Contoh/export_data')) { ?>
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
                                <button type="button" class="btn btn-sm btn-120 btn-warning" onclick="_modal_demo()">
                                    <i class="fas fa-info-circle"></i> Demo
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body p-0 table-height" style="overflow-x:auto; height:450px;" id="ID_divtable">
                        <table class="table table-bordered table-head-fixed table-hover text-nowrap table-sm" id="ID_table">
                            <thead class="<?= $_th_style; ?>">
                                <tr class="text-center">
                                    <th class="col-tab120">Nama</th>
                                    <th class="col-tab300">Display Name </th>
                                    <th class="col-tab300">Deskripsi</th>
                                    <th class="col-tab100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($table->data as $key => $val) {
                                ?>
                                    <tr class="contoh" id="contoh_<?php echo $val->id; ?>">
                                        <td class="text-center"><?= $val->name; ?></td>
                                        <td class="text-left"><?= $val->display_name ?></td>
                                        <td class="text-left"><?= $val->description ?></td>
                                        <td class="text-center">
                                            <?php if (can('Contoh/update_data')) { ?>
                                                <button type="button" class="btn btn-xs btn-action-icon btn-warning" onclick="_modal_update('<?= $val->id; ?>')">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-xs btn-action-icon btn-warning" disabled>
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            <?php } ?>
                                            <?php if (can('Contoh/delete_data')) { ?>
                                                <button type="button" class="btn btn-xs btn-action-icon btn-danger" onclick="_delete('<?= $val->id; ?>')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-xs btn-action-icon btn-danger" disabled>
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                                }

                                if (empty($table)) {
                                ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-bold">No Data</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>
                    <input type="hidden" id="row" value="0">
                    <input type="hidden" id="all_contoh_count" value="<?php echo $total; ?>">
                    <div class="row ajax-load" style="display: none;">
                        <div class="col-lg-12" style="text-align: center;"><img width="100" height="100" src="https://www.primebldg.com/wp-content/uploads/2017/09/ajax-loader.gif"></div>
                    </div>


                    <div class="card-footer clearfix" id="ID_divpagination">
                        <div class="row" id="ID_pagination">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-left">
                                <i style="margin-right: 10px;">Filter : </i>
                                <br>
                                <i style="margin-right: 10px;">Total Data : <?= $total; ?></i>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-right">

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
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama" minlength="3" maxlength="30" value="<?= @$this->session->userdata('contoh')['nama'] ?>" id="fil_nama">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" placeholder="Alamat" minlength="3" maxlength="30" value="<?= @$this->session->userdata('contoh')['alamat'] ?>" id="fil_alamat">
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
                <h4 class="modal-title">Tambah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" id="formInput" class="form-validation">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama" id="nama">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" placeholder="Alamat" id="alamat"></textarea>
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
                <h4 class="modal-title">Ubah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formUpdate" class="form-validation">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="hidden" name="id" id="up_id" required readonly>
                                <input type="text" name="nama" class="form-control" placeholder="Nama" id="up_nama" required>

                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" placeholder="Alamat" id="up_alamat"></textarea>

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

<div class="modal fade" id="modal-demo">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Validation Demo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-demo" method="post" class="form-horizontal" action="">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="firstname">First name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="lastname">Last name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="username">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="phone">Phone</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="email">Email</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="password">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="confirm_password">Confirm password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-8 offset-sm-4">
                            <div class="form-check">
                                <input type="checkbox" id="agree" name="agree" value="agree" class="form-check-input" />
                                <label class="form-check-label">Please agree to our policy</label>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-sm-9 offset-sm-4">
                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer justify-content-between">

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Submit
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
    let baseUrl = '<?= site_url('Contoh'); ?>';
    let confLimit = <?= $this->lib_pagination->limit() ?>;
    let informasi_url = '<?= $module->user_guide ?>';
</script>