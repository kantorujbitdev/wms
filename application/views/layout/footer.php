<!-- /Main Content -->
</div>
</div>

<!-- App Footer -->
<div class="app-footer">
    <div class="container-fluid main-container">
        <div class="row">
            <div class="col-md-6">
                <span class="footer-text">© <?php echo date('Y'); ?> <?php echo $this->config->item('app_name'); ?>. All
                    rights reserved.</span>
            </div>
            <div class="col-md-6 text-right">
                <span class="footer-text">Version 1.0.0</span>
            </div>
        </div>
    </div>
</div>
<!-- /App Footer -->
</div>
<!-- /App Main Content -->
</div>

<!-- Mantis Template JS -->
<script src="<?php echo base_url('assets/mantis/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/mantis/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/mantis/js/icons.js'); ?>"></script>
<script src="<?php echo base_url('assets/mantis/js/app.js'); ?>"></script>

<!-- SweetAlert2 JS -->
<script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>

<!-- Chart.js -->
<script src="<?php echo base_url('assets/js/chart.min.js'); ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url('assets/js/select2.min.js'); ?>"></script>

<!-- Custom JS -->
<script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>

<script>
    $(document).ready(function () {
        // Hide app loading
        $('#app-loading').fadeOut();

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Initialize popovers
        $('[data-toggle="popover"]').popover();

        // Auto hide flash messages after 5 seconds
        setTimeout(function () {
            $(".alert").fadeOut();
        }, 5000);
    });
</script>
</body>

</html>