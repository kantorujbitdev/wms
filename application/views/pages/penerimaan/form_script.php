<!-- Include Select2 CSS & JS untuk Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Store products data safely
        const productsData = <?= json_encode($products_js, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        // Function to escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
                '`': '&#96;'
            };
            return text.replace(/[&<>"'`]/g, function (m) { return map[m]; });
        }

        // Initialize Select2 for Bootstrap 5
        function initSelect2(element) {
            $(element).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true,
                dropdownParent: $(element).closest('.modal').length ? $(element).closest('.modal') : document.body
            });
        }

        // Initialize all Select2 elements
        $('.select2').each(function () {
            initSelect2(this);
        });

        // Get warehouse data from hidden input
        const warehouseData = JSON.parse($('#warehouse_data').val() || '{}');

        // Function to update stockin code based on warehouse selection
        function updateStockinCode() {
            let selectedWarehouseId = '';

            <?php if ($user_role == 'superadmin'): ?>
                // For superadmin, get from dropdown selection
                selectedWarehouseId = $('#to_warehouse_id').val();
            <?php else: ?>
                // For non-superadmin, use their warehouse
                selectedWarehouseId = '<?= $user_warehouse_id ?>';
            <?php endif; ?>

            // Get warehouse code from data
            let warehouseCode = 'WH'; // default
            if (selectedWarehouseId && warehouseData[selectedWarehouseId]) {
                warehouseCode = warehouseData[selectedWarehouseId];
            }

            // Generate new stockin code
            const romanMonth = getRomanMonth(new Date().getMonth() + 1);
            const currentYear = new Date().getFullYear();

            // Tentukan kode prefix
            let kode_prefix = 'RI/';
            <?php if ($from_status == '3'): ?>
                kode_prefix = 'TI/';
            <?php endif; ?>

            const newStockinCode = `${kode_prefix}${warehouseCode}/${romanMonth}/${currentYear}`;

            // Update the input field
            $('#stockin_code').val(newStockinCode);

            // Update the help text
            let warehouseName = 'Gudang';
            <?php if ($user_role == 'superadmin'): ?>
                if (selectedWarehouseId) {
                    const selectedOption = $('#to_warehouse_id option:selected').text();
                    warehouseName = selectedOption || 'Gudang';
                }
            <?php else: ?>
                warehouseName = '<?= addslashes($user_warehouse_name) ?>';
            <?php endif; ?>

            $('#stockin_code').next('div.form-text').html(
                `Kode otomatis berdasarkan Gudang Tujuan: <strong>${warehouseName}</strong>`
            );
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
            $('#to_warehouse_id').on('change', function () {
                updateStockinCode();
            });
        <?php endif; ?>

        // Update code on page load
        updateStockinCode();

        // Prevent future date selection
        $('#stockin_date').on('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Tidak bisa memilih tanggal yang akan datang');
                this.value = '<?= date('Y-m-d') ?>';
            }
        });

        // Prevent typing future dates
        $('#stockin_date').on('keydown', function (e) {
            e.preventDefault();
        });

        // Validasi gudang asal dan tujuan tidak boleh sama untuk antar gudang
        <?php if ($from_status == '3'): ?>
            $('#from_warehouse_id, #to_warehouse_id').on('change', function () {
                const fromWarehouseId = $('#from_warehouse_id').val();
                const toWarehouseId = $('#to_warehouse_id').val();

                    <?php if ($user_role == 'superadmin'): ?>
                    if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                        alert('Gudang asal dan tujuan tidak boleh sama!');
                        $(this).val('').trigger('change');
                    }
                    <?php else: ?>
                    // Untuk non-superadmin, to_warehouse_id adalah gudang mereka
                    if (fromWarehouseId && fromWarehouseId === '<?= $user_warehouse_id ?>') {
                        alert('Tidak bisa memilih gudang sendiri sebagai gudang asal!');
                        $('#from_warehouse_id').val('').trigger('change');
                    }
                    <?php endif; ?>
            });
        <?php endif; ?>

        // Clear form button
        $('#clearForm').click(function () {
            if (confirm('Apakah Anda yakin ingin membersihkan semua data form?')) {
                // Reset form
                $('#penerimaanForm')[0].reset();
                // Reset Select2
                $('.select2').val('').trigger('change');
                // Reset items to single row
                $('.item-row:gt(0)').remove();
                $('.remove-item').prop('disabled', true);
                // Reset stockin code
                updateStockinCode();
                // Refresh page to clear all data
                window.location.href = window.location.href.split('?')[0];
            }
        });

        // Function to generate product options HTML
        function generateProductOptions(selectedProductId = '') {
            let optionsHtml = '<option value="">Pilih Produk</option>';

            if (productsData && productsData.length > 0) {
                productsData.forEach(function (product) {
                    const isSelected = (selectedProductId && product.product_id == selectedProductId) ? 'selected' : '';
                    // Gunakan display_name yang sudah di-escape dari PHP
                    optionsHtml += `
                        <option value="${product.product_id}" ${isSelected} data-display="${escapeHtml(product.display_name)}">
                            ${product.display_name}
                        </option>
                    `;
                });
            }

            return optionsHtml;
        }

        // Add item row
        $('#addItem').click(function () {
            const optionsHtml = generateProductOptions();
            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Produk *</label>
                        <select class="form-control select2 product-select" name="product_id[]" required>
                            ${optionsHtml}
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">Qty *</label>
                        <input type="number" class="form-control qty-input" name="qty[]" step="0.01" min="0.01" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Barang</label>
                        <input type="text" class="form-control" name="detail_note[]" placeholder="Keterangan tambahan untuk barang ini">
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <div class="mb-3">
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
            initSelect2($('#itemsContainer .item-row:last-child .product-select'));

            // Enable remove buttons if more than one row
            if ($('.item-row').length > 1) {
                $('.remove-item').prop('disabled', false);
            }
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
            e.preventDefault();

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

            // Validasi berdasarkan tipe penerimaan
            <?php if ($from_status == '1'): ?>
                // Penerimaan dari Pengguna
                if (!$('#customer_id').val()) {
                    errorMessages.push('Harap pilih pengguna');
                    $('#customer_id').focus();
                    valid = false;
                }
            <?php elseif ($from_status == '2'): ?>
                // Penerimaan dari Supplier
                if (!$('#supplier_id').val()) {
                    errorMessages.push('Harap pilih supplier');
                    $('#supplier_id').focus();
                    valid = false;
                }
            <?php elseif ($from_status == '3'): ?>
                // Penerimaan Antar Gudang
                if (!$('#from_warehouse_id').val()) {
                    errorMessages.push('Harap pilih gudang asal');
                    $('#from_warehouse_id').focus();
                    valid = false;
                }

                // Validasi gudang asal dan tujuan tidak boleh sama
                const fromWarehouseId = $('#from_warehouse_id').val();
                const toWarehouseId = $('#to_warehouse_id').val();
                    <?php if ($user_role == 'superadmin'): ?>
                    if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                        errorMessages.push('Gudang asal dan tujuan tidak boleh sama');
                        valid = false;
                    }
                    <?php else: ?>
                    // Untuk non-superadmin
                    if (fromWarehouseId && fromWarehouseId === '<?= $user_warehouse_id ?>') {
                        errorMessages.push('Tidak bisa memilih gudang sendiri sebagai gudang asal');
                        valid = false;
                    }
                    <?php endif; ?>
            <?php endif; ?>

            // Validasi nomor invoice/referensi
            const invoiceValue = $('#stockin_invoice').val().trim();
            if (!invoiceValue) {
                errorMessages.push('Harap isi nomor invoice/referensi');
                $('#stockin_invoice').focus();
                valid = false;
            }

            // Check if at least one item has product selected
            let hasItems = false;
            let itemErrors = false;

            $('select[name="product_id[]"]').each(function (index) {
                const productId = $(this).val();
                if (productId) {
                    hasItems = true;

                    // Validasi quantity untuk item yang dipilih
                    const qtyInput = $(this).closest('.item-row').find('.qty-input');
                    const qty = parseFloat(qtyInput.val());

                    if (qty <= 0 || isNaN(qty)) {
                        errorMessages.push(`Quantity pada baris ${index + 1} harus lebih dari 0`);
                        itemErrors = true;
                        valid = false;
                    }
                } else {
                    // Item tanpa produk yang dipilih
                    errorMessages.push(`Produk pada baris ${index + 1} harus dipilih`);
                    itemErrors = true;
                    valid = false;
                }
            });

            if (!hasItems && !itemErrors) {
                errorMessages.push('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            if (!valid) {
                if (errorMessages.length > 0) {
                    alert('PERBAIKI ERROR BERIKUT:\n\n' + errorMessages.join('\n'));
                }
                return false;
            }

            // Jika semua valid, submit form
            this.submit();
        });

        // Handle Select2 change untuk produk yang sudah ada
        $(document).on('change', '.product-select', function () {
            const selectedOption = $(this).find('option:selected');
            const displayText = selectedOption.data('display') || selectedOption.text();

            // Update tampilan jika diperlukan
            $(this).data('display-text', displayText);
        });
    });
</script>