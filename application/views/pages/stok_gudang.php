<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stok di Gudang: <?php echo $warehouse['data']['name']; ?></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo isset($warehouse['data']['capacity']) ? $warehouse['data']['capacity'] : '0'; ?>
                        </h3>
                        <p>Kapasitas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo isset($warehouse['data']['used_capacity']) ? $warehouse['data']['used_capacity'] : '0'; ?>%
                        </h3>
                        <p>Penggunaan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo isset($warehouse['data']['total_items']) ? $warehouse['data']['total_items'] : '0'; ?>
                        </h3>
                        <p>Total Jenis Barang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo isset($warehouse['data']['low_stock_items']) ? $warehouse['data']['low_stock_items'] : '0'; ?>
                        </h3>
                        <p>Stok Menipis</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>

        <div class="table-responsive">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($stock_items['data']) && is_array($stock_items['data']) && count($stock_items['data']) > 0): ?>
                        <?php foreach ($stock_items['data'] as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo $item['code']; ?></td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['category_name']; ?></td>
                                <td><?php echo $item['unit']; ?></td>
                                <td><?php echo $item['stock'] . ' ' . $item['unit']; ?></td>
                                <td>
                                    <?php if ($item['stock'] <= $item['min_stock']): ?>
                                        <span class="badge bg-danger">Stok Menipis</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Tersedia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo site_url('barang/detail/' . $item['id']); ?>"
                                            class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('transaksi/masuk?item_id=' . $item['id'] . '&warehouse_id=' . $warehouse['data']['id']); ?>"
                                            class="btn btn-sm btn-success" title="Barang Masuk">
                                            <i class="fas fa-arrow-down"></i>
                                        </a>
                                        <a href="<?php echo site_url('transaksi/keluar?item_id=' . $item['id'] . '&warehouse_id=' . $warehouse['data']['id']); ?>"
                                            class="btn btn-sm btn-danger" title="Barang Keluar">
                                            <i class="fas fa-arrow-up"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data stok di gudang ini</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a href="<?php echo site_url('gudang'); ?>" class="btn btn-default">Kembali</a>
        <a href="<?php echo site_url('transaksi/masuk?warehouse_id=' . $warehouse['data']['id']); ?>"
            class="btn btn-success float-right">Barang Masuk</a>
    </div>
</div>
<!-- /.card -->