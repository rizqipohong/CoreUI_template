<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->config->item('site_title') ?> | <?php echo $_title; ?></title>

    <link rel="apple-touch-icon" sizes="57x57"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo base_url(); ?>assets/main-img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="<?php echo base_url(); ?>assets/main-img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
          href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo base_url(); ?>assets/main-img/favicon/favicon-16x16.png">
    <!-- <link rel="manifest" href="<?php echo base_url(); ?>assets/main-img/favicon/manifest.json"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url(); ?>assets/main-img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!--STYLES CSS-->
    <?php
    foreach ($cssArr as $css) { ?>
        <link rel="stylesheet" href="<?php echo site_url($css) ?>">

        <?php
    }
    ?>
</head>

<body class="hold-transition layout-fixed layout-navbar-fixed <?php echo $_theme_body; ?>">
<div class="wrapper">

    <!-- Preloader -->
    <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?php echo base_url(); ?>assets/main-img/sanders-gold.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark <?php echo $_theme_navbar; ?> dropdown-legacy text-sm">
        <?php echo $_headers; ?>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar <?php echo $_theme_sidebar; ?> elevation-4">
        <?php echo $_sidebars; ?>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper text-sm">
        <?php echo $_contents; ?>
    </div>
    <!-- /.content-wrapper -->
    <?php

      // echo "<pre>"; print_r($jsArr); echo "</pre>";

    /**/
    foreach ($jsArr as $js) { ?>
        <script src="<?php echo site_url($js) ?>"></script>
        <?php
    }

    ?>
    <!-- Main Footer -->
    <footer class="main-footer text-sm">
        <?php echo $_footers; ?>
    </footer>
    <!-- REQUIRED SCRIPTS -->

</div>
<!-- ./wrapper -->


</body>

</html>
