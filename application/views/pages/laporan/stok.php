<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Stok</h1>
        <a href="<?php echo site_url('laporan/export_stok?' . $_SERVER['QUERY_STRING']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Export CSV
        </a>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('laporan/stok', ['method' => 'GET']); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">Semua</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category; ?>" 
                                            <?php echo $this->input->get('category') == $category ? 'selected' : ''; ?>>
                                            <?php echo $category; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="warehouse_id">Gudang</label>
                            <select class="form-control" id="warehouse_id" name="warehouse_id">
                                <option value="">Semua</option>
                                <?php if (!empty($warehouses)): ?>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?php echo $warehouse['id']; ?>" 
                                            <?php echo $this->input->get('warehouse_id') == $warehouse['id'] ? 'selected' : ''; ?>>
                                            <?php echo $warehouse['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua</option>
                                <option value="available" <?php echo $this->input->get('status') == 'available' ? 'selected' : ''; ?>>Available</option>
                                <option value="low_stock" <?php echo $this->input->get('status') == 'low_stock' ? 'selected' : ''; ?>>Low Stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?php echo site_url('laporan/stok'); ?>" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Stok</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
                            <th>Gudang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stock_report)): ?>
                            <?php foreach ($stock_report as $item): ?>
                                <tr>
                                    <td><?php echo $item['code']; ?></td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td><?php echo $item['category']; ?></td>
                                    <td><?php echo $item['unit']; ?></td>
                                    <td><?php echo $item['current_stock']; ?></td>
                                    <td><?php echo $item['min_stock']; ?></td>
                                    <td><?php echo $item['warehouse_name']; ?></td>
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
                                <td colspan="8" class="text-center">No data found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>