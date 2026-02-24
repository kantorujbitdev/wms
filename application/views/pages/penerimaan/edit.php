<style>
    /* Loading Screen */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.3s ease;
    }

    .loader {
        text-align: center;
        max-width: 400px;
        padding: 30px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .spinner {
        width: 60px;
        height: 60px;
        margin: 0 auto 20px;
        border: 5px solid #36b9cc;
        border-top: 5px solid #4e73df;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader h4 {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .loader p {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .progress {
        height: 8px;
        margin-top: 20px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #4e73df, #36b9cc);
        animation: progress 2s ease-in-out infinite;
    }

    @keyframes progress {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; }
    }

    /* Fade out animation */
    .fade-out {
        opacity: 0;
        pointer-events: none;
    }
</style>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loader">
        <div class="spinner"></div>
        <h4>Memuat Data Penerimaan</h4>
        <p>Mohon tunggu sebentar...</p>
        <p class="small text-muted" id="loading-status">Mengambil data penerimaan</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar"></div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php
    $back_url = 'penerimaan/dari_pengguna';
    if ($from_status == '2')
        $back_url = 'penerimaan/dari_supplier';
    elseif ($from_status == '3')
        $back_url = 'penerimaan/antar_gudang';
    ?>
    
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_code">Kode Penerimaan *</label>
                            <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                value="<?= $penerimaan['header']['stockin_code'] ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
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
                    <?php if (!empty($penerimaan['detail'])): ?>
                        <?php foreach ($penerimaan['detail'] as $index => $detail): ?>
                            <div class="item-row row mb-3" data-index="<?= $index ?>">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Produk *</label>
                                        <select class="form-control product-select-ajax" name="product_id[]" data-index="<?= $index ?>"
                                            data-selected-id="<?= $detail['product_id'] ?>"
                                            data-selected-text="<?= ($detail['product_code'] ?? '') ?> - <?= ($detail['product_name'] ?? '') ?> (Satuan: <?= ($detail['unit_code'] ?? '') ?>)"
                                            style="width: 100%;" required>
                                            <?php if (!empty($detail['product_id'])): ?>
                                                <option value="<?= $detail['product_id'] ?>" selected>
                                                    <?= ($detail['product_code'] ?? '') ?> - <?= ($detail['product_name'] ?? '') ?> (Satuan:
                                                    <?= ($detail['unit_code'] ?? '') ?>)
                                                </option>
                                            <?php endif; ?>
                                        </select>
                                        <input type="hidden" name="stock_id[]" value="<?= $detail['product_id'] ?>">
                                        <small class="form-text text-info stock-info" id="stockInfo<?= $index ?>">
                                            Satuan: <?= $detail['unit_code'] ?? '' ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]" data-index="<?= $index ?>" min="0.01"
                                            step="0.01" value="<?= $detail['qty'] ?? 0 ?>" required>
                                        <small class="form-text text-danger qty-error" id="qtyError<?= $index ?>" style="display: none;">
                                            Quantity harus lebih dari 0
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]" value="<?= $detail['detail_note'] ?? '' ?>"
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
                    <?php endif; ?>
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
    // Tampilkan loading
    showLoading('Mengambil data penerimaan...');
    
    // Parse products data dari PHP JSON
    var productsData = <?= $products_json ?: '[]' ?>;
        updateLoadingStatus('Memproses data produk (' + productsData.length + ' produk)');

        let itemCounter = <?= count($penerimaan['detail']) ?>;

        /**
         * Function untuk menampilkan loading
         */
        function showLoading(message) {
            $('#loading-overlay').show();
            if (message) {
                $('#loading-status').text(message);
            }
        }

        /**
         * Function untuk mengupdate status loading
         */
        function updateLoadingStatus(message) {
            $('#loading-status').text(message);
        }

        /**
         * Function untuk menyembunyikan loading
         */
        function hideLoading() {
            $('#loading-overlay').addClass('fade-out');
            setTimeout(function () {
                $('#loading-overlay').hide();
                $('#loading-overlay').removeClass('fade-out');
                $('#main-content').fadeIn(500);
            }, 300);
        }

        /**
         * Inisialisasi Select2 dengan client-side filtering
         */
        function initSelect2Client(element) {
            updateLoadingStatus('Menginisialisasi form...');

            element.select2({
                width: '100%',
                dropdownParent: $('.card-body'),
                data: productsData,
                minimumInputLength: 2,
                placeholder: 'Cari produk (min. 2 karakter)',
                allowClear: false,
                matcher: function (params, data) {
                    if ($.trim(params.term) === '') {
                        return null;
                    }
                    var searchTerm = params.term.toLowerCase();
                    var text = data.text.toLowerCase();
                    if (text.indexOf(searchTerm) > -1) {
                        return data;
                    }
                    return null;
                },
                templateResult: function (product) {
                    if (product.loading) {
                        return product.text;
                    }
                    if (!product.id) {
                        return product.text;
                    }
                    var $container = $(
                        '<div class="clearfix">' +
                        '<div class="font-weight-bold">' + product.text + '</div>' +
                        '<small class="text-muted">Kode: ' + (product.product_code || '') + '</small>' +
                        '</div>'
                    );
                    return $container;
                },
                templateSelection: function (product) {
                    if (!product.id) {
                        return product.text;
                    }
                    return product.text || $(product.element).text();
                },
                language: {
                    searching: function () {
                        return 'Mencari...';
                    },
                    noResults: function () {
                        return 'Produk tidak ditemukan';
                    },
                    inputTooShort: function () {
                        return 'Masukkan minimal 2 karakter';
                    }
                }
            });

            // Set initial value jika ada
            var selectedId = element.data('selected-id');
            var selectedText = element.data('selected-text');

            if (selectedId && selectedText) {
                if (element.find('option[value="' + selectedId + '"]').length === 0) {
                    var option = new Option(selectedText, selectedId, true, true);
                    element.append(option);
                }
                element.val(selectedId).trigger('change');
            }
        }

        // Inisialisasi semua select produk yang sudah ada
        let initCount = 0;
        let totalSelects = $('.product-select-ajax').length;

        $('.product-select-ajax').each(function (index) {
            initSelect2Client($(this));
            initCount++;
            updateLoadingStatus('Memuat form barang (' + initCount + '/' + totalSelects + ')');
        });

        /**
         * Handle perubahan pilihan produk
         */
        $(document).on('change', '.product-select-ajax', function () {
            const index = $(this).data('index');
            const selectedData = $(this).select2('data')[0];

            if (selectedData && selectedData.id) {
                $(this).closest('.item-row').find('input[name="stock_id[]"]').val(selectedData.id);
                $('#stockInfo' + index).text('Satuan: ' + (selectedData.unit_code || ''));
                $(this).attr('data-selected-id', selectedData.id);
                $(this).attr('data-selected-text', selectedData.text);
            } else {
                $(this).closest('.item-row').find('input[name="stock_id[]"]').val('');
                $('#stockInfo' + index).text('');
            }
        });

        /**
         * Handle validasi input quantity
         */
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

        /**
         * Tambah item baru
         */
        $('#addItem').click(function () {
            const newIndex = itemCounter;

            const newRow = `
        <div class="item-row row mb-3" data-index="${newIndex}">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Produk *</label>
                    <select class="form-control product-select-ajax" 
                            name="product_id[]"
                            data-index="${newIndex}"
                            style="width: 100%;" required>
                        <option value="">Pilih Produk</option>
                    </select>
                    <input type="hidden" name="stock_id[]" value="">
                    <small class="form-text text-info stock-info" id="stockInfo${newIndex}"></small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Qty *</label>
                    <input type="number" class="form-control qty-input" name="qty[]" 
                        data-index="${newIndex}" step="0.01" min="0.01" required>
                    <small class="form-text text-danger qty-error" id="qtyError${newIndex}" style="display: none;">
                        Quantity harus lebih dari 0
                    </small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Keterangan Barang</label>
                    <input type="text" class="form-control" name="detail_note[]" 
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
        `;

            $('#itemsContainer').append(newRow);
            initSelect2Client($('#itemsContainer .item-row:last-child .product-select-ajax'));
            itemCounter++;
        });

        /**
         * Hapus item
         */
        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
        });

        /**
         * Validasi form sebelum submit
         */
        $('#penerimaanForm').submit(function (e) {
            let valid = true;
            let errorMessages = [];

        <?php if ($user_role == 'superadmin'): ?>
            if (!$('#to_warehouse_id').val()) {
                    errorMessages.push('Harap pilih gudang tujuan');
                    $('#to_warehouse_id').focus();
                    valid = false;
                }
        <?php endif; ?>

        <?php if ($from_status == '1'): ?>
            if (!$('#customer_id').val()) {
                    errorMessages.push('Harap pilih pengguna');
                    $('#customer_id').focus();
                    valid = false;
                }
        <?php elseif ($from_status == '2'): ?>
            if (!$('#supplier_id').val()) {
                    errorMessages.push('Harap pilih supplier');
                    $('#supplier_id').focus();
                    valid = false;
                }
        <?php elseif ($from_status == '3'): ?>
            if (!$('#from_warehouse_id').val()) {
                    errorMessages.push('Harap pilih gudang asal');
                    $('#from_warehouse_id').focus();
                    valid = false;
                }

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

            let hasQuantityError = false;
            $('.qty-input').each(function () {
                const qty = parseFloat($(this).val()) || 0;
                if (qty <= 0 || isNaN(qty)) {
                    hasQuantityError = true;
                }
            });

            if (hasQuantityError) {
                errorMessages.push('Quantity harus lebih dari 0');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                if (errorMessages.length > 0) {
                    alert(errorMessages.join('\n'));
                }
            } else {
                // Tampilkan loading saat submit
                showLoading('Menyimpan data...');
            }
        });

        // Sembunyikan loading setelah semua selesai
        $(window).on('load', function () {
            // Beri jeda sedikit untuk memastikan semua sudah render
            setTimeout(hideLoading, 500);
        });

        // Jika window sudah load tapi kita perlu menunggu Select2
        if (document.readyState === 'complete') {
            setTimeout(hideLoading, 500);
        }

    <?php if ($user_role == 'superadmin' && $from_status == '3'): ?>
                $('#to_warehouse_id').on('change', function () {
                    const toWarehouse = $(this).val();
                    $('#from_warehouse_id option').each(function () {
                        if ($(this).val() == toWarehouse) {
                            $(this).prop('disabled', true);
                            if ($(this).is(':selected')) {
                                $(this).prop('selected', false);
                            }
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    $('#from_warehouse_id').trigger('change.select2');
                });
    <?php endif; ?>

});
</script>