<div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <a href="<?= site_url('pengiriman/add_pengguna') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengiriman
        </a>
    </div> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman ke Pengguna</h6>
                <a href="<?= site_url('pengiriman/add_pengguna') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengiriman
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($pengiriman_list)): ?>
                <div class="alert alert-info">
                    Tidak ada data Pengiriman ke Pengguna.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pengiriman</th>
                                <th>Tanggal</th>
                                <th>Dari Gudang</th>
                                <th>Ke Pengguna</th>
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
                                    <td><?= $pengiriman['stockout_note'] ?: '-' ?></td>
                                    <td><?= $pengiriman['user_name'] ?></td>
                                    <td>
                                        <a href="<?= site_url('pengiriman/detail/' . $pengiriman['stockout_id']) ?>"
                                            class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if (has_permission('pengiriman', 'edit')): ?>
                                            <a href="<?= site_url('pengiriman/edit/' . $pengiriman['stockout_id']) ?>"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (has_permission('pengiriman', 'delete')): ?>
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete"
                                                data-id="<?= $pengiriman['stockout_id']; ?>"
                                                data-name="<?= $pengiriman['stockout_code']; ?>"
                                                data-url="<?= site_url('pengiriman/delete'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
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