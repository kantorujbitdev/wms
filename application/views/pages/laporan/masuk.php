<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <div>
            <a href="<?= site_url('laporan/export_masuk') . '?' . http_build_query($_GET) ?>"
                class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="<?= site_url('laporan/masuk') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-sync"></i> Reset
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= site_url('laporan/masuk') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Tanggal Dari</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="<?= isset($filter_date_from) ? $filter_date_from : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Tanggal Sampai</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="<?= isset($filter_date_to) ? $filter_date_to : '' ?>">
                        </div>
                    </div>

                    <?php if (isset($user_role) && $user_role == 'superadmin'): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="warehouse_id">Gudang</label>
                                <select class="form-control select2" id="warehouse_id" name="warehouse_id">
                                    <option value="">Semua Gudang</option>
                                    <?php if (isset($warehouses)): ?>
                                        <?php foreach ($warehouses as $warehouse): ?>
                                            <option value="<?= $warehouse['warehouse_id'] ?>"
                                                <?= (isset($filter_warehouse_id) && $filter_warehouse_id == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                                <?= $warehouse['warehouse_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">Barang</label>
                            <select class="form-control select2" id="product_id" name="product_id">
                                <option value="">Semua Barang</option>
                                <?php if (isset($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['product_id'] ?>"
                                            <?= (isset($filter_product_id) && $filter_product_id == $product['product_id']) ? 'selected' : '' ?>>
                                            <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control select2" id="supplier_id" name="supplier_id">
                                <option value="">Semua Supplier</option>
                                <?php if (isset($suppliers)): ?>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['supplier_id'] ?? $supplier['id'] ?>" 
                                            <?= (isset($filter_supplier_id) && $filter_supplier_id == ($supplier['supplier_id'] ?? $supplier['id'])) ? 'selected' : '' ?>>
                                            <?= $supplier['supplier_name'] ?? $supplier['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
            <h6 class="m-0 font-weight-bold text-primary">Data Barang Masuk (Penerimaan)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Transaksi</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Supplier</th>
                            <th>Gudang</th>
                            <th>Keterangan</th>
                            <th>Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($in_report) && !empty($in_report)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($in_report as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= isset($item['stockin_date']) ? date('d-m-Y', strtotime($item['stockin_date'])) : '' ?></td>
                                    <td><?= $item['stockin_code'] ?? '' ?></td>
                                    <td><?= $item['product_code'] ?? '' ?></td>
                                    <td><?= $item['product_name'] ?? '' ?></td>
                                    <td class="text-right"><?= isset($item['qty']) ? number_format($item['qty'], 2) : '0.00' ?></td>
                                    <td><?= $item['unit_name'] ?? '' ?></td>
                                    <td><?= $item['supplier_name'] ?? '-' ?></td>
                                    <td><?= $item['warehouse_name'] ?? '' ?></td>
                                    <td><?= $item['stockin_note'] ?? '-' ?></td>
                                    <td><?= $item['user_name'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data barang masuk</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right">
                                <strong>
                                    <?php
                                    $total_qty = 0;
                                    if (isset($in_report) && !empty($in_report)) {
                                        foreach ($in_report as $item) {
                                            $total_qty += isset($item['qty']) ? (float)$item['qty'] : 0;
                                        }
                                    }
                                    echo number_format($total_qty, 2);
                                    ?>
                                </strong>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Inisialisasi Select2 jika ada
$(document).ready(function() {
    // Set date max untuk date_to
    $('#date_from').on('change', function() {
        $('#date_to').attr('min', $(this).val());
    });
    
    // Set date min untuk date_from
    $('#date_to').on('change', function() {
        $('#date_from').attr('max', $(this).val());
    });
});
</script>