<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kartu Stok</h6>
        </div>

        <!-- Ganti bagian card body form dengan ini -->
        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/stok_card'); ?>" class="form-horizontal" id="filterForm">
                <div class="row align-items-end">
                    <?php if ($user_role == 'superadmin'): ?>
                            <!-- Warehouse Filter -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Nama Gudang <span class="text-danger">*</span></label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-control select2-gudang" required>
                                        <option value="">-- Pilih Gudang --</option>
                                        <?php foreach ($warehouses as $w): ?>
                                                <option value="<?= $w['warehouse_id'] ?>" <?= ($w['warehouse_id'] == $filter_warehouse_id) ? 'selected' : ''; ?>>
                                                    <?= $w['warehouse_name'] ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- <small class="text-muted">Pilih gudang terlebih dahulu untuk melihat produk</small> -->
                                </div>
                            </div>
                    <?php else: ?>
                            <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?= $user_warehouse_id ?>">
                    <?php endif; ?>

                    <!-- Product Filter -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <select name="stock_id" id="stock_id" class="form-control select2-produk"
                                <?= ($user_role == 'superadmin' && !$filter_warehouse_id) ? 'disabled' : '' ?>>
                                <option value="">-- Pilih Produk --</option>
                                <?php if ($user_role == 'superadmin'): ?>
                                        <?php if ($filter_warehouse_id): ?>
                                                <?php
                                                // Filter produk berdasarkan warehouse yang dipilih
                                                $filtered_products = array_filter($products, function ($product) use ($filter_warehouse_id) {
                                                    return $product['warehouse_id'] == $filter_warehouse_id;
                                                });
                                                ?>
                                                <?php foreach ($filtered_products as $product): ?>
                                                        <option value="<?= $product['stock_id'] ?>"
                                                            data-warehouse="<?= $product['warehouse_id'] ?>"
                                                            <?= ($product['stock_id'] == $filter_stock_id) ? 'selected' : ''; ?>>
                                                            <!-- <?= $product['product_name'] . ' || ' . $product['product_code'] . ' (' . $product['bos_code'] . ')' ?> -->
                                                            <?= $product['product_code'] . ' - ' . $product['product_name'] . ' (Satuan:' . $product['unit_code'] . ')' ?>
                                                        </option>
                                                <?php endforeach; ?>
                                        <?php endif; ?>
                                <?php else: ?>
                                        <?php foreach ($products as $product): ?>
                                                <option value="<?= $product['stock_id'] ?>"
                                                    data-warehouse="<?= $product['warehouse_id'] ?>"
                                                    <?= ($product['stock_id'] == $filter_stock_id) ? 'selected' : ''; ?>>
                                                    <!-- <?= $product['product_name'] . ' || ' . $product['product_code'] . ' (' . $product['bos_code'] . ')' ?> -->
                                                    <?= $product['product_code'] . ' - ' . $product['product_name'] . ' (Satuan:' . $product['unit_code'] . ')' ?>
                                                </option>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="date_start">Tanggal Mulai</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr" id="date_start" name="date_start"
                                    placeholder="dd/mm/yyyy"
                                    value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="date_end">Tanggal Akhir</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr" id="date_end" name="date_end"
                                    placeholder="dd/mm/yyyy"
                                    value="<?= isset($filter_date_end) ? date('d/m/Y', strtotime($filter_date_end)) : date('d/m/Y') ?>"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right mt-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?= site_url('laporan/stok_card'); ?>" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Card Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Kartu Stok</h6>
                <small class="text-muted">
                    Periode: <?= date('d/m/Y', strtotime($filter_date_start)) ?> -
                    <?= date('d/m/Y', strtotime($filter_date_end)) ?>
                </small>
                <?php if (!empty($stock_cards)): ?>
                        <a href="<?= site_url('laporan/export_stok_card?' . http_build_query([
                            'warehouse_id' => $filter_warehouse_id,
                            'stock_id' => $filter_stock_id,
                            'date_start' => $filter_date_start,
                            'date_end' => $filter_date_end
                        ])); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">

            <?php if (empty($stock_cards)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Tidak ada data Pengiriman ke Pengguna.
                        <?= date('d/m/Y', strtotime($filter_date_start)) ?> -
                        <?= date('d/m/Y', strtotime($filter_date_end)) ?>.
                    </div>
            <?php else: ?>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                            <thead class="text-center align-middle">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. Referensi</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stok<br>Awal</th>
                                    <th>Qty</th>
                                    <th>Stok<br>Akhir</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($stock_cards as $card): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= $card['movement_date']; ?></td>
                                            <td><?= $card['movement_refno']; ?></td>
                                            <td><?= $card['product_code']; ?></td>
                                            <td><?= $card['product_name']; ?></td>
                                            <td class="text-center">
                                                <strong>
                                                    <?= $card['begin_stock']; ?>
                                                </strong>
                                            </td>

                                            <td class="text-center">
                                                <?php if ($card['movement_type'] == '1'): ?>
                                                        <strong><span class="text-success">
                                                                +<?= $card['qty']; ?>
                                                            </span></strong>
                                                <?php else: ?>
                                                        <strong><span class="text-danger">
                                                                -<?= $card['qty']; ?>
                                                            </span></strong>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <strong><?= $card['last_stock']; ?></strong>
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
                                        <strong><?= count($stock_cards); ?></strong>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Tambahkan CSS dan JS Flatpickr di head -->
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/flatpickr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flatpickr/material_blue.css'); ?>">
<script src="<?php echo base_url('assets/flatpickr/flatpickr.js'); ?>"></script>
<script src="<?php echo base_url('assets/flatpickr/flatpickr__.js'); ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userRole = '<?= $user_role ?>';
        const isSuperAdmin = userRole === 'superadmin';

        // Data produk dari server (disimpan dalam JavaScript)
        const allProducts = <?= json_encode($products) ?>;

        // Inisialisasi Select2 untuk Gudang
        if ($('.select2-gudang').length) {
            $('.select2-gudang').select2({
                placeholder: '-- Pilih Gudang --',
                width: '100%'
            });
        }

        // Inisialisasi Select2 untuk Produk
        function initProductSelect2(disabled = false) {
            if ($('.select2-produk').length) {
                $('.select2-produk').select2({
                    placeholder: '-- Pilih Produk --',
                    width: '100%',
                    minimumInputLength: 0,
                    disabled: disabled,
                    language: {
                        noResults: function () {
                            return "Produk tidak ditemukan";
                        },
                        searching: function () {
                            return "Mencari...";
                        }
                    }
                });
            }
        }

        // Fungsi untuk memfilter produk berdasarkan warehouse
        function filterProductsByWarehouse(warehouseId) {
            const productSelect = $('#stock_id');
            const currentValue = productSelect.val();

            // Hapus semua option kecuali yang pertama
            productSelect.find('option:not(:first)').remove();

            if (!warehouseId) {
                // Jika tidak ada warehouse yang dipilih, disable produk select
                productSelect.prop('disabled', true).trigger('change');
                return;
            }

            // Filter produk berdasarkan warehouse_id
            const filteredProducts = allProducts.filter(product =>
                product.warehouse_id == warehouseId
            );

            // Tambahkan option produk yang sudah difilter
            filteredProducts.forEach(product => {
                const option = new Option(
                    product.product_code + ' || ' + product.product_name + ' (Satuan: ' + (product.unit_code || '') + ')',
                    product.stock_id,
                    false,
                    product.stock_id == currentValue
                );
                $(option).attr('data-warehouse', product.warehouse_id);
                productSelect.append(option);
            });

            // Enable produk select
            productSelect.prop('disabled', false).trigger('change');
        }

        // Event handler untuk perubahan warehouse
        $('#warehouse_id').on('change', function () {
            const warehouseId = $(this).val();

            // Reset produk yang dipilih
            $('#stock_id').val('').trigger('change');

            // Filter produk berdasarkan warehouse yang dipilih
            filterProductsByWarehouse(warehouseId);
        });

        // Validasi form sebelum submit
        $('#filterForm').on('submit', function (e) {
            if (isSuperAdmin) {
                const warehouseId = $('#warehouse_id').val();

                if (!warehouseId) {
                    e.preventDefault();
                    alert('Silahkan pilih gudang terlebih dahulu');
                    return false;
                }
            }

            // Optional: Validasi tanggal
            const dateStart = $('input[name="date_start"]').val();
            const dateEnd = $('input[name="date_end"]').val();

            if (dateStart && dateEnd) {
                // Konversi dd/mm/yyyy ke Date object untuk perbandingan
                const startParts = dateStart.split('/');
                const endParts = dateEnd.split('/');

                const startDate = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                const endDate = new Date(endParts[2], endParts[1] - 1, endParts[0]);

                if (startDate > endDate) {
                    e.preventDefault();
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    return false;
                }
            }
        });

        // Inisialisasi Flatpickr untuk tanggal
        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1, // Senin sebagai hari pertama
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                }
            },
            onChange: function (selectedDates, dateStr, instance) {
                // Validasi tanggal
                const startDate = document.getElementById('date_start').value;
                const endDate = document.getElementById('date_end').value;

                if (startDate && endDate) {
                    const startParts = startDate.split('/');
                    const endParts = endDate.split('/');

                    const start = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                    const end = new Date(endParts[2], endParts[1] - 1, endParts[0]);

                    if (start > end) {
                        if (instance.element.id === 'date_start') {
                            alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                            instance.clear();
                        } else {
                            alert('Tanggal akhir tidak boleh lebih kecil dari tanggal awal');
                            instance.clear();
                        }
                    }
                }
            }
        });

        // Inisialisasi awal
        if (isSuperAdmin) {
            const initialWarehouse = $('#warehouse_id').val();

            if (initialWarehouse) {
                // Jika ada warehouse yang sudah dipilih, filter produk
                filterProductsByWarehouse(initialWarehouse);
                initProductSelect2(false);
            } else {
                // Jika belum ada warehouse yang dipilih, disable produk select
                initProductSelect2(true);
            }
        } else {
            // Untuk admin, langsung filter berdasarkan warehouse mereka
            const adminWarehouse = $('#warehouse_id').val();
            filterProductsByWarehouse(adminWarehouse);
            initProductSelect2(false);
        }
    });
</script>