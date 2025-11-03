</div> <!-- end container-fluid -->
</div> <!-- end content -->
</div> <!-- end wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>

<!-- Bootstrap Bundle -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- DataTables -->
<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.bootstrap4.min.js'); ?>"></script>

<!-- SweetAlert2 -->
<script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>

<!-- Custom Script -->
<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        $('.datatable').DataTable({
            responsive: true,
            paging: true,
            ordering: false,
            info: true,
            scrollX: true,
            autoWidth: false,
            pageLength: 5,
            language: { url: "<?php echo base_url('assets/Indonesian.json'); ?>" }
        });
    });

    // SweetAlert Notif
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $this->session->flashdata('success'); ?>',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $this->session->flashdata('error'); ?>',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });
        <?php endif; ?>
    });
</script>

</body>

</html>