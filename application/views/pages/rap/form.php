<div class="container-fluid">
    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?php echo isset($rap) ? 'Edit RAP' : 'Tambah RAP'; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php echo form_open('rap/save', ['id' => 'rapForm']); ?>
            <?php if (isset($rap)): ?>
                <input type="hidden" name="id" value="<?php echo $rap['id']; ?>">
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="warehouse_id" class="form-label">Gudang Proyek</label>
                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                        <option value="">-- Pilih Gudang Proyek --</option>
                        <?php foreach ($proyeks as $proyek): ?>
                            <option value="<?php echo $proyek['warehouse_id']; ?>"
                                <?php echo (isset($rap) && $rap['warehouse_id'] == $proyek['warehouse_id']) ? 'selected' : ''; ?>>
                                <?php echo $proyek['warehouse_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Proyek</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($rap) ? $rap['name'] : set_value('name'); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="contract_number" class="form-label">Nomor Kontrak</label>
                    <input type="text" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo isset($rap) ? $rap['contract_number'] : set_value('contract_number'); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('rap'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#rapForm').on('submit', function (e) {
            e.preventDefault();

            showConfirmationModal({
                title: 'Konfirmasi Simpan',
                message: 'Apakah data yang Anda masukkan sudah benar?',
                confirmText: 'Ya, Simpan',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    // Submit form
                    $('#rapForm').unbind('submit').submit();
                }
            });
        });
    });
</script>