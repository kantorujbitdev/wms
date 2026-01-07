<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');
$warning = $this->session->flashdata('warning');
$info = $this->session->flashdata('info');
?>

<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Peringatan !</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <p class="mb-0" id="errorMessage"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Default options untuk toastr (kecuali error)
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
        <?php elseif ($warning): ?>
            toastr.warning(<?= json_encode($warning); ?>, <?= json_encode($wording['warning']); ?>);
        <?php elseif ($info): ?>
            toastr.info(<?= json_encode($info); ?>, <?= json_encode($wording['info']); ?>);
        <?php elseif ($error): ?>
            // Tampilkan modal untuk error
            $('#errorMessage').text(<?= json_encode($error); ?>);
            $('#errorModal').modal('show');
        <?php endif; ?>
    });
</script>