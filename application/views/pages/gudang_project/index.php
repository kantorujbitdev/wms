<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['gudang_project']; ?></h1>
        <a href="<?php echo site_url('gudang/add'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> <?= $wording['gudang_add_project']; ?>
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['gudang_list_project']; ?></h6>
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
                            <th>Contact Person</th>
                            <th>Telepon</th>
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
                                    <td><?php echo !empty($warehouse['Phone']) ? $warehouse['Phone'] : '-'; ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('gudang/edit_gudang_project/' . $warehouse['warehouse_id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('gudang/stock/' . $warehouse['warehouse_id']); ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <!-- "warehouse_id": "1",
                                        "warehouse_code": "UJB-SE",
                                        "warehouse_name": "Gudang Logistik - UJB Office",
                                        "warehouse_address": "abd",
                                        "contact_person": "Bp. Agus",
                                        "phone": "082201827612",
                                        "warehouse_status": "0",
                                        "warehouse_status_name": "Aktif",
                                        "warehouse_type": "0",
                                        "warehouse_type_name": "Gudang Utama" -->
                                        <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                            data-id="<?php echo $warehouse['warehouse_id']; ?>"
                                            data-name="<?php echo $warehouse['warehouse_name']; ?>"
                                            data-url="<?= site_url('gudang/delete'); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data gudang</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>