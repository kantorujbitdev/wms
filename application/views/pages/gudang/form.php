<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($warehouse) ? 'Edit Gudang Utama' : 'Tambah Gudang Utama'; ?>
        </h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['gudang_form_utama']; ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open('gudang/save_warehouse_utama'); ?>
            <?php if (isset($warehouse)): ?>
                <input type="hidden" name="id" value="<?php echo $warehouse['warehouse_id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="warehouse_code" class="form-label">Kode Gudang <span
                            class="text-danger">*</span></label>

                    <input type="text" class="form-control <?php echo isset($warehouse) ? 'bg-light' : ''; ?>"
                        id="warehouse_code" name="warehouse_code" <?php echo isset($warehouse) ? 'readonly' : ''; ?>
                        value="<?php echo isset($warehouse) ? $warehouse['warehouse_code'] : set_value('warehouse_code'); ?>"
                        required>
                </div>

                <div class="col-md-6">
                    <label for="warehouse_name" class="form-label">Nama Gudang <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="warehouse_name" name="warehouse_name"
                        value="<?php echo isset($warehouse) ? $warehouse['warehouse_name'] : set_value('warehouse_name'); ?>"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label for="warehouse_address" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" id="warehouse_address" name="warehouse_address" rows="3"
                    required><?php echo isset($warehouse) ? $warehouse['warehouse_address'] : set_value('warehouse_address'); ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="contact_person" class="form-label">Nama Kontak <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contact_person" name="contact_person"
                        value="<?php echo isset($warehouse) ? $warehouse['contact_person'] : set_value('contact_person'); ?>"
                        required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor Kontak</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo isset($warehouse) ? $warehouse['phone'] : set_value('phone'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('gudang'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Handle checkbox change
        $('#is_project').change(function () {
            if ($(this).is(':checked')) {
                $('#warehouse_type').val('1'); // Gudang Project
            } else {
                $('#warehouse_type').val('0'); // Gudang Utama
            }
        });

        // Set default status to active (0) for new warehouse
        if (!$('input[name="id"]').val()) {
            $('#warehouse_status').val('0');
        }

        $('#warehouseForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#warehouseForm').unbind('submit').submit();
                }
            });
        });
    });
</script>