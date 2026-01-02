<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['customer']; ?></h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['customer_list']; ?>
                </h6>

                <a href="<?= site_url('customer/add') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $wording['customer_add']; ?>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php $no = 1;
                            foreach ($customers as $customer): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $customer['name']; ?></td>
                                    <td><?php echo $customer['person']; ?></td>
                                    <td><?php echo $customer['phone']; ?></td>
                                    <td><?php echo $customer['address']; ?></td>
                                    <td class="text-center">
                                        <?php if (has_permission('customer', 'edit')): ?>
                                            <a href="<?php echo site_url('customer/edit/' . $customer['id']); ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (has_permission('customer', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?php echo $customer['id']; ?>"
                                                data-name="<?php echo $customer['name']; ?>"
                                                data-url="<?= site_url('customer/delete'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data customer</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>