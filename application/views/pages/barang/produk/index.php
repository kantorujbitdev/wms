<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $wording['barang']; ?></h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['barang_list']; ?>
                </h6>

                <a href="<?= site_url('barang/add_produk') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    <?= $wording['barang_add']; ?>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>ID BOS</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php $no = 1;
                            foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $product['bos_code']; ?></td>
                                    <td><?php echo $product['product_code']; ?></td>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td><?php echo $product['unit_code']; ?></td>
                                    <td><?php echo $product['type_name']; ?></td>
                                    <td class="text-center">
                                        <?php if (has_permission('barang', 'delete')): ?>
                                            <a href="<?php echo site_url('barang/edit_produk/' . $product['product_id']); ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (has_permission('barang', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?php echo $product['product_id']; ?>" data-name="<?php echo '<br>Kode : ' . $product['product_code'] .
                                                       '<br>Barang : ' . $product['product_name']; ?>"
                                                data-url="<?= site_url('barang/delete_produk'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data produk</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>