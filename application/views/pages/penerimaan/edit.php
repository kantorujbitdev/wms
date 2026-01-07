<div class="container-fluid">
    <?php
    $back_url = 'penerimaan/dari_pengguna';
    if ($from_status == '2')
        $back_url = 'penerimaan/dari_supplier';
    elseif ($from_status == '3')
        $back_url = 'penerimaan/antar_gudang';
    ?>
    
    
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div> -->

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
           <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a> 
            <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm" action="<?= site_url('penerimaan/update/' . $penerimaan['header']['stockin_id']) ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_date">Tanggal Penerimaan *</label>
                            <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                value="<?= date('Y-m-d', strtotime($penerimaan['header']['stockin_date'])) ?>"
                                max="<?= date('Y-m-d') ?>" required>
                            <small class="form-text text-muted">Tidak bisa memilih tanggal yang akan datang</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_code">Kode Penerimaan *</label>
                            <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                value="<?= $penerimaan['header']['stockin_code'] ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Kode tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_warehouse_id">Ke Gudang *</label>
                            <?php if ($user_role == 'superadmin'): ?>
                                    <!-- Superadmin dapat mengubah gudang tujuan -->
                                    <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                        <option value="">Pilih Gudang Tujuan</option>
                                        <?php foreach ($warehouses as $warehouse): ?>
                                                <option value="<?= $warehouse['warehouse_id'] ?>"
                                                    <?= ($penerimaan['header']['warehouse_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                                    <?= $warehouse['warehouse_name'] ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Superadmin dapat mengubah gudang tujuan</small>
                            <?php else: ?>
                                    <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                    <input type="text" class="form-control bg-light"
                                        value="<?= $penerimaan['header']['warehouse_name'] ?>" readonly
                                        style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                                    <input type="hidden" id="to_warehouse_id" name="to_warehouse_id"
                                        value="<?= $penerimaan['header']['warehouse_id'] ?>">
                                    <small class="form-text text-muted">Gudang tujuan tidak dapat diubah</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_note">Keterangan</label>
                            <textarea class="form-control" id="stockin_note" name="stockin_note"
                                placeholder="Masukkan keterangan tambahan"
                                rows="2"><?= $penerimaan['header']['stockin_note'] ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Penerimaan dari Pengguna (from_status = 1) -->
                <?php if ($from_status == '1'): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Dari Pengguna *</label>
                                    <select class="form-control select2" id="customer_id" name="customer_id" required>
                                        <option value="">Pilih Pengguna</option>
                                        <?php foreach ($customers as $customer): ?>
                                                <option value="<?= $customer['id'] ?>"
                                                    <?= ($penerimaan['header']['from_id'] == $customer['id']) ? 'selected' : '' ?>>
                                                    <?= $customer['name'] ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stockin_invoice">No Referensi *</label>
                                    <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                        value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                        placeholder="Masukkan nomor referensi" required>
                                </div>
                            </div>
                        </div>

                    <!-- Form untuk Penerimaan dari Supplier (from_status = 2) -->
                <?php elseif ($from_status == '2'): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id">Dari Supplier *</label>
                                    <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                        <option value="">Pilih Supplier</option>
                                        <?php foreach ($suppliers as $supplier): ?>
                                                <option value="<?= $supplier['id'] ?>"
                                                    <?= ($penerimaan['header']['from_id'] == $supplier['id']) ? 'selected' : '' ?>>
                                                    <?= $supplier['name'] ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stockin_invoice">No Referensi *</label>
                                    <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                        value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                        placeholder="Masukkan nomor referensi" required>
                                </div>
                            </div>
                        </div>

                    <!-- Form untuk Penerimaan Antar Gudang (from_status = 3) -->
                <?php elseif ($from_status == '3'): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_warehouse_id">Dari Gudang *</label>
                                    <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id" required>
                                        <option value="">Pilih Gudang Asal</option>
                                        <?php foreach ($warehouses as $warehouse):
                                            // Jangan tampilkan gudang yang sama dengan tujuan
                                            $disabled = '';
                                            if ($warehouse['warehouse_id'] == $penerimaan['header']['warehouse_id']) {
                                                $disabled = 'disabled';
                                            }
                                            ?>
                                                <option value="<?= $warehouse['warehouse_id'] ?>"
                                                    <?= ($penerimaan['header']['from_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>
                                                    <?= $disabled ?>>
                                                    <?= $warehouse['warehouse_name'] ?>
                                                    <?= ($disabled) ? ' (Gudang Tujuan)' : '' ?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Pilih gudang asal penerimaan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stockin_invoice">No Referensi *</label>
                                    <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                        value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                        placeholder="Masukkan nomor referensi transfer" required>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_status">Tipe Penerimaan</label>
                            <?php
                            $tipe_text = 'Dari Pengguna';
                            if ($from_status == '2')
                                $tipe_text = 'Dari Supplier';
                            elseif ($from_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Tipe penerimaan tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Detail Barang</h5>
                        <small class="text-muted">Pilih barang yang diterima</small>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <?php foreach ($penerimaan['detail'] as $index => $detail):
                        $qty = floatval($detail['qty'] ?? 0);
                        $unit_code = $detail['unit_code'] ?? '';
                        ?>
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Produk *</label>
                                        <select class="form-control select2 product-select" name="product_id[]"
                                            data-index="<?= $index ?>" required>
                                            <option value="">Pilih Produk</option>
                                            <?php foreach ($products as $product): ?>
                                                    <option value="<?= $product['product_id'] ?>"
                                                        data-stock-id="<?= $product['product_id'] ?>"
                                                        <?= ($detail['product_id'] == $product['product_id']) ? 'selected' : '' ?>>
                                                        <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                        (Unit: <?= $product['unit_code'] ?>)
                                                    </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="stock_id[]" value="<?= $detail['product_id'] ?>">
                                        <small class="form-text text-info stock-info" id="stockInfo<?= $index ?>">
                                            Unit: <?= $unit_code ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]"
                                            data-index="<?= $index ?>" step="0.01" min="0.01" 
                                            value="<?= number_format($qty, 2) ?>" required>
                                        <small class="form-text text-danger qty-error" id="qtyError<?= $index ?>"
                                            style="display: none;">
                                            Quantity harus lebih dari 0
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            value="<?= $detail['detail_note'] ?>"
                                            placeholder="Keterangan tambahan untuk barang ini">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Penerimaan
                        </button>
                        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
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
        let itemCounter = <?= count($penerimaan['detail']) ?>;

        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('.card-body')
        });

        // For superadmin: Handle warehouse change for antar gudang
        <?php if ($user_role == 'superadmin' && $from_status == '3'): ?>
                $('#to_warehouse_id').on('change', function () {
                    const warehouseId = $(this).val();
                    if (warehouseId) {
                        // Disable same warehouse in from dropdown
                        $('#from_warehouse_id option').each(function () {
                            const optionValue = $(this).val();
                            if (optionValue == warehouseId) {
                                $(this).prop('disabled', true);
                                if ($(this).is(':selected')) {
                                    $(this).prop('selected', false);
                                    $('#from_warehouse_id').trigger('change.select2');
                                }
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });
                        $('#from_warehouse_id').trigger('change.select2');
                    }
                });
        <?php endif; ?>

        // Prevent future date selection
        $('#stockin_date').on('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Tidak bisa memilih tanggal yang akan datang');
                this.value = '<?= date('Y-m-d', strtotime($penerimaan['header']['stockin_date'])) ?>';
            }
        });

        // Prevent typing future dates
        $('#stockin_date').on('keydown', function (e) {
            e.preventDefault();
        });

        // Handle product selection
        $(document).on('change', '.product-select', function () {
            const index = $(this).data('index');
            const selectedOption = $(this).find('option:selected');
            const stockId = selectedOption.data('stock-id');

            // Update hidden stock_id input
            $(this).closest('.item-row').find('input[name="stock_id[]"]').val(stockId);

            // Extract unit from option text
            const optionText = selectedOption.text();
            const unitMatch = optionText.match(/\(Unit: (.+?)\)/);
            const unit = unitMatch ? unitMatch[1] : '';

            // Update unit info
            $('#stockInfo' + index).text('Unit: ' + unit);
        });

        // Handle qty input validation
        $(document).on('input', '.qty-input', function () {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;

            if (qty <= 0) {
                $('#qtyError' + index).show();
                $(this).addClass('is-invalid');
            } else {
                $('#qtyError' + index).hide();
                $(this).removeClass('is-invalid');
            }
        });

        // Add item row
        $('#addItem').click(function () {
            // Generate options HTML from global productsData
            let optionsHtml = '<option value="">Pilih Produk</option>';
            if (window.productsData && window.productsData.length > 0) {
                window.productsData.forEach(product => {
                    optionsHtml += `
                        <option value="${product.product_id}" 
                            data-stock-id="${product.product_id}">
                            ${product.product_code} - ${product.product_name}
                            (Unit: ${product.unit_code})
                        </option>
                    `;
                });
            }

            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select class="form-control select2 product-select" name="product_id[]" data-index="${itemCounter}" required>
                            ${optionsHtml}
                        </select>
                        <input type="hidden" name="stock_id[]" value="">
                        <small class="form-text text-info stock-info" id="stockInfo${itemCounter}"></small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Qty *</label>
                        <input type="number" class="form-control qty-input" name="qty[]" 
                            data-index="${itemCounter}" step="0.01" min="0.01" required>
                        <small class="form-text text-danger qty-error" id="qtyError${itemCounter}" style="display: none;">
                            Quantity harus lebih dari 0
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Keterangan Barang</label>
                        <input type="text" class="form-control" name="detail_note[]" placeholder="Keterangan tambahan untuk barang ini">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
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
        $('#penerimaanForm').submit(function (e) {
            let valid = true;
            let errorMessages = [];

            // Validasi untuk superadmin - harus memilih gudang tujuan
            <?php if ($user_role == 'superadmin'): ?>
                    if (!$('#to_warehouse_id').val()) {
                        errorMessages.push('Harap pilih gudang tujuan');
                        $('#to_warehouse_id').focus();
                        valid = false;
                    }
            <?php endif; ?>

            // Validasi untuk penerimaan dari pengguna
            <?php if ($from_status == '1'): ?>
                    if (!$('#customer_id').val()) {
                        errorMessages.push('Harap pilih pengguna');
                        $('#customer_id').focus();
                        valid = false;
                    }
            <?php elseif ($from_status == '2'): ?>
                    // Validasi untuk penerimaan dari supplier
                    if (!$('#supplier_id').val()) {
                        errorMessages.push('Harap pilih supplier');
                        $('#supplier_id').focus();
                        valid = false;
                    }
            <?php elseif ($from_status == '3'): ?>
                    // Validasi untuk penerimaan antar gudang
                    if (!$('#from_warehouse_id').val()) {
                        errorMessages.push('Harap pilih gudang asal');
                        $('#from_warehouse_id').focus();
                        valid = false;
                    }

                    // Validasi: jangan terima dari gudang yang sama
                    const fromWarehouseId = $('#from_warehouse_id').val();
                    const toWarehouseId = $('#to_warehouse_id').val();
                    if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                        errorMessages.push('Tidak bisa menerima dari gudang yang sama');
                        valid = false;
                    }
            <?php endif; ?>

            if (!$('#stockin_invoice').val()) {
                errorMessages.push('Harap isi nomor referensi');
                $('#stockin_invoice').focus();
                valid = false;
            }

            // Check if at least one item has product selected
            let hasItems = false;
            $('select[name="product_id[]"]').each(function () {
                if ($(this).val()) {
                    hasItems = true;
                }
            });

            if (!hasItems) {
                errorMessages.push('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            // Check quantity values
            let hasQuantityError = false;
            $('.qty-input').each(function (index) {
                const qty = parseFloat($(this).val()) || 0;

                if (qty <= 0 || isNaN(qty)) {
                    errorMessages.push('Quantity harus lebih dari 0');
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

        // Initialize existing product selections
        $('.product-select').each(function() {
            const index = $(this).data('index');
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.length > 0 && selectedOption.val()) {
                // Extract unit from option text
                const optionText = selectedOption.text();
                const unitMatch = optionText.match(/\(Unit: (.+?)\)/);
                const unit = unitMatch ? unitMatch[1] : '';
                
                // Update unit info display
                if ($('#stockInfo' + index).length) {
                    $('#stockInfo' + index).text('Unit: ' + unit);
                }
            }
        });
    });
</script>