<!-- C:\xampp\htdocs\wms\application\views\pages\laporan\history_proyek.php -->

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
            <h6 class="m-0 font-weight-bold text-primary">Filter Daftar</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/history_proyek') ?>" id="filterForm">
                <div class="row align-items-end">

                    <!-- Filter Proyek/Gudang -->
                    <!--
                        BUG FIX: sebelumnya ada DUA elemen dengan id="warehouse_id"
                        (satu <select> tanpa kondisi + satu blok dengan kondisi superadmin).
                        getElementById hanya ambil yang pertama → Select2 dan event
                        listener terpasang ke elemen yang salah.
                        Sekarang hanya SATU elemen dengan id yang unik.
                    -->
                    <div class="col-md-3 mb-3">
                        <label for="warehouse_id" class="form-label">
                            Nama Proyek
                            <?php if ($user_role == 'superadmin'): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>

                        <?php if ($user_role == 'superadmin'): ?>
                            <select name="warehouse_id" id="warehouse_id" class="form-control select2-gudang">
                                <option value="">-- Pilih Proyek --</option>
                                <?php foreach ($warehouse_list as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>" <?= isset($filter_warehouse_id) && $filter_warehouse_id == $warehouse['warehouse_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($warehouse['warehouse_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <!-- name wajib ada agar terkirim saat submit -->
                            <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?= $user_warehouse_id ?>">
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($user_warehouse_name ?? '') ?>" disabled>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_end) ? date('d/m/Y', strtotime($filter_date_end)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Reset -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('laporan/history_proyek') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>

                <!-- Detail Proyek/Gudang -->
                <div id="warehouseDetailContainer">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Silahkan pilih proyek terlebih dahulu untuk melihat Histori Proyek.
                    </div>
                </div>

            </form>
        </div>
    </div>

    <?php if ($is_filtered): ?>

        <?php
        $total_transaksi = count($pengiriman_list);
        $total_masuk = 0;
        $total_keluar = 0;
        $produk_unik = [];

        foreach ($pengiriman_list as $row) {
            $produk_unik[$row['product_code']] = true;
            if ($row['transaction_type'] === 'Masuk') {
                $total_masuk += (float) $row['qty'];
            } else {
                $total_keluar += (float) $row['qty'];
            }
        }
        $total_produk = count($produk_unik);
        ?>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaksi</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?= number_format($total_transaksi) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jenis Barang</div>
                        <div class="h4 mb-0 font-weight-bold text-warning"><?= number_format($total_produk) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Masuk</div>
                        <div class="h4 mb-0 font-weight-bold text-success"><?= number_format($total_masuk) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-danger shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Keluar</div>
                        <div class="h4 mb-0 font-weight-bold text-danger"><?= number_format($total_keluar) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Histori Proyek -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Histori Proyek</h6>
                    <button type="button" id="exportPdfBtn" class="btn btn-danger btn-sm mt-2 mt-md-0">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($pengiriman_list)): ?>
                    <div class="alert alert-info">Tidak ada data Histori Proyek.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="100">Tanggal</th>
                                    <th width="180">Nomor Surat</th>
                                    <th width="100">Kode</th>
                                    <th>Nama Barang</th>
                                    <th width="80">Satuan</th>
                                    <th width="120">Qty Masuk</th>
                                    <th width="120">Qty Keluar</th>
                                    <th width="100">Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($pengiriman_list as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['transaction_date'])) ?></td>
                                        <td><?= htmlspecialchars($row['transaction_code']) ?></td>
                                        <td><strong><?= htmlspecialchars($row['product_code']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row['unit']) ?></td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] === 'Masuk'): ?>
                                                <span class="font-weight-bold text-success">
                                                    + <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] === 'Keluar'): ?>
                                                <span class="font-weight-bold text-danger">
                                                    - <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] === 'Masuk'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-arrow-down"></i> Masuk
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-arrow-up"></i> Keluar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>

</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(255,255,255,0.8); z-index:9999;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
        <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status"></div>
        <h5 class="mt-3 mb-0">Memuat data...</h5>
    </div>
</div>

<!-- JS — urutan penting: Flatpickr → Select2 → script kita -->
<!-- FIX: flatpickr__.js dihapus, path Select2 disesuaikan dengan path CSS -->
<script src="<?= base_url('assets/flatpickr/flatpickr.js') ?>"></script>
<script src="<?= base_url('assets/flatpickr/flatpickr__.js') ?>"></script>
<script src="<?= base_url('assets/select2/select2.min.js') ?>"></script>

<script>
    // Data gudang untuk render detail card
    const warehouseData = <?= json_encode($warehouse_list) ?>;
</script>

<script>
    $(document).ready(function () {

        // =========================================================
        // Konstanta
        // =========================================================
        const isSuperAdmin = '<?= $user_role ?>' === 'superadmin';
        const filterForm = document.getElementById('filterForm');
        const warehouseContainer = document.getElementById('warehouseDetailContainer');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

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
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        // =========================================================
        // Render detail card proyek/gudang
        // =========================================================
        function renderWarehouseDetail(warehouseId) {
            if (!warehouseId || warehouseId === '') {
                warehouseContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Silahkan pilih proyek terlebih dahulu untuk melihat Histori Proyek.
                </div>`;
                return;
            }

            const warehouse = warehouseData.find(item => item.warehouse_id == warehouseId);
            if (!warehouse) return;

            const isAktif = warehouse.warehouse_status == '0';
            const badgeClass = isAktif ? 'badge-success' : 'badge-danger';
            const badgeText = isAktif
                ? '<i class="fas fa-check-circle mr-1"></i> Aktif'
                : '<i class="fas fa-times-circle mr-1"></i> Tidak Aktif';

            warehouseContainer.innerHTML = `
            <div class="card shadow-sm border-left-primary mb-3">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col-lg-10">
                            <h4 class="text-primary font-weight-bold mb-2">
                                <i class="fas fa-warehouse mr-2"></i>
                                ${warehouse.warehouse_name}
                            </h4>
                            <div class="text-muted" style="font-size:15px; line-height:1.8;">
                                <span class="mr-4">
                                    <i class="fas fa-barcode text-secondary mr-1"></i>
                                    ${warehouse.warehouse_code}
                                </span>
                                <span class="mr-4">
                                    <i class="fas fa-layer-group text-info mr-1"></i>
                                    ${warehouse.warehouse_type_name}
                                </span>
                                <span class="mr-4">
                                    <i class="fas fa-user text-success mr-1"></i>
                                    ${warehouse.contact_person || '-'}
                                </span>
                                <span>
                                    <i class="fas fa-phone text-warning mr-1"></i>
                                    ${warehouse.phone || '-'}
                                </span>
                            </div>
                            <div class="mt-2" style="font-size:15px;">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                ${warehouse.warehouse_address || '-'}
                            </div>
                        </div>
                        <div class="col-lg-2 text-lg-right mt-3 mt-lg-0">
                            <span class="badge ${badgeClass} px-4 py-2" style="font-size:14px;">
                                ${badgeText}
                            </span>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        // =========================================================
        // submitFilter: validasi tanggal lalu submit
        // =========================================================
        function submitFilter() {
            const start = parseDate(startDateInput.value);
            const end = parseDate(endDateInput.value);

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
        // FIX: select2Config dipindah ke dalam $(document).ready
        // agar jQuery ($) sudah pasti tersedia saat dieksekusi.
        // Gunakan jQuery .on('change') bukan addEventListener native
        // agar konsisten dengan event lifecycle Select2.
        // =========================================================
        if (isSuperAdmin && $('#warehouse_id').is('select')) {
            $('#warehouse_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                minimumResultsForSearch: 0,
                placeholder: '-- Pilih Proyek --',
                dropdownParent: $('body')
            });

            // Event gudang — jQuery .on() untuk Select2
            $('#warehouse_id').on('change', function () {
                const val = $(this).val() || '';
                renderWarehouseDetail(val);

                if (!val) {
                    toastr.info('Silahkan pilih proyek untuk menampilkan histori', 'Informasi');
                    return;
                }

                submitFilter();
            });

            // Render detail card untuk nilai yang sudah terpilih dari server
            const initialVal = $('#warehouse_id').val() || '';
            renderWarehouseDetail(initialVal);

        } else {
            // Non-superadmin: render langsung dari value hidden input
            const hiddenVal = document.getElementById('warehouse_id')
                ? document.getElementById('warehouse_id').value
                : '';
            renderWarehouseDetail(hiddenVal);
        }

        // =========================================================
        // Flatpickr
        //
        // FIX: tambahkan guard `selectedDates.length === 0` di baris
        // pertama onChange. Tanpa ini, instance.clear() memicu onChange
        // lagi → infinite loop → halaman hang.
        // =========================================================
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
                // GUARD: instance.clear() memicu onChange lagi dengan selectedDates=[].
                // Tanpa guard ini → infinite loop → halaman hang.
                if (selectedDates.length === 0) return;

                // Validasi: superadmin harus pilih gudang dulu
                const warehouseVal = $('#warehouse_id').val() || document.getElementById('warehouse_id')?.value || '';
                if (isSuperAdmin && !warehouseVal) {
                    toastr.warning('Silahkan pilih proyek terlebih dahulu', 'Peringatan');
                    instance.clear(); // aman karena guard di atas menghentikan re-entry
                    return;
                }

                const start = parseDate(startDateInput.value);
                const end = parseDate(endDateInput.value);

                if (start && end && start > end) {
                    toastr.error(
                        instance.element.id === 'start_date'
                            ? 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                            : 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal',
                        'Tanggal Tidak Valid'
                    );
                    instance.clear(); // aman karena guard di atas menghentikan re-entry
                    return;
                }

                if (startDateInput.value && endDateInput.value) {
                    submitFilter();
                }
            }
        });

        // =========================================================
        // Safety net: validasi saat form submit manual
        // =========================================================
        filterForm.addEventListener('submit', function (e) {
            const warehouseVal = $('#warehouse_id').val() || document.getElementById('warehouse_id')?.value || '';
            if (isSuperAdmin && !warehouseVal) {
                e.preventDefault();
                toastr.warning('Silahkan pilih proyek terlebih dahulu', 'Peringatan');
                return;
            }

            const start = parseDate(startDateInput.value);
            const end = parseDate(endDateInput.value);

            if (start && end && start > end) {
                e.preventDefault();
                toastr.error(
                    'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid'
                );
            }
        });

        // =========================================================
        // Export PDF
        // =========================================================
        const exportPdfBtn = document.getElementById('exportPdfBtn');
        if (exportPdfBtn) {
            exportPdfBtn.addEventListener('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(
                    '<?= site_url("laporan/export_history_proyek_pdf") ?>?' + params.toString(),
                    '_blank'
                );
            });
        }

    });
</script>