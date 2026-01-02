<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['gudang']; ?></h1>

    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['gudang_list']; ?>
                </h6>

                <a href="<?= site_url('gudang/add') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $wording['gudang_add']; ?>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Nama Kontak</th>
                            <th>Nomor Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($warehouses)): ?>
                            <?php $no = 1;
                            foreach ($warehouses as $warehouse): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $warehouse['warehouse_code']; ?></td>
                                    <td><?php echo $warehouse['warehouse_name']; ?></td>
                                    <td><?php echo $warehouse['warehouse_address']; ?></td>
                                    <td><?php echo $warehouse['contact_person']; ?></td>
                                    <td><?php echo !empty($warehouse['phone']) ? $warehouse['phone'] : '-'; ?></td>
                                    <td class="text-center">
                                        <?php if ($warehouse['warehouse_status'] == 0): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (has_permission('gudang', 'edit')): ?>
                                            <a href="<?php echo site_url('gudang/edit/' . $warehouse['warehouse_id']); ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (has_permission('gudang', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?php echo $warehouse['warehouse_id']; ?>"
                                                data-name="<?php echo $warehouse['warehouse_name']; ?>"
                                                data-url="<?= site_url('gudang/delete'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data gudang</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>