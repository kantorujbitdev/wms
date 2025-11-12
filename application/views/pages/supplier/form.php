<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['supplier_form']; ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open('supplier/save', ['id' => 'supplierForm']); ?>
            <?php if (isset($supplier)): ?>
                <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Supplier</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($supplier) ? $supplier['Name'] : set_value('name'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="contact" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="contact" name="contact"
                        value="<?php echo isset($supplier) ? $supplier['Contact'] : set_value('contact'); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo isset($supplier) ? $supplier['Phone'] : set_value('phone'); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    required><?php echo isset($supplier) ? $supplier['Addr'] : set_value('address'); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('supplier'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#supplierForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#supplierForm').unbind('submit').submit();
                }
            });
        });
    });
</script>