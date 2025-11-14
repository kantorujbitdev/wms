<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['supplier']; ?></h1>
        <a href="<?php echo site_url('supplier/add'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> <?= $wording['supplier_add']; ?>
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $wording['supplier_list']; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="supplierTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>Nama</th>
                            <th>Contact</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $supplier): ?>
                                <tr>
                                    <td><?php echo $supplier['Name']; ?></td>
                                    <td><?php echo $supplier['Contact']; ?></td>
                                    <td><?php echo $supplier['Phone']; ?></td>
                                    <td><?php echo $supplier['Addr']; ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('supplier/edit/' . $supplier['id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                            data-id="<?php echo $supplier['id']; ?>"
                                            data-name="<?php echo $supplier['Name']; ?>"
                                            data-url="<?= site_url('supplier/delete'); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data supplier</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>