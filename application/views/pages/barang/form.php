<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($item) ? 'Edit Barang' : 'Tambah Barang'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Barang</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('barang/save'); ?>
                <?php if (isset($item)): ?>
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Nama Barang</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php echo isset($item) ? $item['name'] : set_value('name'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="code">Kode Barang</label>
                    <input type="text" class="form-control" id="code" name="code" 
                           value="<?php echo isset($item) ? $item['code'] : set_value('code'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>" 
                                    <?php echo (isset($item) && $item['category'] == $category) ? 'selected' : ''; ?>>
                                    <?php echo $category; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="unit">Satuan</label>
                    <select class="form-control" id="unit" name="unit" required>
                        <option value="">-- Pilih Satuan --</option>
                        <?php if (!empty($units)): ?>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?php echo $unit; ?>" 
                                    <?php echo (isset($item) && $item['unit'] == $unit) ? 'selected' : ''; ?>>
                                    <?php echo $unit; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control" id="price" name="price" 
                           value="<?php echo isset($item) ? $item['price'] : set_value('price'); ?>" 
                           step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="min_stock">Stok Minimum</label>
                    <input type="number" class="form-control" id="min_stock" name="min_stock" 
                           value="<?php echo isset($item) ? $item['min_stock'] : set_value('min_stock'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($item) ? $item['description'] : set_value('description'); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?= $wording['save']; ?></button>
                    <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary"><?= $wording['cancel']; ?></a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>