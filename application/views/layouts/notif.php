<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');
$warning = $this->session->flashdata('warning');
$info = $this->session->flashdata('info');
?>

<!-- Modal untuk Error -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> <?= $wording['error'] ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                    </div>
                    <div>
                        <p class="mb-0" id="errorMessage"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-check"></i> Mengerti
                </button>
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