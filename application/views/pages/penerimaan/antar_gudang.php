<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Penerimaan Antar Gudang</h1>
        <a href="<?= site_url('penerimaan/add_antar_gudang') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            Tambah Penerimaan
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penerimaan Antar Gudang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Kode Penerimaan</th>
                            <th>Tanggal</th>
                            <th>Dari</th>
                            <th>Ke Gudang</th>
                            <th>No Referensi</th>
                            <th>Keterangan</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($penerimaan_list)): ?>
                            <?php $no = 1;
                            foreach ($penerimaan_list as $penerimaan): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $penerimaan['stockin_code'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['stockin_date'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['from_name'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['warehouse_name'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['stockin_invoice'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['stockin_note'] ?? '-'; ?></td>
                                    <td><?= $penerimaan['createby_name'] ?? '-'; ?></td>
                                    <td class="text-center">
                                        <?php if (has_permission('penerimaan')): ?>
                                            <a href="<?= site_url('penerimaan/detail/' . $penerimaan['stockin_id']) ?>"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (has_permission('penerimaan', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?= $penerimaan['stockin_id']; ?>"
                                                data-name="<?= $penerimaan['stockin_code']; ?>"
                                                data-url="<?= site_url('penerimaan/delete'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data penerimaan antar gudang</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>