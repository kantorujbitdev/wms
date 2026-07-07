<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<style>
    .select2-container--bootstrap-5 .select2-search--dropdown {
        display: block !important;
        padding: 6px;
    }

    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 6px 10px !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        z-index: 9999;
    }
</style>

<div class="container-fluid">

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan Stok</h6>
        </div>
        <div class="card-body">
            <div class="row">

                <?php if ($user_role === 'superadmin'): ?>
                    <!-- Superadmin: dropdown gudang -->
                    <div class="col-md-4 mb-3">
                        <label for="warehouse_filter" class="form-label">Gudang</label>
                        <select id="warehouse_filter" class="form-control" name="warehouse_filter">
                            <option value="">Semua Gudang</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w['warehouse_id'] ?>">
                                    <?= htmlspecialchars($w['warehouse_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <!-- User gudang: tampilkan nama gudang saja, value tersimpan di hidden -->
                    <input type="hidden" id="warehouse_filter" value="<?= $user_warehouse_id ?>">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gudang</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user_warehouse_name ?? '') ?>"
                            disabled>
                    </div>
                <?php endif; ?>

                <!-- Dropdown Produk -->
                <div class="col-md-4 mb-3">
                    <label for="product_filter" class="form-label">Nama Barang</label>
                    <select id="product_filter" class="form-control" name="product_filter">
                        <option value="">Semua Produk</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['product_id'] ?>">
                                <?= htmlspecialchars($p['product_code'] . ' - ' . $p['product_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol Reset -->
                <div class="col-md-4 mb-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="button" id="resetFilter" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset Filter
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Empty state -->
    <?php if ($user_role === 'superadmin'): ?>
        <div id="emptyFilterState">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Silakan pilih gudang atau produk untuk menampilkan data stok.
            </div>
        </div>
    <?php endif; ?>

    <!-- Tabel Card -->
    <div id="tableCard" class="card shadow mb-4" style="<?= $user_role === 'superadmin' ? 'display:none;' : '' ?>">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Stok Barang</h6>
                <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm mt-2 mt-md-0">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="stokLaporanTable" width="100%" cellspacing="0">
                    <thead class="thead-light text-center align-middle">
                        <tr>
                            <th width="40">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Gudang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="stokTableBody">
                        <!-- Diisi via AJAX -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right font-weight-bold" id="totalStok">0</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {

        // =========================================================
        // Konstanta dari PHP
        // =========================================================
        const userRole = '<?= $user_role ?>';
        const userWarehouseId = '<?= $user_warehouse_id ?>';

        // =========================================================
        // Helper: format angka
        // =========================================================
        function viewNumber(value) {
            let num = parseFloat(value || 0).toFixed(2);
            num = num.replace('.', ',');
            num = num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            num = num.replace(/,00$/, '');
            num = num.replace(/,(\d*[1-9])0+$/, ',$1');
            return num;
        }

        // =========================================================
        // DataTables — destroy/rebuild pattern (sama seperti stok gudang)
        // =========================================================
        let stokLaporanDT = null;

        function destroyDT() {
            if ($.fn.dataTable.isDataTable('#stokLaporanTable')) {
                $('#stokLaporanTable').DataTable().destroy();
            }
            stokLaporanDT = null;
        }

        function initDT() {
            destroyDT();
            stokLaporanDT = $('#stokLaporanTable').DataTable({
                language: {
                    emptyTable: 'Tidak ada data stok untuk filter yang dipilih',
                    zeroRecords: 'Tidak ada data yang cocok dengan pencarian',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    paginate: {
                        first: 'Pertama', last: 'Terakhir',
                        next: 'Berikutnya', previous: 'Sebelumnya'
                    }
                },
                columnDefs: [{ orderable: false, targets: 0 }],
                order: [[1, 'asc']],
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                responsive: true,
                drawCallback: function () {
                    let api = this.api();
                    let start = api.page.info().start;
                    api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                }
            });
        }

        // =========================================================
        // Inisialisasi Select2
        // =========================================================
        const select2Config = {
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0,
            dropdownAutoWidth: true,
            dropdownParent: $('body')
        };

        if ($('#warehouse_filter').is('select')) {
            $('#warehouse_filter').select2({ ...select2Config, placeholder: 'Semua Gudang' });
        }

        $('#product_filter').select2({ ...select2Config, placeholder: 'Semua Produk' });

        // =========================================================
        // State references
        // =========================================================
        const $emptyState = $('#emptyFilterState');
        const $tableCard = $('#tableCard');
        const $tableBody = $('#stokTableBody');
        const $totalStok = $('#totalStok');
        const $overlay = $('#loadingOverlay');

        // Simpan filter aktif untuk dipakai saat export
        let activeFilter = { warehouse_id: '', product_id: '' };

        // =========================================================
        // Render baris status badge
        // =========================================================
        function getStatusBadge(currentStock) {
            if (currentStock <= 0) {
                return '<span class="badge bg-danger">Kosong</span>';
            } else {
                return '<span class="badge bg-success">Normal</span>';
            }
        }

        function getStokClass(currentStock) {
            return currentStock <= 0 ? 'text-danger font-weight-bold' : '';
        }

        // =========================================================
        // loadStokData — inti filter AJAX
        // =========================================================
        function loadStokData() {
            const warehouseId = $('#warehouse_filter').val() || '';
            const productId = $('#product_filter').val() || '';

            // Superadmin tanpa filter → tampilkan empty state
            if (userRole === 'superadmin' && warehouseId === '' && productId === '') {
                destroyDT();
                $emptyState.show();
                $tableCard.hide();
                activeFilter = { warehouse_id: '', product_id: '' };
                return;
            }

            $overlay.show();
            $emptyState.hide();
            $tableCard.show();

            // Simpan filter aktif untuk export
            activeFilter = { warehouse_id: warehouseId, product_id: productId };

            $.ajax({
                url: '<?= site_url("laporan/get_stok_ajax") ?>',
                type: 'POST',
                data: { warehouse_id: warehouseId, product_id: productId },
                dataType: 'json',
                success: function (res) {
                    $overlay.hide();

                    // Step 1: destroy sebelum sentuh tbody
                    destroyDT();

                    if (res.empty || !res.success || !res.data || res.data.length === 0) {
                        // Tbody kosong — DataTables tampilkan emptyTable
                        $tableBody.empty();
                        $totalStok.text('0');
                    } else {
                        let rows = '';
                        let total = 0;

                        $.each(res.data, function (index, stok) {
                            const stock = parseFloat(stok.current_stock || 0);
                            const stokClass = getStokClass(stock);
                            total += stock;

                            rows += `<tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${stok.product_code || '-'}</td>
                            <td>${stok.product_name || '-'}</td>
                            <td>${stok.type_name || '-'}</td>
                            <td class="text-center">${stok.unit_code || '-'}</td>
                            <td class="text-right ${stokClass}">${viewNumber(stock)}</td>
                            <td>${stok.warehouse_name || '-'}</td>
                            <td class="text-center">${getStatusBadge(stock)}</td>
                            <td>${stok.product_note || '-'}</td>
                        </tr>`;
                        });

                        $tableBody.html(rows);
                        $totalStok.text(viewNumber(total));
                    }

                    // Step 2: init DataTables
                    initDT();
                },
                error: function (xhr, status, error) {
                    $overlay.hide();
                    console.error('AJAX Error:', error);
                    toastr.error('Terjadi kesalahan saat memuat data stok', 'Error');
                }
            });
        }

        // =========================================================
        // Events: filter berubah
        // =========================================================
        $('#warehouse_filter').on('change', function () { loadStokData(); });
        $('#product_filter').on('change', function () { loadStokData(); });

        // =========================================================
        // Reset filter
        // =========================================================
        $('#resetFilter').on('click', function () {
            if ($('#warehouse_filter').is('select')) {
                $('#warehouse_filter').val('').trigger('change');
            }
            $('#product_filter').val('').trigger('change');
            // loadStokData() akan dipanggil otomatis oleh event 'change' di atas
        });

        // =========================================================
        // Export Excel — kirim filter aktif ke endpoint export
        // =========================================================
        $('#exportExcelBtn').on('click', function () {
            const params = new URLSearchParams({
                warehouse_id: activeFilter.warehouse_id,
                product_id: activeFilter.product_id
            });
            window.location.href = '<?= site_url("laporan/export_stok") ?>?' + params.toString();
        });

        // =========================================================
        // Auto-load untuk user non-superadmin (warehouse sudah dari session)
        // =========================================================
        if (userRole !== 'superadmin') {
            loadStokData();
        }

    });
</script>