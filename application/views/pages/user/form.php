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
                <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo isset($user_data) ? $user_data['username'] : set_value('username'); ?>" required>
            </div>

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($user_data) ? $user_data['name'] : set_value('name'); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo isset($user_data) ? $user_data['email'] : set_value('email'); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role; ?>" <?php echo (isset($user_data) && $user_data['role'] == $role) ? 'selected' : ''; ?>>
                                <?php echo $role; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Active" <?php echo (isset($user_data) && $user_data['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo (isset($user_data) && $user_data['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password
                    <?php echo isset($user_data) ? '(Kosongkan jika tidak ingin mengubah)' : ''; ?></label>
                <input type="password" class="form-control" id="password" name="password" <?php echo !isset($user_data) ? 'required' : ''; ?>>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" <?php echo !isset($user_data) ? 'required' : ''; ?>>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('user'); ?>" class="btn btn-secondary">Batal</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Password confirmation
        $('form').submit(function (e) {
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password tidak cocok',
                    text: 'Password dan konfirmasi password harus sama!'
                });
            }
        });
    });
</script>