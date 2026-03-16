                                                                                                                                                                                                                                                                                                                                                 <!-- C:\xampp\htdocs\wms\application\views\pages\pengiriman\form.php -->
<?php $this->load->view('pages/pengiriman/edit_style'); ?>

<!-- Select2 CSS & JS with Custom Styles for better UX -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Enhanced Select2 Dropdown for Form */
.select2-container--default .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px !important;
    color: #212529;
    padding-right: 20px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 34px !important;
    right: 8px;
}

.select2-dropdown {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    z-index: 1061 !important;
    max-height: 300px !important;
    overflow: hidden !important;
}

.select2-dropdown--below {
    margin-top: -1px;
}

.select2-results {
    max-height: 280px !important;
}

.select2-container--default .select2-results__option {
    padding: 10px 12px !important;
    font-size: 14px !important;
}

.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #4361ee !important;
    color: white !important;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #f8f9fa !important;
    color: #212529 !important;
}

.select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    padding: 8px 12px !important;
    height: 38px !important;
    font-size: 14px !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #4361ee;
    color: white;
}

/* Smooth scroll */
.select2-results__options {
    scrollbar-width: thin;
    scrollbar-color: #adb5bd transparent;
}

.select2-results__options::-webkit-scrollbar {
    width: 8px;
}

.select2-results__options::-webkit-scrollbar-track {
    background: transparent;
}

.select2-results__options::-webkit-scrollbar-thumb {
    background-color: #adb5bd;
    border-radius: 4px;
}

.select2-results__options::-webkit-scrollbar-thumb:hover {
    background-color: #6c757d;
}
</style>

<div class="container-fluid">
    <?php
    $back_url = 'pengiriman/penggunaan';
    if ($to_status == '3') {
        $back_url = 'pengiriman/antar_gudang';
    }
    ?>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a>
            <h6 class="m-0 font-weight-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="pengirimanForm" action="<?= site_url('pengiriman/create') ?>" method="POST">
                <input type="hidden" name="to_status" value="<?= $to_status ?>">

                <!-- Bagian 1: Informasi Dasar Pengiriman -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_date" class="form-label">Tanggal Pengiriman *</label>
                            <input type="date" class="form-control" id="stockout_date" name="stockout_date"
                                value="<?= isset($old_form_data['stockout_date']) ? $old_form_data['stockout_date'] : date('Y-m-d') ?>"
                                max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_code" class="form-label">Kode Pengiriman *</label>
                            <?php
                            // Generate kode awal berdasarkan gudang default
                            $default_warehouse_code = 'WH';
                            $default_warehouse_name = 'Pilih Gudang';

                            if ($user_role != 'superadmin' && isset($user_warehouse_id)) {
                                // Untuk non-superadmin, gunakan gudang mereka
                                foreach ($warehouses as $wh) {
                                    if ($wh['warehouse_id'] == $user_warehouse_id) {
                                        $default_warehouse_code = $wh['warehouse_code'];
                                        $default_warehouse_name = $wh['warehouse_name'];
                                        break;
                                    }
                                }
                            } elseif (isset($old_form_data['from_warehouse_id']) && !empty($old_form_data['from_warehouse_id'])) {
                                // Jika ada data lama, gunakan gudang dari data lama
                                foreach ($warehouses as $wh) {
                                    if ($wh['warehouse_id'] == $old_form_data['from_warehouse_id']) {
                                        $default_warehouse_code = $wh['warehouse_code'];
                                        $default_warehouse_name = $wh['warehouse_name'];
                                        break;
                                    }
                                }
                            }

                            if ($to_status == '3') {
                                $kode_prefix = 'TO/' . $default_warehouse_code . '/';
                            } else {
                                $kode_prefix = 'DO/' . $default_warehouse_code . '/';
                            }

                            $romanMonth = monthToRoman(date('m'));
                            $stockout_code = $kode_prefix . $romanMonth . '/' . date('Y');

                            if (isset($old_form_data['stockout_code'])) {
                                $stockout_code = $old_form_data['stockout_code'];
                            }
                            ?>

                            <input type="text" class="form-control bg-light" id="stockout_code" name="stockout_code"
                                value="<?= $stockout_code ?>" readonly>
                            <input type="hidden" id="warehouse_data"
                                value='<?= json_encode(array_column($warehouses, 'warehouse_code', 'warehouse_id')) ?>'>

                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Informasi Gudang -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_warehouse_id" class="form-label">Dari Gudang *</label>
                            <?php
                            $selected_from_warehouse = '';
                            if (isset($old_form_data['from_warehouse_id'])) {
                                $selected_from_warehouse = $old_form_data['from_warehouse_id'];
                            } elseif ($user_role != 'superadmin') {
                                $selected_from_warehouse = $user_warehouse_id;
                            }
                            ?>

                            <?php if ($user_role == 'superadmin'): ?>
                                <!-- Superadmin dapat memilih gudang asal -->
                                <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id"
                                    required>
                                    <option value="">Pilih Gudang Asal</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($selected_from_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                <input type="text" class="form-control bg-light" value="<?= $user_warehouse_name ?>"
                                    readonly>
                                <input type="hidden" id="from_warehouse_id" name="from_warehouse_id"
                                    value="<?= $user_warehouse_id ?>">
                                <small class="form-text text-muted">Gudang asal berdasarkan login Anda</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_note" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="stockout_note" name="stockout_note"
                                placeholder="Masukkan keterangan tambahan"
                                rows="2"><?= isset($old_form_data['stockout_note']) ? $old_form_data['stockout_note'] : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Tujuan Pengiriman -->
                <?php if ($to_status == '1'): ?>
                    <!-- Pengiriman ke Pengguna -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id" class="form-label">Ke Pengguna *</label>
                                <select class="form-control select2" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php
                                    $selected_customer = isset($old_form_data['to_id']) ? $old_form_data['to_id'] : '';
                                    foreach ($customers as $customer):
                                        ?>
                                        <option value="<?= $customer['id'] ?>" <?= ($selected_customer == $customer['id']) ? 'selected' : '' ?>>
                                            <?= $customer['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tipe Pengiriman</label>
                                <?php
                                $tipe_text = 'Ke Pengguna';
                                if ($to_status == '3') {
                                    $tipe_text = 'Antar Gudang';
                                }
                                ?>
                                <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly>
                            </div>
                        </div>
                    </div>

                <?php elseif ($to_status == '3'): ?>
                    <!-- Pengiriman Antar Gudang -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_warehouse_id" class="form-label">Ke Gudang *</label>
                                <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                    <option value="">Pilih Gudang Tujuan</option>
                                    <?php
                                    $selected_to_warehouse = isset($old_form_data['to_id']) ? $old_form_data['to_id'] : '';
                                    foreach ($warehouses as $warehouse):
                                        // Jangan tampilkan gudang yang sama dengan asal
                                        $disabled = '';
                                        if ($user_role != 'superadmin' && $warehouse['warehouse_id'] == $user_warehouse_id) {
                                            $disabled = 'disabled';
                                        }
                                        // For superadmin, check against selected from warehouse
                                        if ($user_role == 'superadmin' && isset($old_form_data['from_warehouse_id'])) {
                                            $disabled = ($warehouse['warehouse_id'] == $old_form_data['from_warehouse_id']) ? 'disabled' : '';
                                        }
                                        ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($selected_to_warehouse == $warehouse['warehouse_id']) ? 'selected' : '' ?>
                                            <?= $disabled ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                            <?= ($disabled) ? ' (Gudang Asal)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tipe Pengiriman</label>
                                <?php
                                $tipe_text = 'Ke Pengguna';
                                if ($to_status == '3') {
                                    $tipe_text = 'Antar Gudang';
                                }
                                ?>
                                <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>


                <hr class="my-4">

                <!-- Bagian 5: Detail Barang - Tabel Format -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold mb-3">Detail Barang</h5>
                    </div>
                </div>

                <div class="edit-card">
                    <div class="edit-card-header">
                        <h5><i class="fas fa-boxes me-2"></i>Daftar Barang</h5>
                        <button type="button" id="addItem" class="btn btn-success btn-sm"
                            style="background: #28a745; border: none; font-weight: 600;">
                            <i class="fas fa-plus me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="edit-card-body p-0">
                        <div class="table-responsive" style="min-height: 200px;">
                            <table class="excel-table" id="itemsTable" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No</th>
                                        <th style="width:350px;">Barang</th>
                                        <th style="width:90px;">Qty</th>
                                        <th style="width:100px;">Stok<br>Tersedia</th>
                                        <th style="width:80px;">Satuan</th>
                                        <th>Keterangan</th>
                                        <th style="width:80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <?php if (isset($old_form_data['items']) && !empty($old_form_data['items'])): ?>
                                        <!-- Tampilkan item dari session jika ada -->
                                        <?php foreach ($old_form_data['items'] as $index => $item): ?>
                                            <tr class="item-row" data-index="<?= $index ?>">
                                                <td style="text-align:center; font-weight: 600;" class="row-number"><?= $index + 1 ?></td>
                                                <td>
                                                    <select class="form-control cell-input product-select" name="product_id[]"
                                                        data-index="<?= $index ?>" required>
                                                        <option value="">Pilih Produk</option>
                                                        <?php foreach ($products as $product):
                                                            $current_stock = $product['current_stock'];
                                                            $stock_display = $current_stock < 0 ? '0' : $current_stock;
                                                            $available_stock = $current_stock < 0 ? 0 : $current_stock;
                                                            ?>
                                                            <option value="<?= $product['product_id'] ?>"
                                                                data-stock-id="<?= $product['stock_id'] ?>"
                                                                data-available-qty="<?= $available_stock ?>"
                                                                data-unit-code="<?= $product['unit_code'] ?>"
                                                                <?= (isset($item['stock_id']) && ($item['stock_id'] == $product['stock_id'])) ? 'selected' : '' ?>
                                                                <?= ($current_stock <= 0) ? 'disabled style="color: #dc3545;"' : '' ?>>
                                                                <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                                (Stok: <?= $stock_display ?> <?= $product['unit_code'] ?>)
                                                                <?= ($current_stock <= 0) ? ' - Stok Habis' : '' ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="hidden" name="stock_id[]"
                                                        value="<?= isset($item['stock_id']) ? $item['stock_id'] : '' ?>">
                                                </td>
                                                <td>
                                                    <?php
                                                    // Get available qty for this item
                                                    $item_available_qty = 0;
                                                    $item_unit_code = '';
                                                    if (isset($item['stock_id'])) {
                                                        foreach ($products as $product) {
                                                            if ($item['stock_id'] == $product['stock_id']) {
                                                                $item_available_qty = $product['current_stock'] < 0 ? 0 : $product['current_stock'];
                                                                $item_unit_code = $product['unit_code'];
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <input type="number" class="form-control cell-input qty-input" name="qty[]"
                                                        data-index="<?= $index ?>" value="<?= isset($item['qty']) ? $item['qty'] : '' ?>"
                                                        min="1" max="<?= $item_available_qty ?>" required style="text-align: right;">
                                                    <small class="stock-info text-danger qty-error" id="qtyError<?= $index ?>"
                                                        style="display: none;">
                                                        Melebihi stok
                                                    </small>
                                                </td>
                                                <td style="text-align:center; font-weight: 600;" class="stock-display">
                                                    <?= number_format($item_available_qty, 0) ?>
                                                </td>
                                                <td style="text-align:center; font-weight: 600;" class="unit-display">
                                                    <?= htmlspecialchars($item_unit_code ?: '-') ?>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control cell-input" name="detail_note[]"
                                                        value="<?= isset($item['detail_note']) ? $item['detail_note'] : '' ?>"
                                                        placeholder="Keterangan">
                                                </td>
                                                <td style="text-align:center">
                                                    <button type="button" class="btn btn-action btn-action-delete remove-item"
                                                        title="Hapus Baris" <?= (count($old_form_data['items']) <= 1) ? 'disabled' : '' ?>>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Default: satu row kosong -->
                                        <tr class="item-row" data-index="0">
                                            <td style="text-align:center; font-weight: 600;" class="row-number">1</td>
                                            <td>
                                                <select class="form-control cell-input product-select" name="product_id[]" data-index="0"
                                                    required>
                                                    <option value="">Pilih Produk</option>
                                                    <?php foreach ($products as $product):
                                                        $current_stock = $product['current_stock'];
                                                        $stock_display = $current_stock < 0 ? '0' : $current_stock;
                                                        ?>
                                                        <option value="<?= $product['product_id'] ?>"
                                                            data-stock-id="<?= $product['stock_id'] ?>"
                                                            data-available-qty="<?= $current_stock < 0 ? 0 : $current_stock ?>"
                                                            data-unit-code="<?= $product['unit_code'] ?>"
                                                            <?= ($current_stock <= 0) ? 'disabled style="color: #dc3545;"' : '' ?>>
                                                            <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                            (Stok: <?= $stock_display ?> <?= $product['unit_code'] ?>)
                                                            <?= ($current_stock <= 0) ? ' - Stok Habis' : '' ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="hidden" name="stock_id[]" value="">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control cell-input qty-input" name="qty[]" data-index="0"
                                                    step="1" min="1" max="0" required style="text-align: right;">
                                                <small class="stock-info text-danger qty-error" id="qtyError0" style="display: none;">
                                                    Melebihi stok
                                                </small>
                                            </td>
                                            <td style="text-align:center; font-weight: 600;" class="stock-display">-</td>
                                            <td style="text-align:center; font-weight: 600;" class="unit-display">-</td>
                                            <td>
                                                <input type="text" class="form-control cell-input" name="detail_note[]"
                                                    placeholder="Keterangan">
                                            </td>
                                            <td style="text-align:center">
                                                <button type="button" class="btn btn-action btn-action-delete remove-item"
                                                    title="Hapus Baris" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Bagian 6: Tombol Aksi -->
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengiriman
                        </button>
                        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> <?= $wording['cancel']; ?>
                        </a>
                        <?php if (isset($old_form_data)): ?>
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

<!-- form_script.php file is included separately for JS code -->
<?php $this->load->view('pages/pengiriman/form_script.php'); ?>