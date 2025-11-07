<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Management</h1>
        <a href="<?php echo site_url('user/add'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah User
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php $no = 1;
                            foreach ($users as $user): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $user['User_Name']; ?></td>
                                    <td><?php echo !empty($user['Full_Name']) ? $user['Full_Name'] : '-'; ?></td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-<?php echo $user['User_Role'] == 'admin' ? 'danger' : ($user['User_Role'] == 'Supervisor' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($user['User_Role']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d-m-Y H:i', strtotime($user['CreatedAt'])); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('user/edit/' . $user['User_Id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                            <?php save_log("Edit button rendered for user ID: " . $user['User_Id'], 'debug'); ?>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?php echo $user['User_Id']; ?>"
                                            data-name="<?php echo $user['User_Name']; ?>">
                                            <i class="fas fa-trash"></i>
                                            <?php save_log("Delete button rendered for user ID: " . $user['User_Id'], 'debug'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Delete confirmation using our dynamic modal
        $('.delete-btn').click(function () {
            var id = $(this).data('id');
            var name = $(this).data('name');

            showConfirmationModal({
                title: 'Konfirmasi Hapus',
                message: `Apakah Anda yakin ingin menghapus user "${name}"?`,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                confirmUrl: '<?php echo site_url('user/delete/'); ?>' + id
            });
        });
    });
</script>