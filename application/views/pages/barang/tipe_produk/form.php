<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?php echo isset($product_type) ? 'Edit Tipe Produk' : 'Tambah Tipe Produk'; ?>
        </h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tipe Produk</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('barang/save_tipe_produk'); ?>
            <?php if (isset($product_type)): ?>
                <input type="hidden" name="id" value="<?php echo $product_type['Product_Type_Id']; ?>">
            <?php endif; ?>
            <?php if (isset($user_data)): ?>
                <input type="hidden" name="id" value="<?php echo $user_data['User_Id']; ?>">
            <?php endif; ?>
            <?php save_log('view save_tipe_produk called with ID: ' . $product_type['Product_Type_Id'], 'debug'); ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Tipe Produk</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($product_type) ? $product_type['Product_Type_Code'] : set_value('Product_Type_Code'); ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description"
                    rows="3"><?php echo isset($product_type) ? $product_type['Product_Type_Name'] : set_value('Product_Type_Name'); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
                <a href="<?php echo site_url('barang/tipe_produk'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#productTypeForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#productTypeForm').unbind('submit').submit();
                }
            });
        });
    });
</script>