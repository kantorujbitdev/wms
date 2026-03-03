<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Penerimaan / Pengiriman</h6>
                <?php if (isset($is_filtered) && $is_filtered): ?>
                    <a href="<?= site_url('laporan/export_keluar_masuk') . '?' . http_build_query($_GET) ?>"
                        class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= site_url('laporan/keluar_masuk') ?>" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transaksi_type">Jenis Transaksi</label>
                            <select class="form-control select2" id="transaksi_type" name="transaksi_type">
                                <option value="">Semua</option>
                                <option value="in" <?= (isset($filter_transaksi_type) && $filter_transaksi_type == 'in') ? 'selected' : '' ?>>Penerimaan</option>
                                <option value="out" <?= (isset($filter_transaksi_type) && $filter_transaksi_type == 'out') ? 'selected' : '' ?>>Pengiriman</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="source_type">Asal / Tujuan</label>
                            <select class="form-control select2" id="source_type" name="source_type">
                                <option value="">Semua</option>
                                <option value="warehouse" <?= (isset($filter_source_type) && $filter_source_type == 'warehouse') ? 'selected' : '' ?>>Antar Gudang</option>
                                <option value="supplier" <?= (isset($filter_source_type) && $filter_source_type == 'supplier') ? 'selected' : '' ?>>Supplier</option>
                                <option value="customer" <?= (isset($filter_source_type) && $filter_source_type == 'customer') ? 'selected' : '' ?>>Pengguna</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="<?= isset($filter_date_from) ? $filter_date_from : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="<?= isset($filter_date_to) ? $filter_date_to : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="filterBtn">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="<?= site_url('laporan/keluar_masuk') ?>" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Screen -->
    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; text-align: center; padding-top: 20%;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <h5 class="mt-2">Memuat data...</h5>
    </div>

    <?php if ($filter_transaksi_type == 'in' || empty($filter_transaksi_type)): ?>
        <!-- Data Barang Masuk -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Barang Masuk (Penerimaan)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableIn" width="100%" cellspacing="0">
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
                            <?php if (isset($is_filtered) && $is_filtered): ?>
                                <?php if (isset($in_report) && !empty($in_report)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($in_report as $item): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= isset($item['stockin_date']) ? date('d-m-Y', strtotime($item['stockin_date'])) : '' ?></td>
                                            <td><?= $item['stockin_code'] ?? '' ?></td>
                                            <td><?= $item['product_code'] ?? '' ?></td>
                                            <td><?= $item['product_name'] ?? '' ?></td>
                                            <td class="text-right">
                                                <?= isset($item['qty']) ? number_format($item['qty'], 2) : '0.00' ?>
                                            </td>
                                            <td><?= $item['unit_name'] ?? '' ?></td>
                                            <td><?= $item['supplier_name'] ?? '-' ?></td>
                                            <td><?= $item['warehouse_name'] ?? '' ?></td>
                                            <td><?= $item['stockin_note'] ?? '-' ?></td>
                                            <td><?= $item['user_name'] ?? '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data barang masuk dengan filter yang dipilih
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        <i class="fas fa-filter fa-2x mb-2"></i><br>
                                        Silakan pilih filter terlebih dahulu untuk menampilkan data
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if (isset($is_filtered) && $is_filtered && !empty($in_report)): ?>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right">
                                        <strong>
                                            <?php
                                            $total_qty = 0;
                                            if (isset($in_report) && !empty($in_report)) {
                                                foreach ($in_report as $item) {
                                                    $total_qty += isset($item['qty']) ? (float) $item['qty'] : 0;
                                                }
                                            }
                                            echo number_format($total_qty, 2);
                                            ?>
                                        </strong>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($filter_transaksi_type == 'out' || empty($filter_transaksi_type)): ?>
        <!-- Data Barang Keluar -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Barang Keluar (Pengiriman)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableOut" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Customer</th>
                                <th>Gudang</th>
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($is_filtered) && $is_filtered): ?>
                                <?php if (isset($out_report) && !empty($out_report)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($out_report as $item): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= isset($item['stockout_date']) ? date('d-m-Y', strtotime($item['stockout_date'])) : '' ?></td>
                                            <td><?= $item['stockout_code'] ?? '' ?></td>
                                            <td><?= $item['product_code'] ?? '' ?></td>
                                            <td><?= $item['product_name'] ?? '' ?></td>
                                            <td class="text-right">
                                                <?= isset($item['qty']) ? number_format($item['qty'], 2) : '0.00' ?>
                                            </td>
                                            <td><?= $item['unit_name'] ?? '' ?></td>
                                            <td><?= $item['customer_name'] ?? '-' ?></td>
                                            <td><?= $item['warehouse_name'] ?? '' ?></td>
                                            <td><?= $item['stockout_note'] ?? '-' ?></td>
                                            <td><?= $item['user_name'] ?? '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data barang keluar dengan filter yang dipilih
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        <i class="fas fa-filter fa-2x mb-2"></i><br>
                                        Silakan pilih filter terlebih dahulu untuk menampilkan data
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if (isset($is_filtered) && $is_filtered && !empty($out_report)): ?>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right">
                                        <strong>
                                            <?php
                                            $total_qty = 0;
                                            if (isset($out_report) && !empty($out_report)) {
                                                foreach ($out_report as $item) {
                                                    $total_qty += isset($item['qty']) ? (float) $item['qty'] : 0;
                                                }
                                            }
                                            echo number_format($total_qty, 2);
                                            ?>
                                        </strong>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        // Set date max untuk date_to
        $('#date_from').on('change', function () {
            $('#date_to').attr('min', $(this).val());
        });

        // Set date min untuk date_from
        $('#date_to').on('change', function () {
            $('#date_from').attr('max', $(this).val());
        });

        // Loading screen saat submit form
        $('#filterForm').on('submit', function () {
            $('#loadingOverlay').fadeIn();
        });

        // Sembunyikan loading screen setelah halaman selesai dimuat
        $(window).on('load', function () {
            $('#loadingOverlay').fadeOut();
        });
    });
</script>

<style>
    .gap-2 {
        gap: 0.5rem;
    }

    .btn-block {
        width: auto;
        display: inline-block;
    }

    .text-muted i {
        color: #6c757d;
        opacity: 0.5;
    }
</style>