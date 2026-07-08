<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\form_antar_gudang.php -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= site_url('penerimaan/antar_gudang') ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a>
            <h6 class="m-0 fw-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm" action="<?= site_url('penerimaan/create') ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">
                <input type="hidden" name="form_type" value="<?= $form_type ?>">

                <!-- Pilih Pengiriman -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockout_id" class="form-label">Kode Pengiriman *</label>
                            <select name="stockout_id" id="stockout_id" class="form-control select2-pengiriman"
                                required>
                                <option value="">-- Pilih Kode Pengiriman --</option>
                                <?php foreach ($list_pengiriman as $pengiriman): ?>
                                    <option value="<?= $pengiriman['stockout_id'] ?>"
                                        <?= ($pengiriman['stockout_id'] == $filter_stockout_id) ? 'selected' : '' ?>>
                                        <?= $pengiriman['stockout_code'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Pilih kode pengiriman untuk menampilkan detail</div>
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="loadingIndicator" class="d-none">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 mb-0">Memuat data pengiriman...</p>
                    </div>
                </div>

                <!-- Form Data Section -->
                <div id="formDataSection" class="d-none">

                    <div class="row mb-4">
                        <!-- Informasi Pengiriman -->
                        <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="m-0 fw-bold">Informasi Pengiriman</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Pengiriman</label>
                                        <input type="text" class="form-control bg-light" id="header_stockout_date"
                                            readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Dari Gudang</label>
                                                <input type="text" class="form-control bg-light"
                                                    id="header_warehouse_name" readonly>
                                                <input type="hidden" name="from_warehouse_id" id="header_warehouse_id">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Ke Tujuan</label>
                                                <input type="text" class="form-control bg-light" id="header_to_name"
                                                    readonly>
                                                <input type="hidden" name="to_status" id="header_to_status">
                                                <input type="hidden" name="to_id" id="header_to_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan Pengiriman</label>
                                        <textarea class="form-control bg-light" id="header_stockout_note" rows="2"
                                            readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Penerimaan -->
                        <div class="col-lg-6 col-md-12">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="m-0 fw-bold">Informasi Penerimaan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="stockin_date" class="form-label">Tanggal Penerimaan
                                                    *</label>
                                                <input type="date" class="form-control" id="stockin_date"
                                                    name="stockin_date"
                                                    value="<?= isset($old_form_data['stockin_date']) ? $old_form_data['stockin_date'] : date('Y-m-d') ?>"
                                                    max="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="stockin_code" class="form-label">Kode Penerimaan *</label>
                                                <input type="text" class="form-control bg-light" id="stockin_code"
                                                    name="stockin_code"
                                                    value="<?= isset($stockin_code) ? $stockin_code : '' ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Tipe Penerimaan</label>
                                                <input type="text" class="form-control bg-light"
                                                    value="Penerimaan Antar Gudang" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="stockin_invoice" class="form-label">Nomor Referensi</label>
                                                <input type="text" class="form-control bg-light" id="stockin_invoice"
                                                    name="stockin_invoice" readonly
                                                    value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                                    placeholder="Nomor referensi penerimaan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stockin_note" class="form-label">Keterangan Penerimaan</label>
                                        <textarea class="form-control" id="stockin_note" name="stockin_note"
                                            placeholder="Masukkan keterangan tambahan untuk penerimaan"
                                            rows="2"><?= isset($old_form_data['stockin_note']) ? htmlspecialchars($old_form_data['stockin_note'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Barang -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 fw-bold">Detail Barang</h6>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" id="btnSimpan" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Penerimaan
                            </button>
                            <button type="button" id="resetForm" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Batal
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- Item Template -->
<div id="itemTemplate" class="d-none">
    <div class="item-row row mb-3 align-items-end">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Produk</label>
                <input type="text" class="form-control bg-light product-display" readonly>
                <input type="hidden" class="product-id" name="product_id[]">
                <input type="hidden" class="stock-id" name="stock_id[]">
                <input type="hidden" class="detail-id" name="detail_id[]">
                <input type="hidden" class="qty-sent">
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control qty-received-input" name="qty[]" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" class="form-control detail-note" name="detail_note[]"
                    placeholder="Keterangan penerimaan">
            </div>
        </div>
    </div>
</div>

<!-- appData untuk JS -->
<script>
    var appData = {
        warehouses: <?= json_encode($warehouses ?? []) ?>,
        userWarehouseCode: '<?= $user_warehouse_code ?? '' ?>',
        userRole: '<?= $user_role ?? '' ?>',
        userWarehouseId: '<?= $user_warehouse_id ?? '' ?>'
    };
    // CSRF — akan di-refresh tiap kali AJAX sukses agar tidak 403
    var csrfData = {
        name: '<?= $this->security->get_csrf_token_name() ?>',
        hash: '<?= $this->security->get_csrf_hash() ?>'
    };
</script>

<?php include_once 'application/views/pages/penerimaan/form_antar_gudang_js.php'; ?>