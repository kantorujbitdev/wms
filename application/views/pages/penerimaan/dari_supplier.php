<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\dari_supplier.php -->

<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter"></i> Filter Data
                </h6>
                <a href="<?= site_url('penerimaan/add_supplier') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    Tambah Penerimaan
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="get" action="<?= site_url('penerimaan/dari_supplier') ?>" id="filterForm">
                <div class="row align-items-end">

                    <!-- Tanggal Mulai -->
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_start_date) ? date('d/m/Y', strtotime($filter_start_date)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                            placeholder="dd/mm/yyyy"
                            value="<?= isset($filter_end_date) ? date('d/m/Y', strtotime($filter_end_date)) : date('d/m/Y') ?>"
                            autocomplete="off">
                    </div>

                    <!-- Reset Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="<?= site_url('penerimaan/reset_filter/supplier') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Penerimaan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Daftar Penerimaan dari Supplier
                    <?php if (!empty($penerimaan_list)): ?>
                        <span class="badge text-primary ml-2">
                            <?= count($penerimaan_list) ?> Data
                        </span>
                    <?php endif; ?>
                </h6>
                <small class="text-muted">
                    Periode:
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?>
                    &ndash;
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>
                </small>
            </div>
        </div>

        <div class="card-body">
            <?php if (empty($penerimaan_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data Penerimaan dari Supplier untuk periode
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?>
                    &ndash;
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light text-center align-middle">
                            <tr>
                                <th width="40">No</th>
                                <th>Kode Penerimaan</th>
                                <th width="100">Tanggal</th>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <th>No Invoice</th>
                                <th>Keterangan</th>
                                <th>Dibuat</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($penerimaan_list as $penerimaan): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($penerimaan['stockin_code'] ?? '-') ?></td>
                                    <td><?= date('d/m/Y', strtotime($penerimaan['stockin_date'] ?? date('Y-m-d'))) ?></td>
                                    <td><?= htmlspecialchars($penerimaan['from_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($penerimaan['warehouse_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($penerimaan['stockin_invoice'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($penerimaan['stockin_note'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($penerimaan['user_name'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if (has_permission('penerimaan')): ?>
                                            <a href="<?= site_url('penerimaan/detail/' . $penerimaan['stockin_id']) ?>"
                                                class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (has_permission('penerimaan', 'edit')): ?>
                                            <?php if (isset($penerimaan['is_cetak']) && $penerimaan['is_cetak'] < 2): ?>
                                                <a href="<?= site_url('penerimaan/edit/' . $penerimaan['stockin_id']) ?>"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (has_permission('penerimaan', 'delete')): ?>
                                            <?php if (isset($penerimaan['is_cetak']) && $penerimaan['is_cetak'] < 2): ?>
                                                <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
                                                    data-id="<?= $penerimaan['stockin_id'] ?>"
                                                    data-name="<?= htmlspecialchars($penerimaan['stockin_code']) ?>"
                                                    data-url="<?= site_url('penerimaan/delete') ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(255,255,255,0.8); z-index:9999;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
        <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status"></div>
        <h5 class="mt-3 mb-0">Memuat data...</h5>
    </div>
</div>

<?php $this->load->view('style/script_list'); ?>