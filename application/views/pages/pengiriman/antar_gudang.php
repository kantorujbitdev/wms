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
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <form method="GET" action="<?= site_url('pengiriman/antar_gudang') ?>" class="form-inline">
                    <div class="row g-3 align-items-end">

                        <!-- Tanggal Mulai -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <div class="input-group">
                                    <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                                        placeholder="dd/mm/yyyy"
                                        value="<?= isset($filter_start_date) ? date('d/m/Y', strtotime($filter_start_date)) : '' ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Akhir -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Tanggal Akhir</label>
                                <div class="input-group">
                                    <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                                        placeholder="dd/mm/yyyy"
                                        value="<?= isset($filter_end_date) ? date('d/m/Y', strtotime($filter_end_date)) : '' ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="col-auto">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tampilkan
                                    </button>
                                    <a href="<?= site_url('pengiriman/reset_filter/antar_gudang') ?>" class="btn btn-secondary">
                                        <i class="fas fa-sync"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman Antar Gudang
                    <?php if (!empty($pengiriman_list)): ?>
                        <span class="badge badge-primary ml-2">
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
                                            <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
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

<!-- Tambahkan CSS dan JS Flatpickr di head -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Flatpickr
        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1 // Senin sebagai hari pertama
            },
            position: "below", // Paksa posisi di bawah
            positionElement: null,
            static: true, // Membuat kalender tetap di posisi relatif terhadap input
            onChange: function (selectedDates, dateStr, instance) {
                // Validasi tanggal
                var currentInput = instance.element;
                var startDate = document.getElementById('start_date');
                var endDate = document.getElementById('end_date');

                if (currentInput.id === 'start_date' && endDate.value) {
                    var start = selectedDates[0];
                    var endParts = endDate.value.split('/');
                    var end = new Date(endParts[2], endParts[1] - 1, endParts[0]);

                    if (start > end) {
                        $('#errorMessage').text('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                        $('#errorModal').modal('show');

                        return false;
                    }
                } else if (currentInput.id === 'end_date' && startDate.value) {
                    var startParts = startDate.value.split('/');
                    var start = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                    var end = selectedDates[0];

                    if (end < start) {
                        $('#errorMessage').text('Tanggal akhir tidak boleh lebih kecil dari tanggal awal');
                        $('#errorModal').modal('show');
                        return false;
                    }
                }
            }
        });
    });
</script>