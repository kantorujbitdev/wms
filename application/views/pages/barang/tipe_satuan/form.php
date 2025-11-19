<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($unit_type) ? 'Edit Tipe Satuan' : 'Tambah Tipe Satuan'; ?>
        </h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tipe Satuan</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('barang/save_tipe_satuan'); ?>
            <?php if (isset($unit_type)): ?>
                <input type="hidden" name="id" value="<?php echo $unit_type['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Tipe Satuan</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($unit_type) ? $unit_type['code'] : set_value('code'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description"
                    rows="3"><?php echo isset($unit_type) ? $unit_type['name'] : set_value('name'); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
                <a href="<?php echo site_url('barang/tipe_satuan'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#unitTypeForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#unitTypeForm').unbind('submit').submit();
                }
            });
        });
    });
</script>