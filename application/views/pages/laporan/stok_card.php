<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Stock Card</h6>
        </div>

        <div class="card-body">
            <form method="get"
                  action="<?= site_url('laporan/stok_card'); ?>"
                  class="form-horizontal">

                <div class="row">

                    <!-- Warehouse Filter -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Gudang</label>
                            <select name="warehouse_id" class="form-control select2">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id']; ?>"
                                        <?= (isset($filter_warehouse_id) && $filter_warehouse_id == $warehouse['warehouse_id']) ? 'selected' : ''; ?>>
                                        <?= $warehouse['warehouse_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Product Filter -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Barang</label>
                            <select name="stock_id" class="form-control select2">
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['product_id']; ?>"
                                        <?= ($product['product_id'] == $filter_stock_id) ? 'selected' : ''; ?>>
                                        <?= $product['product_code'] . ' - ' . $product['product_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Date Start -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date"
                                   name="date_start"
                                   class="form-control"
                                   value="<?= $filter_date_start ? $filter_date_start : date('Y-m-01'); ?>">
                        </div>
                    </div>

                    <!-- Date End -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date"
                                   name="date_end"
                                   class="form-control"
                                   value="<?= $filter_date_end ? $filter_date_end : date('Y-m-d'); ?>">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 text-right mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="<?= site_url('laporan/stok_card'); ?>"
                           class="btn btn-secondary">
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
                        'stock_id'     => $filter_stock_id,
                        'date_start'   => $filter_date_start,
                        'date_end'     => $filter_date_end
                    ])); ?>"
                       class="btn btn-success btn-sm">
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
                    <table class="table table-bordered"
                           id="dataTable"
                           width="100%"
                           cellspacing="0">

                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <th>Tipe</th>
                                <th>Dari</th>
                                <th>Ke</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Stok Awal</th>
                                <th>Stok Akhir</th>
                                <th>User</th>
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

                                    <td class="text-center">
                                        <?php if ($card['movement_type'] == '1'): ?>
                                            <span class="badge bg-success">
                                                <?= $card['movement_type_name']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <?= $card['movement_type_name']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $card['warehouse_name']; ?></td>
                                    <td><?= $card['warehouse_status_name']; ?>
                                    </td> <td><?= $card['product_code']; ?></td>
                                    <td><?= $card['product_name']; ?></td>

                                    <td class="text-center">
                                        <?php if ($card['movement_type'] == '1'): ?>
                                            <span class="text-success">
                                                +<?= $card['qty']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-danger">
                                                -<?= $card['qty']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= $card['begin_stock']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $card['last_stock']; ?>
                                    </td>

                                    <td><?= $card['user_name']; ?></td>
                                    <td><?= $card['movement_note']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-right">
                                    <strong>Total Transaksi:</strong>
                                </td>
                                <td colspan="5" class="text-center">
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
