<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Variables
        let currentStockoutId = null;

        // When stockout_id changes
        $('#stockout_id').on('change', function () {
            const stockoutId = $(this).val();

            if (!stockoutId) {
                $('#formDataSection').addClass('d-none');
                $('#itemsContainer').empty();
                currentStockoutId = null;
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
                    console.log('API Response:', response); // Untuk debugging

                    $('#loadingIndicator').addClass('d-none');

                    if (response.success) {
                        const header = response.header || {};
                        const detail = response.detail || [];
                        currentStockoutId = stockoutId;

                        // Populate header information
                        populateHeader(header);

                        // Populate items
                        populateItems(detail);

                        // Show form section
                        $('#formDataSection').removeClass('d-none');

                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#formDataSection').offset().top - 100
                        }, 500);
                    } else {
                        showError(response.message || 'Gagal memuat data pengiriman');
                        $('#formDataSection').addClass('d-none');
                    }
                },
                error: function (xhr, status, error) {
                    $('#loadingIndicator').addClass('d-none');
                    console.error('AJAX Error:', xhr.responseText);
                    showError('Terjadi kesalahan saat memuat data: ' + error);
                }
            });
        });

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
                $('#stockin_invoice').val('TI-' + header.stockout_code);
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
                    $itemRow.find('.qty-sent').after('<small class="form-text text-muted d-block">Satuan: ' + item.unit_code + '</small>');
                    $itemRow.find('.qty-received-input').after('<small class="form-text text-muted d-block">Satuan: ' + item.unit_code + '</small>');
                }

                // Add current stock info if available
                if (item.current_stock !== undefined) {
                    $itemRow.find('.product-display').after(
                        '<small class="form-text text-muted d-block">Stok asal: ' + item.current_stock + ' ' + (item.unit_code || '') + '</small>'
                    );
                }

                // Set hidden inputs with proper names
                $itemRow.find('.product-id').attr('name', 'product_id[' + itemIndex + ']');
                $itemRow.find('.stock-id').attr('name', 'stock_id[' + itemIndex + ']');
                $itemRow.find('.detail-id').attr('name', 'detail_id[' + itemIndex + ']');
                $itemRow.find('input[name="detail_note[]"]').attr('name', 'detail_note[' + itemIndex + ']');

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

            $('.item-checkbox').off('change').on('change', function () {
                const $row = $(this).closest('.item-row');
                const $qtyInput = $row.find('.qty-received-input');

                if ($(this).is(':checked')) {
                    $qtyInput.prop('disabled', false);
                    const maxQty = parseFloat($qtyInput.attr('data-max') || 0);
                    $qtyInput.val(maxQty);
                } else {
                    $qtyInput.prop('disabled', true);
                    $qtyInput.val(0);
                }
            });
        }

        // Validate qty input
        function validateQtyInput($input) {
            const maxQty = parseFloat($input.attr('data-max') || 0);
            let currentQty = parseFloat($input.val() || 0);

            // Handle empty value
            if (isNaN(currentQty)) {
                currentQty = 0;
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
            const $feedback = $input.siblings('.invalid-feedback');
            if ($feedback.length) {
                $feedback.text(message).show();
            }
        }

        // Hide input error
        function hideInputError($input) {
            $input.removeClass('is-invalid');
            const $feedback = $input.siblings('.invalid-feedback');
            if ($feedback.length) {
                $feedback.hide();
            }
        }

        // Show error message
        function showError(message) {
            // Use Bootstrap alert
            const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            // Remove existing alerts
            $('.alert-danger').remove();

            // Add new alert
            $('#penerimaanForm').prepend(alertHtml);

            // Auto remove after 5 seconds
            setTimeout(function () {
                $('.alert-danger').alert('close');
            }, 5000);
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

                if ($('#to_warehouse_id').length) {
                    $('#to_warehouse_id').val('').trigger('change');
                }
            }
        });

        // Form submission validation
        $('#penerimaanForm').on('submit', function (e) {
            e.preventDefault();

            let isValid = true;
            let hasItems = false;
            let totalQty = 0;

            // Check if any items are selected
            $('.item-checkbox:checked').each(function () {
                const $row = $(this).closest('.item-row');
                const $qtyInput = $row.find('.qty-received-input');
                const qty = parseFloat($qtyInput.val() || 0);

                if (qty > 0) {
                    hasItems = true;
                    totalQty += qty;

                    if (!validateQtyInput($qtyInput)) {
                        isValid = false;
                    }
                }
            });

            if (!hasItems) {
                showError('Pilih minimal satu barang dengan qty > 0');
                isValid = false;
            }

            if (totalQty === 0) {
                showError('Total qty yang diterima harus lebih dari 0');
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            // Disable items that are not checked
            $('.item-checkbox:not(:checked)').each(function () {
                const $row = $(this).closest('.item-row');
                $row.find('input, select').prop('disabled', true);
            });

            // Show loading on submit button
            const $submitBtn = $(this).find('button[type="submit"]');
            const originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            // Submit form
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    $submitBtn.prop('disabled', false).html(originalText);

                    if (response.success) {
                        alert('Penerimaan berhasil disimpan');
                        window.location.href = '<?= site_url("penerimaan/antar_gudang") ?>';
                    } else {
                        showError('Gagal menyimpan penerimaan: ' + (response.message || 'Terjadi kesalahan'));
                    }
                },
                error: function (xhr, status, error) {
                    $submitBtn.prop('disabled', false).html(originalText);
                    showError('Terjadi kesalahan saat menyimpan data');
                    console.error('Error:', error, xhr.responseText);
                }
            });
        });
    });
</script>