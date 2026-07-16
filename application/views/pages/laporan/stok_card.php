<!-- C:\xampp\htdocs\wms\application\views\pages\laporan\kartu_stok.php -->

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css') ?>">

<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/select2/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet" />
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
            <h6 class="m-0 font-weight-bold text-primary">Filter Kartu Stok</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/stok_card') ?>" id="filterForm">
                <div class="row">
                    <!-- Kolom 1: Gudang -->
                    <div class="col-md-3 mb-3">
                        <label for="warehouse_id" class="form-label">
                            Gudang Asal
                            <?php if ($user_role == 'superadmin'): ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>

                        <?php if ($user_role == 'superadmin'): ?>
                            <select name="warehouse_id" id="warehouse_id" class="form-control select2-gudang">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($warehouses as $w): ?>
                                    <option value="<?= $w['warehouse_id'] ?>" <?= ($w['warehouse_id'] == $filter_warehouse_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($w['warehouse_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?= $user_warehouse_id ?>">
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($user_warehouse_name ?? '') ?>" disabled>
                        <?php endif; ?>
                    </div>

                    <!-- Kolom 2: Produk -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nama Produk</label>
                        <?php
                        $render_products = $products;
                        if ($user_role == 'superadmin' && $filter_warehouse_id) {
                            $render_products = array_filter($products, function ($p) use ($filter_warehouse_id) {
                                return $p['warehouse_id'] == $filter_warehouse_id;
                            });
                        } elseif ($user_role == 'superadmin' && !$filter_warehouse_id) {
                            $render_products = [];
                        }
                        ?>
                        <select name="stock_id" id="stock_id" class="form-control select2-produk"
                            <?= ($user_role == 'superadmin' && !$filter_warehouse_id) ? 'disabled' : '' ?>>
                            <option value="">-- Semua Produk --</option>
                            <?php foreach ($render_products as $product): ?>
                                <option value="<?= $product['stock_id'] ?>" data-warehouse="<?= $product['warehouse_id'] ?>"
                                    <?= ($product['stock_id'] == $filter_stock_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'] . ' (Satuan: ' . $product['unit_code'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Kolom 3: Tanggal Mulai -->
                    <div class="col-md-2 mb-3">
                        <label for="date_start" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control flatpickr" id="date_start" name="date_start"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Kolom 4: Tanggal Akhir -->
                    <div class="col-md-2 mb-3">
                        <label for="date_end" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control flatpickr" id="date_end" name="date_end"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_date_end) ? date('d/m/Y', strtotime($filter_date_end)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Kolom 5: Reset -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('laporan/stok_card') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Kartu Stok -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Kartu Stok</h6>
                <small class="text-muted">
                    Periode:
                    <?= date('d/m/Y', strtotime($filter_date_start)) ?>
                    &ndash;
                    <?= date('d/m/Y', strtotime($filter_date_end)) ?>
                </small>
                <?php if (!empty($stock_cards)): ?>
                    <a href="<?= site_url('laporan/export_stok_card?' . http_build_query([
                        'warehouse_id' => $filter_warehouse_id,
                        'stock_id' => $filter_stock_id,
                        'date_start' => $filter_date_start,
                        'date_end' => $filter_date_end,
                    ])) ?>" class="btn btn-success btn-sm mt-2 mt-md-0">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($stock_cards)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <?php if ($user_role == 'superadmin' && !$filter_warehouse_id): ?>
                        Silahkan pilih gudang untuk menampilkan data Kartu Stok.
                    <?php else: ?>
                        Tidak ada data Kartu Stok untuk periode
                        <?= date('d/m/Y', strtotime($filter_date_start)) ?>
                        &ndash;
                        <?= date('d/m/Y', strtotime($filter_date_end)) ?>.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light text-center align-middle">
                            <tr>
                                <th width="40">No</th>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th width="80">Stok Awal</th>
                                <th width="80">Qty</th>
                                <th width="80">Stok Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($stock_cards as $card): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($card['movement_date']) ?></td>
                                    <td><?= htmlspecialchars($card['movement_refno']) ?></td>
                                    <td><?= htmlspecialchars($card['product_code']) ?></td>
                                    <td><?= htmlspecialchars($card['product_name']) ?></td>
                                    <td class="text-center">
                                        <strong><?= $card['begin_stock'] ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($card['movement_type'] == '1'): ?>
                                            <strong class="text-success">+<?= $card['qty'] ?></strong>
                                        <?php else: ?>
                                            <strong class="text-danger">-<?= $card['qty'] ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= $card['last_stock'] ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    <strong>Total Transaksi:</strong>
                                </td>
                                <td colspan="3" class="text-center">
                                    <strong><?= count($stock_cards) ?></strong>
                                </td>
                            </tr>
                        </tfoot>
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
<script src="<?= base_url('assets/flatpickr/flatpickr.js') ?>"></script>
<!-- FIX: path JS Select2 disesuaikan dengan path CSS () -->
<!-- flatpickr__.js dihapus — locale sudah di-set manual di config -->
<script src="<?= base_url('assets/select2/select2.min.js') ?>"></script>

<script>
    $(document).ready(function () {

        const isSuperAdmin = '<?= $user_role ?>' === 'superadmin';
        const allProducts = <?= json_encode(array_values($products)) ?>;

        // =========================================================
        // Helper
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

        function submitFilter() {
            const start = parseDate($('#date_start').val());
            const end = parseDate($('#date_end').val());

            if (start && end && start > end) {
                toastr.error(
                    'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid'
                );
                return;
            }

            showLoading();
            document.getElementById('filterForm').submit();
        }

        // =========================================================
        // Select2 config terpusat
        // =========================================================
        const select2Config = {
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        };

        if ($('#warehouse_id').is('select')) {
            $('#warehouse_id').select2({
                ...select2Config,
                placeholder: '-- Pilih Gudang --'
            });
        }

        $('#stock_id').select2({
            ...select2Config,
            placeholder: '-- Semua Produk --'
        });

        // =========================================================
        // filterProductsByWarehouse
        // Dipanggil saat gudang berubah dari user (bukan saat init)
        // =========================================================
        function filterProductsByWarehouse(warehouseId, submitAfter) {
            const $produk = $('#stock_id');

            if ($produk.hasClass('select2-hidden-accessible')) {
                $produk.select2('destroy');
            }

            $produk.find('option:not(:first)').remove();

            if (!warehouseId) {
                $produk.prop('disabled', true);
            } else {
                const filtered = allProducts.filter(p => p.warehouse_id == warehouseId);
                filtered.forEach(function (p) {
                    const label = p.product_code + ' - ' + p.product_name
                        + ' (Satuan: ' + (p.unit_code || '') + ')';
                    $produk.append(new Option(label, p.stock_id, false, false));
                });
                $produk.prop('disabled', false);
            }

            $produk.select2({
                ...select2Config,
                placeholder: '-- Semua Produk --'
            });

            if (submitAfter) submitFilter();
        }

        // =========================================================
        // Event: gudang berubah
        // FIX: saat gudang di-clear (value=""), tetap submit
        // agar controller reset ke state "pilih gudang dulu"
        // =========================================================
        if (isSuperAdmin) {
            $('#warehouse_id').on('change', function () {
                const warehouseId = $(this).val() || '';
                filterProductsByWarehouse(warehouseId, true);
            });
        }

        // =========================================================
        // Event: produk berubah
        // =========================================================
        $('#stock_id').on('change', function () {
            if (isSuperAdmin && !$('#warehouse_id').val()) {
                toastr.warning('Pilih gudang terlebih dahulu', 'Peringatan');
                return;
            }
            submitFilter();
        });

        // =========================================================
        // Flatpickr
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
                // GUARD: instance.clear() memicu onChange lagi dengan selectedDates = [].
                // Tanpa guard ini terjadi infinite loop → halaman hang.
                // Cukup return langsung jika tidak ada tanggal yang dipilih.
                if (selectedDates.length === 0) return;

                if (isSuperAdmin && !$('#warehouse_id').val()) {
                    toastr.warning('Pilih gudang terlebih dahulu', 'Peringatan');
                    return;
                }

                const start = parseDate($('#date_start').val());
                const end = parseDate($('#date_end').val());

                if (start && end && start > end) {
                    toastr.error(
                        instance.element.id === 'date_start'
                            ? 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                            : 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal',
                        'Tanggal Tidak Valid'
                    );
                    instance.clear(); // aman karena guard di atas akan menghentikan re-entry
                    return;
                }

                if ($('#date_start').val() && $('#date_end').val()) {
                    submitFilter();
                }
            }
        };

        flatpickr('#date_start', flatpickrConfig);
        flatpickr('#date_end', flatpickrConfig);

        // =========================================================
        // Safety net: validasi saat form submit manual
        // =========================================================
        $('#filterForm').on('submit', function (e) {
            if (isSuperAdmin && !$('#warehouse_id').val()) {
                e.preventDefault();
                toastr.warning('Silahkan pilih gudang terlebih dahulu', 'Peringatan');
                return false;
            }

            const start = parseDate($('#date_start').val());
            const end = parseDate($('#date_end').val());

            if (start && end && start > end) {
                e.preventDefault();
                toastr.error(
                    'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
                    'Tanggal Tidak Valid'
                );
                return false;
            }
        });

        // =========================================================
        // Init awal: pastikan state disabled produk sesuai kondisi.
        // PHP sudah render opsi yang benar — JS tidak perlu re-render.
        // =========================================================
        if (isSuperAdmin && !$('#warehouse_id').val()) {
            $('#stock_id').prop('disabled', true).select2('destroy').select2({
                ...select2Config,
                placeholder: '-- Semua Produk --'
            });
        }

    });
</script>