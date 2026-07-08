<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\form.php -->

<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/select2/bootstrap5/select2.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/select2/bootstrap5/select2-bootstrap-5-theme.min.css'); ?>" rel="stylesheet" />
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
    <?php
    $back_url = 'penerimaan/dari_supplier';
    if ($from_status == '1') {
        $back_url = 'penerimaan/dari_pengguna';
    } elseif ($from_status == '3') {
        $back_url = 'penerimaan/antar_gudang';
    }

    $products_js = [];
    foreach ($products as $product) {
        $products_js[] = [
            'product_id'   => $product['product_id'],
            'product_code' => $product['product_code'],
            'product_name' => $product['product_name'],
            'display_name' => htmlspecialchars(
                $product['product_code'] . ' - ' . $product['product_name'] . ' || ' . $product['unit_code'],
                ENT_QUOTES, 'UTF-8'
            )
        ];
    }

    // Tentukan prefix kode
    $kode_prefix = ($from_status == '3') ? 'TI/' : 'RI/';

    // Tentukan warehouse code default untuk stockin_code
    $default_warehouse_code = 'WH';
    if ($user_role != 'superadmin' && isset($user_warehouse_id)) {
        foreach ($warehouses as $wh) {
            if ($wh['warehouse_id'] == $user_warehouse_id) {
                $default_warehouse_code = $wh['warehouse_code'];
                break;
            }
        }
    } elseif (isset($old_form_data['to_warehouse_id']) && !empty($old_form_data['to_warehouse_id'])) {
        foreach ($warehouses as $wh) {
            if ($wh['warehouse_id'] == $old_form_data['to_warehouse_id']) {
                $default_warehouse_code = $wh['warehouse_code'];
                break;
            }
        }
    }

    $romanMonth  = monthToRoman(date('m'));
    $stockin_code = isset($old_form_data['stockin_code'])
        ? $old_form_data['stockin_code']
        : $kode_prefix . $default_warehouse_code . '/' . $romanMonth . '/' . date('Y');

    $selected_to_warehouse = '';
    if (isset($old_form_data['to_warehouse_id'])) {
        $selected_to_warehouse = $old_form_data['to_warehouse_id'];
    } elseif ($user_role != 'superadmin') {
        $selected_to_warehouse = $user_warehouse_id;
    }

    $tipe_text = 'Dari Supplier';
    if ($from_status == '1') $tipe_text = 'Dari Pengguna';
    elseif ($from_status == '3') $tipe_text = 'Antar Gudang';
    ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back'] ?>
            </a>
            <h6 class="m-0 fw-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm" action="<?= site_url('penerimaan/create') ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">
                <input type="hidden" id="warehouse_data"
                    value='<?= json_encode(array_column($warehouses, 'warehouse_code', 'warehouse_id')) ?>'>

                <!-- Tanggal & Kode -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_date" class="form-label">Tanggal Penerimaan *</label>
                            <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                value="<?= isset($old_form_data['stockin_date']) ? $old_form_data['stockin_date'] : date('Y-m-d') ?>"
                                max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_code" class="form-label">Kode Penerimaan *</label>
                            <input type="text" class="form-control bg-light" id="stockin_code"
                                name="stockin_code" value="<?= $stockin_code ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Penerimaan dari Pengguna -->
                <?php if ($from_status == '1'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Dari Pengguna *</label>
                                <select class="form-control select2-field" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php
                                    $selected_customer = isset($old_form_data['customer_id']) ? $old_form_data['customer_id'] : '';
                                    foreach ($customers as $customer):
                                    ?>
                                        <option value="<?= $customer['id'] ?>"
                                            <?= ($selected_customer == $customer['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor referensi" required>
                            </div>
                        </div>
                    </div>

                <!-- Penerimaan dari Supplier -->
                <?php elseif ($from_status == '2'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier_id" class="form-label">Dari Supplier *</label>
                                <select class="form-control select2-field" id="supplier_id" name="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php
                                    $selected_supplier = isset($old_form_data['supplier_id']) ? $old_form_data['supplier_id'] : '';
                                    foreach ($suppliers as $supplier):
                                    ?>
                                        <option value="<?= $supplier['id'] ?>"
                                            <?= ($selected_supplier == $supplier['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($supplier['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Invoice *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor invoice" required>
                            </div>
                        </div>
                    </div>

                <!-- Penerimaan Antar Gudang -->
                <?php elseif ($from_status == '3'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="from_warehouse_id" class="form-label">Dari Gudang *</label>
                                <select class="form-control select2-field" id="from_warehouse_id" name="from_id" required>
                                    <option value="">Pilih Gudang Asal</option>
                                    <?php
                                    $selected_from = isset($old_form_data['from_id']) ? $old_form_data['from_id'] : '';
                                    foreach ($warehouses as $warehouse):
                                        if ($user_role != 'superadmin' && $warehouse['warehouse_id'] == $user_warehouse_id) continue;
                                    ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($selected_from == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['warehouse_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($user_role != 'superadmin'): ?>
                                    <div class="form-text">Tidak bisa memilih gudang sendiri</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockin_invoice" class="form-label">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                    placeholder="Masukkan nomor referensi transfer" required>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Gudang Tujuan & Keterangan -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="to_warehouse_id" class="form-label">Ke Gudang *</label>
                            <?php if ($user_role == 'superadmin'): ?>
                                <select class="form-control select2-field" id="to_warehouse_id" name="to_warehouse_id" required>
                                    <option value="">Pilih Gudang Tujuan</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($selected_to_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['warehouse_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="text" class="form-control bg-light"
                                    value="<?= htmlspecialchars($user_warehouse_name, ENT_QUOTES, 'UTF-8') ?>" readonly>
                                <input type="hidden" name="to_warehouse_id" value="<?= $user_warehouse_id ?>">
                                <div class="form-text">Gudang tujuan berdasarkan login Anda</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockin_note" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="stockin_note" name="stockin_note"
                                placeholder="Masukkan keterangan tambahan" rows="2"><?= isset($old_form_data['stockin_note']) ? htmlspecialchars($old_form_data['stockin_note'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipe Penerimaan</label>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Detail Barang -->
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Detail Barang</h5>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <?php if (isset($old_form_data['items']) && !empty($old_form_data['items'])): ?>
                        <?php foreach ($old_form_data['items'] as $index => $item): ?>
                            <div class="item-row row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Produk *</label>
                                        <select class="form-control select2-product product-select" name="product_id[]" required>
                                            <option value="">Pilih Produk</option>
                                            <?php foreach ($products as $product):
                                                $dn = htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'] . ' || ' . $product['unit_code'], ENT_QUOTES, 'UTF-8');
                                            ?>
                                                <option value="<?= $product['product_id'] ?>"
                                                    <?= (isset($item['product_id']) && $item['product_id'] == $product['product_id']) ? 'selected' : '' ?>>
                                                    <?= $dn ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]"
                                            step="any" min="1"
                                            value="<?= isset($item['qty']) ? $item['qty'] : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            value="<?= isset($item['detail_note']) ? htmlspecialchars($item['detail_note'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                            placeholder="Keterangan tambahan">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-danger btn-block remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Default: satu row kosong -->
                        <div class="item-row row mb-3 align-items-end">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Produk *</label>
                                    <select class="form-control select2-product product-select" name="product_id[]" required>
                                        <option value="">Pilih Produk</option>
                                        <?php foreach ($products as $product):
                                            $dn = htmlspecialchars($product['product_code'] . ' - ' . $product['product_name'] . ' || ' . $product['unit_code'], ENT_QUOTES, 'UTF-8');
                                        ?>
                                            <option value="<?= $product['product_id'] ?>"><?= $dn ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Qty *</label>
                                    <input type="number" class="form-control qty-input" name="qty[]"
                                        step="any" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan Barang</label>
                                    <input type="text" class="form-control" name="detail_note[]"
                                        placeholder="Keterangan tambahan">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mb-3">
                                    <!-- Disabled jika hanya 1 row -->
                                    <button type="button" class="btn btn-danger btn-block remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" id="btnSimpan" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Penerimaan
                        </button>
                        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <?php if (isset($old_form_data) && !empty($old_form_data)): ?>
                            <button type="button" id="clearForm" class="btn btn-warning">
                                <i class="fas fa-broom"></i> Bersihkan Form
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- CSRF + appData untuk JS -->
<script>
var csrfData = {
    name: '<?= $this->security->get_csrf_token_name() ?>',
    hash: '<?= $this->security->get_csrf_hash() ?>'
};
var formConfig = {
    fromStatus:      '<?= $from_status ?>',
    userRole:        '<?= $user_role ?>',
    userWarehouseId: '<?= $user_warehouse_id ?? '' ?>',
    userWarehouseName: '<?= addslashes($user_warehouse_name ?? '') ?>',
    kodePrefix:      '<?= $kode_prefix ?>',
    productsData:    <?= json_encode($products_js, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
};
</script>

<script src="<?php echo base_url('assets/select2/select2.min.js'); ?>"></script>
<script>
$(document).ready(function () {

    // =========================================================
    // STATE
    // =========================================================
    let isSubmitting = false;

    // =========================================================
    // HELPER: update CSRF hash dari response
    // =========================================================
    function updateCsrfHash(hash) {
        if (hash) csrfData.hash = hash;
    }

    // =========================================================
    // HELPER: Roman month
    // =========================================================
    function getRomanMonth(month) {
        return ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][month - 1] || 'I';
    }

    // =========================================================
    // HELPER: Generate stockin code
    // =========================================================
    function updateStockinCode() {
        let warehouseId = '';

        if (formConfig.userRole === 'superadmin') {
            warehouseId = $('#to_warehouse_id').val() || '';
        } else {
            warehouseId = formConfig.userWarehouseId;
        }

        const warehouseData = JSON.parse($('#warehouse_data').val() || '{}');
        const warehouseCode = (warehouseId && warehouseData[warehouseId]) ? warehouseData[warehouseId] : 'WH';
        const romanMonth    = getRomanMonth(new Date().getMonth() + 1);
        const year          = new Date().getFullYear();

        $('#stockin_code').val(`${formConfig.kodePrefix}${warehouseCode}/${romanMonth}/${year}`);
    }

    // =========================================================
    // HELPER: Notifikasi — pakai toastr (konsisten dengan form lain)
    // =========================================================
    function notifSuccess(msg) { toastr.success(msg); }
    function notifWarning(msg) { toastr.warning(msg); }

    // =========================================================
    // HELPER: Reset tombol submit
    // =========================================================
    function resetSubmitButton() {
        $('#btnSimpan')
            .prop('disabled', false)
            .html('<i class="fas fa-save"></i> Simpan Penerimaan');
    }

    // =========================================================
    // INISIALISASI SELECT2
    // Satu fungsi terpusat dengan minimumResultsForSearch: 0
    // FIX: dropdownParent menggunakan $('body') bukan document.body
    // =========================================================
    function initSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(element).data('placeholder') || 'Pilih opsi',
            allowClear: true,
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        });
    }

    // Init semua select2 saat load
    $('.select2-field, .select2-product').each(function () {
        initSelect2(this);
    });

    // =========================================================
    // Update stockin code saat load dan saat gudang tujuan berubah
    // =========================================================
    updateStockinCode();

    if (formConfig.userRole === 'superadmin') {
        $('#to_warehouse_id').on('change', function () {
            updateStockinCode();
        });
    }

    // =========================================================
    // Validasi gudang asal & tujuan tidak boleh sama (from_status = 3)
    // =========================================================
    if (formConfig.fromStatus === '3') {
        $('#from_warehouse_id, #to_warehouse_id').on('change', function () {
            const fromId = $('#from_warehouse_id').val();
            const toId   = formConfig.userRole === 'superadmin'
                ? $('#to_warehouse_id').val()
                : formConfig.userWarehouseId;

            if (fromId && toId && fromId === toId) {
                notifWarning('Gudang asal dan tujuan tidak boleh sama!');
                $(this).val(null).trigger('change');
            }
        });
    }

    // =========================================================
    // Cegah tanggal masa depan
    // =========================================================
    $('#stockin_date').on('change', function () {
        const selected = new Date(this.value);
        const today    = new Date();
        today.setHours(0, 0, 0, 0);
        if (selected > today) {
            notifWarning('Tidak bisa memilih tanggal yang akan datang');
            this.value = '<?= date('Y-m-d') ?>';
        }
    });

    $('#stockin_date').on('keydown', function (e) { e.preventDefault(); });

    // =========================================================
    // Generate options HTML untuk produk
    // =========================================================
    function generateProductOptions() {
        let html = '<option value="">Pilih Produk</option>';
        formConfig.productsData.forEach(function (p) {
            html += `<option value="${p.product_id}">${p.display_name}</option>`;
        });
        return html;
    }

    // =========================================================
    // Update state tombol hapus (disable jika hanya 1 row)
    // =========================================================
    function updateRemoveButtons() {
        const count = $('.item-row').length;
        $('.remove-item').prop('disabled', count <= 1);
    }

    // =========================================================
    // Tambah row barang
    // =========================================================
    $('#addItem').on('click', function () {
        const newRow = `
            <div class="item-row row mb-3 align-items-end">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">Produk *</label>
                        <select class="form-control select2-product product-select" name="product_id[]" required>
                            ${generateProductOptions()}
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Qty *</label>
                        <input type="number" class="form-control qty-input" name="qty[]"
                            step="any" min="1" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Barang</label>
                        <input type="text" class="form-control" name="detail_note[]"
                            placeholder="Keterangan tambahan">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <button type="button" class="btn btn-danger btn-block remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;

        $('#itemsContainer').append(newRow);

        // Init Select2 khusus untuk row baru
        initSelect2($('#itemsContainer .item-row:last-child .select2-product'));
        updateRemoveButtons();
    });

    // =========================================================
    // Hapus row barang
    // =========================================================
    $(document).on('click', '.remove-item', function () {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    // =========================================================
    // Bersihkan form
    // =========================================================
    $('#clearForm').on('click', function () {
        if (!confirm('Apakah Anda yakin ingin membersihkan semua data form?')) return;
        window.location.href = window.location.href.split('?')[0];
    });

    // =========================================================
    // SUBMIT FORM via AJAX — dengan perlindungan double-submit
    //
    // FIX utama:
    // 1. Flag isSubmitting mencegah klik ganda
    // 2. Tombol TIDAK di-enable kembali saat sukses (redirect terjadi)
    // 3. Hanya di-enable kembali saat gagal agar user bisa coba lagi
    // 4. CSRF hash di-refresh dari setiap response
    // 5. Validasi baris kosong: baris tanpa produk dilewati (bukan error),
    //    yang penting minimal 1 baris punya produk + qty valid
    // =========================================================
    $('#penerimaanForm').on('submit', function (e) {
        e.preventDefault();

        // Lapisan 1: cegah double submit
        if (isSubmitting) {
            notifWarning('Data sedang diproses, harap tunggu...');
            return false;
        }

        // ---- Validasi ----
        let errors  = [];
        let isValid = true;

        // Validasi gudang tujuan (superadmin)
        if (formConfig.userRole === 'superadmin' && !$('#to_warehouse_id').val()) {
            errors.push('Harap pilih gudang tujuan');
            isValid = false;
        }

        // Validasi sumber berdasarkan tipe
        if (formConfig.fromStatus === '1' && !$('#customer_id').val()) {
            errors.push('Harap pilih pengguna');
            isValid = false;
        } else if (formConfig.fromStatus === '2' && !$('#supplier_id').val()) {
            errors.push('Harap pilih supplier');
            isValid = false;
        } else if (formConfig.fromStatus === '3' && !$('#from_warehouse_id').val()) {
            errors.push('Harap pilih gudang asal');
            isValid = false;
        }

        // Validasi invoice/referensi
        if (!$('#stockin_invoice').val().trim()) {
            errors.push('Harap isi nomor invoice/referensi');
            isValid = false;
        }

        // Validasi item — skip baris tanpa produk, hitung yang valid
        let validItems = 0;
        $('.item-row').each(function (index) {
            const productId = $(this).find('.product-select').val();
            if (!productId) return; // lewati baris kosong

            const qty = parseFloat($(this).find('.qty-input').val());
            if (!qty || qty <= 0) {
                errors.push(`Qty pada baris ${index + 1} harus lebih dari 0`);
                isValid = false;
            } else {
                validItems++;
            }
        });

        if (validItems === 0) {
            errors.push('Minimal satu barang harus dipilih dan diisi qty-nya');
            isValid = false;
        }

        if (!isValid) {
            errors.forEach(function (msg) { notifWarning(msg); });
            return false;
        }

        // ---- Set flag & ubah tampilan tombol ----
        isSubmitting = true;
        $('#btnSimpan')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...');

        // ---- Kirim via AJAX dengan CSRF terkini ----
        const formData = $(this).serialize() + '&' + csrfData.name + '=' + csrfData.hash;

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                updateCsrfHash(res.csrf_hash);

                if (res.success) {
                    notifSuccess(res.message || 'Penerimaan berhasil disimpan');

                    // Tombol TIDAK di-enable — redirect segera terjadi
                    setTimeout(function () {
                        if (formConfig.fromStatus === '1') {
                            window.location.href = '<?= site_url("penerimaan/dari_pengguna") ?>';
                        } else if (formConfig.fromStatus === '2') {
                            window.location.href = '<?= site_url("penerimaan/dari_supplier") ?>';
                        } else {
                            window.location.href = '<?= site_url("penerimaan/antar_gudang") ?>';
                        }
                    }, 1200);
                } else {
                    // Gagal → enable kembali agar user bisa coba lagi
                    isSubmitting = false;
                    resetSubmitButton();
                    $('#errorMessage').html(res.message || 'Gagal menyimpan penerimaan');
                    $('#errorModal').modal('show');
                }
            },
            error: function (xhr) {
                isSubmitting = false;
                resetSubmitButton();

                let msg = 'Terjadi kesalahan saat menyimpan data';
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.message) msg = res.message;
                } catch (e) {
                    if (xhr.status === 403) {
                        msg = 'Sesi keamanan kedaluwarsa. Silakan refresh halaman dan coba lagi.';
                    } else if (xhr.responseText.indexOf('<!DOCTYPE') !== -1) {
                        msg = 'Terjadi kesalahan server. Silakan coba lagi.';
                    }
                }

            $('#errorMessage').html(res.message);
            $('#errorModal').modal('show');
                console.error('AJAX Error:', xhr.status, xhr.responseText.substring(0, 300));
            }
        });
    });

    // =========================================================
    // Cegah Enter memicu submit tidak sengaja
    // =========================================================
    $('#penerimaanForm').on('keydown', 'input[type="text"], input[type="number"]', function (e) {
        if (e.key === 'Enter') e.preventDefault();
    });

});
</script>