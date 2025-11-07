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
            <?php echo form_open('gudang/save', ['id' => 'warehouseForm']); ?>
            <?php if (isset($warehouse)): ?>
                <input type="hidden" name="id" value="<?php echo $warehouse['Warehouse_Id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">Kode Gudang</label>
                    <input type="text" class="form-control" id="code" name="code"
                        value="<?php echo isset($warehouse) ? $warehouse['Warehouse_Code'] : set_value('code'); ?>"
                        required>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Gudang</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($warehouse) ? $warehouse['Warehouse_Name'] : set_value('name'); ?>"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label for="addr" class="form-label">Alamat</label>
                <textarea class="form-control" id="addr" name="addr" rows="3"
                    required><?php echo isset($warehouse) ? $warehouse['Warehouse_Address'] : set_value('addr'); ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="contact" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="contact" name="contact"
                        value="<?php echo isset($warehouse) ? $warehouse['Contact_Person'] : set_value('contact'); ?>"
                        required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo isset($warehouse) ? $warehouse['Phone'] : set_value('phone'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
                <a href="<?php echo site_url('gudang'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
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