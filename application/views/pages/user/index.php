<div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['user_management']; ?></h1>
    </div> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['user_list']; ?>
                </h6>

                <a href="<?= site_url('user/add') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $wording['user_add']; ?>
                </a>
            </div>
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
                            <th>Lingkup</th>
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
                                    <td><?php echo $user['user_name']; ?></td>
                                    <td><?php echo !empty($user['full_name']) ? $user['full_name'] : '-'; ?></td>
                                    <td class="text-center">
                                        <?php
                                        $roleColors = [
                                            'superadmin' => 'danger',
                                            'admin' => 'warning',
                                            'staff' => 'info'
                                        ];

                                        $badgeColor = $roleColors[$user['user_role']] ?? 'secondary'; // default jika role tidak dikenal
                                        ?>
                                        <span class="badge bg-<?= $badgeColor ?>">
                                            <?= ucfirst($user['user_role']); ?>
                                        </span>

                                    </td>
                                    <td><?php echo $user['warehouse_name']; ?></td>
                                    <td><?php echo date('d-m-Y H:i:s', strtotime($user['create_at'])); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('user/edit/' . $user['user_id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                            data-id="<?php echo $user['user_id']; ?>"
                                            data-name="<?php echo $user['full_name']; ?>"
                                            data-url="<?= site_url('user/delete'); ?>">
                                            <i class="fas fa-trash"></i>
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