<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<style>
    /* Fix: search box Select2 sering tertimpa CSS template admin */
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
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $wording['stok_list'] ?>
                </h6>

                <?php $usernames = strtolower($this->session->userdata('username')); ?>
                <?php if ($usernames === 'adminwms'): ?>
                    <a href="<?= site_url('gudang_stok/add') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        <?= $wording['stok_add'] ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">

            <!-- Filter Row -->
            <div class="row mb-3">

                <?php if (empty($this->session->userdata('warehouse_id'))): ?>
                    <div class="col-md-4 mb-3">
                        <label for="warehouse_filter" class="form-label">Pilih Proyek</label>
                        <select id="warehouse_filter" class="form-select" name="warehouse_filter">
                            <option value="">Semua Proyek</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w['warehouse_id'] ?>">
                                    <?= htmlspecialchars($w['warehouse_name'] . ' || ' . $w['warehouse_code']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" id="warehouse_filter" value="<?= $this->session->userdata('warehouse_id') ?>">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Proyek</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($this->session->userdata('warehouse_name') ?? '') ?>" disabled>
                    </div>
                <?php endif; ?>

                <div class="col-md-4 mb-3">
                    <label for="product_filter" class="form-label">Nama Barang</label>
                    <select id="product_filter" class="form-select" name="product_filter">
                        <option value="">Semua Produk</option>
                        <?php foreach ($products_list as $product):
                            $display_name = htmlspecialchars(
                                $product['product_code'] . ' - ' . $product['product_name'] . ' || ' . $product['unit_code'],
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                            <option value="<?= $product['product_id'] ?>" <?= (string) $filter_product_id === (string) $product['product_id'] ? 'selected' : '' ?>>
                                <?= $display_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <!-- State: belum ada filter aktif -->
            <div id="emptyFilterState" style="<?= $is_filtered ? 'display:none;' : '' ?>">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Silakan pilih proyek atau barang terlebih dahulu untuk menampilkan data stok.
                </div>
            </div>

            <!-- Tabel Stok
                 id="stokTable" — dikelola manual, bukan oleh main.js.
                 tbody HARUS selalu bersih (kosong atau berisi <tr> normal)
                 saat DataTables diinisialisasi — JANGAN inject colspan row
                 karena DataTables membaca jumlah kolom dari <td> pertama
                 di tbody dan akan error tn/18 jika tidak cocok dengan thead.
                 Pesan "tidak ada data" ditangani via language.emptyTable.
            -->
            <div id="tableContainer" style="<?= !$is_filtered ? 'display:none;' : '' ?>">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="stokTable" width="100%" cellspacing="0">
                        <thead class="thead-light text-center align-middle">
                            <tr>
                                <th width="35">No</th>
                                <th width="230">Nama Proyek</th>
                                <th width="90">ID BOS</th>
                                <th width="100">Kode Barang</th>
                                <th width="260">Nama Barang</th>
                                <th width="110">Tipe Barang</th>
                                <th width="85">Stok Terakhir</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (!empty($stoks)): ?>
                                <?php $no = 1;
                                foreach ($stoks as $stok): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($stok['warehouse_name']) ?></td>
                                        <td>
                                            <?= htmlspecialchars(
                                                ($stok['bos_code'] !== null && $stok['bos_code'] !== '')
                                                ? $stok['bos_code']
                                                : '-'
                                            ) ?>
                                        </td>
                                        <td><?= htmlspecialchars($stok['product_code']) ?></td>
                                        <td><?= htmlspecialchars($stok['product_name']) ?></td>
                                        <td><?= htmlspecialchars($stok['type_name']) ?></td>
                                        <td class="text-right"><?= viewNumber($stok['current_stock']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <!-- JANGAN tambahkan row "tidak ada data" di sini.
                                 Biarkan tbody kosong — DataTables akan tampilkan
                                 language.emptyTable sendiri tanpa error tn/18. -->
                        </tbody>
                    </table>
                </div>
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
        // DataTables
        //
        // Aturan wajib agar tidak error tn/18 & tn/3:
        //   1. Selalu destroyDataTable() sebelum sentuh tbody
        //   2. Isi tbody HANYA dengan <tr> berisi tepat 7 <td>
        //      ATAU biarkan tbody kosong sama sekali
        //   3. JANGAN inject colspan row — serahkan ke language.emptyTable
        //   4. initDataTable() selalu memanggil destroyDataTable() di awal
        // =========================================================
        let stokDT = null;

        function destroyDataTable() {
            if ($.fn.dataTable.isDataTable('#stokTable')) {
                $('#stokTable').DataTable().destroy();
            }
            stokDT = null;
        }

        function initDataTable() {
            destroyDataTable();

            stokDT = $('#stokTable').DataTable({
                language: {
                    // Pesan ini muncul otomatis saat tbody kosong — aman, tidak error tn/18
                    emptyTable: 'Tidak ada data stok untuk filter yang dipilih',
                    zeroRecords: 'Tidak ada data yang cocok dengan pencarian',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: 'Cari:',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    },
                    lengthMenu: 'Tampilkan _MENU_ data'
                },
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
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

        // Inisialisasi awal jika data sudah ada dari server
        <?php if ($is_filtered): ?>
            initDataTable();
        <?php endif; ?>

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
            $('#warehouse_filter').select2({ ...select2Config, placeholder: 'Semua Proyek' });
        }

        $('#product_filter').select2({ ...select2Config, placeholder: 'Semua Produk' });

        // =========================================================
        // State references
        // =========================================================
        const $emptyState = $('#emptyFilterState');
        const $tableContainer = $('#tableContainer');
        const $tableBody = $('#tableBody');
        const $overlay = $('#loadingOverlay');

        // =========================================================
        // loadStokData
        // =========================================================
        function loadStokData() {
            const warehouseId = $('#warehouse_filter').val() || '';
            const productId = $('#product_filter').val() || '';

            if (warehouseId === '' && productId === '') {
                destroyDataTable();
                $emptyState.show();
                $tableContainer.hide();
                return;
            }

            $overlay.show();
            $emptyState.hide();
            $tableContainer.show();

            $.ajax({
                url: '<?= site_url("gudang_stok/get_stock_by_warehouse") ?>',
                type: 'POST',
                data: { warehouse_id: warehouseId, product_id: productId },
                dataType: 'json',
                success: function (res) {
                    $overlay.hide();

                    // Step 1: destroy SEBELUM sentuh tbody
                    destroyDataTable();

                    // Step 2: update tbody — kosongkan dulu, isi jika ada data
                    // JANGAN inject colspan row — biarkan kosong jika tidak ada data
                    if (res.success && res.data && res.data.length > 0) {
                        let rows = '';
                        $.each(res.data, function (index, stok) {
                            rows += `<tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${stok.warehouse_name || '-'}</td>
                            <td>${stok.bos_code || '-'}</td>
                            <td>${stok.product_code || '-'}</td>
                            <td>${stok.product_name || '-'}</td>
                            <td>${stok.type_name || '-'}</td>
                            <td class="text-right">${viewNumber(stok.current_stock)}</td>
                        </tr>`;
                        });
                        $tableBody.html(rows);
                    } else {
                        // Kosongkan tbody — DataTables tampilkan language.emptyTable
                        $tableBody.empty();
                    }

                    // Step 3: init DataTables dengan tbody yang sudah siap
                    initDataTable();
                },
                error: function (xhr, status, error) {
                    $overlay.hide();
                    console.error('AJAX Error:', error);
                    toastr.error('Terjadi kesalahan saat memuat data stok', 'Error');
                }
            });
        }

        // =========================================================
        // Events
        // =========================================================
        $('#warehouse_filter').on('change', function () { loadStokData(); });
        $('#product_filter').on('change', function () { loadStokData(); });

    });
</script>