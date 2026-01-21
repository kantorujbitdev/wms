<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\form_antar_gudang.php -->
<div class="container-fluid">
    <!-- Form -->
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
                
                <!-- Step 1: Select Pengiriman -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stockout_id" class="form-label">Kode Pengiriman *</label>
                            <select name="stockout_id" id="stockout_id" class="form-control select2" required>
                                <option value="">-- Pilih Kode Pengiriman --</option>
                                <?php foreach ($list_pengiriman as $pengiriman): ?>
                                    <option value="<?= $pengiriman['stockout_id'] ?>" 
                                        <?= ($pengiriman['stockout_id'] == $filter_stockout_id) ? 'selected' : ''; ?>>
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
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Memuat data pengiriman...</p>
                    </div>
                </div>

                <!-- Form Data Section (hidden by default) -->
                <div id="formDataSection" class="d-none">
                    <!-- Header Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 fw-bold">Informasi Pengiriman</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Pengiriman</label>
                                        <input type="text" class="form-control bg-light" id="header_stockout_date" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Dari Gudang</label>
                                        <input type="text" class="form-control bg-light" id="header_warehouse_name" readonly>
                                        <input type="hidden" name="from_warehouse_id" id="header_warehouse_id">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Ke Tujuan</label>
                                        <input type="text" class="form-control bg-light" id="header_to_name" readonly>
                                        <input type="hidden" name="to_status" id="header_to_status">
                                        <input type="hidden" name="to_id" id="header_to_id">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan Pengiriman</label>
                                        <textarea class="form-control bg-light" id="header_stockout_note" rows="2" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penerimaan Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 fw-bold">Informasi Penerimaan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="stockin_date" class="form-label">Tanggal Penerimaan *</label>
                                        <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                            value="<?= isset($old_form_data['stockin_date']) ? $old_form_data['stockin_date'] : date('Y-m-d') ?>" 
                                            max="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="stockin_code" class="form-label">Kode Penerimaan *</label>
                                        <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                            value="<?= isset($stockin_code) ? $stockin_code : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="stockin_invoice" class="form-label">No Referensi *</label>
                                        <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                            value="<?= isset($old_form_data['stockin_invoice']) ? $old_form_data['stockin_invoice'] : '' ?>"
                                            placeholder="Masukkan nomor referensi penerimaan" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="to_warehouse_id" class="form-label">Gudang Penerima *</label>
                                        <?php 
                                        $selected_to_warehouse = '';
                                        if (isset($old_form_data['to_warehouse_id'])) {
                                            $selected_to_warehouse = $old_form_data['to_warehouse_id'];
                                        } elseif ($user_role != 'superadmin') {
                                            $selected_to_warehouse = $user_warehouse_id;
                                        }
                                        ?>
                                        
                                        <?php if ($user_role == 'superadmin'): ?>
                                            <!-- Superadmin dapat memilih gudang tujuan -->
                                            <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                                <option value="">Pilih Gudang Penerima</option>
                                                <?php foreach ($warehouses as $warehouse): ?>
                                                    <option value="<?= $warehouse['warehouse_id'] ?>" 
                                                        <?= ($selected_to_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($warehouse['warehouse_name'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user_warehouse_name, ENT_QUOTES, 'UTF-8') ?>" readonly>
                                            <input type="hidden" name="to_warehouse_id" value="<?= $user_warehouse_id ?>">
                                            <div class="form-text">Gudang penerima berdasarkan login Anda</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stockin_note" class="form-label">Keterangan Penerimaan</label>
                                        <textarea class="form-control" id="stockin_note" name="stockin_note"
                                            placeholder="Masukkan keterangan tambahan untuk penerimaan" rows="2"><?= isset($old_form_data['stockin_note']) ? htmlspecialchars($old_form_data['stockin_note'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe Penerimaan</label>
                                        <input type="text" class="form-control bg-light" value="Penerimaan Antar Gudang" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold">Detail Barang</h6>
                                <div class="text-end">
                                    <button type="button" id="addItem" class="btn btn-success btn-sm d-none">
                                        <i class="fas fa-plus"></i> Tambah Barang Manual
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer">
                                <!-- Items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Penerimaan
                            </button>
                            <a href="<?= site_url('penerimaan/antar_gudang') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="button" id="resetForm" class="btn btn-warning">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden template for items -->
<div id="itemTemplate" class="d-none">
    <div class="item-row row mb-3 align-items-center">
        <div class="col-md-1">
            <div class="form-check text-center">
                <input class="form-check-input item-checkbox" type="checkbox" checked>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Produk</label>
                <input type="text" class="form-control bg-light product-display" readonly>
                <input type="hidden" class="product-id" name="product_id[]">
                <input type="hidden" class="stock-id" name="stock_id[]">
                <input type="hidden" class="detail-id" name="detail_id[]">
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label class="form-label">Qty Dikirim</label>
                <input type="text" class="form-control bg-light qty-sent" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label class="form-label">Qty Diterima *</label>
                <input type="number" class="form-control qty-received-input" name="qty[]" step="0.01" min="0" 
                    value="0" required>
                <div class="invalid-feedback">Qty tidak boleh melebihi qty dikirim</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" class="form-control" name="detail_note[]" 
                    placeholder="Keterangan penerimaan">
            </div>
        </div>
        <div class="col-md-1">
            <div class="mb-3">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-block remove-item d-none">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'application/views/pages/penerimaan/form_antar_gudang_js.php'; ?>