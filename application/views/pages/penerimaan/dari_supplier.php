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
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <form method="GET" action="<?= site_url('penerimaan/dari_supplier') ?>" class="form-inline">
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
                                    <a href="<?= site_url('penerimaan/dari_supplier') ?>" class="btn btn-secondary">
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
                <h6 class="m-0 font-weight-bold text-primary">
                    Daftar Penerimaan dari Supplier
                    <?php if (!empty($penerimaan_list)): ?>
                        <span class="badge badge-primary ml-2"><?= count($penerimaan_list) ?> Data</span>
                    <?php endif; ?>
                </h6>
                <small class="text-muted">
                    Periode: <?= date('d/m/Y', strtotime($filter_start_date)) ?> -
                    <?= date('d/m/Y', strtotime($filter_end_date)) ?>
                </small>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($penerimaan_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Tidak ada data Penerimaan dari Supplier untuk periode
                    <?= date('d/m/Y', strtotime($filter_start_date)) ?> - <?= date('d/m/Y', strtotime($filter_end_date)) ?>.
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
                                <th>No Invoice</th>
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
                                        <td><?= date('d/m/Y', strtotime($penerimaan['stockin_date'] ?? date('Y-m-d'))); ?></td>
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
                                            <?php if (has_permission('penerimaan', 'edit')): ?>
                                                <a href="<?= site_url('penerimaan/edit/' . $penerimaan['stockin_id']) ?>"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (has_permission('penerimaan', 'delete')): ?>
                                                <button type="button" class="btn btn-danger btn-sm actionBtnDelete" title="Hapus"
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
                                    <td colspan="9" class="text-center">Tidak ada data penerimaan dari supplier</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Tambahkan CSS dan JS Flatpickr di head -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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