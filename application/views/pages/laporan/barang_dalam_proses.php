<!-- C:\xampp\htdocs\wms\application\views\pages\laporan\barang_dalam_proses.php -->

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css'); ?>">

<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Daftar</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/barang_proses') ?>" id="filterForm">
                <div class="row">

                    <?php if ($user_role == 'superadmin'): ?>
                        <!-- Warehouse Filter -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="warehouse_id" class="form-label">Gudang Asal <span class="text-danger">*</span>
                                </label>
                                <select name="warehouse_id" id="warehouse_id" class="form-control select2-gudang" required>
                                    <option value="">-- Semua Gudang --</option>
                                    <?php foreach ($warehouse_list as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>" <?= isset($filter_warehouse_id) && $filter_warehouse_id == $warehouse['warehouse_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['warehouse_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- User gudang: tampilkan nama gudang saja, value tersimpan di hidden -->
                        <input type="hidden" id="warehouse_id" value="<?= $user_warehouse_id ?>">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gudang</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($user_warehouse_name ?? '') ?>" disabled>
                        </div>
                    <?php endif; ?>


                    <!-- Filter Status -->
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

                    <!-- Tanggal Mulai -->
                    <div class="col-md-2 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-2 mb-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_end) ? date('d/m/Y', strtotime($filter_date_end)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Tombol Reset saja — Filter dihapus, auto-submit -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('laporan/barang_proses') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman Barang dalam Proses</h6>
        </div>
        <div class="card-body">
            <?php if (empty($pengiriman_list)): ?>
                <div class="alert alert-info">
                    Tidak ada data Pengiriman Barang dalam Proses.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            <?php $no = 1; ?>
                            <?php foreach ($pengiriman_list as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['stockout_code']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['stockout_date'])) ?></td>
                                    <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                                    <td><?= htmlspecialchars(!empty($row['to_name']) ? $row['to_name'] : ($row['to_id'] ?? '-')) ?>
                                    </td>
                                    <td><?= htmlspecialchars(!empty($row['stockout_note']) ? $row['stockout_note'] : '-') ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['user_name']) ?></td>
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

<!-- Flatpickr JS -->
<script src="<?php echo base_url('assets/flatpickr/flatpickr.js'); ?>"></script>
<script src="<?php echo base_url('assets/flatpickr/flatpickr__.js'); ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const filterForm = document.getElementById('filterForm');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const statusSel = document.getElementById('status');
        const warehouseSel = document.getElementById('warehouse_id');
        const overlay = document.getElementById('loadingOverlay');

        // =========================================================
        // Helper: parse tanggal d/m/Y → Date
        // (new Date('dd/mm/yyyy') tidak valid di semua browser)
        // =========================================================
        function parseDate(str) {
            if (!str) return null;
            const parts = str.split('/');
            if (parts.length !== 3) return null;
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }

        function showLoading() {
            overlay.style.display = 'block';
        }

        // =========================================================
        // Submit filter dengan validasi tanggal
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
        // Inisialisasi Flatpickr
        // onChange: auto-submit setelah pilih tanggal (seperti history_proyek)
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
                // Validasi dulu sebelum submit
                const start = parseDate(startInput.value);
                const end = parseDate(endInput.value);

                if (start && end && start > end) {
                    if (instance.element.id === 'start_date') {
                        toastr.error('Tanggal mulai tidak boleh lebih besar dari tanggal akhir', 'Tanggal Tidak Valid');
                    } else {
                        toastr.error('Tanggal akhir tidak boleh lebih kecil dari tanggal awal', 'Tanggal Tidak Valid');
                    }
                    instance.clear();
                    return;
                }

                // Kedua tanggal terisi → auto-submit
                if (startInput.value && endInput.value) {
                    submitFilter();
                }
            }
        };

        flatpickr('#start_date', flatpickrConfig);
        flatpickr('#end_date', flatpickrConfig);

        // =========================================================
        // Event: status & gudang → auto-submit saat berubah
        // =========================================================
        statusSel.addEventListener('change', function () {
            submitFilter();
        });

        warehouseSel.addEventListener('change', function () {
            submitFilter();
        });

        // =========================================================
        // Validasi saat form di-submit manual (safety net)
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