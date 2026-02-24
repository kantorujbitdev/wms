<div class="container-fluid">

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
            <div class="row mb-3">
                <!-- Tampilkan dropdown jika user BUKAN user gudang -->
                <div class="col-md-4">
                    <label>Tipe Barang:</label>
                    <select id="tipe_filter" class="form-control">
                        <option value="">Semua Tipe Barang</option>
                        <?php foreach ($product_types as $w): ?>
                            <option value="<?= $w['Product_Type_Id']; ?>">
                                <?= $w['Product_Type_Name'] . ' - ' . $w['Product_Type_Code'] . ' (' . $w['Product_Type_Id'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">
                        Tidak ada data Produk.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-center align-middle">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="8%">ID BOS</th>
                                    <th width="8%">Kode</th>
                                    <th width="48%">Nama Produk</th>
                                    <th width="8%">Satuan</th>
                                    <th width="10%">Tipe</th>
                                    <th width="10%">Aksi</th>
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
                                                        class="btn btn-info btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (has_permission('barang', 'delete')): ?>
                                                    <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        // Ambil instance DataTable
        let table = getDataTableInstance();
        if (!table) {
            table = initializeDataTables();
        }

        // Filter berdasarkan TIPE BARANG
        $("#tipe_filter").on("change", function () {
            let type_id = $(this).val();

            $.ajax({
                url: "<?= site_url('barang/get_barang_by_type'); ?>",
                type: "POST",
                data: { Product_Type_Id: type_id },
                dataType: "json",
                success: function (res) {

                    if (res.success && res.data.length > 0) {

                        const newData = res.data.map(function (item, index) {

                            return [
                                `<div class="text-center">${index + 1}</div>`,
                                item.bos_code ?? '-',
                                item.product_code ?? '-',
                                item.product_name ?? '-',
                                item.unit_code ?? '-',
                                item.type_name ?? '-',
                                `
                                <div class="text-center">
                                    <a href="<?= site_url('barang/edit_produk/'); ?>${item.product_id}"
                                       class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
                                        data-id="${item.product_id}"
                                        data-name="<br>Kode : ${item.product_code}<br>Barang : ${item.product_name}"
                                        data-url="<?= site_url('barang/delete_produk'); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                `
                            ];
                        });

                        refreshDataTable(newData);

                    } else {
                        refreshDataTable([]);
                        showDataTableEmptyState('Tidak ada data produk untuk tipe yang dipilih');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat memuat data');
                }
            });
        });

    });
</script>