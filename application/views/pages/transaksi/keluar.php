<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Barang Keluar</h1>
        <a href="<?php echo site_url('transaksi'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> <?= $wording['back']; ?>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Barang Keluar</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('transaksi/save_keluar'); ?>
            <div class="form-group">
                <label for="item_id">Barang</label>
                <select class="form-control" id="item_id" name="item_id" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?>
                                (<?php echo $item['code']; ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="warehouse_id">Gudang</label>
                <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                    <option value="">-- Pilih Gudang --</option>
                    <?php if (!empty($warehouses)): ?>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['name']; ?>
                                (<?php echo $warehouse['code']; ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Jumlah</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>

            <div class="form-group">
                <label for="date">Tanggal</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="notes">Catatan</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><?= $wording['save']; ?></button>
                <a href="<?php echo site_url('transaksi'); ?>" class="btn btn-secondary"><?= $wording['cancel']; ?></a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>