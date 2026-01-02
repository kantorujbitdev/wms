<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($user_data) ? 'Edit User' : 'Tambah User'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form User</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('user/save'); ?>
            <?php if (isset($user_data)): ?>
                <input type="hidden" name="id" value="<?php echo $user_data['user_id']; ?>">
                <?php echo save_log("user_id: " . ucfirst($user_data['user_id'])); ?>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?php echo isset($user_data) ? $user_data['user_name'] : set_value('username'); ?>"
                        required>
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <?php if (!empty($roles)): ?>
                            <?php foreach ($roles as $role): ?>
                                <?php echo save_log("user_role: " . ucfirst($user_data['user_role'])); ?>
                                <option value="<?php echo $role; ?>" <?php echo (isset($user_data) && ucfirst($user_data['user_role']) == $role) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($role); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="fullname" name="fullname"
                    value="<?php echo isset($user_data) ? $user_data['full_name'] : set_value('fullname'); ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="warehouse_id" class="form-label">Ruang Lingkup (Gudang)</label>
                    <select class="form-control" id="warehouse_id" name="warehouse_id">
                        <option value="">-- Pilih Gudang --</option>
                        <?php if (!empty($warehouses)): ?>
                            <?php foreach ($warehouses as $g): ?>
                                <option value="<?= $g['warehouse_id']; ?>" <?= (isset($user_data) && $user_data['warehouse_id'] == $g['warehouse_id']) ? 'selected' : ''; ?>>
                                    <?= $g['warehouse_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small id="warehouseHelp" class="form-text text-muted"></small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password
                        <?php echo isset($user_data) ? '<small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>' : ''; ?></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" <?php echo !isset($user_data) ? 'required' : ''; ?>>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" <?php echo !isset($user_data) ? 'required' : ''; ?>>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Fungsi untuk update tampilan gudang berdasarkan role
        function updateWarehouseField() {
            const role = $('#role').val().toLowerCase();
            const warehouseField = $('#warehouse_id');
            const warehouseHelp = $('#warehouseHelp');

            if (role === 'superadmin') {
                // Nonaktifkan required untuk superadmin
                warehouseField.prop('required', false);
                warehouseField.prop('disabled', true);
                warehouseField.val('');
                warehouseHelp.text('Superadmin tidak memerlukan ruang lingkup gudang');
                warehouseHelp.removeClass('text-danger').addClass('text-muted');
            } else {
                // Aktifkan required untuk role lainnya
                warehouseField.prop('required', true);
                warehouseField.prop('disabled', false);
                warehouseHelp.text('Pilih gudang untuk ruang lingkup user');
                warehouseHelp.removeClass('text-danger').addClass('text-muted');
            }
        }

        // Inisialisasi saat halaman dimuat
        updateWarehouseField();

        // Update saat role berubah
        $('#role').change(function () {
            updateWarehouseField();
        });

        // Toggle password visibility
        $('#togglePassword').click(function () {
            const passwordField = $('#password');
            const passwordType = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', passwordType);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggleConfirmPassword').click(function () {
            const confirmPasswordField = $('#confirm_password');
            const confirmPasswordType = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.attr('type', confirmPasswordType);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        // Validasi form submit
        $('form').submit(function (e) {
            var role = $('#role').val();
            var warehouseId = $('#warehouse_id').val();
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();
            var isEdit = <?php echo isset($user_data) ? 'true' : 'false'; ?>;

            // Validasi untuk role non-superadmin
            if (role && role.toLowerCase() !== 'superadmin' && !warehouseId) {
                e.preventDefault();
                showConfirmationModal({
                    title: 'Gudang Harus Dipilih',
                    message: 'Untuk role ' + role + ', ruang lingkup gudang wajib dipilih!',
                    confirmText: 'OK',
                    confirmClass: 'btn-primary',
                    onConfirm: function () {
                        // Just close the modal
                    }
                });
                return;
            }

            // Validasi password
            if (isEdit && password === '') {
                return true;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                showConfirmationModal({
                    title: 'Password Tidak Cocok',
                    message: 'Password dan konfirmasi password harus sama!',
                    confirmText: 'OK',
                    confirmClass: 'btn-primary',
                    onConfirm: function () {
                        // Just close the modal
                    }
                });
            }
        });
    });
</script>