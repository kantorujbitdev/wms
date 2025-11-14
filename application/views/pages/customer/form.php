<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($customer) ? 'Edit Customer' : 'Tambah Customer'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['customer_form']; ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open('customer/save', ['id' => 'customerForm']); ?>
            <?php if (isset($customer)): ?>
                <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Customer</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($customer) ? $customer['Name'] : set_value('name'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="contact" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="contact" name="contact"
                        value="<?php echo isset($customer) ? $customer['Contact'] : set_value('contact'); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo isset($customer) ? $customer['Phone'] : set_value('phone'); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    required><?php echo isset($customer) ? $customer['Address'] : set_value('address'); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('customer'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $('#customerForm').on('submit', function (e) {
        e.preventDefault();
        showConfirmationModal({
            title: 'Konfirmasi Simpan',
            message: 'Apakah data yang Anda masukkan sudah benar?',
            confirmText: 'Ya, Simpan',
            confirmClass: 'btn-primary',
            onConfirm: function () {
                // Submit form
                $('#customerForm').unbind('submit').submit();
            }
        });
    });
</script>