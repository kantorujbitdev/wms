<script>
    $(document).ready(function () {
        // Store products data from PHP - use window object for global access
        window.productsData = <?= $products_json ?: '[]' ?>;
        let itemCounter = <?= isset($old_form_data['items']) ? count($old_form_data['items']) : 1 ?>;

        // Get warehouse data from hidden input
        const warehouseData = JSON.parse($('#warehouse_data').val() || '{}');

        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('.card-body')
        });

        // Function to update stockout code based on warehouse selection
        function updateStockoutCode() {
            let selectedWarehouseId = '';

            <?php if ($user_role == 'superadmin'): ?>
                // For superadmin, get from dropdown selection
                selectedWarehouseId = $('#from_warehouse_id').val();
            <?php else: ?>
                // For non-superadmin, use their warehouse
                selectedWarehouseId = '<?= $user_warehouse_id ?>';
            <?php endif; ?>

            // Get warehouse code from data
            let warehouseCode = 'WH'; // default
            if (selectedWarehouseId && warehouseData[selectedWarehouseId]) {
                warehouseCode = warehouseData[selectedWarehouseId];
            }

            // Generate new stockout code
            const romanMonth = getRomanMonth(new Date().getMonth() + 1);
            const currentYear = new Date().getFullYear();
            let kode_prefix = 'DO/';
            <?php if ($to_status == '3'): ?>
                kode_prefix = 'TO/';
            <?php endif; ?>
            const newStockoutCode = `${kode_prefix}${warehouseCode}/${romanMonth}/${currentYear}`;

            // Update the input field
            $('#stockout_code').val(newStockoutCode);

            // Update the help text
            let warehouseName = 'Gudang';
            <?php if ($user_role == 'superadmin'): ?>
                if (selectedWarehouseId) {
                    const selectedOption = $('#from_warehouse_id option:selected').text();
                    warehouseName = selectedOption || 'Gudang';
                }
            <?php else: ?>
                warehouseName = '<?= $user_warehouse_name ?>';
            <?php endif; ?>

            // $('#stockout_code').next('small').html(
            //     `Kode otomatis berdasarkan Gudang Asal: <strong>${warehouseName}</strong>`
            // );
        }

        // Function to convert month to Roman numeral
        function getRomanMonth(month) {
            const romanNumerals = {
                1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI',
                7: 'VII', 8: 'VIII', 9: 'IX', 10: 'X', 11: 'XI', 12: 'XII'
            };
            return romanNumerals[month] || 'I';
        }

        // Update code when warehouse selection changes (for superadmin)
        <?php if ($user_role == 'superadmin'): ?>
            $('#from_warehouse_id').on('change', function () {
                updateStockoutCode();
            });
        <?php endif; ?>

        // Update code on page load
        updateStockoutCode();

        // For superadmin: Load products when warehouse changes
        <?php if ($user_role == 'superadmin'): ?>
            $('#from_warehouse_id').on('change', function () {
                const warehouseId = $(this).val();
                if (warehouseId) {
                    loadProductsByWarehouse(warehouseId);
                    updateStockoutCode(); // Update kode juga

                    // Disable same warehouse in destination dropdown
                    <?php if ($to_status == '3'): ?>
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
                    <?php endif; ?>
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

        // Initialize product select with custom formatting
        function initProductSelect(selectElement) {
            // Custom template untuk menampilkan hasil dropdown
            function formatProduct(product) {
                if (!product.id) return product.text;

                const option = $(product.element);
                const productCode = option.data('product-code') || '';
                const unitCode = option.data('unit-code') || '-';
                const currentStock = parseInt(option.data('available-qty')) || 0;
                const stockQty = currentStock > 0 ? currentStock : '0';
                const textColor = currentStock <= 0 ? 'color: #dc3545 !important; font-weight: 500;' : 'font-weight: 500;';
                const stockTextColor = currentStock <= 0 ? 'color: #dc3545 !important;' : '';

                return $(`
                    <div style="${textColor}">
                        <div style="font-size: 14px; margin-bottom: 4px;">
                            <strong>${productCode}</strong> - ${product.text}
                        </div>
                        <div style="font-size: 12px; ${stockTextColor}">
                            <i class="fas fa-box me-1"></i><span style="font-weight: 500;">Satuan:</span> ${unitCode} | 
                            <i class="fas fa-cubes me-1"></i><span style="font-weight: 500;">Stok:</span> <strong>${stockQty}</strong>
                            ${currentStock <= 0 ? ' <i class="fas fa-exclamation-triangle text-danger"></i> <span style="font-weight: 600;">Stok Habis</span>' : ''}
                        </div>
                    </div>
                `);
            }

            // Template untuk selected option (display mode)
            function formatSelection(product) {
                if (!product.id) {
                    return product.text;
                }

                const option = $(product.element);
                const productCode = option.data('product-code') || '';
                const displayText = `${productCode} - ${product.text}`;

                return $(`<div style="font-weight: 500; font-size: 14px;">${displayText}</div>`);
            }

            selectElement.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Cari produk... (Kode / Nama)',
                allowClear: true,
                closeOnSelect: true,
                templateResult: formatProduct,
                templateSelection: formatSelection,
                dropdownParent: $('.card-body'),
                dropdownCssClass: 'product-dropdown',
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    if (typeof data.text === 'undefined') {
                        return null;
                    }

                    var term = params.term.toUpperCase(),
                        text = data.text.toUpperCase();

                    // Search in product code, name, or option attributes
                    if (text.indexOf(term) > -1) {
                        return data;
                    }

                    return null;
                }
            });
        }

        // Initialize Select2 with custom formatting for existing selects
        $('.product-select').each(function () {
            initProductSelect($(this));
        });

        // Handle product selection - Update stock and unit in table
        $(document).on('change', '.product-select', function () {
            const index = $(this).data('index');
            const selectedOption = $(this).find('option:selected');
            const stockId = selectedOption.data('stock-id');
            const availableQty = selectedOption.data('available-qty') || 0;
            const unitCode = selectedOption.data('unit-code') || '-';

            // Update hidden stock_id input
            $(this).closest('tr').find('input[name="stock_id[]"]').val(stockId);

            // Update stock display and unit in the same row
            const row = $(this).closest('tr');
            row.find('.stock-display').text(availableQty > 0 ? availableQty : '0');
            row.find('.unit-display').text(unitCode);

            // Update max qty
            const qtyInput = row.find('.qty-input');
            qtyInput.attr('max', availableQty);

            // Clear error if any
            $('#qtyError' + index).hide();
            qtyInput.removeClass('is-invalid');
        });

        // Handle qty input validation - Table format
        $(document).on('input', '.qty-input', function () {
            const index = $(this).data('index');
            const rawVal = $(this).val();
            const qty = parseInt(rawVal);
            const maxQty = parseInt($(this).attr('max')) || 0;

            const $error = $('#qtyError' + index);
            const $input = $(this);

            // Reset state
            $input.removeClass('is-invalid');
            $error.hide();

            // Jika input kosong → jangan validasi dulu
            if (rawVal === '') {
                return;
            }

            // Validasi angka
            if (isNaN(qty)) {
                $input.addClass('is-invalid');
                return;
            }

            // Validasi minimum
            if (qty <= 0) {
                $input.addClass('is-invalid');
                return;
            }

            // Validasi maksimum (stok)
            if (qty > maxQty) {
                $input.addClass('is-invalid');
                $error.show();
                return;
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
                // Reset stockout code
                updateStockoutCode();
                // Refresh page to clear all data
                window.location.href = window.location.href.split('?')[0];
            }
        });

        // Add item row - Table format
        $('#addItem').click(function () {
            // Generate options HTML from global productsData
            let optionsHtml = '<option value="">Pilih Produk</option>';
            if (window.productsData && window.productsData.length > 0) {
                window.productsData.forEach(product => {
                    const currentStock = parseInt(product.current_stock) || 0;
                    const availableStock = currentStock < 0 ? 0 : currentStock;
                    const stockDisplay = currentStock < 0 ? '0' : currentStock;
                    const isDisabled = currentStock <= 0;
                    const unitCode = product.unit_code || '';

                    optionsHtml += `
                        <option value="${product.product_id}" 
                            data-stock-id="${product.stock_id}"
                            data-available-qty="${availableStock}"
                            data-unit-code="${unitCode}"
                            ${isDisabled ? 'disabled style="color: #dc3545;"' : ''}>
                            ${product.product_code} - ${product.product_name}
                            (Stok: ${stockDisplay} ${unitCode})
                            ${isDisabled ? ' - Stok Habis' : ''}
                        </option>
                    `;
                });
            }

            const newRow = `
            <tr class="item-row" data-index="${itemCounter}">
                <td style="text-align:center; font-weight: 600;" class="row-number">${$('.item-row').length + 1}</td>
                <td>
                    <select class="form-control cell-input product-select" name="product_id[]" data-index="${itemCounter}" required>
                        ${optionsHtml}
                    </select>
                    <input type="hidden" name="stock_id[]" value="">
                </td>
                <td>
                    <input type="number" class="form-control cell-input qty-input" name="qty[]" 
                        data-index="${itemCounter}" step="1" min="1" max="0" required style="text-align: right;">
                    <small class="stock-info text-danger qty-error" id="qtyError${itemCounter}" style="display: none;">
                        Melebihi stok
                    </small>
                </td>
                <td style="text-align:center; font-weight: 600;" class="stock-display">-</td>
                <td style="text-align:center; font-weight: 600;" class="unit-display">-</td>
                <td>
                    <input type="text" class="form-control cell-input" name="detail_note[]" placeholder="Keterangan">
                </td>
                <td style="text-align:center">
                    <button type="button" class="btn btn-action btn-action-delete remove-item" title="Hapus Baris">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#itemsContainer').append(newRow);

            // Reinitialize Select2 for new row with custom formatting
            initProductSelect($('#itemsContainer .product-select:last'));

            // Enable remove buttons if more than one row
            if ($('.item-row').length > 1) {
                $('.remove-item').prop('disabled', false);
            }

            // Update row numbers
            updateRowNumbers();

            itemCounter++;
        });

        // Remove item row - Table format
        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
            }
            if ($('.item-row').length === 1) {
                $('.remove-item').prop('disabled', true);
            }
        });

        // Update row numbers
        function updateRowNumbers() {
            $('.item-row').each(function (index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

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

                // if (!$('#stockout_invoice').val()) {
                //     errorMessages.push('Harap isi nomor referensi');
                //     $('#stockout_invoice').focus();
                //     valid = false;
                // }
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

                // if (!$('#stockout_invoice').val()) {
                //     errorMessages.push('Harap isi nomor referensi');
                //     $('#stockout_invoice').focus();
                //     valid = false;
                // }
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
                const qty = parseInt($(this).val()) || 0;
                const maxQty = parseInt($(this).attr('max')) || 0;

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

            // Validasi kode pengiriman
            if (!$('#stockout_code').val()) {
                errorMessages.push('Kode pengiriman tidak valid');
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
                    $('#itemsContainer').html('<tr><td colspan="7" class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Memuat data stok...</td></tr>');
                },
                success: function (response) {
                    if (response.success && response.data) {
                        // Update GLOBAL productsData variable
                        window.productsData = response.data;

                        // Generate options HTML
                        let optionsHtml = '<option value="">Pilih Produk</option>';
                        response.data.forEach(product => {
                            const currentStock = parseInt(product.current_stock) || 0;
                            const availableStock = currentStock < 0 ? 0 : currentStock;
                            const stockDisplay = currentStock < 0 ? '0' : currentStock;
                            const isDisabled = currentStock <= 0;
                            const unitCode = product.unit_code || '';

                            optionsHtml += `
                                <option value="${product.product_id}" 
                                    data-stock-id="${product.stock_id}"
                                    data-available-qty="${availableStock}"
                                    data-unit-code="${unitCode}"
                                    ${isDisabled ? 'disabled style="color: #dc3545;"' : ''}>
                                    ${product.product_code} - ${product.product_name}
                                    (Stok: ${stockDisplay} ${unitCode})
                                    ${isDisabled ? ' - Stok Habis' : ''}
                                </option>
                            `;
                        });

                        // Replace items container with new row - Table format
                        $('#itemsContainer').html(`
                            <tr class="item-row" data-index="0">
                                <td style="text-align:center; font-weight: 600;" class="row-number">1</td>
                                <td>
                                    <select class="form-control cell-input product-select" name="product_id[]" data-index="0" required>
                                        ${optionsHtml}
                                    </select>
                                    <input type="hidden" name="stock_id[]" value="">
                                </td>
                                <td>
                                    <input type="number" class="form-control cell-input qty-input" name="qty[]" 
                                        data-index="0" step="1" min="1" max="0" required style="text-align: right;">
                                    <small class="stock-info text-danger qty-error" id="qtyError0" style="display: none;">
                                        Melebihi stok
                                    </small>
                                </td>
                                <td style="text-align:center; font-weight: 600;" class="stock-display">-</td>
                                <td style="text-align:center; font-weight: 600;" class="unit-display">-</td>
                                <td>
                                    <input type="text" class="form-control cell-input" name="detail_note[]" placeholder="Keterangan">
                                </td>
                                <td style="text-align:center">
                                    <button type="button" class="btn btn-action btn-action-delete remove-item" title="Hapus Baris" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `);

                        // Reinitialize Select2 with custom formatting
                        initProductSelect($('#itemsContainer .product-select'));

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
                        // Reset items container - Table format
                        $('#itemsContainer').html(`
                            <tr class="item-row" data-index="0">
                                <td style="text-align:center; font-weight: 600;" class="row-number">1</td>
                                <td>
                                    <select class="form-control cell-input product-select" name="product_id[]" data-index="0" required>
                                        <option value="">Pilih Produk</option>
                                    </select>
                                    <input type="hidden" name="stock_id[]" value="">
                                </td>
                                <td>
                                    <input type="number" class="form-control cell-input qty-input" name="qty[]" 
                                        data-index="0" step="1" min="1" max="0" required style="text-align: right;">
                                    <small class="stock-info text-danger qty-error" id="qtyError0" style="display: none;">
                                        Melebihi stok
                                    </small>
                                </td>
                                <td style="text-align:center; font-weight: 600;" class="stock-display">-</td>
                                <td style="text-align:center; font-weight: 600;" class="unit-display">-</td>
                                <td>
                                    <input type="text" class="form-control cell-input" name="detail_note[]" placeholder="Keterangan">
                                </td>
                                <td style="text-align:center">
                                    <button type="button" class="btn btn-action btn-action-delete remove-item" title="Hapus Baris" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
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
                    const availableQty = selectedOption.data('available-qty') || 0;
                    const unitCode = selectedOption.data('unit-code') || '-';

                    // Update stock display and unit in the same row (table format)
                    const row = $(this).closest('tr');
                    row.find('.stock-display').text(availableQty > 0 ? availableQty : '0');
                    row.find('.unit-display').text(unitCode);

                    const qtyInput = $(this).closest('tr').find('.qty-input');
                    qtyInput.attr('max', availableQty);
                }
            });
        <?php endif; ?>
    });
</script>