<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['stok_list']; ?>
                </h6>

                <!-- add stock -->
                <?php $usernames = strtolower($this->session->userdata('username')); ?>

                <?php if ($usernames == 'adminwms'): ?><a href="<?= site_url('gudang_stok/add') ?>"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        <?= $wording['stok_add']; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">

                <?php if (empty($this->session->userdata('warehouse_id'))): ?>
                    <!-- Tampilkan dropdown jika user BUKAN user gudang -->
                    <div class="col-md-4">
                        <label>Pilih Gudang:</label>
                        <select id="warehouse_filter" class="form-control">
                            <option value="">Semua Gudang</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w['warehouse_id']; ?>">
                                    <?= $w['warehouse_name'] . ' || ' . $w['warehouse_code']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                <?php else: ?>
                    <!-- Jika user sudah punya warehouse_id → simpan ke input hidden -->
                    <input type="hidden" id="warehouse_filter" value="<?= $this->session->userdata('warehouse_id'); ?>">

                    <div class="col-md-4">
                        <label>Gudang:</label>
                        <input type="text" class="form-control" value="<?= $this->session->userdata('warehouse_name'); ?>"
                            disabled>
                    </div>
                <?php endif; ?>

            </div>

            <?php if (empty($stoks)): ?>
                <div class="alert alert-info">
                    Tidak ada data Penerimaan dari Supplier.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Nama Gudang</th>
                                <th>ID BOS</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Tipe Barang</th>
                                <th>Stok Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stoks)): ?>
                                <?php $no = 1;
                                foreach ($stoks as $stok): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $stok['warehouse_name']; ?></td>
                                        <td><?= $stok['bos_code']; ?></td>
                                        <td><?= $stok['product_code']; ?></td>
                                        <td><?= $stok['product_name']; ?></td>
                                        <td><?= $stok['type_name']; ?></td>
                                        <td><?= viewNumber($stok['current_stock']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data stok</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function viewNumber(value) {
            let num = parseFloat(value || 0).toFixed(2);
            num = num.replace('.', ',');
            num = num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            num = num.replace(/,00$/, '');
            num = num.replace(/,(\d*[1-9])0+$/, ',$1');
            return num;
        }

        // Inisialisasi sudah dilakukan oleh main.js, kita cukup ambil instance-nya
        let table = getDataTableInstance();

        // Jika table belum diinisialisasi, inisialisasi manual
        if (!table) {
            table = initializeDataTables();
        }

        // Filter warehouse dengan AJAX
        $("#warehouse_filter").change(function () {
            let warehouse_id = $(this).val();

            $.ajax({
                url: "<?= site_url('gudang_stok/get_stock_by_warehouse'); ?>",
                type: "POST",
                data: { warehouse_id: warehouse_id },
                dataType: "json",
                success: function (res) {
                    if (res.success && res.data.length > 0) {
                        // Format data untuk DataTables
                        const newData = res.data.map(function (stok, index) {
                            $view_stok = viewNumber(stok.current_stock);
                            return [
                                `<span class="text-center d-block">${index + 1}</span>`,
                                stok.warehouse_name || '-',
                                stok.bos_code || '-',
                                stok.product_code || '-',
                                stok.product_name || '-',
                                stok.type_name || '-',
                                $view_stok
                            ];
                        });

                        // Refresh table dengan data baru
                        refreshDataTable(newData);
                    } else {
                        // Jika tidak ada data, tampilkan pesan
                        showDataTableEmptyState('Tidak ada data stok untuk gudang yang dipilih');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data');
                }
            });
        });
    });
</script>