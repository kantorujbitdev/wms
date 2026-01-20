<div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stok Gudang - <?php echo $warehouse['name']; ?></h1>
        <a href="<?php echo site_url('gudang'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> <?= $wording['back']; ?>
        </a>
    </div> -->

    <!-- Warehouse Info -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Gudang</h6>
            <a href="<?php echo site_url('gudang'); ?>"
                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> <?php echo $warehouse['name']; ?></p>
                    <p><strong>Kode:</strong> <?php echo $warehouse['code']; ?></p>
                    <p><strong>Alamat:</strong> <?php echo $warehouse['address']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Kapasitas:</strong> <?php echo $warehouse['capacity']; ?></p>
                    <p><strong>Manager:</strong> <?php echo $warehouse['manager']; ?></p>
                    <p><strong>Telepon:</strong> <?php echo $warehouse['phone']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Items -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Stok</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stock_items)): ?>
                            <?php foreach ($stock_items as $item): ?>
                                <tr>
                                    <td><?php echo $item['code']; ?></td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td><?php echo $item['category']; ?></td>
                                    <td><?php echo $item['unit']; ?></td>
                                    <td><?php echo $item['current_stock']; ?></td>
                                    <td><?php echo $item['min_stock']; ?></td>
                                    <td>
                                        <?php if ($item['current_stock'] <= $item['min_stock']): ?>
                                            <span class="badge badge-danger">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No stock items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>