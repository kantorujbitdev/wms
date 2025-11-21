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
                            <th>Nama Gudang</th>
                            <th>Nama Barang</th>
                            <th>Tipe Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <!-- "warehouse_id": "7",
            "warehouse_name": "Project Cisumdawu",
            "product_id": "2",
            "product_code": "MH0002",
            "product_name": "Besi ukuran 8\"",
            "type_id": "2",
            "type_code": "MH",
            "type_name": "Material HSSE",
            "unit_id": "13",
            "unit_code": "BTG",
            "stock_id": "3",
            "current_stock": "50.00",
            "mrl_stock": ".00",
            "rap_stock": ".00",
            "inuse_stock": ".00",
            "damage_stock": ".00",
            "starting_stock": "50.00",
            "before_adjust_stock": ".00",
            "diff_adjust_stock": ".00" -->
                    <tbody>
                        <?php if (!empty($stoks)): ?>
                            <?php $no = 1;
                            foreach ($stoks as $stok): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $stok['warehouse_name']; ?></td>
                                    <td><?php echo $stok['product_name']; ?></td>
                                    <td><?php echo $stok['type_name']; ?></td>
                                    <td><?php echo $stok['current_stock']; ?></td>

                                    <td class="text-center">
                                        <a href="<?php echo site_url('stok/edit/' . $stok['warehouse_id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('stok/stock/' . $stok['warehouse_id']); ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <?php if (is_role(['superadmin', 'admin'])): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?php echo $stok['warehouse_id']; ?>"
                                                data-name="<?php echo $stok['warehouse_name']; ?>"
                                                data-url="<?= site_url('stok/delete'); ?>">
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