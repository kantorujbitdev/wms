<?php
$current_user_role     = $this->session->userdata('role');
$user_id              = $this->session->userdata('user_id');
$current_user_warehouse = $this->session->userdata('warehouse_id');
$current_user_warehouse_name = $this->session->userdata('warehouse_name');
$is_edit = isset($user_data);
?>

<div class="container-fluid">
    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?= $is_edit ? 'Edit User' : 'Tambah User'; ?>
            </h6>
        </div>

        <div class="card-body">
            <?= form_open('user/save', ['id' => 'userForm']); ?>

            <!-- Hidden Fields -->
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($user_data['user_id']); ?>">
                <input type="hidden" id="is_edit" value="1">
            <?php else: ?>
                <input type="hidden" id="is_edit" value="0">
            <?php endif; ?>

            <!-- User login info -->
            <input type="hidden" id="current_user_role" value="<?= htmlspecialchars($current_user_role ?? ''); ?>">
            <input type="hidden" id="current_user_id" value="<?= htmlspecialchars($user_id ?? ''); ?>">
            <input type="hidden" id="current_user_warehouse_id" value="<?= htmlspecialchars($current_user_warehouse ?? ''); ?>">
            <input type="hidden" id="current_user_warehouse_name" value="<?= htmlspecialchars($current_user_warehouse_name ?? ''); ?>">

            <!-- Username -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" required
                           value="<?= $is_edit ? htmlspecialchars($user_data['user_name']) : htmlspecialchars(set_value('username')); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    
                    <?php if ($is_edit && ($user_id == $user_data['user_id'])): ?>
                        <!-- SEDANG EDIT DIRI SENDIRI: Role readonly, kirim via hidden -->
                        <div class="form-control bg-light">
                            <?= htmlspecialchars(ucfirst($user_data['user_role'])); ?>
                            <small class="text-muted">(Tidak dapat mengubah role sendiri)</small>
                        </div>
                        <input type="hidden" name="role" value="<?= htmlspecialchars(strtolower($user_data['user_role'])); ?>">
                        
                    <?php else: ?>
                        <!-- EDIT USER LAIN ATAU TAMBAH BARU: Tampilkan dropdown -->
                        <select class="form-control" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): 
                                $role_lower = strtolower($role);
                                $selected = ($is_edit && strtolower($user_data['user_role']) === $role_lower) ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($role_lower); ?>" <?= $selected; ?>>
                                    <?= htmlspecialchars($role); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fullname -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="fullname" required
                       value="<?= $is_edit ? htmlspecialchars($user_data['full_name']) : htmlspecialchars(set_value('fullname')); ?>">
            </div>

            <!-- Warehouse Section -->
            <div id="warehouse-section">
                <?php if ($current_user_role === 'superadmin'): ?>
                    <!-- SUPERADMIN: Bisa pilih warehouse untuk non-superadmin -->
                    <input type="hidden" id="warehouse_hidden" name="warehouse_id" value="0">

                    <div class="row mb-3" id="warehouse-dropdown-container" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label">Ruang Lingkup (Gudang) <span class="text-danger">*</span></label>
                            <select class="form-control" id="warehouse_dropdown" name="warehouse_select">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($warehouses as $w): 
                                    $selected = ($is_edit && $user_data['warehouse_id'] == $w['warehouse_id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= htmlspecialchars($w['warehouse_id']); ?>" <?= $selected; ?>>
                                        <?= htmlspecialchars($w['warehouse_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Pilih gudang untuk role Admin/Staff</small>
                        </div>
                    </div>

                <?php elseif ($current_user_role === 'admin'): ?>
                    <!-- ADMIN: Hanya bisa assign ke warehouse sendiri -->
                    <input type="hidden" name="warehouse_id" value="<?= htmlspecialchars($current_user_warehouse); ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ruang Lingkup (Gudang)</label>
                            <input type="text" class="form-control bg-light" readonly
                                   value="<?= htmlspecialchars($current_user_warehouse_name); ?>">
                            <small class="form-text text-muted">User akan ditempatkan di gudang: <?= htmlspecialchars($current_user_warehouse_name); ?></small>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Section -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">
                        Password
                        <?php if ($is_edit): ?>
                            <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                        <?php else: ?>
                            <span class="text-danger">*</span>
                        <?php endif; ?>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password"
                               <?= $is_edit ? '' : 'required'; ?>
                               minlength="1" maxlength="50">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted">Minimal 1 karakter</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                               <?= $is_edit ? '' : 'required'; ?>>
                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save mr-2"></i> <?= isset($wording['save']) ? htmlspecialchars($wording['save']) : 'Simpan'; ?>
                </button>
                <a href="<?= site_url('user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i> <?= isset($wording['cancel']) ? htmlspecialchars($wording['cancel']) : 'Batal'; ?>
                </a>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal for Messages -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Peringatan</h5>
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
$(document).ready(function() {
    // ==================== VARIABLES ====================
    const currentUserRole = $('#current_user_role').val();
    const currentUserId = $('#current_user_id').val();
    const isEdit = $('#is_edit').val() === '1';
    const editingUserId = isEdit ? $('input[name="id"]').val() : null;
    const isEditingSelf = isEdit && (currentUserId === editingUserId);

    // Elements
    const $roleSelect = $('#role');
    const $warehouseHidden = $('#warehouse_hidden');
    const $warehouseDropdown = $('#warehouse_dropdown');
    const $warehouseContainer = $('#warehouse-dropdown-container');
    const $submitBtn = $('#submitBtn');
    const $form = $('#userForm');

    // ==================== INITIALIZATION ====================
    // Only run warehouse logic if superadmin
    if (currentUserRole === 'superadmin') {
        updateWarehouseSection();
        
        // Set initial warehouse value for edit mode
        if (isEdit) {
            const userRole = '<?= $is_edit ? strtolower($user_data['user_role']) : '' ?>';
            const userWarehouse = '<?= $is_edit ? $user_data['warehouse_id'] : '' ?>';
            
            if (userRole !== 'superadmin' && userWarehouse && userWarehouse !== '0') {
                $warehouseDropdown.val(userWarehouse);
                $warehouseHidden.val(userWarehouse);
                $warehouseContainer.show();
            }
        }
    }

    // ==================== EVENT LISTENERS ====================
    if ($roleSelect.length) {
        $roleSelect.on('change', updateWarehouseSection);
    }

    $('#togglePassword').on('click', function() {
        togglePasswordVisibility('#password', $(this).find('i'));
    });

    $('#toggleConfirmPassword').on('click', function() {
        togglePasswordVisibility('#confirm_password', $(this).find('i'));
    });

    $form.on('submit', validateForm);

    if (currentUserRole === 'superadmin' && $warehouseDropdown.length) {
        $warehouseDropdown.on('change', function() {
            $warehouseHidden.val($(this).val());
        });
    }

    // Reset submit button when form is submitted successfully
    $form.on('form-submit-success', function() {
        $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Simpan');
    });

    // ==================== FUNCTIONS ====================
    
    /**
     * Show modal with message
     */
    function showModal(message) {
        $('#modalMessage').text(message);
        $('#userModal').modal('show');
    }

    /**
     * Update warehouse section based on selected role (for superadmin only)
     */
    function updateWarehouseSection() {
        if (currentUserRole !== 'superadmin') return;
        
        // Jika sedang edit user superadmin, sembunyikan warehouse
        if (isEdit && '<?= $is_edit ? strtolower($user_data['user_role']) : '' ?>' === 'superadmin') {
            $warehouseContainer.hide();
            $warehouseHidden.val('0');
            $warehouseDropdown.prop('required', false);
            return;
        }

        const selectedRole = $roleSelect.val()?.toLowerCase() || '';

        if (selectedRole === 'superadmin') {
            // Superadmin tidak perlu warehouse
            $warehouseContainer.hide();
            $warehouseHidden.val('0');
            $warehouseDropdown.prop('required', false);
        } 
        else if (selectedRole === 'admin' || selectedRole === 'staff') {
            // Admin/Staff wajib pilih warehouse
            $warehouseContainer.show();
            $warehouseDropdown.prop('required', true);
            
            // Jika belum ada value, reset
            if (!$warehouseDropdown.val()) {
                $warehouseHidden.val('');
            }
        } 
        else {
            // Role belum dipilih
            $warehouseContainer.hide();
            $warehouseHidden.val('0');
            $warehouseDropdown.prop('required', false);
        }
    }

    /**
     * Toggle password visibility
     */
    function togglePasswordVisibility(fieldId, $icon) {
        const $field = $(fieldId);
        const type = $field.attr('type') === 'password' ? 'text' : 'password';
        
        $field.attr('type', type);
        $icon.toggleClass('fa-eye fa-eye-slash');
    }

    /**
     * Form validation
     */
    function validateForm(e) {
        e.preventDefault();

        // Prevent double submit
        if ($submitBtn.prop('disabled')) {
            return false;
        }

        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

        // Ambil role (dari select atau hidden)
        let selectedRole = '';
        if ($roleSelect.length) {
            selectedRole = $roleSelect.val()?.toLowerCase() || '';
        } else {
            selectedRole = $('input[name="role"]').val()?.toLowerCase() || '';
        }
        
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();
        
        // Ambil warehouse value untuk superadmin
        let warehouseValue = '0';
        if (currentUserRole === 'superadmin') {
            warehouseValue = $warehouseHidden.val();
            // Jika hidden kosong, coba ambil dari dropdown
            if (!warehouseValue && $warehouseDropdown.length) {
                warehouseValue = $warehouseDropdown.val();
            }
        }

        let isValid = true;
        let errorMessages = [];

        // ===== VALIDASI ROLE =====
        if (!selectedRole) {
            isValid = false;
            errorMessages.push('Role harus dipilih!');
        }

        // ===== VALIDASI ADMIN TIDAK BOLEH BUAT SUPERADMIN =====
        if (currentUserRole !== 'superadmin' && selectedRole === 'superadmin') {
            isValid = false;
            errorMessages.push('Anda tidak memiliki izin untuk membuat/mengubah user Superadmin!');
        }

        // ===== VALIDASI ADMIN TIDAK BOLEH EDIT SUPERADMIN =====
        if (currentUserRole !== 'superadmin' && isEdit) {
            const existingUserRole = '<?= $is_edit ? strtolower($user_data['user_role']) : '' ?>';
            if (existingUserRole === 'superadmin') {
                isValid = false;
                errorMessages.push('Anda tidak memiliki izin untuk mengedit user Superadmin!');
            }
        }

        // ===== VALIDASI WAREHOUSE UNTUK SUPERADMIN =====
        if (currentUserRole === 'superadmin' && selectedRole && selectedRole !== 'superadmin') {
            if (!warehouseValue || warehouseValue === '0' || warehouseValue === '') {
                isValid = false;
                errorMessages.push('Untuk role ' + selectedRole + ', ruang lingkup gudang WAJIB dipilih!');
            }
        }

        // ===== VALIDASI PASSWORD =====
        if (isEdit) {
            // MODE EDIT: Password optional
            if (password !== '' || confirmPassword !== '') {
                // Jika salah satu diisi, keduanya harus diisi dan match
                if (password === '') {
                    isValid = false;
                    errorMessages.push('Password harus diisi jika ingin mengubah password!');
                } else if (confirmPassword === '') {
                    isValid = false;
                    errorMessages.push('Konfirmasi password harus diisi!');
                } else if (password !== confirmPassword) {
                    isValid = false;
                    errorMessages.push('Password dan konfirmasi password tidak cocok!');
                } else if (password.length < 1) {
                    isValid = false;
                    errorMessages.push('Password minimal 1 karakter!');
                }
            }
        } else {
            // MODE TAMBAH: Password wajib
            if (!password) {
                isValid = false;
                errorMessages.push('Password wajib diisi untuk user baru!');
            } else if (password !== confirmPassword) {
                isValid = false;
                errorMessages.push('Password dan konfirmasi password tidak cocok!');
            } else if (password.length < 1) {
                isValid = false;
                errorMessages.push('Password minimal 1 karakter!');
            }
        }

        if (!isValid) {
            // Reset submit button
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Simpan');
            showModal(errorMessages.join('\n'));
        } else {
            // Submit form
            e.target.submit();
            
            // Note: The button will remain disabled after form submission
            // This is intentional to prevent double submission
            // The page will redirect/reload after successful submission
        }
    }
});
</script>

<style>
/* Additional style for loading state */
.btn-primary:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}
</style>