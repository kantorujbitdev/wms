<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($product) ? 'Edit Produk' : 'Tambah Produk'; ?></h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Produk</h6>
        </div>
        <div class="card-body">

            <?php echo form_open('barang/save_produk'); ?>

            <?php if (isset($product)): ?>
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <?php if (isset($product)): ?>
                    <div class="col-md-6">
                        <label>Kode Produk</label>
                        <input type="text" class="form-control" name="product_code" value="<?php echo $product['product_code']; ?>"
                            readonly>
                    </div>
                <?php endif; ?>


                <div class="col-md-6">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" name="product_name"
                        value="<?php echo isset($product) ? $product['product_name'] : set_value('product_name'); ?>"
                        required>
                </div>
            </div>

            <div class="row mb-3">
                <!-- PRODUCT TYPE -->
                <div class="col-md-6">
                    <label>Tipe Produk</label>
                    <select class="form-control" name="product_type_id" required>
                        <option value="">-- Pilih Tipe Produk --</option>

                        <?php foreach ($product_types as $pt): ?>
                            <option value="<?php echo $pt['Product_Type_Id']; ?>"
                                <?php echo (isset($product) && $product['type_id'] == $pt['Product_Type_Id']) ? 'selected' : ''; ?>>
                                <?php echo $pt['Product_Type_Code'] . " - " . $pt['Product_Type_Name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- UNIT TYPE -->
                <div class="col-md-6">
                    <label>Tipe Satuan</label>
                    <select class="form-control" name="unit_type_id" required>
                        <option value="">-- Pilih Satuan --</option>

                        <?php foreach ($unit_types as $ut): ?>
                            <option value="<?php echo $ut['id']; ?>"
                                <?php echo (isset($product) && $product['unit_id'] == $ut['id']) ? 'selected' : ''; ?>>
                                <?php echo $ut['code'] . " - " . $ut['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $wording['save']; ?>
                </button>

                <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> <?= $wording['cancel']; ?>
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
            onConfirm: () => {
                $('#productForm').unbind('submit').submit();
            }
        });
    });
});
</script>
