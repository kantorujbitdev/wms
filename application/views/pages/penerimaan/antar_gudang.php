<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter"></i> Filter Data
                </h6>
                <a href="<?= site_url('penerimaan/add_antar_gudang') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    Tambah Penerimaan
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('penerimaan/antar_gudang') ?>" id="filterForm">
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
                        <a href="<?= site_url('penerimaan/reset_filter/antar_gudang') ?>"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Daftar Penerimaan Antar Gudang
                    <?php if (!empty($penerimaan_list)): ?>
                        <span class="badge text-primary ml-2">
                            <?= count($penerimaan_list) ?> Data
                        </span>
                    <?php endif; ?>
                </h6>
                <small class="text-muted">
                    Periode:
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?> -
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>
                </small>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($penerimaan_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data Penerimaan Antar Gudang untuk periode
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?> -
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Kode Penerimaan</th>
                                <th>Tanggal</th>
                                <th>Asal</th>
                                <th>Tujuan</th>
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
                                        <td><?= $penerimaan['user_name'] ?? '-'; ?></td>
                                        <td class="text-center">
                                            <?php if (has_permission('penerimaan')): ?>
                                                <a href="<?= site_url('penerimaan/detail/' . $penerimaan['stockin_id']) ?>"
                                                    class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            <!-- <?php if (has_permission('penerimaan', 'edit')): ?>
                                <a href="<?= site_url('penerimaan/edit/' . $penerimaan['stockin_id']) ?>"
                                    class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php endif; ?>

                                <?php if (has_permission('penerimaan', 'delete')): ?>
                                <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                    data-id="<?= $penerimaan['stockin_id']; ?>"
                                    data-name="<?= $penerimaan['stockin_code']; ?>"
                                    data-url="<?= site_url('penerimaan/delete'); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?> -->
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->load->view('style/script_list'); ?>