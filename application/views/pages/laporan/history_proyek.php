<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Daftar</h6>
        </div>

        <div class="card-body">
            <!-- Filter Form -->
            <form method="get" action="<?= site_url('laporan/history_proyek') ?>" id="filterForm" class="mb-4">
                <div class="row">
                    <!-- Filter Warehouse -->
                    <div class="col-md-3 mb-3">
                        <label for="warehouse_id" class="form-label">Gudang Asal</label>
                        <select name="warehouse_id" id="warehouse_id" class="form-control">
                            <option value="all">Semua Gudang</option>
                            <?php if (!empty($warehouse_list)): ?>
                                <?php foreach ($warehouse_list as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>" <?= isset($filter_warehouse_id) && $filter_warehouse_id == $warehouse['warehouse_id'] ? 'selected' : '' ?>>
                                        <?= $warehouse['warehouse_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>


                    <!-- Tanggal Mulai -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr" id="start_date" name="start_date"
                                    placeholder="dd/mm/yyyy"
                                    value="<?= isset($filter_date_start) ? date('d/m/Y', strtotime($filter_date_start)) : date('d/m/Y') ?>"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Akhir -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr" id="end_date" name="end_date"
                                    placeholder="dd/mm/yyyy"
                                    value="<?= isset($filter_end_date) ? date('d/m/Y', strtotime($filter_end_date)) : date('d/m/Y') ?>"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="warehouseDetailContainer">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Silakan pilih gudang terlebih dahulu untuk melihat Histori Proyek.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="<?= site_url('laporan/history_proyek') ?>" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if ($is_filtered): ?>
        <?php

        $total_transaksi = count($pengiriman_list);

        $total_masuk = 0;
        $total_keluar = 0;
        $produk_unik = [];

        foreach ($pengiriman_list as $row) {

            $produk_unik[$row['product_code']] = true;

            if ($row['transaction_type'] == 'Masuk') {
                $total_masuk += (float) $row['qty'];
            } else {
                $total_keluar += (float) $row['qty'];
            }
        }

        $total_produk = count($produk_unik);

        ?>
        <div class="row mb-3">

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Transaksi
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($total_transaksi) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Barang Masuk
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-success">
                            <?= number_format($total_masuk) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-danger shadow h-100">
                    <div class="card-body text-center">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total Barang Keluar
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-danger">
                            <?= number_format($total_keluar) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" col-xl-3 col-md-6 mb-3">
                <div class="card border-left-warning shadow h-100">
                    <div class=" card-body text-center">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Jumlah Barang
                        </div>

                        <div class="h4 mb-0 font-weight-bold text-warning">
                            <?= number_format($total_produk) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Stock Card Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Histori Proyek</h6>
                </div>
            </div>

            <div class="card-body">

                <!-- Results -->
                <?php if (empty($pengiriman_list)): ?>
                    <div class="alert alert-info">
                        Tidak ada data Histori Proyek.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="100">Tanggal</th>
                                    <th width="180">Nomor Surat</th>
                                    <th width="100">Kode</th>
                                    <th>Nama Barang</th>
                                    <th width="80">Satuan</th>
                                    <th width="120">Qty Masuk</th>
                                    <th width="120">Qty Keluar</th>
                                    <th width="100">Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($pengiriman_list as $row): ?>
                                    <tr>
                                        <td class="text-center"> <?= $no++ ?> </td>
                                        <td> <?= date('d-m-Y', strtotime($row['transaction_date'])) ?> </td>
                                        <td> <?= $row['transaction_code'] ?> </td>
                                        <td> <strong> <?= $row['product_code'] ?></strong> </td>
                                        <td> <?= $row['product_name'] ?> </td>
                                        <td class="text-center"> <?= $row['unit'] ?> </td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] == 'Masuk'): ?>
                                                <span class="font-weight-bold text-success">
                                                    + <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] == 'Keluar'): ?>
                                                <span class="font-weight-bold text-danger">
                                                    -
                                                    <?= number_format($row['qty']) ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['transaction_type'] == 'Masuk'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-arrow-down"></i>
                                                    Masuk
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-arrow-up"></i>
                                                    Keluar
                                                </span>
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
    <?php endif; ?>
</div>


<!-- Tambahkan CSS dan JS Flatpickr di head -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    const warehouseData = <?= json_encode($warehouse_list); ?>;
</script>

<!-- JavaScript untuk validasi form dan export -->
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const warehouseSelect = document.getElementById('warehouse_id');
        const warehouseContainer = document.getElementById('warehouseDetailContainer');

        function renderWarehouseDetail(warehouseId) {

            if (!warehouseId || warehouseId === 'all') {

                warehouseContainer.style.display = 'block';

                warehouseContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Silakan pilih gudang terlebih dahulu untuk melihat Histori Proyek.
            </div>
        `;
                return;
            }

            const warehouse = warehouseData.find(
                item => item.warehouse_id == warehouseId
            );

            if (!warehouse) return;

            warehouseContainer.style.display = 'block';
            warehouseContainer.innerHTML = `
<div class="card shadow-sm border-left-primary mb-3">
    <div class="card-body py-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
            <div>
                <h5 class="mb-1 text-primary font-weight-bold">
                    <i class="fas fa-warehouse mr-2"></i>
                    ${warehouse.warehouse_name}
                </h5>
                <div class="small text-muted">
                    <span class="mr-3">
                        <i class="fas fa-barcode"></i>
                        ${warehouse.warehouse_code}
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-layer-group"></i>
                        ${warehouse.warehouse_type_name}
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-user"></i>
                        ${warehouse.contact_person || '-'}
                    </span>
                    <span>
                        <i class="fas fa-phone"></i>
                        ${warehouse.phone || '-'}
                    </span>
                </div>
                <div class="small mt-2">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                    ${warehouse.warehouse_address || '-'}
                </div>
            </div>

            <div class="mt-3 mt-lg-0">
                <span class="badge ${warehouse.warehouse_status == '0'
                    ? 'badge-success'
                    : 'badge-danger'
                } px-4 py-2">
                    ${warehouse.warehouse_status == '0'
                    ? '<i class="fas fa-check-circle"></i> Aktif'
                    : '<i class="fas fa-times-circle"></i> Tidak Aktif'
                }
                </span>
            </div>
        </div>
    </div>
</div>
`;
        }

        renderWarehouseDetail(warehouseSelect.value);
        warehouseSelect.addEventListener('change', function () {
            renderWarehouseDetail(this.value);
        });

        // Validasi tanggal
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const filterForm = document.getElementById('filterForm');

        filterForm.addEventListener('submit', function (e) {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);

                if (start > end) {
                    e.preventDefault();
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    startDate.focus();
                    return false;
                }
            }
        });

        // Inisialisasi Flatpickr untuk tanggal
        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1, // Senin sebagai hari pertama
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                }
            },
            onChange: function (selectedDates, dateStr, instance) {
                // Validasi tanggal
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (startDate && endDate) {
                    const startParts = startDate.split('/');
                    const endParts = endDate.split('/');

                    const start = new Date(startParts[2], startParts[1] - 1, startParts[0]);
                    const end = new Date(endParts[2], endParts[1] - 1, endParts[0]);

                    if (start > end) {
                        if (instance.element.id === 'start_date') {
                            alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                            instance.clear();
                        } else {
                            alert('Tanggal akhir tidak boleh lebih kecil dari tanggal awal');
                            instance.clear();
                        }
                    }
                }
            }
        });
        // Export to Excel
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                const filters = new URLSearchParams(window.location.search);
                window.location.href = '<?= site_url("laporan/export_barang_proses") ?>?' + filters.toString();
            });
        }

        // Set max date untuk end_date berdasarkan start_date
        if (startDate) {
            startDate.addEventListener('change', function () {
                if (endDate) {
                    endDate.min = this.value;
                }
            });
        }

        // Set min date untuk start_date berdasarkan end_date
        if (endDate) {
            endDate.addEventListener('change', function () {
                if (startDate) {
                    startDate.max = this.value;
                }
            });
        }
        filterForm.addEventListener('submit', function (e) {
            if (warehouseSelect.value === '' || warehouseSelect.value === 'all') {
                e.preventDefault();
                alert('Silakan pilih gudang terlebih dahulu.');
                warehouseSelect.focus();
                return false;
            }
        });
    });

</script>