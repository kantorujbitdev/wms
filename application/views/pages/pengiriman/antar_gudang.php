<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter"></i> Filter Data
                </h6>
                <a href="<?= site_url('pengiriman/add_antar_gudang') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    Tambah Pengiriman
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="get" action="<?= site_url('pengiriman/antar_gudang') ?>" id="filterForm">
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
                        <a href="<?= site_url('pengiriman/reset_filter/antar_gudang') ?>"
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
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman Antar Gudang
                    <?php if (!empty($pengiriman_list)): ?>
                        <span class="badge text-primary ml-2">
                            <?= count($pengiriman_list) ?> Data
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
            <?php if (empty($pengiriman_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data Pengiriman Antar Gudang untuk periode
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?> -
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pengiriman</th>
                                <th>Tanggal</th>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <!-- <th>Referensi</th> -->
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <!-- Dalam loop pengiriman -->
                            <?php foreach ($pengiriman_list as $pengiriman): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $pengiriman['stockout_code'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($pengiriman['stockout_date'])) ?></td>
                                    <td><?= $pengiriman['warehouse_name'] ?></td>
                                    <td><?= $pengiriman['to_name'] ? $pengiriman['to_name'] : ($pengiriman['to_id'] ?? '-') ?>
                                    </td>
                                    <!-- <td>-</td> stockout_invoice tidak ada -->
                                    <td><?= $pengiriman['stockout_note'] ?></td>
                                    <td><?= $pengiriman['user_name'] ?></td>
                                    <td>
                                        <a href="<?= site_url('pengiriman/detail/' . $pengiriman['stockout_id']) ?>"
                                            class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if (has_permission('pengiriman', 'edit')): ?>
                                            <?php if (isset($pengiriman['is_cetak']) && $pengiriman['is_cetak'] < 2): ?>
                                                <?php if ($pengiriman['on_transfer_status'] == 1): ?>
                                                    <a href="<?= site_url('pengiriman/edit/' . $pengiriman['stockout_id']) ?>"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (has_permission('pengiriman', 'delete')): ?>
                                            <?php if (isset($pengiriman['is_cetak']) && $pengiriman['is_cetak'] < 2): ?>
                                                <?php if ($pengiriman['on_transfer_status'] == 1): ?>
                                                    <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
                                                        data-id="<?= $pengiriman['stockout_id']; ?>"
                                                        data-name="<?= $pengiriman['stockout_code']; ?>"
                                                        data-url="<?= site_url('pengiriman/delete'); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
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
<?php $this->load->view('style/script_list'); ?>