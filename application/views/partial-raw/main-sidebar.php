<a href="<?= $_url_dashboard; ?>" class="brand-link <?php echo $_theme_navbar; ?> text-sm">
    <img src="<?php echo base_url('assets/main-img/sanders-gold.png'); ?>" alt="Sanders Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light" style="color:#ffffff !important; font-weight:bold !important;"><?php echo $this->config->item('site_title') ?></span>
</a>

<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?php echo base_url('uploads/profile_picture/'.$_user_data[0]->user_photo); ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="<?= $_url_profile; ?>" class="d-block"><?= $_SESSION['sgn_user_fullname']; ?><br></a>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
            <?= $_menu_sidebar; ?>
        </ul>
    </nav>

</div>