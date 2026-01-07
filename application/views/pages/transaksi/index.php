<div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi</h1>
    </div> -->

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('transaksi', ['method' => 'GET']); ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Jenis Transaksi</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">Semua</option>
                            <option value="in" <?php echo $this->input->get('type') == 'in' ? 'selected' : ''; ?>>Barang
                                Masuk</option>
                            <option value="out" <?php echo $this->input->get('type') == 'out' ? 'selected' : ''; ?>>Barang
                                Keluar</option>
                            <option value="transfer" <?php echo $this->input->get('type') == 'transfer' ? 'selected' : ''; ?>>Transfer</option>
                        </select>
                    </div>
                </div>
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
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo site_url('transaksi'); ?>" class="btn btn-secondary">Reset</a>
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Gudang</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($transaction['date'])); ?></td>
                                    <td>
                                        <span
                                            class="badge badge-<?php echo $transaction['type'] == 'in' ? 'success' : ($transaction['type'] == 'out' ? 'danger' : 'info'); ?>">
                                            <?php echo ucfirst($transaction['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $transaction['item_name']; ?></td>
                                    <td><?php echo $transaction['quantity']; ?></td>
                                    <td>
                                        <?php if ($transaction['type'] == 'transfer'): ?>
                                            <?php echo $transaction['from_warehouse_name']; ?> →
                                            <?php echo $transaction['to_warehouse_name']; ?>
                                        <?php else: ?>
                                            <?php echo $transaction['warehouse_name']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $transaction['notes']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                            data-id="<?php echo $transaction['id']; ?>"
                                            data-name="<?php echo $transaction['warehouse_name']; ?>"
                                            data-url="<?= site_url('transaksi/delete/'); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>