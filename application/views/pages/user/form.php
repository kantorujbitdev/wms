<?php $current_user_role = $this->session->userdata('role'); ?>
<?php $current_user_warehouse = $this->session->userdata('warehouse_id'); ?><div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?= isset($user_data) ? 'Edit User' : 'Tambah User'; ?>
        </h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form User</h6>
        </div>

        <div class="card-body">
            <?= form_open('user/save', ['id' => 'userForm']); ?>

            <?php if (isset($user_data)): ?>
                <input type="hidden" name="id" value="<?= $user_data['user_id']; ?>">
                <input type="hidden" id="is_edit" value="1">
            <?php else: ?>
                <input type="hidden" id="is_edit" value="0">
            <?php endif; ?>

            <!-- Info user login -->
            <input type="hidden" id="current_user_role" value="<?= $current_user_role ?? ''; ?>">
            <input type="hidden" id="current_user_warehouse_id" value="<?= $current_user_warehouse ?? ''; ?>">
            <input type="hidden" id="current_user_warehouse_name" value="<?= $this->session->userdata('warehouse_name') ?? ''; ?>">

            <!-- Username & Role -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required
                           value="<?= isset($user_data) ? $user_data['user_name'] : set_value('username'); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <?php
                            $current_role = $current_user_role ?? ''; ?>
                                <option value="<?= $role; ?>"
                                    <?= isset($user_data) && strtolower($user_data['user_role']) === $role ? 'selected' : ''; ?>>
                                    <?= ucfirst($role); ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Fullname -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="fullname" required
                       value="<?= isset($user_data) ? $user_data['full_name'] : set_value('fullname'); ?>">
            </div>

            <!-- Warehouse Section - Logika berdasarkan role yang login dan role yang dipilih -->
            <div id="warehouse-section">
                <!-- Untuk Superadmin: akan ditampilkan/diisi oleh JavaScript -->
                <?php if ($current_user_role === 'superadmin'): ?>
                    <!-- Input hidden untuk superadmin (default 0) -->
                    <input type="hidden" id="warehouse_hidden" name="warehouse_id" value="0">
                    
                    <!-- Container untuk dropdown (akan diatur oleh JS) -->
                    <div class="row mb-3" id="warehouse-dropdown-container" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label">Ruang Lingkup (Gudang) <span class="text-danger">*</span></label>
                            <select class="form-control" id="warehouse_dropdown">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($warehouses as $w): ?>
                                    <option value="<?= $w['warehouse_id']; ?>"
                                        <?= isset($user_data) && $user_data['warehouse_id'] == $w['warehouse_id'] ? 'selected' : ''; ?>>
                                        <?= $w['warehouse_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small id="warehouseHelp" class="form-text text-muted">
                                Wajib dipilih untuk user non-superadmin
                            </small>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Untuk Admin: warehouse dari session -->
                <?php if ($current_user_role === 'admin'): ?>
                    <input type="hidden" name="warehouse_id" value="<?= $current_user_warehouse; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ruang Lingkup (Gudang)</label>
                            <input type="text" class="form-control bg-light" readonly
                                   value="<?= $this->session->userdata('warehouse_name'); ?>">
                            <small class="form-text text-muted">
                                Gudang akan mengikuti akun admin Anda
                            </small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">
                        Password
                        <?= isset($user_data) ? '<small class="text-muted">(Kosongkan jika tidak diubah)</small>' : ''; ?>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password"
                            <?= isset($user_data) ? '' : 'required'; ?>>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            <?= isset($user_data) ? '' : 'required'; ?>>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= $wording['save']; ?>
                </button>
                <a href="<?= site_url('user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> <?= $wording['cancel']; ?>
                </a>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="warehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warehouseModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Variables
    const currentUserRole = $('#current_user_role').val();
    const isEdit = $('#is_edit').val() === '1';
    
    // Elements
    const roleSelect = $('#role');
    const warehouseHidden = $('#warehouse_hidden');
    const warehouseDropdown = $('#warehouse_dropdown');
    const warehouseContainer = $('#warehouse-dropdown-container');
    
    // Initialize
    updateWarehouseSection();

    // Event Listeners
    roleSelect.on('change', updateWarehouseSection);
    
    $('#togglePassword').on('click', function() {
        togglePasswordVisibility('#password', $(this).find('i'));
    });
    
    $('#toggleConfirmPassword').on('click', function() {
        togglePasswordVisibility('#confirm_password', $(this).find('i'));
    });
    
    $('#userForm').on('submit', validateForm);
    
    // Handle dropdown change untuk superadmin
    if (currentUserRole === 'superadmin') {
        warehouseDropdown.on('change', function() {
            warehouseHidden.val($(this).val());
        });
    }

    // Functions
    function showModal(title, message) {
        $('#warehouseModalLabel').text(title);
        $('#modalMessage').text(message);
        $('#warehouseModal').modal('show');
    }

    function updateWarehouseSection() {
        const selectedRole = roleSelect.val().toLowerCase();
        
        // SUPERADMIN yang login
        if (currentUserRole === 'superadmin') {
            if (selectedRole === 'superadmin') {
                // 1. Superadmin membuat user superadmin -> tidak tampil, warehouse_id = 0
                warehouseContainer.hide();
                warehouseHidden.val('0');
                warehouseDropdown.prop('required', false);
            } else if (selectedRole === 'admin' || selectedRole === 'staff') {
                // 2. Superadmin membuat user non-superadmin -> tampil dropdown, wajib pilih
                warehouseContainer.show();
                warehouseDropdown.prop('required', true);
                
                // Set default value jika belum ada
                if (!warehouseDropdown.val()) {
                    warehouseDropdown.val('');
                    warehouseHidden.val('');
                }
            } else {
                // Untuk role lain (jika ada)
                warehouseContainer.hide();
                warehouseHidden.val('0');
            }
        }
        // ADMIN yang login - tidak ada perubahan, sudah statis di PHP
        // Warehouse otomatis dari session, form tidak tampil
    }

    function togglePasswordVisibility(fieldId, iconElement) {
        const field = $(fieldId);
        const type = field.attr('type') === 'password' ? 'text' : 'password';
        field.attr('type', type);
        
        // Toggle icon
        if (type === 'text') {
            iconElement.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            iconElement.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }

    function validateForm(e) {
        e.preventDefault();
        
        const selectedRole = roleSelect.val().toLowerCase();
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();
        
        let isValid = true;
        let errorMessage = '';

        // VALIDASI 1: Warehouse untuk superadmin membuat non-superadmin
        if (currentUserRole === 'superadmin' && selectedRole !== 'superadmin') {
            const warehouseValue = warehouseHidden.val();
            
            if (!warehouseValue || warehouseValue === '0') {
                isValid = false;
                errorMessage = 'Untuk role ' + selectedRole + ', ruang lingkup gudang WAJIB dipilih!';
            }
        }

        // VALIDASI 2: Password match
        if (!isEdit || password !== '') {
            if (password !== confirmPassword) {
                isValid = false;
                if (errorMessage) errorMessage += '\n';
                errorMessage += 'Password dan konfirmasi password tidak cocok!';
            }
            
            // VALIDASI 3: Password length for new user or when changing password
            if (password.length > 0 && password.length < 1) {
                isValid = false;
                if (errorMessage) errorMessage += '\n';
                errorMessage += 'Password minimal 1 karakter!';
            }
        }

        // VALIDASI 4: Role harus dipilih
        if (!selectedRole) {
            isValid = false;
            if (errorMessage) errorMessage += '\n';
            errorMessage += 'Role harus dipilih!';
        }

        // Tampilkan error atau submit form
        if (!isValid) {
            showModal('Validasi Gagal', errorMessage);
        } else {
            // All validations passed, submit the form
            e.target.submit();
        }
    }
    
    // Handle edit mode: jika edit, set nilai warehouse berdasarkan data yang ada
    if (isEdit && currentUserRole === 'superadmin') {
        // Cek apakah ada data warehouse di edit mode
        const warehouseValue = '<?= isset($user_data) ? $user_data["warehouse_id"] : ""; ?>';
        const userRole = '<?= isset($user_data) ? strtolower($user_data["user_role"]) : ""; ?>';
        
        if (userRole !== 'superadmin' && warehouseValue && warehouseValue !== '0') {
            warehouseDropdown.val(warehouseValue);
            warehouseHidden.val(warehouseValue);
            
            // Tampilkan dropdown jika role bukan superadmin
            if (userRole !== 'superadmin') {
                warehouseContainer.show();
            }
        }
    }
});
</script>