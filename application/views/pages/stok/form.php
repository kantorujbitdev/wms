<?php
$role_id = $this->session->userdata('role');
$session_warehouse_id = $this->session->userdata('warehouse_id');
?>


<div class="container-fluid">
    <h3 class="mb-4">Tambah Stok</h3>

    <div class="card shadow">
        <div class="card-body">

            <form action="<?= site_url('gudang_stok/store') ?>" method="post">

                <!-- Jika superadmin, tampilkan dropdown -->
                <?php if ($role_id === 'superadmin'): ?>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse_id" class="form-control" required>
                            <option value="">-- Pilih Gudang --</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w['warehouse_id'] ?>">
                                    <?= $w['warehouse_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Jika bukan superadmin, sembunyikan dropdown -->
                <?php else: ?>
                    <input type="hidden" name="warehouse_id" value="<?= $session_warehouse_id ?>">
                    <div class="mb-3">
                        <label class="form-label">Gudang</label>
                        <input type="text" class="form-control" value="<?= $this->session->userdata('warehouse_name'); ?>"
                            readonly style="background:#e9ecef;">
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['product_id'] ?>">
                                <?= $p['product_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="current_stock" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?= site_url('gudang_stok') ?>" class="btn btn-secondary"><?= $wording['back']; ?></a>

            </form>

        </div>
    </div>
</div>