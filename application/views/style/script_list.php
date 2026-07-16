<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(255,255,255,0.8); z-index:9999;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
        <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status"></div>
        <h5 class="mt-3 mb-0">Memuat data...</h5>
    </div>
</div>

<!-- Flatpickr — asset lokal -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css') ?>">
<script src="<?php echo base_url('assets/flatpickr/flatpickr.js') ?>"></script>
<script src="<?php echo base_url('assets/flatpickr/flatpickr__.js'); ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const filterForm = document.getElementById('filterForm');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const overlay = document.getElementById('loadingOverlay');

        function parseDate(str) {
            if (!str) return null;
            const p = str.split('/');
            if (p.length !== 3) return null;
            return new Date(p[2], p[1] - 1, p[0]);
        }

        function showLoading() {
            overlay.style.display = 'block';
        }

        function submitFilter() {
            const start = parseDate(startInput.value);
            const end = parseDate(endInput.value);

            if (start && end && start > end) {
                $('#errorMessage').text('Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid');
                $('#errorModal').modal('show');
                return;
            }

            showLoading();
            filterForm.submit();
        }

        flatpickr('.flatpickr', {
            dateFormat: 'd/m/Y',
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                }
            },
            onChange: function (selectedDates, dateStr, instance) {
                const start = parseDate(startInput.value);
                const end = parseDate(endInput.value);

                if (start && end && start > end) {
                    $('#errorMessage').text(instance.element.id === 'start_date'
                        ? 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                        : 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal',
                        'Tanggal Tidak Valid');
                    $('#errorModal').modal('show');

                    instance.clear();
                    return;
                }

                if (startInput.value && endInput.value) {
                    submitFilter();
                }
            }
        });

        filterForm.addEventListener('submit', function (e) {
            const start = parseDate(startInput.value);
            const end = parseDate(endInput.value);

            if (start && end && start > end) {
                e.preventDefault();
                $('#errorMessage').text('Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid');
                $('#errorModal').modal('show');
            }
        });

    });
</script>