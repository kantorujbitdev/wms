<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Barang Keluar</h1>
        <a href="<?php echo site_url('laporan/export_keluar?' . $_SERVER['QUERY_STRING']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Export CSV
        </a>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('laporan/keluar', ['method' => 'GET']); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="<?php echo $this->input->get('date_from'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="<?php echo $this->input->get('date_to'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="item_id">Barang</label>
                            <select class="form-control" id="item_id" name="item_id">
                                <option value="">Semua</option>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <option value="<?php echo $item['id']; ?>" 
                                            <?php echo $this->input->get('item_id') == $item['id'] ? 'selected' : ''; ?>>
                                            <?php echo $item['name']; ?>
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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo site_url('laporan/keluar'); ?>" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Barang Keluar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Gudang</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($out_report)): ?>
                            <?php foreach ($out_report as $item): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($item['date'])); ?></td>
                                    <td><?php echo $item['item_code']; ?></td>
                                    <td><?php echo $item['item_name']; ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo $item['warehouse_name']; ?></td>
                                    <td><?php echo $item['notes']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>