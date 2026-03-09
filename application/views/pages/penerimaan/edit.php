<?php $this->load->view('pages/penerimaan/edit_style'); ?>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loader">
        <div class="spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="container-fluid py-3">
    <?php
    $back_url = 'penerimaan/dari_pengguna';
    $tipe_penerimaan_text = 'Dari Pengguna';

    if ($from_status == '2') {
        $back_url = 'penerimaan/dari_supplier';
        $tipe_penerimaan_text = 'Dari Supplier';
    } elseif ($from_status == '3') {
        $back_url = 'penerimaan/antar_gudang';
        $tipe_penerimaan_text = 'Antar Gudang';
    }
    ?>

    <!-- Back Button & Title -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button type="button" class="btn btn-back" onclick="confirmBack()">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </button>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="info-badge">
                <i
                    class="fas fa-<?= ($from_status == '1') ? 'user' : (($from_status == '2') ? 'building' : 'warehouse') ?> me-1"></i>
                <?= $tipe_penerimaan_text ?>
            </span>
        </div>
    </div>

    <form id="penerimaanForm" action="<?= site_url('penerimaan/update/' . $penerimaan['header']['stockin_id']) ?>"
        method="POST">
        <input type="hidden" name="from_status" value="<?= $from_status ?>">

        <!-- Header Card -->
        <div class="edit-card">
            <div class="edit-card-header">
                <h5><i class="fas fa-file-alt me-2"></i>Informasi Penerimaan</h5>
                <span class="header-subtitle"><?= $penerimaan['header']['stockin_code'] ?></span>
            </div>
            <div class="edit-card-body">
                <div class="row">
                    <!-- Kode Penerimaan (Readonly) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Penerimaan</label>
                        <input type="text" class="form-control cell-input-readonly"
                            value="<?= $penerimaan['header']['stockin_code'] ?>" readonly>
                    </div>

                    <!-- Tanggal Penerimaan -->
                    <!-- <div class="col-md-6 mb-3">
                        <label class="form-label form-label-required">Tanggal Penerimaan</label>
                        <input type="date" class="form-control cell-input" id="stockin_date" name="stockin_date"
                            value="<?= date('Y-m-d', strtotime($penerimaan['header']['stockin_date'])) ?>" required>
                    </div> -->

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="start_date" class="form-label form-label-required">Tanggal Penerimaan</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr" id="stockin_date" name="stockin_date"
                                    value="<?= $penerimaan['header']['stockin_date'] ? date('d/m/Y', strtotime($penerimaan['header']['stockin_date'])) : '' ?>"
                                    autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Gudang Tujuan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label form-label-required">Ke Gudang</label>
                        <?php if ($user_role == 'superadmin'): ?>
                            <select class="form-control cell-input select2" id="to_warehouse_id" name="to_warehouse_id"
                                required>
                                <option value="">Pilih Gudang</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['warehouse_id'] ?>"
                                        <?= ($penerimaan['header']['warehouse_id'] == $wh['warehouse_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['warehouse_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control cell-input-readonly"
                                value="<?= htmlspecialchars($penerimaan['header']['warehouse_name']) ?>" readonly>
                            <input type="hidden" name="to_warehouse_id"
                                value="<?= $penerimaan['header']['warehouse_id'] ?>">
                        <?php endif; ?>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" class="form-control cell-input" name="stockin_note"
                            value="<?= htmlspecialchars($penerimaan['header']['stockin_note'] ?? '') ?>"
                            placeholder="Catatan tambahan">
                    </div>
                </div>

                <?php if ($from_status == '1'): // Dari Pengguna ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">Dari Pengguna</label>
                            <select class="form-control cell-input select2" id="customer_id" name="customer_id" required>
                                <option value="">Pilih Pengguna</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($penerimaan['header']['from_id'] == $c['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">No Referensi</label>
                            <input type="text" class="form-control cell-input" id="stockin_invoice" name="stockin_invoice"
                                value="<?= htmlspecialchars($penerimaan['header']['stockin_invoice']) ?>" required>
                        </div>
                    </div>
                <?php elseif ($from_status == '2'): // Dari Supplier ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">Dari Supplier</label>
                            <select class="form-control cell-input select2" id="supplier_id" name="supplier_id" required>
                                <option value="">Pilih Supplier</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= ($penerimaan['header']['from_id'] == $s['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">No Invoice</label>
                            <input type="text" class="form-control cell-input" id="stockin_invoice" name="stockin_invoice"
                                value="<?= htmlspecialchars($penerimaan['header']['stockin_invoice']) ?>" required>
                        </div>
                    </div>
                <?php elseif ($from_status == '3'): // Antar Gudang ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">Dari Gudang</label>
                            <select class="form-control cell-input select2" id="from_warehouse_id" name="from_warehouse_id"
                                required>
                                <option value="">Pilih Gudang</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <?php if ($wh['warehouse_id'] != $penerimaan['header']['warehouse_id']): ?>
                                        <option value="<?= $wh['warehouse_id'] ?>"
                                            <?= ($penerimaan['header']['from_id'] == $wh['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($wh['warehouse_name']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">No Referensi</label>
                            <input type="text" class="form-control cell-input" id="stockin_invoice" name="stockin_invoice"
                                value="<?= htmlspecialchars($penerimaan['header']['stockin_invoice']) ?>" required>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Card - Excel Like Table -->
        <div class="edit-card">
            <div class="edit-card-header">
                <h5><i class="fas fa-boxes me-2"></i>Detail Barang</h5>
                <button type="button" class="btn btn-success btn-sm" onclick="addNewRow()"
                    style="background: #28a745; border: none; font-weight: 600;">
                    <i class="fas fa-plus me-1"></i> Tambah Baris
                </button>
            </div>
            <div class="edit-card-body p-0">
                <div class="table-responsive">
                    <table class="excel-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Produk</th>
                                <th style="width:100px">Qty</th>
                                <th style="width:80px">Satuan</th>
                                <th>Keterangan</th>
                                <th style="width:100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <?php if (!empty($penerimaan['detail'])): ?>
                                <?php foreach ($penerimaan['detail'] as $index => $detail): ?>
                                    <tr class="item-row" data-index="<?= $index ?>">
                                        <td style="text-align:center; font-weight: 600;"><?= $index + 1 ?></td>
                                        <td>
                                            <!-- Display Mode -->
                                            <div class="product-cell product-display" id="display-<?= $index ?>">
                                                <span
                                                    class="product-cell-text"><?= htmlspecialchars(($detail['product_code'] ?? '') . ' - ' . ($detail['product_name'] ?? '')) ?></span>
                                                <span
                                                    class="product-cell-unit"><?= htmlspecialchars($detail['unit_code'] ?? '-') ?></span>
                                                <input type="hidden" name="stock_id[]" value="<?= $detail['product_id'] ?>"
                                                    id="stock_id_<?= $index ?>">
                                            </div>
                                            <!-- Edit Mode -->
                                            <div class="product-cell product-edit hidden" id="edit-<?= $index ?>">
                                                <select class="form-control cell-input select2-product"
                                                    id="product_select_<?= $index ?>" style="height:32px;">
                                                    <option value="">Pilih Produk</option>
                                                </select>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-action btn-action-save"
                                                        onclick="saveProduct(<?= $index ?>)" title="Simpan">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-action btn-action-cancel"
                                                        onclick="cancelEdit(<?= $index ?>)" title="Batal">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control cell-input" name="qty[]" min="1" step="1"
                                                value="<?= (int) ($detail['qty'] ?? 0) ?>" required style="text-align: right;">
                                        </td>
                                        <td style="text-align:center; font-weight: 600;">
                                            <?= htmlspecialchars($detail['unit_code'] ?? '-') ?></td>
                                        <td>
                                            <input type="text" class="form-control cell-input" name="detail_note[]"
                                                value="<?= htmlspecialchars($detail['detail_note'] ?? '') ?>"
                                                placeholder="Keterangan">
                                        </td>
                                        <td style="text-align:center">
                                            <button type="button" class="btn btn-action btn-action-edit"
                                                onclick="enableEdit(<?= $index ?>)" title="Ganti Produk">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-action btn-action-delete btn-delete-item"
                                                title="Hapus Baris" <?= (count($penerimaan['detail']) <= 1) ? 'disabled' : '' ?>>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="edit-card">
            <div class="edit-card-body d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-back" onclick="confirmBack()">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </button>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Tambahkan CSS dan JS Flatpickr di head -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    // Data produk dari controller - sudah termasuk unit_code
    var productsInReceipt = <?= $products_in_receipt_json ?? '[]' ?>;
    var allProducts = <?= $all_products_json ?? '[]' ?>;
    var currentRowCount = <?= count($penerimaan['detail']) ?>;
    var hasChanges = false;

    $(document).ready(function () {

        // Inisialisasi Flatpickr
        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            locale: {
                firstDayOfWeek: 1 // Senin sebagai hari pertama
            },
            position: "below", // Paksa posisi di bawah
            positionElement: null,
        });

        // Hide loading after page load
        setTimeout(function () {
            $('#loading-overlay').fadeOut();
        }, 500);

        // Initialize Select2
        $('.select2').each(function () {
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: $(this).data('placeholder') || 'Pilih opsi',
                allowClear: true
            });
        });

        // Initialize product selects in table
        $('.select2-product').each(function () {
            initProductSelect($(this));
        });

        // Delete row handler
        $('.btn-delete-item').click(function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
                hasChanges = true;
            } else {
                alert('Minimal harus ada 1 barang');
            }
        });

        // Form validation
        $('#penerimaanForm').submit(function (e) {
            let valid = true;
            let messages = [];

            <?php if ($user_role == 'superadmin'): ?>
                if (!$('#to_warehouse_id').val()) { messages.push('Pilih gudang tujuan'); valid = false; }
            <?php endif; ?>

            <?php if ($from_status == '1'): ?>
                if (!$('#customer_id').val()) { messages.push('Pilih pengguna'); valid = false; }
            <?php elseif ($from_status == '2'): ?>
                if (!$('#supplier_id').val()) { messages.push('Pilih supplier'); valid = false; }
            <?php elseif ($from_status == '3'): ?>
                if (!$('#from_warehouse_id').val()) { messages.push('Pilih gudang asal'); valid = false; }
            <?php endif; ?>

            if (!$('#stockin_invoice').val()) { messages.push('Isi nomor referensi'); valid = false; }

            let hasStock = false;
            $('input[name="stock_id[]"]').each(function () { if ($(this).val()) hasStock = true; });
            if (!hasStock) { messages.push('Tambah minimal 1 barang'); valid = false; }

            let hasQtyError = false;
            $('input[name="qty[]"]').each(function () {
                const qty = parseInt($(this).val()) || 0;
                if (qty <= 0) hasQtyError = true;
            });
            if (hasQtyError) { messages.push('Quantity harus lebih dari 0'); valid = false; }

            if (!valid) {
                e.preventDefault();
                alert('PERBAIKI DATA:\n\n• ' + messages.join('\n• '));
                return false;
            }

            hasChanges = false;
        });
    });

    // Initialize product select with data -显示单位信息
    function initProductSelect(selectElement) {
        // Format produk dengan menampilkan kode, nama, dan satuan
        const formattedData = allProducts.map(function (p) {
            return {
                id: p.id,
                text: p.product_code + ' - ' + p.product_name + ' (' + (p.unit_code || '-') + ')',
                product_id: p.product_id,
                product_code: p.product_code,
                product_name: p.product_name,
                unit_code: p.unit_code
            };
        });

        selectElement.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Cari produk...',
            allowClear: true,
            data: formattedData
        });
    }

    // Confirm back navigation
    function confirmBack() {
        if (hasChanges) {
            if (confirm('Perubahan belum disimpan. Apakah Anda yakin ingin kembali?')) {
                window.location.href = '<?= site_url($back_url) ?>';
            }
        } else {
            window.location.href = '<?= site_url($back_url) ?>';
        }
    }

    // Enable edit mode for product
    function enableEdit(index) {
        $('#display-' + index).addClass('hidden');
        $('#edit-' + index).removeClass('hidden');

        const select = $('#product_select_' + index);
        const currentStockId = $('#stock_id_' + index).val();

        // Set current value
        if (currentStockId) {
            select.val(currentStockId).trigger('change');
        }

        hasChanges = true;
    }

    // Save product selection
    function saveProduct(index) {
        const select = $('#product_select_' + index);
        const selectedId = select.val();
        const selectedOption = select.find('option:selected');
        const selectedText = selectedOption.text();

        if (!selectedId) {
            alert('Pilih produk terlebih dahulu');
            return;
        }

        // Find product info from allProducts
        const productInfo = allProducts.find(p => p.product_id == selectedId);
        const unitCode = productInfo ? productInfo.unit_code : '';

        // Update hidden input
        $('#stock_id_' + index).val(selectedId);

        // Update display - Pisahkan kode produk dan nama dari text
        const fullText = selectedText.replace(/\s*\([^)]*\)\s*$/, ''); // Hapus bagian (satuan)
        const unitMatch = selectedText.match(/\(([^)]+)\)\s*$/);
        const displayUnit = unitMatch ? unitMatch[1] : unitCode;

        $('#display-' + index).html(
            '<span class="product-cell-text">' + fullText + '</span>' +
            '<span class="product-cell-unit">' + displayUnit + '</span>' +
            '<input type="hidden" name="stock_id[]" value="' + selectedId + '" id="stock_id_' + index + '">'
        );

        // Update unit cell
        const row = $('#display-' + index).closest('tr');
        row.find('td:nth-child(4)').text(displayUnit);

        // Switch back to display mode
        $('#edit-' + index).addClass('hidden');
        $('#display-' + index).removeClass('hidden');
    }

    // Cancel edit
    function cancelEdit(index) {
        $('#edit-' + index).addClass('hidden');
        $('#display-' + index).removeClass('hidden');

        // Reset select to original value
        const originalValue = $('#stock_id_' + index).val();
        $('#product_select_' + index).val(originalValue).trigger('change');
    }

    // Add new row
    function addNewRow() {
        const newIndex = currentRowCount;
        currentRowCount++;

        const newRow = `
    <tr class="item-row" data-index="${newIndex}">
        <td style="text-align:center; font-weight: 600;" class="row-number">${$('.item-row').length + 1}</td>
        <td>
            <div class="product-cell product-display" id="display-${newIndex}">
                <span class="product-cell-text text-muted">-</span>
                <input type="hidden" name="stock_id[]" value="" id="stock_id_${newIndex}">
            </div>
            <div class="product-cell product-edit hidden" id="edit-${newIndex}">
                <select class="form-control cell-input select2-product" id="product_select_${newIndex}" style="height:32px;">
                    <option value="">Pilih Produk</option>
                </select>
                <div class="mt-2">
                    <button type="button" class="btn btn-action btn-action-save" onclick="saveProduct(${newIndex})" title="Simpan">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-action btn-action-cancel" onclick="cancelEdit(${newIndex})" title="Batal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </td>
        <td>
            <input type="number" class="form-control cell-input" name="qty[]" min="1" step="1" value="1" required style="text-align: right;">
        </td>
        <td style="text-align:center; font-weight: 600;" class="unit-cell">-</td>
        <td>
            <input type="text" class="form-control cell-input" name="detail_note[]" placeholder="Keterangan">
        </td>
        <td style="text-align:center">
            <button type="button" class="btn btn-action btn-action-edit" onclick="enableEdit(${newIndex})" title="Pilih Produk">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-action btn-action-delete btn-delete-item" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;

        $('#itemsBody').append(newRow);

        // Initialize Select2 for new row
        initProductSelect($('#product_select_' + newIndex));

        // Rebind delete button
        $('.btn-delete-item').unbind('click').click(function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
                hasChanges = true;
            } else {
                alert('Minimal harus ada 1 barang');
            }
        });

        hasChanges = true;
    }

    // Update row numbers
    function updateRowNumbers() {
        $('.item-row').each(function (index) {
            $(this).find('.row-number').text(index + 1);
        });
    }
</script>