<!-- C:\xampp\htdocs\wms\application\views\pages\laporan\barang_dalam_proses.php -->

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css') ?>">

<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/select2/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet" />

<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kartu Stok</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/barang_proses') ?>" id="filterForm">
                <div class="row align-items-start">

                    <!-- Kolom 1: Gudang -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Gudang Asal
                            <?php if ($user_role == 'superadmin'): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>

                        <?php if ($user_role == 'superadmin'): ?>
                            <select name="warehouse_id" id="warehouse_id" class="form-control select2-gudang">
                                <option value="">-- Semua Gudang --</option>
                                <?php foreach ($warehouse_list as $w): ?>
                                    <option value="<?= $w['warehouse_id'] ?>" <?= ($w['warehouse_id'] == $filter_warehouse_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($w['warehouse_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <!-- name="warehouse_id" wajib ada agar terkirim saat submit -->
                            <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?= $user_warehouse_id ?>">
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($user_warehouse_name ?? '') ?>" disabled>
                        <?php endif; ?>
                    </div>

                    <!-- Kolom 2: Status -->
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status Pengiriman</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="0" <?= isset($filter_status) && $filter_status === '0' ? 'selected' : '' ?>>
                                Terkirim
                            </option>
                            <option value="1" <?= isset($filter_status) && $filter_status === '1' ? 'selected' : '' ?>>
                                Dalam Proses
                            </option>
                        </select>
                    </div>

                    <!-- Kolom 3: Tanggal Mulai -->
                    <div class="col-md-2 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Kolom 4: Tanggal Akhir -->
                    <div class="col-md-2 mb-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_end) ? date('d/m/Y', strtotime($filter_date_end)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Kolom 5: Reset -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('laporan/barang_proses') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                        <span class="filter-hint">&nbsp;</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Pengiriman Barang dalam Proses
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($pengiriman_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data Pengiriman Barang dalam Proses.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light text-center align-middle">
                            <tr>
                                <th width="40">No</th>
                                <th>Kode Pengiriman</th>
                                <th width="100">Tanggal</th>
                                <th>Dari Gudang</th>
                                <th>Ke Gudang</th>
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                                <th width="110">Status</th>
                                <th width="60">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($pengiriman_list as $row): ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $no++ ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['stockout_code']) ?>
                                    </td>
                                    <td>
                                        <?= date('d-m-Y', strtotime($row['stockout_date'])) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['warehouse_name']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(!empty($row['to_name']) ? $row['to_name'] : ($row['to_id'] ?? '-')) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(!empty($row['stockout_note']) ? $row['stockout_note'] : '-') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['user_name']) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['on_transfer_status'] == 0): ?>
                                            <span class="badge bg-success">Terkirim</span>
                                        <?php elseif ($row['on_transfer_status'] == 1): ?>
                                            <span class="badge bg-warning text-dark">Dalam Proses</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('laporan/detail/' . $row['stockout_id']) ?>"
                                            class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(255,255,255,0.8); z-index:9999;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
        <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status"></div>
        <h5 class="mt-3 mb-0">Memuat data...</h5>
    </div>
</div>

<!-- Flatpickr JS — asset lokal -->
<script src="<?= base_url('assets/flatpickr/flatpickr.js') ?>"></script>

<!-- Select2 JS — asset lokal -->
<script src="<?= base_url('assets/select2/select2.min.js') ?>"></script>

<script>
    $(document).ready(function () {

        const isSuperAdmin = '<?= $user_role ?>' === 'superadmin';

        const filterForm = document.getElementById('filterForm');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const overlay = document.getElementById('loadingOverlay');

        // =========================================================
        // Helper: parse tanggal d/m/Y → Date (safe cross-browser)
        // =========================================================
        function parseDate(str) {
            if (!str) return null;
            const p = str.split('/');
            if (p.length !== 3) return null;
            return new Date(p[2], p[1] - 1, p[0]);
        }

        function showLoading() {
            overlay.style.display = 'block';
        }

        // =========================================================
        // submitFilter: validasi tanggal lalu submit
        // =========================================================
        function submitFilter() {
            const start = parseDate(startInput.value);
            const end = parseDate(endInput.value);

            if (start && end && start > end) {
                toastr.error(
                    'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid'
                );
                return;
            }

            showLoading();
            filterForm.submit();
        }

        // =========================================================
        // Inisialisasi Select2 untuk gudang (superadmin saja)
        //
        // FIX: Select2 CSS sudah di-load tapi JS-nya tidak pernah
        // diinisialisasi di versi sebelumnya — dropdown tampil sebagai
        // native select tanpa search box.
        //
        // Pakai jQuery .on('change') bukan addEventListener agar
        // event Select2 (yang dikelola jQuery) tertangkap dengan benar.
        // =========================================================
        if (isSuperAdmin && $('#warehouse_id').is('select')) {
            $('#warehouse_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                minimumResultsForSearch: 0,
                placeholder: '-- Semua Gudang --',
                dropdownParent: $('body')
            });

            // Event gudang: pakai jQuery .on('change') karena Select2
            $('#warehouse_id').on('change', function () {
                submitFilter();
            });
        }

        // =========================================================
        // Event: status → auto-submit
        // Status adalah native <select> biasa, addEventListener cukup
        // =========================================================
        document.getElementById('status').addEventListener('change', function () {
            submitFilter();
        });

        // =========================================================
        // Flatpickr — auto-submit setelah kedua tanggal valid terisi
        // =========================================================
        const flatpickrConfig = {
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
                    toastr.error(
                        instance.element.id === 'start_date'
                            ? 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                            : 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal',
                        'Tanggal Tidak Valid'
                    );
                    instance.clear();
                    return;
                }

                if (startInput.value && endInput.value) {
                    submitFilter();
                }
            }
        };

        flatpickr('#start_date', flatpickrConfig);
        flatpickr('#end_date', flatpickrConfig);

        // =========================================================
        // Safety net: validasi saat form di-submit manual
        // =========================================================
        filterForm.addEventListener('submit', function (e) {
            const start = parseDate(startInput.value);
            const end = parseDate(endInput.value);

            if (start && end && start > end) {
                e.preventDefault();
                toastr.error(
                    'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid'
                );
            }
        });

    });
</script>