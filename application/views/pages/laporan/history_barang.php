<!-- Flatpickr CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css'); ?>">

<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/select2/bootstrap5/select2.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/select2/bootstrap5/select2-bootstrap-5-theme.min.css'); ?>" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-search--dropdown {
        display: block !important;
        padding: 6px;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 6px 10px !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        z-index: 9999;
    }
</style>

<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Daftar</h6>
        </div>

        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/history_barang') ?>" id="filterForm" class="mb-4">
                <div class="row">

                    <!-- Filter Barang -->
                    <div class="col-md-3 mb-3">
                        <label for="product_id" class="form-label">Nama Barang</label>
                        <select id="product_id" class="form-control select2" name="product_id">
                            <option value="">Pilih Produk</option>
                            <?php foreach ($products_list as $product):
                                $display_name = htmlspecialchars(
                                    $product['product_code'] . ' - ' . $product['product_name'] . ' || ' . $product['unit_code'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                                <option value="<?= $product['product_id'] ?>" <?= (string) $filter_product_id === (string) $product['product_id'] ? 'selected' : '' ?>>
                                    <?= $display_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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

                    <!-- Tombol Reset -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('laporan/history_barang') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>

                <!-- Detail Barang -->
                <div id="barangDetail" class="mt-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Silakan pilih barang terlebih dahulu untuk melihat Histori Barang.
                    </div>
                </div>

            </form>
        </div>
    </div>

    <?php if ($is_filtered): ?>

        <!-- Tabel Histori Barang -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Histori Barang</h6>

                    <button type="button" id="exportPdfBtn" class="btn btn-danger btn-sm mt-2 mt-md-0">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                </div>
            </div>

            <div class="card-body">
                <?php if (empty($history_barang_list)): ?>
                    <div class="alert alert-info">Tidak ada data Histori Barang.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th width="40">No</th>
                                    <th width="80">Tanggal</th>
                                    <th width="180">Nomor Surat</th>
                                    <th width="120">Asal</th>
                                    <th width="120">Tujuan</th>
                                    <th width="90">Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th width="60">Satuan</th>
                                    <th width="90">Qty</th>
                                    <th width="80">Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($history_barang_list as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['movement_date'])) ?></td>
                                        <td><?= $row['movement_refno'] ?></td>
                                        <td class="text-center"><?= $row['from_origin_name'] ?? '-' ?></td>
                                        <td class="text-center"><?= $row['to_destination_name'] ?? '-' ?></td>
                                        <td><strong><?= $row['product_code'] ?></strong></td>
                                        <td><?= $row['product_name'] ?></td>
                                        <td class="text-center"><?= $row['unit_code'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['movement_type_name'] === 'MASUK'): ?>
                                                <span class="font-weight-bold text-success">
                                                    + <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="font-weight-bold text-danger">
                                                    - <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($row['movement_type_name'] === 'MASUK'): ?>
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

<script src="<?php echo base_url('assets/flatpickr/flatpickr.js'); ?>"></script>
<script src="<?php echo base_url('assets/flatpickr/flatpickr__.js'); ?>"></script>
<script src="<?php echo base_url('assets/select2/select2.min.js'); ?>"></script>

<script>
    const productData = <?= json_encode($products_list) ?>;
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // -------------------------
        // Elemen utama
        // -------------------------
        const filterForm = document.getElementById('filterForm');
        const productSelect = document.getElementById('product_id');
        const productContainer = document.getElementById('barangDetail');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // -------------------------
        // Inisialisasi Select2
        // PENTING: minimumResultsForSearch: 0 memaksa search box SELALU
        // muncul meskipun jumlah opsi sedikit (default Select2 baru
        // menampilkan search box otomatis jika opsi > 9 / lebar dropdown cukup).
        // -------------------------
        const $productSelect = $('#product_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Produk',
            allowClear: true,
            minimumResultsForSearch: 0,
            dropdownAutoWidth: true
        });

        // -------------------------
        // Helper: parse tanggal d/m/Y → Date
        // -------------------------
        function parseDate(str) {
            if (!str) return null;
            const parts = str.split('/');
            if (parts.length !== 3) return null;
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        // -------------------------
        // Render detail barang terpilih
        // -------------------------
        function renderProductDetail(productId) {
            if (!productId || productId === '') {
                productContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Silakan pilih barang terlebih dahulu untuk melihat Histori Barang.
                </div>`;
                return;
            }

            const product = productData.find(item => item.product_id == productId);
            if (!product) return;

            const isAktif = product.product_status == '0';
            const badgeClass = isAktif ? 'badge-success' : 'badge-danger';
            const badgeText = isAktif
                ? '<i class="fas fa-check-circle mr-1"></i> Aktif'
                : '<i class="fas fa-times-circle mr-1"></i> Tidak Aktif';

            productContainer.innerHTML = `
            <div class="card shadow-sm border-left-primary mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-9">
                            <h4 class="text-primary font-weight-bold mb-2">
                                <i class="fas fa-box mr-2"></i>
                                ${product.product_name}
                            </h4>
                            <div class="mt-2" style="font-size:15px;">
                                <span class="mr-4">
                                    <i class="fas fa-barcode text-secondary"></i>
                                    ${product.product_code}
                                </span>
                                <span class="mr-4">
                                    <i class="fas fa-ruler text-success"></i>
                                    ${product.unit_code}
                                </span>
                                <span class="mr-4">
                                    <i class="fas fa-tags text-info"></i>
                                    ${product.type_name || '-'}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 text-lg-right mt-3 mt-lg-0">
                            <span class="badge ${badgeClass} px-4 py-2">
                                ${badgeText}
                            </span>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        // -------------------------
        // Submit filter (dengan validasi tanggal)
        // -------------------------
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

        // -------------------------
        // Inisialisasi Flatpickr
        // -------------------------
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
            onChange: function () {
                // BUGFIX: cek terhadap '' (default kosong), bukan 'all'
                // (option value default select ini adalah "" bukan "all")
                if (productSelect.value === '') {
                    toastr.warning('Silakan pilih barang terlebih dahulu', 'Peringatan');
                    return;
                }
                submitFilter();
            }
        });

        // -------------------------
        // Event: pilih Barang
        // PENTING: gunakan event Select2 ($.on('change')), bukan
        // addEventListener native, karena Select2 mengelola perubahan
        // value melalui jQuery dan lebih konsisten dipicu lewat jQuery event.
        // -------------------------
        renderProductDetail(productSelect.value);

        $productSelect.on('change', function () {
            const value = $(this).val();
            renderProductDetail(value);

            // BUGFIX: cek terhadap '' bukan 'all'
            if (!value || value === '') {
                toastr.info('Silakan pilih barang untuk menampilkan histori barang', 'Informasi');
                return;
            }

            submitFilter();
        });

        // -------------------------
        // Validasi saat form di-submit manual
        // -------------------------
        filterForm.addEventListener('submit', function (e) {
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

        // -------------------------
        // Export PDF
        // -------------------------
        const exportPdfBtn = document.getElementById('exportPdfBtn');
        if (exportPdfBtn) {
            exportPdfBtn.addEventListener('click', function () {
                const params = new URLSearchParams(window.location.search);
                const exportUrl = '<?= site_url("laporan/export_history_barang_pdf") ?>?' + params.toString();
                window.open(exportUrl, '_blank');
            });
        }

    });
</script>