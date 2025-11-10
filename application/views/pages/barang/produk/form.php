<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($product) ? 'Edit Produk' : 'Tambah Produk'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Produk</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('barang/save_produk'); ?>
            <?php if (isset($product)): ?>
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">Kode Produk</label>
                    <input type="text" class="form-control" id="code" name="code"
                        value="<?php echo isset($product) ? $product['code'] : set_value('code'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($product) ? $product['name'] : set_value('name'); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="product_type_id" class="form-label">Tipe Produk</label>
                    <select class="form-control" id="product_type_id" name="product_type_id" required>
                        <option value="">-- Pilih Tipe Produk --</option>
                        <?php if (!empty($product_types)): ?>
                            <?php foreach ($product_types as $type): ?>
                                <option value="<?php echo $type['Product_Type_Id']; ?>" <?php echo (isset($product) && $product['product_type_id'] == $type['Product_Type_Id']) ? 'selected' : ''; ?>>
                                    <?php echo $type['Product_Type_Name']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="unit_type_id" class="form-label">Tipe Satuan</label>
                    <select class="form-control" id="unit_type_id" name="unit_type_id" required>
                        <option value="">-- Pilih Tipe Satuan --</option>
                        <?php if (!empty($unit_types)): ?>
                            <?php foreach ($unit_types as $type): ?>
                                <option value="<?php echo $type['Unit_Id']; ?>" <?php echo (isset($product) && $product['id'] == $type['Unit_Id']) ? 'selected' : ''; ?>>
                                    <?php echo $type['Unit_Name']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="min_stock" class="form-label">Minimum Stok</label>
                    <input type="number" class="form-control" id="min_stock" name="min_stock" min="0"
                        value="<?php echo isset($product) ? $product['min_stock'] : set_value('min_stock'); ?>"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description"
                    rows="3"><?php echo isset($product) ? $product['description'] : set_value('description'); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
                <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#productForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#productForm').unbind('submit').submit();
                }
            });
        });
    });
</script>