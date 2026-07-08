<script>
    $(document).ready(function () {

        // =========================================================
        // STATE
        // =========================================================
        let currentStockoutId = null;
        let isSubmitting = false;   // FLAG anti double-submit

        // =========================================================
        // HELPER: refresh CSRF token dari response header/cookie
        // CodeIgniter regenerate CSRF tiap request jika
        // csrf_regenerate = TRUE (default). Kita baca dari meta atau
        // dari response JSON yang kita sertakan di tiap reply.
        // =========================================================
        function getCsrfData() {
            return {
                [csrfData.name]: csrfData.hash
            };
        }

        function updateCsrfHash(newHash) {
            if (newHash) csrfData.hash = newHash;
        }

        // =========================================================
        // HELPER: Roman month
        // =========================================================
        function getRomanMonth(month) {
            return ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][month - 1] || 'I';
        }

        // =========================================================
        // HELPER: Generate stockin code
        // =========================================================
        function generateStockinCode(warehouseId) {
            const romanMonth = getRomanMonth(new Date().getMonth() + 1);
            const currentYear = new Date().getFullYear();
            let warehouseCode = 'WH';

            if (appData.userRole === 'superadmin') {
                const wh = appData.warehouses.find(w => w.warehouse_id == warehouseId);
                if (wh) warehouseCode = wh.warehouse_code;
            } else {
                warehouseCode = appData.userWarehouseCode;
            }

            return `TI/${warehouseCode}/${romanMonth}/${currentYear}`;
        }

        // =========================================================
        // HELPER: Notifikasi — pakai toastr, bukan modal
        // (modal #errorModal tidak ada di HTML)
        // =========================================================
        function notifSuccess(msg) { toastr.success(msg); }
        function notifWarning(msg) { toastr.warning(msg); }

        // =========================================================
        // HELPER: Validasi qty input
        // Hanya validasi saat blur/submit, BUKAN saat input event
        // agar user bisa menghapus angka dan mengetik ulang.
        // =========================================================
        function validateQtyInput($input) {
            const maxQty = parseFloat($input.data('max') || 0);
            const currentVal = $input.val();
            let currentQty = parseFloat(currentVal);

            // Nilai kosong → set ke maxQty sebagai default
            if (currentVal === '' || isNaN(currentQty)) {
                $input.val(maxQty);
                currentQty = maxQty;
            }

            if (currentQty < 0) {
                $input.val(0);
                currentQty = 0;
                setInputError($input, 'Qty tidak boleh negatif');
                return false;
            }

            if (currentQty > maxQty) {
                $input.val(maxQty);
                setInputError($input, `Qty tidak boleh melebihi ${maxQty}`);
                return false;
            }

            clearInputError($input);
            return true;
        }

        function setInputError($input, msg) {
            $input.addClass('is-invalid');
            let $fb = $input.siblings('.invalid-feedback');
            if ($fb.length === 0) {
                $fb = $('<div class="invalid-feedback"></div>').insertAfter($input);
            }
            $fb.text(msg).show();
        }

        function clearInputError($input) {
            $input.removeClass('is-invalid');
            $input.siblings('.invalid-feedback').hide();
        }

        // =========================================================
        // INISIALISASI SELECT2
        // Class berbeda (select2-pengiriman) agar tidak bentrok dengan
        // inisialisasi global di main.js yang mungkin sudah jalan.
        // =========================================================
        $('.select2-pengiriman').select2({
            width: '100%',
            placeholder: '-- Pilih Kode Pengiriman --',
            allowClear: true,
            minimumResultsForSearch: 0
        });

        // =========================================================
        // EVENT: Pilih kode pengiriman
        // =========================================================
        $('#stockout_id').on('change', function () {
            const stockoutId = $(this).val();

            if (!stockoutId) {
                $('#formDataSection').addClass('d-none');
                $('#itemsContainer').empty();
                currentStockoutId = null;
                $('#stockin_code').val(generateStockinCode(appData.userWarehouseId));
                return;
            }

            // Jika pilih pengiriman yang sama, tidak perlu reload
            // TAPI: jika setelah reset lalu pilih lagi, currentStockoutId
            // sudah di-null saat reset, jadi ini aman
            if (currentStockoutId === stockoutId) return;

            $('#loadingIndicator').removeClass('d-none');
            $('#formDataSection').addClass('d-none');
            $('#itemsContainer').empty();

            $.ajax({
                url: '<?= site_url("penerimaan/get_pengiriman_detail") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    stockout_id: stockoutId,
                    ...getCsrfData()
                },
                success: function (res) {
                    // Refresh CSRF token dari response (jika disertakan)
                    updateCsrfHash(res.csrf_hash);

                    $('#loadingIndicator').addClass('d-none');

                    if (res.success) {
                        const header = res.header || {};
                        const detail = res.detail || [];

                        currentStockoutId = stockoutId;
                        populateHeader(header);
                        updateStockinCode(header);
                        populateItems(detail);

                        $('#formDataSection').removeClass('d-none');
                        $('html, body').animate({ scrollTop: $('#formDataSection').offset().top - 100 }, 400);
                    } else {

                        $('#errorMessage').html(res.message || 'Gagal memuat data pengiriman');
                        $('#errorModal').modal('show');
                    }
                },
                error: function (xhr) {
                    $('#loadingIndicator').addClass('d-none');
                    $('#errorMessage').html('Terjadi kesalahan saat memuat data pengiriman');
                    $('#errorModal').modal('show');
                    console.error('AJAX Error:', xhr.responseText.substring(0, 200));
                }
            });
        });

        // =========================================================
        // Populate header
        // =========================================================
        function populateHeader(header) {
            $('#header_stockout_date').val(header.stockout_date || '');
            $('#header_warehouse_name').val(header.warehouse_name || '');
            $('#header_warehouse_id').val(header.warehouse_id || '');
            $('#header_to_name').val(header.to_name || '');
            $('#header_to_status').val(header.to_status || '');
            $('#header_to_id').val(header.to_id || '');
            $('#header_stockout_note').val(header.stockout_note || '');

            if (header.stockout_code) {
                $('#stockin_invoice').val(header.stockout_code);
            }
        }

        // =========================================================
        // Update stockin code
        // =========================================================
        function updateStockinCode(header) {
            const warehouseId = appData.userRole === 'superadmin'
                ? (header.to_id || appData.userWarehouseId)
                : appData.userWarehouseId;

            $('#stockin_code').val(generateStockinCode(warehouseId));
        }

        // =========================================================
        // Populate items
        // =========================================================
        function populateItems(items) {
            const $container = $('#itemsContainer');
            $container.empty();

            if (!items || items.length === 0) {
                $container.html('<div class="alert alert-info">Tidak ada barang dalam pengiriman ini</div>');
                return;
            }

            const templateHtml = $('#itemTemplate').html();
            if (!templateHtml) {
                console.error('Item template tidak ditemukan');
                return;
            }

            items.forEach(function (item, index) {
                const $row = $(templateHtml);

                const productLabel = (item.product_code || '') + ' - ' + (item.product_name || '');
                const qtySent = parseFloat(item.qty_on_transfer || item.qty || 0);

                $row.find('.product-display').val(productLabel);
                $row.find('.product-id').attr('name', `product_id[${index}]`).val(item.product_id || '');
                $row.find('.stock-id').attr('name', `stock_id[${index}]`).val(item.stock_id || '');
                $row.find('.detail-id').attr('name', `detail_id[${index}]`).val(item.detail_id || '');
                $row.find('.detail-note').attr('name', `detail_note[${index}]`).val(item.detail_note || '');
                $row.find('.qty-sent').val(qtySent);

                $row.find('.qty-received-input')
                    .attr('name', `qty[${index}]`)
                    .attr('max', qtySent)
                    .data('max', qtySent)
                    .val(qtySent);

                $container.append($row);
            });
        }

        // =========================================================
        // EVENT: Validasi qty saat blur (bukan input agar bisa hapus angka)
        // =========================================================
        $(document).on('blur', '.qty-received-input', function () {
            validateQtyInput($(this));
        });

        // =========================================================
        // RESET FORM
        // =========================================================
        $('#resetForm').on('click', function () {
            if (!confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) return;

            // Reset select2 + trigger change agar #formDataSection tersembunyi
            $('#stockout_id').val(null).trigger('change');
            currentStockoutId = null;

            // Reset field penerimaan
            $('#stockin_date').val('<?= date('Y-m-d') ?>');
            $('#stockin_invoice').val('');
            $('#stockin_note').val('');
            $('#stockin_code').val(generateStockinCode(appData.userWarehouseId));

            // Reset flag submit
            isSubmitting = false;
            resetSubmitButton();
        });

        // =========================================================
        // HELPER: Reset tombol submit ke kondisi awal
        // =========================================================
        function resetSubmitButton() {
            $('#btnSimpan')
                .prop('disabled', false)
                .html('<i class="fas fa-save"></i> Simpan Penerimaan');
        }

        // =========================================================
        // SUBMIT FORM — AJAX dengan perlindungan double-submit
        //
        // FIX:
        // 1. Flag isSubmitting: cegah submit kedua dari klik ganda
        // 2. Tombol TIDAK di-enable kembali saat sukses (redirect akan
        //    terjadi, enable kembali hanya saat error)
        // 3. CSRF token di-refresh tiap kali dari response
        // =========================================================
        $('#penerimaanForm').on('submit', function (e) {
            e.preventDefault();

            // Lapisan 1: cegah double submit
            if (isSubmitting) {
                notifWarning('Data sedang diproses, harap tunggu...');
                return false;
            }

            // ---- Validasi lokal ----
            let isValid = true;
            let hasItems = false;
            let totalQty = 0;

            $('.qty-received-input').each(function () {
                const $input = $(this);
                const qty = parseFloat($input.val() || 0);
                const maxQty = parseFloat($input.data('max') || 0);

                if (!validateQtyInput($input)) {
                    isValid = false;
                }

                if (qty > 0) {
                    hasItems = true;
                    totalQty += qty;
                }

                if (maxQty > 0 && qty === 0) {
                    setInputError($input, 'Qty tidak boleh 0 untuk barang yang dikirim');
                    isValid = false;
                }
            });

            if (!hasItems || totalQty === 0) {
                notifWarning('Total qty yang diterima harus lebih dari 0');
                isValid = false;
            }

            if (!$('#stockin_date').val()) {
                notifWarning('Harap pilih tanggal penerimaan');
                isValid = false;
            }

            if (!$('#stockin_code').val()) {
                notifWarning('Kode penerimaan tidak valid');
                isValid = false;
            }

            if (!isValid) return false;

            // ---- Set flag & ubah tampilan tombol ----
            isSubmitting = true;
            $('#btnSimpan')
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...');
            $('#resetForm').prop('disabled', true);

            // ---- Kirim via AJAX ----
            // Sertakan CSRF token saat ini di data
            const formData = $(this).serialize() + '&' + csrfData.name + '=' + csrfData.hash;

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    // Refresh CSRF dari response
                    updateCsrfHash(res.csrf_hash);

                    if (res.success) {
                        notifSuccess(res.message || 'Penerimaan berhasil disimpan');

                        // Tombol TIDAK di-enable kembali — redirect segera terjadi
                        // Ini mencegah klik kedua di jeda sebelum redirect
                        const fromStatus = $('input[name="from_status"]').val();
                        setTimeout(function () {
                            if (fromStatus === '1') {
                                window.location.href = '<?= site_url("penerimaan/dari_pengguna") ?>';
                            } else if (fromStatus === '2') {
                                window.location.href = '<?= site_url("penerimaan/dari_supplier") ?>';
                            } else {
                                window.location.href = '<?= site_url("penerimaan/antar_gudang") ?>';
                            }
                        }, 1200);
                    } else {
                        // Gagal → enable kembali agar user bisa coba lagi
                        isSubmitting = false;
                        resetSubmitButton();
                        $('#resetForm').prop('disabled', false);
                        $('#errorMessage').html(res.message || 'Gagal menyimpan penerimaan');
                        $('#errorModal').modal('show');
                    }
                },
                error: function (xhr) {
                    // Error → enable kembali
                    isSubmitting = false;
                    resetSubmitButton();
                    $('#resetForm').prop('disabled', false);

                    let msg = 'Terjadi kesalahan saat menyimpan data';
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (res.message) msg = res.message;
                    } catch (e) {
                        if (xhr.status === 403) {
                            msg = 'Sesi keamanan kedaluwarsa. Silakan refresh halaman dan coba lagi.';
                        } else if (xhr.responseText.indexOf('<!DOCTYPE') !== -1) {
                            msg = 'Terjadi kesalahan server. Silakan coba lagi.';
                        }
                    }

                    $('#errorMessage').html(msg);
                    $('#errorModal').modal('show');
                    console.error('AJAX Error:', xhr.status, xhr.responseText.substring(0, 300));
                }
            });
        });

        // =========================================================
        // Cegah Enter di input teks memicu submit tidak sengaja
        // =========================================================
        $('#penerimaanForm').on('keydown', 'input[type="text"], input[type="number"]', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        // =========================================================
        // Auto-load jika filter_stockout_id sudah ada dari URL/session
        // =========================================================
        const preSelectedId = $('#stockout_id').val();
        if (preSelectedId) {
            $('#stockout_id').trigger('change');
        }

    });
</script>