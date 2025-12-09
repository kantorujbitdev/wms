<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <div>
            <a href="<?= site_url('laporan/export_stok') . '?' . http_build_query($_GET) ?>"
                class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= site_url('laporan/stok') ?>">
                <div class="row">
                    <?php if ($user_role == 'superadmin'): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="warehouse_id">Gudang</label>
                                <select class="form-control select2" id="warehouse_id" name="warehouse_id">
                                    <option value="">Semua Gudang</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($filter_warehouse_id == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="warehouse_id" value="<?= $user_warehouse_id ?>">
                    <?php endif; ?>

                </div>

                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?= site_url('laporan/stok') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Stok Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Gudang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stoks)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($stoks as $stok): ?>
                                <?php
                                $min_stock = 10;
                                $current_stock = isset($stok['current_stock']) ? (float) $stok['current_stock'] : 0;

                                // Determine status
                                $status_class = '';
                                $status_text = '';
                                if ($current_stock <= 0) {
                                    $status_class = 'danger';
                                    $status_text = 'Kosong';
                                } elseif ($current_stock <= $min_stock) {
                                    $status_class = 'warning';
                                    $status_text = 'Menipis';
                                } else {
                                    $status_class = 'success';
                                    $status_text = 'Normal';
                                }
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $stok['product_code'] ?></td>
                                    <td><?= $stok['product_name'] ?></td>
                                    <td><?= $stok['type_name'] ?? '-' ?></td>
                                    <td><?= $stok['unit_code'] ?></td>
                                    <td
                                        class="text-right <?= ($current_stock <= $min_stock) ? 'text-danger font-weight-bold' : '' ?>">
                                        <?= number_format($current_stock, 2) ?>
                                        <?php if ($current_stock <= $min_stock): ?>
                                            <span class="badge bg-<?= $status_class ?>"><?= $status_text ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $stok['warehouse_name'] ?? '-' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $status_class ?>">
                                            <?= $status_text ?>
                                        </span>
                                    </td>
                                    <td><?= $stok['product_note'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data stok</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right">
                                <strong>
                                    <?php
                                    $total_stok = 0;
                                    foreach ($stoks as $stok) {
                                        $total_stok += isset($stok['current_stock']) ? (float) $stok['current_stock'] : 0;
                                    }
                                    echo number_format($total_stok, 2);
                                    ?>
                                </strong>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>