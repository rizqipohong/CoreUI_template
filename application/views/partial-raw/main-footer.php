<strong>Copyright &copy; <?php echo $this->config->item('copyright_year') ?> <a href="<?php echo $this->config->item('copyright_site') ?>" target="_blank"><?php echo $this->config->item('site_title') ?></a>.</strong>
All rights reserved.

<div class="float-right d-none d-sm-inline-block">
    <b>Version</b> <?php echo $this->config->item('version') ?> -
    Page rendered in <strong>{elapsed_time}</strong>
</div>

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
    });

    $('.double-scroll').doubleScroll({
        resetOnWindowResize: true,
        timeToWaitForResize: 30,
        onlyIfScroll: true
    });
</script>