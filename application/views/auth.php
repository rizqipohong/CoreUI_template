<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->config->item('site_title') ?></title>

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url(); ?>assets/main-img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-16x16.png">
    <!-- <link rel="manifest" href="<?php echo base_url(); ?>assets/main-img/favicon/manifest.json"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url(); ?>assets/main-img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= $_url_plugin; ?>fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= $_url_plugin; ?>icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= $_url_css; ?>adminlte.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo $_url_plugin; ?>toastr/toastr.min.css">

    <style>
        .preloader {
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            background-color: #001f3f;
            height: 100vh;
            width: 100%;
            transition: height 200ms linear;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 9999;
        }
    </style>

</head>

<body class="hold-transition login-page">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble" src="<?php echo base_url(); ?>assets/main-img/sanders-gold.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url(); ?>"><b><?php echo $this->config->item('site_title') ?></b>Backend</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Masuk Untuk Memulai</p>
                <form action="<?= $_signinURL; ?>" autocomplete="off" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username or Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember_me" id="remember">
                                <label for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="social-auth-links text-center mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            Masuk
                        </button>
                    </div>
                </form>

                <p class="mb-1 text-center">
                    <a href="<?= $_forgotURL; ?>">Saya Lupa Password</a>
                </p>

            </div>
        </div>
    </div>

    <div>
        Page rendered in <strong>{elapsed_time}</strong>
    </div>

    <?php if (ENVIRONMENT == "development") { ?>
        <div class="container" style="margin-top: 60px;">
            <div class="row">
                <div class="col-12 text-center">
                    <a href="<?= site_url('Auth/x_migration'); ?>" style="padding-right:30px;">Migrasi Database</a>
                    <a href="<?= site_url('Auth/x_default'); ?>">Data Awal</a>
                </div>
            </div>
        </div>
    <?php } ?>

    <script src="<?= $_url_plugin; ?>jquery/jquery.min.js"></script>
    <script src="<?= $_url_plugin; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $_url_js; ?>adminlte.min.js"></script>
    <!-- Toastr -->
    <script src="<?php echo $_url_plugin; ?>toastr/toastr.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var success = '<?= $this->session->flashdata('alert_success'); ?>';
            var error = '<?= $this->session->flashdata('alert_error'); ?>';
            var info = '<?= $this->session->flashdata('alert_info'); ?>';

            if (success) {
                toastr.success(success);
            }

            if (error) {
                toastr.error(error);
            }

            if (info) {
                toastr.error(info);
            }

            sessionStorage.clear();
            localStorage.clear();
        });

        toastr.options = {
            closeButton: false,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right", //toast-top-center
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
        };

    </script>
</body>

</html>
