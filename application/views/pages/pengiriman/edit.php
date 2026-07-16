<?php $this->load->view('pages/pengiriman/edit_style'); ?>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loader">
        <div class="spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="container-fluid py-3">
    <?php
    $back_url = 'pengiriman/penggunaan';
    $tipe_pengiriman_text = 'Ke Pengguna';

    if ($to_status == '3') {
        $back_url = 'pengiriman/antar_gudang';
        $tipe_pengiriman_text = 'Antar Gudang';
    }

    // Format tanggal untuk display (dd/mm/yyyy)
    $display_date = date('d/m/Y', strtotime($pengiriman['header']['stockout_date']));
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
                <i class="fas fa-<?= ($to_status == '1') ? 'user' : 'warehouse' ?> me-1"></i>
                <?= $tipe_pengiriman_text ?>
            </span>
        </div>
    </div>

    <form id="pengirimanForm" action="<?= site_url('pengiriman/update/' . $pengiriman['header']['stockout_id']) ?>"
        method="POST">
        <input type="hidden" name="to_status" value="<?= $to_status ?>">

        <!-- Header Card -->
        <div class="edit-card">
            <div class="edit-card-header">
                <h5><i class="fas fa-file-alt me-2"></i>Informasi Pengiriman</h5>
                <span class="header-subtitle"><?= $pengiriman['header']['stockout_code'] ?></span>
            </div>
            <div class="edit-card-body">
                <div class="row">
                    <!-- Kode Pengiriman (Readonly) -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Pengiriman</label>
                        <input type="text" class="form-control cell-input-readonly"
                            value="<?= $pengiriman['header']['stockout_code'] ?>" readonly>
                    </div>

                    <!-- Tanggal Pengiriman - Display Format -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label form-label-required">Tanggal Pengiriman</label>
                        <input type="text" class="form-control cell-input" id="stockout_date_display"
                            value="<?= $display_date ?>" placeholder="dd/mm/yyyy">
                        <input type="hidden" name="stockout_date" id="stockout_date"
                            value="<?= date('Y-m-d', strtotime($pengiriman['header']['stockout_date'])) ?>">
                    </div>
                </div>

                <div class="row">
                    <!-- Gudang Asal -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label form-label-required">Dari Gudang</label>
                        <?php if ($user_role == 'superadmin'): ?>
                            <select class="form-control cell-input select2" id="from_warehouse_id" name="from_warehouse_id"
                                required>
                                <option value="">Pilih Gudang</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['warehouse_id'] ?>"
                                        <?= ($pengiriman['header']['warehouse_id'] == $wh['warehouse_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['warehouse_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control cell-input-readonly"
                                value="<?= htmlspecialchars($pengiriman['header']['warehouse_name']) ?>" readonly>
                            <input type="hidden" name="from_warehouse_id"
                                value="<?= $pengiriman['header']['warehouse_id'] ?>">
                        <?php endif; ?>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" class="form-control cell-input" name="stockout_note"
                            value="<?= htmlspecialchars($pengiriman['header']['stockout_note'] ?? '') ?>"
                            placeholder="Catatan tambahan">
                    </div>
                </div>

                <?php if ($to_status == '1'): // Ke Pengguna ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">Ke Pengguna</label>
                            <select class="form-control cell-input select2" id="customer_id" name="customer_id" required>
                                <option value="">Pilih Pengguna</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($pengiriman['header']['to_id'] == $c['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Pengiriman</label>
                            <input type="text" class="form-control cell-input-readonly" value="Ke Pengguna" readonly>
                        </div>
                    </div>
                <?php elseif ($to_status == '3'): // Antar Gudang ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-required">Ke Gudang</label>
                            <select class="form-control cell-input select2" id="to_warehouse_id" name="to_warehouse_id"
                                required>
                                <option value="">Pilih Gudang</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <?php if ($wh['warehouse_id'] != $pengiriman['header']['warehouse_id']): ?>
                                        <option value="<?= $wh['warehouse_id'] ?>"
                                            <?= ($pengiriman['header']['to_id'] == $wh['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($wh['warehouse_name']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Pengiriman</label>
                            <input type="text" class="form-control cell-input-readonly" value="Antar Gudang" readonly>
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
                <div class="table-responsive" style="min-height: 200px;">
                    <table class="excel-table" id="itemsTable" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th style="width:5%;">No</th>
                                <th style="width:30%;">Barang</th>
                                <th style="width:8%;">Qty</th>
                                <th style="width:10%;">Stok<br>Tersedia</th>
                                <th style="width:10%;">Satuan</th>
                                <th style="width:27%;">Keterangan</th>
                                <th style="width:10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <?php if (!empty($pengiriman['detail'])): ?>
                                <?php foreach ($pengiriman['detail'] as $index => $detail): ?>
                                    <tr class="item-row" data-index="<?= $index ?>"
                                        data-unit="<?= htmlspecialchars($detail['unit_code'] ?? '') ?>">
                                        <td style="text-align:center; font-weight: 600;"><?= $index + 1 ?></td>
                                        <td>
                                            <!-- Display Mode -->
                                            <div class="product-cell product-display" id="display-<?= $index ?>">
                                                <span
                                                    class="product-cell-text"><?= htmlspecialchars(($detail['product_code'] ?? '') . ' - ' . ($detail['product_name'] ?? '')) ?></span>
                                                <input type="hidden" name="stock_id[]" value="<?= $detail['stock_id'] ?>"
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
                                            <input type="number" class="form-control cell-input" name="qty[]" min="0" step="1"
                                                value="<?= (int) ($detail['qty'] ?? 0) ?>" required style="text-align: right;"
                                                data-index="<?= $index ?>">
                                            <small class="stock-info text-danger" id="qtyError<?= $index ?>" style="display: none;">
                                                Melebihi stok
                                            </small>
                                        </td>
                                        <td style="text-align:center; font-weight: 600;" class="stock-display">
                                            <?= number_format($detail['available_qty'] ?? 0, 0) ?>
                                        </td>
                                        <td style="text-align:center; font-weight: 600;" class="unit-display">
                                            <?= htmlspecialchars($detail['unit_code'] ?? '-') ?>
                                        </td>
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
                                                title="Hapus Baris" <?= (count($pengiriman['detail']) <= 1) ? 'disabled' : '' ?>>
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

        <!-- Action Buttons -->
        <div class="edit-card">
            <div class="edit-card-body d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-back" onclick="confirmBack()">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </button>
                <button type="button" class="btn btn-save" onclick="confirmSave()">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/select2/select2.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/select2/select2-bootstrap-5-theme.min.css'); ?>" rel="stylesheet" />


<script src="<?php echo base_url('assets/select2/select2.min.js'); ?>"></script>

<script>
    // Data produk dari controller
    var allProducts = <?= $products_json ?? '[]' ?>;
    var currentRowCount = <?= count($pengiriman['detail']) ?>;
    var hasChanges = false;
    var confirmationCallback = null;
    
    // Simpan data produk yang sudah di-load per warehouse untuk menghindari load ulang
    var warehouseProductsCache = {};

    $(document).ready(function () {
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
                showConfirmation('Minimal harus ada 1 barang', null, false);
            }
        });

        // Format tanggal - input blur
        $('#stockout_date_display').on('blur', function () {
            const value = $(this).val();
            const parts = value.split('/');
            if (parts.length === 3) {
                // Konversi dd/mm/yyyy ke yyyy-mm-dd
                const formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
                $('#stockout_date').val(formatted);
                $(this).val(value);
            } else {
                // Format tidak valid, kembalikan ke nilai sebelumnya
                const currentVal = $('#stockout_date').val();
                if (currentVal) {
                    const dateParts = currentVal.split('-');
                    $(this).val(dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0]);
                }
            }
        });

        // Format tanggal - saat typing
        $('#stockout_date_display').on('keyup', function (e) {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length >= 2 && value.length <= 4) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            } else if (value.length > 4) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4) + '/' + value.substring(4, 8);
            }
            $(this).val(value);
        });

    // Qty validation on input
        $(document).on('input', 'input[name="qty[]"]', function () {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;
            const row = $(this).closest('.item-row');
            const stockId = row.find('input[name="stock_id[]"]').val();
            
            // Find available qty for this stock - use displayed available stock from table
            // The available stock in the table already includes the edited qty
            const stockDisplay = row.find('.stock-display').text();
            const availableQty = parseFloat(stockDisplay.replace(/,/g, '')) || 0;
            
            // Show/hide error - qty should not exceed displayed available stock
            if (qty > availableQty && availableQty > 0) {
                $('#qtyError' + index).show();
            } else {
                $('#qtyError' + index).hide();
            }
        });

        // Handle confirmation modal button click
        $('#confirmButton').on('click', function () {
            $('#confirmationModal').modal('hide');
            
            if (confirmationCallback) {
                confirmationCallback();
                confirmationCallback = null;
            }
        });
        
        // Handle modal hidden event to clean up
        $('#confirmationModal').on('hidden.bs.modal', function () {
            confirmationCallback = null;
        });
    });

    // Show confirmation modal
    function showConfirmation(message, callback, isConfirm = true) {
        // Set title based on type
        if (isConfirm && callback) {
            $('#confirmationModalLabel').text('Konfirmasi');
            $('#confirmButton').text('Ya').removeClass('d-none btn-danger btn-success btn-primary btn-warning').addClass('btn-danger');
            confirmationCallback = callback;
        } else {
            $('#confirmationModalLabel').text('Peringatan');
            $('#confirmButton').addClass('d-none');
            confirmationCallback = null;
        }
        
        // Set message
        $('#confirmationMessage').text(message || 'Apakah Anda yakin ingin melanjutkan?');
        
        // Show modal
        $('#confirmationModal').modal('show');
    }

    // Initialize product select with data
    function initProductSelect(selectElement) {
        // Format produk dengan menampilkan kode, nama, dan stok
        const formattedData = allProducts.map(function (p) {
            const stockDisplay = p.current_stock > 0 ? p.current_stock : '0';
            return {
                id: p.stock_id,
                text: p.product_code + ' - ' + p.product_name,
                stock_id: p.stock_id,
                product_code: p.product_code,
                product_name: p.product_name,
                unit_code: p.unit_code || '-',
                current_stock: p.current_stock || 0
            };
        });

        // Custom template untuk menampilkan hasil dropdown
        function formatProduct(product) {
            if (!product.id) return product.text;
            
            const unitCode = product.unit_code || '-';
            const stockQty = product.current_stock > 0 ? product.current_stock : '0';
            const textColor = product.current_stock <= 0 ? 'color: #dc3545;' : '';
            
            return $(`
                <div style="${textColor}">
                    <div>${product.product_code} - ${product.product_name}</div>
                    <div style="color: #6c757d; font-size: 15px; margin-top: 6px;">
                        <i class="fas fa-box me-1"></i>Satuan: ${unitCode} | 
                        <i class="fas fa-cubes me-1"></i>Stok: ${stockQty}
                        ${product.current_stock <= 0 ? ' - <b>Stok Habis</b>' : ''}
                    </div>
                </div>
            `);
        }

        selectElement.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Cari produk...',
            allowClear: true,
            data: formattedData,
            templateResult: formatProduct,
            templateSelection: formatProduct
        });
    }

    // Confirm back navigation
    function confirmBack() {
        if (hasChanges) {
            showConfirmation(
                'Perubahan belum disimpan. Apakah Anda yakin ingin kembali?',
                function () {
                    window.location.href = '<?= site_url($back_url) ?>';
                }
            );
        } else {
            window.location.href = '<?= site_url($back_url) ?>';
        }
    }

    // Form validation - NO duplicate check (duplicates allowed)
    function validateForm() {
        let valid = true;
        let messages = [];

        <?php if ($user_role == 'superadmin'): ?>
            if (!$('#from_warehouse_id').val()) { messages.push('Pilih gudang asal'); valid = false; }
        <?php endif; ?>

        <?php if ($to_status == '1'): ?>
            if (!$('#customer_id').val()) { messages.push('Pilih pengguna'); valid = false; }
        <?php elseif ($to_status == '3'): ?>
            if (!$('#to_warehouse_id').val()) { messages.push('Pilih gudang tujuan'); valid = false; }
            // Validasi gudang asal dan tujuan tidak boleh sama
            const fromWh = '<?= $pengiriman['header']['warehouse_id'] ?>';
            const toWh = $('#to_warehouse_id').val();
            if (fromWh && toWh && fromWh === toWh) {
                messages.push('Gudang asal dan tujuan tidak boleh sama');
                valid = false;
            }
        <?php endif; ?>

        let hasStock = false;
        $('input[name="stock_id[]"]').each(function () { if ($(this).val()) hasStock = true; });
        if (!hasStock) { messages.push('Tambah minimal 1 barang'); valid = false; }

        // Calculate total qty per product and validate against available stock
        var stockQtys = {};
        $('input[name="stock_id[]"]').each(function (index) {
            var sid = $(this).val();
            var qty = parseFloat($('input[name="qty[]"]').eq(index).val()) || 0;
            if (sid && qty > 0) {
                if (!stockQtys[sid]) stockQtys[sid] = 0;
                stockQtys[sid] += qty;
            }
        });

        let exceedsStock = false;
        let zeroQty = false;
        
        $('input[name="qty[]"]').each(function (index) {
            const qty = parseFloat($(this).val()) || 0;
            const row = $(this).closest('.item-row');
            const stockId = row.find('input[name="stock_id[]"]').val();
            
            if (qty <= 0) {
                zeroQty = true;
            }
            
            // Check if total qty for this product exceeds available stock
            if (stockId && stockQtys[stockId]) {
                let stockDisplay = row.find('.stock-display').text().trim();
                
                // Handle new rows where stock display might be "-" or empty
                let availableQty = 0;
                if (stockDisplay !== '-' && stockDisplay !== '' && stockDisplay !== '0') {
                    availableQty = parseFloat(stockDisplay.replace(/,/g, '')) || 0;
                } else {
                    // Try to get stock from allProducts for new rows
                    const productInfo = allProducts.find(p => p.stock_id == stockId);
                    if (productInfo) {
                        availableQty = parseFloat(productInfo.current_stock) || 0;
                    }
                }
                
                if (stockQtys[stockId] > availableQty && availableQty > 0) {
                    exceedsStock = true;
                    $('#qtyError' + index).show();
                    $('#qtyError' + index).text('Total melebihi stok tersedia (' + availableQty + ')');
                } else {
                    $('#qtyError' + index).hide();
                }
            }
        });
        
        if (zeroQty) { messages.push('Quantity harus lebih dari 0'); valid = false; }
        if (exceedsStock) { messages.push('Total quantity melebihi stok tersedia'); valid = false; }

        return { valid: valid, messages: messages };
    }

    // Confirm save
    function confirmSave() {
        const validation = validateForm();

        if (!validation.valid) {
            showConfirmation('PERBAIKI DATA:\n\n• ' + validation.messages.join('\n• '), null, false);
            return;
        }

        // Jika valid, munculkan konfirmasi simpan
        showConfirmation(
            'Apakah Anda yakin ingin menyimpan perubahan?',
            function () {
                hasChanges = false;
                $('#pengirimanForm')[0].submit();
            }
        );
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
            showConfirmation('Pilih produk terlebih dahulu', null, false);
            return;
        }

        // Find product info from allProducts
        const productInfo = allProducts.find(p => p.stock_id == selectedId);
        const unitCode = productInfo ? (productInfo.unit_code || '-') : '-';
        const currentStock = productInfo ? (productInfo.current_stock || 0) : 0;

        // Extract product name - clean up the text
        const productNameText = selectedText.replace(/\s*\[Satuan:[^\]]*\]\s*$/, '').replace(/\s*\|.*$/, '').replace(/\(Stok:.*?\)/, '').trim();

        // Update the display div - remove old hidden input and add new one
        $('#display-' + index).html(
            '<span class="product-cell-text">' + productNameText + '</span>' +
            '<input type="hidden" name="stock_id[]" value="' + selectedId + '" id="stock_id_' + index + '">'
        );

        // Update unit cell dan stock cell di baris yang sama
        const row = $('#display-' + index).closest('tr');
        row.find('.unit-display').text(unitCode);
        row.find('.stock-display').text(currentStock > 0 ? currentStock : '0');

        // Switch back to display mode
        $('#edit-' + index).addClass('hidden');
        $('#display-' + index).removeClass('hidden');
        
        // Mark as changed
        hasChanges = true;
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
        <tr class="item-row" data-index="${newIndex}" data-unit="-">
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
                <input type="number" class="form-control cell-input" name="qty[]" min="0" step="1" value="1" required style="text-align: right;" data-index="${newIndex}">
                <small class="stock-info text-danger" id="qtyError${newIndex}" style="display: none;">
                    Melebihi stok
                </small>
            </td>
            <td style="text-align:center; font-weight: 600;" class="stock-display">-</td>
            <td style="text-align:center; font-weight: 600;" class="unit-display">-</td>
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
        enableEdit(newIndex);

        // Add change event to update stock display when product is selected
        $('#product_select_' + newIndex).on('change', function() {
            const selectedId = $(this).val();
            const row = $(this).closest('tr');
            if (selectedId) {
                const productInfo = allProducts.find(p => p.stock_id == selectedId);
                const currentStock = productInfo ? (productInfo.current_stock || 0) : 0;
                row.find('.stock-display').text(currentStock > 0 ? currentStock : '0');
            } else {
                row.find('.stock-display').text('-');
            }
        });

        // Rebind delete button
        $('.btn-delete-item').unbind('click').click(function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
                hasChanges = true;
            } else {
                showConfirmation('Minimal harus ada 1 barang', null, false);
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

