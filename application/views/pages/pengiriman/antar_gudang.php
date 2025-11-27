<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengiriman Antar Gudang</h1>
        <a href="<?= site_url('pengiriman/add_antar_gudang') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengiriman
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman Antar Gudang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Kode Pengiriman</th>
                            <th>Tanggal</th>
                            <th>Gudang Asal</th>
                            <th>Gudang Tujuan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengiriman_list)): ?>
                            <?php $no = 1;
                            foreach ($pengiriman_list as $pengiriman): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $pengiriman['StockOut_Code'] ?? '-'; ?></td>
                                    <td><?= $pengiriman['StockOut_Date'] ?? '-'; ?></td>
                                    <td><?= $pengiriman['Warehouse_Name'] ?? '-'; ?></td>
                                    <td><?= $pengiriman['To_Warehouse_Name'] ?? '-'; ?></td>
                                    <td><?= $pengiriman['StockOut_Note'] ?? '-'; ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('pengiriman/detail/' . $pengiriman['StockOut_Id']) ?>"
                                            class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <?php if (is_role(['superadmin', 'admin'])): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?= $pengiriman['StockOut_Id']; ?>"
                                                data-name="<?= $pengiriman['StockOut_Code']; ?>"
                                                data-url="<?= site_url('pengiriman/delete'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pengiriman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>