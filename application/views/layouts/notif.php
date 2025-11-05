<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');
$warning = $this->session->flashdata('warning');
$info = $this->session->flashdata('info');
?>
<!-- Toastr CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Default options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showDuration": "300",
            "hideDuration": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        <?php if ($success): ?>
            toastr.success(<?= json_encode($success); ?>, <?= json_encode($wording['success']); ?>);
        <?php elseif ($error): ?>
            toastr.error(<?= json_encode($error); ?>, <?= json_encode($wording['error']); ?>);
        <?php elseif ($warning): ?>
            toastr.warning(<?= json_encode($warning); ?>, <?= json_encode($wording['warning']); ?>);
        <?php elseif ($info): ?>
            toastr.info(<?= json_encode($info); ?>, <?= json_encode($wording['info']); ?>);
        <?php endif; ?>
    });
</script>