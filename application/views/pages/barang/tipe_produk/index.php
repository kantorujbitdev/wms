<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tipe Produk</h1>
        <a href="<?php echo site_url('barang/add_tipe_produk'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Tipe Produk
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tipe Produk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Nama Tipe Produk</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
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
                                    <td class="text-center">
                                        <a href="<?php echo site_url('barang/edit_tipe_produk/' . $type['Product_Type_Id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?php echo $type['Product_Type_Id']; ?>"
                                            data-name="<?php echo $type['Product_Type_Name']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<script>
    $(document).ready(function () {
        $('.delete-btn').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            showConfirmationModal({
                title: 'Konfirmasi Hapus',
                message: `Apakah Anda yakin ingin menghapus tipe produk "${name}"?`,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                confirmUrl: `<?= site_url('barang/delete_tipe_produk') ?>/${id}`
            });
        });
    });
</script>