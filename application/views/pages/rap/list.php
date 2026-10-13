<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['rap_list']; ?>
                </h6>

                <a href="<?= site_url('rap/add') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $wording['rap_add']; ?>
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($raps)): ?>
                <div class="alert alert-info">
                    Tidak ada data RAP.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Nama RAP</th>
                                <th>Nomor Kontrak</th>
                                <th>Nama Proyek</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($raps as $rap): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $rap['rap_name']; ?></td>
                                    <td><?php echo $rap['contract_number']; ?></td>
                                    <td><?php echo $rap['warehouse_name']; ?></td>
                                    <td class="text-center">
                                        <?php if (has_permission('rap', 'edit')): ?>
                                            <a href="<?php echo site_url('rap/edit/' . $rap['rap_id']); ?>"
                                                class="btn btn-info btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (has_permission('rap', 'delete')): ?>
                                            <?php if ($rap['rap_status'] == 0): ?>
                                                <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
                                                    data-id="<?php echo $rap['rap_id']; ?>" data-name="<?php echo $rap['rap_name']; ?>"
                                                    data-url="<?= site_url('rap/delete'); ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>