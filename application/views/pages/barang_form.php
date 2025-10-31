<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($item) ? 'Edit Barang' : 'Tambah Barang'; ?></h3>
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
        <?php echo form_open(isset($item) ? 'barang/edit/' . $item['id'] : 'barang/add'); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($item) ? $item['name'] : set_value('name'); ?>" required>
                    <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="code">Kode Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="code" name="code"
                        value="<?php echo isset($item) ? $item['code'] : set_value('code'); ?>" required>
                    <?php echo form_error('code', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori <span class="text-danger">*</span></label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php if (isset($categories['data']) && is_array($categories['data'])): ?>
                            <?php foreach ($categories['data'] as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (isset($item) && $item['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php echo form_error('category_id', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="unit">Satuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="unit" name="unit"
                        value="<?php echo isset($item) ? $item['unit'] : set_value('unit'); ?>" required>
                    <?php echo form_error('unit', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">Harga <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" class="form-control" id="price" name="price"
                            value="<?php echo isset($item) ? $item['price'] : set_value('price'); ?>" required>
                    </div>
                    <?php echo form_error('price', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="min_stock">Minimal Stok</label>
                    <input type="number" class="form-control" id="min_stock" name="min_stock"
                        value="<?php echo isset($item) ? $item['min_stock'] : set_value('min_stock'); ?>">
                    <?php echo form_error('min_stock', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="3"><?php echo isset($item) ? $item['description'] : set_value('description'); ?></textarea>
                    <?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="image">Gambar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image">
                        <label class="custom-file-label" for="image">Pilih file</label>
                    </div>
                    <?php if (isset($item) && !empty($item['image'])): ?>
                        <img src="<?php echo $item['image']; ?>" class="mt-2" alt="Gambar Barang"
                            style="max-width: 100px; max-height: 100px;">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('barang'); ?>" class="btn btn-default">Batal</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<script>
    $(document).ready(function () {
        // Custom file input
        $('.custom-file-input').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>