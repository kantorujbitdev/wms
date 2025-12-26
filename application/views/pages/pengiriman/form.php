<!-- C:\xampp\htdocs\wms\application\views\pages\pengiriman\form.php -->
<div class="container-fluid">
    <?php
    $back_url = 'pengiriman/penggunaan';
    if ($to_status == '3')
        $back_url = 'pengiriman/antar_gudang'; ?>
         <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> <?= $wording['back']; ?>
    </a>
    
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="pengirimanForm" action="<?= site_url('pengiriman/create') ?>" method="POST">
                <input type="hidden" name="to_status" value="<?= $to_status ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_date">Tanggal Pengiriman *</label>
                            <input type="date" class="form-control" id="stockout_date" name="stockout_date"
                                value="<?= isset($old_form_data['stockout_date']) ? $old_form_data['stockout_date'] : date('Y-m-d') ?>" 
                                max="<?= date('Y-m-d') ?>" required>
                            <small class="form-text text-muted">Tidak bisa memilih tanggal yang akan datang</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_code">Kode Pengiriman *</label>
                            <?php 
                            $code = $warehouses[0]['warehouse_code'];
                            $kode_prefix = $code . '/RO/INV/';
                            if ($to_status == '3')
                                $kode_prefix = $code . '/TRF/OUT/';
                            $romanMonth = monthToRoman(date('m'));
                            $stockout_code = $kode_prefix . $romanMonth . '/' . date('Y');
                            if (isset($old_form_data['stockout_code'])) {
                                $stockout_code = $old_form_data['stockout_code'];
                            }
                            ?>
                            <input type="text" class="form-control bg-light" id="stockout_code" name="stockout_code"
                                value="<?= $stockout_code ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Kode otomatis generated oleh sistem</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_warehouse_id">Dari Gudang *</label>
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
                                        <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id" required>
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
                                        <input type="text" class="form-control bg-light" value="<?= $user_warehouse_name ?>" readonly
                                            style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                                        <input type="hidden" id="from_warehouse_id" name="from_warehouse_id" value="<?= $user_warehouse_id ?>">
                                        <small class="form-text text-muted">Gudang asal berdasarkan login Anda</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_note">Keterangan</label>
                            <textarea class="form-control" id="stockout_note" name="stockout_note"
                                placeholder="Masukkan keterangan tambahan" rows="2"><?= isset($old_form_data['stockout_note']) ? $old_form_data['stockout_note'] : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Pengiriman ke Pengguna (to_status = 1) -->
                <?php if ($to_status == '1'): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_id">Ke Pengguna *</label>
                                        <select class="form-control select2" id="customer_id" name="customer_id" required>
                                            <option value="">Pilih Pengguna</option>
                                            <?php
                                            $selected_customer = isset($old_form_data['to_id']) ? $old_form_data['to_id'] : '';
                                            foreach ($customers as $customer):
                                                ?>
                                                        <option value="<?= $customer['id'] ?>" 
                                                            <?= ($selected_customer == $customer['id']) ? 'selected' : '' ?>>
                                                            <?= $customer['name'] ?>
                                                        </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stockout_invoice">No Referensi *</label>
                                        <input type="text" class="form-control" id="stockout_invoice" name="stockout_invoice"
                                            value="<?= isset($old_form_data['stockout_invoice']) ? $old_form_data['stockout_invoice'] : '' ?>"
                                            placeholder="Masukkan nomor referensi" required>
                                    </div>
                                </div>
                            </div>

                        <!-- Form untuk Pengiriman Antar Gudang (to_status = 3) -->
                <?php elseif ($to_status == '3'): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_warehouse_id">Ke Gudang *</label>
                                        <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                            <option value="">Pilih Gudang Tujuan</option>
                                            <?php
                                            $selected_to_warehouse = isset($old_form_data['to_id']) ? $old_form_data['to_id'] : '';
                                            foreach ($warehouses as $warehouse):
                                                // Jangan tampilkan gudang yang sama dengan asal
                                                $disabled = ($user_role != 'superadmin' && $warehouse['warehouse_id'] == $user_warehouse_id) ? 'disabled' : '';
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
                                        <small class="form-text text-muted">Pilih gudang tujuan pengiriman</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stockout_invoice">No Referensi *</label>
                                        <input type="text" class="form-control" id="stockout_invoice" name="stockout_invoice"
                                            value="<?= isset($old_form_data['stockout_invoice']) ? $old_form_data['stockout_invoice'] : '' ?>"
                                            placeholder="Masukkan nomor referensi transfer" required>
                                    </div>
                                </div>
                            </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_status">Tipe Pengiriman</label>
                            <?php
                            $tipe_text = 'Ke Pengguna';
                            if ($to_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Detail Barang</h5>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <?php if (isset($old_form_data['items']) && !empty($old_form_data['items'])): ?>
                                <!-- Tampilkan item dari session jika ada -->
                                <?php foreach ($old_form_data['items'] as $index => $item): ?>
                                            <div class="item-row row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Produk *</label>
                                                        <select class="form-control select2 product-select" name="product_id[]" data-index="<?= $index ?>" required>
                                                            <option value="">Pilih Produk</option>
                                                            <?php foreach ($products as $product): 
                                                                $current_stock = floatval($product['current_stock']);
                                                                $stock_display = $current_stock < 0 ? '0.00' : number_format($current_stock, 2);
                                                                ?>
                                                                        <option value="<?= $product['product_id'] ?>" 
                                                                            data-stock-id="<?= $product['stock_id'] ?>"
                                                                            data-available-qty="<?= $current_stock < 0 ? 0 : $current_stock ?>"
                                                                            <?= (isset($item['stock_id']) && ($item['stock_id'] == $product['stock_id'])) ? 'selected' : '' ?>
                                                                            <?= ($current_stock <= 0) ? 'disabled style="color: #dc3545;"' : '' ?>>
                                                                            <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                                            (Stok: <?= $stock_display ?> <?= $product['unit_code'] ?>)
                                                                            <?= ($current_stock <= 0) ? ' - Stok Habis/Tidak Tersedia' : '' ?>
                                                                        </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <input type="hidden" name="stock_id[]" value="<?= isset($item['stock_id']) ? $item['stock_id'] : '' ?>">
                                                        <small class="form-text text-info stock-info" id="stockInfo<?= $index ?>">
                                                            <?php if (isset($item['stock_id'])): ?>
                                                                <?php foreach ($products as $product): ?>
                                                                    <?php if ($item['stock_id'] == $product['stock_id']): 
                                                                        $current_stock = floatval($product['current_stock']);
                                                                        $available_stock = $current_stock < 0 ? 0 : $current_stock;
                                                                    ?>
                                                                        Stok tersedia: <?= number_format($available_stock, 2) ?> <?= $product['unit_code'] ?>
                                                                        <?php break; ?>
                                                                        <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Qty *</label>
                                                        <input type="number" class="form-control qty-input" name="qty[]" 
                                                            data-index="<?= $index ?>" step="0.01" min="0.01" 
                                                            value="<?= isset($item['qty']) ? $item['qty'] : '' ?>" required>
                                                        <small class="form-text text-danger qty-error" id="qtyError<?= $index ?>" style="display: none;">
                                                            Melebihi stok tersedia
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan Barang</label>
                                                        <input type="text" class="form-control" name="detail_note[]"
                                                            value="<?= isset($item['detail_note']) ? $item['detail_note'] : '' ?>"
                                                            placeholder="Keterangan tambahan untuk barang ini">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-4">
                                                    <div class="form-group">
                                                        <label class="form-label">&nbsp;</label>
                                                        <button type="button" class="btn btn-danger btn-block remove-item">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                <?php endforeach; ?>
                    <?php else: ?>
                                <!-- Default: satu row kosong -->
                                <div class="item-row row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Produk *</label>
                                            <select class="form-control select2 product-select" name="product_id[]" data-index="0" required>
                                                <option value="">Pilih Produk</option>
                                                <?php foreach ($products as $product): 
                                                    $current_stock = floatval($product['current_stock']);
                                                    $stock_display = $current_stock < 0 ? '0.00' : number_format($current_stock, 2);
                                                    ?>
                                                            <option value="<?= $product['product_id'] ?>" 
                                                                data-stock-id="<?= $product['stock_id'] ?>"
                                                                data-available-qty="<?= $current_stock < 0 ? 0 : $current_stock ?>"
                                                                <?= ($current_stock <= 0) ? 'disabled style="color: #dc3545;"' : '' ?>>
                                                                <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                                (Stok: <?= $stock_display ?> <?= $product['unit_code'] ?>)
                                                                <?= ($current_stock <= 0) ? ' - Stok Habis/Tidak Tersedia' : '' ?>
                                                            </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="stock_id[]" value="">
                                            <small class="form-text text-info stock-info" id="stockInfo0"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Qty *</label>
                                            <input type="number" class="form-control qty-input" name="qty[]" 
                                                data-index="0" step="0.01" min="0.01" max="0" required>
                                            <small class="form-text text-danger qty-error" id="qtyError0" style="display: none;">
                                                Melebihi stok tersedia
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Keterangan Barang</label>
                                            <input type="text" class="form-control" name="detail_note[]"
                                                placeholder="Keterangan tambahan untuk barang ini">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <div class="form-group">
                                            <label class="form-label">&nbsp;</label>
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
                    <div class="col-12 text-right">
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

<script>
    $(document).ready(function () {
        // Store products data from PHP - use window object for global access
        window.productsData = <?= $products_json ?: '[]' ?>;
        let itemCounter = <?= isset($old_form_data['items']) ? count($old_form_data['items']) : 1 ?>;

        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('.card-body')
        });

        // For superadmin: Load products when warehouse changes
        <?php if ($user_role == 'superadmin'): ?>
            $('#from_warehouse_id').on('change', function () {
                const warehouseId = $(this).val();
                if (warehouseId) {
                    loadProductsByWarehouse(warehouseId);

                    // Disable same warehouse in destination dropdown
                    $('#to_warehouse_id option').each(function () {
                        const optionValue = $(this).val();
                        if (optionValue == warehouseId) {
                            $(this).prop('disabled', true);
                            if ($(this).is(':selected')) {
                                $(this).prop('selected', false);
                                $('#to_warehouse_id').trigger('change.select2');
                            }
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    $('#to_warehouse_id').trigger('change.select2');
                }
            });
        <?php endif; ?>

        // Prevent future date selection
        $('#stockout_date').on('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Tidak bisa memilih tanggal yang akan datang');
                this.value = '<?= date('Y-m-d') ?>';
            }
        });

        // Prevent typing future dates
        $('#stockout_date').on('keydown', function (e) {
            e.preventDefault();
        });

        // Handle product selection
        $(document).on('change', '.product-select', function () {
            const index = $(this).data('index');
            const selectedOption = $(this).find('option:selected');
            const stockId = selectedOption.data('stock-id');
            const availableQty = parseFloat(selectedOption.data('available-qty')) || 0;

            // Update hidden stock_id input
            $(this).closest('.item-row').find('input[name="stock_id[]"]').val(stockId);

            // Extract unit from option text
            const optionText = selectedOption.text();
            const unitMatch = optionText.match(/\(Stok: [\d.,]+ (.+?)\)/);
            const unit = unitMatch ? unitMatch[1] : '';

            // Update stock info
            $('#stockInfo' + index).text('Stok tersedia: ' + availableQty.toFixed(2) + ' ' + unit);

            // Update max qty
            const qtyInput = $(this).closest('.item-row').find('.qty-input');
            qtyInput.attr('max', availableQty);

            // Clear error if any
            $('#qtyError' + index).hide();
            qtyInput.removeClass('is-invalid');
        });

        // Handle qty input validation
        $(document).on('input', '.qty-input', function () {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;
            const maxQty = parseFloat($(this).attr('max')) || 0;

            if (qty > maxQty) {
                $('#qtyError' + index).show();
                $(this).addClass('is-invalid');
            } else {
                $('#qtyError' + index).hide();
                $(this).removeClass('is-invalid');
            }

            // Validate minimum value
            if (qty <= 0) {
                $(this).addClass('is-invalid');
            }
        });

        // Clear form button
        $('#clearForm').click(function () {
            if (confirm('Apakah Anda yakin ingin membersihkan semua data form?')) {
                // Reset form
                $('#pengirimanForm')[0].reset();
                // Reset Select2
                $('.select2').val('').trigger('change');
                // Reset items to single row
                $('.item-row:gt(0)').remove();
                $('.remove-item').prop('disabled', true);
                // Refresh page to clear all data
                window.location.href = window.location.href.split('?')[0];
            }
        });

        // Add item row
        $('#addItem').click(function () {
            // Generate options HTML from global productsData
            let optionsHtml = '<option value="">Pilih Produk</option>';
            if (window.productsData && window.productsData.length > 0) {
                window.productsData.forEach(product => {
                    const currentStock = parseFloat(product.current_stock) || 0;
                    const availableStock = currentStock < 0 ? 0 : currentStock;
                    const stockDisplay = currentStock < 0 ? '0.00' : currentStock.toFixed(2);
                    const isDisabled = currentStock <= 0;
                    const unitCode = product.unit_code || '';

                    optionsHtml += `
                        <option value="${product.product_id}" 
                            data-stock-id="${product.stock_id}"
                            data-available-qty="${availableStock}"
                            ${isDisabled ? 'disabled style="color: #dc3545;"' : ''}>
                            ${product.product_code} - ${product.product_name}
                            (Stok: ${stockDisplay} ${unitCode})
                            ${isDisabled ? ' - Stok Habis/Tidak Tersedia' : ''}
                        </option>
                    `;
                });
            }

            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Produk *</label>
                        <select class="form-control select2 product-select" name="product_id[]" data-index="${itemCounter}" required>
                            ${optionsHtml}
                        </select>
                        <input type="hidden" name="stock_id[]" value="">
                        <small class="form-text text-info stock-info" id="stockInfo${itemCounter}"></small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Qty *</label>
                        <input type="number" class="form-control qty-input" name="qty[]" 
                            data-index="${itemCounter}" step="0.01" min="0.01" max="0" required>
                        <small class="form-text text-danger qty-error" id="qtyError${itemCounter}" style="display: none;">
                            Melebihi stok tersedia
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Keterangan Barang</label>
                        <input type="text" class="form-control" name="detail_note[]" placeholder="Keterangan tambahan untuk barang ini">
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
            $('#itemsContainer').append(newRow);

            // Reinitialize Select2 for new row
            $('#itemsContainer .item-row:last-child .select2').select2({
                width: '100%',
                dropdownParent: $('.card-body')
            });

            // Enable remove buttons if more than one row
            if ($('.item-row').length > 1) {
                $('.remove-item').prop('disabled', false);
            }

            itemCounter++;
        });

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
            if ($('.item-row').length === 1) {
                $('.remove-item').prop('disabled', true);
            }
        });

        // Form validation
        $('#pengirimanForm').submit(function (e) {
            let valid = true;
            let errorMessages = [];

            // Validasi untuk superadmin - harus memilih gudang asal
            <?php if ($user_role == 'superadmin'): ?>
                if (!$('#from_warehouse_id').val()) {
                    errorMessages.push('Harap pilih gudang asal');
                    $('#from_warehouse_id').focus();
                    valid = false;
                }
            <?php endif; ?>

            // Validasi untuk pengiriman ke pengguna
            <?php if ($to_status == '1'): ?>
                if (!$('#customer_id').val()) {
                    errorMessages.push('Harap pilih pengguna');
                    $('#customer_id').focus();
                    valid = false;
                }

                if (!$('#stockout_invoice').val()) {
                    errorMessages.push('Harap isi nomor referensi');
                    $('#stockout_invoice').focus();
                    valid = false;
                }
            <?php elseif ($to_status == '3'): ?>
                // Validasi untuk pengiriman antar gudang
                if (!$('#to_warehouse_id').val()) {
                    errorMessages.push('Harap pilih gudang tujuan');
                    $('#to_warehouse_id').focus();
                    valid = false;
                }

                // Validasi: jangan kirim ke gudang yang sama
                const fromWarehouseId = $('#from_warehouse_id').val();
                const toWarehouseId = $('#to_warehouse_id').val();
                if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                    errorMessages.push('Tidak bisa mengirim ke gudang yang sama');
                    valid = false;
                }

                if (!$('#stockout_invoice').val()) {
                    errorMessages.push('Harap isi nomor referensi');
                    $('#stockout_invoice').focus();
                    valid = false;
                }
            <?php endif; ?>

            // Check if at least one item has product selected
            let hasItems = false;
            $('select[name="product_id[]"]').each(function () {
                if ($(this).val()) {
                    hasItems = true;

                    // Check if product is disabled (stock <= 0)
                    const selectedOption = $(this).find('option:selected');
                    if (selectedOption.is(':disabled')) {
                        errorMessages.push('Tidak bisa memilih produk dengan stok habis');
                        valid = false;
                    }
                }
            });

            if (!hasItems) {
                errorMessages.push('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            // Check quantity values and stock availability
            let hasQuantityError = false;
            $('.qty-input').each(function (index) {
                const qty = parseFloat($(this).val()) || 0;
                const maxQty = parseFloat($(this).attr('max')) || 0;

                if (qty <= 0 || isNaN(qty)) {
                    errorMessages.push('Quantity harus lebih dari 0');
                    hasQuantityError = true;
                }

                if (qty > maxQty) {
                    errorMessages.push('Quantity melebihi stok tersedia');
                    hasQuantityError = true;
                }
            });

            if (hasQuantityError) {
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                if (errorMessages.length > 0) {
                    alert(errorMessages.join('\n'));
                }
            }
        });

        // Function to load products by warehouse (for superadmin only)
        function loadProductsByWarehouse(warehouseId) {
            $.ajax({
                url: '<?= site_url("pengiriman/load_products_by_warehouse") ?>',
                type: 'POST',
                data: {
                    warehouse_id: warehouseId,
                    <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
                },
                dataType: 'json',
                beforeSend: function () {
                    // Show loading
                    $('#itemsContainer').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Memuat data stok...</div>');
                },
                success: function (response) {
                    if (response.success && response.data) {
                        // Update GLOBAL productsData variable
                        window.productsData = response.data;

                        // Generate options HTML
                        let optionsHtml = '<option value="">Pilih Produk</option>';
                        response.data.forEach(product => {
                            const currentStock = parseFloat(product.current_stock) || 0;
                            const availableStock = currentStock < 0 ? 0 : currentStock;
                            const stockDisplay = currentStock < 0 ? '0.00' : currentStock.toFixed(2);
                            const isDisabled = currentStock <= 0;
                            const unitCode = product.unit_code || '';

                            optionsHtml += `
                                <option value="${product.product_id}" 
                                    data-stock-id="${product.stock_id}"
                                    data-available-qty="${availableStock}"
                                    ${isDisabled ? 'disabled style="color: #dc3545;"' : ''}>
                                    ${product.product_code} - ${product.product_name}
                                    (Stok: ${stockDisplay} ${unitCode})
                                    ${isDisabled ? ' - Stok Habis/Tidak Tersedia' : ''}
                                </option>
                            `;
                        });

                        // Replace items container with new row
                        $('#itemsContainer').html(`
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Produk *</label>
                                        <select class="form-control select2 product-select" name="product_id[]" data-index="0" required>
                                            ${optionsHtml}
                                        </select>
                                        <input type="hidden" name="stock_id[]" value="">
                                        <small class="form-text text-info stock-info" id="stockInfo0"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]" 
                                            data-index="0" step="0.01" min="0.01" max="0" required>
                                        <small class="form-text text-danger qty-error" id="qtyError0" style="display: none;">
                                            Melebihi stok tersedia
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            placeholder="Keterangan tambahan untuk barang ini">
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Reinitialize Select2
                        $('#itemsContainer .select2').select2({
                            width: '100%',
                            dropdownParent: $('.card-body')
                        });

                        // Reset item counter
                        itemCounter = 1;

                        // Initialize product selection for the first row
                        setTimeout(() => {
                            const firstSelect = $('#itemsContainer .product-select').first();
                            if (firstSelect.length) {
                                firstSelect.trigger('change');
                            }
                        }, 100);

                    } else {
                        alert(response.message || 'Gagal memuat data produk');
                        // Reset to empty products data
                        window.productsData = [];
                        // Reset items container
                        $('#itemsContainer').html(`
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Produk *</label>
                                        <select class="form-control select2 product-select" name="product_id[]" data-index="0" required>
                                            <option value="">Pilih Produk</option>
                                        </select>
                                        <input type="hidden" name="stock_id[]" value="">
                                        <small class="form-text text-info stock-info" id="stockInfo0"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]" 
                                            data-index="0" step="0.01" min="0.01" max="0" required>
                                        <small class="form-text text-danger qty-error" id="qtyError0" style="display: none;">
                                            Melebihi stok tersedia
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            placeholder="Keterangan tambahan untuk barang ini">
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error loading products:', error);
                    alert('Terjadi kesalahan saat memuat data produk');
                    // Reset to original state
                    location.reload();
                }
            });
        }

        // Initialize product selects on page load (for non-superadmin)
        <?php if ($user_role != 'superadmin'): ?>
            $('.product-select').each(function () {
                const index = $(this).data('index');
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.length > 0 && selectedOption.val()) {
                    const availableQty = parseFloat(selectedOption.data('available-qty')) || 0;

                    // Extract unit from option text
                    const optionText = selectedOption.text();
                    const unitMatch = optionText.match(/\(Stok: [\d.,]+ (.+?)\)/);
                    const unit = unitMatch ? unitMatch[1] : '';

                    $('#stockInfo' + index).text('Stok tersedia: ' + availableQty.toFixed(2) + ' ' + unit);

                    const qtyInput = $(this).closest('.item-row').find('.qty-input');
                    qtyInput.attr('max', availableQty);
                }
            });
        <?php endif; ?>
    });
</script>