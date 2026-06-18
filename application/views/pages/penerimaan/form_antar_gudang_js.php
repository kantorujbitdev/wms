<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Variables
        let currentStockoutId = null;

        // Fungsi untuk konversi bulan ke Romawi
        function getRomanMonth(month) {
            const romanNumerals = {
                1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI',
                7: 'VII', 8: 'VIII', 9: 'IX', 10: 'X', 11: 'XI', 12: 'XII'
            };
            return romanNumerals[month] || 'I';
        }

        // Fungsi untuk generate stockin code berdasarkan warehouse tujuan
        function generateStockinCode(warehouseId) {
            const romanMonth = getRomanMonth(new Date().getMonth() + 1);
            const currentYear = new Date().getFullYear();
            let warehouseCode = 'WH';

            // Cari warehouse code berdasarkan ID
            if (appData.userRole === 'superadmin') {
                // Untuk superadmin, cari berdasarkan warehouse tujuan
                for (let i = 0; i < appData.warehouses.length; i++) {
                    if (appData.warehouses[i].warehouse_id == warehouseId) {
                        warehouseCode = appData.warehouses[i].warehouse_code;
                        break;
                    }
                }
            } else {
                // Untuk non-superadmin, gunakan warehouse user
                warehouseCode = appData.userWarehouseCode;
            }

            return `TI/${warehouseCode}/${romanMonth}/${currentYear}`;
        }

        // When stockout_id changes
        $('#stockout_id').on('change', function () {
            const stockoutId = $(this).val();

            if (!stockoutId) {
                $('#formDataSection').addClass('d-none');
                $('#itemsContainer').empty();
                currentStockoutId = null;

                // Reset stockin code ke default
                const defaultCode = generateStockinCode(appData.userWarehouseId);
                $('#stockin_code').val(defaultCode);
                return;
            }

            // If same stockout_id selected, do nothing
            if (currentStockoutId === stockoutId) {
                return;
            }

            // Show loading indicator
            $('#loadingIndicator').removeClass('d-none');
            $('#formDataSection').addClass('d-none');
            $('#itemsContainer').empty();

            // Get pengiriman detail
            $.ajax({
                url: '<?= site_url("penerimaan/get_pengiriman_detail") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    stockout_id: stockoutId,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function (response) {
                    $('#loadingIndicator').addClass('d-none');

                    if (response.success) {
                        const header = response.header || {};
                        const detail = response.detail || [];
                        currentStockoutId = stockoutId;

                        // Populate header information
                        populateHeader(header);

                        // Generate dan set stockin code berdasarkan gudang tujuan
                        updateStockinCode(header);

                        // Populate items
                        populateItems(detail);

                        // Show form section
                        $('#formDataSection').removeClass('d-none');

                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#formDataSection').offset().top - 100
                        }, 500);
                    } else {
                        showToastrError(response.message || 'Gagal memuat data pengiriman');
                        $('#formDataSection').addClass('d-none');
                    }
                },
                error: function (xhr, status, error) {
                    $('#loadingIndicator').addClass('d-none');
                    console.error('AJAX Error:', xhr.responseText);
                    showToastrError('Terjadi kesalahan saat memuat data: ' + error);
                }
            });
        });

        // Fungsi untuk update stockin code berdasarkan header
        function updateStockinCode(header) {
            if (!header) return;

            let warehouseId;

            // Tentukan warehouse ID berdasarkan user role
            if (appData.userRole === 'superadmin') {
                // Untuk superadmin, gunakan to_id dari header (gudang tujuan)
                warehouseId = header.to_id || appData.userWarehouseId;
            } else {
                // Untuk non-superadmin, gunakan warehouse user
                warehouseId = appData.userWarehouseId;
            }

            // Generate stockin code baru
            const newStockinCode = generateStockinCode(warehouseId);

            // Update input field
            $('#stockin_code').val(newStockinCode);
        }

        // Populate header information
        function populateHeader(header) {
            if (!header) return;

            $('#header_stockout_date').val(header.stockout_date || '');
            $('#header_warehouse_name').val(header.warehouse_name || '');
            $('#header_warehouse_id').val(header.warehouse_id || '');
            $('#header_to_name').val(header.to_name || '');
            $('#header_to_status').val(header.to_status || '');
            $('#header_to_id').val(header.to_id || '');
            $('#header_stockout_note').val(header.stockout_note || '');

            // Set default stockin_invoice from stockout_code
            if (header.stockout_code) {
                $('#stockin_invoice').val(header.stockout_code);
            }
        }

        // Populate items
        function populateItems(items) {
            const container = $('#itemsContainer');
            container.empty();

            if (!items || items.length === 0) {
                container.html('<div class="alert alert-info">Tidak ada barang dalam pengiriman ini</div>');
                return;
            }

            // Counter for naming
            let itemIndex = 0;

            items.forEach((item) => {
                const template = $('#itemTemplate').html();
                const $itemRow = $(template);

                // Populate item data
                const productName = (item.product_code || '') + ' - ' + (item.product_name || '');
                const qtySent = parseFloat(item.qty_on_transfer || item.qty || 0);

                $itemRow.find('.product-display').val(productName);
                $itemRow.find('.product-id').val(item.product_id || '');
                $itemRow.find('.stock-id').val(item.stock_id || '');
                $itemRow.find('.detail-id').val(item.detail_id || '');
                $itemRow.find('.detail-note').val(item.detail_note || '');
                $itemRow.find('.qty-sent').val(qtySent);

                // Set qty received input
                const $qtyInput = $itemRow.find('.qty-received-input');
                $qtyInput.attr({
                    'max': qtySent,
                    'data-max': qtySent,
                    'name': 'qty[' + itemIndex + ']'
                }).val(qtySent);

                // Set unit information if available
                if (item.unit_code) {
                    // Tampilkan satuan di sebelah qty
                    // $itemRow.find('.qty-sent').after('<small class="form-text text-muted">Satuan: ' + item.unit_code + '</small>');
                    // $itemRow.find('.qty-received-input').after('<small class="form-text text-muted">Satuan: ' + item.unit_code + '</small>');
                }

                // Add current stock info if available
                if (item.current_stock !== undefined) {
                    $itemRow.find('.product-display').after(
                        // '<small class="form-text text-muted">Stok asal: ' + item.current_stock + ' ' + (item.unit_code || '') + '</small>'
                    );
                }

                // Set hidden inputs with proper names
                $itemRow.find('.product-id').attr('name', 'product_id[' + itemIndex + ']');
                $itemRow.find('.stock-id').attr('name', 'stock_id[' + itemIndex + ']');
                $itemRow.find('.detail-id').attr('name', 'detail_id[' + itemIndex + ']');
                $itemRow.find('.detail-note').attr('name', 'detail_note[' + itemIndex + ']');

                // Tambahkan event listener untuk input qty
                $qtyInput.on('input', function () {
                    validateQtyInput($(this));
                });

                container.append($itemRow);
                itemIndex++;
            });

            // Initialize validation for qty inputs
            initializeQtyValidation();
        }

        // Initialize qty validation
        function initializeQtyValidation() {
            $('.qty-received-input').off('input').on('input', function () {
                validateQtyInput($(this));
            });
        }

        // Validate qty input
        function validateQtyInput($input) {
            const maxQty = parseFloat($input.attr('data-max') || 0);
            let currentQty = parseFloat($input.val() || 0);

            // Handle empty value
            if ($input.val() === '') {
                $input.val(maxQty); // Set default ke max qty jika kosong
                currentQty = maxQty;
            }

            if (isNaN(currentQty)) {
                $input.val(maxQty);
                showInputError($input, 'Qty harus berupa angka');
                return false;
            }

            if (currentQty < 0) {
                $input.val(0);
                showInputError($input, 'Qty tidak boleh negatif');
                return false;
            }

            if (currentQty > maxQty) {
                $input.val(maxQty);
                showInputError($input, 'Qty tidak boleh melebihi ' + maxQty);
                return false;
            }

            hideInputError($input);
            return true;
        }

        // Show input error
        function showInputError($input, message) {
            $input.addClass('is-invalid');
            let $feedback = $input.next('.invalid-feedback');

            if ($feedback.length === 0) {
                $feedback = $('<div class="invalid-feedback">' + message + '</div>');
                $input.after($feedback);
            } else {
                $feedback.text(message).show();
            }
        }

        // Hide input error
        function hideInputError($input) {
            $input.removeClass('is-invalid');
            const $feedback = $input.next('.invalid-feedback');
            if ($feedback.length) {
                $feedback.hide();
            }
        }

        // Show toastr success message
        function showToastrSuccess(message) {
            toastr.success(message, '<?= $wording['success'] ?>');
        }

        // Show toastr error message using modal
        function showToastrError(message) {
            $('#errorMessage').text(message);
            $('#errorModal').modal('show');
        }

        // Show toastr warning message
        function showToastrWarning(message) {
            toastr.warning(message, '<?= $wording['warning'] ?>');
        }

        // Show toastr info message
        function showToastrInfo(message) {
            toastr.info(message, '<?= $wording['info'] ?>');
        }

        // Reset form
        $('#resetForm').on('click', function () {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
                $('#stockout_id').val('').trigger('change');
                $('#formDataSection').addClass('d-none');
                $('#itemsContainer').empty();
                currentStockoutId = null;

                // Reset form inputs
                $('#stockin_date').val('<?= date('Y-m-d') ?>');
                $('#stockin_invoice').val('');
                $('#stockin_note').val('');

                // Reset stockin code ke default
                const defaultCode = generateStockinCode(appData.userWarehouseId);
                $('#stockin_code').val(defaultCode);

                if ($('#to_warehouse_id').length) {
                    $('#to_warehouse_id').val('').trigger('change');
                }
            }
        });

        // Form submission validation
        $('#penerimaanForm').on('submit', function (e) {
            e.preventDefault();

            // Validasi form
            let isValid = true;
            let hasItems = false;
            let totalQty = 0;

            // Check all items (tidak ada checkbox, semua item wajib diisi)
            $('.qty-received-input').each(function () {
                const $qtyInput = $(this);
                const qty = parseFloat($qtyInput.val() || 0);
                const maxQty = parseFloat($qtyInput.attr('data-max') || 0);

                // Validasi input qty
                if (!validateQtyInput($qtyInput)) {
                    isValid = false;
                }

                // Cek jika qty > 0
                if (qty > 0) {
                    hasItems = true;
                    totalQty += qty;
                }

                // Validasi: qty tidak boleh 0 jika ada pengiriman
                if (maxQty > 0 && qty === 0) {
                    showInputError($qtyInput, 'Qty tidak boleh 0 untuk barang yang dikirim');
                    isValid = false;
                }
            });

            if (!hasItems) {
                showToastrWarning('Semua qty yang diterima harus lebih dari 0');
                isValid = false;
            }

            if (totalQty === 0) {
                showToastrWarning('Total qty yang diterima harus lebih dari 0');
                isValid = false;
            }

            // Validasi tanggal
            const stockinDate = $('#stockin_date').val();
            if (!stockinDate) {
                showToastrWarning('Harap pilih tanggal penerimaan');
                isValid = false;
            }

            // Validasi stockin code
            const stockinCode = $('#stockin_code').val();
            if (!stockinCode) {
                showToastrWarning('Kode penerimaan tidak valid');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            // Show loading on submit button
            const $submitBtn = $(this).find('button[type="submit"]');
            const originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            // Submit form via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    $submitBtn.prop('disabled', false).html(originalText);

                    if (response.success) {
                        // Tampilkan pesan sukses
                        showToastrSuccess(response.message || 'Penerimaan berhasil disimpan');

                        // Delay redirect untuk menampilkan toastr
                        setTimeout(function () {
                            // Redirect berdasarkan from_status
                            const fromStatus = $('input[name="from_status"]').val();
                            if (fromStatus === '1') {
                                window.location.href = '<?= site_url("penerimaan/dari_pengguna") ?>';
                            } else if (fromStatus === '2') {
                                window.location.href = '<?= site_url("penerimaan/dari_supplier") ?>';
                            } else {
                                window.location.href = '<?= site_url("penerimaan/antar_gudang") ?>';
                            }
                        }, 1500); // Delay 1.5 detik untuk menampilkan toastr
                    } else {
                        showToastrError('Gagal menyimpan penerimaan: ' + (response.message || 'Terjadi kesalahan'));
                        console.error('Error response:', response);
                    }
                },
                error: function (xhr, status, error) {
                    $submitBtn.prop('disabled', false).html(originalText);

                    console.log('XHR Response:', xhr.responseText.substring(0, 100)); // Debug

                    // Coba parse response meski error
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            showToastrError('Terjadi kesalahan: ' + response.message);
                        } else {
                            showToastrError('Terjadi kesalahan saat menyimpan data (Error: ' + xhr.status + ')');
                        }
                    } catch (e) {
                        // Jika bukan JSON, cek apakah itu HTML error page
                        if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<html')) {
                            showToastrError('Terjadi kesalahan server. Kemungkinan ada masalah dengan CSRF token atau session.');
                        } else {
                            showToastrError('Terjadi kesalahan server. Silakan coba lagi atau hubungi administrator.');
                        }
                    }
                    console.error('AJAX Error:', error, xhr.responseText);
                }
            });
        });

        // Auto-validate all qty inputs when form is loaded
        $(document).on('change', '.qty-received-input', function () {
            validateQtyInput($(this));
        });

        // Set default value for empty qty inputs
        $(document).on('blur', '.qty-received-input', function () {
            if ($(this).val() === '') {
                const maxQty = parseFloat($(this).attr('data-max') || 0);
                $(this).val(maxQty);
                validateQtyInput($(this));
            }
        });
    });
</script>