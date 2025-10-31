<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($user) ? 'Edit User' : 'Tambah User'; ?></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php echo form_open(isset($user) ? 'user/edit/' . $user['id'] : 'user/add'); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($user) ? $user['name'] : set_value('name'); ?>" required>
                    <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="username">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?php echo isset($user) ? $user['username'] : set_value('username'); ?>" required>
                    <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo isset($user) ? $user['email'] : set_value('email'); ?>" required>
                    <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password
                        <?php echo !isset($user) ? '<span class="text-danger">*</span>' : '(Kosongkan jika tidak ingin mengubah)'; ?></label>
                    <input type="password" class="form-control" id="password" name="password" <?php echo !isset($user) ? 'required' : ''; ?>>
                    <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password
                        <?php echo !isset($user) ? '<span class="text-danger">*</span>' : ''; ?></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" <?php echo !isset($user) ? 'required' : ''; ?>>
                    <?php echo form_error('confirm_password', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin" <?php echo (isset($user) && $user['role'] == 'admin') ? 'selected' : ''; ?>>
                            Admin</option>
                        <option value="supervisor" <?php echo (isset($user) && $user['role'] == 'supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                        <option value="staff" <?php echo (isset($user) && $user['role'] == 'staff') ? 'selected' : ''; ?>>
                            Staff</option>
                    </select>
                    <?php echo form_error('role', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo isset($user) ? $user['phone'] : set_value('phone'); ?>">
                    <?php echo form_error('phone', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" id="address" name="address"
                        rows="2"><?php echo isset($user) ? $user['address'] : set_value('address'); ?></textarea>
                    <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('user'); ?>" class="btn btn-default">Batal</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->