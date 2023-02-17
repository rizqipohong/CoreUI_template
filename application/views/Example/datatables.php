<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $_url_plugin; ?>datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $_url_plugin; ?>datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $_url_plugin; ?>datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>DataTables</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">DataTables</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sample Datatable</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>Platform(s)</th>
                                    <th>Engine version</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($x = 0; $x <= 100; $x++) { ?>
                                    <tr>
                                        <td>Trident</td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>
                                            <a href="#" class="text-dotted" data-toggle="modal" data-target="#modal-xl">
                                                John Doe
                                            </a>
                                        </td>
                                        <td> 4</td>
                                        <td>X</td>
                                        <td>Trident</td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>Trident</td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td> 4</td>
                                        <td>X</td>
                                        <td>Trident</td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td> 4</td>
                                        <td>X</td>
                                        <td>Trident</td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td>Internet
                                            Explorer 4.0
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-xs">Action</button>
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>Platform(s)</th>
                                    <th>Engine version</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Extra Large Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- DataTables  & Plugins -->
<script src="<?php echo $_url_plugin; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $_url_plugin; ?>datatables-bs4/js/dataTables.bootstrap4.min.js">
</script>
<script src="<?php echo $_url_plugin; ?>datatables-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?php echo $_url_plugin; ?>datatables-responsive/js/responsive.bootstrap4.min.js">
</script>
<script src="<?php echo $_url_plugin; ?>datatables-buttons/js/dataTables.buttons.min.js">
</script>
<script src="<?php echo $_url_plugin; ?>datatables-buttons/js/buttons.bootstrap4.min.js">
</script>
<script src="<?php echo $_url_plugin; ?>jszip/jszip.min.js"></script>
<script src="<?php echo $_url_plugin; ?>pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $_url_plugin; ?>pdfmake/vfs_fonts.js"></script>
<script src="<?php echo $_url_plugin; ?>datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $_url_plugin; ?>datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo $_url_plugin; ?>datatables-buttons/js/buttons.colVis.min.js">
</script>

<script>
    $(function() {
        $("#example1").DataTable({
            "paging": true,
            "responsive": false,
            "lengthChange": true,
            "autoWidth": false,
            "searching": true,
            "info": true,
            "ordering": true,
            "pageLength": 5,
            "processing": false,
            "scrollX": true,
            "stateSave": true,
            "columns": [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                {
                    "searchable": false
                },
            ],
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>