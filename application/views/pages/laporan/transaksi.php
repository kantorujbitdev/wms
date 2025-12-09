<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <div>
            <a href="<?= site_url('laporan/export_transaksi') . '?' . http_build_query($_GET) ?>"
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
            <form method="GET" action="<?= site_url('laporan/transaksi') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Tanggal Dari</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="<?= $filter_date_from ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Tanggal Sampai</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="<?= $filter_date_to ?>">
                        </div>
                    </div>

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
                    <?php endif; ?>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">Barang</label>
                            <select class="form-control select2" id="product_id" name="product_id">
                                <option value="">Semua Barang</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['product_id'] ?>"
                                        <?= ($filter_product_id == $product['product_id']) ? 'selected' : '' ?>>
                                        <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transaction_type">Jenis Transaksi</label>
                            <select class="form-control select2" id="transaction_type" name="transaction_type">
                                <option value="">Semua Jenis</option>
                                <option value="masuk" <?= ($filter_transaction_type == 'masuk') ? 'selected' : '' ?>>Barang
                                    Masuk</option>
                                <option value="keluar" <?= ($filter_transaction_type == 'keluar') ? 'selected' : '' ?>>
                                    Barang Keluar</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="<?= site_url('laporan/transaksi') ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Transaksi (Masuk & Keluar)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kode Transaksi</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Asal/Tujuan</th>
                            <th>Gudang</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($transactions as $item): ?>
                                <tr class="<?= ($item['type'] == 'MASUK') ? 'table-success' : 'table-warning' ?>">
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= ($item['type'] == 'MASUK') ?
                                            date('d-m-Y', strtotime($item['stockin_date'])) :
                                            date('d-m-Y', strtotime($item['stockout_date'])) ?>
                                    </td>
                                    <td>
                                        <?php if ($item['type'] == 'MASUK'): ?>
                                            <span class="badge badge-success">MASUK</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">KELUAR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= ($item['type'] == 'MASUK') ? $item['stockin_code'] : $item['stockout_code'] ?>
                                    </td>
                                    <td><?= $item['product_code'] ?></td>
                                    <td><?= $item['product_name'] ?></td>
                                    <td class="text-right"><?= number_format($item['qty'], 2) ?></td>
                                    <td><?= $item['unit_name'] ?></td>
                                    <td>
                                        <?php if ($item['type'] == 'MASUK'): ?>
                                            <?= $item['supplier_name'] ?? '-' ?>
                                        <?php else: ?>
                                            <?= $item['to_name'] ?? '-' ?>
                                            <?php if ($item['to_status'] == '1'): ?>
                                                <span class="badge badge-primary badge-sm">Customer</span>
                                            <?php else: ?>
                                                <span class="badge badge-info badge-sm">Gudang</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['warehouse_name'] ?></td>
                                    <td>
                                        <?= ($item['type'] == 'MASUK') ?
                                            ($item['stockin_note'] ?? '-') :
                                            ($item['stockout_note'] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right">
                                <strong>
                                    <?php
                                    $total_qty = 0;
                                    foreach ($transactions as $item) {
                                        $total_qty += $item['qty'];
                                    }
                                    echo number_format($total_qty, 2);
                                    ?>
                                </strong>
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Barang Masuk</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        $total_masuk = 0;
                                        $count_masuk = 0;
                                        foreach ($transactions as $item) {
                                            if ($item['type'] == 'MASUK') {
                                                $total_masuk += $item['qty'];
                                                $count_masuk++;
                                            }
                                        }
                                        echo number_format($total_masuk, 2);
                                        ?>
                                    </div>
                                    <div class="text-xs text-muted"><?= $count_masuk ?> transaksi</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-down fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Barang Keluar</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        $total_keluar = 0;
                                        $count_keluar = 0;
                                        foreach ($transactions as $item) {
                                            if ($item['type'] == 'KELUAR') {
                                                $total_keluar += $item['qty'];
                                                $count_keluar++;
                                            }
                                        }
                                        echo number_format($total_keluar, 2);
                                        ?>
                                    </div>
                                    <div class="text-xs text-muted"><?= $count_keluar ?> transaksi</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-up fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Saldo (Masuk - Keluar)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($total_masuk - $total_keluar, 2) ?>
                                    </div>
                                    <div class="text-xs text-muted">Netto Transaksi</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-balance-scale fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>