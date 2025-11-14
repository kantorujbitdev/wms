<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['gudang']; ?></h1>
        <a href="<?php echo site_url('gudang/add'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> <?= $wording['gudang_add']; ?>
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['gudang_list']; ?></h6>
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
                                    <td><?php echo $warehouse['Warehouse_Code']; ?></td>
                                    <td><?php echo $warehouse['Warehouse_Name']; ?></td>
                                    <td><?php echo $warehouse['Warehouse_Address']; ?></td>
                                    <td><?php echo $warehouse['Contact_Person']; ?></td>
                                    <td><?php echo !empty($warehouse['Phone']) ? $warehouse['Phone'] : '-'; ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('gudang/edit/' . $warehouse['Warehouse_Id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('gudang/stock/' . $warehouse['Warehouse_Id']); ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                            data-id="<?php echo $warehouse['Warehouse_Id']; ?>"
                                            data-name="<?php echo $warehouse['Warehouse_Name']; ?>"
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