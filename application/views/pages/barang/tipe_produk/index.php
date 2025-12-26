<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['tipe_Barang']; ?></h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['barang_tipe_list']; ?>
                </h6>

                <?php if (has_permission('tipe_produk', 'edit')): ?>
                    <a href="<?= site_url('barang/add_tipe_produk') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        <?= $wording['barang_tipe_add']; ?>
                    </a>
                <?php endif; ?>

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
                            <?php if (has_permission('tipe_produk', 'edit')): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product_types)): ?>
                            <?php $no = 1;
                            foreach ($product_types as $type): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $type['Product_Type_Code']; ?></td>
                                    <td><?php echo !empty($type['Product_Type_Name']) ? $type['Product_Type_Name'] : '-'; ?>
                                    </td>
                                    <?php if (has_permission('tipe_produk', 'edit')): ?>
                                        <td class="text-center">
                                            <a href="<?php echo site_url('barang/edit_tipe_produk/' . $type['Product_Type_Id']); ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (has_permission('tipe_produk', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?php echo $type['Product_Type_Id']; ?>" data-name="<?php echo '<br>Kode : ' . $type['Product_Type_Code'] .
                                                       '<br>Nama : ' . $type['Product_Type_Name']; ?>"
                                                data-url="<?= site_url('barang/delete_tipe_produk'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data tipe produk</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>