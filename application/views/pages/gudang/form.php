<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($warehouse) ? 'Edit Gudang' : 'Tambah Gudang'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Gudang</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('gudang/save'); ?>
            <?php if (isset($warehouse)): ?>
                <input type="hidden" name="id" value="<?php echo $warehouse['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Nama Gudang</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($warehouse) ? $warehouse['name'] : set_value('name'); ?>" required>
            </div>

            <div class="form-group">
                <label for="code">Kode Gudang</label>
                <input type="text" class="form-control" id="code" name="code"
                    value="<?php echo isset($warehouse) ? $warehouse['code'] : set_value('code'); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    required><?php echo isset($warehouse) ? $warehouse['address'] : set_value('address'); ?></textarea>
            </div>

            <div class="form-group">
                <label for="capacity">Kapasitas</label>
                <input type="number" class="form-control" id="capacity" name="capacity"
                    value="<?php echo isset($warehouse) ? $warehouse['capacity'] : set_value('capacity'); ?>" required>
            </div>

            <div class="form-group">
                <label for="manager">Manager</label>
                <input type="text" class="form-control" id="manager" name="manager"
                    value="<?php echo isset($warehouse) ? $warehouse['manager'] : set_value('manager'); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Telepon</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="<?php echo isset($warehouse) ? $warehouse['phone'] : set_value('phone'); ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('gudang'); ?>" class="btn btn-secondary">Batal</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>