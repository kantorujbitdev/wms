</div> <!-- end container -->
</div> <!-- end content-wrapper -->

<!-- Dynamic Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmButton">Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Sidebar Toggle Script dan modal-->
<script>
    $(document).ready(function () {

        // Inisialisasi Select2 global
        $('select').select2({
            width: '100%'
        });

        // Mobile sidebar toggle functionality
        $('#sidebarToggle').on('click', function () {
            $('.sidebar').toggleClass('show');
        });

        // Desktop sidebar toggle functionality
        $('#sidebarToggleDesktop').on('click', function () {
            $('.sidebar').toggleClass('collapsed');
            $('.content-wrapper').toggleClass('expanded');

            // Save sidebar state to localStorage
            if ($('.sidebar').hasClass('collapsed')) {
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                localStorage.setItem('sidebarState', 'expanded');
            }
        });

        // Check saved sidebar state for desktop
        if (window.innerWidth >= 992) {
            if (localStorage.getItem('sidebarState') === 'collapsed') {
                $('.sidebar').addClass('collapsed');
                $('.content-wrapper').addClass('expanded');
            }
        }

        // Close sidebar when clicking outside on mobile
        $(document).on('click', function (e) {
            if (window.innerWidth < 992) {
                if (!$(e.target).closest('.sidebar').length &&
                    !$(e.target).closest('#sidebarToggle').length &&
                    $('.sidebar').hasClass('show')) {
                    $('.sidebar').removeClass('show');
                }
            }
        });

        // Handle window resize
        $(window).on('resize', function () {
            if (window.innerWidth >= 992) {
                // For desktop views
                if (localStorage.getItem('sidebarState') !== 'collapsed') {
                    $('.sidebar').removeClass('collapsed show');
                    $('.content-wrapper').removeClass('expanded');
                } else {
                    $('.sidebar').addClass('collapsed');
                    $('.content-wrapper').addClass('expanded');
                }
            } else {
                // For mobile views
                $('.sidebar').removeClass('collapsed');
                $('.content-wrapper').removeClass('expanded');
            }
        });

        // Add active class to submenu items
        $('.sidebar-sublink').each(function () {
            if ($(this).attr('href') === window.location.pathname) {
                $(this).addClass('active');
                $(this).closest('.collapse').addClass('show');
                $(this).closest('.sidebar-item').addClass('active');
            }
        });

        // Handle dropdown menu on mobile
        $('.dropdown-toggle').on('click', function (e) {
            if (window.innerWidth < 992) {
                // Let Bootstrap handle the dropdown toggle
                // This ensures it works properly on mobile
            }
        });

        // Fungsi untuk menampilkan modal konfirmasi
        window.showConfirmationModal = function (options) {
            // Set default values
            const defaults = {
                title: 'Konfirmasi',
                message: 'Apakah Anda yakin ingin melanjutkan?',
                confirmText: 'Ya',
                confirmClass: 'btn-danger',
                onConfirm: null,
                confirmUrl: null
            };

            // Merge options with defaults
            const settings = $.extend({}, defaults, options);

            // Set modal content
            $('#confirmationModalLabel').text(settings.title);
            $('#confirmationMessage').text(settings.message);
            $('#confirmButton').text(settings.confirmText);

            // Set button class
            $('#confirmButton').removeClass('btn-danger btn-success btn-primary btn-warning')
                .addClass(settings.confirmClass);

            // Remove previous event handlers
            $('#confirmButton').off('click');

            // Set confirm button action
            if (settings.confirmUrl) {
                // If URL is provided, redirect to that URL
                $('#confirmButton').on('click', function () {
                    window.location.href = settings.confirmUrl;
                });
            } else if (settings.onConfirm && typeof settings.onConfirm === 'function') {
                // If callback function is provided, execute it
                $('#confirmButton').on('click', function () {
                    settings.onConfirm();
                    $('#confirmationModal').modal('hide');
                });
            }

            // Show the modal
            $('#confirmationModal').modal('show');
        };

        // Logout confirmation modal
        $('#logoutBtn').on('click', function (e) {
            e.preventDefault();
            showConfirmationModal({
                title: 'Konfirmasi Logout',
                message: 'Apakah Anda yakin ingin keluar dari sistem?',
                confirmText: 'Ya, Logout',
                confirmClass: 'btn-danger',
                confirmUrl: '<?= site_url('auth/logout'); ?>'
            });
        });

        $(document).on('click', '.actionBtnDelete', function () {

            const url = $(this).data('url'); // ambil dari atribut data-url
            const id = $(this).data('id'); // ambil dari atribut data-url
            const name = $(this).data('name'); // ambil dari atribut data-url

            showConfirmationModal({
                title: 'Konfirmasi Hapus?',
                message: 'Yakin ingin menghapus data: ' + name + '?',
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                confirmUrl: url + '/' + id // kirim ke modal
            });
        });

        $('#confirmationModal').on('hidden.bs.modal', function () {
            // Hilangkan fokus dari elemen yang sebelumnya aktif
            if (document.activeElement) {
                document.activeElement.blur();
            }

            // (Opsional) arahkan fokus kembali ke body atau tombol pemicu
            $('body').trigger('focus');
        });


    });
</script>

</body>

</html>