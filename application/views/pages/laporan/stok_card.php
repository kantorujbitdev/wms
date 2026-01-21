<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kartu Stok</h6>
        </div>

        <div class="card-body">
            <form method="get" action="<?= site_url('laporan/stok_card'); ?>" class="form-horizontal">

                <div class="row">

                    <!-- Warehouse Filter -->
                    <div class="col-md-3">
                        <div class="form-group">

                            <label class="form-label">Nama Gudang</label>
                            <select name="warehouse_id" class="form-control select2-gudang">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($warehouses as $w): ?>
                                    <option value="<?= $w['warehouse_id'] ?>" <?= ($w['warehouse_id'] == $filter_warehouse_id) ? 'selected' : ''; ?>>
                                        <?= $w['warehouse_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <!-- Product Filter -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <select name="stock_id" class="form-control select2-produk">
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['product_id'] ?>"
                                        <?= ($product['product_id'] == $filter_stock_id) ? 'selected' : ''; ?>>
                                        <?= $product['product_name'] . ' || ' . $product['product_code'] . ' (' . $product['bos_code'] . ')' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Date Start -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="date_start" class="form-control"
                                value="<?= $filter_date_start ? $filter_date_start : date('Y-m-01'); ?>">
                        </div>
                    </div>

                    <!-- Date End -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="date_end" class="form-control"
                                value="<?= $filter_date_end ? $filter_date_end : date('Y-m-d'); ?>">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 text-right mt-4">
                        <button type="submit" class="btn btn-primary">
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
                <h6 class="m-0 font-weight-bold text-primary">Data Stock Card</h6>

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
                    Tidak ada data stock card untuk periode yang dipilih.
                </div>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <!-- <th>Tipe</th> -->
                                <th>Dari</th>
                                <th>Ke</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Stok<br>Awal</th>
                                <th>Stok<br>Akhir</th>
                                <!-- <th>User</th> -->
                                <th>Ket</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($stock_cards as $card): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $card['movement_date']; ?></td>
                                    <td><?= $card['movement_refno']; ?></td>

                                    <!-- <td class="text-center">
                                        <?php if ($card['movement_type'] == '1'): ?>
                                            <span class="badge bg-success">
                                                <?= $card['movement_type_name']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <?= $card['movement_type_name']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td> -->

                                    <td><?= $card['warehouse_name']; ?></td>
                                    <td><?= $card['warehouse_status_name']; ?>
                                    </td>
                                    <td><?= $card['product_code']; ?></td>
                                    <td><?= $card['product_name']; ?></td>

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
                                        <?= $card['begin_stock']; ?>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= $card['last_stock']; ?></strong>
                                    </td>

                                    <!-- <td><?= $card['user_name']; ?></td> -->
                                    <td><?= $card['movement_note']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-right">
                                    <strong>Total Transaksi:</strong>
                                </td>
                                <td colspan="4" class="text-center">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Select2 untuk Gudang
        $('.select2-gudang').select2({
            placeholder: '-- Pilih Gudang --',
            // allowClear: true,
            width: '100%'
        });
        // Inisialisasi Select2 untuk Produk dengan pencarian
        $('.select2-produk').select2({
            placeholder: '-- Pilih Produk --',
            // allowClear: true,
            width: '100%',
            minimumInputLength: 0, // Minimal karakter untuk mulai mencari
            language: {
                noResults: function () {
                    return "Produk tidak ditemukan";
                },
                searching: function () {
                    return "Mencari...";
                }
            }
        });
    });
</script>